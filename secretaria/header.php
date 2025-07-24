<?php
// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

 
// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
  header('location: ../index.php');
  exit();
}

// Extraer datos del usuario
$rol = $_SESSION['usuario']['rol'];
$nombre = htmlspecialchars($_SESSION['usuario']['nombre'], ENT_QUOTES, 'UTF-8');
//$correo = htmlspecialchars($_SESSION['usuario']['email'], ENT_QUOTES, 'UTF-8');

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Estudiantes - Entidad de Tráfico</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
   

    <script src="../css/sweetalert2@11.js"></script>
    <style>
        /* --- CSS Adicional / Modificaciones para un diseño sin sidebar --- */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            padding-top: 70px; /* Ajusta este valor para que el contenido no quede debajo de la navbar fija */
            background-color: #f8f9fa; /* Color de fondo general */
        }

        /* Elimina los estilos del sidebar antiguo */
        .wrapper {
            display: flex;
            width: 100%; /* El wrapper ahora ocupa todo el ancho */
        }

        /* Oculta completamente el sidebar */
        #sidebar {
            display: none !important;
        }

        /* Asegura que el contenido principal ocupe todo el ancho disponible */
        .main-content {
            flex-grow: 1; /* Permite que el contenido principal ocupe el espacio restante */
            width: 100%; /* Ocupa todo el ancho */
            padding: 1.5rem; /* Añade un padding para el contenido */
            transition: all 0.3s ease;
        }

        /* Estilos para la barra de navegación personalizada */
        .navbar-dark {
            background-color: #007bff; /* Color primario de Bootstrap */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: bold;
        }

        .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.75); /* Color de enlaces normal */
            transition: color 0.3s ease;
        }

        .navbar-nav .nav-link.active,
        .navbar-nav .nav-link:hover {
            color: #fff; /* Color de enlaces activo/hover */
        }

        .user-info-card {
            background-color: rgba(255, 255, 255, 0.1);
        }

        /* --- Ajustes para la tabla responsiva --- */
        /* Asegura que la tabla tenga desplazamiento horizontal en pantallas pequeñas */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch; /* Mejora el scroll en iOS */
        }

        /* Estilos para que algunas columnas se oculten en tamaños específicos */
        /* Ya tienes esto en tu HTML con d-none d-lg-table-cell, etc. */
        /* Puedes añadir más reglas aquí si quieres ocultar más columnas en otros breakpoints */

        /* Estilos generales para el card-header */
        .card-header.bg-gradient-primary {
            background: linear-gradient(45deg, #007bff, #0056b3); /* Gradiente de color */
            color: white;
        }

        /* Otros estilos existentes que quieres mantener */
        .animate-button {
            transition: all 0.3s ease;
        }
        .animate-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top px-4">
        <div class="container-fluid">
            <a class="navbar-brand mb-0 h1" href="#">
                <i class="bi bi-shield-shaded me-1"></i>Entidad de Tráfico
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#mainNavbarCollapse" aria-controls="mainNavbarCollapse" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbarCollapse">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="../secretaria/index.php">
                            <i class="bi bi-house-door me-1"></i>Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../secretaria/estudiantes.php">
                            <i class="bi bi-people me-1"></i>Estudiantes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../secretaria/resultados.php">
                            <i class="bi bi-card-list me-1"></i>Exámenes
                        </a>
                    </li>
                    
                </ul>

                <div class="d-flex align-items-center gap-3 flex-wrap p-3 rounded-3 user-info-card mt-3 mt-lg-0">
                    <div class="d-flex align-items-center gap-2 position-relative">
                        <i class="bi bi-person-circle fs-3 text-white"></i>
                        <span class="text-white fw-semibold text-truncate" style="max-width: 180px;">
                            <?= htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                        <span class="btn btn-outline-light btn-sm d-flex align-items-center gap-1 px-3 shadow-sm">
                            (<?= htmlspecialchars($rol, ENT_QUOTES, 'UTF-8'); ?>)
                        </span>
                    </div>
                    <a href="../logout.php"
                        class="btn btn-outline-light btn-sm d-flex align-items-center gap-1 px-3 shadow-sm logout-button">
                        <i class="bi bi-box-arrow-right fs-5"></i> Cerrar
                    </a>
                </div>
            </div>
        </div>
    </nav>
<div id="toast-container" class="position-fixed top-0 start-50 translate-middle-x p-3"
      style="z-index: 1060; max-width: 90%; width: 400px;"></div>