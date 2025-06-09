<?php
require_once '../includes/conexion.php';
header('Content-Type: application/json');

$estudiante_id = isset($_GET['estudiante_id']) ? (int) $_GET['estudiante_id'] : 0;

if ($estudiante_id <= 0) {
    echo json_encode(['status' => false,  'message' => 'ID no válido para la categoria']);
    exit;
}

$sql = "SELECT 
    ec.id, 
    ec.categoria_id, 
    c.nombre AS categoria,
    CONCAT(e.nombre, ' ', e.apellidos) AS estudiante,
    e.fecha_nacimiento AS edad,
    ec.estado, 
    e.id AS estudiante_id,
    ec.fecha_asignacion,
    (SELECT COUNT(*) FROM estudiante_categorias WHERE estudiante_id = ec.estudiante_id) AS total
FROM estudiante_categorias ec
JOIN categorias c ON ec.categoria_id = c.id
JOIN estudiantes e ON ec.estudiante_id = e.id
WHERE ec.estudiante_id = ?

            ";
$stmt = $pdo->prepare($sql);
$stmt->execute([$estudiante_id]);
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
if($categorias){
    echo json_encode(['status' => true, 'message' => 'categorias disponibles para ', 'data' => $categorias]);
    exit;
}else{
    echo json_encode(['status' => false, 'message' => 'Sin categorias disponibles para ', 'data' => $categorias]);
exit;
}




 