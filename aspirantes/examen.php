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
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
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

    <div id="contenedor-pregunta" class="mb-4"></div>

<!-- Botón siguiente -->
<button id="btnSiguiente" class="btn btn-primary">Siguiente</button>

</div>
<!-- 
<script>
let preguntaActual = 0;
let totalPreguntas = 0;
let tiempoRestante = 0;
let intervaloTemporizador;
const examenId = <?php //echo $examen_id; ?>;
const estudianteId = <?php //echo $estudiante_id; ?>;

document.getElementById('btnSiguiente').addEventListener('click', () => {
    enviarRespuesta(); // envía y luego carga siguiente
});

function iniciarTemporizador(tiempo, tipo) {
    tiempoRestante = tiempo;

    const barra = document.getElementById('barraProgreso');
    barra.classList.remove('bg-danger', 'bg-warning', 'bg-success');
    barra.classList.add('bg-info');

    if (intervaloTemporizador) clearInterval(intervaloTemporizador);

    intervaloTemporizador = setInterval(() => {
        tiempoRestante--;
        const porcentaje = ((tiempo / tiempoRestante) * 100).toFixed(0);
        barra.style.width = `${(100 - porcentaje)}%`;
        if (tiempoRestante <= 0) {
            clearInterval(intervaloTemporizador);
            enviarRespuesta(); // auto-envía al terminar tiempo
        }
    }, 1000);
}

function obtenerTiempoPorTipo(tipo) {
    switch(tipo) {
        case 'multiple': return 60;
        case 'respuesta_unica': return 40;
        case 'vf': return 20;
        default: return 30;
    }
}

async function cargarPregunta() {
    try {
        const res = await fetch(`../php/obtener_pregunta.php?examen_id=${examenId}&estudiante_id=${estudianteId}`);
        const data = await res.json();

        if (data.error) {
            document.getElementById('contenedor-pregunta').innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
            document.getElementById('btnSiguiente').disabled = true;
            return;
        }

        totalPreguntas = data.total_pregunta ?? 1;
        mostrarPregunta(data.pregunta);
        preguntaActual++;

        // Actualizar barra de progreso
        const progreso = (preguntaActual / totalPreguntas) * 100;
        document.getElementById('barraProgreso').style.width = `${progreso}%`;
        document.getElementById('textoProgreso').innerText = `Pregunta ${preguntaActual} de ${totalPreguntas}`;

        const segundos = obtenerTiempoPorTipo(data.pregunta.tipo_pregunta);
        iniciarTemporizador(segundos, data.pregunta.tipo_pregunta);

    } catch (error) {
        console.error(error);
        document.getElementById('contenedor-pregunta').innerHTML = `<div class="alert alert-danger">Error al cargar la pregunta.</div>`;
    }
}

function mostrarPregunta(pregunta) {
    const contenedor = document.getElementById('contenedor-pregunta');
    let contenido = `
        <div class="pregunta-card">
            <h5 class="mb-3">Pregunta:</h5>
            <p>${pregunta.texto_pregunta}</p>`;

    if (pregunta.tipo_contenido === 'con_ilustracion' && pregunta.ruta_imagen) {
        contenido += `<img src="../uploads/${pregunta.ruta_imagen}" class="img-fluid mb-3 rounded shadow" alt="Ilustración">`;
    }

    contenido += `<form id="form-opciones">`;

    if (pregunta.tipo_pregunta === 'vf') {
        contenido += `
            <div class="opcion-card">
                <input type="radio" name="opciones[]" value="1" id="vf_1">
                <label for="vf_1">Verdadero</label>
            </div>
            <div class="opcion-card">
                <input type="radio" name="opciones[]" value="0" id="vf_0">
                <label for="vf_0">Falso</label>
            </div>`;
    } else {
        const inputType = (pregunta.tipo_pregunta === 'respuesta_unica') ? 'radio' : 'checkbox';
        pregunta.opciones.forEach((opcion, index) => {
            contenido += `
                <div class="opcion-card">
                    <input type="${inputType}" name="opciones[]" value="${opcion.id}" id="opcion_${index}">
                    <label for="opcion_${index}">${opcion.texto_opcion}</label>
                </div>`;
        });
    }

    contenido += `</form></div>`;
    contenedor.innerHTML = contenido;
}

