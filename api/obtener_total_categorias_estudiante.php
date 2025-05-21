<?php
require_once '../includes/conexion.php';
header('Content-Type: application/json');

$categoria_id = isset($_GET['categoria_id']) ? (int) $_GET['categoria_id'] : 0;

if ($categoria_id <= 0) {
    echo json_encode(['status' => false, 'categorias' => [], 'message' => 'ID no válido']);
    exit;
}

$sql = "SELECT COUNT(*) AS total FROM pregunta_categoria WHERE categoria_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$categoria_id]);
$total_categoria = $stmt->fetch(PDO::FETCH_ASSOC);
$total = (int) $total_categoria['total'];

echo json_encode(['status' => true, 'data' => $total]);



 