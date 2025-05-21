<?php
require '../includes/conexion.php'; // incluir conexión

header('Content-Type: application/json'); // indicar que la respuesta es JSON

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $estado = intval($_POST['estado'] ?? 0);

    if ($id > 0) {
        $sql = "UPDATE preguntas SET activa = :estado WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(['estado' => $estado, 'id' => $id])) {
            echo json_encode([
                
                'status' => true,
                'message' => 'Estado actualizado correctamente.'
            ]);
        } else {
            echo json_encode([
                
                'status' => false,
                'message' => 'Error al actualizar el estado.'
            ]);
        }
    } else {
        echo json_encode([
            
            'status' => false,
            'message' => 'ID inválido.'
        ]);
    }
} else {
    echo json_encode([
        
        'status' => false,
        'message' => 'Petición no válida.'
    ]);
}
