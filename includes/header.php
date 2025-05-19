  <?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario'])) {
     header('location: ../index.php');
} 
 
$rol = $_SESSION['usuario']['rol'];
$nombre = $_SESSION['usuario']['nombre'];
$correo = $_SESSION['usuario']['email'];
 
 
?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" /> 
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Dashboard | Entidad de Tráfico</title>
  <!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Icons (opcional) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<!-- Material Design for Bootstrap 5 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.css" rel="stylesheet" />

  <style>
  
:root {
  --sidebar-width: 250px;
  --sidebar-collapsed-width: 70px;

  /* Colores vivos estilo Material Design */
  --primary: #1976D2;           /* Azul Material Design */
  --primary-light: #BBDEFB;     /* Azul claro */
  --primary-dark: #0D47A1;      /* Azul más intenso */
  --text-color: #ffffff;        /* Blanco */
  --sidebar-bg: #1565C0;        /* Azul medio */
  --navbar-bg: #1976D2;
  --main-bg: #f5f5f5;
  --border-color: #e0e0e0;
}


  body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background-color: var(--main-bg);
     overflow-x: hidden;
  }

/* Navbar fijo */
.navbar {
  height: 56px;
  background-color: var(--navbar-bg, #343a40) !important;
  color: var(--text-color, #fff);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);
  z-index: 1040;
}

/* Ajustes para el botón sidebar */
#hamburger {
  cursor: pointer;
}

/* Asegurar que los íconos y botones no se superpongan */
.navbar-toggler {
  color: #fff;
  z-index: 1050;
}

/* Fondo al contenido colapsado (derecha del navbar en móviles) */
#navbarUserCollapse {
  
  padding: 10px;
  border-radius: 0.5rem;
}

/* Truncar texto de usuario */
.navbar .text-truncate {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}


  /* SIDEBAR */
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
@media (min-width: 769px) {
  .main-content {
    margin-left: var(--sidebar-width);
  }

  .sidebar.collapsed ~ .main-content {
    margin-left: var(--sidebar-collapsed-width);
  }
}
@media (max-width: 768px) {
  .sidebar {
    left: -100%; /* oculto inicialmente */
    transition: left 0.3s ease;
  }

  .sidebar.show {
    left: 0;
    z-index: 1040;
  }

  .main-content {
    margin-left: 0 !important;
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

  /* MAIN CONTENT */
  .main-content { 
    box-sizing: border-box;
    margin-left: 120px;
    padding: 1.5rem;
    padding-top: 56px;
    height: calc(100vh - 6px);
    overflow-y: auto;
    background-color: var(--main-bg);
    transition: margin-left 0.3s ease; 
  }

  .main-content.collapsed {
    margin-left: 0px;
  }
 

  /* TOGGLE */
  .sidebar-toggle {
    cursor: pointer;
    color: var(--primary);
  }

  @media (max-width: 768px) {
    .sidebar {
      left: -250px;
    }

    .sidebar.show {
      left: 0;
    }

    .main-content,
    .main-content.collapsed {
      margin-left: 0;
    }

    .overlay {
      display: block;
      position: fixed;
      top: 56px;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: rgba(0, 0, 0, 0.3);
      z-index: 1020;
      opacity: 0;
      visibility: hidden;
      transition: opacity 0.3s ease;
    }

    .overlay.show {
      opacity: 1;
      visibility: visible;
    }
  }

  /* --Mensaje de alerta de fetch-------- */

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


</style>

 
</head>
<body>
