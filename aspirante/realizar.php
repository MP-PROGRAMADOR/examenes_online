
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selecciona tu Examen</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles_realizar.css">
</head>
<body class="d-flex flex-column min-vh-100">
    <header class="bg-white shadow-sm">
        <nav class="navbar navbar-expand-lg navbar-light container">
            <a class="navbar-brand" href="./portal.php">
                <img src="logo.png" alt="Logo Autoescuela" height="40"> Autoescuela Online
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="./portal.php">Inicio</a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contacto</a>
                    </li>
                   
                </ul>
            </div>
        </nav>
    </header>

    <main class="container my-5 flex-grow-1">
        <section class="text-center">
            <h1>Selecciona el Tipo de Examen</h1>
            <p>Elige el tipo de examen que deseas realizar según el carné de conducir.</p>
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card shadow">
                        <div class="card-body">
                            <h5 class="card-title">Carné B (Coche)</h5>
                            <p class="card-text">Examen teórico y práctico para coches.</p>
                            <a href="./folio_test.php" class="btn btn-primary">Realizar Examen B</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card shadow">
                        <div class="card-body">
                            <h5 class="card-title">Carné A (Motocicleta)</h5>
                            <p class="card-text">Examen teórico y práctico para motocicletas.</p>
                            <a href="./folio_test.php" class="btn btn-primary">Realizar Examen A</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card shadow">
                        <div class="card-body">
                            <h5 class="card-title">Carné C (Camión)</h5>
                            <p class="card-text">Examen teórico y práctico para camiones.</p>
                            <a href="./folio_test.php" class="btn btn-primary">Realizar Examen C</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card shadow">
                        <div class="card-body">
                            <h5 class="card-title">Carné D (Autobús)</h5>
                            <p class="card-text">Examen teórico y práctico para autobuses.</p>
                            <a href="./folio_test.php" class="btn btn-primary">Realizar Examen D</a>
                        </div>
                    </div>
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
    <script src="script.js"></script>
</body>
</html>