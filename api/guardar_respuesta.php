<?php
require_once '../includes/conexion.php';



// Validar datos
if (!isset($_POST['examen_id'], $_POST['pregunta_id'], $_POST['opciones'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos incompletos']);
    exit;
}

$examen_id = $_POST['examen_id'];
$examen_pregunta_id = $_POST['examen_pregunta_id'];
$opciones = $_POST['opciones']; // array

try {
    $pdo->beginTransaction();

    // Obtener el id de examen_pregunta
    /* $stmt = $pdo->prepare("SELECT id FROM examen_preguntas WHERE examen_id = ? AND pregunta_id = ?");
    $stmt->execute([$examen_id, $pregunta_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) throw new Exception('Pregunta no asignada al examen');

    $examen_pregunta_id = $row['id'];
 */


    $pdo->commit();
    echo json_encode([
        'success' => true,
        ' data' => [
            'examen' => $examen_id,
            'pregunta' => $examen_pregunta_id,
            'opciones' => $opciones

        ]
    ]);
} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
