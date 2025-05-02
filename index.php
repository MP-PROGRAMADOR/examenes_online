

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Iniciar sesión - Exámenes Teóricos DGT Guinea Ecuatorial</title>
    <style>
        :root {
            --azul-oscuro: #0f172a;
            --azul-intermedio: #1e293b;
            --azul-claro: #00bfff;
            --blanco: #ffffff;
            --gris-texto: #ccc;
            --gris-input: #f2f2f2;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--blanco);
        }

        body {
            display: flex;
            flex-direction: column;
            background-color: #f8fafc;
        }

        header {
            flex: 0 0 27vh;
            background-color: var(--azul-oscuro);
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            text-align: center;
            animation: fadeDown 1s ease-out;
        }

        header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        header p {
            font-size: 1rem;
            color: var(--gris-texto);
        }

        main {
            flex: 1;
            background-color: #f8fafc;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 60px 20px;
            position: relative;
        }

        .login-card {
            background-color: var(--azul-oscuro);
            padding: 40px 30px;
            border-radius: 15px;
            box-shadow: 0 15px 60px rgba(0, 0, 0, 0.6);
            width: 100%;
            max-width: 400px;
            text-align: center;
            animation: floatUp 1.2s ease forwards;
            opacity: 0;
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
            color: var(--gris-texto);
        }

        .form-group {
            text-align: left;
            margin-bottom: 20px;
        }

        .form-group label {
            font-size: 0.9rem;
            margin-bottom: 6px;
            display: block;
            color: var(--gris-texto);
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            background-color: var(--gris-input);
            color: #333;
        }

        .form-group input:focus {
            outline: none;
            box-shadow: 0 0 4px var(--azul-claro);
        }

        button {
            width: 100%;
            padding: 12px;
            font-size: 1.05em;
            font-weight: bold;
            background-color: var(--azul-claro);
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
            color: var(--gris-texto);
        }

        .form-links a {
            color: var(--azul-claro);
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
            color: var(--azul-claro);
            border: 1px solid var(--azul-claro);
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s;
        }

        .volver-btn:hover {
            background-color: var(--azul-claro);
            color: white;
        }

        @keyframes floatUp {
            0% {
                opacity: 0;
                transform: translateY(40px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            header h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>

<body>

    <header>
        <h1>Plataforma de Exámenes DGT</h1>
        <p>Bienvenido a la plataforma oficial de Guinea Ecuatorial</p>
    </header>

    <main>
        <div class="login-card">
            <img src="logo_dgt.png" alt="Logo DGT" class="logo" />
            <h2>Acceso al Examen Teórico</h2>
            <p class="sub">Plataforma oficial de Guinea Ecuatorial</p>
            <form action="login.php" method="post">
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
            <div class="form-links">
                <a href="aspirantes/politicas.php">entra sin contraseña</a>
            </div>
            <a href="../index.php" class="volver-btn">← Volver a la página principal</a>
        </div>
    </main>

</body>

</html>




