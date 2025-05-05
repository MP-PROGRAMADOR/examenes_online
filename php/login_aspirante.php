<?php
session_start();
include '../config/conexion.php'; // Asegúrate de que este archivo retorna $pdo

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = trim($_POST['codigo']);

    // Validación básica
    if (empty($codigo)) {
        $_SESSION['error'] = "El código de acceso es obligatorio.";
        header("Location: ../aspirantes/index.php");
        exit();
    }

    try {
        // Obtener conexión
        $conn = $pdo->getConexion(); // O usar $pdo directamente si es una instancia de PDO

        // Buscar estudiante por código
        $stmt = $conn->prepare("SELECT * FROM estudiantes WHERE codigo_registro_examen = :codigo");
        $stmt->bindParam(':codigo', $codigo, PDO::PARAM_STR);
        $stmt->execute();

        $estudiante = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($estudiante) {
            // Guardar información del estudiante en la sesión
            $_SESSION['estudiante'] = [
                'id' => $estudiante['id'],
                'nombre' => $estudiante['nombre'],
                'apellido' => $estudiante['apellido'],
                'codigo' => $estudiante['codigo_registro_examen'],
                'email' => $estudiante['email'] ?? null // opcional si existe campo email
            ];

            // Redirigir al panel del aspirante
            header("Location: ../aspirantes/aspirante.php");
            exit();
        } else {
            $_SESSION['error'] = "Código incorrecto. Intenta nuevamente.";
            header("Location: ../aspirantes/index.php");
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error de conexión con la base de datos.";
        header("Location: ../aspirantes/index.php");
        exit();
    }
} else {
    // Acceso no válido
    header("Location: ../aspirantes/index.php");
    exit();
}
