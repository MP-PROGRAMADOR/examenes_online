<?php
require_once '../includes/conexion.php';
header('Content-Type: application/json');

$estudiante_id = isset($_GET['estudiante_id']) ? (int) $_GET['estudiante_id'] : 0;

if ($estudiante_id <= 0) {
    echo json_encode(['status' => false, 'categorias' => [], 'message' => 'ID no válido']);
    exit;
}

$sql = "SELECT ec.id, ec.estado, ec.fecha_asignacion, c.nombre AS categoria
        FROM estudiante_categorias ec
        JOIN categorias c ON ec.categoria_id = c.id
        WHERE ec.estudiante_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$estudiante_id]);
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['status' => true, 'data' => $categorias]);
