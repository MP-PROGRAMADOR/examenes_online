<?php
session_start();
require_once '../config/conexion.php'; // Ajusta la ruta según tu estructura

// Establecer respuesta JSON
header('Content-Type: application/json');

// Manejo global de errores
try {
    // Obtener datos desde el cuerpo de la solicitud JSON
    $datos = json_decode(file_get_contents('php://input'), true);

    // Validar sesión y datos requeridos
    $estudiante_id = $_SESSION['estudiante']['id'] ?? null;
    $pregunta_id = $datos['pregunta_id'] ?? null;
    $opciones = $datos['opciones'] ?? [];

    if (!$estudiante_id || !$pregunta_id || !is_array($opciones) || empty($opciones)) {
        throw new Exception('Datos incompletos o inválidos.');
    }

    // Obtener conexión PDO
    $pdo = $pdo->getConexion();

    // Buscar intento activo del estudiante (último intento válido)
    $sql = "SELECT * FROM examenes_estudiantes 
                            WHERE estudiante_id = ? 
                            AND (estado = 'pendiente' OR estado = 'reprobado') 
                            AND acceso_habilitado = 1 
                            ORDER BY id DESC 
                            LIMIT 1
                            ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':estudiante_id' => $estudiante_id]);
    $intento = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$intento) {
        throw new Exception('No hay intento activo para el estudiante.');
    }

    $examen_estudiante_id = (int) $intento['id'];

    // Obtener opciones correctas para la pregunta
    $sql = "SELECT id FROM opciones_pregunta 
            WHERE pregunta_id = :pregunta_id 
            AND es_correcta = 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':pregunta_id' => $pregunta_id]);
    $correctas = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Comparar opciones seleccionadas con las correctas (ambas ordenadas)
    sort($correctas);
    sort($opciones);
    $es_correcta = ($correctas == $opciones) ? 1 : 0;

    // Insertar cada respuesta seleccionada por el estudiante
    $sql = "INSERT INTO respuestas_estudiante 
            (examenes_estudiantes_id, pregunta_id, opcion_seleccionada_id, es_correcta) 
            VALUES (:examen_id, :pregunta_id, :opcion_id, :es_correcta)";

    $stmt = $pdo->prepare($sql);

    foreach ($opciones as $opcion_id) {
        $stmt->execute([
            ':examen_id' => $examen_estudiante_id,
            ':pregunta_id' => $pregunta_id,
            ':opcion_id' => $opcion_id,
            ':es_correcta' => $es_correcta
        ]);
    }

    // Éxito
    echo json_encode(['ok' => true]);

} catch (PDOException $e) {
    // Error en base de datos
    echo json_encode(['ok' => false, 'error' => 'Error en la base de datos: ' . $e->getMessage()]);
    http_response_code(500);

} catch (Exception $e) {
    // Error general
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
    http_response_code(400);
}
