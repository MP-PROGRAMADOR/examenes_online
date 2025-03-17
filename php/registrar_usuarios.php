<?php
include '../conexion/conexion.php'; // Conexión a la BD

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST["nombre"]);
    $edad = trim($_POST["edad"]);
    $tipo_usuario = trim($_POST["tipo_usuario"]);
    $fecha_registro = trim($_POST["fecha_registro"]);
    $centro_procedencia = trim($_POST["centro"]);
    $dip = trim($_POST["dip"]);
    $edad = trim($_POST["edad"]);
   

    // Verificar si el usuario ya está registrado
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE dip = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        die("Error: El usuario con este DIP ya está registrado.");
    }
    $stmt->close();

    // Generar una contraseña única de 10 caracteres (alfanumérica)
    $password_plano = bin2hex(random_bytes(5));

    // Hashear la contraseña antes de guardarla
   

    // Insertar el nuevo usuario en la base de datos
    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, password, tipo_usuario, id_centro, dip, edad) VALUES (?, ?, ?, ?,?,?)");
    $stmt->bind_param("ssssss", $nombre, $password_plano, $tipo_usuario, $centro_procedencia,$dip,$edad);

    if ($stmt->execute()) {
        
        header('Location: ../admin/usuarios.php?mensaje=insertado');
    } else {
        echo "Error al registrar el usuario.";
    }

    $stmt->close();
    $conn->close();
} else {
    die("Acceso no autorizado.");
}
?>
