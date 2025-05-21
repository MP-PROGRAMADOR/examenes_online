

<?php
// Encabezados para permitir acceso desde el frontend
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

include_once('../includes/conexion.php');

try { 
    // Consulta para obtener todas las estudiantes
    $stmt = $pdo->prepare("SELECT * FROM estudiantes ORDER BY nombre ASC");
    $stmt->execute();
    
    // Obtener resultados como arreglo asociativo
    $estudiantes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if($estudiantes){
        
        echo json_encode([
            'status' => true,
            'message' => 'Estudiantes cargadas correctamente',
           'data' => $estudiantes
        ]);
        exit;
    }else{
        echo json_encode([
            'status' => false,
            'message' => 'No se encontraron estudiantes',
           'data' => 'Sin estudiantes'
        ]);
        exit;

    }
    // Devolver JSON

} catch (PDOException $e) {
    // En caso de error, enviar un mensaje con error 500
    http_response_code(500);
    echo json_encode([ 
        'status' => false ,
         'message' => 'Error al conectar con la base de datos o al ejecutar la consulta.'
        ]);
}
?>
