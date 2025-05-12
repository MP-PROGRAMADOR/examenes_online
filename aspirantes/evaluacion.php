<?php
$examen_id = $_GET['id'] ?? 0;

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examen Online</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #e9ecef;
            font-family: 'Segoe UI', sans-serif;
        }

        .card-pregunta {
            max-width: 850px;
            margin: 3rem auto;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.08);
            background-color: #ffffff;
        }

        .pregunta-numero {
            font-size: 1rem;
            color: #6c757d;
        }

        .pregunta-texto {
            font-size: 1.25rem;
            font-weight: 500;
            margin-top: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .imagen-pregunta {
            max-width: 100%;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .opcion {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            margin-bottom: 1rem;
        }

        .opcion:hover {
            background: #e2e6ea;
            cursor: pointer;
        }

        .btn-siguiente {
            float: right;
            margin-top: 1.5rem;
        }

        .barra-progreso {
            height: 10px;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card card-pregunta">
            <!-- Progreso -->

            <!-- Pregunta -->

            <div id="contenedor-pregunta">

                <div id="numero-pregunta" class="pregunta-numero">Pregunta 1</div>






                <!--   

                <div id="pregunta-texto" class="pregunta-texto">¿Cuál es la capital de Francia?</div>
                <img id="imagen-pregunta" id="imagen-pregunta" class="imagen-pregunta d-none" src=""
                alt="Imagen de la pregunta">
                
                <div id="opciones-container" class="p-4 border "> </div>
      
                <hr class="my-4">
                
                <button class="btn btn-primary btn-siguiente">
                    Siguiente <i class="bi bi-arrow-right"></i>
                </button> -->

            </div>
        </div>
    </div>
    </div>







    <!-- Bootstrap JS (opcional si usas componentes interactivos) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>


        let examenEstudianteId = <?php echo $examen_id; ?>
        // Función para obtener la pregunta desde PHP
        function cargarPregunta(examenEstudianteId) {
            fetch(`../php/get_preguntas.php?examen_estudiante_id=${examenEstudianteId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }
                     
                    /* ----------------------------------- */
                    const contenedor = document.getElementById('contenedor-pregunta');
                    let opcionesHtml = '';
                    const tipo = data.tipo_pregunta;

                    if (tipo === 'vf') {
                        opcionesHtml = `
                            <label class='p-3'><input type="radio" name="opcion" value="v"> Verdadero</label><br>
                            <label class='p-3'><input type="radio" name="opcion" value="f"> Falso</label>    `;

                    } else {
                        const inputType = tipo === 'multiple' ? 'checkbox' : 'radio';
                        data.opciones.forEach(op => {
                            opcionesHtml += `
                        <label>
                        <input type="${inputType}" class='p-3 mx-3' name="opcion" value="${op.id}">
                        ${op.texto_opcion}
                           
                        </label><br>
                     `;
                        });

                    }
                    // renderizar imagenes
                    const imagenHtml = data.tipo_contenido === 'ilustracion' && data.ruta_imagen
                        ? `<img src="../imagenes/${p.ruta_imagen}" style="max-width:300px;"><br>`
                        : '';

                    contenedor.innerHTML = `
                             
                         <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Progreso</span>
                                        <span id="progreso-texto">${data.pregunta_actual} de ${data.total_preguntas}</span>
                                    </div>
                                <div class="progress barra-progreso">
                                    <div id="barra-progreso" class="progress-bar" role="progressbar" style="width: 10%;"
                                                    aria-valuenow="${data.pregunta_actual}" aria-valuemin="0" aria-valuemax="${data.total_preguntas}"></div>
                                            </div>
                                        </div> 
                                                        ${imagenHtml}
                                                <div id="pregunta-texto" class="pregunta-texto">${data.texto_pregunta}</div>
                                                
                                                <form id="form-respuesta">
                                                    ${opcionesHtml}
                                                    <br>
                                                    <hr>
                                                   <button class="btn btn-primary btn-siguiente">
                                                        Responder <i class="bi bi-arrow-right"></i>
                                                    </button>
                                                </form>
                                            `;


                    // evento para capturar las respuestas
                    document.getElementById('form-respuesta').onsubmit = function (e) {
                        e.preventDefault();
                        const form = e.target;
                        let seleccion = [];

                        if (tipo === 'vf' || tipo === 'unica') {
                            const seleccionada = form.querySelector('input[name="opcion"]:checked');
                            if (!seleccionada) return alert("Debes seleccionar una opción.");
                            seleccion = [seleccionada.value];
                        } else {
                            const seleccionados = Array.from(form.querySelectorAll('input[name="opcion"]:checked'));
                            if (seleccionados.length === 0) return alert("Debes seleccionar al menos una opción.");
                            seleccion = seleccionados.map(el => el.value);
                        }

                        // Llamar a la función para enviar al backend
                        enviarRespuesta(data.id, seleccion, tipo, data.examen_id);
                    };
                   
                })
                .catch(error => {
                    console.error('Error al cargar la pregunta:', error);
                    alert('Error al obtener la pregunta.');
                });
        }

        // Ejemplo de uso
        cargarPregunta(examenEstudianteId); // pasa el ID real del examen_estudiante
 
        /* ---------------------------echo--------- */

        async function enviarRespuesta(preguntaId, opcionesSeleccionadas, tipoPregunta, examenId) {
            try {
                const response = await fetch('../php/guardar_respuestas.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        pregunta_id: preguntaId,
                        opciones: opcionesSeleccionadas,  // Puede ser ['v'], ['f'], [id1], [id2]
                        tipo: tipoPregunta,               // 'unica', 'multiple', 'vf'
                        examen_id: examenId
                    })
                });

                const resultado = await response.json();

                if (resultado.ok) {
                    alert(resultado.mensaje);
                    console.log(resultado.data)
                    if (resultado.finalizado) {
                        alert('Examen finalizado. Calificación: ' + resultado.calificacion + '%');
                        // Redireccionar o mostrar resumen
                        // window.location.href = "resultado.php?examen_id=" + examenId;
                    }
                } else {
                    console.error(resultado.mensaje);
                    alert('Error al guardar respuesta.');
                }

            } catch (error) {
                console.error('Error al enviar respuesta:', error);
                alert('Error al comunicarse con el servidor.');
            }
        }

    </script>

</body>

</html>