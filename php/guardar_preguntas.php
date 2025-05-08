<?php
session_start();
include '../config/conexion.php';
$conn = $pdo->getConexion();

// Recuperar datos del formulario
$examen_id = $_POST['examen_id'];
$texto_pregunta = $_POST['texto_pregunta'] ?? null;
$tipo_pregunta = $_POST['tipo_pregunta'];
$tipo_contenido = $_POST['tipo_contenido'] ?? null;
$imagenes = $_FILES['imagenes'] ?? null;
$opciones = $_POST['opciones'] ?? [];
$correctas = $_POST['correctas'] ?? [];
$respuesta_correctas = $_POST['respuesta_correcta'] ?? null;

try {
    // Validación básica de los campos obligatorios
    if (empty($examen_id) || empty($tipo_pregunta)) {
        $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'Faltan campos obligatorios.'];
        header('Location: ../admin/registrar_preguntas.php');
        exit();
    }
    $examen_id = filter_input(INPUT_POST, 'examen_id', FILTER_VALIDATE_INT);
    if (!$examen_id) {
        throw new Exception("ID de examen inválido.");
    }

    // Iniciar transacción
    $conn->beginTransaction();

    // Insertar la pregunta
    $stmt = $conn->prepare("INSERT INTO preguntas (examen_id, texto_pregunta, tipo_pregunta, tipo_contenido) VALUES (?, ?, ?, ?)");
    $stmt->execute([$examen_id, $texto_pregunta, $tipo_pregunta, $tipo_contenido]);
    $pregunta_id = $conn->lastInsertId();

    // Subir imágenes si aplica
    if ($imagenes && $tipo_contenido === 'ilustracion') {
        $upload_dir = "../uploads/preguntas/";
        if (!is_dir($upload_dir)) {
            if (!mkdir($upload_dir, 0777, true)) {
                throw new Exception("No se pudo crear la carpeta de carga.");
            }
        }

        if (!is_writable($upload_dir)) {
            throw new Exception("La carpeta de carga no tiene permisos de escritura.");
        }

        foreach ($imagenes['tmp_name'] as $key => $tmp_name) {
            if ($imagenes['error'][$key] === 0) {
                $file_name = basename($imagenes['name'][$key]);
                $target_file = $upload_dir . $file_name;

                // Validar extensión y tamaño
                $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
                $max_file_size = 5 * 1024 * 1024;

                if (!in_array($file_extension, $allowed_extensions)) {
                    throw new Exception("Extensión no permitida para la imagen '$file_name'.");
                }

                if (filesize($tmp_name) > $max_file_size) {
                    throw new Exception("La imagen '$file_name' excede el límite de 5MB.");
                }

                if (!getimagesize($tmp_name)) {
                    throw new Exception("El archivo '$file_name' no es una imagen válida.");
                }

                if (file_exists($target_file)) {
                    throw new Exception("La imagen '$file_name' ya existe en el servidor.");
                }

                if (move_uploaded_file($tmp_name, $target_file)) {
                    $stmt = $conn->prepare("INSERT INTO imagenes_pregunta (pregunta_id, ruta_imagen) VALUES (?, ?)");
                    $stmt->execute([$pregunta_id, $target_file]);
                } else {
                    throw new Exception("No se pudo mover la imagen '$file_name'.");
                }
            }
        }
    }

    // Insertar opciones
    if ($tipo_pregunta === 'multiple' || $tipo_pregunta === 'unica') {
        foreach ($opciones as $key => $opcion) {
            $correcta = isset($correctas[$key]) ? 1 : 0;
            $stmt = $conn->prepare("INSERT INTO opciones_pregunta (pregunta_id, texto_opcion, es_correcta) VALUES (?, ?, ?)");
            $stmt->execute([$pregunta_id, $opcion, $correcta]);
        }
    }
    if($tipo_pregunta === 'vf'){
        
        $correcta = isset($respuesta_correctas) ? 1 : 0;
        $stmt = $conn->prepare("INSERT INTO opciones_pregunta (pregunta_id, texto_opcion, es_correcta) VALUES (?, ?, ?)");
        $stmt->execute([$pregunta_id, $texto_pregunta, $correcta]);
    }


    // ✅ Actualizar el total de preguntas del examen
    $stmtActualizar = $conn->prepare("
    UPDATE examenes 
    SET total_preguntas = (
        SELECT COUNT(*) FROM preguntas WHERE examen_id = ?
    ) 
    WHERE id = ?
");
    $stmtActualizar->execute([$examen_id, $examen_id]);

    // Confirmar transacción
    $conn->commit();

    $_SESSION['alerta'] = ['tipo' => 'success', 'mensaje' => 'Pregunta registrada exitosamente.'];
    header('Location: ../admin/preguntas.php');
} catch (PDOException $e) {
    $conn->rollBack();
    $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'Error al registrar la pregunta: ' . $e->getMessage()];
    header('Location: ../admin/registrar_preguntas.php');
} catch (Exception $e) {
    $conn->rollBack();
    $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => $e->getMessage()];
    header('Location: ../admin/registrar_preguntas.php');
}
?>