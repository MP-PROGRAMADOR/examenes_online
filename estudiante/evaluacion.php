<?php
session_start();

// Verificar si hay sesión activa
if (!isset($_SESSION['estudiante'])) {
    header("Location: index.php");
    exit();
}

// Acceder a los datos del estudiante
$estudiante = $_SESSION['estudiante'] ?? [];
$nombre = $estudiante['estudiante_nombre'] ?? '';
$codigo = $estudiante['codigo_acceso'] ?? '';

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examen en Curso</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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

        /* Estilos para el temporizador general */
        .timer-container {
            display: flex;
            align-items: center;
            gap: 15px;
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
            color: #28a745;
            /* Verde para el temporizador general */
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

            <div
                class="bg-light px-4 py-3 border-bottom sticky-top z-1 d-flex justify-content-between align-items-center">
                <div class="progress rounded-pill flex-grow-1 me-3" style="height: 0.9rem;">
                    <div class="progress-bar bg-success" id="progresoBarra" style="width: 0%;">
                    </div>
                </div>
                <div class="timer-container">
                    <div class="timer-box general">
                        <i class="bi bi-hourglass-split timer-icon"></i>
                        <span id="temporizadorGeneral">00:00</span>
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

    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1100">
        <div id="toastFinalizado" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi bi-check-circle me-2"></i>
                    <span id="mensajeToast">Examen finalizado. Redirigiendo...</span>
                </div>
            </div>
        </div>
    </div>




    <script>
        // --- 1. CONFIGURACIÓN Y VARIABLES GLOBALES ---
        const params = new URLSearchParams(window.location.search);
        let examenId = params.get('examen_id');
        let preguntaActual = 0;
        let totalPreguntas = 0;
        let listaPreguntas = [];
        let seleccionUsuario = null;
        let tiempoRestanteGeneral = 0;
        let intervaloTemporizadorGeneral;
        let finalizandoProceso = false;

        // --- 2. ELEMENTOS DEL DOM ---
        const UI = {
            btnSiguiente: document.getElementById('btnSiguiente'),
            preguntaContenido: document.getElementById('preguntaContenido'),
            preguntaTitulo: document.getElementById('preguntaTitulo'),
            progresoBarra: document.getElementById('progresoBarra'),
            temporizadorDisplay: document.getElementById('temporizadorGeneral'),
            confirmarSalir: document.getElementById('confirmarSalir'),
            modalSalir: new bootstrap.Modal(document.getElementById('modalSalir')),
            toast: new bootstrap.Toast(document.getElementById('toastFinalizado'))
        };

        // --- 3. SEGURIDAD Y BLOQUEOS ---
        const inicializarSeguridad = () => {
            // Bloqueo de navegación
            window.onbeforeunload = () => "¿Seguro que quieres salir? El examen se cancelará.";

            // Evitar botón atrás del navegador
            history.pushState(null, null, location.href);
            window.onpopstate = () => {
                history.go(1);
                UI.modalSalir.show();
            };

            // Detectar cambio de pestaña
            document.addEventListener('visibilitychange', () => {
                if (document.visibilityState === 'hidden') {
                    clearInterval(intervaloTemporizadorGeneral);
                    window.onbeforeunload = null;
                }
            });

            // Evitar F5 / Ctrl+R
            document.addEventListener('keydown', e => {
                if ((e.ctrlKey && (e.key === 'r' || e.key === 'R')) || e.key === 'F5') {
                    e.preventDefault();
                    UI.modalSalir.show();
                }
            });

            // Bloqueo dispositivos pequeños
            const esMovil = window.innerWidth <= 768 || /android|iphone|ipad/.test(navigator.userAgent.toLowerCase());
            if (esMovil) {
                alert('Este examen requiere una pantalla más grande.');
                window.location.href = 'cerrar_sesion.php?motivo=Dispositivo_no_permitido';
            }
        };

        // --- 4. TEMPORIZADOR ---
        const temporizador = {
            formatear: (s) => `${Math.floor(s / 60).toString().padStart(2, '0')}:${(s % 60).toString().padStart(2, '0')}`,
            iniciar: () => {
                clearInterval(intervaloTemporizadorGeneral);
                UI.temporizadorDisplay.innerText = temporizador.formatear(tiempoRestanteGeneral);
                intervaloTemporizadorGeneral = setInterval(() => {
                    tiempoRestanteGeneral--;
                    UI.temporizadorDisplay.innerText = temporizador.formatear(tiempoRestanteGeneral);
                    if (tiempoRestanteGeneral <= 0) {
                        clearInterval(intervaloTemporizadorGeneral);
                        finalizarExamenPorTiempo();
                    }
                }, 1000);
            }
        };

        // --- 5. LÓGICA DEL EXAMEN (CARGA Y RENDERIZADO) ---
        async function cargarPreguntas() {
            if (!examenId || isNaN(examenId)) {
                window.location.href = 'cerrar_sesion.php';
                return;
            }

            const datos = new FormData();
            datos.append('examen_id', examenId);

            try {
                const res = await fetch('../api/obtener_preguntas.php', {
                    method: 'POST',
                    body: datos
                });
                const data = await res.json();

                if (!data.status) throw new Error(data.message);

                listaPreguntas = data.preguntas;
                totalPreguntas = data.preguntas.length;
                tiempoRestanteGeneral = data.duracion;

                if (totalPreguntas > 0 && tiempoRestanteGeneral > 0) {
                    mostrarPregunta();
                    temporizador.iniciar();
                } else {
                    throw new Error("Sin preguntas o duración inválida.");
                }
            } catch (err) {
                alert("Error: " + err.message);
                window.location.href = 'cerrar_sesion.php';
            }
        }

        function mostrarPregunta() {
            if (preguntaActual >= totalPreguntas) return finalizarExamen();

            const p = listaPreguntas[preguntaActual];
            seleccionUsuario = null;
            UI.btnSiguiente.disabled = true;

            // Actualizar Progreso
            const progreso = Math.round(((preguntaActual + 1) / totalPreguntas) * 100);
            UI.progresoBarra.style.width = `${progreso}%`;
            UI.progresoBarra.innerText = `Pregunta ${preguntaActual + 1} de ${totalPreguntas}`;

            // Título e Imagen
            UI.preguntaTitulo.innerHTML = `<h5 class="fw-semibold mb-0 text-primary"><i class="bi bi-question-circle me-2"></i> ${p.texto}</h5>`;
            const imgHTML = (p.imagenes?.[0]?.ruta_imagen) ?
                `<div class="text-center mb-4"><img src="../api/${p.imagenes[0].ruta_imagen}" class="img-fluid rounded shadow-sm" style="max-height: 250px;"></div>` : '';

            // Opciones
            let opcionesHTML = '';
            const items = p.tipo === 'vf' ? [{
                id: "1",
                texto: "Verdadero"
            }, {
                id: "0",
                texto: "Falso"
            }] : p.opciones;
            const tipoInput = (p.tipo === 'multiple') ? 'checkbox' : 'radio';

            items.forEach(op => {
                opcionesHTML += `
            <div class="opcion p-3 border rounded-3 mb-3 bg-light d-flex justify-content-between align-items-center cursor-pointer" onclick="seleccionarOpcion(this, '${tipoInput}')">
                <label class="mb-0 flex-grow-1 cursor-pointer">${op.texto}</label>
                <input class="form-check-input fs-4 ms-3" type="${tipoInput}" name="opciones" value="${op.id}">
            </div>`;
            });

            UI.preguntaContenido.innerHTML = `${imgHTML}<form id="formPregunta">${opcionesHTML}</form>`;
        }

        // Helper para selección
        window.seleccionarOpcion = (el, tipo) => {
            const input = el.querySelector('input');
            if (tipo === 'radio') {
                document.querySelectorAll('input[name="opciones"]').forEach(r => r.checked = false);
            }
            input.checked = !input.checked;
            UI.btnSiguiente.disabled = !document.querySelector('input[name="opciones"]:checked');
        };

        // --- 6. GUARDADO Y FINALIZACIÓN ---
        async function guardarRespuestaYContinuar() {
            const seleccionados = Array.from(document.querySelectorAll('input[name="opciones"]:checked')).map(i => i.value);
            const datos = new FormData();
            datos.append('examen_id', examenId);
            datos.append('pregunta_id', listaPreguntas[preguntaActual].pregunta_id);
            datos.append('tipo_pregunta', listaPreguntas[preguntaActual].tipo);

            if (seleccionados.length > 0) {
                seleccionados.forEach(id => datos.append('opciones[]', id));
            } else {
                datos.append('opciones[]', '');
            }

            try {
                const res = await fetch('../api/guardar_respuesta.php', {
                    method: 'POST',
                    body: datos
                });
                const data = await res.json();

                if (data.status && data.finalizado) {
                    finalizarExamen();
                } else {
                    preguntaActual++;
                    mostrarPregunta();
                }
            } catch (error) {
                console.error("Error guardando:", error);
                preguntaActual++;
                mostrarPregunta();
            }
        }

        async function procesarFinalizacion() {
            if (finalizandoProceso) return;
            finalizandoProceso = true;

            clearInterval(intervaloTemporizadorGeneral);
            window.onbeforeunload = null;

            const seleccionados = Array.from(document.querySelectorAll('input[name="opciones"]:checked')).map(i => i.value);
            const datos = new FormData();
            datos.append('examen_id', examenId);
            datos.append('forzar_finalizacion', 'true');

            if (seleccionados.length > 0 && listaPreguntas[preguntaActual]) {
                seleccionados.forEach(id => datos.append('opciones[]', id));
                datos.append('pregunta_id', listaPreguntas[preguntaActual].pregunta_id);
                datos.append('tipo_pregunta', listaPreguntas[preguntaActual].tipo);
            } else {
                // Importante: Enviar opciones vacías si no hay selección para no romper la validación
                datos.append('opciones[]', '');
                datos.append('pregunta_id', listaPreguntas[preguntaActual]?.pregunta_id || 0);
                datos.append('tipo_pregunta', listaPreguntas[preguntaActual]?.tipo || 'unica');
            }

            try {
                const res = await fetch('../api/guardar_respuesta.php', {
                    method: 'POST',
                    body: datos
                });
                const resultado = await res.json();

                // AQUÍ ESTÁ EL CAMBIO PARA EL 100%
                const nota = (resultado.data && resultado.data.calificacion_final !== undefined) ?
                    resultado.data.calificacion_final :
                    0;

                const toastEl = document.getElementById('toastFinalizado');
                const mensajeToast = document.getElementById('mensajeToast');

                toastEl.classList.remove('bg-danger');
                toastEl.classList.add('bg-success');

                mensajeToast.innerHTML = `
            <div class="text-center text-white">
                <i class="bi bi-check-circle-fill fs-2 mb-2"></i>
                <h6 class="mb-1">Examen Finalizado</h6>
                <p class="mb-0">Tu calificación: <b>${nota}%</b></p>
            </div>`;

                UI.toast.show();
                setTimeout(() => {
                    window.location.href = 'cerrar_sesion.php';
                }, 4000);

            } catch (e) {
                console.error("Error finalizando:", e);
                window.location.href = 'cerrar_sesion.php';
            }
        }




        const finalizarExamen = () => procesarFinalizacion('finalizado');

        async function finalizarExamenPorTiempo() {
            const haySeleccion = document.querySelector('input[name="opciones"]:checked');
            if (haySeleccion) await guardarRespuestaYContinuar();
            procesarFinalizacion('tiempo_agotado');
        }

        // --- 7. EVENT LISTENERS ---
        UI.btnSiguiente.addEventListener('click', guardarRespuestaYContinuar);
        UI.confirmarSalir.addEventListener('click', () => {
            UI.modalSalir.hide();
            procesarFinalizacion('abandonado');
        });

        // --- 8. INICIO ---
        inicializarSeguridad();
        cargarPreguntas();
    </script>






    <script src="../js/bootstrap.bundle.min.js"></script>


</body>

</html>