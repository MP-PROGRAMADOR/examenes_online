<?php
session_start();

// Verificar si hay sesión activa
if (!isset($_SESSION['estudiante'])) {
    header("Location: index.php");
    exit();
}

// Acceder a los datos del estudiante
$estudiante = $_SESSION['estudiante'];
$nombre = $estudiante['nombre'] . ' ' . $estudiante['apellidos'];
$codigo = $estudiante['usuario'];

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examen en Curso</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Segoe UI', sans-serif;
        }

        .pregunta-card {
            max-width: 800px;
            margin: auto;
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        .pregunta-imagen {
            max-width: 100%;
            height: auto;
            margin-bottom: 20px;
            border-radius: 10px;
        }

        .barra-progreso {
            height: 20px;
            background-color: #e9ecef;
            border-radius: 10px;
            overflow: hidden;
        }

        .barra-progreso .progreso {
            background-color: #0d6efd;
            height: 100%;
            transition: width 0.4s ease;
        }

        .opcion {
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 12px;
            margin-bottom: 12px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .opcion:hover {
            background-color: #f8f9fa;
        }

        .icono-pregunta {
            font-size: 1.5rem;
            color: #0d6efd;
            margin-right: 10px;
        }

        /* Estilo moderno de tarjeta de examen */
        .pregunta-card {
            background: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 1rem;
            padding: 2rem;
        }

        /* Botón de acción */
        #btnSiguiente:disabled {
            opacity: 0.6;
            pointer-events: none;
        }

        /* Transición suave para barra */
        #progresoBarra {
            transition: width 0.4s ease-in-out;
        }

        /* Opcional: animación al mostrar pregunta */
        #preguntaContenido {
            animation: fadeIn 0.4s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Estilos para los temporizadores */
        .timer-container {
            display: flex;
            align-items: center;
            gap: 15px; /* Espacio entre los temporizadores */
        }

        .timer-box {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 8px 15px;
            font-size: 1rem;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .timer-box.general {
            color: #28a745; /* Verde para el temporizador general */
            font-size: 1.1rem;
        }

        .timer-box.question {
            color: #dc3545; /* Rojo para el temporizador de pregunta */
            font-size: 1.1rem;
        }

        .timer-icon {
            font-size: 1.2rem;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <div id="vistaExamen" class="shadow-lg rounded-4 bg-white overflow-hidden position-relative">

            <div class="bg-light px-4 py-3 border-bottom sticky-top z-1 d-flex justify-content-between align-items-center">
                <div class="progress rounded-pill flex-grow-1 me-3" style="height: 0.9rem;">
                    <div class="progress-bar bg-success" id="progresoBarra" style="width: 0%;">
                    </div>
                </div>
                <div class="timer-container">
                    <div class="timer-box general">
                        <i class="bi bi-hourglass-split timer-icon"></i>
                        <span id="temporizadorGeneral">00:00</span>
                    </div>
                    <div class="timer-box question">
                        <i class="bi bi-clock-fill timer-icon"></i>
                        <span id="temporizadorPregunta">00:00</span>
                    </div>
                </div>
            </div>

            <div class="card border-0 rounded-0">

                <div class="card-header bg-white border-bottom py-4">
                    <h5 id="preguntaTitulo" class="mb-0 fw-semibold text-primary d-flex align-items-start">
                        <i class="bi bi-question-circle me-2"></i> Texto de la pregunta aquí
                    </h5>
                </div>

                <div class="card-body" id="preguntaContenido">
                    </div>

                <div class="card-footer bg-white border-top text-end py-4">
                    <button id="btnSiguiente" class="btn btn-primary px-4 py-2 rounded-pill shadow" disabled>
                        Responder y Continuar <i class="bi bi-arrow-right-circle ms-2"></i>
                    </button>
                </div>

            </div>

        </div>
    </div>

    <div class="modal fade" id="modalSalir" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title"><i class="bi bi-exclamation-triangle"></i> Salir del Examen</h5>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas salir del examen? Esto puede terminar tu intento.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button id="confirmarSalir" class="btn btn-danger">Salir</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const params = new URLSearchParams(window.location.search);
        let examenId = params.get('examen_id');
        let preguntaActual = 0;
        let totalPreguntas = 0;
        let listaPreguntas = [];
        let seleccionUsuario = null;

        // Temporizadores
        let tiempoRestanteGeneral = 0; // Duración total del examen en segundos
        let intervaloTemporizadorGeneral; // ID del intervalo para el temporizador general

        let tiempoRestantePregunta = 0; // Duración de la pregunta actual en segundos
        let intervaloTemporizadorPregunta; // ID del intervalo para el temporizador de pregunta
        const TIEMPO_VF = 10; // Segundos para preguntas Verdadero/Falso
        const TIEMPO_OTRAS = 15; // Segundos para preguntas Múltiple/Única

        // Elementos del DOM
        const btnSiguiente = document.getElementById('btnSiguiente');
        const preguntaContenido = document.getElementById('preguntaContenido');
        const modalSalir = new bootstrap.Modal(document.getElementById('modalSalir'));
        const confirmarSalir = document.getElementById('confirmarSalir');
        const progresoBarra = document.getElementById('progresoBarra');
        const temporizadorGeneralDisplay = document.getElementById('temporizadorGeneral');
        const temporizadorPreguntaDisplay = document.getElementById('temporizadorPregunta');

        // Evitar navegación/salida
        window.onbeforeunload = () => "¿Seguro que quieres salir? El examen se cancelará.";

        // Detectar cambio de pestaña o minimización - CONSIDERACIÓN: Podrías hacer que el examen finalice automáticamente aquí
        // document.addEventListener('visibilitychange', () => {
        //     if (document.visibilityState === 'hidden') {
        //         // Opcional: Finalizar examen si el usuario cambia de pestaña
        //         // clearInterval(intervaloTemporizadorGeneral);
        //         // clearInterval(intervaloTemporizadorPregunta);
        //         // window.onbeforeunload = null;
        //         // window.location.href = 'aspirante.php?motivo=abandono';
        //     }
        // });

        // Evitar recargar con Ctrl+R o F5
        document.addEventListener('keydown', e => {
            if ((e.ctrlKey && (e.key === 'r' || e.key === 'R')) || e.key === 'F5') {
                e.preventDefault();
                modalSalir.show();
            }
        });

        // Confirmar salida desde el modal
        confirmarSalir.addEventListener('click', () => {
            clearInterval(intervaloTemporizadorGeneral);
            clearInterval(intervaloTemporizadorPregunta);
            window.onbeforeunload = null;
            window.location.href = 'aspirante.php';
        });

        // 🚫 Bloqueo para dispositivos pequeños
        const esDispositivoPequenio = window.innerWidth <= 768 || /android|iphone|ipad/.test(navigator.userAgent.toLowerCase());
        if (esDispositivoPequenio) {
            alert('Este examen no está disponible para dispositivos pequeños. Por favor, usa una pantalla más grande.');
            window.location.href = 'aspirante.php?motivo=Dispositivo_no_permitido';
        }

        // --- Funciones de Temporizador ---
        function formatTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const remainingSeconds = seconds % 60;
            return `${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
        }

        function iniciarTemporizadorGeneral() {
            clearInterval(intervaloTemporizadorGeneral); // Asegura que no haya intervalos duplicados
            temporizadorGeneralDisplay.innerText = formatTime(tiempoRestanteGeneral);

            intervaloTemporizadorGeneral = setInterval(() => {
                tiempoRestanteGeneral--;
                temporizadorGeneralDisplay.innerText = formatTime(tiempoRestanteGeneral);

                if (tiempoRestanteGeneral <= 0) {
                    clearInterval(intervaloTemporizadorGeneral);
                    clearInterval(intervaloTemporizadorPregunta); // Detener el temporizador de pregunta también
                    finalizarExamenPorTiempo();
                }
            }, 1000);
        }

        function iniciarTemporizadorPregunta() {
            clearInterval(intervaloTemporizadorPregunta); // Reinicia el temporizador de la pregunta anterior

            const pregunta = listaPreguntas[preguntaActual];
            tiempoRestantePregunta = (pregunta.tipo === 'vf') ? TIEMPO_VF : TIEMPO_OTRAS;
            temporizadorPreguntaDisplay.innerText = formatTime(tiempoRestantePregunta);

            intervaloTemporizadorPregunta = setInterval(() => {
                tiempoRestantePregunta--;
                temporizadorPreguntaDisplay.innerText = formatTime(tiempoRestantePregunta);

                if (tiempoRestantePregunta <= 0) {
                    clearInterval(intervaloTemporizadorPregunta);
                    // Si el tiempo de la pregunta se agota, guarda la respuesta (si existe) y avanza
                    guardarRespuestaYContinuar();
                }
            }, 1000);
        }

        // --- Lógica del Examen ---
        function cargarPreguntas() {
            if (!examenId || isNaN(examenId)) {
                alert("Examen inválido (ID no definido en URL).");
                window.location.href = 'aspirante.php';
                return;
            }

            const datos = new FormData();
            datos.append('examen_id', examenId);

            fetch('../api/obtener_preguntas.php', {
                method: 'POST',
                body: datos
            })
            .then(res => {
                if (!res.ok) {
                    throw new Error(`HTTP error! status: ${res.status}`);
                }
                return res.json();
            })
            .then(res => {
                if (!res.status) {
                    console.error("Error al cargar preguntas:", res.message);
                    alert("No se pudieron cargar las preguntas: " + res.message);
                    window.location.href = 'aspirante.php';
                } else {
                    listaPreguntas = res.preguntas;
                    totalPreguntas = res.preguntas.length; // Asegúrate de que esta variable está bien escrita
                    tiempoRestanteGeneral = res.duracion;

                    if (totalPreguntas > 0 && tiempoRestanteGeneral > 0) {
                        mostrarPregunta();
                        iniciarTemporizadorGeneral(); // Inicia el temporizador general
                    } else {
                        alert("El examen no tiene preguntas o la duración es inválida.");
                        window.location.href = 'aspirante.php';
                    }
                }
            })
            .catch(err => {
                alert('Error inesperado al cargar preguntas: ' + err.message);
                console.error(err);
                window.location.href = 'aspirante.php';
            });
        }

        function mostrarPregunta() {
            if (preguntaActual >= totalPreguntas) {
                return finalizarExamen();
            }

            const pregunta = listaPreguntas[preguntaActual];
            seleccionUsuario = null; // Reiniciar selección
            btnSiguiente.disabled = true;

            // Iniciar o reiniciar el temporizador de pregunta
            iniciarTemporizadorPregunta();

            // Actualizar barra de progreso
            const progreso = Math.round(((preguntaActual + 1) / totalPreguntas) * 100);
            progresoBarra.style.width = `${progreso}%`;
            progresoBarra.innerText = `Pregunta ${preguntaActual + 1} de ${totalPreguntas}`;

            // Actualizar título de la pregunta
            document.getElementById('preguntaTitulo').innerHTML = `
                <h5 class="fw-semibold mb-0 text-primary d-flex align-items-start">
                    <i class="bi bi-question-circle me-2"></i> ${pregunta.texto}
                </h5>`;

            // Renderizar imagen (si existe)
            const imagenHTML = (pregunta.imagenes && pregunta.imagenes.length > 0 && pregunta.imagenes[0].ruta_imagen)
                ? `<div class="text-center border border-2 mb-4 p-3 rounded-3 bg-light">
                        <img src="../api/${pregunta.imagenes[0].ruta_imagen}"
                            class="img-fluid rounded-3 shadow-sm"
                            style="max-width: 400px; max-height: 300px; object-fit: contain;"
                            alt="Imagen relacionada">
                    </div>`
                : '';

            // Renderizar opciones
            let opcionesHTML = '';
            const crearOpcion = (id, texto, tipo) => `
                <div class="opcion p-3 border rounded-3 mb-3 bg-light shadow-sm d-flex justify-content-between align-items-center fs-5" data-option-id="${id}">
                    <label class="mb-0 flex-grow-1" for="opcion-${id}">${texto}</label>
                    <input class="form-check-input fs-4 ms-3" type="${tipo}" name="opciones" value="${id}" id="opcion-${id}">
                </div>`;

            if (pregunta.tipo === 'vf') {
                opcionesHTML += crearOpcion("1", "Verdadero", "radio");
                opcionesHTML += crearOpcion("0", "Falso", "radio");
            } else {
                pregunta.opciones.forEach(op => {
                    const tipoInput = pregunta.tipo === 'multiple' ? 'checkbox' : 'radio';
                    opcionesHTML += crearOpcion(op.id, op.texto, tipoInput);
                });
            }

            document.getElementById('preguntaContenido').innerHTML = `
                ${imagenHTML}
                <form id="formPregunta" class="mt-3">${opcionesHTML}</form>
            `;

            // Event Listeners para opciones
            document.querySelectorAll('.opcion').forEach(opcionDiv => {
                opcionDiv.addEventListener('click', function() {
                    const input = this.querySelector('input[name="opciones"]');
                    if (input) {
                        // Para radios, desmarcar otros y marcar este
                        if (input.type === 'radio') {
                            document.querySelectorAll('input[name="opciones"]').forEach(radio => radio.checked = false);
                        }
                        input.checked = !input.checked; // Alternar para checkboxes, marcar para radios

                        // Asegurarse de que el botón Siguiente se habilite si hay alguna selección
                        seleccionUsuario = Array.from(document.querySelectorAll('input[name="opciones"]:checked')).length > 0;
                        btnSiguiente.disabled = !seleccionUsuario;
                    }
                });
            });
            // Asegurar que el cambio en el input también actualice el estado
            document.querySelectorAll('input[name="opciones"]').forEach(input => {
                input.addEventListener('change', () => {
                    seleccionUsuario = Array.from(document.querySelectorAll('input[name="opciones"]:checked')).length > 0;
                    btnSiguiente.disabled = !seleccionUsuario;
                });
            });
        }

        async function guardarRespuestaYContinuar() {
            const seleccionados = Array.from(document.querySelectorAll('input[name="opciones"]:checked'))
                .map(input => input.value);

            const datos = new FormData();
            datos.append('examen_id', examenId);
            datos.append('pregunta_id', listaPreguntas[preguntaActual].pregunta_id);
            datos.append('tipo_pregunta', listaPreguntas[preguntaActual].tipo);
            // Si no hay selección, envía un array vacío o un valor nulo, según tu API
            if (seleccionados.length > 0) {
                seleccionados.forEach(id => datos.append('opciones[]', id));
            } else {
                datos.append('opciones[]', ''); // O enviar un indicador de "sin respuesta" si tu API lo espera
            }

            try {
                const res = await fetch('../api/guardar_respuesta.php', {
                    method: 'POST',
                    body: datos
                });

                if (!res.ok) {
                    throw new Error(`HTTP error! status: ${res.status}`);
                }
                const data = await res.json();

                if (data.success) {
                    console.log("Respuesta guardada:", data.data);
                    preguntaActual++;
                    mostrarPregunta(); // Muestra la siguiente pregunta o finaliza
                } else {
                    console.error("Error al guardar la respuesta:", data.message);
                    // Decide cómo manejar un error al guardar la respuesta. Podrías continuar o alertar.
                    // Por ahora, solo logueamos el error y continuamos para no detener el examen.
                    preguntaActual++;
                    mostrarPregunta();
                }
            } catch (error) {
                console.error('Error en la solicitud de guardar respuesta:', error);
                alert('Hubo un problema al guardar tu respuesta. Por favor, verifica tu conexión o contacta soporte.');
                // En un escenario real, podrías querer pausar el examen o forzar la salida
                preguntaActual++; // Intentar avanzar a la siguiente pregunta a pesar del error
                mostrarPregunta();
            }
        }

        // Listener para el botón "Siguiente"
        btnSiguiente.addEventListener('click', () => {
            clearInterval(intervaloTemporizadorPregunta); // Detener el temporizador de la pregunta actual
            guardarRespuestaYContinuar();
        });

        function finalizarExamen() {
            clearInterval(intervaloTemporizadorGeneral);
            clearInterval(intervaloTemporizadorPregunta);
            window.onbeforeunload = null; // Quitar el aviso de salida

            alert('¡Has finalizado el examen!');
            // Opcional: Enviar una señal final al servidor de que el examen ha sido completado
            // fetch('../api/finalizar_examen.php', { method: 'POST', body: JSON.stringify({ examen_id: examenId, motivo: 'completado' }), headers: { 'Content-Type': 'application/json' }})...

            window.location.href = `aspirante.php?examen_id=${examenId}&estado=finalizado`;
        }

        function finalizarExamenPorTiempo() {
            clearInterval(intervaloTemporizadorGeneral);
            clearInterval(intervaloTemporizadorPregunta);
            window.onbeforeunload = null;

            alert('¡Se ha agotado el tiempo para el examen! El examen ha finalizado.');
            // Opcional: Enviar una señal final al servidor de que el examen ha terminado por tiempo
            // fetch('../api/finalizar_examen.php', { method: 'POST', body: JSON.stringify({ examen_id: examenId, motivo: 'tiempo_agotado' }), headers: { 'Content-Type': 'application/json' }})...

            window.location.href = `aspirante.php?examen_id=${examenId}&estado=tiempo_agotado`;
        }

        // Iniciar la carga de preguntas al cargar la página
        cargarPreguntas();
    </script>

</body>

</html>