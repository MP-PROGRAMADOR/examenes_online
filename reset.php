<?php
require_once '../conexion.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$examen_id = $_POST['examen_id'] ?? null;

if (!$examen_id || !is_numeric($examen_id)) {
    echo json_encode(['success' => false, 'message' => 'ID de examen inválido']);
    exit;
}

try {
    $pdo->beginTransaction();

    // 1. Borrar respuestas del estudiante
    $stmt1 = $pdo->prepare("
        DELETE re FROM respuestas_estudiante re
        INNER JOIN examen_preguntas ep ON re.examen_pregunta_id = ep.id
        WHERE ep.examen_id = ?
    ");
    $stmt1->execute([$examen_id]);

    // 2. Marcar todas las preguntas como no respondidas
    $stmt2 = $pdo->prepare("UPDATE examen_preguntas SET respondida = 0 WHERE examen_id = ?");
    $stmt2->execute([$examen_id]);

    // 3. Resetear estado del examen y calificación
    $stmt3 = $pdo->prepare("UPDATE examenes SET estado = 'pendiente', calificacion = 0 WHERE id = ?");
    $stmt3->execute([$examen_id]);

    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'Examen reiniciado correctamente.']);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Error al reiniciar el examen: ' . $e->getMessage()]);
}
?>
