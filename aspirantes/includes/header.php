<?php
session_start();

// Verificar si hay sesión activa
if (!isset($_SESSION['estudiante'])) {
    header("Location: index.php");
    exit();
}

// Acceder a los datos del estudiante
$estudiante = $_SESSION['estudiante'];
$nombre = $estudiante['nombre'];
$apellido = $estudiante['apellido'];
$codigo = $estudiante['codigo'];
$id_categoria_carne = $estudiante['categoria_carne']; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del Estudiante</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f2f4f6;
        }
        .navbar {
            background-color: #343a40;
        }
        .navbar-brand, .nav-link, .navbar-text {
            color: white !important;
        }
    </style>
</head>
<body>

<!-- Navbar superior -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">🎓 Autoescuela | Estudiante M  </a>
        <div class="ms-auto d-flex align-items-center">
            <span class="navbar-text me-3">
                Bienvenido, <strong><?php echo htmlspecialchars($nombre . ' ' . $apellido); ?> <?php echo htmlspecialchars($id_categoria_carne); ?></strong>
            </span>
            <a href="cerrar_sesion.php" class="btn btn-sm btn-outline-light">Cerrar sesión</a>
        </div>
    </div>
</nav>

<!-- Espaciado por navbar fija -->
<div style="height: 70px;"></div>

<!-- Contenido principal -->
 

</body>
</html>
