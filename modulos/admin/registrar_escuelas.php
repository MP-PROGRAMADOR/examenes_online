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
                    <ul class="nav nav-tabs card-header-tabs float-end">
                         
                        <li class="nav-item">
                        <a href="listar.php" class="btn btn-primary " type="button"><i class="fas fa-list me-2"></i> Visualizar</a> 
                        </li>
                        <li class="nav-item">
                            <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Opción
                                Deshabilitada</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <div class="container-fluid  mt-5 pt-2">

            <div class="row d-flex justify-content-center align-items-center">
                <div class="card p-5 mt-5 w-50">
                    <div class="form-container">
                        <h2>Registrar Escuela de Conducción</h2>
                        <form action="../php/guardar_escuelas.php" method="POST">
                            <!-- Campo Nombre de la Escuela -->
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre de la Escuela</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>

                            <!-- Campo Dirección -->
                            <div class="mb-3">
                                <label for="direccion" class="form-label">Dirección</label>
                                <input type="text" class="form-control" id="direccion" name="direccion">
                            </div>

                            <!-- Campo Teléfono -->
                            <div class="mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="tel" class="form-control" id="telefono" name="telefono">
                            </div>

                        
                            <!-- Botón de Enviar -->
                            <div class="mb-3 text-center">
                                <button type="submit" class="btn btn-primary">Registrar Escuela</button>
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var examStatsData = {
                labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio'],
                datasets: [{
                    label: 'Exámenes Completados',
                    data: [65, 59, 80, 81, 56, 55],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            };

            var examStatsConfig = {
                type: 'bar',
                data: examStatsData,
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            };

            var examStatsChart = new Chart(
                document.getElementById('examStatsChart'),
                examStatsConfig
            );
        });
    </script>

</body>

</html>