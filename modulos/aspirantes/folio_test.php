<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test de Examen</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles_folio_test.css">
</head>
<body class="d-flex flex-column min-vh-100">
    <main class="container my-5 flex-grow-1">
        <section class="text-center">
            <h1>Test de Examen</h1>
            <p>Responde las siguientes preguntas.</p>
            <div id="timer" class="timer">Tiempo restante: <span>10:00</span></div>
            <div id="questions-remaining" class="questions-remaining">Preguntas restantes: <span>2</span></div>
            <div class="row">
                <div class="col-md-6">
                    <div class="card shadow">
                        <div class="card-body">
                            <h5 class="card-title" id="question">Pregunta 1:</h5>
                            <p class="card-text" id="question-text">¿Cuál es la señal de tráfico que indica "Ceda el paso"?</p>
                            <img src="señal_ceda_el_paso.png" alt="Señal Ceda el paso" class="img-fluid">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="pregunta1" id="opcion1">
                                <label class="form-check-label" for="opcion1">Opción 1</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="pregunta1" id="opcion2">
                                <label class="form-check-label" for="opcion2">Opción 2</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="pregunta1" id="opcion3">
                                <label class="form-check-label" for="opcion3">Opción 3</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button id="submit-button" class="btn btn-primary mt-4">Enviar Respuesta</button>
        </section>
    </main>

    <footer class="bg-light text-center py-3">
        <p>&copy; 2024 Autoescuela Online</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="../js/js_folio_test.js"></script>
</body>
</html>