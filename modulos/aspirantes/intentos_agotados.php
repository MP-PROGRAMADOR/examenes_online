<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Límite de Intentos Alcanzado - Autoescuela Online</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles_realizar.css">
    <style>
        body {
            background-color: #f8f9fa;
            color: #343a40;
            font-family: 'Montserrat', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .alert-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            padding: 40px;
            text-align: center;
            max-width: 500px;
            width: 90%;
        }

        .alert-icon {
            font-size: 4em;
            color: #dc3545; /* Rojo para alerta */
            margin-bottom: 20px;
        }

        .alert-title {
            color: #dc3545;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .alert-message {
            line-height: 1.7;
            margin-bottom: 30px;
        }

        .btn-contact {
            background-color: #007bff;
            border-color: #007bff;
            color: white;
            padding: 12px 25px;
            font-size: 1.1em;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .btn-contact:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .temporary-lock-info {
            margin-top: 30px;
            padding: 15px;
            background-color: #ffebee;
            border: 1px solid #f5c6cb;
            color: #721c24;
            border-radius: 5px;
            font-size: 0.95em;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="alert-container">
        <i class="fas fa-exclamation-triangle alert-icon"></i>
        <h2 class="alert-title">Límite de Intentos Agotado</h2>
        <p class="alert-message">
            Has agotado tus dos intentos permitidos para realizar el examen de conducir.
            Entendemos tu deseo de avanzar, pero es importante tomar este tiempo para revisar el material de estudio y prepararte mejor.
        </p>
        <p class="alert-message">
            Para desbloquear tu acceso al examen y tener una nueva oportunidad, te pedimos que te pongas en contacto con nuestro equipo de soporte o tu instructor.
            Ellos podrán proporcionarte un código con validez limitada para que puedas intentarlo nuevamente.
        </p>

        <a href="#" class="btn-contact">Contactar con Soporte</a>

        <div class="temporary-lock-info">
            <strong>Importante:</strong> Tu acceso al examen ha sido suspendido temporalmente. Una vez que obtengas y utilices un código válido, podrás acceder nuevamente.
        </div>
        <a href="../../index.php" class="back-link text-end">Volver a inicio</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>