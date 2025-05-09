<?php


require '../config/conexion.php';

$conn = $pdo->getConexion();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim(htmlspecialchars($_POST['nombre']));
    $telefono = trim(htmlspecialchars($_POST['telefono']));
    $direccion = trim(htmlspecialchars($_POST['direccion']));
    
    try {
        $sql = "INSERT INTO escuelas_conduccion (nombre, telefono, direccion) VALUES (:nombre, :telefono, :direccion)";
        $stmt = $conn->prepare($sql);
        
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':telefono', $telefono, PDO::PARAM_STR);
        $stmt->bindParam(':direccion', $direccion, PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            $alerta = [
                'tipo' => 'success',
                'mensaje' => "✅ La escuela fue registrada correctamente."
            ];
            header('location: ../admin/escuelas.php');
        } else {
            echo "Error al registrar el usuario.";
        }
    } catch (PDOException $e) {
        error_log("Error en la inserción: " . $e->getMessage());
        $alerta = [
            'tipo' => 'error',
            'mensaje' => " Ocurrió un error. Por favor, inténtelo de nuevo más tarde."
        ];
        header('location: ../admin/escuelas.php');
        
    }
}
?>