<?php
$examen_id = $_GET['id'] ?? 0;

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>



    <div class="h2">Evaluacion</div>
    <div id="contenedor-pregunta">
        <div id="numero-pregunta"></div>
        <div id="pregunta-texto"></div>
        <img id="imagen-pregunta" style="max-width: 100%; display: none;">
        <div id="opciones-container"></div>
    </div>

    <!-- 
<div id="contenedor-pregunta"></div>
 -->
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

                    // Mostrar número de pregunta
                    document.getElementById('numero-pregunta').innerText =
                        `Pregunta ${data.pregunta_actual} de ${data.total_preguntas}`;

                    // Mostrar texto o imagen de la pregunta
                    const textoPregunta = document.getElementById('pregunta-texto');
                    const imagenPregunta = document.getElementById('imagen-pregunta');

                    textoPregunta.innerText = '';
                    imagenPregunta.style.display = 'none';

                    if (data.tipo_contenido === 'texto') {
                        textoPregunta.innerText = data.texto_pregunta;
                    } else if (data.tipo_contenido === 'ilustracion' && data.ruta_imagen) {
                        imagenPregunta.src = data.ruta_imagen;
                        imagenPregunta.style.display = 'block';
                    }

                    // Renderizar opciones
                    const opcionesContainer = document.getElementById('opciones-container');
                    opcionesContainer.innerHTML = ''; // limpiar

                    data.opciones.forEach(opcion => {
                        const label = document.createElement('label');
                        label.style.display = 'block';

                        const input = document.createElement('input');
                        input.type = data.tipo_pregunta === 'multiple' ? 'checkbox' : 'radio';
                        input.name = 'opcion';
                        input.value = opcion.id;

                        label.appendChild(input);
                        label.append(` ${opcion.texto_opcion}`);
                        opcionesContainer.appendChild(label);
                    });
                })
                .catch(error => {
                    console.error('Error al cargar la pregunta:', error);
                    alert('Error al obtener la pregunta.');
                });
        }

        // Ejemplo de uso
        cargarPregunta(1); // pasa el ID real del examen_estudiante


        function mostrarPregunta(p) {
            const contenedor = document.getElementById('contenedor-pregunta');
            let opcionesHtml = '';
            const tipo = p.tipo_pregunta;

            if (tipo === 'vf') {
                opcionesHtml = `
            <label><input type="radio" name="opcion" value="v"> Verdadero</label><br>
            <label><input type="radio" name="opcion" value="f"> Falso</label>
        `;
            } else {
                const inputType = tipo === 'multiple' ? 'checkbox' : 'radio';
                p.opciones.forEach(op => {
                    opcionesHtml += `
                <label>
                    <input type="${inputType}" name="opcion" value="${op.id}">
                    ${op.texto_opcion}
                </label><br>
            `;
                });
            }

            const imagenHtml = p.tipo_contenido === 'ilustracion' && p.ruta_imagen
                ? `<img src="../imagenes/${p.ruta_imagen}" style="max-width:300px;"><br>`
                : '';

            contenedor.innerHTML = `
        <h4>Pregunta ${p.pregunta_actual} de ${p.total_preguntas}</h4>
        ${imagenHtml}
        <p>${p.texto_pregunta}</p>
        <form id="form-respuesta">
            ${opcionesHtml}
            <br>
            <button type="submit">Responder</button>
        </form>
    `;

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
                enviarRespuesta(p.id, seleccion, tipo, p.examen_id);
            };
        }



        async function enviarRespuesta(preguntaId, opcionesSeleccionadas, tipoPregunta, examenId) {
            try {
                const response = await fetch('guardar_respuestas.php', {
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