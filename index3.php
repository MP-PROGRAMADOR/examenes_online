<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Iniciar sesión - Exámenes Teóricos DGT Guinea Ecuatorial</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: url('fondo_auto.jpg') no-repeat center center fixed;
      background-size: cover;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      color: #fff;
    }

    .login-card {
      background-color: rgba(0, 0, 0, 0.65);
      padding: 40px 30px;
      border-radius: 15px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
      width: 100%;
      max-width: 400px;
      text-align: center;
    }

    .login-card img.logo {
      width: 80px;
      margin-bottom: 20px;
    }

    .login-card h2 {
      margin-bottom: 10px;
      font-size: 1.8rem;
      font-weight: 600;
    }

    .login-card p.sub {
      font-size: 0.95rem;
      margin-bottom: 30px;
      color: #ccc;
    }

    .form-group {
      text-align: left;
      margin-bottom: 20px;
    }

    .form-group label {
      font-size: 0.9rem;
      margin-bottom: 6px;
      display: block;
      color: #ccc;
    }

    .form-group input {
      width: 100%;
      padding: 12px 15px;
      border: none;
      border-radius: 8px;
      font-size: 1em;
      background-color: #f2f2f2;
      color: #333;
    }

    .form-group input:focus {
      outline: none;
      box-shadow: 0 0 4px #00bfff;
    }

    button {
      width: 100%;
      padding: 12px;
      font-size: 1.05em;
      font-weight: bold;
      background-color: #00bfff;
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    button:hover {
      background-color: #009fd1;
    }

    .form-links {
      margin-top: 20px;
      font-size: 0.9em;
      color: #aaa;
    }

    .form-links a {
      color: #00bfff;
      text-decoration: none;
    }

    .form-links a:hover {
      text-decoration: underline;
    }

    .volver-btn {
      margin-top: 30px;
      display: inline-block;
      padding: 10px 20px;
      background-color: transparent;
      color: #00bfff;
      border: 1px solid #00bfff;
      border-radius: 8px;
      text-decoration: none;
      font-weight: bold;
      transition: all 0.3s;
    }

    .volver-btn:hover {
      background-color: #00bfff;
      color: white;
    }
  </style>
</head>

<body>
  <div class="login-card">
    <img src="logo_dgt.png" alt="Logo DGT" class="logo" />
    <h2>Acceso al Examen Teórico</h2>
    <p class="sub">Plataforma oficial de Guinea Ecuatorial</p>
    <form action="controller_login.php" method="post">
      <div class="form-group">
        <label for="email">Correo electrónico</label>
        <input type="email" id="email" name="email" placeholder="ejemplo@correo.com" required />
      </div>
      <div class="form-group">
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" placeholder="••••••••" required />
      </div>
      <button type="submit">Iniciar sesión</button>
    </form>
    <div class="form-links">
      <a href="#">¿Olvidaste tu contraseña?</a>
    </div>
    <a href="index.php" class="volver-btn">← Volver a la página principal</a>
  </div>
</body>

</html>
