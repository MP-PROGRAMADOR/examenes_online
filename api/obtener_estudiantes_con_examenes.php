<?php
require_once '../includes/conexion.php';
header('Content-Type: application/json');

$response = ['status' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $searchTerm = trim($_GET['search'] ?? '');

        if ($limit <= 0) $limit = 10;
        if ($page <= 0) $page = 1;
        $offset = ($page - 1) * $limit;

        // 1. Contar TOTAL con filtros
        $countSql = "SELECT COUNT(ex.id) AS total 
                     FROM examenes ex 
                     JOIN estudiantes e ON ex.estudiante_id = e.id";
        $params = [];

        if (!empty($searchTerm)) {
            $countSql .= " WHERE e.dni LIKE ? OR e.nombre LIKE ? OR e.apellidos LIKE ? OR ex.codigo_acceso LIKE ?";
            $searchParam = '%' . $searchTerm . '%';
            $params = [$searchParam, $searchParam, $searchParam, $searchParam];
        }

        $stmt = $pdo->prepare($countSql);
        $stmt->execute($params);
        $totalRecords = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        $totalPages = ceil($totalRecords / $limit);

        // 2. Consulta principal unificada
        $sql = "SELECT 
                    ex.id AS ultimo_examen_id, 
                    ex.fecha_asignacion AS ultima_fecha_examen,
                    ex.calificacion AS ultima_calificacion_examen,
                    ex.estado AS ultimo_estado_examen,
                    ex.codigo_acceso,
                    e.dni, 
                    e.nombre AS estudiante_nombre, 
                    e.apellidos, 
                    e.email, 
                    e.telefono,
                    c.nombre AS ultima_categoria_examen
                FROM examenes ex
                JOIN estudiantes e ON ex.estudiante_id = e.id
                JOIN categorias c ON ex.categoria_id = c.id";

        if (!empty($searchTerm)) {
            $sql .= " WHERE e.dni LIKE ? OR e.nombre LIKE ? OR e.apellidos LIKE ? OR ex.codigo_acceso LIKE ?";
        }

        $sql .= " ORDER BY ex.fecha_asignacion DESC LIMIT $limit OFFSET $offset";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $estudiantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $response = [
            'status' => true,
            'estudiantes' => $estudiantes,
            'currentPage' => $page,
            'perPage' => $limit,
            'totalRecords' => $totalRecords,
            'totalPages' => $totalPages
        ];
    } catch (Exception $e) {
        $response['message'] = "Error: " . $e->getMessage();
    }
}
echo json_encode($response);