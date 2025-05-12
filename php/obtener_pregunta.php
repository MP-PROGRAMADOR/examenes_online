<?php
// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está logueado
if (!isset($_SESSION['estudiante'])) {
    // Redirigir si no está logueado
    header('Location: ../aspirantes/index.php');
    exit;
}

// Incluir archivo de conexión
require_once '../config/conexion.php';
$pdo = $pdo->getConexion(); // Asegúrate de que esta función retorne una instancia válida de PDO

// Validar conexión a la base de datos
if (!$pdo) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión a la base de datos.']);
    exit;
}

$estudiante = $_SESSION['estudiante'];
$estudiante_id = $estudiante['id'];

// Consultar examen más reciente para el estudiante
$sql = "SELECT id, acceso_habilitado, intentos_examen, total_preguntas 
        FROM examenes_estudiantes 
        WHERE estudiante_id = ? 
        ORDER BY id DESC 
        LIMIT 1";

$stmt = $pdo->prepare($sql);
$stmt->execute([$estudiante_id]);
$examen = $stmt->fetch(PDO::FETCH_ASSOC);

// Validar si el examen existe
if (!$examen) {
    header('Location: ../aspirantes/aspirante.php');
    exit;
}

// Validar si tiene acceso habilitado
if ((int) $examen['acceso_habilitado'] !== 1) {
    header('Location: ../aspirantes/aspirante.php');
    exit;
}

// Establecer cabecera JSON
header('Content-Type: application/json');

// Inicializar pregunta actual (se podría calcular desde la BD si se desea)
$cant_respondidas = 0;

try {
    // Obtener ID de examen desde GET
    $id_examen = filter_input(INPUT_GET, 'examen_id', FILTER_VALIDATE_INT);

    // Validar examen_id
    if (!$id_examen) {
        http_response_code(400);
        echo json_encode(['error' => 'ID de examen inválido o ausente.']);
        exit;
    }

    // Total de preguntas permitidas para este examen
    $total_permitido = (int) $examen['total_preguntas'];
    $examen_id = (int) $examen['id']; // ID de la tabla examenes_estudiantes


    // Consultar una pregunta aleatoria no respondida aún por este estudiante
    $sql = "SELECT p.*
            FROM preguntas p
            WHERE p.examen_id = :id_examen
            AND p.id NOT IN (
                SELECT pregunta_id 
                FROM respuestas_estudiante 
                WHERE examenes_estudiantes_id = :examen_id
            )
            ORDER BY RAND()
            LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id_examen' => $id_examen,
        ':examen_id' => $examen_id
    ]);

    $pregunta = $stmt->fetch(PDO::FETCH_ASSOC);

    // Validar si se encontró una pregunta disponible
    if (!$pregunta) {
        echo json_encode(['error' => 'No hay más preguntas disponibles.']);
        exit;
    }

    // Obtener opciones de respuesta para la pregunta
    $sql = "SELECT id, texto_opcion 
            FROM opciones_pregunta 
            WHERE pregunta_id = :pregunta_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':pregunta_id' => $pregunta['id']]);
    $opciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Obtener imagen asociada a la pregunta, si existe
    $sql = "SELECT ruta_imagen 
            FROM imagenes_pregunta 
            WHERE pregunta_id = :pregunta_id 
            LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':pregunta_id' => $pregunta['id']]);
    $imagen = $stmt->fetchColumn() ?: null;

    // Enviar los datos al frontend en formato JSON
    echo json_encode([
        'pregunta_id' => $pregunta['id'],
        'texto_pregunta' => $pregunta['texto_pregunta'],
        'tipo_pregunta' => $pregunta['tipo_pregunta'],
        'tipo_contenido' => $pregunta['tipo_contenido'],
        'opciones' => $opciones,
        'ruta_imagen' => $imagen,
        'pregunta_actual' => $cant_respondidas + 1,
        'total_preguntas' => $total_permitido
    ]);


} catch (PDOException $e) {
    // Error con la base de datos
    echo json_encode(['error' => 'Error en base de datos: ' . $e->getMessage()]);
    http_response_code(500);
} catch (Exception $e) {
    // Otro tipo de error
    echo json_encode(['error' => $e->getMessage()]);
    http_response_code(400);
}



