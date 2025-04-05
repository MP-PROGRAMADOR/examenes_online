<?php
// Configurar encabezado para codificación UTF-8
header('Content-Type: text/html; charset=utf-8');

// Incluir la conexión PDO (ya configurada previamente)
require_once '../../config/conexion.php';

// Inicializar variables para mensajes de error y éxito
$errores = [];
$mensaje_exito = '';

// Verificar que la solicitud sea POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Obtener y validar el examen
        $examen_id = filter_input(INPUT_POST, 'examen_id', FILTER_VALIDATE_INT);
        if (!$examen_id) {
            $errores[] = 'El examen seleccionado no es válido.';
        }

        // Obtener y validar el tipo de pregunta
        $tipo_pregunta = filter_input(INPUT_POST, 'tipo_pregunta', FILTER_SANITIZE_STRING);
        $tipos_validos = ['multiple_choice', 'respuesta_unica', 'verdadero_falso'];
        if (!in_array($tipo_pregunta, $tipos_validos)) {
            $errores[] = 'Tipo de pregunta no válido.';
        }

        // Validar el tipo de contenido (texto o ilustración)
        $tipo_contenido = filter_input(INPUT_POST, 'tipo_contenido', FILTER_SANITIZE_STRING);
        if (!in_array($tipo_contenido, ['texto', 'ilustracion'])) {
            $errores[] = 'Tipo de contenido inválido.';
        }

        // Validar texto de pregunta si aplica
        $texto_pregunta = '';
        if ($tipo_contenido === 'texto' || $tipo_contenido === 'ilustracion') {
            if (isset($_POST['texto_pregunta']) && !empty(trim($_POST['texto_pregunta']))) {
                $texto_pregunta = trim(filter_var($_POST['texto_pregunta'], FILTER_SANITIZE_STRING));
            } else {
                $errores[] = 'El texto de la pregunta es obligatorio.';
            }
        }

        // Validación de imágenes si es con ilustración
        $imagenes_rutas = [];
        if ($tipo_contenido === 'ilustracion' && isset($_FILES['imagenes'])) {
            foreach ($_FILES['imagenes']['tmp_name'] as $index => $tmp) {
                if ($_FILES['imagenes']['error'][$index] === UPLOAD_ERR_OK) {
                    $nombre = basename($_FILES['imagenes']['name'][$index]);
                    $extension = strtolower(pathinfo($nombre, PATHINFO_EXTENSION));

                    // Validar tipo de archivo permitido
                    if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                        $errores[] = "La imagen '$nombre' tiene un formato no permitido.";
                        continue;
                    }

                    $ruta_destino = '../../uploads/' . uniqid('img_', true) . '.' . $extension;

                    // Mover archivo al servidor
                    if (!move_uploaded_file($tmp, $ruta_destino)) {
                        $errores[] = "No se pudo subir la imagen '$nombre'.";
                    } else {
                        $imagenes_rutas[] = $ruta_destino;
                    }
                }
            }
        }

        // Validar las opciones según el tipo
        $opciones = [];
        $respuesta_correcta = null;

        if (in_array($tipo_pregunta, ['multiple_choice', 'respuesta_unica'])) {
            if (!isset($_POST['opcion']) || !is_array($_POST['opcion'])) {
                $errores[] = 'Debe ingresar al menos dos opciones.';
            } else {
                $opciones = array_map('trim', $_POST['opcion']);
                $opciones = array_filter($opciones, fn($val) => $val !== '');

                // Validar cantidad mínima
                if ($tipo_pregunta === 'multiple_choice' && count($opciones) < 2) {
                    $errores[] = 'Debe ingresar al menos dos opciones para opción múltiple.';
                }
                if ($tipo_pregunta === 'respuesta_unica' && count($opciones) !== 1) {
                    $errores[] = 'Solo debe ingresar una opción para respuesta única.';
                }

                // Validar la opción correcta
                $respuesta_correcta = filter_input(INPUT_POST, 'es_correcta', FILTER_VALIDATE_INT);
                if ($respuesta_correcta === false || !isset($opciones[$respuesta_correcta - 1])) {
                    $errores[] = 'Debe seleccionar una respuesta correcta válida.';
                }
            }
        }

        // Validar respuesta para verdadero/falso
        if ($tipo_pregunta === 'verdadero_falso') {
            if (!isset($_POST['es_correcta_vf']) || !in_array($_POST['es_correcta_vf'], ['verdadero', 'falso'])) {
                $errores[] = 'Debe seleccionar si la respuesta es verdadero o falso.';
            } else {
                $respuesta_correcta = $_POST['es_correcta_vf'];
            }
        }

        // Si no hay errores, guardar en la base de datos
        if (empty($errores)) {
            $conn = $pdo->getConexion();
            $conn->beginTransaction();

            // Insertar pregunta
            $sql = "INSERT INTO preguntas (examen_id, tipo_pregunta, tipo_contenido, texto_pregunta, respuesta_correcta, fecha_creacion) 
                    VALUES (:examen_id, :tipo_pregunta, :tipo_contenido, :texto_pregunta, :respuesta_correcta, NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':examen_id' => $examen_id,
                ':tipo_pregunta' => $tipo_pregunta,
                ':tipo_contenido' => $tipo_contenido,
                ':texto_pregunta' => $texto_pregunta,
                ':respuesta_correcta' => $respuesta_correcta
            ]);

            $pregunta_id = $conn->lastInsertId();

            // Insertar imágenes si las hay
            foreach ($imagenes_rutas as $ruta) {
                $sql_img = "INSERT INTO pregunta_imagenes (pregunta_id, ruta_imagen) VALUES (:pregunta_id, :ruta)";
                $stmt_img = $conn->prepare($sql_img);
                $stmt_img->execute([
                    ':pregunta_id' => $pregunta_id,
                    ':ruta' => $ruta
                ]);
            }

            // Insertar opciones si aplica
            if (!empty($opciones)) {
                foreach ($opciones as $index => $opcion) {
                    $es_correcta_opcion = ($respuesta_correcta == $index + 1) ? 1 : 0;
                    $sql_opcion = "INSERT INTO opciones_pregunta (pregunta_id, texto_opcion, es_correcta) VALUES (:pregunta_id, :texto, :es_correcta)";
                    $stmt_opcion = $conn->prepare($sql_opcion);
                    $stmt_opcion->execute([
                        ':pregunta_id' => $pregunta_id,
                        ':texto' => $opcion,
                        ':es_correcta' => $es_correcta_opcion
                    ]);
                }
            }

            $conn->commit();
            header("Location: ../vistas/listar_preguntas.php?mensaje=exito");
            exit;
        }
    } catch (PDOException $e) {
        // En caso de error de base de datos
        if (isset($conn)) {
            $conn->rollBack();
        }
        error_log("Error al guardar la pregunta: " . $e->getMessage());
        $errores[] = "Ocurrió un error al guardar la pregunta.";
    }
}

// Si hay errores, redireccionar con mensaje
if (!empty($errores)) {
    $mensaje_error = 'error_' . urlencode(implode(', ', $errores));
    header("Location: ../admin/preguntas.php?mensaje=$mensaje_error");
    exit;
}
?>
