<?php
require_once '../includes/conexion.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $escuela_id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

    try {
        // Validar ID
        if ($escuela_id <= 0) {
            throw new Exception("ID de escuela inválido.");
        }

        // Verificar que exista la escuela
        $stmt = $pdo->prepare("SELECT id FROM escuelas_conduccion WHERE id = ?");
        $stmt->execute([$escuela_id]);

        if ($stmt->rowCount() === 0) {
            throw new Exception("La escuela no existe o ya fue eliminada.");
        }

        // Eliminar escuela
        $stmt = $pdo->prepare("DELETE FROM escuelas_conduccion WHERE id = ?");
        $stmt->execute([$escuela_id]);

        echo json_encode(['status' => true, 'message' => 'Escuela eliminada correctamente']);
    } catch (Exception $e) {
        echo json_encode(['status' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => false, 'message' => 'Método no permitido']);
}
