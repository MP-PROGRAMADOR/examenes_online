<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
  }

  /* NAVBAR */
  .navbar {
    height: 56px;
    background-color: var(--navbar-bg) !important;
    color: var(--text-color);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);
  }

  .navbar .navbar-brand,
  .navbar .text-white {
    color: var(--text-color) !important;
  }

  .navbar .btn-outline-light {
    border-color: var(--primary);
    color: var(--primary);
  }

  .navbar .btn-outline-light:hover {
    background-color: var(--primary-light);
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

  /* MAIN CONTENT */
  .main-content {
    margin-left: var(--sidebar-width);
    padding: 1.5rem;
    padding-top: 56px;
    height: calc(100vh - 56px);
    overflow-y: auto;
    background-color: var(--main-bg);
    transition: margin-left 0.3s ease;
  }

  .main-content.collapsed {
    margin-left: var(--sidebar-collapsed-width);
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
</style>

 
</head>
<body>

  <!-- Cambios en el NAVBAR -->
<nav class="navbar navbar-expand-lg bg-primary navbar-dark fixed-top px-3">
  <div class="container-fluid">
    <button class="navbar-toggler me-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
      <span class="navbar-toggler-icon"></span>
    </button>

    <a class="navbar-brand d-flex align-items-center gap-2" href="#">
      <i class="bi bi-shield-shaded"></i> Entidad de Tráfico
    </a>

    <div class="collapse navbar-collapse justify-content-end" id="navbarContent">
      <ul class="navbar-nav mb-2 mb-lg-0 align-items-center gap-2">
        <li class="nav-item text-white">
          <i class="bi bi-person-circle"></i> Juan Pérez (admin)
        </li>
        <li class="nav-item">
          <a href="/" class="btn btn-outline-white btn-sm"><i class="bi bi-house"></i></a>
        </li>
        <li class="nav-item">
          <a href="logout.php" class="btn btn-outline-white btn-sm"><i class="bi bi-box-arrow-right"></i> Salir</a>
        </li>
      </ul>
    </div>
  </div>
</nav>


  <!-- Sidebar -->
  <div id="sidebar" class="sidebar">
    <a href="#" class="nav-link"><i class="bi bi-house-door"></i><span class="link-text">Inicio</span></a>

    <div class="section-title">Gestión Académica</div>
    <a href="#" class="nav-link"><i class="bi bi-people"></i><span class="link-text">Estudiantes</span></a>
    <a href="#" class="nav-link"><i class="bi bi-card-list"></i><span class="link-text">Exámenes</span></a>
    <a href="#" class="nav-link"><i class="bi bi-ui-checks"></i><span class="link-text">Asignar Preguntas</span></a>
    <a href="#" class="nav-link"><i class="bi bi-check-circle"></i><span class="link-text">Respuestas</span></a>
    <a href="#" class="nav-link"><i class="bi bi-send"></i><span class="link-text">Correos Enviados</span></a>

    <div class="section-title">Contenido</div>
    <a href="#" class="nav-link"><i class="bi bi-collection"></i><span class="link-text">Preguntas</span></a>
    <a href="#" class="nav-link"><i class="bi bi-card-image"></i><span class="link-text">Imágenes</span></a>
    <a href="#" class="nav-link"><i class="bi bi-list-ul"></i><span class="link-text">Opciones</span></a>
    <a href="#" class="nav-link"><i class="bi bi-tags"></i><span class="link-text">Categorías</span></a>

    <div class="section-title">Configuración</div>
    <a href="#" class="nav-link"><i class="bi bi-bank"></i><span class="link-text">Escuelas</span></a>
    <a href="#" class="nav-link"><i class="bi bi-person-gear"></i><span class="link-text">Usuarios</span></a>
    <a href="#" class="nav-link"><i class="bi bi-shield-lock"></i><span class="link-text">Roles</span></a>
  </div>

  <!-- Overlay para móviles -->
  <div id="overlay" class="overlay d-none"></div>

  <!-- Main content -->
  <div id="main" class="main-content">
    <h1 class="mb-4">Panel de Control</h1>
    <p class="lead">Bienvenido al sistema de evaluación en línea de la Entidad de Tráfico de Guinea Ecuatorial.</p>
  </div>

  <!-- Bootstrap Bundle JS (necesario para componentes interactivos) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- MDB JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.js"></script>

  <script>
    const sidebar = document.getElementById("sidebar");
    const hamburger = document.getElementById("hamburger");
    const overlay = document.getElementById("overlay");
    const main = document.getElementById("main");

    function toggleSidebar() {
      const isMobile = window.innerWidth <= 768;
      if (isMobile) {
        sidebar.classList.toggle("show");
        overlay.classList.toggle("d-none");
      } else {
        sidebar.classList.toggle("collapsed");
        main.classList.toggle("collapsed");
      }
    }

    hamburger.addEventListener("click", toggleSidebar);
    overlay.addEventListener("click", () => {
      sidebar.classList.remove("show");
      overlay.classList.add("d-none");
    });
  </script>
</body>
</html>
