<?php
session_start();
require_once '../config/conexion.php';
$pdo = $pdo->getConexion();

header('Content-Type: application/json');

// Variables de entrada
$examen_id = $_GET['examen_id'] ?? 0; 
$estudiante_id = $_GET['estudiante_id'] ?? 0;

// Verificar si el estudiante ya tiene un intento para este examen
$sql = "SELECT *   FROM examenes_estudiantes WHERE estudiante_id = :estudiante_id ";
$stmt = $pdo->prepare($sql);
$stmt->execute(['estudiante_id' => $estudiante_id]);
$examen_estudiante = $stmt->fetch();

if ($examen_estudiante) {
    if ($examen_estudiante['estado'] == 'pendiente' || $examen_estudiante['estado'] == 'aprobado' || $examen_estudiante['intentos_examen'] == '1') {
        // Si el examen está en curso o ya ha sido aprobado, permitimos continuar

        // Obtener el total de preguntas permitidas desde examenes_estudiantes
        $total_preguntas = $examen_estudiante['total_preguntas'];

        // Obtener preguntas en base al id_examen
        $sql = "SELECT 
                    p.id AS pregunta_id, 
                    p.texto_pregunta, 
                    p.tipo_contenido, 
                    p.tipo_pregunta, 
                    pi.ruta_imagen
                    FROM preguntas p
                    LEFT JOIN imagenes_pregunta pi ON p.id = pi.pregunta_id
                    WHERE p.examen_id = ?
                    ORDER BY RAND() LIMIT 1";
            $params = [$examen_id];
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $pregunta = $stmt->fetch();
    
             // Obtener opciones de la pregunta
        $sql = "SELECT * FROM opciones_pregunta WHERE pregunta_id = :pregunta_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['pregunta_id' => $pregunta['pregunta_id']]);
        $opciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Devolver respuesta
        $response = [
            'pregunta' => [
                'id' => $pregunta['pregunta_id'],
                'texto_pregunta' => $pregunta['texto_pregunta'],
                'tipo_pregunta' => $pregunta['tipo_pregunta'],
                'tipo_contenido' => $pregunta['tipo_contenido'],
                'ruta_imagen' => $pregunta['ruta_imagen'],
                'opciones' => $opciones
            ],
            'total_pregunta' => $total_preguntas
        ];

        echo json_encode($response); 
    }}

?>