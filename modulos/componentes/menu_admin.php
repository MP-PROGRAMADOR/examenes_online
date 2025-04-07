 <!-- Sidebar -->
 <div class="offcanvas-lg offcanvas-start bg-dark text-white position-relative h-100" id="sidebar">
        <div class="offcanvas-header d-lg-none">
            <h5 class="offcanvas-title">Menú</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column p-0">
            <div class="p-3 border-bottom">
                <h5><i class="bi bi-speedometer2"></i> <span>Admin</span></h5>
            </div>

            <ul class="nav flex-column px-2 overflow-auto">
                <li class="nav-item mt-2">
                    <a class="nav-link text-white <?= $pagina === 'inicio' ? 'active' : '' ?>" href="dashboard.php">
                        <i class="bi bi-house me-2"></i> <span>Inicio</span>
                    </a>
                </li>

                <li class="nav-item mt-3 text-uppercase text-muted small px-3">Gestión Académica</li>

                <li class="nav-item">
                    <a class="nav-link text-white <?= in_array($pagina, ['preguntas', 'examenes', 'respuestas']) ? 'active' : '' ?>"
                        data-bs-toggle="collapse" href="#academicoSubmenu">
                        <i class="bi bi-mortarboard me-2"></i> <span>Académico</span>
                    </a>
                    <div class="collapse <?= in_array($pagina, ['preguntas', 'examenes', 'respuestas']) ? 'show' : '' ?>"
                        id="academicoSubmenu">
                        <ul class="nav flex-column ms-3">
                            <li><a href="examenes.php"
                                    class="nav-link text-white <?= $pagina === 'examenes' ? 'active' : '' ?>">Exámenes</a>
                            </li>
                            <li><a href="preguntas.php"
                                    class="nav-link text-white <?= $pagina === 'preguntas' ? 'active' : '' ?>">Preguntas</a>
                            </li>
                            <li><a href="respuestas.php"
                                    class="nav-link text-white <?= $pagina === 'respuestas' ? 'active' : '' ?>">Respuestas</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-white <?= in_array($pagina, ['escuelas', 'registrar_escuela']) ? 'active' : '' ?>"
                        data-bs-toggle="collapse" href="#escuelasSubmenu">
                        <i class="bi bi-building me-2"></i> <span>Escuelas</span>
                    </a>
                    <div class="collapse <?= in_array($pagina, ['escuelas', 'registrar_escuela']) ? 'show' : '' ?>"
                        id="escuelasSubmenu">
                        <ul class="nav flex-column ms-3">
                            <li><a href="escuelas.php"
                                    class="nav-link text-white <?= $pagina === 'escuelas' ? 'active' : '' ?>">Listado</a>
                            </li>
                            <li><a href="registrar_escuela.php"
                                    class="nav-link text-white <?= $pagina === 'registrar_escuela' ? 'active' : '' ?>">Registrar</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item mt-3 text-uppercase text-muted small px-3">Usuarios y Seguridad</li>

                <li class="nav-item">
                    <a class="nav-link text-white <?= in_array($pagina, ['usuarios', 'roles', 'registrar_usuario']) ? 'active' : '' ?>"
                        data-bs-toggle="collapse" href="#usuariosSubmenu">
                        <i class="bi bi-people me-2"></i> <span>Usuarios</span>
                    </a>
                    <div class="collapse <?= in_array($pagina, ['usuarios', 'roles', 'registrar_usuario']) ? 'show' : '' ?>"
                        id="usuariosSubmenu">
                        <ul class="nav flex-column ms-3">
                            <li><a href="usuarios.php"
                                    class="nav-link text-white <?= $pagina === 'usuarios' ? 'active' : '' ?>">Listado</a>
                            </li>
                            <li><a href="registrar_usuario.php"
                                    class="nav-link text-white <?= $pagina === 'registrar_usuario' ? 'active' : '' ?>">Registrar</a>
                            </li>
                            <li><a href="roles.php"
                                    class="nav-link text-white <?= $pagina === 'roles' ? 'active' : '' ?>">Roles</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-white <?= $pagina === 'auditoria' ? 'active' : '' ?>" href="auditoria.php">
                        <i class="bi bi-shield-check me-2"></i> <span>Auditoría</span>
                    </a>
                </li>

                <li class="nav-item mt-3 text-uppercase text-muted small px-3">Configuración</li>

                <li class="nav-item">
                    <a class="nav-link text-white <?= in_array($pagina, ['categorias_carne', 'parametros_generales']) ? 'active' : '' ?>"
                        data-bs-toggle="collapse" href="#configSubmenu">
                        <i class="bi bi-gear me-2"></i> <span>Parámetros</span>
                    </a>
                    <div class="collapse <?= in_array($pagina, ['categorias_carne', 'parametros_generales']) ? 'show' : '' ?>"
                        id="configSubmenu">
                        <ul class="nav flex-column ms-3">
                            <li><a href="categorias_carne.php"
                                    class="nav-link text-white <?= $pagina === 'categorias_carne' ? 'active' : '' ?>">Categorías
                                    de Carné</a></li>
                            <li><a href="parametros_generales.php"
                                    class="nav-link text-white <?= $pagina === 'parametros_generales' ? 'active' : '' ?>">Parámetros
                                    del sistema</a></li>
                        </ul>
                    </div>
                </li>
            </ul>

            <div class="mt-auto p-3">
                <button id="darkToggle" class="btn btn-outline-light w-100"><i class="bi bi-moon"></i> Modo
                    Oscuro</button>
            </div>
        </div>
    </div>