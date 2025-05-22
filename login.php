<!-- Login Backend (login.php) -->
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['usuario'])) {
     header('location: admin/index.php');
} 
?>






<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Iniciar Sesión</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    .bg-cover {
      background-image: url('img/cemaforo.jpg');
      border-top-left-radius: 1rem;
      border-bottom-left-radius: 1rem;
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
    }
  </style>
</head>

<body class="bg-light">
  <!-- alerta personalizada  -->
 <div id="toast-container" class="position-fixed top-0 start-50 translate-middle-x p-3"
    style="z-index: 1060; max-width: 90%; width: 400px;"></div>

  <div class="container-fluid">
    <div class="row vh-100 align-items-center justify-content-center">

      <!-- Ilustración (sólo visible en pantallas md+) -->
      <div class="col-md-6 bg-cover" style=""></div>

      <!-- Formulario -->
      <div class="col-12 col-md-6 px-4 py-5 bg-white shadow rounded-end">
        <div class="text-center mb-4">
          <i class="bi bi-shield-lock-fill fs-1 text-primary"></i>
          <h2 class="text-primary fw-bold">Acceso al Sistema</h2>
        </div>

       <form method="post" id="formLogin" class="row d-flex justify-content-center">

          <div class="col-md-8">


            <!-- Toggle tipo de usuario -->
            <!-- <div class="mb-4 text-center">
              <label class="form-label d-block mb-2 fw-semibold">Tipo de Usuario</label>
              <div class="btn-group" role="group" aria-label="Tipo de usuario" id="tipoUsuarioGroup">
                <input type="radio" class="btn-check" name="tipoUsuario" id="usuarioBtn" value="usuario"
                  autocomplete="off" checked>
                <label class="btn btn-outline-primary" for="usuarioBtn">
                  <i class="bi bi-person-fill me-1"></i>Usuario
                </label>

                <input type="radio" class="btn-check" name="tipoUsuario" id="estudianteBtn" value="estudiante"
                  autocomplete="off">
                <label class="btn btn-outline-primary" for="estudianteBtn">
                  <i class="bi bi-mortarboard-fill me-1"></i>Estudiante
                </label>
              </div>
            </div> -->

            <!-- Usuario -->
            <div class="mb-3">
              <label for="usuario" class="form-label fw-semibold">Usuario / Código de Acceso</label>
              <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                <input type="text" class="form-control" id="usuario" placeholder="Ingrese su usuario o código" required>
              </div>
            </div>

            <!-- Contraseña -->
            <div class="mb-4" id="grupoPassword">
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
      e.preventDefault(); // Evita el envío tradicional

      const formData = new FormData();
      const tipoUsuario = document.querySelector('input[name="tipoUsuario"]:checked').value;
      const usuario = document.getElementById('usuario').value.trim();
      const password = document.getElementById('password').value;

      formData.append('tipoUsuario', tipoUsuario);
      formData.append('usuario', usuario);
      formData.append('password', password);
      console.log(password)
      fetch('api/login.php', {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.status) {
          // Mostrar mensaje y redirigir, por ejemplo:
          mostrarToast('success', data.message);
          setTimeout(() => window.location.href = data.redirect || 'dashboard.php', 1200);
        } else {
          mostrarToast('danger', data.message || 'Credenciales incorrectas');
        }
      })
      .catch(error => {
        console.error('Error en la solicitud:', error);
        mostrarToast('danger', 'Ocurrió un error al procesar el login: ');
      });
    });
  });
</script>




</body>

</html>