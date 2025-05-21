<?php
require '../includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $id = intval($_GET['id'] ?? 0);

  $stmt = $pdo->prepare("
    SELECT pc.id AS rel_id, c.id, c.nombre
    FROM pregunta_categoria pc
    INNER JOIN categorias c ON pc.categoria_id = c.id
    WHERE pc.pregunta_id = ?
  ");
  $stmt->execute([$id]);
  $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

  echo json_encode(['status' => true, 'data' => $categorias]);
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $accion = $_POST['accion'] ?? '';

  if ($accion === 'asignar') {
    $pregunta_id = intval($_POST['pregunta_id'] ?? 0);
    $categoria_id = intval($_POST['categoria_id'] ?? 0);

    // Validar que la pregunta esté activa
    $stmt = $pdo->prepare("SELECT activa FROM preguntas WHERE id = ?");
    $stmt->execute([$pregunta_id]);
    $estado = $stmt->fetchColumn();

    if ($estado != 1) {
      echo json_encode(['status' => false, 'message' => 'No se puede asignar categoría a una pregunta inactiva.']);
      exit;
    }

    // Validar duplicados
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM pregunta_categoria WHERE pregunta_id = ? AND categoria_id = ?");
    $stmt->execute([$pregunta_id, $categoria_id]);
    if ($stmt->fetchColumn() > 0) {
      echo json_encode(['status' => false, 'message' => 'La categoría ya está asignada.']);
      exit;
    }

    $stmt = $pdo->prepare("INSERT INTO pregunta_categoria (pregunta_id, categoria_id) VALUES (?, ?)");
    $stmt->execute([$pregunta_id, $categoria_id]);
    echo json_encode(['status' => true, 'message' => 'Categoría asignada correctamente.']);
    exit;
  }

  if ($accion === 'eliminar') {
    $rel_id = intval($_POST['rel_id'] ?? 0);

    // Obtener el ID de la pregunta asociada y validar si está activa
    $stmt = $pdo->prepare("
      SELECT p.activa
      FROM pregunta_categoria pc
      INNER JOIN preguntas p ON pc.pregunta_id = p.id
      WHERE pc.id = ?
    ");
    $stmt->execute([$rel_id]);
    $estado = $stmt->fetchColumn();

    if ($estado != 1) {
      echo json_encode(['status' => false, 'message' => 'No se puede eliminar categoría de una pregunta inactiva.']);
      exit;
    }

    $stmt = $pdo->prepare("DELETE FROM pregunta_categoria WHERE id = ?");
    $stmt->execute([$rel_id]);
    echo json_encode(['status' => true, 'message' => 'Categoría eliminada.']);
    exit;
  }

  echo json_encode(['status' => false, 'message' => 'Acción no válida']);
}
