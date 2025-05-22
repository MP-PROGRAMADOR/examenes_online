<?php
session_start();

/* 
CREATE TABLE `estudiantes` (
  `id` int(11) NOT NULL,
  `dni` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `usuario` varchar(100) UNIQUE NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `escuela_id` int(11) DEFAULT NULL,
  `estado` enum('activo','inactivo') DEFAULT 'activo',
  `creado_en` datetime DEFAULT current_timestamp(),
  `apellidos` varchar(250) DEFAULT NULL,
  `direccion` varchar(250) DEFAULT NULL
)
 */

// Verificar si hay sesión activa
if (!isset($_SESSION['estudiante'])) {
    header("Location: index.php");
    exit();
}

// Acceder a los datos del estudiante
$estudiante = $_SESSION['estudiante'];
$nombre = $estudiante['nombre'] . ' ' . $estudiante['apellidos'];
$codigo = $estudiante['usuario'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Panel del Estudiante</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .sidebar {
            min-height: 100vh;
            background: #ffffff;
            border-right: 1px solid #dee2e6;
            transition: all 0.3s ease-in-out;
        }

        .sidebar .nav-link {
            font-size: 0.95rem;
            color: #495057;
            border-radius: 0.375rem;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: #e9f2ff;
            color: #0d6efd;
        }

        .sidebar .bi {
            font-size: 1.2rem;
            margin-right: 0.5rem;
        }

        .navbar {
            background-color: #0d6efd;
        }

        .color {
            background-color: rgb(13, 82, 185);
        }

        .navbar .navbar-brand,
        .navbar .nav-link,
        .navbar .bi {
            color: #fff !important;
        }

        .sidebar h6 {
            font-size: 0.85rem;
            color: #6c757d;
            text-transform: uppercase;
            margin-top: 1rem;
        }

        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }

            #mainContent {
                margin-left: 0;
            }
        }

        @media (min-width: 769px) {
            #mainContent {
                margin-left: 260px;
            }
        }

        .accordion-button {
            transition: all 0.2s ease-in-out;
        }

        .accordion-button:not(.collapsed) {
            background-color: #e9f0ff;
            color: #0d6efd;
        }
    </style>


</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <i class="bi bi-mortarboard-fill me-2 fs-4"></i>
                CÓDIGO DE ACCESO: <strong class="px-2"><?= htmlspecialchars($codigo) ?></strong>
            </a>
            <div class="collapse navbar-collapse" id="navbarEstudiante">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="bi bi-person-circle"></i>
                            <strong><?= strtoupper(htmlspecialchars($nombre)) ?></strong></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-warning" href="logout.php"><i class="bi bi-box-arrow-right"></i> Cerrar
                            sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

 