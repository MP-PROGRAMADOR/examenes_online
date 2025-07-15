<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../includes/conexion.php'; // Asumiendo que este archivo establece la conexión $pdo

header('Content-Type: application/json'); // Indicar que la respuesta es JSON

// Inicializar variables de sesión si no existen
if (!isset($_SESSION['resumen'])) {
    $_SESSION['resumen'] = 0.0;
}
if (!isset($_SESSION['contador_de_pregunta'])) {
    $_SESSION['contador_de_pregunta'] = 0;
}

// Función de validación
function validarDatosPregunta(): array
{
    $errores = [];
    $datosValidados = [];

    // Verificar campos obligatorios
    if (!isset($_POST['examen_id'], $_POST['pregunta_id'], $_POST['opciones'], $_POST['tipo_pregunta'])) {
        $errores[] = 'Faltan campos obligatorios en el formulario.';
        return ['errores' => $errores]; // Retornar solo errores si faltan datos críticos
    }

    // Sanitizar y validar entradas
    $examen_id = trim($_POST['examen_id']);
    $pregunta_id = trim($_POST['pregunta_id']);
    $tipo_pregunta = trim($_POST['tipo_pregunta']);
    $opciones_raw = $_POST['opciones']; // Opciones en bruto, puede ser string o array

    if (!ctype_digit($examen_id) || (int) $examen_id <= 0) {
        $errores[] = 'ID de examen inválido.';
    }
    if (!ctype_digit($pregunta_id) || (int) $pregunta_id <= 0) {
        $errores[] = 'ID de pregunta inválido.';
    }

    // IMPORTANTE: Tu enum `tipo` en la tabla `preguntas` usa 'unica', 'multiple', 'vf'.
    // Tu función de validación aquí usaba 'opcion_multiple', 'respuesta_unica'.
    // He ajustado esto para que coincida con los valores de tu ENUM de BD.
    if (!in_array($tipo_pregunta, ['unica', 'multiple', 'vf'])) { // Tipos corregidos
        $errores[] = 'Tipo de pregunta inválido.';
    }

    // Asegurarse de que las opciones sean un array
    $opciones_seleccionadas = [];
    if (!is_array($opciones_raw)) {
        // Si es un solo valor (ej. botón de radio), convertir a array
        $opciones_seleccionadas = [$opciones_raw];
    } else {
        $opciones_seleccionadas = $opciones_raw;
    }

    // Verificar si las opciones están vacías, permitiendo escenarios donde no se selecciona nada (ej. tiempo agotado)
    // Si está vacío o contiene solo una cadena vacía, se considera que no hay selección
    if (empty($opciones_seleccionadas) || (count($opciones_seleccionadas) == 1 && empty(trim($opciones_seleccionadas[0])))) {
        // Este es un escenario válido (no se respondió), por lo que no añadimos un error.
        // El código que llama a esta función deberá manejar esto como una pregunta omitida.
    } else {
        foreach ($opciones_seleccionadas as $opcion) {
            // Todas las opciones seleccionadas deben ser IDs enteros válidos
            if (!ctype_digit(strval($opcion)) || (int) $opcion <= 0) {
                $errores[] = 'Una de las opciones seleccionadas tiene un ID inválido.';
                break; // Detener la comprobación si una es inválida
            }
        }
    }

    // Retornar un array asociativo para separar claramente los errores de los datos validados
    return [
        'errores' => $errores,
        'datos' => [
            'examen_id' => (int) $examen_id,
            'pregunta_id' => (int) $pregunta_id,
            'tipo_pregunta' => $tipo_pregunta,
            'opciones_seleccionadas' => $opciones_seleccionadas
        ]
    ];
}

