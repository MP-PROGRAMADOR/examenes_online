<?php
session_start();
require '../includes/conexion.php';

if (!isset($_SESSION['estudiante'])) {
    header("Location: cerrar_sesion.php");
    exit();
}

$examen = $_SESSION['estudiante'];
$nombre = $examen['nombre'] ?? 'Estudiante';
$codigo = $examen['codigo_acceso'] ?? '';
$estudiante_id = (int)($examen['estudiante_id'] ?? 0);
$examen_id = (int)($examen['examen_id'] ?? 15);
$preguntas = $examen['total_preguntas'] ?? 30;
$duracion = $examen['duracion'] ?? 50;


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del Estudiante</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Google Fonts & Bootstrap 5 -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding-top: 70px;
        }

        .navbar {
            background-color: #084298;
        }

        .navbar-brand, .navbar .nav-link {
            color: #fff !important;
            font-weight: 500;
        }

        .info-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.05);
            transition: all 0.3s ease-in-out;
        }

        .info-card h2 {
            font-size: 1.8rem;
            font-weight: 600;
            color: #0d6efd;
        }

        .info-card ul {
            padding-left: 1rem;
        }

        .info-card li {
            margin-bottom: 10px;
        }

        .important-note {
            background-color: #e7f3ff;
            border-left: 4px solid #0d6efd;
            padding: 1rem 1.25rem;
            border-radius: 8px;
            margin-top: 2rem;
            font-weight: 500;
        }

        .btn-start {
            background-color: #0d6efd;
            color: #fff;
            border-radius: 30px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            font-size: 1.1rem;
            transition: background-color 0.3s ease-in-out;
        }

        .btn-start:hover {
            background-color: #0b5ed7;
        }

        footer {
            margin-top: 4rem;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .info-card {
                padding: 25px;
            }

            .info-card h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg fixed-top shadow-sm">
    <div class="container-fluid px-4">
        <a class="navbar-brand" href="#">
            <i class="bi bi-mortarboard-fill me-2 fs-4"></i> CÓDIGO: <strong><?= htmlspecialchars($codigo) ?></strong>
        </a>
        <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <span class="nav-link"><i class="bi bi-person-circle"></i> <?= htmlspecialchars($nombre) ?></span>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="info-card mt-5">
                <h2 class="mb-3">Información del Examen</h2>
                <p class="text-muted mb-4">Lee cuidadosamente las siguientes reglas y condiciones antes de comenzar:</p>

                <h5 class="text-primary mb-2">Políticas</h5>
                <ul>
                    <li><strong>Duración:</strong> <span id="exam-duration">--</span></li>
                    <li><strong>Preguntas:</strong> <span id="exam-questions">--</span></li>
                    <li><strong>Navegación:</strong> Puedes avanzar y retroceder libremente.</li>
                    <li><strong>Finalización:</strong> El examen se cierra automáticamente al finalizar.</li>
                    <li><strong>Intentos:</strong> <span id="exam-attempts">--</span></li>
                    <li><strong>Internet:</strong> Conexión estable es obligatoria.</li>
                </ul>

                <h5 class="text-primary mt-4 mb-2">Condiciones</h5>
                <ul>
                    <li><strong>Calificación mínima:</strong> 90% de respuestas correctas (varía por categoría).</li>
                    <li><strong>Envío de resultados:</strong> Automáticamente a tu autoescuela.</li>
                    <li><strong>Reclamos:</strong> Solo ante la Dirección General de Tráfico.</li>
                    <li><strong>Confidencialidad:</strong> El contenido del examen es privado.</li>
                </ul>

                <div class="important-note">
                    Al iniciar el examen, aceptas todas las condiciones establecidas. No compartas tu contenido ni repitas el intento.
                </div>

                <div class="text-center mt-4">
                    <a href="evaluacion.php?examen_id=<?= htmlspecialchars($examen_id )?>" class="btn btn-start">
                        <i class="bi bi-play-circle-fill me-2"></i> Comenzar Examen
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>

<footer class="text-center text-muted py-4">
    &copy; <?= date('Y') ?> Autoescuela Online. Todos los derechos reservados.
</footer>

<!-- Scripts -->
<script src="../js/bootstrap.bundle.min.js"></script>

<script>
  
    const totalPreguntas = <?= json_encode((int)$preguntas) ?>;
    const totalTiempo = <?= json_encode((int)$duracion) ?>;
    document.getElementById('exam-questions').textContent = totalPreguntas + ' preguntas';
 
    document.getElementById('exam-duration').textContent = ((totalTiempo * 50) / 60).toFixed(2) + ' minutos';
    
    document.getElementById('exam-attempts').textContent = '1 intento';
</script>
</body>
</html>
