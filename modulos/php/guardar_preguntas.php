<?php
session_start();
include '../../config/conexion.php';
$conn = $pdo->getConexion();

// Recuperar datos del formulario
$examen_id = $_POST['examen_id'];
$texto_pregunta = $_POST['texto_pregunta'] ?? null;
$tipo_pregunta = $_POST['tipo_pregunta'];
$tipo_contenido = $_POST['tipo_contenido'] ?? null;
$imagenes = $_FILES['imagenes'] ?? null;
$opciones = $_POST['opciones'] ?? [];
$correctas = $_POST['correctas'] ?? [];

try {
    // Validación básica de los campos
    if (empty($examen_id) || empty($tipo_pregunta)) {
        $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'Faltan campos obligatorios.'];
        header('Location: ../pages/registrar_pregunta.php');
        exit();
    }

    // Iniciar transacción
    $conn->beginTransaction();

    // Insertar la pregunta en la base de datos
    $stmt = $conn->prepare("INSERT INTO preguntas (examen_id, texto_pregunta, tipo_pregunta, tipo_contenido) VALUES (?, ?, ?, ?)");
    $stmt->execute([$examen_id, $texto_pregunta, $tipo_pregunta, $tipo_contenido]);
    $pregunta_id = $conn->lastInsertId(); // Obtener el ID de la nueva pregunta

    // Subir las imágenes si es necesario
    if ($imagenes && $tipo_contenido === 'ilustracion') {
        // Verificar la carpeta de carga
        $upload_dir = "../../uploads/preguntas/";
        if (!is_dir($upload_dir)) {
            if (!mkdir($upload_dir, 0777, true)) {
                throw new Exception("No se pudo crear la carpeta de carga.");
            }
        }

        // Verificar permisos de escritura en la carpeta de carga
        if (!is_writable($upload_dir)) {
            throw new Exception("La carpeta de carga no tiene permisos de escritura.");
        }

        foreach ($imagenes['tmp_name'] as $key => $tmp_name) {
            if ($imagenes['error'][$key] === 0) {
                $file_name = basename($imagenes['name'][$key]);
                $target_file = $upload_dir . $file_name;

                // Validaciones de la imagen
                $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif']; // Extensiones permitidas
                $max_file_size = 5 * 1024 * 1024; // 5MB maximo

                // Validar extensión de archivo
                if (!in_array($file_extension, $allowed_extensions)) {
                    throw new Exception("Solo se permiten imágenes con las extensiones: .jpg, .jpeg, .png, .gif.");
                }

                // Validar tamaño del archivo
                if (filesize($tmp_name) > $max_file_size) {
                    throw new Exception("El tamaño de la imagen excede el límite de 5MB.");
                }

                // Verificar si el archivo ya existe en el servidor
                if (file_exists($target_file)) {
                    throw new Exception("La imagen '$file_name' ya existe en el servidor.");
                }

                // Verificar que la imagen es un archivo real
                if (!getimagesize($tmp_name)) {
                    throw new Exception("El archivo '$file_name' no es una imagen válida.");
                }

                // Mover la imagen al directorio de destino
                if (move_uploaded_file($tmp_name, $target_file)) {
                    // Insertar ruta de la imagen en la base de datos
                    $stmt = $conn->prepare("INSERT INTO imagenes_pregunta (pregunta_id, ruta_imagen) VALUES (?, ?)");
                    $stmt->execute([$pregunta_id, $target_file]);
                } else {
                    throw new Exception("No se pudo mover la imagen '$file_name' al servidor.");
                }
            }
        }
    }

    // Insertar las opciones de respuesta
    if ($tipo_pregunta === 'multiple_choice' || $tipo_pregunta === 'respuesta_unica') {
        foreach ($opciones as $key => $opcion) {
            $correcta = isset($correctas[$key]) ? 1 : 0;
            $stmt = $conn->prepare("INSERT INTO opciones_pregunta (pregunta_id, texto_opcion, es_correcta) VALUES (?, ?, ?)");
            $stmt->execute([$pregunta_id, $opcion, $correcta]);
        }
    }

    // Confirmar transacción
    $conn->commit();

    $_SESSION['alerta'] = ['tipo' => 'success', 'mensaje' => 'Pregunta registrada exitosamente.'];
    header('Location: ../pages/listar_preguntas.php');
} catch (PDOException $e) {
    // En caso de error, revertir la transacción
    $conn->rollBack();
    $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'Error al registrar la pregunta.'];
    header('Location: ../pages/registrar_pregunta.php');
} catch (Exception $e) {
    // En caso de error general (validaciones de imagen)
    $conn->rollBack();
    $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => $e->getMessage()];
    header('Location: ../pages/registrar_pregunta.php');
}
?>