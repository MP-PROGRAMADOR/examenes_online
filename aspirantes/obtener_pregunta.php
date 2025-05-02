<?php
require_once '../config/conexion.php';
$pdo = $pdo->getConexion();

if (!isset($_GET['examen_id']) || !is_numeric($_GET['examen_id'])) {
    die("ID de examen inválido.");
}

$examen_id = intval($_GET['examen_id']);

// Obtener pregunta específica
$pregunta_id = isset($_GET['pregunta_id']) ? intval($_GET['pregunta_id']) : 0;

// Obtener pregunta
$stmt = $pdo->prepare("SELECT * FROM preguntas WHERE examen_id = ? AND id > ? ORDER BY id ASC LIMIT 1");
$stmt->execute([$examen_id, $pregunta_id]);
$pregunta = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pregunta) {
    echo json_encode(['finalizado' => true]);
    exit;
}

// Obtener opciones de la pregunta
$stmt_opciones = $pdo->prepare("SELECT * FROM opciones_pregunta WHERE pregunta_id = ?");
$stmt_opciones->execute([$pregunta['id']]);
$opciones = $stmt_opciones->fetchAll(PDO::FETCH_ASSOC);

// Obtener imágenes de la pregunta
$stmt_img = $pdo->prepare("SELECT ruta_imagen FROM imagenes_pregunta WHERE pregunta_id = ?");
$stmt_img->execute([$pregunta['id']]);
$imagenes = $stmt_img->fetchAll(PDO::FETCH_ASSOC);

// Responder con pregunta, opciones e imágenes
echo json_encode([
    'pregunta' => $pregunta,
    'opciones' => $opciones,
    'imagenes' => $imagenes
]);
?>
