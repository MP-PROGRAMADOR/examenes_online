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
                            <i class="bi bi-building me-2 fs-4"></i>
                            Registrar Escuela de Conducción
                        </h4>
                    </div>
                    <div class="card-body">
                        <!-- Formulario con validación Bootstrap -->
                        <form action="../php/guardar_escuelas.php" method="POST" class="needs-validation" novalidate>
                            <!-- Campo Nombre -->
                            <div class="mb-3">
                                <label for="nombre" class="form-label fw-semibold">
                                    <i class="bi bi-card-heading me-2 text-primary"></i>Nombre de la Escuela <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control shadow-sm" id="nombre" name="nombre" placeholder="Ej. Escuela San Cristóbal" required>
                                <div class="invalid-feedback">
                                    Por favor ingresa el nombre de la escuela.
                                </div>
                            </div>

                            <!-- Campo Dirección -->
                            <div class="mb-3">
                                <label for="direccion" class="form-label fw-semibold">
                                    <i class="bi bi-geo-alt-fill me-2 text-primary"></i>Dirección
                                </label>
                                <input type="text" class="form-control shadow-sm" id="direccion" name="direccion" placeholder="Ej. Av. Libertador 1234">
                            </div>

                            <!-- Campo Teléfono -->
                            <div class="mb-4">
                                <label for="telefono" class="form-label fw-semibold">
                                    <i class="bi bi-telephone-fill me-2 text-primary"></i>Teléfono
                                </label>
                                <input type="tel" class="form-control shadow-sm" id="telefono" name="telefono" placeholder="Ej. 1234-567890" pattern="[0-9\-]+" maxlength="20">
                                <div class="invalid-feedback">
                                    Solo números y guiones son permitidos.
                                </div>
                            </div>

                            <!-- Botones -->
                            <div class="d-flex justify-content-between flex-column flex-sm-row gap-2">
                                <a href="escuelas.php" class="btn btn-outline-secondary w-100">
                                    <i class="bi bi-arrow-left-circle me-2"></i>Volver
                                </a>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-save2-fill me-2"></i>Registrar Escuela
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
