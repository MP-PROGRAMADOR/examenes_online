<?php
require_once '../config/conexion.php';
$pdo = $pdo->getConexion();

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$intento_examen_id = $data['intento_examen_id'] ?? null;
$pregunta_indice = $data['pregunta_indice'] ?? null;
$seleccion = $data['seleccion'] ?? [];

if (!$intento_examen_id || $pregunta_indice === null || empty($seleccion)) {
    echo json_encode(['error' => 'Datos incompletos']);
    exit;
}

// Obtener examen_id asociado al intento
$stmt = $pdo->prepare("SELECT examen_id FROM intentos_examen WHERE id = ?");
$stmt->execute([$intento_examen_id]);
$examen = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$examen) {
    echo json_encode(['error' => 'Intento no válido']);
    exit;
}

// Obtener preguntas del examen
$stmt = $pdo->prepare("SELECT id FROM preguntas WHERE examen_id = ? ORDER BY id ASC");
$stmt->execute([$examen['examen_id']]);
$preguntas = $stmt->fetchAll(PDO::FETCH_COLUMN);

if (!isset($preguntas[$pregunta_indice])) {
    echo json_encode(['error' => 'Pregunta no encontrada']);
    exit;
}

$pregunta_id = $preguntas[$pregunta_indice];

// Guardar respuestas
foreach ($seleccion as $opcion_id) {
    $stmt = $pdo->prepare("SELECT es_correcta FROM opciones_pregunta WHERE id = ? AND pregunta_id = ?");
    $stmt->execute([$opcion_id, $pregunta_id]);
    $opcion = $stmt->fetch(PDO::FETCH_ASSOC);

    $es_correcta = $opcion ? $opcion['es_correcta'] : 0;

    $stmt = $pdo->prepare("INSERT INTO respuestas_estudiante (intento_examen_id, pregunta_id, opcion_seleccionada_id, es_correcta) VALUES (?, ?, ?, ?)");
    $stmt->execute([$intento_examen_id, $pregunta_id, $opcion_id, $es_correcta]);
}

echo json_encode(['success' => true]);
?>
