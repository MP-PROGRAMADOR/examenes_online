<?php
// Incluye tu archivo de conexión a la base de datos
// Asegúrate de que la ruta sea correcta, por ejemplo:
require_once '../includes/conexion.php'; 

header('Content-Type: application/json');

$response = [
    'status' => false,
    'message' => 'Error al obtener estudiantes.',
    'data' => [],
    'total_registros' => 0,
    'total_paginas' => 0,
    'pagina_actual' => 1
];

try {
    // 1. Obtener parámetros de paginación y búsqueda
    // Usamos (int) para asegurar que 'limite' y 'pagina' sean enteros, por seguridad.
    $limite = isset($_GET['limite']) ? (int)$_GET['limite'] : 10;
    $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : ''; // Eliminar espacios en blanco

    $offset = ($pagina - 1) * $limite;

    // 2. Construir la consulta SQL base con JOINs
    // Es crucial usar DISTINCT e.id para evitar filas duplicadas si un estudiante tiene múltiples categorías
    // Esto asegura que cada estudiante aparezca una sola vez en el listado principal.
    $sql_base = "
        FROM
            estudiantes e
        LEFT JOIN
            escuelas_conduccion esc ON e.escuela_id = esc.id
        LEFT JOIN
            estudiante_categorias ec ON e.id = ec.estudiante_id
        LEFT JOIN
            categorias c ON ec.categoria_id = c.id
    ";

    $conditions = [];
    $params = [];

    // 3. Añadir condiciones de búsqueda si hay un término
    if (!empty($busqueda)) {
        $searchTerm = '%' . $busqueda . '%';
        $conditions[] = "(e.nombre LIKE :busqueda_nombre OR 
                           e.apellidos LIKE :busqueda_apellidos OR 
                           e.dni LIKE :busqueda_dni OR 
                           e.email LIKE :busqueda_email OR
                           e.usuario LIKE :busqueda_usuario OR
                           e.Doc LIKE :busqueda_doc OR
                           esc.nombre LIKE :busqueda_escuela OR
                           c.nombre LIKE :busqueda_categoria)";
        // Bindear el mismo término de búsqueda a todos los parámetros de LIKE
        $params[':busqueda_nombre'] = $searchTerm;
        $params[':busqueda_apellidos'] = $searchTerm;
        $params[':busqueda_dni'] = $searchTerm;
        $params[':busqueda_email'] = $searchTerm;
        $params[':busqueda_usuario'] = $searchTerm;
        $params[':busqueda_doc'] = $searchTerm;
        $params[':busqueda_escuela'] = $searchTerm;
        $params[':busqueda_categoria'] = $searchTerm;
    }

    $where_clause = '';
    if (!empty($conditions)) {
        $where_clause = ' WHERE ' . implode(' AND ', $conditions);
    }

    // 4. Contar el total de registros que coinciden con la búsqueda (para la paginación)
    // Contamos DISTINCT e.id para la paginación, igual que para la selección de datos.
    $sql_count = "SELECT COUNT(DISTINCT e.id) AS total " . $sql_base . $where_clause;
    $stmt_count = $pdo->prepare($sql_count);
    // Bindea los parámetros de búsqueda para la consulta de conteo
    foreach ($params as $key => $value) {
        $stmt_count->bindValue($key, $value);
    }
    $stmt_count->execute();
    $total_registros = $stmt_count->fetch(PDO::FETCH_ASSOC)['total'];

    $total_paginas = ceil($total_registros / $limite);

    // Asegurarse de que la página actual no exceda el total de páginas
    if ($pagina > $total_paginas && $total_paginas > 0) {
        $pagina = $total_paginas;
        $offset = ($pagina - 1) * $limite;
    } elseif ($total_paginas == 0) {
        $pagina = 1; // Si no hay registros, la página es 1
        $offset = 0;
    }
    
    // 5. Obtener los estudiantes para la página actual con el filtro de búsqueda
    $sql_data = "
        SELECT DISTINCT
            e.id,
            e.dni,
            e.nombre,
            e.apellidos,
            e.email,
            e.telefono,
            e.fecha_nacimiento,
            e.direccion,
            e.estado,
            e.usuario,
            e.Doc,
            esc.nombre AS escuela_nombre, -- Alias para el nombre de la escuela
            -- Agregamos GROUP_CONCAT para obtener todas las categorías en una sola columna, separadas por coma
            GROUP_CONCAT(DISTINCT c.nombre ORDER BY c.nombre ASC SEPARATOR ', ') AS categoria_nombre
        " . $sql_base . $where_clause . "
        GROUP BY e.id -- Agrupar por estudiante para que GROUP_CONCAT funcione correctamente
        ORDER BY e.apellidos ASC, e.nombre ASC
        LIMIT :limite OFFSET :offset;
    ";

    $stmt_data = $pdo->prepare($sql_data);
    
    // Bind parámetros de búsqueda
    foreach ($params as $key => $value) {
        $stmt_data->bindValue($key, $value);
    }

    // Bind parámetros de paginación
    $stmt_data->bindValue(':limite', $limite, PDO::PARAM_INT);
    $stmt_data->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt_data->execute();

    $estudiantes = $stmt_data->fetchAll(PDO::FETCH_ASSOC);

    // 6. Preparar la respuesta JSON
    $response['status'] = true;
    $response['message'] = 'Estudiantes obtenidos correctamente.';
    $response['data'] = $estudiantes;
    $response['total_registros'] = $total_registros;
    $response['total_paginas'] = $total_paginas;
    $response['pagina_actual'] = $pagina;

} catch (PDOException $e) {
    error_log("Error al listar estudiantes: " . $e->getMessage());
    $response['message'] = 'Error en la base de datos al listar estudiantes: ' . $e->getMessage();
}

echo json_encode($response);
?>