<?php
// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluir archivo de conexión
require '../includes/conexion.php';

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
  <meta charset="UTF-8" /> 
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard | Entidad de Tráfico</title>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

  <!-- MDBootstrap -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.css" rel="stylesheet" />





  <!-- Estilos personalizados -->
  <style>
    :root {
      --sidebar-width: 250px;
      --sidebar-collapsed-width: 70px;
      --primary: #1976D2;
      --primary-light: #BBDEFB;
      --primary-dark: #0D47A1;
      --text-color: #ffffff;
      --sidebar-bg: #1565C0;
      --navbar-bg: #1976D2;
      --main-bg: #f5f5f5;
      --border-color: #e0e0e0;
    }

    body {
      display: flex;
      flex-direction: column;
      height: 100vh;
      overflow: hidden;
    }

    .wrapper {
      display: flex;
      flex: 1;
      overflow: hidden;
      padding-top: 50px;
      background-color:rgba(236, 227, 227, 0.65);
    }

    /* .main-content { 
      flex: 1;
      overflow-y: auto;
      padding: 1rem;
      background-color: var(--main-bg);
    } */

   /*  .wrapper {
            display: flex;
            height: 100vh;
            overflow: hidden;
        } */

        /* ---------------- SIDEBAR ---------------- */
        .sidebar {
            width: 250px;
            background-color:var(--sidebar-bg);
            color: #fff;
            box-shadow: 2px 0 5px var(--sidebar-bg);
            overflow-y: auto;
            transition: all 0.3s ease;
            z-index: 1050;
            position: relative;
            padding: 10px;
            scrollbar-width: thin;
            scrollbar-color: var(--sidebar-bg);
        }

        .sidebar::-webkit-scrollbar {
            width: 10px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: var(--sidebar-bg);
            border-radius: 10px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background:var(--sidebar-bg);
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: #334155;
        }

        .sidebar.collapsed {
            width: 80px;
            background-color: var(--sidebar-bg);
        }

        .sidebar.collapsed .link-text {
            display: none; /* ocultar las letras en menu colapsado  */
        }

        .sidebar .nav-link {
            color: #fff;
            transition: all 0.2s;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: rgb(7, 88, 169);
            color: rgb(160, 160, 160);
        }

        .sidebar .nav-link i {
            font-size: 1.2rem;
        }

        .sidebar.collapsed .nav-link span,
        .sidebar.collapsed h5 {
            display: none;
        }

        @media (max-width: 767.98px) {
            .sidebar {
                position: fixed;
                height: calc(100vh - 80px);
                margin-top: 60px;
                left: -250px;
                box-shadow: 2px 0 10px var(--sidebar-bg);
            }

            .sidebar.show {
                left: 0;
            }
        }


        /* ---------------- CONTENIDO PRINCIPAL ---------------- */
        #content {
            flex-grow: 1;
            overflow-y: auto;
            height: calc(100vh - 80px);
            margin-top: 60px;
            /*  height: 100vh; */
            margin-left: 250px;
            padding: 1rem;
            transition: margin-left 0.3s ease;
            /*   border: solid 2px #52a552; */
        }

        #content.collapsed {
            margin-left: 80px;
        }


        @media (max-width: 767.98px) {
            #content {
                margin-left: 0 !important;

            }

            #navContent {
                margin-left: 0 !important;

            }

        }

        body.sidebar-collapsed #navContent {
            left: 0px !important;
        }


    .navbar {
      height: 56px;
      background-color: var(--navbar-bg) !important;
      color: var(--text-color);
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);
      z-index: 1040;
    }

    #hamburger {
      cursor: pointer;
    }

    .navbar-toggler {
      color: #fff;
      z-index: 1050;
    }

    #navbarUserCollapse {
      padding: 10px;
      border-radius: 0.5rem;
    }

    .navbar .text-truncate {
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    .sidebar {
      height: 100vh;
      width: var(--sidebar-width);
      background-color: var(--sidebar-bg);
      position: fixed;
      top: 56px;
      left: 0;
      overflow-y: auto;
      border-right: 1px solid var(--border-color);
      transition: width 0.3s ease;
      z-index: 1030;
    }

    .sidebar.collapsed {
      width: var(--sidebar-collapsed-width);
    }

    .sidebar .nav-link {
      color: var(--text-color);
      padding: 0.75rem 1rem;
      display: flex;
      align-items: center;
      gap: 0.75rem;
      transition: all 0.2s ease;
      border-radius: 0 50px 50px 0;
    }

    .sidebar .nav-link:hover {
      background-color: var(--primary-light);
      padding-left: 1.25rem;
      color: var(--primary);
    }

    .sidebar .nav-link.active {
      background-color: var(--primary-light);
      color: var(--primary);
      font-weight: 500;
    }

    .sidebar .section-title {
      padding: 0.5rem 1rem;
      font-size: 0.75rem;
      color: #9e9e9e;
      text-transform: uppercase;
      margin-top: 1rem;
    }

    .sidebar.collapsed .link-text {
      display: none;
    }

 /*    @media (min-width: 992px) {
      .main-content {
        margin-left: var(--sidebar-collapsed-width);
        transition: margin-left 0.3s ease;
      }

      .main-content {
        margin-left: var(--sidebar-collapsed-width);
      }
    } */

     
    @media (max-width: 768px) {
      .sidebar {
        left: -100%;
        transition: left 0.3s ease;
      }

      .sidebar.show {
        left: 0;
        z-index: 1040;
      }

       

      .overlay {
        position: fixed;
        top: 56px;
        left: 0;
        width: 100%;
        height: calc(100vh - 56px);
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1035;
      }
    }

    

    .sidebar-toggle {
      cursor: pointer;
      color: var(--primary);
    }

    @keyframes fadeSlideIn {
      0% {
        opacity: 0;
        transform: translateY(20px);
      }
      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes fadeSlideOut {
      0% {
        opacity: 1;
        transform: translateY(0);
      }
      100% {
        opacity: 0;
        transform: translateY(-20px);
      }
    }

    .toast.show {
      animation: fadeSlideIn 0.4s ease forwards;
    }

    .toast.hide {
      animation: fadeSlideOut 0.4s ease forwards;
    }

    #toast-container .toast {
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
      border-radius: 0.5rem;
    }

    .modal.fade .modal-dialog {
      transform: translateY(30px);
      opacity: 0;
      transition: transform 0.4s ease, opacity 0.4s ease;
    }

    .modal.fade.show .modal-dialog {
      transform: translateY(0);
      opacity: 1;
    }

    .modal-backdrop.show {
      background-color: rgba(0, 0, 0, 0.7);
    }

    .table-responsive {
      width: 100%;
      overflow-x: auto;
    }

    .table-responsive .table {
      min-width: 600px;
    }
  </style>
</head>
<body>
 