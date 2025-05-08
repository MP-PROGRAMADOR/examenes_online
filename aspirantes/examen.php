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

        <div id="progreso"></div>
        <div id="contenedor-pregunta"></div>


    </div>

    <script>
    let examenId = <?php echo $examen_id; ?>;
    let contenedor = document.getElementById('contenedor-pregunta');
    let progreso = document.getElementById('progreso');

    function cargarPregunta() {
        fetch(`../php/obtener_pregunta.php?examen_id=${examenId}`)
            .then(res => res.json())
            .then(data => {
                if (data.finalizado) {
                    contenedor.innerHTML = "<h3>¡Has completado el examen!</h3>";
                    return;
                }
                mostrarPregunta(data);
            })
            .catch(err => {
                console.error("Error al cargar la pregunta:", err);
                contenedor.innerHTML = "<p>Error al cargar pregunta</p>";
            });
    }

    function mostrarPregunta(p) {
        let opcionesHtml = '';
        const tipo = p.tipo_pregunta;

        // Tipo VF personalizado
        if (tipo === 'vf') {
            opcionesHtml += `
                <div>
                    <label>
                        <input type="radio" name="opcion_vf" value="v"> Verdadero
                    </label>
                </div>
                <div>
                    <label>
                        <input type="radio" name="opcion_vf" value="f"> Falso
                    </label>
                </div>
            `;
        } else {
            // Unica o multiple
            p.opciones.forEach(op => {
                const inputType = tipo === 'multiple' ? 'checkbox' : 'radio';
                opcionesHtml += `
                    <div>
                        <label>
                            <input type="${inputType}" name="opcion" value="${op.id}">
                            ${op.texto_opcion}
                        </label>
                    </div>
                `;
            });
        }

        const imagenHtml = p.tipo_contenido === 'ilustracion' && p.ruta_imagen
            ? `<img src="../imagenes/${p.ruta_imagen}" style="max-width: 300px;">`
            : '';

        contenedor.innerHTML = `
            <div>
                <h4>Pregunta ${p.pregunta_actual} de ${p.total_preguntas}</h4>
                ${imagenHtml}
                <p>${p.texto_pregunta}</p>
                <form id="form-respuesta">
                    ${opcionesHtml}
                    <button type="submit">Siguiente</button>
                </form>
            </div>
        `;

        document.getElementById('form-respuesta').onsubmit = (e) => {
            e.preventDefault();
            enviarRespuesta(p.pregunta_id, tipo);
        };
    }

    function enviarRespuesta(pregunta_id, tipo) {
        const form = document.getElementById('form-respuesta');
        let seleccion = [];

        if (tipo === 'vf') {
            const seleccionada = form.querySelector('input[name="opcion_vf"]:checked');
            if (!seleccionada) {
                alert("Debes seleccionar Verdadero o Falso.");
                return;
            }
            seleccion = [seleccionada.value]; // 'v' o 'f'
        } else {
            const seleccionados = Array.from(form.elements['opcion']).filter(el => el.checked);
            if (seleccionados.length === 0) {
                alert("Debes seleccionar una opción.");
                return;
            }
            seleccion = seleccionados.map(el => el.value);
        }

        fetch('../php/guardar_respuestas.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                pregunta_id: pregunta_id,
                opciones: seleccion,
                tipo: tipo // opcional, por si necesitas manejar en el backend
            })
        })
            .then(res => res.json())
            .then(res => {
                if (res.ok) {
                    cargarPregunta(); // Cargar la siguiente pregunta
                } else {
                    alert("Error al guardar la respuesta");
                }
            });
    }

    cargarPregunta(); // Inicializar
</script>




   <!--  <script>
        //let examenId = new URLSearchParams(window.location.search).get('examen_id');
        let examenId = <?php echo $examen_id; ?>;
        let contenedor = document.getElementById('contenedor-pregunta');
        let progreso = document.getElementById('progreso');

        function cargarPregunta() {
            fetch(`../php/obtener_pregunta.php?examen_id=${examenId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.finalizado) {
                        contenedor.innerHTML = "<h3>¡Has completado el examen!</h3>";
                        return;
                    }

                    mostrarPregunta(data);
                })
                .catch(err => {
                    console.error("Error al cargar la pregunta:", err);
                    contenedor.innerHTML = "<p>Error al cargar pregunta</p>";
                });
        }

        function mostrarPregunta(p) {
            let opcionesHtml = '';

            p.opciones.forEach(op => {
                let tipo = p.tipo_pregunta === 'multiple' ? 'checkbox' : 'radio';
                opcionesHtml += `
            <div>
                <label>
                    <input type="${tipo}" name="opcion" value="${op.id}">
                    ${op.texto_opcion}
                </label>
            </div>
        `;
            });

            let imagenHtml = p.tipo_contenido === 'ilustracion' && p.ruta_imagen
                ? `<img src="../imagenes/${p.ruta_imagen}" style="max-width: 300px;">`
                : '';

            contenedor.innerHTML = `
        <div>
            <h4>Pregunta ${p.pregunta_actual} de ${p.total_preguntas}</h4>
            ${imagenHtml}
            <p>${p.texto_pregunta}</p>
            <form id="form-respuesta">
                ${opcionesHtml}
                <button type="submit">Siguiente</button>
            </form>
        </div>
    `;

            document.getElementById('form-respuesta').onsubmit = (e) => {
                e.preventDefault();
                enviarRespuesta(p.pregunta_id);
            };
        }

        function enviarRespuesta(pregunta_id) {
            const form = document.getElementById('form-respuesta');
            const datos = new FormData(form);

            // Soporte para múltiples opciones seleccionadas
            const seleccion = Array.from(form.elements['opcion'])
                .filter(el => el.checked)
                .map(el => el.value);

            if (seleccion.length === 0) {
                alert("Debes seleccionar una opción");
                return;
            }

            fetch('../php/guardar_respuestas.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    pregunta_id: pregunta_id,
                    opciones: seleccion
                })
            })
                .then(res => res.json())
                .then(res => {
                    if (res.ok) {
                        cargarPregunta(); // Siguiente
                    } else {
                        alert("Error al guardar respuesta");
                    }
                });
        }

        // Inicializa
        cargarPregunta();
    </script>
 -->

</body>

</html>