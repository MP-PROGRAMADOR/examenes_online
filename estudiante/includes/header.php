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
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f2f4f6;
        }

        .navbar-custom {
            background-color: #0d6efd;
        }

        .navbar-brand,
        .nav-link,
        .navbar-text {
            color: #ffffff !important;
        }

        .btn-outline-light:hover {
            background-color: #ffffff;
            color: #2c3e50 !important;
        }

        /* ---------------------POLITICAS------------------------------------- */
        .header-shadow {
            box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075);
        }

        .main-section {
            padding: 40px 0;
        }

        .info-card {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 30px;
            background-color: white;
        }

        .info-card h2 {
            color: #007bff;
            font-weight: bold;
            margin-bottom: 20px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }

        .info-card h3 {
            color: #28a745;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .info-card p {
            line-height: 1.7;
            color: #6c757d;
        }

        .important-note {
            background-color: #fff3cd;
            border: 1px solid #ffeeba;
            color: #85640c;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }

        .btn-start-exam {
            background-color: #28a745;
            border-color: #28a745;
            color: white;
            padding: 12px 25px;
            font-size: 1.1em;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .btn-start-exam:hover {
            background-color: #1e7e34;
            border-color: #1e7e34;
        }

        .back-link {
            display: block;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        /* 
        
--------------------------------

*/
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="aspirante.php">
                <i class="bi bi-mortarboard-fill me-2 fs-4"></i> 
                CÓDIGO DE ACCESO: <strong class="px-2"><?= htmlspecialchars($codigo) ?></strong>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContenido">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarContenido">
                <ul class="navbar-nav mb-2 mb-lg-0 me-3">
                    <li class="nav-item">
                        <a class="nav-link" href="aspirante.php">
                            <i class="bi bi-house-door-fill me-1"></i>Inicio
                        </a>
                    </li>
                </ul>
                <span class="navbar-text me-3">
                    <i class="bi bi-person-circle me-1"></i>
                    Bienvenido, <strong><?= strtoupper(htmlspecialchars($nombre)) . ' ' . strtoupper(htmlspecialchars($apellido)) ?></strong>
                </span>
                <a href="cerrar_sesion.php" class="btn btn-sm btn-outline-light">
                    <i class="bi bi-box-arrow-right me-1"></i> Cerrar sesión
                </a>
            </div>
        </div>
    </nav>

    <!-- Espacio para navbar fija -->
    <div style="height: 80px;"></div>
