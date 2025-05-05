<?php
require '../config/conexion.php';

header('Content-Type: application/json');

if (!isset($_GET['categoria_id'])) {
    echo json_encode(['error' => 'ID no recibido']);
    exit;
}

$categoria_id = intval($_GET['categoria_id']);

try {
    $conn = $pdo->getConexion();
    $stmt = $conn->prepare("SELECT COUNT(*) FROM examenes WHERE categoria_carne_id = ?");
    $stmt->execute([$categoria_id]);
    $existe = $stmt->fetchColumn() > 0;

    echo json_encode(['existe' => $existe]);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Error en la consulta']);
}
