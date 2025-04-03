<?php
// backend/procesar_pregunta.php
require '../../config/conexion.php';

$conn=$pdo->getConexion(); 

// Inicializar variables
$examen_id = '';
$texto_pregunta = '';
$tipo_pregunta = ''; 
$errores = [];
$mensaje_exito = '';
$mensaje_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger y sanitizar datos
    $examen_id = filter_input(INPUT_POST, 'examen_id', FILTER_SANITIZE_NUMBER_INT);
    $texto_pregunta = trim(strip_tags($_POST['texto_pregunta']));
    $tipo_pregunta = filter_input(INPUT_POST, 'tipo_pregunta', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    

    // Validar examen_id
    if (empty($examen_id) || $examen_id <= 0) {
        $errores['examen_id'] = 'Por favor, seleccione un examen válido.';
    }

    // Validar texto_pregunta
    if (empty($texto_pregunta)) {
        $errores['texto_pregunta'] = 'El texto de la pregunta es obligatorio.';
    }

    // Validar tipo_pregunta
    $tipos_validos = ['multiple_choice', 'verdadero_falso', 'respuesta_unica'];
    if (empty($tipo_pregunta) || !in_array($tipo_pregunta, $tipos_validos)) {
        $errores['tipo_pregunta'] = 'Por favor, seleccione un tipo de pregunta válido.';
    }

    // Validar opciones según el tipo de pregunta
    $opciones = isset($_POST['opcion']) && is_array($_POST['opcion']) ? $_POST['opcion'] : [];
    $es_correcta = isset($_POST['es_correcta']) ? filter_input(INPUT_POST, 'es_correcta', FILTER_SANITIZE_NUMBER_INT) : null;
    $es_correcta_vf = isset($_POST['es_correcta_vf']) ? filter_input(INPUT_POST, 'es_correcta_vf', FILTER_SANITIZE_FULL_SPECIAL_CHARS) : null;

    if (($tipo_pregunta === 'multiple_choice' || $tipo_pregunta === 'respuesta_unica')) {
        $opciones_validas = 0;
        foreach ($opciones as $opcion_texto) {
            if (!empty(trim(strip_tags($opcion_texto)))) {
                $opciones_validas++;
            }
        }
        if ($opciones_validas < 2 && $tipo_pregunta === 'multiple_choice') {
            $errores['opcion'] = 'Debe ingresar al menos dos opciones para opción múltiple.';
        } elseif ($opciones_validas < 1 && $tipo_pregunta === 'respuesta_unica') {
            $errores['opcion'] = 'Debe ingresar al menos una opción para respuesta única.';
        }
        if (empty($es_correcta) && $opciones_validas > 0) {
            $errores['es_correcta'] = 'Debe seleccionar la opción correcta.';
        } elseif ($es_correcta !== null && !isset($opciones[$es_correcta])) {
            $errores['es_correcta'] = 'La opción correcta seleccionada no existe.';
        }
    } elseif ($tipo_pregunta === 'verdadero_falso') {
        if (empty($es_correcta_vf) || !in_array($es_correcta_vf, ['verdadero', 'falso'])) {
            $errores['es_correcta_vf'] = 'Debe seleccionar si la respuesta correcta es verdadero o falso.';
        }
    }

    // Si no hay errores, guardar en la base de datos
    if (empty($errores)) {
        try {
            $conn->beginTransaction();

            // Insertar la pregunta
            $sqlPregunta = "INSERT INTO preguntas (examen_id, texto_pregunta, tipo_pregunta)
                            VALUES (:examen_id, :texto_pregunta, :tipo_pregunta)";
            $stmtPregunta = $conn->prepare($sqlPregunta);
            $stmtPregunta->bindParam(':examen_id', $examen_id, PDO::PARAM_INT);
            $stmtPregunta->bindParam(':texto_pregunta', $texto_pregunta, PDO::PARAM_STR);
            $stmtPregunta->bindParam(':tipo_pregunta', $tipo_pregunta, PDO::PARAM_STR);
          
            $stmtPregunta->execute();
            $pregunta_id = $conn->lastInsertId();

            // Insertar las opciones si es necesario
            if ($pregunta_id && ($tipo_pregunta === 'multiple_choice' || $tipo_pregunta === 'respuesta_unica')) {
                foreach ($opciones as $key => $opcion_texto) {
                    $opcion_texto_sanitizada = trim(strip_tags($opcion_texto));
                    if (!empty($opcion_texto_sanitizada)) {
                        $esCorrectaOpcion = ($key + 1 == $es_correcta) ? 1 : 0;
                        $sqlOpcion = "INSERT INTO opciones_pregunta (pregunta_id, texto_opcion, es_correcta)
                                      VALUES (:pregunta_id, :texto_opcion, :es_correcta)";
                        $stmtOpcion = $conn->prepare($sqlOpcion);
                        $stmtOpcion->bindParam(':pregunta_id', $pregunta_id, PDO::PARAM_INT);
                        $stmtOpcion->bindParam(':texto_opcion', $opcion_texto_sanitizada, PDO::PARAM_STR);
                        $stmtOpcion->bindParam(':es_correcta', $esCorrectaOpcion, PDO::PARAM_INT);
                        $stmtOpcion->execute();
                    }
                }
            } elseif ($pregunta_id && $tipo_pregunta === 'verdadero_falso') {
                $esCorrectaVF = ($es_correcta_vf === 'verdadero') ? 1 : 0;
                $sqlOpcion = "INSERT INTO opciones_pregunta (pregunta_id, texto_opcion, es_correcta)
                                  VALUES (:pregunta_id, :texto_opcion, :es_correcta)";
                $stmtOpcion = $conn->prepare($sqlOpcion);
                $stmtOpcion->bindParam(':pregunta_id', $pregunta_id, PDO::PARAM_INT);
                $stmtOpcion->bindParam(':texto_opcion', $es_correcta_vf, PDO::PARAM_STR);
                $stmtOpcion->bindParam(':es_correcta', $esCorrectaVF, PDO::PARAM_INT);
                $stmtOpcion->execute();
            }

            $conn->commit();
            $mensaje_exito = 'Pregunta guardada exitosamente.';
            header('Location: listar_preguntas.php?mensaje=exito');
            exit();

        } catch (PDOException $e) {
            $conn->rollBack();
            $mensaje_error = 'Error al guardar la pregunta: ' . $e->getMessage();
            error_log("PDOException al insertar pregunta: " . $e->getMessage());
        }
    }
}

// Incluir el formulario para mostrar errores (opcional, si no se redirige)
include '../frontend/registrar_pregunta.php';
?>