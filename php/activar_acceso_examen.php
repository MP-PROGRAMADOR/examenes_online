<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../config/conexion.php';
$conn = $pdo->getConexion();

header('Content-Type: application/json');
// Obtener los datos JSON enviados por fetch
$data = json_decode(file_get_contents('php://input'), true);

// Verificar que se ha recibido el ID y el nuevo estado
if (isset($data['id']) && isset($data['acceso_habilitado'])) {
    $id = $data['id'];
    $acceso_habilitado = $data['acceso_habilitado'];

    try {
        // Preparar la consulta para actualizar el estado
        $stmt = $conn->prepare("UPDATE examenes_estudiantes SET acceso_habilitado = :acceso_habilitado WHERE id = :id");
        $stmt->bindParam(':acceso_habilitado', $acceso_habilitado, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Si la actualización fue exitosa, devolver respuesta
            echo json_encode(['success' => true]);
        } else {
            // Si hubo un error en la ejecución de la consulta
            echo json_encode(['success' => false, 'error' => 'Error en la actualización de la base de datos.']);
        }
    } catch (Exception $e) {
        // Capturar y mostrar errores de SQL u otros problemas
        echo json_encode(['success' => false, 'error' => 'Excepción al ejecutar la consulta: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Faltan parámetros en la solicitud.']);
}
?>
