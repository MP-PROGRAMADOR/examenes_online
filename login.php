<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Examenes Online</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    .bg-cover {
      background-image: url('img/cemaforo.jpg');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      border-top-left-radius: 1rem;
      border-bottom-left-radius: 1rem;
    }

    body, html {
      margin: 0;
      padding: 0;
      min-height: 100vh;
      background: #f8f9fa; /* Fondo claro */
    }

    .container-fluid, #toast-container {
      position: relative;
      z-index: 2;
    }
  </style>
</head>
<body class="bg-light min-vh-100 d-flex align-items-center justify-content-center">

  <!-- Alerta -->
  <div id="toast-container" class="position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 1060; max-width: 90%; width: 400px;"></div>

  <div class="container-fluid p-0 shadow-lg rounded overflow-hidden" style="max-width: 960px;">
    <div class="row g-0">

      <!-- Imagen solo en md+ -->
      <div class="col-md-6 bg-cover d-none d-md-block"></div>

      <!-- Formulario -->
      <div class="col-12 col-md-6 p-5 bg-white">
        <div class="text-center mb-4">
          <i class="bi bi-shield-lock-fill fs-1 text-primary"></i>
          <h2 class="text-primary fw-bold">Acceso al Sistema</h2>
        </div>

        <form method="post" id="formLogin" class="row justify-content-center">
          <div class="col-md-10">

            <!-- Usuario -->
            <div class="mb-3">
              <label for="usuario" class="form-label fw-semibold">Usuario</label>
              <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                <input type="text" class="form-control" id="usuario" placeholder="Ingrese su usuario o código" required>
              </div>
            </div>

            <!-- Contraseña -->
            <div class="mb-4">
              <label for="password" class="form-label fw-semibold">Contraseña</label>
              <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-key-fill"></i></span>
                <input type="password" class="form-control" id="password" placeholder="Ingrese su contraseña" required>
              </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 fw-semibold">
              <i class="bi bi-box-arrow-in-right me-2"></i>Ingresar
            </button>

          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="js/alerta.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const form = document.getElementById('formLogin');
      form.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData();
        formData.append('tipoUsuario', 'usuario');
        formData.append('usuario', document.getElementById('usuario').value.trim());
        formData.append('password', document.getElementById('password').value);

        fetch('api/login.php', {
          method: 'POST',
          body: formData
        })
        .then(res => res.json())
        .then(data => {
          if (data.status) {
            mostrarToast('success', data.message);
            setTimeout(() => window.location.href = data.redirect || 'dashboard.php', 1200);
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

</body>
</html>
