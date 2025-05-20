<?php
require_once '../includes/conexion.php';

$response = ['status' => false];

try {
   

    $estudiante_id = $_POST['estudiante_id'] ?? null;
    $categoria_id = $_POST['categoria_id'] ?? null;

    if (!$estudiante_id || !$categoria_id) {
        throw new Exception('Parámetros incompletos');
    }

    // Verificar si ya tiene esa categoría asignada
    $stmt = $pdo->prepare("SELECT id FROM estudiante_categorias WHERE estudiante_id = ? AND categoria_id = ?");
    $stmt->execute([$estudiante_id, $categoria_id]);

    if ($stmt->rowCount() > 0) {
        throw new Exception('Esta categoría ya está asignada al estudiante');
    }

    // Insertar nueva asignación
    $stmt = $pdo->prepare("INSERT INTO estudiante_categorias (estudiante_id, categoria_id) VALUES (?, ?)");
    $stmt->execute([$estudiante_id, $categoria_id]);

    $response['status'] = true;
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
