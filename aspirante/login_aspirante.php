
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autenticación</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/login_aspirante.css">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <main class="text-center col-md-6 col-lg-5 mx-auto">
        <section class="text-center">
            <h1>Autenticación</h1>
            <p>Ingresa tus datos para acceder al examen teórico.</p>
            <div class="card shadow mx-auto max-width">
                <div class="card-body ">
                  <!--  <form id="auth-form" action="../php/login_aspirante.php" method="post">-->
                    <form id="auth-form" action="../php/login_aspirante.php" method="post">
                        <div class="form-group">
                            <label for="username">Usuario</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Ingresa tu usuario">
                        </div>
                        <div class="form-group">
                            <label for="password">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Ingresa tu contraseña">
                        </div>
                        <button type="submit" class="btn btn-primary">Acceder</button>
                    </form>
                </div>
            </div>
        </section>
    </main>
 

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="../js/login_aspirante.js"></script>
</body>
</html>