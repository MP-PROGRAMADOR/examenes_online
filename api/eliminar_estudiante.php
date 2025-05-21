<?php
// eliminar_usuario.php
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

    if ($id > 0) {
        include_once('../includes/conexion.php');


        try {


            $stmt = $pdo->prepare("DELETE FROM estudiantes WHERE id = ?");
            $stmt->execute([$id]);

            echo json_encode(['status' => true, 'message' => 'Estudiante eliminado correctamente.']);
        } catch (PDOException $e) {
            echo json_encode(['status' => false, 'message' => 'Error al eliminar el registro.']);
        }
    } else {
        echo json_encode(['status' => false, 'message' => 'ID inválido.']);
    }
} else {
    echo json_encode(['status' => false, 'message' => 'Método no permitido.']);
}
