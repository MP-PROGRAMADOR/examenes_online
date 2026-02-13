<?php
ini_set('display_errors', 0);
session_start();
require_once '../includes/conexion.php';
header('Content-Type: application/json');

function responder(bool $status, string $message = "", array $data = [])
{
    echo json_encode(['status' => $status, 'message' => $message, 'data' => $data]);
    exit;
}

try {
    $examen_id = $_POST['examen_id'] ?? null;
    $pregunta_id = $_POST['pregunta_id'] ?? null;
    $tipo_pregunta = $_POST['tipo_pregunta'] ?? null;
    $opciones_raw = $_POST['opciones'] ?? null;
    $forzar_finalizacion = ($_POST['forzar_finalizacion'] ?? 'false') === 'true';

    if (!$examen_id) throw new Exception("ID de examen no recibido.");

    $pdo->beginTransaction();

    // 1. GUARDAR RESPUESTA (Solo si el usuario interactuó)
    if ($pregunta_id && $pregunta_id != 0) {
        $stmtRel = $pdo->prepare("SELECT id FROM examen_preguntas WHERE examen_id = ? AND pregunta_id = ?");
        $stmtRel->execute([$examen_id, $pregunta_id]);
        $examen_pregunta_id = $stmtRel->fetchColumn();

        if ($examen_pregunta_id) {
            $pdo->prepare("DELETE FROM respuestas_estudiante WHERE examen_pregunta_id = ?")->execute([$examen_pregunta_id]);

            if ($opciones_raw !== null) {
                $opciones_sel = is_array($opciones_raw) ? $opciones_raw : [$opciones_raw];
                $registro_exitoso = false;

                foreach ($opciones_sel as $op_id) {
                    if ($op_id === "" || $op_id === null) continue;

                    if ($tipo_pregunta === 'vf' && ($op_id === "1" || $op_id === "0")) {
                        $stVf = $pdo->prepare("SELECT id FROM opciones_pregunta WHERE pregunta_id = ? AND es_correcta = ? LIMIT 1");
                        $stVf->execute([$pregunta_id, $op_id]);
                        $op_id = $stVf->fetchColumn();
                    }

                    if ($op_id) {
                        $pdo->prepare("INSERT INTO respuestas_estudiante (examen_pregunta_id, opcion_id) VALUES (?, ?)")
                            ->execute([$examen_pregunta_id, $op_id]);
                        $registro_exitoso = true;
                    }
                }
                if($registro_exitoso) {
                    $pdo->prepare("UPDATE examen_preguntas SET respondida = 1 WHERE id = ?")->execute([$examen_pregunta_id]);
                }
            }
        }
    }

    // 2. CÁLCULO DE LA NOTA (ESTRICTO)
    $stTotal = $pdo->prepare("SELECT COUNT(*) FROM examen_preguntas WHERE examen_id = ?");
    $stTotal->execute([$examen_id]);
    $total_preguntas = (int)$stTotal->fetchColumn();

    // SQL CORREGIDO: Añadimos una verificación para asegurar que EXISTAN respuestas del estudiante.
    // Si el conteo de respuestas del estudiante para esa pregunta es 0, no entra en el conteo de puntos.
    $sqlPuntos = "SELECT COUNT(*) FROM examen_preguntas ep
                  WHERE ep.examen_id = ? AND (
                    -- CONDICIÓN OBLIGATORIA: El estudiante debe haber marcado AL MENOS una opción
                    SELECT COUNT(*) FROM respuestas_estudiante WHERE examen_pregunta_id = ep.id
                  ) > 0 AND (
                    -- Comprobar que marcó TODAS las correctas
                    SELECT COUNT(*) FROM respuestas_estudiante re 
                    JOIN opciones_pregunta op ON re.opcion_id = op.id 
                    WHERE re.examen_pregunta_id = ep.id AND op.es_correcta = 1
                  ) = (
                    SELECT COUNT(*) FROM opciones_pregunta WHERE pregunta_id = ep.pregunta_id AND es_correcta = 1
                  ) AND (
                    -- Comprobar que NO marcó ninguna incorrecta
                    SELECT COUNT(*) FROM respuestas_estudiante re 
                    JOIN opciones_pregunta op ON re.opcion_id = op.id 
                    WHERE re.examen_pregunta_id = ep.id AND op.es_correcta = 0
                  ) = 0";

    $stPuntos = $pdo->prepare($sqlPuntos);
    $stPuntos->execute([$examen_id]);
    $preguntas_correctas = (int)$stPuntos->fetchColumn();

    $nota_final = ($total_preguntas > 0) ? ($preguntas_correctas / $total_preguntas) * 100 : 0;
    $nota_limpia = number_format($nota_final, 2, '.', '');

    // 3. FINALIZACIÓN
    if ($forzar_finalizacion) {
        $pdo->prepare("UPDATE examenes SET estado = 'finalizado', calificacion = ? WHERE id = ?")
            ->execute([$nota_limpia, $examen_id]);

        if (isset($_SESSION['estudiante']['estudiante_id'])) {
            $est_id = $_SESSION['estudiante']['estudiante_id'];
            $nuevo_estado = ($nota_final >= 50) ? 'aprobado' : 'en_proceso';
            $sqlCat = "UPDATE estudiante_categorias SET estado = ?, fecha_aprobacion = IF(? >= 50, NOW(), fecha_aprobacion) 
                       WHERE estudiante_id = ? AND categoria_id = (SELECT categoria_id FROM examenes WHERE id = ?)";
            $pdo->prepare($sqlCat)->execute([$nuevo_estado, $nota_final, $est_id, $examen_id]);
        }
    }

    $pdo->commit();

    responder(true, "Procesado", [
        'finalizado' => $forzar_finalizacion,
        'calificacion_final' => $nota_limpia,
        'puntos' => $preguntas_correctas,
        'total' => $total_preguntas
    ]);

} catch (Exception $e) {
    if ($pdo && $pdo->inTransaction()) $pdo->rollBack();
    responder(false, "Error: " . $e->getMessage());
}