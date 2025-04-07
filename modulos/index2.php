<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - Examen Teórico</title>
  <style>
    :root {
      --color-primario: #00558c;
      --color-secundario: #e6f0fa;
      --sombra-suave: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #00558c, #007dc3);
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      color: #333;
    }

    .card {
      background-color: white;
      padding: 40px 30px;
      border-radius: 16px;
      box-shadow: var(--sombra-suave);
      width: 100%;
      max-width: 420px;
      text-align: center;
      animation: fadeInUp 0.6s ease-out;
    }

    .card img {
      width: 70px;
      margin-bottom: 20px;
    }

    .card h2 {
      margin-bottom: 25px;
      font-size: 1.8rem;
      color: var(--color-primario);
    }

    .form-group {
      text-align: left;
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-size: 0.95rem;
    }

    .form-group input {
      width: 100%;
      padding: 12px 15px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 1rem;
    }

    .form-group input:focus {
      outline: none;
      border-color: var(--color-primario);
      box-shadow: 0 0 5px var(--color-primario);
    }

    button {
      width: 100%;
      padding: 12px;
      margin-top: 10px;
      background-color: var(--color-primario);
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 1rem;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    button:hover {
      background-color: #003f66;
    }

    .volver {
      display: block;
      margin-top: 25px;
      color: var(--color-primario);
      font-weight: bold;
      text-decoration: none;
      font-size: 0.95rem;
    }

    .volver:hover {
      text-decoration: underline;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(40px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @media screen and (max-width: 480px) {
      .card {
        padding: 30px 20px;
      }
    }
  </style>
</head>

<body>
  <div class="card">
    <img src="logo_dgt.png" alt="Logo DGT">
    <h2>Accede a tu cuenta</h2>
    <form action="controller_login.php" method="POST">
      <div class="form-group">
        <label for="email">Correo electrónico</label>
        <input type="email" id="email" name="email" required />
      </div>
      <div class="form-group">
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" required />
      </div>
      <button type="submit">Iniciar sesión</button>
    </form>
    <a class="volver" href="index.php">← Volver a la página principal</a>
  </div>
</body>

</html>
