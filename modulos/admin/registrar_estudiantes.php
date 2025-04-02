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
                    <div class="  ">
                        <h2 class="text-center mb-4">Registro de Estudiante</h2>
                        <form action="../php/guardar_estudante.php" method="POST">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="escuela_id" class="form-label">Escuela de Conducción:</label>
                            <select name="escuela_id" class="form-select" required>
                                <option value="">Seleccione una escuela</option>
                                <option value="1">Escuela 1</option>
                                <option value="2">Escuela 2</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="numero_identificacion" class="form-label">Número de Identificación:</label>
                            <input type="text" name="numero_identificacion" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre:</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="apellido" class="form-label">Apellido:</label>
                            <input type="text" name="apellido" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento:</label>
                            <input type="date" name="fecha_nacimiento" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono:</label>
                            <input type="number" name="telefono" class="form-control" pattern="\d{9,15}" title="Ingrese un número de teléfono válido (9-15 dígitos)">
                        </div>
                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección:</label>
                            <input type="text" name="direccion" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="categoria_carne" class="form-label">Categoría de Carné:</label>
                            <select name="categoria_carne" class="form-select" required>
                                <option value="">Seleccione una categoría</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="codigo_registro_examen" class="form-label">Código de Registro para el Examen:</label>
                            <input type="text" name="codigo_registro_examen" class="form-control" >
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Registrar</button>
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
        document.addEventListener('DOMContentLoaded', function() {
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