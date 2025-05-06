<?php
session_start();
require_once '../config/conexion.php';
$conn = $pdo->getConexion();

try {
    if (!isset($_POST['pregunta_id']) || !is_numeric($_POST['pregunta_id'])) {
        throw new Exception("ID de pregunta no válido.");
    }

    $pregunta_id = intval($_POST['pregunta_id']);

    // Verificar si la pregunta ha sido utilizada en respuestas de usuarios
    $stmt = $conn->prepare("SELECT COUNT(*) FROM respuestas_estudiante WHERE pregunta_id = ?");
    $stmt->execute([$pregunta_id]);
    $usada = $stmt->fetchColumn();

    if ($usada > 0) {
        throw new Exception("No se puede eliminar la pregunta porque ya ha sido respondida por al menos un usuario.");
    }

    // Obtener el examen_id
    $stmt = $conn->prepare("SELECT examen_id FROM preguntas WHERE id = ?");
    $stmt->execute([$pregunta_id]);
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$resultado) {
        throw new Exception("Pregunta no encontrada.");
    }

    $examen_id = $resultado['examen_id'];

    // Iniciar transacción
    $conn->beginTransaction();

    // Eliminar imágenes asociadas
    $stmt = $conn->prepare("SELECT ruta_imagen FROM imagenes_pregunta WHERE pregunta_id = ?");
    $stmt->execute([$pregunta_id]);
    $imagenes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($imagenes as $imagen) {
        $ruta = $imagen['ruta_imagen'];
        if (file_exists($ruta)) {
            unlink($ruta);
        }
    }

    // Eliminar datos relacionados
    $conn->prepare("DELETE FROM imagenes_pregunta WHERE pregunta_id = ?")->execute([$pregunta_id]);
    $conn->prepare("DELETE FROM opciones_pregunta WHERE pregunta_id = ?")->execute([$pregunta_id]);
    $conn->prepare("DELETE FROM preguntas WHERE id = ?")->execute([$pregunta_id]);

    // Actualizar el total de preguntas del examen
    $stmtActualizar = $conn->prepare("
        UPDATE examenes 
        SET total_preguntas = (
            SELECT COUNT(*) FROM preguntas WHERE examen_id = :examen_id
        ) 
        WHERE id = :examen_id
    ");
    $stmtActualizar->bindValue(':examen_id', $examen_id, PDO::PARAM_INT);
    $stmtActualizar->bindValue(':examen_id', $examen_id, PDO::PARAM_INT);
    $stmtActualizar->execute();

    // Confirmar cambios
    $conn->commit();

    $_SESSION['alerta'] = ['tipo' => 'success', 'mensaje' => 'Pregunta eliminada correctamente.'];
    header("Location: ../admin/preguntas.php");

} catch (Exception $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => $e->getMessage()];
    header("Location: ../admin/preguntas.php");
}
?>
