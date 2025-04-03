<!DOCTYPE html>
<html lang="es">


<?php


include '../componentes/head_admin.php';



?>

<body>

    <?php


    include '../componentes/menu_admin.php';



    ?>

    <div class="content ">
        <div class="top-bar">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNavbar"
                    aria-controls="topNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="topNavbar">
                    <ul class="navbar-nav ml-auto">
                        <!--  <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-bell"></i> <span class="badge bg-danger">3</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="alertsDropdown">
                                <li><a class="dropdown-item" href="#">Nueva inscripción de aspirante</a></li>
                                <li><a class="dropdown-item" href="#">Examen teórico finalizado</a></li>
                            </ul>
                        </li> -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle"></i> Admin User
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="perfil.html">Perfil</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="../login/logout.php">Cerrar Sesión</a></li>
                            </ul>
                        </li>

                    </ul>
                    <!--   <ul class="nav nav-tabs card-header-tabs float-end">

                        <li class="nav-item">
                            <a href="listar.php" class="btn btn-primary " type="button"><i class="fas fa-list me-2"></i> Visualizar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Opción
                                Deshabilitada</a>
                        </li>
                    </ul> -->
                </div>
            </nav>
        </div>
        <div class="container-fluid  mt-5 pt-2">

            <div class="row d-flex justify-content-center align-items-center">
                <div class="card p-5 mt-5 w-50">
                    <div class="container">
                        <h2 class="mb-4">Crear Nueva Categoría de Carné</h2>
                        <form action="../php/guardar_categorias.php" method="POST" class="needs-validation" novalidate>
                            <div class="mb-3 form-group required">
                                <label for="nombre" class="form-label">Nombre de la Categoría:</label>
                                <input type="text" class="form-control" id="nombre" name="nombre"
                                    placeholder="Ej: B, C1, A" required>
                                <div class="invalid-feedback">Por favor, ingrese el nombre de la categoría.</div>
                            </div>
                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción (Opcional):</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="3"
                                    placeholder="Descripción detallada de la categoría."></textarea>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-2"></i>Guardar
                                    Categoría</button>
                                <!-- <a href="lista_categorias.php" class="btn btn-secondary"><i
                                        class="bi bi-arrow-left me-2"></i>Cancelar</a> -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Scripts optimizados -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


</body>

</html>