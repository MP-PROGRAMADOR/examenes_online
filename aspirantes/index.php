<?php
session_start();
$error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="description" content="Login seguro para acceder al sistema. Inicia sesión usando tu código único." />
  <title>Iniciar Sesión</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />

  <style>
    html,
    body {
      height: 100%;
      margin: 0;
      overflow: hidden;
      font-family: 'Segoe UI', sans-serif;
      background: #f2f2f2;
      color: #333;
    }

    #mensajeError {
      transition: opacity 1s ease;
    }

    .login-container {
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem;
    }

    .card-login {
      display: flex;
      width: 100%;
      max-width: 1100px;
      border-radius: 1rem;
      box-shadow: 0 0 25px rgba(0, 0, 0, 0.1);
      overflow: hidden;
    }

    .login-left {
      flex: 1;
      /*  background: linear-gradient(to bottom right, #007bff, #66d9ff); */
      color: #fff;
      display: flex;
      flex-direction: column;
      justify-content: center;
      padding: 2rem;
      position: relative;
    }

    .login-left .carousel-inner {
      height: 100%;
    }

    .login-left .carousel-item {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100%;
    }

    .login-left .carousel-caption {
      text-align: center;
      z-index: 10;
      animation: fadeInUp 1s ease-in-out;
    }

    .login-left h3 {
      font-size: 1.75rem;
      margin-bottom: 0.5rem;
      font-weight: bold;
      text-transform: uppercase;
    }

    .login-left p {
      font-size: 1rem;
      margin-bottom: 1rem;
      font-style: italic;
    }

    .login-left i {
      font-size: 3rem;
      margin-right: 0.5rem;
      color: #fff;
      transition: transform 0.3s;
    }

    .login-left i:hover {
      transform: rotate(10deg);
    }

    .login-right {
      flex: 1;
      background-color: white;
      padding: 3rem;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .login-right h2 {
      color: #333;
      margin-bottom: 1.5rem;
      text-align: center;
    }

    .form-control {
      background-color: #f9f9f9;
      border: 1px solid #ccc;
      color: #333;
      border-radius: 0.5rem;
    }

    .form-control:focus {
      background-color: #fff;
      border-color: #3399ff;
      box-shadow: 0 0 0 0.2rem rgba(51, 153, 255, 0.25);
    }

    .btn-custom {
      background-color: #007bff;
      border: none;
      color: #fff;
      font-weight: bold;
      border-radius: 0.5rem;
      padding: 0.75rem;
      transition: background-color 0.3s ease;
    }

    .btn-custom:hover {
      background-color: #0056b3;
    }


    /* Ajustar la altura del contenedor */
    .login-left {
      height: 80vh;
    }
  </style>
</head>

<body>

  <div class="login-container">
    <div class="card-login">

      <!-- Columna izquierda: Información animada de seguridad vial -->
      <div class="login-left">
        <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
          <div class="carousel-inner">
            <div class="carousel-item active">
              <div class="carousel-caption">
                <h3><i class="bi bi-shield-check"></i> Conduce Seguro</h3>
                <p>Siempre usa el cinturón de seguridad. Salva vidas.</p>
              </div>
            </div>
            <div class="carousel-item">
              <div class="carousel-caption">
                <h3><i class="bi bi-speedometer2"></i> Respeta los límites</h3>
                <p>Mantén una velocidad adecuada para prevenir accidentes.</p>
              </div>
            </div>
            <div class="carousel-item">
              <div class="carousel-caption">
                <h3><i class="bi bi-traffic-cone"></i> Atención en la vía</h3>
                <p>Evita distracciones. Tu concentración es vital al volante.</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Columna derecha: Login -->
      <div class="login-right">
        <?php if (isset($_SESSION['alerta'])): ?>
          <div class="alert alert-<?php echo $_SESSION['alerta']['tipo']; ?>">
            <?php echo $_SESSION['alerta']['mensaje']; ?>
          </div>
          <?php unset($_SESSION['alerta']); ?>
        <?php endif; ?>

        <h2><i class="bi bi-person-circle"></i> Iniciar sesión</h2>
        <form action="../php/login_aspirante.php" method="POST" autocomplete="off">
          <div class="mb-3">
            <label for="codigo" class="form-label"><i class="bi bi-key-fill"></i> Código de acceso</label>
            <input type="text" name="codigo" id="codigo" class="form-control" required maxlength="50" placeholder="Tu código personal">
          </div>
          <button type="submit" class="btn btn-custom w-100"><i class="bi bi-box-arrow-in-right"></i> Entrar</button>
          <a href="aspirante.php">registrar</a>
        </form>

        <?php if ($error): ?>
          <div id="mensajeError" class="alert alert-danger mt-3" role="alert">
            <?php echo htmlspecialchars($error); ?>
          </div>
        <?php endif; ?>


      </div>
    </div>

  </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Script para desaparecer el mensaje -->
  <script>
  setTimeout(() => {
    const mensaje = document.getElementById('mensajeError');
    if (mensaje) {
      mensaje.style.opacity = '0';
      setTimeout(() => mensaje.style.display = 'none', 1000);
    }
  }, 5000);
</script>

  <script>
    // Inicializar el carrusel manualmente
    var myCarousel = document.getElementById('carouselExampleFade');
    var carousel = new bootstrap.Carousel(myCarousel, {
      interval: 2000, // Tiempo de transición entre cada imagen en milisegundos
      ride: 'carousel' // Esto asegura que el carrusel se inicie automáticamente
    });
  </script>

</body>

</html>