<?php
require_once '../../config/conexion.php';

$pdo = $pdo->getConexion();
// Validar el ID de la imagen
if (!isset($_POST['eliminar_imagen']) || !is_numeric($_POST['eliminar_imagen'])) {
    die("ID de imagen no válido.");
}

$id_imagen = intval($_POST['eliminar_imagen']);

// Obtener la ruta para eliminar del servidor
$stmt = $pdo->prepare("SELECT ruta_imagen FROM imagenes_pregunta WHERE id = ?");
$stmt->execute([$id_imagen]);
$imagen = $stmt->fetch(PDO::FETCH_ASSOC);

if ($imagen) {
    if (file_exists($imagen['ruta_imagen'])) {
        unlink($imagen['ruta_imagen']);
    }

    // Eliminar registro de la base de datos
    $pdo->prepare("DELETE ruta_imagen FROM pregunta WHERE id = ?")->execute([$id_imagen]);
}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
?>
