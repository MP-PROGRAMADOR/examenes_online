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
    </style>
</head>

<body>
    <div class="container py-5">
        <div id="vistaExamen" class="pregunta-card">
            <div class="mb-3">
                <div class="barra-progreso">
                    <div class="progreso" id="barraProgreso" style="width: 0%;"></div>
                </div>
            </div>
            <div id="preguntaContenido">
                <!-- Pregunta dinámica -->
            </div>
            <div class="text-end mt-4">
                <button id="btnSiguiente" class="btn btn-primary" disabled>Responder y Continuar <i
                        class="bi bi-arrow-right-circle ms-2"></i></button>
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

        console.log('id de examen: ' + examenId)


        const btnSiguiente = document.getElementById('btnSiguiente');
        const preguntaContenido = document.getElementById('preguntaContenido');
        const modalSalir = new bootstrap.Modal(document.getElementById('modalSalir'));
        const confirmarSalir = document.getElementById('confirmarSalir');
        const progresoBarra = document.getElementById('progresoBarra');

        // Evitar navegación/salida
        window.onbeforeunload = () => "¿Seguro que quieres salir? El examen se cancelará.";

        // Detectar cambio de pestaña o minimización
         document.addEventListener('visibilitychange', () => {
             if (document.visibilityState === 'hidden') {
                 // Cancelar el examen automáticamente al cambiar de pestaña
                 window.onbeforeunload = null;
                 window.location.href = 'aspirante.php?motivo=abandono';
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
            window.location.href = 'aspirante.php';
        });


        // 🚫 Dispositivos pequeños

        const esDispositivoPequenio = window.innerWidth <= 768 || /android|iphone|ipad/.test(navigator.userAgent.toLowerCase());

        if (esDispositivoPequenio) {
            alert('Este examen no está disponible para dispositivos pequeños.');
            window.location.href = 'aspirante.php?motivo=Dispositivo_no_permitido';
        }




        function cargarPreguntas() {
            console.log("examenId:", examenId);

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
                .then(res => res.json())
                .then(res => {
                    if (!res.status) {
                        console.log(res.message)
                    } else {
                        console.log(res.preguntas)
                        listaPreguntas = res.preguntas;
                        totalPreguntas = listaPreguntas.length;
                        mostrarPregunta();

                    }
                })
                .catch(() => alert('Error inesperado.'));
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

            // Mostrar imagen si existe
            const imagenHTML = pregunta.imagen
                ? `<div class="text-center mb-3">
         <img src="${pregunta.imagen}" class="img-fluid rounded shadow" alt="Imagen pregunta">
       </div>`
                : '';

            // Opciones: tipo única, múltiple o vf
            let opcionesHTML = '';
            if (pregunta.tipo === 'vf') {
                opcionesHTML = `
      <div class="form-check opcion mb-2 p-2 border rounded">
        <input class="form-check-input" type="radio" name="opciones" value="1" id="vf_verdadero">
        <label class="form-check-label" for="vf_verdadero">Verdadero</label>
      </div>
      <div class="form-check opcion p-2 border rounded">
        <input class="form-check-input" type="radio" name="opciones" value="0" id="vf_falso">
        <label class="form-check-label" for="vf_falso">Falso</label>
      </div>
    `;
            } else {
                pregunta.opciones.forEach(op => {
                    opcionesHTML += `
        <div class="form-check opcion p-2 rounded border mb-2">
          <input class="form-check-input" type="${pregunta.tipo === 'multiple' ? 'checkbox' : 'radio'}" 
                 name="opciones" value="${op.id}" id="op${op.id}">
          <label class="form-check-label" for="op${op.id}">${op.texto}</label>
        </div>
      `;
                });
            }

            // Cargar contenido de la pregunta
            preguntaContenido.innerHTML = `
    <h5 class="mb-3"><i class="bi bi-question-circle text-primary"></i> ${pregunta.texto}</h5>
    ${imagenHTML}
    <form id="formPregunta">${opcionesHTML}</form>
  `;

            // Habilitar siguiente si selecciona
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
            datos.append('pregunta_id', listaPreguntas[preguntaActual].id);
            seleccionados.forEach(id => datos.append('opciones[]', id));

            fetch('../api/guardar_respuesta.php', {
                method: 'POST',
                body: datos
            })
                .then(res => res.json())
                .then(() => {
                    preguntaActual++;
                    mostrarPregunta();
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