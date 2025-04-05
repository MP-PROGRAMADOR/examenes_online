<?php
// ==========================================
// ARCHIVO: actualizar_pregunta.php
// Descripción: Procesa la edición de preguntas y sus imágenes asociadas.
// ==========================================

require_once 'conexion.php';

// =======================
// VALIDACIÓN DEL POST
// =======================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Acceso no autorizado.');
}

// =======================
// VALIDACIÓN Y SANITIZACIÓN
// =======================
$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
$texto = isset($_POST['texto_pregunta']) ? trim($_POST['texto_pregunta']) : '';
$tipo = isset($_POST['tipo_pregunta']) ? trim($_POST['tipo_pregunta']) : '';
$contenido = isset($_POST['tipo_contenido']) ? trim($_POST['tipo_contenido']) : '';
$imagenes_eliminar = isset($_POST['eliminar_imagenes']) ? $_POST['eliminar_imagenes'] : [];

if ($id <= 0 || empty($texto) || empty($tipo) || empty($contenido)) {
    die('Datos incompletos o inválidos.');
}

// =======================
// ACTUALIZACIÓN DE DATOS
// =======================
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $usuario, $contrasena);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->beginTransaction();

    // 1. Actualizar datos de la pregunta
    $sql = "UPDATE preguntas SET texto_pregunta = ?, tipo_pregunta = ?, tipo_contenido = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$texto, $tipo, $contenido, $id]);

    // 2. Eliminar imágenes seleccionadas
    if (!empty($imagenes_eliminar)) {
        $inQuery = implode(',', array_fill(0, count($imagenes_eliminar), '?'));

        // Obtener rutas para borrar del servidor
        $stmt = $pdo->prepare("SELECT ruta_imagen FROM imagenes_pregunta WHERE id IN ($inQuery)");
        $stmt->execute($imagenes_eliminar);
        $rutas = $stmt->fetchAll(PDO::FETCH_COLUMN);

        foreach ($rutas as $ruta) {
            if (file_exists($ruta)) {
                unlink($ruta);
            }
        }

        // Eliminar de la base de datos
        $stmt = $pdo->prepare("DELETE FROM imagenes_pregunta WHERE id IN ($inQuery)");
        $stmt->execute($imagenes_eliminar);
    }

    // 3. Subir nuevas imágenes
    if (!empty($_FILES['nuevas_imagenes']['name'][0])) {
        $ruta_base = 'uploads/';
        if (!is_dir($ruta_base)) {
            mkdir($ruta_base, 0777, true);
        }

        foreach ($_FILES['nuevas_imagenes']['tmp_name'] as $key => $tmpName) {
            $nombreOriginal = $_FILES['nuevas_imagenes']['name'][$key];
            $tipoArchivo = $_FILES['nuevas_imagenes']['type'][$key];
            $tamano = $_FILES['nuevas_imagenes']['size'][$key];

            // Validación del archivo
            if ($tmpName && exif_imagetype($tmpName) && $tamano <= 5 * 1024 * 1024) {
                $extension = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
                $nombreArchivo = uniqid('img_') . '.' . $extension;
                $rutaDestino = $ruta_base . $nombreArchivo;

                if (move_uploaded_file($tmpName, $rutaDestino)) {
                    $stmt = $pdo->prepare("INSERT INTO imagenes_pregunta (pregunta_id, ruta_imagen) VALUES (?, ?)");
                    $stmt->execute([$id, $rutaDestino]);
                }
            }
        }
    }

    $pdo->commit();

    // Redirección exitosa
    header("Location: listar_preguntas.php?exito=1");
    exit;

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    die("Error al actualizar: " . $e->getMessage());
}
?>
