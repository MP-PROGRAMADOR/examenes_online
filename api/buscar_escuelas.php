<?php
require_once '../includes/conexion.php'; // Asegúrate de que tu conexión PDO esté configurada aquí
header('Content-Type: application/json');

try {
    // Parámetros de paginación
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';

    // Asegurarse de que los valores de paginación sean válidos
    if ($page <= 0) $page = 1;
    if ($limit <= 0) $limit = 10; // Valor por defecto

    $offset = ($page - 1) * $limit;

    // Construir la consulta SQL
    $sql = "SELECT * FROM escuelas_conduccion";
    $countSql = "SELECT COUNT(*) AS total FROM escuelas_conduccion";
    $params = [];

    // Añadir condiciones de búsqueda si hay un término de búsqueda
    if (!empty($search)) {
        $sql .= " WHERE nombre LIKE ? OR telefono LIKE ? OR director LIKE ? OR nif LIKE ? OR ciudad LIKE ? OR correo LIKE ? OR pais LIKE ? OR ubicacion LIKE ? OR numero_registro LIKE ?";
        $countSql .= " WHERE nombre LIKE ? OR telefono LIKE ? OR director LIKE ? OR nif LIKE ? OR ciudad LIKE ? OR correo LIKE ? OR pais LIKE ? OR ubicacion LIKE ? OR numero_registro LIKE ?";
        $searchTerm = '%' . $search . '%';
        $params = array_fill(0, 9, $searchTerm); // 9 campos para la búsqueda
    }

    // Añadir paginación
    $sql .= " LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;

    // 1. Obtener el total de registros (para la paginación)
    $stmtCount = $pdo->prepare($countSql);
    $stmtCount->execute(array_slice($params, 0, count($params) - 2)); // Excluir LIMIT y OFFSET para el COUNT
    $totalRecords = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];

    // 2. Obtener los datos de las escuelas para la página actual
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $escuelas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calcular el total de páginas
    $totalPages = ceil($totalRecords / $limit);

    echo json_encode([
        'status' => true,
        'escuelas' => $escuelas,
        'currentPage' => $page,
        'perPage' => $limit,
        'totalRecords' => $totalRecords,
        'totalPages' => $totalPages
    ]);

} catch (PDOException $e) {
    error_log("Error en la consulta de obtener_escuelas.php: " . $e->getMessage());
    echo json_encode(['status' => false, 'message' => 'Error en el servidor al obtener las escuelas.']);
} catch (Exception $e) {
    echo json_encode(['status' => false, 'message' => $e->getMessage()]);
}