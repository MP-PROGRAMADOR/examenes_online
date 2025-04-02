 <!-- End Navbar -->
 <?php

    require '../../config/conexion.php';

    $conn = $pdo->getConexion();


    try {
        // Conectar a la base de datos


        // Preparar la consulta para obtener los datos
        $sql = "SELECT id, escuela_id, numero_identificacion, nombre, apellido, fecha_nacimiento, email, telefono, direccion, categoria_carne, codigo_registro_examen FROM estudiantes";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // Obtener los resultados como un array asociativo
        $estudiantes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        die("Error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
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
                             <p> <a href="registrar_estudiantes.php" class="btn btn-primary" type="button">Crear Nuevo</a></p>
                             <tr>
                                 <th>ID</th>
                                 <th>Escuela ID</th>
                                 <th>Número Identificación</th>
                                 <th>Nombre</th>
                                 <th>Apellido</th>
                                 <th>Fecha Nacimiento</th>
                                 <th>Email</th>
                                 <th>Teléfono</th>
                                 <th>Dirección</th>
                                 <th>Categoría Carné</th>
                                 <th>Código Registro Examen</th>
                             </tr>
                         </thead>
                         <tbody>
                         <?php if (!empty($estudiantes)): ?>
                                     <?php foreach ($estudiantes as $estudiante): ?>
                                         <tr>
                                             <td><?= htmlspecialchars($estudiante['id'], ENT_QUOTES, 'UTF-8') ?></td>
                                             <td><?= htmlspecialchars($estudiante['escuela_id'], ENT_QUOTES, 'UTF-8') ?></td>
                                             <td><?= htmlspecialchars($estudiante['numero_identificacion'], ENT_QUOTES, 'UTF-8') ?></td>
                                             <td><?= htmlspecialchars($estudiante['nombre'], ENT_QUOTES, 'UTF-8') ?></td>
                                             <td><?= htmlspecialchars($estudiante['apellido'], ENT_QUOTES, 'UTF-8') ?></td>
                                             <td><?= htmlspecialchars($estudiante['fecha_nacimiento'], ENT_QUOTES, 'UTF-8') ?></td>
                                             <td><?= htmlspecialchars($estudiante['email'], ENT_QUOTES, 'UTF-8') ?></td>
                                             <td><?= htmlspecialchars($estudiante['telefono'], ENT_QUOTES, 'UTF-8') ?></td>
                                             <td><?= htmlspecialchars($estudiante['direccion'], ENT_QUOTES, 'UTF-8') ?></td>
                                             <td><?= htmlspecialchars($estudiante['categoria_carne'], ENT_QUOTES, 'UTF-8') ?></td>
                                             <td><?= htmlspecialchars($estudiante['codigo_registro_examen'], ENT_QUOTES, 'UTF-8') ?></td>
                                         </tr>
                                     <?php endforeach; ?>
                                 <?php else: ?>
                                     <tr>
                                         <td colspan="11">No hay estudiantes registrados.</td>
                                     </tr>
                                 <?php endif; ?>
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