<nav class="navbar fixed-top">
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container-fluid px-4">

      <!-- Sidebar toggle + Brand -->

      <div class="d-flex align-items-center gap-2">
        <i class="bi bi-list sidebar-toggle fs-4 text-white d-lg-inline" id="hamburger"></i>
        <span class="navbar-brand mb-0 h1 d-none d-sm-inline">
          <i class="bi bi-shield-shaded me-1"></i>Entidad de Tráfico
        </span>
      </div>

      <!-- Navbar toggler for right content -->
      <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
        data-bs-target="#navbarUserCollapse" aria-controls="navbarUserCollapse" aria-expanded="false"
        aria-label="Toggle navigation">
        <i class="bi bi-person-circle fs-4 text-white"></i>
      </button>

      <!-- Right content -->
      <div class="collapse navbar-collapse justify-content-end" id="navbarUserCollapse">
        <div class="d-flex align-items-center gap-3 flex-wrap p-3 rounded-3"
          style="background: #1976D2; backdrop-filter: blur(10px);">
          <div class="d-flex align-items-center gap-2 position-relative">
            <i class="bi bi-person-circle fs-3 text-white"></i>
            <span class="text-white fw-semibold text-truncate" style="max-width: 180px;">
              <?= htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8'); ?>
              ( <?= htmlspecialchars($rol, ENT_QUOTES, 'UTF-8'); ?>)</span>

          </div>
          <a href="../admin/index.php"
            class="btn btn-outline-light btn-sm d-flex align-items-center gap-1 px-3 shadow-sm"
            style="transition: background-color 0.3s ease;">
            <i class="bi bi-house-door-fill fs-5"></i> Home
          </a>
          <a href="../logout.php" class="btn btn-outline-light btn-sm d-flex align-items-center gap-1 px-3 shadow-sm"
            style="transition: background-color 0.3s ease;">
            <i class="bi bi-box-arrow-right fs-5"></i> Cerrar
          </a>


        </div>
      </div>

    </div>
  </nav>
</nav>
<div class="wrapper">
  <!-- Sidebar -->
  <div id="sidebar" class="sidebar">
    <a href="../admin/index.php" class="nav-link"><i class="bi bi-house-door"></i><span
        class="link-text">Inicio</span></a>

    <div class="section-title">Gestión Académica</div>
    <a href="../admin/estudiantes.php" class="nav-link"><i class="bi bi-people"></i><span
        class="link-text">Estudiantes</span></a>
    <a href="../admin/examenes.php" class="nav-link"><i class="bi bi-card-list"></i><span
        class="link-text">Exámenes</span></a>
    <a href="../admin/preguntas.php" class="nav-link"><i class="bi bi-collection"></i><span
        class="link-text">Preguntas</span></a>
    <a href="../admin/escuelas.php" class="nav-link"><i class="bi bi-bank"></i><span
        class="link-text">Escuelas</span></a>
    <a href="../admin/correo.php" class="nav-link"><i class="bi bi-send"></i><span class="link-text">Correos
        Enviados</span></a> 

    <div class="section-title">Configuración</div>
    <a href="../admin/categorias.php" class="nav-link"><i class="bi bi-tags"></i><span
        class="link-text">Categorías</span></a>
    <a href="../admin/usuarios.php" class="nav-link"><i class="bi bi-person-gear"></i><span
        class="link-text">Usuarios</span></a> 
  </div>
  <!-- Overlay para móviles -->
  <div id="overlay" class="overlay d-none"></div>
  <!-- Main content -->
  <div id="main" class="main-content">
    <div id="toast-container" class="position-fixed top-0 start-50 translate-middle-x p-3"
      style="z-index: 1060; max-width: 90%; width: 400px;"></div>