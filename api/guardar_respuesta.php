<?php
ini_set('display_errors', 0); // Evita que errores de texto rompan el JSON
session_start();
require_once '../includes/conexion.php';
header('Content-Type: application/json');

/**
 * Función para devolver respuesta estandarizada
 */
function responder(bool $status, string $message = "", array $data = []) {
    echo json_encode(['status' => $status, 'message' => $message, 'data' => $data]);
    exit;
}

try {
    // 1. RECOGER DATOS
    $examen_id = $_POST['examen_id'] ?? null;
    $pregunta_id = $_POST['pregunta_id'] ?? null;
    $tipo_pregunta = $_POST['tipo_pregunta'] ?? null;
    $opciones_raw = $_POST['opciones'] ?? [];
    $forzar_finalizacion = ($_POST['forzar_finalizacion'] ?? 'false') === 'true';

    if (!$examen_id) throw new Exception("ID de examen no recibido.");

    $pdo->beginTransaction();

    // 2. GUARDAR RESPUESTA ACTUAL (Si el usuario marcó algo)
    if ($pregunta_id && $tipo_pregunta) {
        $opciones_sel = is_array($opciones_raw) ? $opciones_raw : [$opciones_raw];

        // Obtener el ID de enlace en el examen
        $stmtRel = $pdo->prepare("SELECT id FROM examen_preguntas WHERE examen_id = ? AND pregunta_id = ?");
        $stmtRel->execute([$examen_id, $pregunta_id]);
        $examen_pregunta_id = $stmtRel->fetchColumn();

        if ($examen_pregunta_id) {
            // Borrar respuestas anteriores para evitar duplicados
            $pdo->prepare("DELETE FROM respuestas_estudiante WHERE examen_pregunta_id = ?")
                ->execute([$examen_pregunta_id]);

            foreach ($opciones_sel as $op_id) {
                if ($op_id === "" && $op_id !== "0") continue;

                // TRADUCCIÓN V/F: Convertir el 1/0 del JS al ID real de la BBDD
                if ($tipo_pregunta === 'vf' && ($op_id === "1" || $op_id === "0")) {
                    $stVf = $pdo->prepare("SELECT id FROM opciones_pregunta WHERE pregunta_id = ? AND es_correcta = ? LIMIT 1");
                    $stVf->execute([$pregunta_id, $op_id]);
                    $real_id = $stVf->fetchColumn();
                    if ($real_id) $op_id = $real_id;
                }

                // Insertar solo si el ID es válido
                if (!empty($op_id) || $op_id === "0") {
                    $pdo->prepare("INSERT INTO respuestas_estudiante (examen_pregunta_id, opcion_id) VALUES (?, ?)")
                        ->execute([$examen_pregunta_id, $op_id]);
                }
            }
            // Marcar como respondida para el progreso
            $pdo->prepare("UPDATE examen_preguntas SET respondida = 1 WHERE id = ?")
                ->execute([$examen_pregunta_id]);
        }
    }

    // 3. CÁLCULO DE NOTA (Lógica Blindada contra el 54.14%)
    // Contamos cuántas PREGUNTAS ÚNICAS están bien contestadas
    $sqlPuntos = "SELECT COUNT(*) FROM (
        SELECT ep.id
        FROM examen_preguntas ep
        INNER JOIN respuestas_estudiante re ON ep.id = re.examen_pregunta_id
        INNER JOIN opciones_pregunta op ON re.opcion_id = op.id
        WHERE ep.examen_id = ?
        GROUP BY ep.id
        HAVING 
            SUM(op.es_correcta) > 0 -- Tiene al menos una correcta marcada
            AND SUM(IF(op.es_correcta = 0, 1, 0)) = 0 -- NO tiene ninguna incorrecta marcada
    ) AS tabla_aciertos";

    $stPuntos = $pdo->prepare($sqlPuntos);
    $stPuntos->execute([$examen_id]);
    $preguntas_correctas = (int)$stPuntos->fetchColumn();

    // Obtener el total real de preguntas asignadas a este examen
    $stTotal = $pdo->prepare("SELECT COUNT(*) FROM examen_preguntas WHERE examen_id = ?");
    $stTotal->execute([$examen_id]);
    $total_preguntas = (int)$stTotal->fetchColumn();

    // Si el examen no tiene preguntas (error raro), la nota es 0.
    $nota_final = ($total_preguntas > 0) ? ($preguntas_correctas / $total_preguntas) * 100 : 0;

    // 4. GESTIÓN DE FINALIZACIÓN
    // Contamos cuántas se han respondido físicamente
    $stRespondidas = $pdo->prepare("SELECT COUNT(*) FROM examen_preguntas WHERE examen_id = ? AND respondida = 1");
    $stRespondidas->execute([$examen_id]);
    $num_respondidas = (int)$stRespondidas->fetchColumn();

    // Se finaliza si respondió todas o si forzó la salida (tiempo/botón salir)
    $finalizado = ($num_respondidas >= $total_preguntas || $forzar_finalizacion);

    if ($finalizado) {
        // Guardar nota y estado final en la tabla examenes
        $pdo->prepare("UPDATE examenes SET estado = 'finalizado', calificacion = ? WHERE id = ?")
            ->execute([round($nota_final, 2), $examen_id]);

        // Actualizar el expediente del estudiante
        if (isset($_SESSION['estudiante']['estudiante_id'])) {
            $est_id = $_SESSION['estudiante']['estudiante_id'];
            $nuevo_estado = ($nota_final >= 80) ? 'aprobado' : 'en_proceso';
            
            // Buscamos la categoría asociada a este examen
            $sqlCat = "UPDATE estudiante_categorias 
                       SET estado = ?, fecha_aprobacion = IF(? >= 80, NOW(), fecha_aprobacion) 
                       WHERE estudiante_id = ? 
                       AND categoria_id = (SELECT categoria_id FROM examenes WHERE id = ?)";
            $pdo->prepare($sqlCat)->execute([$nuevo_estado, $nota_final, $est_id, $examen_id]);
        }
    }

    $pdo->commit();

    // 5. ENVIAR RESULTADOS AL JAVASCRIPT
    responder(true, "Operación exitosa", [
        'finalizado' => $finalizado,
        'calificacion_final' => round($nota_final, 2),
        'puntos' => $preguntas_correctas,
        'total' => $total_preguntas
    ]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    responder(false, "Error en el servidor: " . $e->getMessage());
}