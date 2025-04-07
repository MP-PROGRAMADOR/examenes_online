<?php
// -----------------------------------------
// Procesamiento de login del usuario
// -----------------------------------------

// Validar que los datos vienen por POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    // Si no es POST, redirige o muestra error
    die("Acceso denegado.");
}

// 1. Conexión a la base de datos con PDO

// 2. Sanitizar y validar los datos del formulario
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$password = $_POST['password'] ?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Correo electrónico no válido.");
}

if (strlen($password) < 6) {
    die("Contraseña demasiado corta.");
}

try {
    // Incluir archivo de conexión
    require '../config/conexion.php';

    // Obtener la conexión
    $pdo = $pdo->getConexion();
    $sql = "SELECT id, nombre_usuario, email, password FROM usuarios WHERE email = :email LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    // Si el usuario existe
    if ($stmt->rowCount() === 1) {
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar la contraseña
        if (password_verify($password, $usuario['contraseña'])) {
            // Guardar datos en sesión
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];

            // Redirigir al dashboard o página principal
            header('Location: ../dashboard.php');
            exit;
        } else {
            header('Location: ../login.php?error=clave_incorrecta');
            exit;
        }
    } else {
        header('Location: ../login.php?error=usuario_no_encontrado');
        exit;
    }

} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
// 3. Buscar el usuario en la base de datos

// 4. Validar existencia del usuario
if (!$usuario) {
    die("Usuario no encontrado.");
}

// 5. Verificar la contraseña hasheada
if (!password_verify($password, $usuario['password'])) {
    die("Contraseña incorrecta.");
}

// 6. Si todo va bien, redirigir o mostrar éxito
echo "¡Bienvenido, " . htmlspecialchars($usuario['nombre_usuario']) . "!";
// Aquí podrías iniciar sesión más adelante con session_start()
// header("Location: dashboard.php");
?>