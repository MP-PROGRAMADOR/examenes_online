<?php 
//seguridad de sessiones paginacion
session_start();
error_reporting(0);
$versesion = $_SESSION['usuario_rol'];
$versesionStudent = $_SESSION['numero_identificacion'];

if ($versesion == '' || $versesion == null) {
    header('location: ../login/login.php');
    die();
}
if ($versesion == 'docente') {
    header('../examinador/index_examinador.php');
    die();
}
if (isset($versesionStudent)) {
    header('location: ../aspirantes/preseleccion_de_examen.php');
    die();
}
?>
<!DOCTYPE html>
 <html lang="es">

 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Panel de Administración - Plataforma de Exámenes Online</title>
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
     <link rel="stylesheet" href="admin_styles.css"> <style>
         /* Estilos básicos para el layout minimalista */
         body {
             background-color: #f4f6f9;
             color: #333;
             font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
         }

         .sidebar {
             background-color: #fff;
             border-right: 1px solid #eee;
             height: 100vh;
             position: fixed;
             top: 0;
             left: 0;
             width: 250px;
             padding-top: 20px;
         }

         .sidebar-logo {
             padding: 15px;
             text-align: center;
             margin-bottom: 20px;
         }

         .sidebar-menu {
             list-style: none;
             padding: 0;
             margin: 0;
         }

         .sidebar-menu-item {
             padding: 10px 15px;
             cursor: pointer;
             transition: background-color 0.3s ease;
         }

         .sidebar-menu-item:hover {
             background-color: #e9ecef;
         }

         .sidebar-menu-item a {
             color: #333;
             text-decoration: none;
             display: block;
         }

         .sidebar-menu-item a i {
             margin-right: 10px;
         }

         .content {
             margin-left: 250px;
             padding: 20px;
         }

         .card {
             border: none;
             box-shadow: 0 0.15rem 0.5rem rgba(0, 0, 0, 0.05);
         }

         .card-header {
             background-color: #fff;
             border-bottom: 1px solid #eee;
             padding: 15px;
             font-weight: bold;
         }

         .card-body {
             padding: 15px;
         }

         /* Estilos para la barra superior */
         .top-bar {
             background-color: #fff;
             border-bottom: 1px solid #eee;
             padding: 15px 20px;
             position: fixed;
             top: 0;
             left: 250px;
             right: 0;
             z-index: 100;
         }

         .top-bar .navbar {
             margin: 0;
         }

         .top-bar .navbar-nav {
             align-items: center;
         }

         .top-bar .nav-item {
             margin-left: 15px;
         }

         /* Estilos para los widgets del dashboard */
         .widget {
             background-color: #fff;
             padding: 20px;
             border-radius: 5px;
             box-shadow: 0 0.15rem 0.5rem rgba(0, 0, 0, 0.05);
             margin-bottom: 20px;
         }

         .widget-icon {
             font-size: 2em;
             margin-bottom: 10px;
         }

         .widget-value {
             font-size: 1.5em;
             font-weight: bold;
         }

         .widget-title {
             color: #777;
             font-size: 0.9em;
         }

         /* Estilos personalizados para el tema (puedes ajustarlos) */
         .sidebar {
             background-color: #2c3e50;
             color: #fff;
         }

         .sidebar-menu-item a {
             color: #fff;
         }

         .sidebar-menu-item:hover {
             background-color: #34495e;
         }

         .top-bar {
             background-color: #fff;
         }

         .top-bar .navbar-light .navbar-nav .nav-link {
             color: #333;
         }

         .top-bar .navbar-light .navbar-nav .nav-link:hover,
         .top-bar .navbar-light .navbar-nav .nav-link:focus {
             color: #555;
         }

         .btn-primary {
             background-color: #3498db;
             border-color: #3498db;
         }

         .btn-primary:hover {
             background-color: #2980b9;
             border-color: #2980b9;
         }
     </style>
 </head>

 <body>

     <div class="sidebar">
         <div class="sidebar-logo">
             <a href="#" style="color: #fff; text-decoration: none; font-size: 1.5em; font-weight: bold;">Admin Panel</a>
         </div>
         <ul class="sidebar-menu">
             <li class="sidebar-menu-item">
                 <a href="dashboard.html"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
             </li>
             <li class="sidebar-menu-item">
                 <a href="gestion_admins.html"><i class="fas fa-user-shield"></i> Gestión de Admins</a>
             </li>
             <li class="sidebar-menu-item">
                 <a href="gestion_aspirantes.html"><i class="fas fa-users"></i> Gestión de Aspirantes</a>
             </li>
             <li class="sidebar-menu-item">
                 <a href="gestion_examinadores.html"><i class="fas fa-chalkboard-teacher"></i> Gestión de Examinadores</a>
             </li>
             <li class="sidebar-menu-item">
                 <a href="gestion_preguntas.html"><i class="fas fa-question-circle"></i> Gestión de Preguntas</a>
             </li>
             <li class="sidebar-menu-item">
                 <a href="gestion_examenes.html"><i class="fas fa-file-alt"></i> Gestión de Exámenes</a>
             </li>
             <li class="sidebar-menu-item">
                 <a href="gestion_reportes.html"><i class="fas fa-chart-bar"></i> Reportes</a>
             </li>
             <li class="sidebar-menu-item">
                 <a href="gestion_usuarios.html"><i class="fas fa-user"></i> Gestión de Usuarios</a>
             </li>
             <li class="sidebar-menu-item">
                 <a href="gestion_email.html"><i class="fas fa-envelope"></i> Gestión de Email</a>
             </li>
             <li class="sidebar-menu-item">
                 <a href="gestion_calificaciones.html"><i class="fas fa-star"></i> Calificaciones</a>
             </li>
             <li class="sidebar-menu-item">
                 <a href="gestion_resultados.html"><i class="fas fa-poll"></i> Resultados</a>
             </li>
             <li class="sidebar-menu-item">
                 <a href="gestion_resultados.html"><i class="fas fa-poll"></i> gestion de escuelas</a>
             </li>
             <li class="sidebar-menu-item">
                 <a href="gestion_codigos.html"><i class="fas fa-key"></i> Gestión de Códigos</a>
             </li>
             <li class="sidebar-menu-item">
                 <a href="ajustes.html"><i class="fas fa-cog"></i> Ajustes</a>
             </li>
             <li class="sidebar-menu-item">
                 <a href="../login/logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
             </li>
         </ul>
     </div>

     <div class="content">
         <div class="top-bar">
             <nav class="navbar navbar-expand-lg navbar-light bg-light">
                 <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#topNavbar"
                     aria-controls="topNavbar" aria-expanded="false" aria-label="Toggle navigation">
                     <span class="navbar-toggler-icon"></span>
                 </button>
                 <div class="collapse navbar-collapse" id="topNavbar">
                     <ul class="navbar-nav ml-auto">
                         <li class="nav-item dropdown">
                             <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                 data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                 <i class="fas fa-bell"></i> <span class="badge badge-danger">3</span>
                             </a>
                             <div class="dropdown-menu dropdown-menu-right" aria-labelledby="alertsDropdown">
                                 <a class="dropdown-item" href="#">Nueva inscripción de aspirante</a>
                                 <a class="dropdown-item" href="#">Examen teórico finalizado</a>
                                 <a class="dropdown-item" href="#">Nuevo reporte generado</a>
                             </div>
                         </li>
                         <li class="nav-item dropdown">
                             <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                 data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                 <i class="fas fa-user-circle"></i> Admin User
                             </a>
                             <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                                 <a class="dropdown-item" href="perfil.html">Perfil</a>
                                 <div class="dropdown-divider"></div>
                                 <a class="dropdown-item" href="../login/logout.php">Cerrar Sesión</a>
                             </div>
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
                 <div class="col-md-3">
                     <div class="widget">
                         <div class="widget-icon text-warning"><i class="fas fa-question-circle"></i></div>
                         <div class="widget-value">500</div>
                         <div class="widget-title">Preguntas en Base de Datos</div>
                     </div>
                 </div>
                 <div class="col-md-3">
                     <div class="widget">
                         <div class="widget-icon text-info"><i class="fas fa-chalkboard-teacher"></i></div>
                         <div class="widget-value">10</div>
                         <div class="widget-title">Examinadores Activos</div>
                     </div>
                 </div>
             </div>

             <div class="row">
                 <div class="col-md-6">
                     <div class="card">
                         <div class="card-header">
                             Actividad Reciente
                         </div>
                         <div class="card-body">
                             <ul class="list-unstyled">
                                 <li><i class="fas fa-user-plus text-success mr-2"></i> Nuevo aspirante registrado - Hace 5 minutos</li>
                                 <li><i class="fas fa-check-circle text-primary mr-2"></i> Examen teórico iniciado por Aspirante A - Hace 10 minutos</li>
                                 <li><i class="fas fa-file-upload text-info mr-2"></i> Nueva pregunta añadida por Admin - Hace 20 minutos</li>
                                 <li><i class="fas fa-exclamation-triangle text-warning mr-2"></i> Reporte de errores generado - Hace 30 minutos</li>
                             </ul>
                         </div>
                     </div>
                 </div>
                 <div class="col-md-6">
                     <div class="card">
                         <div class="card-header">
                             Estadísticas de Exámenes
                         </div>
                         <div class="card-body">
                             <canvas id="examStatsChart" width="400" height="200"></canvas>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>

     <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
     <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <script>
         $(document).ready(function() {
             // Ejemplo de datos para el gráfico de estadísticas de exámenes
             var examStatsData = {
                 labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio'],
                 datasets: [{
                     label: 'Exámenes Completados',
                     data: [65, 59, 80, 81, 56, 55],
                     backgroundColor: 'rgba(54, 162, 235, 0.2)',
                     borderColor: 'rgba(54, 162, 235, 1)',
                     borderWidth: 1
                 }, {
                     label: 'Promedio de Calificaciones',
                     data: [75, 70, 85, 78, 80, 82],
                     backgroundColor: 'rgba(255, 99, 132, 0.2)',
                     borderColor: 'rgba(255, 99, 132, 1)',
                     borderWidth: 1
                 }]
             };

             // Configuración del gráfico
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

             // Crear el gráfico en el canvas
             var examStatsChart = new Chart(
                 document.getElementById('examStatsChart'),
                 examStatsConfig
             );
         });
     </script>
 </body>

 </html>