<?php
require '../includes/conexion.php';

header('Content-Type: application/json');

$response = [
    'status' => false,
    'message' => 'Error al obtener usuarios.',
    'data' => [],
    'totalRegistros' => 0 // Ahora calcularemos esto en el frontend
];

try {
    // La consulta es mucho más sencilla: seleccionar todos los usuarios
    $sql = "SELECT * FROM usuarios ORDER BY id ASC"; // O el orden que prefieras
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute()) {
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $response = [
            'status' => true,
            'message' => 'Usuarios obtenidos exitosamente.',
            'data' => $usuarios,
            'totalRegistros' => count($usuarios) // El total es la cantidad de datos que se enviaron
        ];
    } else {
        $response['message'] = 'No se pudieron obtener los datos de los usuarios.';
    }

} catch (PDOException $e) {
    $response['message'] = "Error de base de datos: " . $e->getMessage() . " (SQLSTATE: " . $e->getCode() . ")";
    error_log("PDO Error en obtener_usuarios.php (simplicado): " . $e->getMessage());
} catch (Exception $e) {
    $response['message'] = "Error inesperado: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    error_log("Error general en obtener_usuarios.php (simplificado): " . $e->getMessage());
}

echo json_encode($response);
?>