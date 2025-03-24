<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exámenes Online Autoescuela</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles_empezar.css">
</head>

<body class="d-flex flex-column min-vh-100">
    <header class="bg-white shadow-sm">
        <nav class="navbar navbar-expand-lg navbar-light container">
            <a class="navbar-brand" href="index.html">
                <img src="logo.png" alt="Logo Autoescuela" height="40"> Autoescuela Online
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.html">Inicio</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="#">Exámenes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contacto</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-primary" href="#">Acceder</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <main class="container my-5 flex-grow-1">
        <section class="text-center">
            <h1>Selecciona tu Examen</h1>
            <p>Elige el tipo de examen que deseas realizar.</p>
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card shadow">
                        <div class="card-body">
                            <h5 class="card-title">Examen Teórico</h5>
                            <p class="card-text">Practica con preguntas actualizadas.</p>
                            <a href="./login_aspirante.php" class="btn btn-primary">Realizar Examen</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card shadow">
                        <div class="card-body">
                            <h5 class="card-title">Simulacro Completo</h5>
                            <p class="card-text">Evalúa tu nivel de preparación.</p>
                            <a href="./realizar.php" class="btn btn-primary">Realizar Simulacro</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-light text-center py-3">
    <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="card carousel-card">
                    <img src="../img/carousel-1.jpg" class="card-img-top" alt="Ilustración de examen en línea">
                </div>
            </div>
            <div class="carousel-item">
                <div class="card carousel-card">
                    <img src="../img/carousel-2.jpg" class="card-img-top" alt="Ilustración de estudiante realizando examen">
                </div>
            </div>
            <div class="carousel-item">
                <div class="card carousel-card">
                    <img src="../img/carousel-3.jpg" class="card-img-top" alt="Ilustración de plataforma de exámenes">
                </div>
            </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Anterior</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Siguiente</span>
        </a>
    </div>
    <p>&copy; 2024 Autoescuela Online</p>
</footer>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="script.js"></script>
</body>



</html>