<?php
require_once '../includes/conexion.php';

$term = $_POST['search'] ?? '';
$stmt = $pdo->prepare("SELECT id, nombre FROM estudiantes WHERE nombre LIKE ? AND estado = 'activo'");
$stmt->execute(["%$term%"]);

$datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($datos);