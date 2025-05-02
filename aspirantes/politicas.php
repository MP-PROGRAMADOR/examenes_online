<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información del Examen - Autoescuela Online</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles_realizar.css">
    <style>
        /* Estilos personalizados para reflejar disciplina, perseverancia y actitud */
        body {
            background-color: #f8f9fa;
            color: #343a40;
            font-family: 'Montserrat', sans-serif;
        }

        .header-shadow {
            box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075);
        }

        .main-section {
            padding: 40px 0;
        }

        .info-card {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 30px;
            background-color: white;
        }

        .info-card h2 {
            color: #007bff;
            font-weight: bold;
            margin-bottom: 20px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }

        .info-card h3 {
            color: #28a745;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .info-card p {
            line-height: 1.7;
            color: #6c757d;
        }

        .important-note {
            background-color: #fff3cd;
            border: 1px solid #ffeeba;
            color: #85640c;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }

        .btn-start-exam {
            background-color: #28a745;
            border-color: #28a745;
            color: white;
            padding: 12px 25px;
            font-size: 1.1em;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .btn-start-exam:hover {
            background-color: #1e7e34;
            border-color: #1e7e34;
        }

        .back-link {
            display: block;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
    

    <main class="container my-5 flex-grow-1 main-section">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="info-card">
                    <h2>Información Importante del Examen</h2>
                    <p class="lead">Antes de comenzar, por favor lee atentamente las políticas y condiciones del examen.</p>

                    <h3>Políticas del Examen</h3>
                    <ul>
                        <li><strong>Duración del Examen:</strong> Este examen tiene una duración máxima de <span id="exam-duration">60 minutos</span>. Una vez que inicies, el tiempo comenzará a correr y no se detendrá.</li>
                        <li><strong>Número de Preguntas:</strong> El examen consta de <span id="exam-questions">30 preguntas</span> de opción múltiple.</li>
                        <li><strong>Navegación:</strong> Puedes navegar libremente entre las preguntas. Revisa bien tus respuestas antes de finalizar.</li>
                        <li><strong>Finalización:</strong> Una vez que hayas respondido todas las preguntas o se agote el tiempo, podrás finalizar el examen y ver tu puntuación.</li>
                        <li><strong>Integridad Académica:</strong> Se espera que realices este examen de manera individual y sin ayuda externa. Cualquier intento de plagio o copia resultará en la descalificación.</li>
                        <li><strong>Conexión a Internet:</strong> Asegúrate de tener una conexión a internet estable durante todo el examen para evitar interrupciones.</li>
                    </ul>

                    <h3>Condiciones del Examen</h3>
                    <ul>
                        <li><strong>Requisitos:</strong> Para realizar este examen, debes haber completado el módulo correspondiente y estar registrado en la plataforma.</li>
                        <li><strong>Puntuación:</strong> La puntuación se basará en el número de respuestas correctas. Cada pregunta tiene el mismo valor.</li>
                        <li><strong>Resultados:</strong> Los resultados del examen estarán disponibles inmediatamente después de la finalización.</li>
                        <li><strong>Revisión:</strong> En caso de dudas sobre alguna pregunta o resultado, puedes contactar a tu instructor a través de la plataforma.</li>
                        <li><strong>Intentos:</strong> Tendrás un máximo de <span id="exam-attempts">un intento</span> para realizar este examen.</li>
                        <li><strong>Confidencialidad:</strong> El contenido de este examen es confidencial y no debe ser compartido con otros estudiantes.</li>
                    </ul>

                    <div class="important-note">
                        <strong>Importante:</strong> Al hacer clic en "Comenzar Examen", confirmas que has leído y aceptas todas las políticas y condiciones mencionadas anteriormente. ¡Mucho éxito!
                    </div>

                    <a href="./examen.php" class="btn btn-start-exam btn-block mt-4">Comenzar Examen</a>
                    <a href="aspirante.php" class="back-link">Volver</a>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-light text-center py-3">
        <p>&copy; 2024 Autoescuela Online</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Puedes personalizar la duración, número de preguntas e intentos desde JavaScript si es dinámico
        document.getElementById('exam-duration').textContent = '45 minutos';
        document.getElementById('exam-questions').textContent = '25 preguntas';
        document.getElementById('exam-attempts').textContent = 'dos intentos';
    </script>
</body>
</html>