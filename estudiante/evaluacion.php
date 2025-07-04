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

        /* Estilos para el temporizador */
        #temporizador {
            font-size: 1.2rem;
            font-weight: bold;
            color: #dc3545; /* Rojo para el temporizador */
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
                <div id="temporizador">00:00</div>
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
        let tiempoRestante = 0; // Duración total del examen en segundos
        let intervaloTemporizador; // Para almacenar el ID del setInterval

        console.log('id de examen: ' + examenId)


        const btnSiguiente = document.getElementById('btnSiguiente');
        const preguntaContenido = document.getElementById('preguntaContenido');
        const modalSalir = new bootstrap.Modal(document.getElementById('modalSalir'));
        const confirmarSalir = document.getElementById('confirmarSalir');
        const progresoBarra = document.getElementById('progresoBarra');
        const temporizadorDisplay = document.getElementById('temporizador'); // Elemento para mostrar el temporizador

        // Evitar navegación/salida
        window.onbeforeunload = () => "¿Seguro que quieres salir? El examen se cancelará.";

        // Detectar cambio de pestaña o minimización
        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'hidden') {
                // Puedes optar por finalizar el examen o mostrar una advertencia más estricta aquí
                // Por ahora, solo mostraremos el modal de confirmación si intentan salir (como con F5)
                // O podrías hacer que termine el examen automáticamente:
                // window.onbeforeunload = null;
                // window.location.href = 'aspirante.php?motivo=abandono';
            }
        });


        // Evitar recargar con Ctrl+R o F5
        document.addEventListener('keydown', e => {
            if ((e.ctrlKey && (e.key === 'r' || e.key === 'R')) || e.key === 'F5') {
                e.preventDefault();
                modalSalir.show();
            }
        });

        // Confirmar salida desde el modal
        confirmarSalir.addEventListener('click', () => {
            window.onbeforeunload = null;
            // Detener el temporizador antes de redirigir
            clearInterval(intervaloTemporizador);
            window.location.href = 'aspirante.php';
        });


        // 🚫 Dispositivos pequeños

        const esDispositivoPequenio = window.innerWidth <= 768 || /android|iphone|ipad/.test(navigator.userAgent.toLowerCase());

        if (esDispositivoPequenio) {
            alert('Este examen no está disponible para dispositivos pequeños.');
            window.location.href = 'aspirante.php?motivo=Dispositivo_no_permitido';
        }


        // Función para actualizar el display del temporizador
        function actualizarTemporizadorDisplay() {
            const minutos = Math.floor(tiempoRestante / 60);
            const segundos = tiempoRestante % 60;
            temporizadorDisplay.innerText = 
                `${minutos.toString().padStart(2, '0')}:${segundos.toString().padStart(2, '0')}`;
        }

        // Función para iniciar el temporizador
        function iniciarTemporizador() {
            actualizarTemporizadorDisplay(); // Muestra el tiempo inicial
            intervaloTemporizador = setInterval(() => {
                tiempoRestante--;
                actualizarTemporizadorDisplay();

                if (tiempoRestante <= 0) {
                    clearInterval(intervaloTemporizador);
                    finalizarExamenPorTiempo();
                }
            }, 1000); // Cada segundo
        }

        function cargarPreguntas() {
            if (!examenId || isNaN(examenId)) {
                alert("Examen inválido (ID no definido en URL)");
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
                        console.log(res.message);
                        alert("No se pudieron cargar las preguntas: " + res.message);
                        window.location.href = 'aspirante.php'; // Redirige si no hay preguntas
                    } else {
                        listaPreguntas = res.preguntas;
                        totalPreguntas = listaPreguntas.length;
                        tiempoRestante = res.duracion; // Asigna la duración del JSON
                        
                        if (totalPreguntas > 0 && tiempoRestante > 0) {
                            mostrarPregunta();
                            iniciarTemporizador(); // Inicia el temporizador una vez cargadas las preguntas
                        } else {
                            alert("El examen no tiene preguntas o la duración es inválida.");
                            window.location.href = 'aspirante.php';
                        }
                    }
                })
                .catch(err => {
                    alert('Error inesperado al cargar preguntas: ' + err.message);
                    console.error(err);
                    window.location.href = 'aspirante.php'; // Redirige en caso de error de fetch
                });

        }

        function mostrarPregunta() {
            if (preguntaActual >= totalPreguntas) return finalizarExamen();

            const pregunta = listaPreguntas[preguntaActual];
            seleccionUsuario = null;
            btnSiguiente.disabled = true;

            // Actualizar barra de progreso
            const progreso = Math.round(((preguntaActual + 1) / totalPreguntas) * 100);
            progresoBarra.style.width = `${progreso}%`;
            progresoBarra.innerText = `Pregunta ${preguntaActual + 1} de ${totalPreguntas}`;

            // Insertar título de la pregunta en el header
            const tituloHTML = `
                <h5 class="fw-semibold mb-0 text-primary d-flex align-items-start">
                    <i class="bi bi-question-circle me-2"></i> ${pregunta.texto}
                </h5>`;
            document.getElementById('preguntaTitulo').innerHTML = tituloHTML;

            /* ----------------- seccion de rendirizado de la imagen -----------------------*/
            // Imagen (si hay)
            const imagenHTML = pregunta.imagenes && pregunta.imagenes[0]
                    ? `<div class="text-center border border-2 mb-4 p-3 rounded-3 bg-light">
                            <img src="../api/${pregunta.imagenes[0].ruta_imagen}" 
                                class="img-fluid rounded-3 shadow-sm" 
                                style="max-width: 400px; max-height: 300px; object-fit: contain;" 
                                alt="Imagen relacionada">
                        </div>`
                    : '';

            /* --------- fin de la seccion de renderizado de la imagen ------ */

            // Opciones HTML: checkbox o radio, con buena legibilidad
            let opcionesHTML = '';
            const crearOpcion = (id, texto, tipo) => `
                <div class="opcion p-3 border rounded-3 mb-3 bg-light shadow-sm d-flex justify-content-between align-items-center fs-5" style="cursor: pointer;">
                    <label class="mb-0 flex-grow-1" for="opcion-${id}">${texto}</label>
                    <input class="form-check-input fs-4 ms-3" type="${tipo}" name="opciones" value="${id}" id="opcion-${id}">
                </div>`; // Añadido id único para el input y el label

            if (pregunta.tipo === 'vf') {
                opcionesHTML += crearOpcion("1", "Verdadero", "radio");
                opcionesHTML += crearOpcion("0", "Falso", "radio");
            } else {
                pregunta.opciones.forEach(op => {
                    const tipoInput = pregunta.tipo === 'multiple' ? 'checkbox' : 'radio';
                    opcionesHTML += crearOpcion(op.id, op.texto, tipoInput);
                });
            }

            // Renderizar contenido en el cuerpo del card
            document.getElementById('preguntaContenido').innerHTML = `
        ${imagenHTML}
        <form id="formPregunta" class="mt-3">${opcionesHTML}</form>
    `;

            // Activar botón siguiente al seleccionar opción
            document.querySelectorAll('input[name="opciones"]').forEach(input => {
                input.addEventListener('change', () => {
                    seleccionUsuario = true;
                    btnSiguiente.disabled = false;
                });
            });
            // Añadir event listener a los div.opcion para que también activen el input
            document.querySelectorAll('.opcion').forEach(opcionDiv => {
                opcionDiv.addEventListener('click', function() {
                    const input = this.querySelector('input[name="opciones"]');
                    if (input) {
                        input.checked = true;
                        input.dispatchEvent(new Event('change')); // Dispara el evento change
                    }
                });
            });
        }


        btnSiguiente.addEventListener('click', () => {
            const seleccionados = Array.from(document.querySelectorAll('input[name="opciones"]:checked'))
                .map(input => input.value);

            if (seleccionados.length === 0) {
                alert("Por favor, selecciona una opción antes de continuar.");
                return;
            }

            const datos = new FormData();
            datos.append('examen_id', examenId);
            datos.append('pregunta_id', listaPreguntas[preguntaActual].pregunta_id);
            datos.append('tipo_pregunta', listaPreguntas[preguntaActual].tipo);
            seleccionados.forEach(id => datos.append('opciones[]', id));

            fetch('../api/guardar_respuesta.php', {
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
                    if (res.success) {
                        preguntaActual++;
                        console.log(res.data);
                        mostrarPregunta(); // Muestra la siguiente pregunta o finaliza
                    } else {
                        console.log(res.message);
                        alert("Error al guardar la respuesta: " + res.message);
                    }
                })
                .catch(error => {
                    console.error('Error al guardar la respuesta:', error);
                    alert('Hubo un problema al guardar tu respuesta. Por favor, inténtalo de nuevo.');
                });
        });

        function finalizarExamen() {
            // Detener el temporizador
            clearInterval(intervaloTemporizador); 
            alert('¡Has finalizado el examen!');
            // Puedes enviar una última señal al servidor de que el examen ha terminado
            // fetch('../api/finalizar_examen.php', {
            //     method: 'POST',
            //     body: JSON.stringify({ examen_id: examenId, motivo: 'completado' }),
            //     headers: { 'Content-Type': 'application/json' }
            // }).then(response => response.json())
            // .then(data => console.log('Examen finalizado en el servidor:', data))
            // .catch(error => console.error('Error al finalizar examen en servidor:', error));

            window.onbeforeunload = null; // Quitar el aviso de salida
            window.location.href = `aspirante.php?examen_id=${examenId}&estado=finalizado`;
        }

        function finalizarExamenPorTiempo() {
            // Detener el temporizador si no se ha detenido ya
            clearInterval(intervaloTemporizador);
            alert('¡Se ha agotado el tiempo! El examen ha finalizado.');
            // Aquí podrías enviar un evento al servidor indicando que el examen finalizó por tiempo
            // fetch('../api/finalizar_examen.php', {
            //     method: 'POST',
            //     body: JSON.stringify({ examen_id: examenId, motivo: 'tiempo_agotado' }),
            //     headers: { 'Content-Type': 'application/json' }
            // }).then(response => response.json())
            // .then(data => console.log('Examen finalizado por tiempo en el servidor:', data))
            // .catch(error => console.error('Error al finalizar examen por tiempo en servidor:', error));

            window.onbeforeunload = null; // Quitar el aviso de salida
            window.location.href = `aspirante.php?examen_id=${examenId}&estado=tiempo_agotado`;
        }

        // Iniciar la carga de preguntas al cargar la página
        cargarPreguntas();

    </script>

</body>

</html>