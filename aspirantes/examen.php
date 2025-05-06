<?php
session_start();
require_once '../config/conexion.php';
$pdo = $pdo->getConexion();
$estudiante = $_SESSION['estudiante'];
$estudiante_id = $estudiante['id'];
$examen_id = $_GET['id'] ?? 0;

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Examen en Progreso</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #eef2f7;
            font-family: 'Segoe UI', sans-serif;
        }
        .pregunta-card {
            background: #ffffff;
            border-radius: 1rem;
            box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.075);
            padding: 2rem;
            transition: all 0.3s ease;
        }
        .pregunta-card:hover {
            box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.15);
        }
        .progreso {
            height: 1rem;
            border-radius: 1rem;
        }
        .opcion-card {
            border: 2px solid #dee2e6;
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            margin-bottom: 0.75rem;
            transition: background 0.2s ease, border-color 0.2s ease;
            cursor: pointer;
        }
        .opcion-card:hover {
            background-color: #f1f1f1;
            border-color: #007bff;
        }
        .opcion-card input[type="radio"],
        .opcion-card input[type="checkbox"] {
            margin-right: 0.5rem;
        }
        #btnSiguiente {
            font-weight: bold;
            padding: 0.6rem 1.5rem;
            border-radius: 0.5rem;
        }
        .timer {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container py-4">
    <div class="text-center mb-4">
        <h2 class="fw-bold">Resolución del Examen</h2>
    </div>

    <div class="mb-4">
        <div class="progress">
            <div id="barraProgreso" class="progress-bar progreso bg-success" role="progressbar" style="width: 0%"></div>
        </div>
        <small id="textoProgreso" class="text-muted">Pregunta 0 de ?</small>
    </div>

    <div id="contenedor-pregunta" class="pregunta-card mb-3">
        <p class="text-center text-muted">Cargando pregunta...</p>
    </div>

    <div class="text-end">
        <button id="btnSiguiente" class="btn btn-primary" disabled>Responder y continuar</button>
    </div>
</div>

<script>
let preguntaActual = 0;
let totalPreguntas = 0;
const examenId = <?php echo $examen_id; ?>;
const estudianteId = <?php echo $estudiante_id; ?>;
let intentoExamenId = null;
let preguntasMostradas = [];

// Establecer tiempos por tipo de pregunta
const tiemposPorPregunta = {
    'unica': 30,
    'vf': 30,
    'multiple': 45
};

// Iniciar intento
window.addEventListener('DOMContentLoaded', async () => {
    const res = await fetch('../php/iniciar_intento.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ examen_id: examenId, estudiante_id: estudianteId })
    });
    const data = await res.json();

    if (data.error) {
        alert(data.error);
        return;
    }

    intentoExamenId = data.intento_examen_id;
    totalPreguntas = data.total_preguntas;
    cargarPregunta();
});

async function cargarPregunta() {
    const res = await fetch(`../php/obtener_pregunta.php?examen_id=${examenId}&indice=${preguntaActual}`);
    const data = await res.json();

    if (data.fin) {
        document.getElementById('contenedor-pregunta').innerHTML = `
            <div class="alert alert-success text-center">
                <h4 class="mb-2">¡Has finalizado el examen!</h4>
                <p>Gracias por tu participación.</p>
            </div>`;
        document.getElementById('btnSiguiente').style.display = 'none';
        actualizarBarraProgreso();
        return;
    }

    mostrarPregunta(data.pregunta);
    iniciarTemporizador(data.pregunta.tipo_pregunta);
    document.getElementById('btnSiguiente').disabled = false;
    actualizarBarraProgreso();
}

function mostrarPregunta(p) {
    const contenedor = document.getElementById('contenedor-pregunta');
    let html = `<h5 class="mb-3 fw-bold">Pregunta ${preguntaActual + 1}:</h5>`;
    html += `<p class="fs-5">${p.texto_pregunta}</p>`;

    if (p.ruta_imagen) {
        html += `<div class="text-center mb-3"><img src="../uploads/${p.ruta_imagen}" class="img-fluid rounded shadow-sm" style="max-height: 300px;"></div>`;
    }

    const tipo = p.tipo_pregunta;

    html += `<form id="formPregunta">`;

    if (tipo === 'unica' || tipo === 'vf') {
        p.opciones.forEach(o => {
            html += `
                <label class="opcion-card d-flex align-items-center">
                    <input class="form-check-input" type="radio" name="respuesta" value="${o.id}" id="op${o.id}">
                    ${o.texto_opcion}
                </label>`;
        });
    } else if (tipo === 'multiple') {
        p.opciones.forEach(o => {
            html += `
                <label class="opcion-card d-flex align-items-center">
                    <input class="form-check-input" type="checkbox" name="respuesta[]" value="${o.id}" id="op${o.id}">
                    ${o.texto_opcion}
                </label>`;
        });
    }

    html += `</form>`;
    contenedor.innerHTML = html;
}

function iniciarTemporizador(tipoPregunta) {
    let tiempoRestante = tiemposPorPregunta[tipoPregunta] || 30;
    const timerElement = document.createElement('div');
    timerElement.className = 'timer';
    timerElement.id = 'timer';
    timerElement.textContent = `Tiempo restante: ${tiempoRestante} segundos`;
    document.getElementById('contenedor-pregunta').appendChild(timerElement);

    const timer = setInterval(() => {
        if (tiempoRestante > 0) {
            tiempoRestante--;
            document.getElementById('timer').textContent = `Tiempo restante: ${tiempoRestante} segundos`;
        } else {
            clearInterval(timer);
            document.getElementById('btnSiguiente').click();
        }
    }, 1000);
}

function actualizarBarraProgreso() {
    const barra = document.getElementById('barraProgreso');
    const texto = document.getElementById('textoProgreso');
    const porcentaje = ((preguntaActual) / totalPreguntas) * 100;

    barra.style.width = `${porcentaje}%`;
    texto.textContent = `Pregunta ${preguntaActual + 1} de ${totalPreguntas}`;
}

// Botón siguiente
document.getElementById('btnSiguiente').addEventListener('click', async () => {
    const seleccion = document.querySelectorAll('input[name="respuesta"]:checked, input[name="respuesta[]"]:checked');
    const valores = Array.from(seleccion).map(el => el.value);

    if (valores.length === 0) {
        alert("Debes seleccionar al menos una opción.");
        return;
    }

    document.getElementById('btnSiguiente').disabled = true;

    const res = await fetch('../php/guardar_respuesta.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            intento_examen_id: intentoExamenId,
            pregunta_id: <?php echo $id; ?>,
            respuestas: valores
        })
    });
    const data = await res.json();

    if (data.error) {
        alert(data.error);
        return;
    }

    preguntaActual++;
    cargarPregunta();
});
</script>
</body>
</html>
