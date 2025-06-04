  <?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
   require '../includes/conexion.php';
if (!isset($_SESSION['usuario'])) {
     header('location: ../index.php');
} 
 
$rol = $_SESSION['usuario']['rol'];
$nombre = $_SESSION['usuario']['nombre'];
//$correo = $_SESSION['usuario']['correo'];
 
 
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

  /* Establece altura completa para el body y html */
html, body {
  height: 100%;
  margin: 0;
  font-family: 'Segoe UI', sans-serif;
  background-color: var(--main-bg);
  overflow: hidden; /* evita scroll global */
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
  height: 100%; /* usa 100% del espacio disponible */
  overflow-y: auto; /* solo este div tiene scroll vertical */
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

/* MODALES */
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
</style>

 
</head>
<body>
<?php

include_once("../includes/header.php");
include_once("../includes/sidebar.php");
try {
 
    // Consultar todas las categorías
    $sql = "SELECT * FROM categorias";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error en la consulta de categorías: " . $e->getMessage());
    // En caso de error al recuperar categorías, se agrega el mensaje de error

}

?>


<!-- Botón Sidebar (siempre visible) -->

<!-- <div class="d-flex align-items-center gap-2">
<i class="bi bi-list sidebar-toggle fs-4 text-white d-lg-inline" id="hamburger"></i>
<span class="navbar-brand mb-0 h1 d-none d-sm-inline">
<i class="bi bi-shield-shaded me-1"></i>Entidad de Tráfico
</span>
</div> -->

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
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarUserCollapse"
      aria-controls="navbarUserCollapse" aria-expanded="false" aria-label="Toggle navigation">
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
        <a href="../admin/index.php" class="btn btn-outline-light btn-sm d-flex align-items-center gap-1 px-3 shadow-sm"
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

<!-- Sidebar -->
<div id="sidebar" class="sidebar">
  <a href="../admin/index.php" class="nav-link"><i class="bi bi-house-door"></i><span class="link-text">Inicio</span></a>

  <div class="section-title">Gestión Académica</div>
  <a href="../admin/estudiantes.php" class="nav-link"><i class="bi bi-people"></i><span class="link-text">Estudiantes</span></a>
  <a href="../admin/examenes.php" class="nav-link"><i class="bi bi-card-list"></i><span class="link-text">Exámenes</span></a>
  <a href="../admin/preguntas.php" class="nav-link"><i class="bi bi-collection"></i><span class="link-text">Preguntas</span></a>
  <a href="../admin/escuelas.php" class="nav-link"><i class="bi bi-bank"></i><span class="link-text">Escuelas</span></a>
  <a href="../admin/correo.php" class="nav-link"><i class="bi bi-send"></i><span class="link-text">Correos Enviados</span></a>

  <!-- <a href="#" class="nav-link"><i class="bi bi-ui-checks"></i><span class="link-text">Asignar Preguntas</span></a>
  <a href="../admin/preguntas.php" class="nav-link"><i class="bi bi-check-circle"></i><span class="link-text">Respuestas</span></a>
 
  <div class="section-title">Contenido</div>
  
  <a href="#" class="nav-link"><i class="bi bi-card-image"></i><span class="link-text">Imágenes</span></a>
  <a href="#" class="nav-link"><i class="bi bi-list-ul"></i><span class="link-text">Opciones</span></a>
   -->
   
   <div class="section-title">Configuración</div>
   <a href="../admin/categorias.php" class="nav-link"><i class="bi bi-tags"></i><span class="link-text">Categorías</span></a>
  <a href="../admin/usuarios.php" class="nav-link"><i class="bi bi-person-gear"></i><span
      class="link-text">Usuarios</span></a>
 <!--  <a href="#" class="nav-link"><i class="bi bi-shield-lock"></i><span class="link-text">Roles</span></a> -->
</div>

<!-- Overlay para móviles -->
<div id="overlay" class="overlay d-none"></div>
<!-- Main content -->
<div id="main" class="main-content">
  <div id="toast-container" class="position-fixed top-0 start-50 translate-middle-x p-3"
    style="z-index: 1060; max-width: 90%; width: 400px;"></div>



<div class="main-content">
    <div class="container-fluid mt-5">
        <div class="card shadow border-0 rounded-4">

            <!-- ENCABEZADO CON FILTROS Y BUSCADOR -->
            <div class="card-header bg-primary text-white rounded-top-4 px-4 py-3">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">

                    <h5 class="mb-0 d-flex align-items-center">
                        <i class="bi bi-tags-fill me-2"></i>Listado de Categorías
                    </h5>

                    <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-2 w-100 w-md-auto">
                        
                        <div class="search-box position-relative flex-grow-1">
                            <input type="text" class="form-control ps-5" id="customSearch" placeholder="Buscar categoría...">
                            <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <label for="container-length" class="mb-0 text-white fw-medium">Mostrar:</label>
                            <select id="container-length" class="form-select form-select-sm w-auto shadow-sm">
                                <option value="5">5</option>
                                <option value="10" selected>10</option>
                                <option value="15">15</option>
                                <option value="20">20</option>
                                <option value="25">25</option>
                            </select>
                        </div>

                    </div>
                </div>
            </div>

            <!-- TABLA -->
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="container-table" class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th><i class="bi bi-hash"></i> ID</th>
                                <th><i class="bi bi-tag-fill"></i> Nombre</th>
                                <th><i class="bi bi-card-text"></i> Descripción</th>
                                <th><i class="bi bi-person"></i> Edad Mínima</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($categorias)): ?>
                                <?php foreach ($categorias as $categoria): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($categoria['id']) ?></td>
                                        <td><?= htmlspecialchars($categoria['nombre']) ?></td>
                                        <td><?= htmlspecialchars($categoria['descripcion']) ?></td>
                                        <td><?= htmlspecialchars($categoria['edad_minima']) ?> años</td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4">
                                        <div class="alert alert-warning text-center m-0 rounded-0">
                                            <i class="bi bi-exclamation-circle-fill me-2"></i>No hay categorías registradas actualmente.
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
      // Verificamos si ya se cargaron
        if (localStorage.getItem('categoriasCargadas') === 'true') {
            console.log('✅ Categorías ya estaban cargadas.');
            return;
        } 
        const formData = new FormData();
        formData.append('accion', 'cargar_categorias');

        fetch('../api/cargar_categorias.php', {
            method: 'POST',
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                if (data.status) {
                    //mostrarToast('success', data.message);
                   // setTimeout(() => location.reload(), 1200);
                } else {
                    mostrarToast('warning', data.message);
                }
            })
            .catch(error => {
                console.error('Error al cargar categorías:', error);
                mostrarToast('danger', 'Ocurrió un error al cargar las categorias.');
            });
    });

</script>




  </div>
  <!-- Bootstrap Bundle JS (necesario para componentes interactivos) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- MDB JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.js"></script>
<script src="../js/alerta.js"></script>
 
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
