<?php
require '../includes/conexion.php';
$response = ['success' => false];

if (!empty($_POST['id'])) {
  $stmt = $pdo->prepare("DELETE FROM examenes WHERE id = ?");
  if ($stmt->execute([$_POST['id']])) {
    $response['success'] = true;
  } else {
    $response['message'] = "No se pudo eliminar el examen.";
  }
} else {
  $response['message'] = "ID no proporcionado.";
}

echo json_encode($response);
