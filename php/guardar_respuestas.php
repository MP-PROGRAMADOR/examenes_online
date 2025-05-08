<?php
header('Content-Type: application/json');
require_once '../config/conexion.php'; // Ajusta la ruta según tu estructura

$pdo = $pdo->getConexion();

session_start();
$estudiante = $_SESSION['estudiante'];
    $estudiante_id = $estudiante['id'];

$input = json_decode(file_get_contents("php://input"), true);

if (
    !$estudiante_id ||
    !isset($input['pregunta_id'], $input['opciones'], $input['tipo']) ||
    !is_array($input['opciones'])
) {
    echo json_encode(['ok' => false, 'error' => 'Datos inválidos']);
    exit;
}

$pregunta_id = (int)$input['pregunta_id'];
$opciones = $input['opciones'];
$tipo = $input['tipo'];

// Obtener el examen activo del estudiante
$stmt = $pdo->prepare("SELECT * FROM examenes_estudiantes WHERE estudiante_id = ? AND estado = 'pendiente' AND acceso_habilitado = 1");
$stmt->execute([$estudiante_id]);
$examen = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$examen) {
    echo json_encode(['ok' => false, 'error' => 'No tienes un examen activo']);
    exit;
}

$examen_id = $examen['id'];

try {
    $pdo->beginTransaction();

    // Verificar la(s) respuesta(s) correcta(s)
    if ($tipo === 'vf') {
        $respuesta = strtolower( $opciones[0]); // 'v' o 'f'
        $stmt = $pdo->prepare("SELECT es_correcta FROM opciones_pregunta WHERE pregunta_id = ?");
        $stmt->execute([$pregunta_id]);
        $correcta = $stmt->fetchColumn();
        
        // comparamos la respuesta enviada con la opcion correcta de la base de datos
        (strtolower('v') == $respuesta) ? $respuesta = 1 : $respuesta = 0 ;
        $es_correcta = $respuesta === $correcta ? 1 : 0 ;
        // recoger el ID del texto de la pregunta
        $stmt = $pdo->prepare("SELECT id FROM opciones_pregunta WHERE pregunta_id = ?");
        $stmt->execute([$pregunta_id]);
        $opcion_vf_id = $stmt->fetchColumn();


        $stmt = $pdo->prepare("INSERT INTO respuestas_estudiante (examenes_estudiantes_id, pregunta_id, opcion_seleccionada_id, es_correcta)
                               VALUES (?, ?, ?,?)");
        $stmt->execute([$examen_id, $pregunta_id, $opcion_vf_id, $es_correcta]);

    } else {
        // Obtener opciones correctas
        $stmt = $pdo->prepare("SELECT id FROM opciones_pregunta WHERE pregunta_id = ? AND es_correcta = 1");
        $stmt->execute([$pregunta_id]);
        $correctas = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $seleccionadas = array_map('intval', $opciones);
        sort($seleccionadas);
        sort($correctas);

        $es_correcta = ($seleccionadas === $correctas) ? 1 : 0;

        foreach ($seleccionadas as $opcion_id) {
            $stmt = $pdo->prepare("INSERT INTO respuestas_estudiante (examenes_estudiantes_id, pregunta_id, opcion_seleccionada_id, es_correcta)
                                   VALUES (?, ?, ?, ?)");
            $stmt->execute([$examen_id, $pregunta_id, $opcion_id, $es_correcta]);
        }
    }

    // Contar respuestas registradas
    $stmt = $pdo->prepare("SELECT COUNT(DISTINCT pregunta_id) FROM respuestas_estudiante WHERE examenes_estudiantes_id = ?");
    $stmt->execute([$examen_id]);
    $respuestas_totales = $stmt->fetchColumn();

    if ($respuestas_totales >= (int)$examen['total_preguntas']) {
        // Examen finalizado
        $stmt = $pdo->prepare("SELECT COUNT(DISTINCT pregunta_id) FROM respuestas_estudiante 
                               WHERE examenes_estudiantes_id = ? AND es_correcta = 1");
        $stmt->execute([$examen_id]);
        $aciertos = $stmt->fetchColumn();

        $total = (int)$examen['total_preguntas'];
        $porcentaje = round(($aciertos / $total) * 100);
        $estado = $porcentaje >= 80 ? 'aprobado' : 'reprobado';

        $stmt = $pdo->prepare("UPDATE examenes_estudiantes SET 
            estado = ?,
            fecha_realizacion = NOW(),
            fecha_proximo_intento = DATE_ADD(NOW(), INTERVAL 3 DAY),
            acceso_habilitado = 0,
            intentos_examen = 0,
            calificacion = ?
            WHERE id = ?");
        $stmt->execute([$estado, $porcentaje, $examen_id]);

        $pdo->commit();
        echo json_encode(['ok' => true, 'finalizado' => true]);
        exit;
    }

    $pdo->commit();
    echo json_encode(['ok' => true, 'finalizado' => false]);

} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['ok' => false, 'error' => 'Error: ' . $e->getMessage()]);
}
