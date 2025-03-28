<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selecciona tu Examen - Autoescuela Online</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles_realizar.css">
    <style>
        /* Estilos personalizados para reflejar disciplina, perseverancia y actitud */
        body {
            background-color: #f8f9fa; /* Fondo muy claro, casi blanco */
            color: #343a40; /* Texto oscuro para buena legibilidad */
            font-family: 'Montserrat', sans-serif; /* Una fuente moderna y con carácter */
        }

        /* Paleta de colores inspirada en disciplina, perseverancia y actitud */
        .primary-color {
            background-color: #28a745; /* Verde éxito/crecimiento (actitud positiva) */
            color: white;
        }

        .secondary-color {
            background-color: #007bff; /* Azul confianza/estabilidad (disciplina) */
            color: white;
        }

        .accent-color {
            background-color: #ffc107; /* Amarillo energía/motivación (perseverancia) */
            color: #212529;
        }

        .navbar-brand, .nav-link {
            color: #495057 !important; /* Gris oscuro para el texto del navbar */
        }

        .navbar-brand:hover, .nav-link:hover {
            color: #007bff !important; /* Azul al pasar el ratón */
        }

        .header-shadow {
            box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075);
        }

        .main-section {
            padding: 60px 0;
        }

        .card-shadow {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            transition: transform 0.2s ease-in-out;
        }

        .card-shadow:hover {
            transform: translateY(-5px);
        }

        .card-title {
            color: #343a40;
            font-weight: bold;
            display: flex;
            align-items: center;
        }

        .card-title i {
            margin-right: 10px;
            font-size: 1.5em;
        }

        .btn-primary {
            background-color: #28a745;
            border-color: #28a745;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #1e7e34;
            border-color: #1e7e34;
        }

        .btn-secondary {
            background-color: #007bff;
            border-color: #007bff;
            transition: background-color 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .btn-accent {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #212529;
            transition: background-color 0.3s ease;
        }

        .btn-accent:hover {
            background-color: #e0a800;
            border-color: #e0a800;
        }

        .footer-bg {
            background-color: #e9ecef;
        }

        /* Animaciones sutiles */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="d-flex flex-column min-vh-100 fade-in">
    <header class="bg-white header-shadow">
       
        <nav class="navbar navbar-expand-lg navbar-light  ">
            <a class="navbar-brand" href="#">
                <i class="fas fa-car-alt"></i> Examen de Autoescuela Online GE
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse container" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="../../index.php"><i class="fas fa-home"></i> Inicio</a>
                    </li>  
                </ul>
            </div>
        </nav>
         
    </header>


    <main class="container my-5 flex-grow-1 main-section">
        <section class="text-center">
            <h1 class="mb-4 font-weight-bold" style="color: #007bff;">Elige tu Camino hacia el Éxito</h1>
            <p class="lead text-muted mb-5">Selecciona el tipo de examen que deseas realizar. ¡Cada prueba es un paso hacia tu meta!</p>
            <div class="row justify-content-center">
                <div class="col-md-6 mb-4">
                    <div class="card card-shadow border-0">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fas fa-steering-wheel" style="color: #28a745;"></i>
                                Carné B (Coche)
                            </h5>
                            <p class="card-text text-muted">Examen teórico y práctico para coches.</p>
                            <a href="./politicas.php" class="btn btn-primary btn-block">Comenzar Examen B</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card card-shadow border-0">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fas fa-motorcycle" style="color: #007bff;"></i>
                                Carné A (Motocicleta)
                            </h5>
                            <p class="card-text text-muted">Examen teórico y práctico para motocicletas.</p>
                            <a href="./politicas.php" class="btn btn-secondary btn-block">Comenzar Examen A</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card card-shadow border-0">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fas fa-truck" style="color: #ffc107;"></i>
                                Carné C (Camión)
                            </h5>
                            <p class="card-text text-muted">Examen teórico y práctico para camiones.</p>
                            <a href="./politicas.php" class="btn btn-accent btn-block">Comenzar Examen C</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card card-shadow border-0">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fas fa-bus" style="color: #28a745;"></i>
                                Carné D (Autobús)
                            </h5>
                            <p class="card-text text-muted">Examen teórico y práctico para autobuses.</p>
                            <a href="./politicas.php" class="btn btn-primary btn-block">Comenzar Examen D</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-light text-center py-3 footer-bg">
        <p class="text-muted mb-0">&copy; 2024 Autoescuela Online - Impulsando tu camino hacia el éxito.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="script.js"></script>
</body>
</html>