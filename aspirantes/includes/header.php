<?php
session_start();

// Verifica que el estudiante esté logueado
if (!isset($_SESSION['estudiante_id'])) {
    header("Location: index.php");
    exit;
}
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
        <a class="navbar-brand" href="#">🎓 Autoescuela | Estudiante</a>
        <div class="ms-auto d-flex align-items-center">
            <span class="navbar-text me-3">
                Bienvenido, <strong><?php echo htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']); ?></strong>
            </span>
            <a href="cerrar_sesion.php" class="btn btn-sm btn-outline-light">Cerrar sesión</a>
        </div>
    </div>
</nav>

<!-- Espaciado por navbar fija -->
<div style="height: 70px;"></div>

<!-- Contenido principal -->
<div class="container mt-5 pt-3">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-body">
                    <h3>Panel del Estudiante</h3>
                    <p>Bienvenido al panel de estudiante. Desde aquí podrás gestionar tu información.</p>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
