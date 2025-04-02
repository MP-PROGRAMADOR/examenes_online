 <!-- End Navbar -->
 <?php

    require '../../config/conexion.php';

    $conn=$pdo->getConexion();
    


try {
    $sql = "SELECT id, nombre, telefono, direccion FROM escuelas_conduccion";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
    $escuelas= $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error en la consulta: " . $e->getMessage());
    echo "Ocurrió un error al recuperar los Escuelas.";
    exit;
}



?>

   



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
                    <ul class="nav nav-tabs card-header-tabs float-end">
                        <li class="nav-item">
                          

                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            Crear Nuevo
                            </button>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Opción
                                Deshabilitada</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <div class="container-fluid border  mt-5">
            <div class="row p-2 mt-2">

                <h1>Dashboard</h1>
            </div>




<div class="container">

<div class="row">



<table id="example" class="display border p-5">
                <div class="row border-b">
                    <h2 class="text-center">LISTA DE ESCUELAS</h2>
                </div>

                <thead>
                    <p> <a href="registrar_escuelas.php" class="btn btn-primary" type="button">Crear Nuevo</a></p>
                    <tr>
                        <th>ID</th>
                        <th>NOMBRE</th>
                        <th>DIRECCION</th>
                        <th>TELEFONO</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($escuelas as $usuario): ?>
            <tr>
                <td><?= htmlspecialchars($usuario['id']) ?></td>
                <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                <td><?= htmlspecialchars($usuario['telefono']) ?></td>
                <td><?= htmlspecialchars($usuario['direccion']) ?></td>\
                <td><a href="" class="btn btn-warning btn-sm">Editar</a></td>
                <td><a href="" class="btn btn-danger btn-sm">Eliminar</a></td>
            </tr>
        <?php endforeach; ?>
                </tbody>


            </table>





</div>
</div>


            

























        </div>
        <script>
            $(document).ready(function() {
                $('#example').DataTable();
            });
        </script>

        <!-- Scripts optimizados -->
        <script src="../../public/js/bootstrap.bundle.min.js"></script>
        <script src="../../public/js/chart.js"></script>
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