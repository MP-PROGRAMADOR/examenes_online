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
        .stat-card {
            border-left: 5px solid #0d6efd;
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-3px);
        }
        .card-icon {
            font-size: 2.5rem;
            opacity: 0.6;
        }
    </style>
</head>
<body>

<!-- Navbar superior -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">🎓 Autoescuela | Estudiante</a>
        <div class="ms-auto d-flex align-items-center">
            <span class="navbar-text me-3">Bienvenido, <strong>Juan Pérez</strong></span>
            <a href="logout.php" class="btn btn-sm btn-outline-light">Cerrar sesión</a>
        </div>
    </div>
</nav>

<!-- Espaciado por navbar fija -->
<div style="height: 70px;"></div>
