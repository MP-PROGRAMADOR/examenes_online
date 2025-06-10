<?php
session_start();
require_once '../includes/conexion.php';


// Validar datos
if (!isset($_POST['examen_id'], $_POST['pregunta_id'], $_POST['opciones'], $_POST['tipo_pregunta'])) {
    http_response_code(400);
    echo json_encode(['success' => false, ' message' => 'Datos incompletos']);
    exit;
}

$examen_id = (int) trim($_POST['examen_id']);
$pregunta_id = (int) trim($_POST['pregunta_id']);
$opciones = $_POST['opciones']; // array
$tipo_pregunta = $_POST['tipo_pregunta']; // array

//contador de aciertos
if (!isset($_SESSION['resumen'])) {
    $_SESSION['resumen'] = 0.0;
}
$puntaje = 0.0;

try {
    $pdo->beginTransaction();

    // Obtener el id de examen_pregunta
    $stmt = $pdo->prepare("SELECT id FROM examen_preguntas WHERE examen_id = ? AND pregunta_id = ?");
    $stmt->execute([$examen_id, $pregunta_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row)
        throw new Exception('Pregunta no asignada al examen');

    $examen_pregunta_id = $row['id'];
    // Validar si ya fue respondida
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM respuestas_estudiante WHERE examen_pregunta_id = ?");
    $stmt->execute([$examen_pregunta_id]);
    if ($stmt->fetchColumn() > 0) {
        throw new Exception('La pregunta ya fue respondida');
    }


    if ($tipo_pregunta === 'vf') {
        $respuesta = (int) $opciones[0];
        // Insertar opciones seleccionadas
        $stmtInsert = $pdo->prepare("INSERT INTO respuestas_estudiante (examen_pregunta_id, opcion_id) VALUES (?, ?)");
        $stmtInsert->execute([$examen_pregunta_id, $pregunta_id]);

        // Comparar respuesta enviada con la existente
        $stmtCompare = $pdo->prepare('SELECT COUNT(*) FROM opciones_pregunta WHERE id = ? AND es_correcta = ?');
        $stmtCompare->execute([$pregunta_id, $respuesta]);
        $es_correcta_vf = (int) $stmtCompare->fetchColumn();

        $puntaje = (int) $es_correcta_vf;

    } else {
        // Insertar opciones seleccionadas y verificar cuáles son correctas
        $aciertos = 0.0;

        $stmtInsert = $pdo->prepare("INSERT INTO respuestas_estudiante (examen_pregunta_id, opcion_id) VALUES (?, ?)");
        //validamos si las opciones seleccionadas son ciertas
        $stmtCompare = $pdo->prepare('SELECT COUNT(*) FROM opciones_pregunta WHERE id = ? AND es_correcta = 1');
        //obtenemos el total de opciones de la pregunta
        $stmtOpciones = $pdo->prepare('SELECT COUNT(*) FROM opciones_pregunta WHERE pregunta_id = ?');
        $stmtOpciones->execute([$pregunta_id]);
        $total_opciones = (int) $stmtOpciones->fetchColumn(); // Esto devuelve 0 o mayor

        //obtenemos el total de opciones incorrectas de la pregunta. util para el conteo de la nota
        $stmtOpciones = $pdo->prepare('SELECT COUNT(*) FROM opciones_pregunta WHERE pregunta_id = ? AND es_correcta = 0');
        $stmtOpciones->execute([$pregunta_id]);
        $total_opciones_incorrecta = (int) $stmtOpciones->fetchColumn(); // Esto devuelve 0 o mayor


        foreach ($opciones as $opcion_id) {
            $opcion_id = (int) $opcion_id;

            // Insertar la respuesta del estudiante
            $stmtInsert->execute([$examen_pregunta_id, $opcion_id]);

            // Verificar si la opción es correcta
            $stmtCompare->execute([$opcion_id]);
            $es_correcta = (int) $stmtCompare->fetchColumn();

            // Sumar si es correcta
            $aciertos += $es_correcta;
        }

        // Calcular puntuación parcial para esta pregunta  en base a sus opciones y el nuemro total de opciones de la pregunta
        /* 
         @$aciertos.. serefiere al numero total de preguntas respondidas correctamente
         @total_opciones .. se refiere al numero total de opciones que tiene la pregunta en la base de datos
         @total_opciones_incorrectas .. se refiere al numero total de opciones falsas que tiene la pregunta en la base de datos
         @total_opciones_correctas .. se refiere al numero total de opciones correctas que tiene la pregunta en la base de datos
         @opciones .. se refiere al numero de opciones enviadas desde el frontend, es decir opciones seleccionadas por el estudiante
         */

        $puntaje =
            ((float) (
                $aciertos                          // opciones correctas marcadas
                +                                  // más
                ($total_opciones_incorrecta       // total incorrectas que existen
                    - (count($opciones) - $aciertos)) // menos incorrectas que el estudiante seleccionó
            ) / (float) $total_opciones);          // dividido entre total de opciones


    }
    // acumulamos las puntuaciones en la session
    $_SESSION['resumen'] += $puntaje;

    // Marcar como respondida
    $stmt = $pdo->prepare("UPDATE examen_preguntas SET respondida = 1 WHERE id = ?");
    $stmt->execute([$examen_pregunta_id]);

    // Verificar cuántas preguntas han sido respondidas
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM examen_preguntas WHERE examen_id = ? AND respondida = 1");
    $stmt->execute([$examen_id]);
    $respondidas = (float) $stmt->fetchColumn();

    // Obtener total de preguntas del examen
    $stmt = $pdo->prepare("SELECT total_preguntas FROM examenes WHERE id = ?");
    $stmt->execute([$examen_id]);
    $total = (float) $stmt->fetchColumn();



    if ($respondidas == $total) {
        // Calcular la nota  
        //$resumen = $_SESSION['resumen'];
        $calificacion = ((float) $_SESSION['resumen'] / (float) $total) * 100;

        $stmt = $pdo->prepare("UPDATE examenes SET estado = 'finalizado', calificacion = ? WHERE id = ?");
        $stmt->execute([$calificacion, $examen_id]);

        /* ----Actualizar el estado del carne para este estudiante ------- */
        $estudiante = $_SESSION['estudiante']; // estudiante
        $estudiante_id = $estudiante['id'];
        // Obtener el id de la categoria del examen
        $stmt = $pdo->prepare("SELECT categoria_id FROM examenes WHERE id = ?");
        $stmt->execute([$examen_id]);
        $categoria_id = (float) $stmt->fetchColumn();

        if ($calificacion > 80) {
            $stmt = $pdo->prepare("UPDATE estudiante_categorias SET estado = 'aprobado', fecha_aprobacion = NOW() WHERE estudiante_id = ? AND categoria_id = ?");
            $stmt->execute([$estudiante_id, $categoria_id]);
            
        } else {
            $stmt = $pdo->prepare("UPDATE estudiante_categorias SET estado = 'en_proceso' , fecha_aprobacion = NOW() WHERE estudiante_id = ? AND categoria_id = ?");
            $stmt->execute([$estudiante_id, $categoria_id]);
        

        }

        unset($_SESSION['resumen']);
    }

    $pdo->commit();
    echo json_encode([
        'success' => true,
        ' message' => ' todo esta bien',
        'data' => [
            'puntaje' => $puntaje ?? 0,
            'resumen' => $_SESSION['resumen'] ?? 0,
            'calificacion' => $calificacion ?? 0,
            'vf' => $es_correcta_vf ?? 0
        ]
    ]);

} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
