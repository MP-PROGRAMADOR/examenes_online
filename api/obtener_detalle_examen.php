<?php
require_once '../includes/conexion.php'; // Asegúrate de que tu archivo de conexión PDO es correcto
header('Content-Type: application/json');

$response = ['status' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $examenId = isset($_GET['examen_id']) ? (int)$_GET['examen_id'] : null;

    if (!$examenId) {
        $response['message'] = 'ID de examen no proporcionado.';
        echo json_encode($response);
        exit;
    }

    try {
        // 1. Obtener los detalles generales del examen
        $stmt = $pdo->prepare("
            SELECT
                e.id AS examen_id, e.codigo_acceso, e.fecha_asignacion, e.duracion, e.total_preguntas, e.estado, e.calificacion,
                est.nombre AS estudiante_nombre, est.apellidos AS estudiante_apellidos, est.dni AS estudiante_dni,
                cat.nombre AS categoria_nombre,
                u.nombre AS asignado_por_nombre
            FROM examenes e
            JOIN estudiantes est ON e.estudiante_id = est.id
            JOIN categorias cat ON e.categoria_id = cat.id
            LEFT JOIN usuarios u ON e.asignado_por = u.id
            WHERE e.id = ?
        ");
        $stmt->execute([$examenId]);
        $examen = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$examen) {
            $response['message'] = 'Examen no encontrado.';
            echo json_encode($response);
            exit;
        }

        // 2. Obtener las preguntas del examen y las respuestas del estudiante
        $stmt = $pdo->prepare("
            SELECT
                ep.id AS examen_pregunta_id,
                p.id AS pregunta_id,
                p.texto AS pregunta_texto,
                p.tipo AS pregunta_tipo,
                p.tipo_contenido AS pregunta_tipo_contenido,
                ip.ruta_imagen AS pregunta_imagen_ruta,
                GROUP_CONCAT(DISTINCT CONCAT(op.id, '||', op.texto, '||', op.es_correcta) ORDER BY op.id SEPARATOR '###') AS opciones_str,
                GROUP_CONCAT(DISTINCT CONCAT(re.opcion_id) ORDER BY re.opcion_id SEPARATOR ',') AS respuestas_estudiante_ids_str
            FROM examen_preguntas ep
            JOIN preguntas p ON ep.pregunta_id = p.id
            LEFT JOIN imagenes_pregunta ip ON p.id = ip.pregunta_id
            JOIN opciones_pregunta op ON p.id = op.pregunta_id
            LEFT JOIN respuestas_estudiante re ON ep.id = re.examen_pregunta_id
            WHERE ep.examen_id = ?
            GROUP BY ep.id, p.id, p.texto, p.tipo, p.tipo_contenido, ip.ruta_imagen
            ORDER BY ep.id
        ");
        $stmt->execute([$examenId]);
        $preguntasRaw = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $preguntas = [];
        foreach ($preguntasRaw as $pr) {
            $opciones = [];
            $opciones_str_arr = explode('###', $pr['opciones_str']);
            foreach ($opciones_str_arr as $opt_str) {
                list($opt_id, $opt_texto, $opt_es_correcta) = explode('||', $opt_str);
                $opciones[] = [
                    'id' => (int)$opt_id,
                    'texto' => $opt_texto,
                    'es_correcta' => (bool)$opt_es_correcta
                ];
            }

            $respuestas_estudiante_ids = !empty($pr['respuestas_estudiante_ids_str']) ? array_map('intval', explode(',', $pr['respuestas_estudiante_ids_str'])) : [];

            // Determinar si la respuesta del estudiante fue correcta o incorrecta
            $acierto = false;
            if ($pr['pregunta_tipo'] === 'unica' || $pr['pregunta_tipo'] === 'vf') {
                // Para preguntas de opción única o V/F:
                // El acierto se da si la única opción seleccionada es la correcta.
                if (count($respuestas_estudiante_ids) === 1) {
                    foreach ($opciones as $opt) {
                        if ($opt['id'] === $respuestas_estudiante_ids[0] && $opt['es_correcta']) {
                            $acierto = true;
                            break;
                        }
                    }
                }
            } elseif ($pr['pregunta_tipo'] === 'multiple') {
                // Para preguntas de opción múltiple:
                // El acierto se da si TODAS las opciones correctas fueron seleccionadas, Y NINGUNA opción incorrecta fue seleccionada.
                $correct_options_ids = array_map(function($o){ return $o['id']; }, array_filter($opciones, function($o){ return $o['es_correcta']; }));

                if (count($correct_options_ids) === count($respuestas_estudiante_ids)) {
                    $acierto = (count(array_diff($correct_options_ids, $respuestas_estudiante_ids)) === 0);
                }
            }

            $preguntas[] = [
                'examen_pregunta_id' => (int)$pr['examen_pregunta_id'],
                'pregunta_id' => (int)$pr['pregunta_id'],
                'texto' => $pr['pregunta_texto'],
                'tipo' => $pr['pregunta_tipo'],
                'tipo_contenido' => $pr['pregunta_tipo_contenido'],
                'imagen_ruta' => $pr['pregunta_imagen_ruta'],
                'opciones' => $opciones,
                'respuestas_estudiante_ids' => $respuestas_estudiante_ids,
                'acierto' => $acierto // true si la respuesta del estudiante fue correcta, false si fue incorrecta
            ];
        }

        $examen['preguntas'] = $preguntas;

        $response = [
            'status' => true,
            'message' => 'Detalle del examen obtenido correctamente.',
            'examen' => $examen
        ];

    } catch (PDOException $e) {
        error_log("Error PDO en obtener_detalle_examen.php: " . $e->getMessage());
        $response['message'] = 'Error de base de datos: ' . $e->getMessage();
    } catch (Exception $e) {
        error_log("Error en obtener_detalle_examen.php: " . $e->getMessage());
        $response['message'] = 'Error: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'Método de solicitud no permitido.';
}

echo json_encode($response);
?>