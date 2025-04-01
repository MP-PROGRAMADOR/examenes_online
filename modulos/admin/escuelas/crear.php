<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Plataforma de Exámenes Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <!-- Incluir jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <!-- Incluir DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">

    <!-- Incluir DataTables JS -->
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>

    <link rel="stylesheet" href="admin_styles.css">
    <style>
        body {
            background-color: #f4f6f9;
            color: #333;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .sidebar {
            background-color: #2c3e50;
            color: #fff;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            padding-top: 20px;
        }

        .sidebar-logo {
            padding: 15px;
            text-align: center;
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

        .content {
            margin-left: 250px;
            padding: 30px;
        }

        .top-bar {
            background-color: #fff;
            border-bottom: 1px solid #eee;
            padding: 15px;
            position: fixed;
            top: 0;
            left: 250px;
            right: 0;
            z-index: 100;
        }

        .top-bar .navbar {
            margin: 0;
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

        /**
        
        */
    </style>
</head>

<body>

    <div class="sidebar">
        <div class="sidebar-logo">
            Admin Panel
        </div>
        <ul class="sidebar-menu">
            <li class="sidebar-menu-item">
                <a href="../index_admin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            </li>
            <li class="sidebar-menu-item">
                <a href="listar.php"><i class="fas fa-user-shield"></i> Gestión de escuelas</a>
            </li>
            <!-- Añadir más ítems de menú según sea necesario -->
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
                <div class="card p-5 mt-5">
                    <div class="form-container">
                        <h2>Registrar Escuela de Conducción</h2>
                        <form action="/ruta-a-tu-servidor" method="POST">
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

                            <!-- Campo Correo Electrónico -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>

                            <!-- Campo Entidad de Tráfico -->
                            <div class="mb-3">
                                <label for="entidad_trafico" class="form-label">Entidad de Tráfico</label>
                                <select id="entidad_trafico" name="entidad_trafico_id" class="form-select" required>
                                    <option value="">Seleccione una entidad</option>
                                    <!-- Aquí puedes insertar las entidades dinámicamente -->
                                    <option value="1">Entidad 1</option>
                                    <option value="2">Entidad 2</option>
                                </select>
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
        <script>
            $(document).ready(function () {
                $('#example').DataTable();
            });
        </script>

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