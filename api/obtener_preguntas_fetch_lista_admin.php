<?php
// api/obtener_preguntas.php
require '../includes/conexion.php'; // Asegúrate de que esta ruta sea correcta

header('Content-Type: application/json');

$response = [
    'status' => false,
    'message' => 'Error al obtener preguntas.',
    'data' => [],
    'totalRegistros' => 0
];

try {
    // La consulta es sencilla: seleccionar todas las preguntas
    // Puedes ordenar por ID o por la columna que prefieras
    $sql = "SELECT id, texto, tipo, tipo_contenido, activa, creado_en FROM preguntas ORDER BY id ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $preguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $response = [
        'status' => true,
        'message' => 'Preguntas obtenidas exitosamente.',
        'data' => $preguntas,
        'totalRegistros' => count($preguntas) // El total es la cantidad de datos que se enviaron
    ];

} catch (PDOException $e) {
    $response['message'] = "Error de base de datos: " . $e->getMessage() . " (SQLSTATE: " . $e->getCode() . ")";
    error_log("PDO Error en obtener_preguntas.php: " . $e->getMessage());
} catch (Exception $e) {
    $response['message'] = "Error inesperado: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    error_log("Error general en obtener_preguntas.php: " . $e->getMessage());
}

echo json_encode($response);
?>