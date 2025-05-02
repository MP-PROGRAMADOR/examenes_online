<?php
session_start();
include '../config/conexion.php'; // Asegúrate de que este archivo retorna $pdo

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = trim($_POST['codigo']);

    if (empty($codigo)) {
        $_SESSION['error'] = "El código de acceso es obligatorio.";
        header("Location: ../aspirantes/index.php");
        exit();
    }

    try {
        $conn = $pdo->getConexion(); // O directamente $pdo si no usas clase
        $stmt = $conn->prepare("SELECT * FROM estudiantes WHERE codigo_registro_examen = :codigo");
        $stmt->bindParam(':codigo', $codigo);
        $stmt->execute();

        $estudiante = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($estudiante) {
            $_SESSION['estudiante_id'] = $estudiante['id'];
            $_SESSION['nombre'] = $estudiante['nombre'];
            $_SESSION['apellido'] = $estudiante['apellido'];
            $_SESSION['codigo'] = $codigo;
            header("Location: ../aspirantes/aspirante.php"); // Página tras login exitoso
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
    header("Location: ../aspirantes/index.php");
    exit();
}
