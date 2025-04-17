    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
      <h5>Panel</h5>
      <ul class="nav flex-column">

        <li class="nav-item">
          <a href="../admin/index.php" class="nav-link active">
            <i class="bi bi-house-door"></i> Dashboard
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="collapse" href="#gestionAcademica" role="button" aria-expanded="false">
            <i class="bi bi-book"></i> Gestión Académica <i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <div class="collapse submenu" id="gestionAcademica">
            <a href="../admin/categorias.php" class="nav-link">Categorías</a>
            <a href="../admin/examenes.php" class="nav-link">Exámenes</a>
            <a href="../admin/preguntas.php" class="nav-link">Preguntas</a>
          </div>
        </li>

        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="collapse" href="#gestionUsuarios" role="button" aria-expanded="false">
            <i class="bi bi-people"></i> Gestión de Usuarios <i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <div class="collapse submenu" id="gestionUsuarios">
            <a href="../admin/usuarios.php" class="nav-link">Usuarios</a>
            <a href="../admin/estudiantes.php" class="nav-link">Estudiantes</a>
          </div>
        </li>

        <li class="nav-item">
          <a href="../admin/escuelas.php" class="nav-link">
            <i class="bi bi-building"></i> Escuelas
          </a>
        </li>

        <li class="nav-item">
          <a href="ajustes.html" class="nav-link">
            <i class="bi bi-gear"></i> Ajustes
          </a>
        </li>

        <li class="nav-item">
          <a href="../login/logout.php" class="nav-link">
            <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
          </a>
        </li>

      </ul>
    </div>
