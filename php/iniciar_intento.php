<?php
session_start();
$codigo = $_SESSION['estudiante']['codigo'];
header('Content-Type: application/json');
require_once '../config/conexion.php'; // Asegúrate de tener la clase de conexión a la base de datos

// Obtener datos del frontend (json)
$input = json_decode(file_get_contents('php://input'), true);

// Validar los parámetros recibidos
if (!isset($input['examen_id']) || !isset($input['estudiante_id'])) {
    echo json_encode(['error' => 'Faltan parámetros obligatorios']);
    exit;
}

$examen_id = $input['examen_id'];
$estudiante_id = $input['estudiante_id'];

// Verificar si el examen ya fue asignado al estudiante
/* $sql_check = "SELECT * FROM examenes_estudiantes WHERE examen_id = :examen_id AND estudiante_id = :estudiante_id";
$stmt_check = $pdo->prepare($sql_check);
$stmt_check->bindParam(':examen_id', $examen_id, PDO::PARAM_INT);
$stmt_check->bindParam(':estudiante_id', $estudiante_id, PDO::PARAM_INT);
$stmt_check->execute();
 */
/* if ($stmt_check->rowCount() == 0) {
    // Si el examen no fue asignado, asignarlo
    $sql_asignacion = "INSERT INTO examenes_estudiantes (examen_id, estudiante_id, fecha_asignacion) 
                       VALUES (:examen_id, :estudiante_id, CURDATE())";
    $stmt_asignacion = $pdo->prepare($sql_asignacion);
    $stmt_asignacion->bindParam(':examen_id', $examen_id, PDO::PARAM_INT);
    $stmt_asignacion->bindParam(':estudiante_id', $estudiante_id, PDO::PARAM_INT);
    $stmt_asignacion->execute();
}
 */
// Registrar el intento de examen
$sql = "INSERT INTO intentos_examen (examen_estudiante_id, estudiante_id, examen_id, codigo_acceso_utilizado) 
        VALUES (LAST_INSERT_ID(), :estudiante_id, :examen_id, :codigo_acceso_utilizado)";
$stmt = $pdo->prepare($sql);
$codigo_acceso_utilizado = $codigo;  // Puedes generar un código único si lo deseas
$stmt->bindParam(':estudiante_id', $estudiante_id, PDO::PARAM_INT);
$stmt->bindParam(':examen_id', $examen_id, PDO::PARAM_INT);
$stmt->bindParam(':codigo_acceso_utilizado', $codigo_acceso_utilizado, PDO::PARAM_STR);

if ($stmt->execute()) {
    // Obtener el ID del intento
    $intento_examen_id = $pdo->lastInsertId();

    // Obtener la cantidad total de preguntas del examen
    $sql_total_preguntas = "SELECT COUNT(*) as total_preguntas FROM preguntas WHERE examen_id = :examen_id";
    $stmt_total_preguntas = $pdo->prepare($sql_total_preguntas);
    $stmt_total_preguntas->bindParam(':examen_id', $examen_id, PDO::PARAM_INT);
    $stmt_total_preguntas->execute();
    $total_preguntas = $stmt_total_preguntas->fetch(PDO::FETCH_ASSOC)['total_preguntas'];

    echo json_encode([
        'intento_examen_id' => $intento_examen_id,
        'total_preguntas' => $total_preguntas
    ]);
} else {
    echo json_encode(['error' => 'Error al registrar el intento']);
}
