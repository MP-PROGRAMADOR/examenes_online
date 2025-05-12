<?php


// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está logueado
if (!isset($_SESSION['estudiante'])) {
    header('Location: index.php');
    exit;
}

// Conexión a la base de datos
require_once '../config/conexion.php';
$pdo = $pdo->getConexion();

$estudiante = $_SESSION['estudiante'];
$estudiante_id = $estudiante['id'];

// Consultar si el usuario tiene acceso habilitado e intentos disponibles
$sql = "SELECT acceso_habilitado, intentos_examen 
        FROM examenes_estudiantes 
        WHERE estudiante_id = ? 
        ORDER BY id DESC 
        LIMIT 1"; // Obtener el examen más reciente

$stmt = $pdo->prepare($sql);
$stmt->execute([$estudiante_id]);
$examen = $stmt->fetch(PDO::FETCH_ASSOC);

// Validar condiciones
if (!$examen) {
    header('Location: aspirante.php');
    exit;

}

if ((int) $examen['acceso_habilitado'] !== 1) {
    header('Location: aspirante.php');
    exit;
}

if ((int) $examen['intentos_examen'] <= 0) {
    header('Location: aspirante.php');
    exit;
}

// Si pasa todas las validaciones, continúa el flujo normal


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
                        if (res.finalizado) {
                            alert(`Examen finalizado. Calificación: ${res.calificacion}%`);
                            // Mostrar mensaje de finalización
                            const mensaje = document.createElement('div');
                            mensaje.innerText = "¡Examen finalizado! Redirigiendo...";
                            mensaje.classList.add('alert', 'alert-success', 'mt-3');
                            document.getElementById('contenedor-pregunta').innerHTML = '';
                            document.getElementById('contenedor-pregunta').appendChild(mensaje);

                            // Redirigir después de 2 segundos
                            setTimeout(() => {
                                window.location.href = "aspirante.php";
                            }, 2000);
                        } else {
                            // Cargar siguiente pregunta
                            cargarPregunta();
                            console.log(res.mensaje);
                        }
                    } else {
                        console.error(res);
                        alert("Error al guardar la respuesta.");
                    }


                }
                );
        }

        cargarPregunta(); // Inicializar
    </script>


</body>

</html>














