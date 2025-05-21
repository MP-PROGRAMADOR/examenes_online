<?php
require_once '../includes/conexion.php';
header('Content-Type: application/json');

$estudiante_id = isset($_GET['estudiante_id']) ? (int) $_GET['estudiante_id'] : 0;

if ($estudiante_id <= 0) {
    echo json_encode(['status' => false, 'categorias' => [], 'message' => 'ID no válido']);
    exit;
}

$sql = "SELECT COUNT(*) AS total FROM pregunta_categoria WHERE categoria_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$estudiante_id]);
$total_categoria = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['status' => true, 'data' => $total_categoria]);



 