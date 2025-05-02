<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Simulación: Examen de Autoescuela</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .opcion:hover {
            background-color: #f0f0f0;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .card-header {
            background-color: #007bff;
            color: white;
            font-size: 1.2rem;
            font-weight: bold;
        }

        .card-body {
            background-color: #f9f9f9;
        }

        .form-check {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 15px;
            transition: border-color 0.3s ease;
        }

        .form-check:hover {
            border-color: #007bff;
        }

        .img-fluid {
            border-radius: 10px;
            margin-bottom: 10px;
            max-height: 200px;
        }

        .progress-bar {
            background-color: #007bff;
        }

        .btn-finalizar {
            display: none;
        }

        .btn-siguiente {
            width: 100%;
            font-size: 1.2rem;
            padding: 12px;
        }

        .pregunta-container {
            margin-top: 30px;
        }

        .pregunta-container .card {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .pregunta-container .card-header {
            background-color: #4caf50;
            color: white;
        }

        .badge {
            font-size: 0.9rem;
        }

        .timer-container {
            font-size: 1.2rem;
            font-weight: bold;
            color: red;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>🧪 Simulación: Cargando...</h3>
        <div class="fs-5 text-danger timer-container">
            Tiempo restante: <span id="timer"></span>
        </div>
    </div>

    <form id="form-examen" action="../php/respuesta_aspirante.php" method="POST">
        <input type="hidden" name="examen_id" value="1">
        <div id="pregunta-container" class="pregunta-container"></div>
        <div class="text-end">
            <button type="button" id="btn-siguiente" class="btn btn-primary btn-siguiente">
                <i class="bi bi-arrow-right-circle-fill"></i> Siguiente pregunta
            </button>
            <button type="submit" class="btn btn-success btn-lg btn-finalizar" id="btn-finalizar">
                <i class="bi bi-send-check-fill"></i> Finalizar examen
            </button>
        </div>
    </form>
</div>

<script>
    let examen_id = 1;
    let pregunta_id = 0;

    let minutos = 30;
    let tiempo = minutos * 60;

    function updateTimer() {
        const minutos = Math.floor(tiempo / 60);
        const segundos = tiempo % 60;
        document.getElementById("timer").textContent =
            `${minutos}:${segundos < 10 ? '0' : ''}${segundos}`;
        if (tiempo > 0) {
            tiempo--;
        } else {
            clearInterval(timerInterval);
            alert("⏰ ¡Tiempo finalizado! Se enviará el examen automáticamente.");
            document.getElementById("form-examen").submit();
        }
    }

    const timerInterval = setInterval(updateTimer, 1000);
    updateTimer();

    function cargarPregunta() {
        fetch(`obtener_pregunta.php?examen_id=${examen_id}&pregunta_id=${pregunta_id}`)
            .then(response => response.json())
            .then(data => {
                if (data.finalizado) {
                    document.getElementById('pregunta-container').innerHTML =
                        '<div class="alert alert-success">¡Examen finalizado! Gracias por participar.</div>';
                    document.getElementById('btn-finalizar').style.display = 'block';
                    document.getElementById('btn-siguiente').style.display = 'none';
                } else {
                    const pregunta = data.pregunta;
                    const opciones = data.opciones;
                    const imagenes = data.imagenes;

                    let preguntaHtml = `
                        <div class="card mb-4 shadow-sm">
                            <div class="card-header bg-light fw-semibold">
                                ${pregunta_id + 1}. ${pregunta.texto_pregunta}
                            </div>
                            ${imagenes.map(img => `<img src="${img.ruta_imagen}" class="img-fluid mb-2">`).join('')}
                            <div class="card-body">
                                ${opciones.map(opcion => `
                                    <div class="form-check">
                                        <input class="form-check-input" type="${pregunta.tipo_pregunta === 'unica' ? 'radio' : 'checkbox'}" 
                                            name="respuestas[${pregunta.id}][]" value="${opcion.id}" id="opcion${opcion.id}">
                                        <label class="form-check-label" for="opcion${opcion.id}">
                                            ${opcion.texto_opcion}
                                        </label>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    `;
                    document.getElementById('pregunta-container').innerHTML = preguntaHtml;
                    pregunta_id++;
                }
            });
    }

    cargarPregunta();

    document.getElementById('btn-siguiente').addEventListener('click', function () {
        cargarPregunta();
    });
</script>
</body>
</html>
