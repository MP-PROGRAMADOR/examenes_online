<?php
// Incluir archivo de conexión
require '../../config/conexion.php';

// Obtener la conexión
$conn = $pdo->getConexion();

$nombre = '';
$descripcion = '';
$errores = [];
$mensaje_exito = '';
$mensaje_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar que la conexión a la BD está establecida
    if (!isset($conn)) {
        die("Error de conexión a la base de datos.");
    }

    // Recoger y sanitizar datos de entrada
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');

    $nombre = strtoupper(filter_var($nombre, FILTER_SANITIZE_STRING));
    $descripcion = filter_var($descripcion, FILTER_SANITIZE_STRING);

    // Validar nombre
    if (empty($nombre)) {
        $errores['nombre'] = 'El nombre es obligatorio.';
    } elseif (strlen($nombre) > 50) {
        $errores['nombre'] = 'El nombre no puede tener más de 50 caracteres.';
    }

    // Validar descripción (opcional)
    if (!empty($descripcion) && strlen($descripcion) > 255) {
        $errores['descripcion'] = 'La descripción no puede tener más de 255 caracteres.';
    }

    // Si no hay errores, intentar insertar en la base de datos
    if (empty($errores)) {
        try {
            // Preparar consulta SQL
            $sql = "INSERT INTO categorias_carne (nombre, descripcion) VALUES (:nombre, :descripcion)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $mensaje_exito = 'Categoría creada exitosamente.';
                header('Location: ../admin/categorias.php?mensaje=exito');
                exit();
            } else {
                $mensaje_error = 'Error al guardar la categoría.';
                error_log("Error al insertar categoría: " . print_r($stmt->errorInfo(), true));
            }
        } catch (PDOException $e) {
            if ($e->getCode() === '23000' && strpos($e->getMessage(), 'nombre') !== false) {
                $errores['nombre'] = 'El nombre ya existe.';
            } else {
                $mensaje_error = 'Error de base de datos.';
                error_log("PDOException al insertar categoría: " . $e->getMessage());
            }
        } catch (Exception $e) {
            $mensaje_error = 'Error inesperado: ' . $e->getMessage();
            error_log("Excepción general: " . $e->getMessage());
        }
    }
}
?>
