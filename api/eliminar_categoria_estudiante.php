<?php
require_once '../includes/conexion.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $asignacion_id = $_POST['asignacion_id'] ?? null;

    if (!$asignacion_id || !is_numeric($asignacion_id)) {
        echo json_encode(['status' => false, 'message' => 'ID no válido']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM estudiante_categorias WHERE id = ?");
        $stmt->execute([$asignacion_id]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['status' => true, 'message' => 'Categoría eliminada con éxito']);
        } else {
            echo json_encode(['status' => false, 'message' => 'No se encontró la asignación']);
        }
    } catch (PDOException $e) {
        error_log("Error al eliminar asignación: " . $e->getMessage());
        echo json_encode(['status' => false, 'message' => 'Error en el servidor']);
    }
} else {
    echo json_encode(['status' => false, 'message' => 'Método no permitido']);
}
