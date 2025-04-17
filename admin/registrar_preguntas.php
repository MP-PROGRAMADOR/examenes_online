<?php
session_start();

// Recuperamos el mensaje de alerta de la sesión si existe
$alerta = isset($_SESSION['alerta']) ? $_SESSION['alerta'] : null;
unset($_SESSION['alerta']); // Limpiar la sesión después de usar el mensaje
include '../componentes/head_admin.php';
include '../componentes/menu_admin.php';
include '../config/conexion.php';
$conn = $pdo->getConexion();
try {
    $stmt = $conn->prepare("SELECT id, titulo FROM examenes");
    $stmt->execute();
    $examenes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $mensaje_error = "hubo un error en la consulta " . $e;
}
?>



<div class="main-content">
    <div class="container-fluid mt-5 pt-2">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="card shadow rounded-4 p-4">
                    <div class="card-header bg-primary text-white rounded-3 mb-4">
                        <h4 class="mb-0 d-flex align-items-center">
                            <i class="bi bi-journal-text me-2 fs-4"></i>
                            Registrar Pregunta de Examen
                        </h4>
                    </div>

                    <!-- Modal de Alerta -->
                    <?php if ($alerta): ?>
                        <div class="modal fade show" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel"
                            aria-hidden="false" style="display: block;">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div
                                        class="modal-header <?php echo $alerta['tipo'] == 'success' ? 'bg-success' : 'bg-danger'; ?>">
                                        <h5 class="modal-title text-white" id="alertModalLabel">
                                            <?php echo $alerta['tipo'] == 'success' ? '¡Éxito!' : 'Error'; ?>
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="text-center"><?php echo $alerta['mensaje']; ?></p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cerrar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="card-body">
                        <form action="../php/guardar_preguntas.php" method="POST" enctype="multipart/form-data"
                            class="needs-validation" novalidate>
                            <!-- Select Examen -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Selecciona un examen <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-file-earmark-text"></i></span>
                                    <select id="examenSelect" name="examen_id" class="form-select" required>
                                        <option value="">Seleccione...</option>
                                        <?php foreach ($examenes as $exam): ?>
                                            <option value="<?= htmlspecialchars($exam['id']); ?> ">
                                                <?= htmlspecialchars($exam['titulo']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="invalid-feedback">Selecciona un examen.</div>
                            </div>

                            <!-- Tipo de Contenido -->
                            <div class="mb-3 d-none" id="tipoContenidoWrapper">
                                <label class="form-label fw-semibold">Tipo de Pregunta</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-question-circle"></i></span>
                                    <select id="tipoContenido" name="tipo_contenido" class="form-select">
                                        <option value="">Seleccione...</option>
                                        <option value="texto">Solo texto</option>
                                        <option value="ilustracion">Con ilustración</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Texto de la Pregunta -->
                            <div class="mb-3 d-none" id="textoPreguntaWrapper">
                                <label class="form-label fw-semibold">Texto de la Pregunta</label>
                                <textarea name="texto_pregunta" class="form-control" rows="3"
                                    placeholder="Escribe la pregunta..."></textarea>
                            </div>

                            <!-- Input Imagen (dinámico) -->
                            <div class="mb-3 d-none" id="imagenesWrapper">
                                <label class="form-label fw-semibold">Imágenes de apoyo</label>
                                <div id="imagenesContainer">
                                    <div class="input-group mb-2">
                                        <input type="file" name="imagenes[]" class="form-control" accept="image/*">
                                        <button type="button" class="btn btn-danger btn-remover-img">
                                            <i class="bi bi-x-circle-fill"></i>
                                        </button>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-success btn-sm" id="agregarImagenBtn">
                                    <i class="bi bi-plus-circle"></i> Agregar imagen
                                </button>
                            </div>

                            <!-- Tipo de Respuesta -->
                            <div class="mb-3 d-none" id="tipoRespuestaWrapper">
                                <label class="form-label fw-semibold">Tipo de respuesta</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-check-all"></i></span>
                                    <select id="tipoRespuesta" name="tipo_pregunta" class="form-select">
                                        <option value="">Seleccione...</option>
                                        <option value="multiple">Opción múltiple</option>
                                        <option value="unica">Única respuesta</option>
                                        <option value="vf">Verdadero o Falso</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Opciones de Respuesta (dinámico) -->
                            <div class="mb-3 d-none" id="opcionesWrapper">
                                <label class="form-label fw-semibold">Opciones de respuesta</label>
                                <div id="opcionesContainer"></div>
                                <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="agregarOpcionBtn">
                                    <i class="bi bi-plus-circle"></i> Agregar opción
                                </button>
                            </div>

                            <!-- Botones -->
                            <div class="d-flex justify-content-between flex-column flex-sm-row gap-2 mt-4">
                                <a href="preguntas.php" class="btn btn-outline-secondary w-100">
                                    <i class="bi bi-arrow-left-circle me-2"></i>Volver
                                </a>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-save2-fill me-2"></i>Guardar Pregunta
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>


    document.addEventListener('DOMContentLoaded', () => {
        const examenSelect = document.getElementById('examenSelect');
        const tipoContenido = document.getElementById('tipoContenido');
        const tipoRespuesta = document.getElementById('tipoRespuesta');

        const tipoContenidoWrapper = document.getElementById('tipoContenidoWrapper');
        const textoPreguntaWrapper = document.getElementById('textoPreguntaWrapper');
        const imagenesWrapper = document.getElementById('imagenesWrapper');
        const tipoRespuestaWrapper = document.getElementById('tipoRespuestaWrapper');
        const opcionesWrapper = document.getElementById('opcionesWrapper');

        const opcionesContainer = document.getElementById('opcionesContainer');
        const agregarOpcionBtn = document.getElementById('agregarOpcionBtn');

        const imagenesContainer = document.getElementById('imagenesContainer');
        const agregarImagenBtn = document.getElementById('agregarImagenBtn');

        // Estado inicial
        resetFormulario();

        examenSelect.addEventListener('change', () => {
            resetFormulario();
            if (examenSelect.value !== '') {
                tipoContenidoWrapper.classList.remove('d-none');
            }
        });

        tipoContenido.addEventListener('change', () => {
            textoPreguntaWrapper.classList.add('d-none');
            imagenesWrapper.classList.add('d-none');
            tipoRespuestaWrapper.classList.add('d-none');
            opcionesWrapper.classList.add('d-none');
            limpiarOpciones();
            if (tipoContenido.value !== '') {
                textoPreguntaWrapper.classList.remove('d-none');
                tipoRespuestaWrapper.classList.remove('d-none');
                if (tipoContenido.value === 'ilustracion') {
                    imagenesWrapper.classList.remove('d-none');
                }
            }
        });

        tipoRespuesta.addEventListener('change', () => {
            limpiarOpciones();
            opcionesWrapper.classList.add('d-none');
            if (tipoRespuesta.value === 'multiple' || tipoRespuesta.value === 'unica') {
                opcionesWrapper.classList.remove('d-none');
                agregarOpcion();
            } else if (tipoRespuesta.value === 'vf') {
                opcionesWrapper.classList.remove('d-none');
                opcionesContainer.innerHTML = `
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="respuesta_correcta" value="verdadero" required>
                    <label class="form-check-label">Verdadero</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="respuesta_correcta" value="falso" required>
                    <label class="form-check-label">Falso</label>
                </div>
            `;
                agregarOpcionBtn.classList.add('d-none');
            } else {
                agregarOpcionBtn.classList.add('d-none');
            }
        });

        agregarOpcionBtn.addEventListener('click', agregarOpcion);

        function agregarOpcion() {
            const div = document.createElement('div');
            div.classList.add('input-group', 'mb-2');
            div.innerHTML = `
            <input type="text" name="opciones[]" class="form-control" placeholder="Opción de respuesta" required>
            <div class="input-group-text">
                <input class="form-check-input mt-0" type="checkbox" name="correctas[]">
            </div>
            <button type="button" class="btn btn-danger btn-remover-opcion">
                <i class="bi bi-x-circle-fill"></i>
            </button>
        `;
            opcionesContainer.appendChild(div);
        }

        opcionesContainer.addEventListener('click', e => {
            if (e.target.closest('.btn-remover-opcion')) {
                e.target.closest('.input-group').remove();
            }
        });

        agregarImagenBtn.addEventListener('click', () => {
            const div = document.createElement('div');
            div.classList.add('input-group', 'mb-2');
            div.innerHTML = `
            <input type="file" name="imagenes[]" class="form-control" accept="image/*">
                       <button type="button" class="btn btn-danger btn-remover-img">
                <i class="bi bi-x-circle-fill"></i>
            </button>
        `;
            imagenesContainer.appendChild(div);
        });

        imagenesContainer.addEventListener('click', e => {
            if (e.target.closest('.btn-remover-img')) {
                e.target.closest('.input-group').remove();
            }
        });

        function resetFormulario() {
            tipoContenidoWrapper.classList.add('d-none');
            textoPreguntaWrapper.classList.add('d-none');
            imagenesWrapper.classList.add('d-none');
            tipoRespuestaWrapper.classList.add('d-none');
            opcionesWrapper.classList.add('d-none');
            limpiarOpciones();
            tipoContenido.value = '';
            tipoRespuesta.value = '';
        }

        function limpiarOpciones() {
            opcionesContainer.innerHTML = '';
            agregarOpcionBtn.classList.remove('d-none');
        }

        // Ocultar modal de alerta automáticamente después de 4 segundos
        const alertModal = document.getElementById('alertModal');
        if (alertModal) {
            setTimeout(() => {
                const modal = bootstrap.Modal.getOrCreateInstance(alertModal);
                modal.hide();
            }, 4000);
        }
    });
</script>


<?php include_once('../componentes/footer.php'); ?>