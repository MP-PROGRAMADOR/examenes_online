<!DOCTYPE html>
<html lang="es">



<?php


include '../componentes/head_admin.php';



?>





<body>




<?php


include '../componentes/menu_admin.php';



?>

 




    <div class="content">
        <div class="top-bar">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNavbar"
                    aria-controls="topNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="topNavbar">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-bell"></i> <span class="badge bg-danger">3</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="alertsDropdown">
                                <li><a class="dropdown-item" href="#">Nueva inscripción de aspirante</a></li>
                                <li><a class="dropdown-item" href="#">Examen teórico finalizado</a></li>
                            </ul>
                        </li>
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
                </div>
            </nav>
        </div>
<div class="container-fluid mt-5">
    <h1>Dashboard</h1>
    <div class="row">
        <div class="col-md-3">
            <div class="widget">
                <div class="widget-icon text-primary"><i class="fas fa-users"></i></div>
                <div class="widget-value">150</div>
                <div class="widget-title">Aspirantes Registrados</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="widget">
                <div class="widget-icon text-success"><i class="fas fa-file-alt"></i></div>
                <div class="widget-value">25</div>
                <div class="widget-title">Exámenes Activos</div>
            </div>
        </div>
        <!-- Añadir más widgets según sea necesario -->
    </div>

     
</div>

<!-- Scripts optimizados -->
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
 
 

</body>

</html>