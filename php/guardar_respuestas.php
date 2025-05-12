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
$tipo = $data['tipo']; // 'unica', 'multiple', 'vf'
$opciones = $data['opciones']; // ['v'] o ['f'] o IDs de opción

// Verificar tipo de pregunta válido
$tipos_validos = ['unica', 'multiple', 'vf'];
if (!in_array($tipo, $tipos_validos)) {
    echo json_encode(['ok' => false, 'mensaje' => 'Tipo de pregunta inválido']);
    exit;
}
/*  if (in_array($tipo, $tipos_validos)){
    echo json_encode([
        'ok' => true, 
        'mensaje' => 'Tipo de pregunta valido',
        'data' => $data
        ]);
        exit;
        
        } */

// Buscar ID de relación en `examenes_estudiantes`
$stmt = $pdo->prepare("SELECT * FROM examenes_estudiantes WHERE estudiante_id = ? ");
$stmt->execute([$estudiante_id]);
$relacion = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$relacion) {
    echo json_encode(['ok' => false, 'mensaje' => 'Relación examen-estudiante no encontrada']);
    exit;
}
/* else if ($relacion) {
    echo json_encode([
        'ok' => true,
        'mensaje' => 'Relación examen-estudiante encontrada',
        'data' => $relacion
    ]);
    exit;
    
    } */

$examenes_estudiantes_id = $relacion['id'];


// Eliminar respuestas previas para esta pregunta
/*  $stmt = $pdo->prepare("DELETE FROM respuestas_estudiante WHERE examenes_estudiantes_id = ? ");
$stmt->execute([$examenes_estudiantes_id]);
*/
$pdo->beginTransaction();

if ($tipo === 'vf') {
    $respuesta_vf = ($opciones[0] === 'v') ? 1 : 0;

    // Obtener la opción seleccionada (puede haber más de una, ajusta si es necesario)
    $stmt_op = $pdo->prepare("SELECT * FROM opciones_pregunta WHERE pregunta_id = ?");
    $stmt_op->execute([$pregunta_id]);
    $op_data = $stmt_op->fetch(PDO::FETCH_ASSOC);

    // Validar que exista la opción
    if (!$op_data) {
        echo json_encode(['ok' => false, 'mensaje' => 'Opción no encontrada para la pregunta.']);
        exit;
    }

    // Obtener valores a insertar
    $opcion_seleccionada_id = $op_data['id'];
    $respuesta_texto = $respuesta_vf;
    $es_correcta = ($op_data['es_correcta'] == 1) ? 1 : 0;

    // Preparar el insert
    $stmt = $pdo->prepare("INSERT INTO respuestas_estudiante (examenes_estudiantes_id, pregunta_id, opcion_seleccionada_id, respuesta_texto, es_correcta)
                       VALUES (?, ?, ?, ?, ?)");

    // Ejecutar con execute y array de datos
    $ejecutado = $stmt->execute([
        $examenes_estudiantes_id,
        $pregunta_id,
        $opcion_seleccionada_id,
        $respuesta_texto,
        $es_correcta
    ]);

    if ($ejecutado) {
        $id = $pdo->lastInsertId();
        echo json_encode([
            'ok' => true,
            'mensaje' => 'Respuesta registrada con éxito',
            'data' => [
                'id' => $id,
                'respuesta' => $respuesta_texto,
                'es_correcta' => $es_correcta,
                'opcion_id' => $opcion_seleccionada_id
            ]
        ]);
    } else {
        echo json_encode([
            'ok' => false,
            'mensaje' => 'Error al registrar la respuesta'
        ]);
    }
    exit;


}



/* 



else {
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
 */