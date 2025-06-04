<?php
session_start();
if (isset($_SESSION['estudiante'])) {
  header("Location: aspirante.php");
  exit;
}
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
  flex: 1;
  height: 80vh;
  background-image: url('../img/cemaforo.jpg'); /* Ruta de tu imagen */
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  color: #fff;
  display: flex;
  flex-direction: column;
  justify-content: center;
  padding: 2rem;
  position: relative;
}



    /* Imágenes de fondo para cada slide */
    .bg-slide-1 {
      background-image: url('https://via.placeholder.com/600x800?text=Conduce+Seguro');
      background-size: cover;
      background-position: center;
      position: relative;
    }

    .bg-slide-2 {
      background-image: url('../img/cemaforo.jpg');
      background-size: cover;
      background-position: center;
      position: relative;
    }

    .bg-slide-3 {
      background-image: url('../img/cemaforo.jpg');
      background-size: cover;
      background-position: center;
      position: relative;
    }

    /* Oscurecer un poco el fondo para mejorar legibilidad del texto */
    .overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.4);
      z-index: 1;
    }

    /* Asegurar que los textos estén sobre la overlay */
    .carousel-caption {
      position: relative;
      z-index: 1;
      color: #fff;
    }
  </style>
</head>

<body>
  <div id="toast-container" class="position-fixed top-0 start-50 translate-middle-x p-3"
    style="z-index: 1060; max-width: 90%; width: 400px;"></div> <!-- alerta modal -->
  <div class="login-container">


    <div class="card-login">

      <!-- Columna izquierda: Información animada de seguridad vial -->
      <div class="login-left">
        <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
          <div class="carousel-inner">
            <div class="carousel-item active bg-slide-1">
              <div class="overlay"></div>
              <div class="carousel-caption">
                <h3><i class="bi bi-shield-check"></i> Conduce Seguro</h3>
                <p>Siempre usa el cinturón de seguridad. Salva vidas.</p>
              </div>
            </div>
            <div class="carousel-item bg-slide-2">
              <div class="overlay"></div>
              <div class="carousel-caption">
                <h3><i class="bi bi-speedometer2"></i> Respeta los límites</h3>
                <p>Mantén una velocidad adecuada para prevenir accidentes.</p>
              </div>
            </div>
            <div class="carousel-item bg-slide-3">
              <div class="overlay"></div>
              <div class="carousel-caption">
                <h3><i class="bi bi-traffic-cone"></i> Atención en la vía</h3>
                <p>Evita distracciones. Tu concentración es vital al volante.</p>
              </div>
            </div>
          </div>
        </div>
      </div>


      <!-- Columna derecha: Login -->
     <div class="login-right p-4 bg-white shadow-lg rounded-4">
  <div class="text-center mb-4">
    <i class="bi bi-person-circle fs-1 text-primary"></i>
    <h3 class="fw-bold text-dark mt-2">Iniciar Sesión</h3>
    <p class="text-muted small">Introduce tu código personal para acceder</p>
  </div>

  <form id="formLogin" method="POST" autocomplete="off" class="needs-validation" novalidate>
    <div class="mb-4">
      <label for="codigo" class="form-label fw-semibold text-secondary">
        <i class="bi bi-key-fill me-1"></i> Código de acceso
      </label>
      <div class="input-group">
        <span class="input-group-text bg-light"><i class="bi bi-person-badge"></i></span>
        <input type="text" name="codigo" id="codigo" class="form-control" required maxlength="50"
          placeholder="Tu código personal">
      </div>
    </div>

    <button type="submit" class="btn btn-primary w-100 fw-semibold shadow-sm">
      <i class="bi bi-box-arrow-in-right me-2"></i> Entrar
    </button>

    <div class="text-center mt-3">
      <a href="#" class="text-decoration-none text-muted small">¿Olvidaste tu código?</a>
    </div>
  </form>
</div>

    </div>

  </div>
  </div>

  <!-- Bootstrap JS -->

  <script src="../js/alerta.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const form = document.getElementById('formLogin');

      form.addEventListener('submit', function(e) {
        e.preventDefault(); // Previene el envío tradicional

        const codigo = document.getElementById('codigo').value.trim();

        if (!codigo) {
          mostrarToast('warning', 'Por favor, ingresa tu código de acceso.');
          return;
        }

        const formData = new FormData();
        formData.append('tipoUsuario', 'estudiante'); // ← ¡debe ser "tipo"!
        formData.append('usuario', codigo);


        fetch('../api/login.php', {
            method: 'POST',
            body: formData
          })
          .then(response => response.json())
          .then(data => {
            console.log(data)
            if (data.status) {
              mostrarToast('success', data.message || 'Inicio de sesión exitoso');
              setTimeout(() => {
                window.location.href = data.redirect || 'aspirante.php';
              }, 1200);
            } else {
              mostrarToast('danger', data.message || 'Credenciales incorrectas');
            }
          })
          .catch(error => {
            console.error('Error en la solicitud:', error);
            mostrarToast('danger', 'Ocurrió un error al procesar el login.');
          });
      });
    });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>