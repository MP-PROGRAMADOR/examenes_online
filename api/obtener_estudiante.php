<?php
// Incluye tu archivo de conexión a la base de datos.
// Asegúrate de que la ruta sea correcta para tu estructura de directorios.
require_once '../includes/conexion.php'; 

// Establece el encabezado Content-Type para indicar que la respuesta será JSON.
header('Content-Type: application/json');

// Inicializa la estructura de la respuesta.
$response = [
    'status' => false,
    'message' => 'Error desconocido.'
    
];

// Verifica si se proporcionó el ID del estudiante en la URL.
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $response['message'] = 'ID de estudiante no proporcionado.';
    echo json_encode($response);
    exit(); // Termina la ejecución si no hay ID.
}

// Sanitiza y convierte el ID a entero para mayor seguridad.
$estudiante_id = (int)$_GET['id'];

try {
    // Consulta SQL para obtener todos los datos del estudiante.
    // Se realiza un LEFT JOIN con 'escuelas_conduccion' para obtener 'escuela_id'.
    // Se realiza un LEFT JOIN con 'estudiante_categorias' para obtener 'categoria_id'.
    // Nota: Si un estudiante tiene múltiples categorías asignadas en 'estudiante_categorias',
    // esta consulta devolverá la primera 'categoria_id' que encuentre debido al LIMIT 1.
    // Para obtener todas las categorías asignadas, se usa 'obtener_categorias_estudiante.php'.
    $sql = "
        SELECT
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
            e.Doc AS Doc, -- Alias 'Doc' para que coincida con el campo 'numDocEstudiante' en el frontend
            e.escuela_id,
            ec.categoria_id -- Obtiene el ID de una categoría asignada (si existe)
        FROM
            estudiantes e
        LEFT JOIN
            escuelas_conduccion esc ON e.escuela_id = esc.id
        LEFT JOIN
            estudiante_categorias ec ON e.id = ec.estudiante_id
        WHERE
            e.id = :id
        LIMIT 1; -- Limita el resultado a un solo estudiante
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $estudiante_id, PDO::PARAM_INT); // Vincula el ID como un entero.
    $stmt->execute();

    $estudiante = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica si se encontró el estudiante.
    if ($estudiante) {
        $response['status'] = true;
        $response['message'] = 'Estudiante encontrado.';
        $response['data'] = $estudiante;
    } else {
        $response['message'] = 'Estudiante no encontrado.';
    }

} catch (PDOException $e) {
    // Captura cualquier excepción de PDO (errores de base de datos).
    // Registra el error en el log del servidor para depuración.
    error_log("Error al obtener estudiante: " . $e->getMessage());
    $response['message'] = 'Error en la base de datos al obtener estudiante.';
}

// Codifica la respuesta a JSON y la envía al cliente.
echo json_encode($response);
?>