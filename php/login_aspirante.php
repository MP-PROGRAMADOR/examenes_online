<?php
// Configuración de la base de datos
include '../conexion/conexion.php'; // Conexión a la BD
 

// Obtener datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST["username"]) === "" && isset( $_POST["password"]) === "") {
        
    }


    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Consultar la base de datos
    $sql = "SELECT * FROM aspirantes WHERE username = '$username'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        // Verificar contraseña
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Autenticación exitosa
            echo json_encode(['success' => true, 'message' => 'Autenticación exitosa.']);
        } else {
            // Contraseña incorrecta
            echo json_encode(['success' => false, 'message' => 'Credenciales incorrectas.']);
        }
    } else {
        // Usuario no encontrado
        echo json_encode(['success' => false, 'message' => 'Credenciales incorrectas.']);
    }
    
    $conn->close();
}else{
    die("Acceso no autorizado.");
}
?>

