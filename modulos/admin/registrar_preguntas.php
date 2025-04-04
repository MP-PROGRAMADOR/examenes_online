<!DOCTYPE html>
<html lang="es">

<!-- Encabezado que incluye hojas de estilo y configuraciones comunes -->
<?php include '../componentes/head_admin.php'; ?>

<body>
    <!-- Menú de navegación principal -->
    <?php include '../componentes/menu_admin.php'; ?>

    <div class="content">
        <div class="container-fluid mt-5 pt-2">
            <div class="row d-flex justify-content-center align-items-center">
                <div class="card p-5 mt-5 w-75">
                    <div class="form-container">
                        <div class="container">
                            <?php include '../../config/conexion.php'; $conn = $pdo->getConexion(); ?>

                            <h2>Registrar Nueva Pregunta</h2>

                            <!-- Mostrar mensaje de error si existe -->
                            <?php if (!empty($mensaje_error)): ?>
                                <div class="alert alert-danger"><?= $mensaje_error ?></div>
                            <?php endif; ?>

                            <!-- Formulario principal -->
                            <form action="../php/guardar_preguntas.php" method="POST" enctype="multipart/form-data" id="formPregunta">
                                <!-- Selección del examen -->
                                <div class="mb-3">
                                    <label for="examen_id" class="form-label">Examen:</label>
                                    <select name="examen_id" id="examen_id" class="form-select" required>
                                        <option value="">Seleccione un examen</option>
                                        <?php
                                        require_once '../../config/conexion.php';
                                        $conn = $pdo->getConexion();
                                        try {
                                            $sqlExamenes = "SELECT id, titulo FROM examenes ORDER BY titulo ASC";
                                            $stmtExamenes = $conn->prepare($sqlExamenes);
                                            $stmtExamenes->execute();
                                            $examenes = $stmtExamenes->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($examenes as $examen) {
                                                echo '<option value="' . htmlspecialchars($examen['id']) . '">' . htmlspecialchars($examen['titulo']) . '</option>';
                                            }
                                        } catch (PDOException $e) {
                                            echo '<option value="" disabled>Error al cargar los exámenes</option>';
                                            error_log("Error al obtener exámenes: " . $e->getMessage());
                                        }
                                        ?>
                                    </select>
                                </div>

                                <!-- Tipo de pregunta (texto o con ilustración) -->
                                <div class="mb-3">
                                    <label for="tipo_contenido" class="form-label">Tipo de contenido:</label>
                                    <select name="tipo_contenido" id="tipo_contenido" class="form-select" required>
                                        <option value="">Seleccione tipo de contenido</option>
                                        <option value="solo_texto">Solo texto</option>
                                        <option value="con_ilustracion">Pregunta con ilustración</option>
                                    </select>
                                </div>

                                <!-- Tipo de pregunta (funcional) -->
                                <div class="mb-3">
                                    <label for="tipo_pregunta" class="form-label">Tipo de Pregunta:</label>
                                    <select name="tipo_pregunta" id="tipo_pregunta" class="form-select" required>
                                        <option value="">Seleccione un tipo</option>
                                        <option value="multiple_choice">Opción múltiple</option>
                                        <option value="respuesta_unica">Respuesta única</option>
                                        <option value="verdadero_falso">Verdadero / Falso</option>
                                    </select>
                                </div>

                                <!-- Campo para imágenes (solo visible si se selecciona ilustración) -->
                                <div class="mb-3" id="imagenes_container" style="display:none">
                                    <label class="form-label">Imágenes de la pregunta:</label>
                                    <div id="imagenes_dinamicas"></div>
                                    <button type="button" class="btn btn-secondary btn-sm mt-2" id="agregarImagen">+ Agregar imagen</button>
                                </div>

                                <!-- Campo de texto de la pregunta -->
                                <div class="mb-3" id="texto_pregunta_container" style="display: none;">
                                    <label for="texto_pregunta" class="form-label">Texto de la pregunta:</label>
                                    <textarea name="texto_pregunta" id="texto_pregunta" class="form-control" required></textarea>
                                </div>

                                <!-- Opciones para opción múltiple o respuesta única -->
                                <div id="opciones_container" class="mb-3" style="display: none;">
                                    <label class="form-label">Opciones:</label>
                                    <div id="opciones_dinamicas"></div>
                                    <button type="button" class="btn btn-secondary btn-sm mt-2" id="agregarOpcion">+ Agregar opción</button>
                                </div>

                                <!-- Opción de verdadero/falso -->
                                <div id="verdadero_falso_container" class="mb-3" style="display: none;">
                                    <label class="form-label">Seleccione la respuesta correcta:</label>
                                    <div>
                                        <input type="radio" name="es_correcta_vf" value="verdadero" id="vf_verdadero">
                                        <label for="vf_verdadero">Verdadero</label>
                                    </div>
                                    <div>
                                        <input type="radio" name="es_correcta_vf" value="falso" id="vf_falso">
                                        <label for="vf_falso">Falso</label>
                                    </div>
                                </div>

                                <!-- Botón de envío del formulario -->
                                <button type="submit" class="btn btn-primary">Guardar Pregunta</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts JS -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tipoContenido = document.getElementById('tipo_contenido');
            const tipoPregunta = document.getElementById('tipo_pregunta');
            const imagenesContainer = document.getElementById('imagenes_container');
            const textoPreguntaContainer = document.getElementById('texto_pregunta_container');
            const opcionesContainer = document.getElementById('opciones_container');
            const vfContainer = document.getElementById('verdadero_falso_container');
            const opcionesDinamicas = document.getElementById('opciones_dinamicas');
            const agregarOpcionBtn = document.getElementById('agregarOpcion');
            const imagenesDinamicas = document.getElementById('imagenes_dinamicas');
            const agregarImagenBtn = document.getElementById('agregarImagen');

            // Elimina dinámicamente imágenes
            function crearCampoImagen() {
                const div = document.createElement('div');
                div.className = 'input-group mb-2';

                const input = document.createElement('input');
                input.type = 'file';
                input.name = 'imagenes[]';
                input.accept = 'image/*';
                input.className = 'form-control';

                const eliminarBtn = document.createElement('button');
                eliminarBtn.type = 'button';
                eliminarBtn.className = 'btn btn-danger btn-sm ms-2';
                eliminarBtn.textContent = 'X';
                eliminarBtn.onclick = () => div.remove();

                div.appendChild(input);
                div.appendChild(eliminarBtn);

                return div;
            }

            // Crea campos para opciones con botón de eliminar
            function crearOpcion(index) {
                const div = document.createElement('div');
                div.classList.add('input-group', 'mb-2');

                const input = document.createElement('input');
                input.type = 'text';
                input.name = 'opcion[]';
                input.placeholder = `Opción ${index + 1}`;
                input.className = 'form-control';
                input.required = true;

                const radio = document.createElement('input');
                radio.type = 'radio';
                radio.name = 'es_correcta';
                radio.value = index + 1;
                radio.className = 'form-check-input ms-2 mt-2';

                const eliminarBtn = document.createElement('button');
                eliminarBtn.type = 'button';
                eliminarBtn.className = 'btn btn-outline-danger btn-sm ms-2';
                eliminarBtn.textContent = 'Eliminar';
                eliminarBtn.onclick = () => {
                    div.remove();
                    actualizarNumeracionOpciones();
                };

                div.appendChild(input);
                div.appendChild(radio);
                div.appendChild(eliminarBtn);

                return div;
            }

            function actualizarNumeracionOpciones() {
                const opciones = opcionesDinamicas.children;
                [...opciones].forEach((opcion, i) => {
                    opcion.querySelector('input[type="text"]').placeholder = `Opción ${i + 1}`;
                    opcion.querySelector('input[type="radio"]').value = i + 1;
                });
            }

            function mostrarCamposPorSeleccion() {
                const tipo = tipoPregunta.value;
                const contenido = tipoContenido.value;

                textoPreguntaContainer.style.display = (tipo && contenido) ? 'block' : 'none';

                if (contenido === 'con_ilustracion') {
                    imagenesContainer.style.display = 'block';
                } else {
                    imagenesContainer.style.display = 'none';
                    imagenesDinamicas.innerHTML = '';
                }

                opcionesContainer.style.display = 'none';
                vfContainer.style.display = 'none';
                opcionesDinamicas.innerHTML = '';

                if (tipo === 'multiple_choice' || tipo === 'respuesta_unica') {
                    opcionesContainer.style.display = 'block';
                    for (let i = 0; i < 2; i++) {
                        opcionesDinamicas.appendChild(crearOpcion(i));
                    }
                } else if (tipo === 'verdadero_falso') {
                    vfContainer.style.display = 'block';
                }
            }

            tipoPregunta.addEventListener('change', mostrarCamposPorSeleccion);
            tipoContenido.addEventListener('change', mostrarCamposPorSeleccion);

            agregarImagenBtn.addEventListener('click', () => {
                imagenesDinamicas.appendChild(crearCampoImagen());
            });

            agregarOpcionBtn.addEventListener('click', () => {
                const currentCount = opcionesDinamicas.children.length;
                opcionesDinamicas.appendChild(crearOpcion(currentCount));
            });
        });
    </script>
</body>
</html>
