<?php
// examenes_disponibles.php

// Encabezados para respuesta JSON
header('Content-Type: application/json');

// Conexión a la base de datos
require_once '../includes/conexion.php'; // Ajusta la ruta a tu archivo de conexión

$response = [
    'status' => false,
    'message' => 'Ocurrió un error inesperado.',
    'data' => []
];

// Validar parámetro GET
if (!isset($_GET['estudiante_id']) || !is_numeric($_GET['estudiante_id'])) {
    $response['message'] = 'ID de estudiante no válido.';
    echo json_encode($response);
    exit;
}

$estudiante_id = (int) $_GET['estudiante_id'];

try {
    // Preparar consulta 
    $stmt = $pdo->prepare("
        SELECT e.id, c.nombre AS nombre, c.descripcion, e.total_preguntas, e.codigo_acceso, e.duracion
        FROM examenes e
        JOIN categorias c ON c.id = e.categoria_id
        WHERE e.estudiante_id = ? AND e.estado = 'pendiente'
    ");
    $stmt->execute([$estudiante_id]);
    $examenes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($examenes) {
        $response['status'] = true;
        $response['message'] = 'Exámenes encontrados.';
        $response['data'] = $examenes;
    } else {
        $response['message'] = 'No hay exámenes pendientes asignados.';
    }

} catch (PDOException $e) {
    $response['message'] = 'Error en la base de datos: ' . $e->getMessage();
}

echo json_encode($response);