async function enviarRespuesta() {
    const form = document.getElementById('form-opciones');
    const formData = new FormData(form);
    formData.append('examen_id', examenId);
    formData.append('estudiante_id', estudianteId);
    formData.append('pregunta_actual', preguntaActual);

    try {
        const res = await fetch('../php/guardar_respuesta.php', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();
        if (data.success) {
            cargarPregunta(); // cargar siguiente si se guardó
        } else {
            alert(data.message || 'No se pudo guardar la respuesta');
        }
    } catch (err) {
        console.error(err);
        alert('Error al enviar la respuesta');
    }
}

// Iniciar con la primera pregunta
cargarPregunta();
</script>
 -->


 <script>
let preguntaActual = 0;
let totalPreguntas = 0;
let tiposPreguntas = [];
let tiempoRestante = 0;
let temporizadorInterval;

const examenId = <?php echo $examen_id; ?>;
const estudianteId = <?php echo $estudiante_id; ?>;

document.getElementById('btnSiguiente').addEventListener('click', () => {
    cargarPregunta();
});

window.addEventListener('DOMContentLoaded', async () => {
    const res = await fetch(`../php/obtener_pregunta.php?examen_id=${examenId}&estudiante_id=${estudianteId}`);
    const data = await res.json();

    if (data.error) {
        document.getElementById('contenedor-pregunta').innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
        return;
    }

    tiposPreguntas = data.preguntas_tipos;
    totalPreguntas = data.total_preguntas;

    // Calcular tiempo total global
    tiempoRestante = calcularTiempoTotal(tiposPreguntas);
    iniciarTemporizador();

    // Mostrar primera pregunta
    mostrarPregunta(data.pregunta);
    preguntaActual++;

    actualizarProgreso();
});

function calcularTiempoTotal(tipos) {
    let total = 0;
    tipos.forEach(tipo => {
        switch (tipo) {
            case 'verdadero_falso':
                total += 15;
                break;
            case 'respuesta_unica':
                total += 25;
                break;
            case 'multiple_opcion':
                total += 35;
                break;
        }
    });
    return total;
}

function iniciarTemporizador() {
    actualizarTemporizador();

    temporizadorInterval = setInterval(() => {
        tiempoRestante--;
        if (tiempoRestante <= 0) {
            clearInterval(temporizadorInterval);
            alert('¡Tiempo finalizado!');
            document.getElementById('btnSiguiente').disabled = true;
        }
        actualizarTemporizador();
    }, 1000);
}

function actualizarTemporizador() {
    const minutos = Math.floor(tiempoRestante / 60).toString().padStart(2, '0');
    const segundos = (tiempoRestante % 60).toString().padStart(2, '0');
    document.getElementById('temporizador').textContent = `${minutos}:${segundos}`;
}

async function cargarPregunta() {
    const res = await fetch(`../php/obtener_pregunta.php?examen_id=${examenId}&estudiante_id=${estudianteId}`);
    const data = await res.json();

    if (data.error) {
        document.getElementById('contenedor-pregunta').innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
        return;
    }

    mostrarPregunta(data.pregunta);
    preguntaActual++;
    actualizarProgreso();
}

function actualizarProgreso() {
    const porcentaje = (preguntaActual / totalPreguntas) * 100;
    document.getElementById('barraProgreso').style.width = `${porcentaje}%`;
    document.getElementById('textoProgreso').textContent = `Pregunta ${preguntaActual} de ${totalPreguntas}`;
}

function mostrarPregunta(pregunta) {
    const contenedor = document.getElementById('contenedor-pregunta');
    let contenido = `
        <div class="pregunta-card">
            <h5 class="card-title">Pregunta:</h5>
            <p>${pregunta.texto_pregunta}</p>`;

    if (pregunta.tipo_contenido === 'con_ilustracion' && pregunta.ruta_imagen) {
        contenido += `<img src="../uploads/${pregunta.ruta_imagen}" class="img-fluid mb-3" alt="Ilustración">`;
    }

    contenido += `<form id="form-opciones">`;

    pregunta.opciones.forEach((opcion, index) => {
        let inputType = 'checkbox';
        if (pregunta.tipo_pregunta === 'respuesta_unica') inputType = 'radio';
        if (pregunta.tipo_pregunta === 'verdadero_falso') inputType = 'radio';

        contenido += `
            <div class="opcion-card">
                <input type="${inputType}" name="opciones[]" value="${opcion.id}" id="opcion_${index}">
                <label for="opcion_${index}">${opcion.texto_opcion}</label>
            </div>`;
    });

    contenido += `</form></div>`;
    contenedor.innerHTML = contenido;
}
</script>

</body>
</html>
