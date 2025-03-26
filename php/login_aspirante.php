<?php
// Configuración de la base de datos
include '../conexion/conexion.php'; // Conexión a la BD

 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Consulta SQL para buscar al usuario
    $sql = "SELECT * FROM aspirantes WHERE username = '$username'";
    $result = $conn->query($sql); 
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verificar contraseña (usando password_verify si hasheaste la contraseña)
        if ($username == $row['username'] ) {
            // Autenticación exitosa
            $_SESSION['username'] = $username;
            header("Location: ../aspirante/realizar.php"); // Redirigir a la página protegida
            exit();
        }  else {
            
            echo "Contraseña incorrecta.  <br>".$password."<br>";
            print_r($row["password"]);
        }
        
    } else {
        echo "Usuario no encontrado.";
    }
}

$conn->close();
?>
