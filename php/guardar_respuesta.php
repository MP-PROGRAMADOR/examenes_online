<?php
require_once '../config/conexion.php';
$pdo = $pdo->getConexion();

header('Content-Type: application/json');

// Validar y obtener datos del POST
$intento_examen_id = $_POST['intento_examen_id'] ?? null;
$pregunta_id = $_POST['pregunta_id'] ?? null;
$opcion_seleccionada_id = $_POST['opcion_seleccionada_id'] ?? null;
$respuesta_texto = $_POST['respuesta_texto'] ?? null;

if (!$intento_examen_id || !$pregunta_id) {
    echo json_encode(['error' => true, 'message' => 'Faltan datos obligatorios.']);
    exit;
}

// Determinar si la respuesta es correcta
$es_correcta = null;

if ($opcion_seleccionada_id) {
    // Verificar si la opción seleccionada es correcta
    $sql = "SELECT es_correcta FROM opciones_pregunta WHERE id = :id LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $opcion_seleccionada_id]);
    $opcion = $stmt->fetch();

    if ($opcion) {
        $es_correcta = (int)$opcion['es_correcta'];
    }
}

// Insertar la respuesta del estudiante
$sql = "INSERT INTO respuestas_estudiante (intento_examen_id, pregunta_id, opcion_seleccionada_id, respuesta_texto, es_correcta)
        VALUES (:intento_examen_id, :pregunta_id, :opcion_seleccionada_id, :respuesta_texto, :es_correcta)";
$stmt = $pdo->prepare($sql);
$success = $stmt->execute([
    'intento_examen_id' => $intento_examen_id,
    'pregunta_id' => $pregunta_id,
    'opcion_seleccionada_id' => $opcion_seleccionada_id,
    'respuesta_texto' => $respuesta_texto,
    'es_correcta' => $es_correcta
]);

if ($success) {
    echo json_encode(['success' => true, 'message' => 'Respuesta guardada correctamente.']);
} else {
    echo json_encode(['error' => true, 'message' => 'Error al guardar la respuesta.']);
}
