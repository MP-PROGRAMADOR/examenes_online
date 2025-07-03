<?php
require_once '../includes/conexion.php'; // Asegúrate de que tu archivo de conexión PDO es correcto
header('Content-Type: application/json');

$response = ['status' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Recoger y sanear parámetros de paginación y búsqueda
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $searchTerm = trim($_GET['search'] ?? '');

        // Validar límite
        if ($limit <= 0) $limit = 10;
        if ($page <= 0) $page = 1;

        $offset = ($page - 1) * $limit;

        // --- Consulta para contar el total de registros ---
        $countSql = "SELECT COUNT(DISTINCT e.id) AS total_estudiantes
                     FROM estudiantes e
                     JOIN examenes ex ON e.id = ex.estudiante_id";
        $params = [];

        if (!empty($searchTerm)) {
            $countSql .= " WHERE e.dni LIKE ? OR e.nombre LIKE ? OR e.apellidos LIKE ? OR e.email LIKE ? OR e.telefono LIKE ?";
            $searchParam = '%' . $searchTerm . '%';
            $params = [$searchParam, $searchParam, $searchParam, $searchParam, $searchParam];
        }

        $stmt = $pdo->prepare($countSql);
        $stmt->execute($params);
        $totalRecords = $stmt->fetch(PDO::FETCH_ASSOC)['total_estudiantes'];

        $totalPages = ceil($totalRecords / $limit);
        // Asegurarse de que la página actual no excede el total de páginas
        if ($page > $totalPages && $totalPages > 0) {
            $page = $totalPages;
            $offset = ($page - 1) * $limit;
        } elseif ($totalPages == 0) {
            $page = 1;
            $offset = 0;
        }

        // --- Consulta para obtener los estudiantes con sus últimos exámenes ---
        // Usamos una subconsulta para encontrar el examen más reciente de cada estudiante
        $sql = "SELECT e.id AS estudiante_id, e.dni, e.nombre AS estudiante_nombre, e.apellidos, e.email, e.telefono,
                       MAX(ex.fecha_asignacion) AS ultima_fecha_examen,
                       (SELECT c.nombre FROM categorias c JOIN examenes sub_ex ON c.id = sub_ex.categoria_id WHERE sub_ex.estudiante_id = e.id ORDER BY sub_ex.fecha_asignacion DESC LIMIT 1) AS ultima_categoria_examen,
                       (SELECT sub_ex.calificacion FROM examenes sub_ex WHERE sub_ex.estudiante_id = e.id ORDER BY sub_ex.fecha_asignacion DESC LIMIT 1) AS ultima_calificacion_examen,
                       (SELECT sub_ex.estado FROM examenes sub_ex WHERE sub_ex.estudiante_id = e.id ORDER BY sub_ex.fecha_asignacion DESC LIMIT 1) AS ultimo_estado_examen,
                       (SELECT sub_ex.id FROM examenes sub_ex WHERE sub_ex.estudiante_id = e.id ORDER BY sub_ex.fecha_asignacion DESC LIMIT 1) AS ultimo_examen_id
                FROM estudiantes e
                JOIN examenes ex ON e.id = ex.estudiante_id";

        if (!empty($searchTerm)) {
            $sql .= " WHERE e.dni LIKE ? OR e.nombre LIKE ? OR e.apellidos LIKE ? OR e.email LIKE ? OR e.telefono LIKE ?";
        }

        $sql .= " GROUP BY e.id, e.dni, e.nombre, e.apellidos, e.email, e.telefono
                  ORDER BY ultima_fecha_examen DESC
                  LIMIT ? OFFSET ?";

        // Re-generar parámetros para la consulta principal
        $finalParams = $params;
        $finalParams[] = $limit;
        $finalParams[] = $offset;

        $stmt = $pdo->prepare($sql);
        $stmt->execute($finalParams);
        $estudiantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $response = [
            'status' => true,
            'message' => 'Estudiantes con exámenes obtenidos correctamente.',
            'estudiantes' => $estudiantes,
            'currentPage' => $page,
            'perPage' => $limit,
            'totalRecords' => $totalRecords,
            'totalPages' => $totalPages
        ];

    } catch (PDOException $e) {
        error_log("Error PDO en obtener_estudiantes_con_examenes.php: " . $e->getMessage());
        $response['message'] = 'Error de base de datos: ' . $e->getMessage();
    } catch (Exception $e) {
        error_log("Error en obtener_estudiantes_con_examenes.php: " . $e->getMessage());
        $response['message'] = 'Error: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'Método de solicitud no permitido.';
}

echo json_encode($response);
?>