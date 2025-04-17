<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Iniciar sesión - Exámenes Teóricos</title>
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      display: flex;
      min-height: 100vh;
    }

    .left-panel {
      background-color: #00558c;
      color: white;
      width: 45%;
      padding: 60px 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: flex-start;
    }

    .left-panel img {
      width: 80px;
      margin-bottom: 30px;
    }

    .left-panel h1 {
      font-size: 2rem;
      margin: 0 0 10px;
    }

    .left-panel p {
      font-size: 1.1rem;
      color: #d4e7f5;
      line-height: 1.6;
    }

    .right-panel {
      width: 55%;
      padding: 60px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .form-container {
      width: 100%;
      max-width: 400px;
    }

    .form-container h2 {
      margin-bottom: 30px;
      font-size: 1.8rem;
      color: #333;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-size: 0.95rem;
      color: #444;
    }

    .form-group input {
      width: 100%;
      padding: 12px 15px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 1rem;
    }

    .form-group input:focus {
      outline: none;
      border-color: #00558c;
      box-shadow: 0 0 3px #00558c55;
    }

    button {
      width: 100%;
      padding: 12px;
      background-color: #00558c;
      color: white;
      font-size: 1rem;
      font-weight: bold;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    button:hover {
      background-color: #004273;
    }

    .volver {
      display: block;
      margin-top: 30px;
      text-align: center;
      color: #00558c;
      font-weight: bold;
      text-decoration: none;
    }

    .volver:hover {
      text-decoration: underline;
    }

    @media screen and (max-width: 768px) {
      body {
        flex-direction: column;
      }

      .left-panel,
      .right-panel {
        width: 100%;
      }

      .left-panel {
        align-items: center;
        text-align: center;
      }
    }
  </style>
</head>

<body>
  <div class="left-panel">
    <img src="../public/carousel-1.jpg" alt="Logo DGT">
    <h1>Bienvenido/a</h1>
    <p>Accede a la plataforma oficial para realizar el examen teórico de conducción en Guinea Ecuatorial.</p>
  </div>
  <div class="right-panel">
    <div class="form-container">
      <h2>Iniciar sesión</h2>
      <form action="controller_login.php" method="POST">
        <div class="form-group">
          <label for="email">Correo electrónico</label>
          <input type="email" id="email" name="email" required />
        </div>
        <div class="form-group">
          <label for="password">Contraseña</label>
          <input type="password" id="password" name="password" required />
        </div>
        <button type="submit">Entrar</button>
      </form>
      <a class="volver" href="index.php">← Volver a la página principal</a>
    </div>
  </div>
</body>

</html>
