<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
require_once '../includes/conexion.php';

header('Content-Type: application/json');

$id = $_POST['id'] ?? null;
$estado = $_POST['estado'] ?? null;

if (!$id || !in_array($estado, ['INICIO', 'pendiente'])) {
  echo json_encode(['status' => false, 'message' => 'Datos inválidos']);
  exit;
}

try {
  $stmt = $pdo->prepare("UPDATE examenes SET estado = ? WHERE id = ?");
  $stmt->execute([$estado, $id]);

  echo json_encode(['status' => true, 'message' => $estado]);
} catch (PDOException $e) {
  error_log("Error al actualizar estado: " . $e->getMessage());
  echo json_encode(['status' => false, 'message' => 'Error en la base de datos']);
}