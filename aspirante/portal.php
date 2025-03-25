

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exámenes Online Autoescuela</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles_portal.css"> 
     
</head>
<body>
<header class="bg-white shadow-sm">
        <nav class="navbar navbar-expand-lg navbar-light container">
            <a class="navbar-brand" href="#">
                <img src="logo.png" alt="Logo Autoescuela" height="40"> Autoescuela Online
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="./portal.php">Inicio</a>
                    </li>
                   
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contacto</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-primary" href="./login_aspirante.php">Acceder</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <main class="container my-5">
        <div class="jumbotron text-center">
            <h1 class="display-4">¡Prepárate para tu Examen de Conducir!</h1>
            <p class="lead">Realiza exámenes online y practica a tu propio ritmo.</p>
            <a class="btn btn-primary btn-lg" href="./empezar.php" role="button">Comenzar Ahora</a>
        </div>

        <section class="text-center">
            <h2>Nuestros Servicios</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h3>Exámenes Teóricos</h3>
                    <p>Practica con preguntas actualizadas y similares al examen real.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h3>Simulacros</h3>
                    <p>Realiza simulacros completos para evaluar tu nivel de preparación.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h3>Estadísticas</h3>
                    <p>Revisa tus resultados y estadísticas para mejorar tu aprendizaje.</p>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-light text-center py-3">
        <p>&copy; 2024 Autoescuela Online</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="./js/js_portal.js"></script>
</body>
</html>