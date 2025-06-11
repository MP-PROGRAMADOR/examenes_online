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
    </style>
</head>

<body>
    <div class="container py-5">
        <div id="vistaExamen" class="shadow-lg rounded-4 bg-white overflow-hidden position-relative">

            <!-- Progreso tipo navbar -->
            <div class="bg-light px-4 py-3 border-bottom sticky-top z-1">
                <div class="progress rounded-pill" style="height: 0.9rem;">
                    <div class="progress-bar bg-success" id="progresoBarra" style="width: 0%;">
                        <!-- texto opcional como: Pregunta 2 de 10 -->
                    </div>
                </div>
            </div>

            <!-- Tarjeta principal -->
            <div class="card border-0 rounded-0">

                <!-- Cabecera con pregunta -->
                <div class="card-header bg-white border-bottom py-4">
                    <h5 id="preguntaTitulo" class="mb-0 fw-semibold text-primary d-flex align-items-start">
                        <i class="bi bi-question-circle me-2"></i> Texto de la pregunta aquí
                    </h5>
                </div>

                <!-- Cuerpo: imagen + opciones -->
                <div class="card-body" id="preguntaContenido">

                </div>

                <!-- Pie: botón continuar -->
                <div class="card-footer bg-white border-top text-end py-4">
                    <button id="btnSiguiente" class="btn btn-primary px-4 py-2 rounded-pill shadow" disabled>
                        Responder y Continuar <i class="bi bi-arrow-right-circle ms-2"></i>
                    </button>
                </div>

            </div>

        </div>
    </div>

    <!-- Modal de confirmación de salida -->

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

        console.log('id de examen: ' + examenId)


        const btnSiguiente = document.getElementById('btnSiguiente');
        const preguntaContenido = document.getElementById('preguntaContenido');
        const modalSalir = new bootstrap.Modal(document.getElementById('modalSalir'));
        const confirmarSalir = document.getElementById('confirmarSalir');
        const progresoBarra = document.getElementById('progresoBarra');

        // Evitar navegación/salida
        window.onbeforeunload = () => "¿Seguro que quieres salir? El examen se cancelará.";

        // Detectar cambio de pestaña o minimización
        //  document.addEventListener('visibilitychange', () => {
        //      if (document.visibilityState === 'hidden') {
        //          // Cancelar el examen automáticamente al cambiar de pestaña
        //          window.onbeforeunload = null;
        //          window.location.href = 'aspirante.php?motivo=abandono';
        //      }
        //  });





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
            window.location.href = 'aspirante.php';
        });


        // 🚫 Dispositivos pequeños

        const esDispositivoPequenio = window.innerWidth <= 768 || /android|iphone|ipad/.test(navigator.userAgent.toLowerCase());

        if (esDispositivoPequenio) {
            alert('Este examen no está disponible para dispositivos pequeños.');
            window.location.href = 'aspirante.php?motivo=Dispositivo_no_permitido';
        }




        function cargarPreguntas() {
            // console.log("examenId:", examenId);

            if (!examenId || isNaN(examenId)) {
                alert("Examen inválido (ID no definido en URL)");
                return;
            }
            const datos = new FormData();
            datos.append('examen_id', examenId); // examenId debe estar definido globalmente

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
                    } else {
                        //console.log(res.preguntas);
                        listaPreguntas = res.preguntas;
                        totalPreguntas = listaPreguntas.length;
                        mostrarPregunta();
                    }
                })
                .catch(err => {
                    alert('Error inesperado: ' + err.message);
                    console.error(err);
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
            const imagenHTML = pregunta.imagenes[0]
                    ? `<div class="text-center border border-2 mb-4 p-3 rounded-3 bg-light">
                            <img src="../api/${pregunta.imagenes[0].ruta_imagen}" 
                                class="img-fluid rounded-3 shadow-sm" 
                                style="max-width: 400px; max-height: 300px; object-fit: contain;" 
                                alt="Imagen relacionada">
                        </div>`
                    : '';

           // console.log(pregunta)
            /* --------- fin de la seccion de renderizado de la imagen ------ */

            // Opciones HTML: checkbox o radio, con buena legibilidad
            let opcionesHTML = '';
            const crearOpcion = (id, texto, tipo) => `
                <div class="opcion p-3 border rounded-3 mb-3 bg-light shadow-sm d-flex justify-content-between align-items-center fs-5" style="cursor: pointer;">
                    <label class="mb-0 flex-grow-1" for="${id}">${texto}</label>
                    <input class="form-check-input fs-4 ms-3" type="${tipo}" name="opciones" value="${id}" id="${id}">
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
        }






        btnSiguiente.addEventListener('click', () => {


            const seleccionados = Array.from(document.querySelectorAll('input[name="opciones"]:checked'))
                .map(input => input.value);

            if (seleccionados.length === 0) return;


            const datos = new FormData();
            datos.append('examen_id', examenId);
            datos.append('pregunta_id', listaPreguntas[preguntaActual].pregunta_id);
            datos.append('tipo_pregunta', listaPreguntas[preguntaActual].tipo);
            seleccionados.forEach(id => datos.append('opciones[]', id));

            fetch('../api/guardar_respuesta.php', {
                method: 'POST',
                body: datos
            })
                .then(res => res.json())
                .then(res => { // <-- Aquí capturas correctamente el objeto de respuesta JSON
                    if (res.success) {
                        preguntaActual++;
                        console.log(res.data);
                        // Puedes volver a habilitar esta línea si quieres continuar automáticamente
                         // mostrarPregunta();
                    } else {
                        console.log(res.message);
                    }
                })
                .catch(error => {
                    console.error('Error al guardar la respuesta:', error);
                });

        });

        function finalizarExamen() {
            alert('¡Has finalizado el examen!');
            window.location.href = `aspirante.php?examen_id=${examenId}`;
        }

        cargarPreguntas();



    </script>

</body>

</html>