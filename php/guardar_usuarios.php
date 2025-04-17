



<?php
// Incluye el archivo de conexión
 

require '../config/conexion.php';

$conn=$pdo->getConexion();
// Verifica si se están enviando datos por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibe y sanitiza los datos
    $nombre_usuario = htmlspecialchars(trim(strip_tags($_POST['nombre_usuario'])));
    $password = htmlspecialchars(trim(strip_tags($_POST['password'])));
    $email = htmlspecialchars(trim(strip_tags($_POST['email'])));
    $rol = htmlspecialchars(trim(strip_tags($_POST['rol'])));

    // Validaciones
    $error = '';
    if (empty($nombre_usuario) || empty($password) || empty($email) || empty($rol)) {
        $error .= "Todos los campos son obligatorios.<br>";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error .= "El email no es válido.<br>";
    }

    if (!in_array($rol, ['admin', 'docente'])) {
        $error .= "El rol debe ser 'admin' o 'docente'.<br>";
    }

    // Si hay errores, mostrar los errores
    if (!empty($error)) {
        echo "<script>alert(' $error');</script>";
        echo $error;
    } else {
        // Hash del password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Preparar la consulta
        $query = "INSERT INTO usuarios (nombre_usuario, password, email, rol) VALUES (:nombre_usuario, :password, :email, :rol)";
        
        try {
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':nombre_usuario', $nombre_usuario);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':rol', $rol);
            
            if ($stmt->execute()) {
                echo "<script>alert('Registro exitoso con rol: $rol'); window.location.href='../admin/usuarios.php';</script>";
            } else {
                echo "Error al registrar el usuario.";
            }
        } catch (PDOException $e) {
             
            echo "Error: " . $e->getMessage();
        }
    }
} else {
    echo "<script>alert('Método de solicitud no permitido.');</script>";
   
}
$pdo->closeConexion();
?>