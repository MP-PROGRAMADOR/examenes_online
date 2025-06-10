<?php
require_once '../includes/conexion.php';

header('Content-Type: application/json');

try {
    $sql = "
        SELECT 
            c.nombre AS categoria,
            COUNT(CASE WHEN ec.estado = 'aprobado' THEN 1 END) AS total_aprobados,
            COUNT(ec.id) AS total_inscritos,
            ROUND(
                COUNT(CASE WHEN ec.estado = 'aprobado' THEN 1 END) * 100.0 / COUNT(ec.id),
                2
            ) AS porcentaje_aprobados
        FROM estudiante_categorias ec
        JOIN categorias c ON ec.categoria_id = c.id
        GROUP BY c.id
        ORDER BY porcentaje_aprobados DESC
    ";

    $stmt = $pdo->query($sql);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => true,
        'data' => $data
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'status' => false,
        'message' => 'Error al consultar los balances',
        'error' => $e->getMessage()
    ]);
}
