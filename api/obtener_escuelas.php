

<?php
// Encabezados para permitir acceso desde el frontend
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

include_once('../includes/conexion.php');

try { 
    // Consulta para obtener todas las escuelas
    $stmt = $pdo->prepare("SELECT * FROM escuelas_conduccion ORDER BY nombre ASC");
    $stmt->execute();
    
    // Obtener resultados como arreglo asociativo
    $escuelas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if($escuelas){
        
        echo json_encode([
            'status' => true,
            'message' => 'Escuelas cargadas correctamente',
           'data' => $escuelas
        ]);
        exit;
    }else{
        echo json_encode([
            'status' => false,
            'message' => 'No se encontraron escuelas',
           'data' => 'sin escuelas'
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
