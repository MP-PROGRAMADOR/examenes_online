<!DOCTYPE html>
 <html lang="es">

 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Panel de Examinador - Autoescuela Exámenes Online</title>
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
     <link rel="stylesheet" href="examiner_styles.css">
     <style>
         body {
             background-color: #f4f6f9;
             color: #333;
             font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
         }

         /* Sidebar vertical */
         .sidebar {
             background-color: #2c3e50;
             color: #fff;
             height: 100vh;
             width: 250px;
             position: fixed;
             top: 0;
             left: 0;
             padding-top: 20px;
             z-index: 101; /* Asegura que esté por encima del contenido */
         }

         .sidebar-logo {
             padding: 15px;
             text-align: center;
             margin-bottom: 20px;
         }

         .sidebar-logo a {
             color: #fff;
             text-decoration: none;
             font-size: 1.5em;
             font-weight: bold;
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
             background-color: #34495e;
         }

         .sidebar-menu-item a {
             color: #fff;
             text-decoration: none;
             display: block;
         }

         .sidebar-menu-item a i {
             margin-right: 10px;
         }

         /* Barra superior */
         .top-bar {
             background-color: #fff;
             border-bottom: 1px solid #eee;
             padding: 15px 20px;
             position: fixed;
             top: 0;
             left: 250px; /* Ancho del sidebar */
             right: 0;
             z-index: 100;
         }

         .top-bar .navbar {
             margin: 0;
             padding: 0;
         }

         .top-bar .navbar-brand {
             display: flex;
             align-items: center;
             margin-right: 20px;
         }

         .top-bar .navbar-brand img {
             height: 40px;
             margin-right: 10px;
         }

         .top-bar .navbar-nav {
             align-items: center;
         }

         .top-bar .nav-item {
             margin-left: 15px;
         }

         /* Contenido principal */
         .content {
             margin-left: 250px; /* Ancho del sidebar */
             margin-top: 70px; /* Altura de la barra superior */
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
             <a href="#">Examinador</a>
         </div>
         <ul class="sidebar-menu">
             <li class="sidebar-menu-item">
                 <a href="examiner_dashboard.html"><i class="fas fa-home"></i> Inicio</a>
             </li>
             <li class="sidebar-menu-item">
                 <a href="gestion_aspirantes.html"><i class="fas fa-users"></i> Aspirantes</a>
             </li>
             <li class="sidebar-menu-item">
                 <a href="gestion_preguntas.html"><i class="fas fa-question-circle"></i> Preguntas</a>
             </li>
             <li class="sidebar-menu-item">
                 <a href="gestion_examenes.html"><i class="fas fa-file-alt"></i> Exámenes</a>
             </li>
             <li class="sidebar-menu-item">
                 <a href="calificaciones.html"><i class="fas fa-star"></i> Calificaciones</a>
             </li>
             <li class="sidebar-menu-item">
                 <a href="resultados.html"><i class="fas fa-poll"></i> Resultados</a>
             </li>
             <li class="sidebar-menu-item">
                 <a href="perfil.html"><i class="fas fa-user-circle"></i> Perfil</a>
             </li>
             <li class="sidebar-menu-item">
                 <a href="logout.html"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
             </li>
         </ul>
     </div>

     <div class="top-bar">
         <nav class="navbar navbar-expand-lg navbar-light bg-light">
             <a class="navbar-brand" href="#">
                 <img src="logo_autoescuela.png" alt="Logo Autoescuela" style="height: 40px; margin-right: 10px;">
                 Autoescuela Exámenes Online
             </a>
             <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                 aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                 <span class="navbar-toggler-icon"></span>
             </button>
             <div class="collapse navbar-collapse" id="navbarNav">
                 <ul class="navbar-nav ml-auto">
                     </ul>
             </div>
         </nav>
     </div>

     <div class="content">
         <div class="container-fluid">
             <h1>Panel de Examinador</h1>

             <div class="row">
                 <div class="col-md-4">
                     <div class="widget">
                         <div class="widget-icon text-primary"><i class="fas fa-users"></i></div>
                         <div class="widget-value">85</div>
                         <div class="widget-title">Aspirantes Asignados</div>
                     </div>
                 </div>
                 <div class="col-md-4">
                     <div class="widget">
                         <div class="widget-icon text-success"><i class="fas fa-check-circle"></i></div>
                         <div class="widget-value">32</div>
                         <div class="widget-title">Exámenes Calificados</div>
                     </div>
                 </div>
                 <div class="col-md-4">
                     <div class="widget">
                         <div class="widget-icon text-warning"><i class="fas fa-clock"></i></div>
                         <div class="widget-value">5</div>
                         <div class="widget-title">Exámenes Pendientes por Calificar</div>
                     </div>
                 </div>
             </div>

             <div class="row">
                 <div class="col-md-6">
                     <div class="card">
                         <div class="card-header">
                             Aspirantes Recientes
                         </div>
                         <div class="card-body">
                             <ul class="list-unstyled">
                                 <li><i class="fas fa-user-plus text-success mr-2"></i> Nuevo aspirante asignado - Juan Pérez</li>
                                 <li><i class="fas fa-user-plus text-success mr-2"></i> Nuevo aspirante asignado - María López</li>
                                 <li><i class="fas fa-user-plus text-success mr-2"></i> Nuevo aspirante asignado - Carlos García</li>
                                 <li><i class="fas fa-user-plus text-success mr-2"></i> Nuevo aspirante asignado - Ana Rodríguez</li>
                             </ul>
                         </div>
                     </div>
                 </div>
                 <div class="col-md-6">
                     <div class="card">
                         <div class="card-header">
                             Calificaciones Promedio por Examen
                         </div>
                         <div class="card-body">
                             <canvas id="calificacionesChart" width="400" height="200"></canvas>
                         </div>
                     </div>
                 </div>
             </div>

             <div class="row mt-4">
                 <div class="col-md-12">
                     <div class="card">
                         <div class="card-header">
                             Exámenes Pendientes de Calificación
                         </div>
                         <div class="card-body">
                             <div class="table-responsive">
                                 <table class="table table-hover">
                                     <thead>
                                         <tr>
                                             <th>Aspirante</th>
                                             <th>Examen</th>
                                             <th>Fecha de Finalización</th>
                                             <th>Acciones</th>
                                         </tr>
                                     </thead>
                                     <tbody>
                                         <tr>
                                             <td>Sofía Martínez</td>
                                             <td>Teórico General</td>
                                             <td>2025-03-28 10:00</td>
                                             <td><button class="btn btn-sm btn-primary">Calificar</button></td>
                                         </tr>
                                         <tr>
                                             <td>Diego Fernández</td>
                                             <td>Práctico de Maniobras</td>
                                             <td>2025-03-28 11:30</td>
                                             <td><button class="btn btn-sm btn-primary">Calificar</button></td>
                                         </tr>
                                         <tr>
                                             <td>Isabela Vargas</td>
                                             <td>Teórico de Señales</td>
                                             <td>2025-03-27 15:45</td>
                                             <td><button class="btn btn-sm btn-primary">Calificar</button></td>
                                         </tr>
                                     </tbody>
                                 </table>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>

     <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
     <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
     <script>
         $(document).ready(function() {
             // Ejemplo de datos para el gráfico de calificaciones promedio
             var calificacionesData = {
                 labels: ['Teórico General', 'Práctico de Maniobras', 'Teórico de Señales'],
                 datasets: [{
                     label: 'Calificación Promedio',
                     data: [85, 92, 78],
                     backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(255, 206, 86, 0.2)'],
                     borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)'],
                     borderWidth: 1
                 }]
             };

             // Configuración del gráfico
             var calificacionesConfig = {
                 type: 'bar',
                 data: calificacionesData,
                 options: {
                     scales: {
                         y: {
                             beginAtZero: true,
                             max: 100
                         }
                     }
                 }
             };

             // Crear el gráfico en el canvas
             var calificacionesChart = new Chart(
                 document.getElementById('calificacionesChart'),
                 calificacionesConfig
             );
         });
     </script>
 </body>

 </html>