<?php
include '../componentes/head_admin.php';
include '../componentes/menu_admin.php';
?>

<div class="main-content">
    <div class="container-fluid mt-5 pt-2">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-6">
                <div class="card shadow rounded-4 p-4">
                    <div class="card-header bg-primary text-white rounded-3 mb-4">
                        <h4 class="mb-0 d-flex align-items-center">
                            <i class="bi bi-journal-bookmark me-2 fs-4"></i>
                            Registrar Examen
                        </h4>
                    </div>
                    <div class="card-body">
                        <!-- Formulario con validación Bootstrap -->
                        <form action="../php/guardar_examen.php" method="POST" class="needs-validation" novalidate>
                            <!-- Campo Categoría de Carné -->
                            <div class="mb-3">
                                <label for="categoria_carne_id" class="form-label fw-semibold">
                                    <i class="bi bi-archive me-2 text-primary"></i>Categoría de Carné <span class="text-danger">*</span>
                                </label>
                                <select class="form-select shadow-sm" id="categoria_carne_id" name="categoria_carne_id" required>
                                    <option value="">Seleccione una categoría</option>
                                    <?php
                                    // Incluir archivo de conexión
                                    require_once '../config/conexion.php';
                                    $conn = $pdo->getConexion();

                                    try {
                                        $sql = "SELECT id, nombre FROM categorias_carne ORDER BY nombre ASC";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->execute();
                                        $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                        foreach ($categorias as $categoria) {
                                            echo '<option value="' . htmlspecialchars($categoria['id']) . '">' . htmlspecialchars($categoria['nombre']) . '</option>';
                                        }
                                    } catch (PDOException $e) {
                                        echo '<option value="" disabled>Error al cargar las categorías</option>';
                                        error_log("Error al obtener categorías: " . $e->getMessage());
                                    } finally {
                                        $stmt = null;
                                        $pdo->closeConexion();
                                    }
                                    ?>
                                </select>
                                <div class="invalid-feedback">Por favor, seleccione una categoría de carné.</div>
                            </div>

                            <!-- Campo Título del Examen -->
                            <div class="mb-3">
                                <label for="titulo" class="form-label fw-semibold">
                                    <i class="bi bi-file-earmark-text me-2 text-primary"></i>Título del Examen <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control shadow-sm" id="titulo" name="titulo" placeholder="Ej: Examen Teórico General" required>
                                <div class="invalid-feedback">Por favor, ingrese el título del examen.</div>
                            </div>

                            <!-- Campo Descripción (Opcional) -->
                            <div class="mb-3">
                                <label for="descripcion" class="form-label fw-semibold">
                                    <i class="bi bi-file-earmark-person me-2 text-primary"></i>Descripción (Opcional)
                                </label>
                                <textarea class="form-control shadow-sm" id="descripcion" name="descripcion" rows="3" placeholder="Descripción detallada del examen."></textarea>
                            </div>

                            <!-- Campo Duración -->
                            <!-- <div class="mb-3">
                                <label for="duracion_minutos" class="form-label fw-semibold">
                                    <i class="bi bi-clock-fill me-2 text-primary"></i>Duración (en minutos) <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control shadow-sm" id="duracion_minutos" name="duracion_minutos" placeholder="Ej: 30" min="1" required>
                                <div class="invalid-feedback">Por favor, ingrese la duración del examen en minutos.</div>
                            </div> -->

                            <!-- Botones -->
                            <div class="d-flex justify-content-between flex-column flex-sm-row gap-2">
                                <a href="examenes.php" class="btn btn-outline-secondary w-100">
                                    <i class="bi bi-arrow-left-circle me-2"></i>Volver
                                </a>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-save2-fill me-2"></i>Registrar Examen
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script de validación Bootstrap -->
<script>
// Validación visual Bootstrap 5
(() => {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();
</script>

<?php include_once('../componentes/footer.php'); ?>
