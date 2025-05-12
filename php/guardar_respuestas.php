<?php
session_start();
header('Content-Type: application/json');
require_once '../config/conexion.php'; // Ajusta la ruta según tu estructura

$pdo = $pdo->getConexion();

$estudiante = $_SESSION['estudiante'];
$estudiante_id = $estudiante['id'];


// Validar método HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'mensaje' => 'Método no permitido']);
    exit;
}

// Obtener y decodificar JSON
$data = json_decode(file_get_contents("php://input"), true);

// Validar campos requeridos
if (!isset($data['pregunta_id'], $data['opciones'], $data['tipo'], $data['examen_id'])) {
    echo json_encode(['ok' => false, 'mensaje' => 'Faltan datos requeridos']);
    exit;
}
 
$pregunta_id = intval($data['pregunta_id']);
$examen_id = intval($data['examen_id']);
$tipo = $data['tipo'];
$opciones = $data['opciones']; // ['v'] o ['f'] o IDs de opción

// Verificar tipo de pregunta válido
$tipos_validos = ['unica', 'multiple', 'vf'];
if (!in_array($tipo, $tipos_validos)) {
    echo json_encode(['ok' => false, 'mensaje' => 'Tipo de pregunta inválido']);
    exit;
}

// Buscar ID de relación en `examenes_estudiantes`
$stmt = $pdo->prepare("SELECT id FROM examenes_estudiantes WHERE estudiante_id = ? AND examen_id = ?");
$stmt->execute([$estudiante_id, $examen_id]);
$relacion = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$relacion) {
    echo json_encode(['ok' => false, 'mensaje' => 'Relación examen-estudiante no encontrada']);
    exit;
}

$examenes_estudiantes_id = $relacion['id'];

try {
    // Eliminar respuestas previas para esta pregunta
    $stmt = $pdo->prepare("DELETE FROM respuestas_estudiante WHERE examenes_estudiantes_id = ? AND pregunta_id = ?");
    $stmt->execute([$examenes_estudiantes_id, $pregunta_id]);

    $pdo->beginTransaction();

    if ($tipo === 'vf') {
        $respuesta_vf = ($opciones[0] === 'v') ? 'v' : 'f';

        // Obtener la opción correcta desde opciones_pregunta
        $stmt_op = $pdo->prepare("SELECT es_correcta FROM opciones_pregunta WHERE pregunta_id = ? AND texto_opcion = ?");
        $stmt_op->execute([$pregunta_id, strtoupper($respuesta_vf)]);
        $op_data = $stmt_op->fetch(PDO::FETCH_ASSOC);

        $es_correcta = $op_data ? $op_data['es_correcta'] : 0;

        $stmt = $pdo->prepare("
            INSERT INTO respuestas_estudiante (examenes_estudiantes_id, pregunta_id, respuesta_texto, es_correcta)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$examenes_estudiantes_id, $pregunta_id, $respuesta_vf, $es_correcta]);

    } else {
        // Tipo única o múltiple
        foreach ($opciones as $opcion_id) {
            $stmt_op = $pdo->prepare("SELECT es_correcta FROM opciones_pregunta WHERE id = ?");
            $stmt_op->execute([intval($opcion_id)]);
            $op_data = $stmt_op->fetch(PDO::FETCH_ASSOC);

            $es_correcta = $op_data ? $op_data['es_correcta'] : 0;

            $stmt = $pdo->prepare("
                INSERT INTO respuestas_estudiante (examenes_estudiantes_id, pregunta_id, opcion_seleccionada_id, es_correcta)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([$examenes_estudiantes_id, $pregunta_id, intval($opcion_id), $es_correcta]);
        }
    }

    $pdo->commit();

} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['ok' => false, 'mensaje' => 'Error al guardar respuesta', 'error' => $e->getMessage()]);
    exit;
}

// Verificar si ya respondió todas las preguntas
$stmt = $pdo->prepare("
    SELECT COUNT(*) FROM preguntas WHERE examen_id = ?
");
$stmt->execute([$examen_id]);
$total_preguntas = $stmt->fetchColumn();

$stmt = $pdo->prepare("
    SELECT COUNT(DISTINCT pregunta_id) FROM respuestas_estudiante
    WHERE examenes_estudiantes_id = ?
");
$stmt->execute([$examenes_estudiantes_id]);
$total_respondidas = $stmt->fetchColumn();

$finalizado = ($total_respondidas >= $total_preguntas);
$calificacion = null;

if ($finalizado) {
    // Calcular calificación basada en respuestas correctas
    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM respuestas_estudiante
        WHERE examenes_estudiantes_id = ? AND es_correcta = 1
    ");
    $stmt->execute([$examenes_estudiantes_id]);
    $correctas = $stmt->fetchColumn();

    $calificacion = round(($correctas / $total_preguntas) * 100);
    $estado = $calificacion >= 70 ? 'aprobado' : 'reprobado';

    // Actualizar tabla `examenes_estudiantes`
    $stmt = $pdo->prepare("
        UPDATE examenes_estudiantes
        SET estado = ?, acceso_habilitado = 0, fecha_realizacion = NOW(), calificacion = ?
        WHERE id = ?
    ");
    $stmt->execute([$estado, $calificacion, $examenes_estudiantes_id]);
}

// Enviar respuesta al frontend
echo json_encode([
    'ok' => true,
    'mensaje' => $finalizado ? 'Examen finalizado' : 'Respuesta guardada',
    'finalizado' => $finalizado,
    'calificacion' => $calificacion
]);