try {
    // Validar la estructura y el contenido de los datos
    $validacionResultado = validarDatosPregunta();
    $errores = $validacionResultado['errores'];
    $datosValidados = $validacionResultado['datos'] ?? null; // Null si hay errores críticos

    if (!empty($errores)) {
        // Incrementar el contador de preguntas saltadas
        $_SESSION['contador_de_pregunta']++;

        $examen_id = $datosValidados['examen_id'];
        $pregunta_id = $datosValidados['pregunta_id'];
        // Obtener el ID de la relación examen-pregunta
        $stmt = $pdo->prepare("SELECT id FROM examen_preguntas WHERE examen_id = ? AND pregunta_id = ?");
        $stmt->execute([$examen_id, $pregunta_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            throw new Exception('La pregunta no está asignada a este examen.');
        }
        $examen_pregunta_id = $row['id'];

        // Verificar si la pregunta ya fue respondida
        $stmt = $pdo->prepare("SELECT respondida FROM examen_preguntas WHERE id = ?");
        $stmt->execute([$examen_pregunta_id]);
        if ((bool) $stmt->fetchColumn()) {
            $pdo->commit();
            echo json_encode([
                'status' => true,
                'message' => 'Esta pregunta ya fue respondida.',
                'data' => ['resumen_actual' => $_SESSION['resumen']]
            ]);
            exit;
        }

        // Obtener el número de preguntas ya respondidas (para saber si el examen ha terminado) 
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM examen_preguntas WHERE examen_id = ? AND respondida = 1");
        $stmt->execute([$_POST['examen_id'] ?? 0]);
        $respondidas_bd = (int) $stmt->fetchColumn();

        $stmt = $pdo->prepare("SELECT total_preguntas FROM examenes WHERE id = ?");
        $stmt->execute([$_POST['examen_id'] ?? 0]);
        $total_preguntas_examen = (int) $stmt->fetchColumn();

        //$totalRespondidasMasNoRespondidas = (int) $_SESSION['contador_de_pregunta'] + $respondidas_bd;

        // Evaluar si debe cerrarse el examen aunque la pregunta sea inválida
        if ($respondidas_bd === $total_preguntas_examen) {
            $calificacion_final = ($_SESSION['resumen'] / $total_preguntas_examen) * 100;

            $stmt = $pdo->prepare("UPDATE examenes SET estado = 'finalizado', calificacion = ? WHERE id = ?");
            $stmt->execute([$calificacion_final, $_POST['examen_id']]);

            $estudiante_id = $_SESSION['estudiante']['estudiante_id'];
            $stmt = $pdo->prepare("SELECT categoria_id FROM examenes WHERE id = ?");
            $stmt->execute([$_POST['examen_id']]);
            $categoria_id = (int) $stmt->fetchColumn();

            $nuevo_estado = ($calificacion_final >= 80) ? 'aprobado' : 'en_proceso';
            $stmt = $pdo->prepare("UPDATE estudiante_categorias SET estado = ?, fecha_aprobacion = NOW() WHERE estudiante_id = ? AND categoria_id = ?");
            $stmt->execute([$nuevo_estado, $estudiante_id, $categoria_id]);

            unset($_SESSION['resumen']);
            unset($_SESSION['contador_de_pregunta']);
        }

        echo json_encode([
            'status' => true,
            'message' => 'Datos incompletos o inválidos. Saltando pregunta.',
            'data' => [
                'contador_preguntas_no_respondidas' => $_SESSION['contador_de_pregunta'],
                'errores' => $errores,
                'examen_finalizado' => ($totalRespondidasMasNoRespondidas === $total_preguntas_examen),
                'calificacion_final' => isset($calificacion_final) ? round($calificacion_final, 2) : null,
                'resumen_acumulado' => isset($_SESSION['resumen']) ? round($_SESSION['resumen'], 2) : 0.0
            ]
        ]);
        exit;
    }


    $examen_id = $datosValidados['examen_id'];
    $pregunta_id = $datosValidados['pregunta_id'];
    $tipo_pregunta = $datosValidados['tipo_pregunta'];
    $opciones_seleccionadas = $datosValidados['opciones_seleccionadas'];

    $pdo->beginTransaction();

    // Obtener el ID de la relación examen-pregunta
    $stmt = $pdo->prepare("SELECT id FROM examen_preguntas WHERE examen_id = ? AND pregunta_id = ?");
    $stmt->execute([$examen_id, $pregunta_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        throw new Exception('La pregunta no está asignada a este examen.');
    }
    $examen_pregunta_id = $row['id'];

    // Verificar si la pregunta ya fue respondida
    $stmt = $pdo->prepare("SELECT respondida FROM examen_preguntas WHERE id = ?");
    $stmt->execute([$examen_pregunta_id]);
    if ((bool) $stmt->fetchColumn()) {
        $pdo->commit();
        echo json_encode([
            'status' => true,
            'message' => 'Esta pregunta ya fue respondida.',
            'data' => ['resumen_actual' => $_SESSION['resumen']]
        ]);
        exit;
    }

    $puntaje_pregunta = 0.0;

    // Procesar la respuesta según el tipo de pregunta
    if ($tipo_pregunta === 'vf') {
        // Corrección crítica: Asegurarse de que se use un opcion_id real para VF.
        // Se asume que $opciones_seleccionadas[0] ahora contiene el ID real de BD
        // de la opción 'Verdadero' o 'Falso', no solo 0 o 1.
        if (empty($opciones_seleccionadas) || empty(trim($opciones_seleccionadas[0]))) {
            $puntaje_pregunta = 0.0; // No se envió respuesta para VF
        } else {
            $opcion_seleccionada_id = (int) $opciones_seleccionadas[0];

            $stmtInsert = $pdo->prepare("INSERT INTO respuestas_estudiante (examen_pregunta_id, opcion_id) VALUES (?, ?)");
            $stmtInsert->execute([$examen_pregunta_id, $pregunta_id]); // CORREGIDO: Usar el ID real de la opción

            // Verificar si la opción seleccionada es correcta según su ID
            $stmtCheckCorrectVF = $pdo->prepare('SELECT es_correcta FROM opciones_pregunta WHERE id = ?');
            $stmtCheckCorrectVF->execute([$pregunta_id]); // CORREGIDO: Usar el ID real de la opción
            $is_correct_vf = (bool) $stmtCheckCorrectVF->fetchColumn();

            $puntaje_pregunta = $is_correct_vf ? 1.0 : 0.0;
        }
    } else { // 'multiple' o 'unica'
        $aciertos = 0; // Número de opciones seleccionadas correctamente
        $opciones_marcadas_incorrectas = 0; // Número de opciones seleccionadas incorrectamente

        // Obtener todos los IDs de las opciones correctas para esta pregunta desde la BD
        $stmtCorrectOptions = $pdo->prepare('SELECT id FROM opciones_pregunta WHERE pregunta_id = ? AND es_correcta = 1');
        $stmtCorrectOptions->execute([$pregunta_id]);
        $opciones_correctas_reales = $stmtCorrectOptions->fetchAll(PDO::FETCH_COLUMN);

        // Obtener todos los IDs de las opciones incorrectas para esta pregunta desde la BD
        $stmtIncorrectOptions = $pdo->prepare('SELECT id FROM opciones_pregunta WHERE pregunta_id = ? AND es_correcta = 0');
        $stmtIncorrectOptions->execute([$pregunta_id]);
        $opciones_incorrectas_reales = $stmtIncorrectOptions->fetchAll(PDO::FETCH_COLUMN);

        // Verificar si no se seleccionó ninguna opción (ej. se saltó o el tiempo se agotó)
        if (empty($opciones_seleccionadas) || (count($opciones_seleccionadas) == 1 && empty(trim($opciones_seleccionadas[0])))) {
            $puntaje_pregunta = 0.0; // No hay puntuación para preguntas sin respuesta
        } else {
            $stmtInsert = $pdo->prepare("INSERT INTO respuestas_estudiante (examen_pregunta_id, opcion_id) VALUES (?, ?)");

            foreach ($opciones_seleccionadas as $opcion_id) {
                $opcion_id = (int) $opcion_id;

                // Insertar la respuesta del estudiante
                $stmtInsert->execute([$examen_pregunta_id, $opcion_id]);

                // Verificar si la opción seleccionada es una de las opciones correctas reales
                if (in_array($opcion_id, $opciones_correctas_reales)) {
                    $aciertos++;
                }
                // Verificar si la opción seleccionada es una de las opciones incorrectas reales
                if (in_array($opcion_id, $opciones_incorrectas_reales)) {
                    $opciones_marcadas_incorrectas++;
                }
            }

            // Calcular puntuación para esta pregunta
            $total_opciones_correctas_reales = count($opciones_correctas_reales);

            if ($tipo_pregunta === 'unica') {
                // Para opción única, 1 punto si la única opción seleccionada es correcta, 0 si no.
                $puntaje_pregunta = ($aciertos === 1 && $opciones_marcadas_incorrectas === 0) ? 1.0 : 0.0;
            } else { // tipo 'multiple'
                // Puntuación común para opción múltiple: (Seleccionadas Correctas - Seleccionadas Incorrectas) / Total de Opciones Correctas
                // Asegura que la puntuación no baje de cero.
                $puntos_brutos = $aciertos - $opciones_marcadas_incorrectas;

                if ($total_opciones_correctas_reales > 0) {
                    $puntaje_pregunta = max(0.0, (float) $puntos_brutos / (float) $total_opciones_correctas_reales);
                } else {
                    $puntaje_pregunta = 0.0; // Si no hay opciones correctas, la puntuación es 0.
                }
            }
        }
    }

    // Acumular la puntuación en la sesión
    $_SESSION['resumen'] += $puntaje_pregunta;

    // Marcar la pregunta como respondida en la base de datos
    $stmt = $pdo->prepare("UPDATE examen_preguntas SET respondida = 1 WHERE id = ?");
    $stmt->execute([$examen_pregunta_id]);

    // Verificar si el examen está completado
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM examen_preguntas WHERE examen_id = ? AND respondida = 1");
    $stmt->execute([$examen_id]);
    $respondidas_bd = (int) $stmt->fetchColumn();

    $stmt = $pdo->prepare("SELECT total_preguntas FROM examenes WHERE id = ?");
    $stmt->execute([$examen_id]);
    $total_preguntas_examen = (int) $stmt->fetchColumn();

    // Verificar si todas las preguntas han sido respondidas o saltadas
    $totalRespondidasMasNoRespondidas = (int) $_SESSION['contador_de_pregunta'];

    if ($respondidas_bd === $total_preguntas_examen) {
        $calificacion_final = ($_SESSION['resumen'] / $total_preguntas_examen) * 100;

        $stmt = $pdo->prepare("UPDATE examenes SET estado = 'finalizado', calificacion = ? WHERE id = ?");
        $stmt->execute([$calificacion_final, $examen_id]);

        $estudiante_id = $_SESSION['estudiante']['estudiante_id'];

        $stmt = $pdo->prepare("SELECT categoria_id FROM examenes WHERE id = ?");
        $stmt->execute([$examen_id]);
        $categoria_id = (int) $stmt->fetchColumn();

        $nuevo_estado = ($calificacion_final >= 80) ? 'aprobado' : 'en_proceso';
        $stmt = $pdo->prepare("UPDATE estudiante_categorias SET estado = ?, fecha_aprobacion = NOW() WHERE estudiante_id = ? AND categoria_id = ?");
        $stmt->execute([$nuevo_estado, $estudiante_id, $categoria_id]);

        unset($_SESSION['resumen']);
        unset($_SESSION['contador_de_pregunta']);
    }

    $pdo->commit(); // Confirmar la transacción

    echo json_encode([
        'status' => true,
        'finalizado' => ($respondidas_bd === $total_preguntas_examen),
        'message' => 'Respuesta guardada exitosamente.',
        'data' => [
            'iguales' => ($respondidas_bd === $total_preguntas_examen),
            'total_respon_no_respon' => $totalRespondidasMasNoRespondidas,
            'puntaje_pregunta_actual' => round($puntaje_pregunta, 2),
            'resumen_acumulado' => isset($_SESSION['resumen']) ? round($_SESSION['resumen'], 2) : 0.0,
            'examen_finalizado' => ($totalRespondidasMasNoRespondidas === $total_preguntas_examen), // Usar el conteo combinado aquí
            'calificacion_final' => isset($calificacion_final) ? round($calificacion_final, 2) : null
        ]
    ]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack(); // Revertir la transacción en caso de error
    }
    http_response_code(500); // Error interno del servidor
    echo json_encode(['status' => false, 'message' => 'Error al procesar la respuesta: ' . $e->getMessage()]);
}
?>