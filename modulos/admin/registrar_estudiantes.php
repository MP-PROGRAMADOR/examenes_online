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
                            <i class="bi bi-person-plus-fill me-2 fs-4"></i>
                            Registrar Estudiante
                        </h4>
                    </div>
                    <div class="card-body">
                        <!-- Formulario con validación Bootstrap -->
                        <form action="../php/guardar_estudante.php" method="POST" class="needs-validation" novalidate>
                            <div class="row">
                                <!-- Columna izquierda -->
                                <div class="col-md-6">
                                    <!-- Escuela de Conducción -->
                                    <div class="mb-3">
                                        <label for="escuela_id" class="form-label fw-semibold">
                                            <i class="bi bi-building me-2 text-primary"></i>Escuela de Conducción <span class="text-danger">*</span>
                                        </label>
                                        <select name="escuela_id" class="form-select" required>
                                            <option value="">Seleccione una escuela</option>
                                            <?php 
                                            include '../../config/conexion.php';
                                            $conn = $pdo->getConexion();
                                            $stmt = null;
                                            $result = [];

                                            try {
                                                $sql = "SELECT id, nombre FROM escuelas_conduccion ORDER BY nombre ASC";
                                                $stmt = $conn->prepare($sql);
                                                $stmt->execute();
                                                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                                if (!empty($result)) {
                                                    foreach ($result as $row) {
                                                        echo '<option value="' . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($row['nombre'], ENT_QUOTES, 'UTF-8') . '</option>';
                                                    }
                                                } else {
                                                    echo '<option value="" disabled>No se encontraron escuelas</option>';
                                                }

                                            } catch (PDOException $e) {
                                                error_log("Error al obtener escuelas de conducción: " . $e->getMessage() . " en " . __FILE__ . ":" . __LINE__);
                                                echo '<option value="" disabled>Error al cargar las escuelas</option>';
                                            } finally {
                                                $stmt = null;
                                                $pdo->closeConexion();
                                            }
                                            ?>
                                        </select>
                                        <div class="invalid-feedback">
                                            Por favor, selecciona una escuela de conducción.
                                        </div>
                                    </div>

                                    <!-- Número de Identificación -->
                                    <div class="mb-3">
                                        <label for="numero_identificacion" class="form-label fw-semibold">
                                            <i class="bi bi-card-heading me-2 text-primary"></i>Número de Identificación <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="numero_identificacion" class="form-control" required>
                                        <div class="invalid-feedback">
                                            Por favor ingresa el número de identificación.
                                        </div>
                                    </div>

                                    <!-- Nombre -->
                                    <div class="mb-3">
                                        <label for="nombre" class="form-label fw-semibold">
                                            <i class="bi bi-person me-2 text-primary"></i>Nombre <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="nombre" class="form-control" required>
                                        <div class="invalid-feedback">
                                            Por favor ingresa el nombre del estudiante.
                                        </div>
                                    </div>

                                    <!-- Apellido -->
                                    <div class="mb-3">
                                        <label for="apellido" class="form-label fw-semibold">
                                            <i class="bi bi-person-fill me-2 text-primary"></i>Apellido <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="apellido" class="form-control" required>
                                        <div class="invalid-feedback">
                                            Por favor ingresa el apellido del estudiante.
                                        </div>
                                    </div>

                                    <!-- Fecha de Nacimiento -->
                                    <div class="mb-3">
                                        <label for="fecha_nacimiento" class="form-label fw-semibold">
                                            <i class="bi bi-calendar-date me-2 text-primary"></i>Fecha de Nacimiento <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" name="fecha_nacimiento" class="form-control" required>
                                        <div class="invalid-feedback">
                                            Por favor ingresa la fecha de nacimiento.
                                        </div>
                                    </div>
                                </div>

                                <!-- Columna derecha -->
                                <div class="col-md-6">
                                    <!-- Teléfono -->
                                    <div class="mb-3">
                                        <label for="telefono" class="form-label fw-semibold">
                                            <i class="bi bi-telephone-fill me-2 text-primary"></i>Teléfono
                                        </label>
                                        <input type="number" name="telefono" class="form-control" pattern="\d{9,15}" title="Ingrese un número de teléfono válido (9-15 dígitos)">
                                        <div class="invalid-feedback">
                                            Ingrese un número de teléfono válido.
                                        </div>
                                    </div>

                                    <!-- Dirección -->
                                    <div class="mb-3">
                                        <label for="direccion" class="form-label fw-semibold">
                                            <i class="bi bi-geo-alt-fill me-2 text-primary"></i>Dirección
                                        </label>
                                        <input type="text" name="direccion" class="form-control">
                                    </div>

                                    <!-- Categoría de Carné -->
                                    <div class="mb-3">
                                        <label for="categoria_carne" class="form-label fw-semibold">
                                            <i class="bi bi-card-list me-2 text-primary"></i>Categoría de Carné <span class="text-danger">*</span>
                                        </label>
                                        <select name="categoria_carne" class="form-select" required>
                                            <option value="">Seleccione una categoría</option>
                                            <option value="A">A</option>
                                            <option value="B">B</option>
                                            <option value="C">C</option>
                                            <option value="D">D</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Por favor selecciona una categoría de carné.
                                        </div>
                                    </div>

                                    <!-- Código de Registro Examen -->
                                    <div class="mb-3">
                                        <label for="codigo_registro_examen" class="form-label fw-semibold">
                                            <i class="bi bi-pencil-fill me-2 text-primary"></i>Código de Registro para el Examen
                                        </label>
                                        <input type="text" name="codigo_registro_examen" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <!-- Botón de Enviar -->
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-person-plus-fill me-2"></i> Registrar Estudiante
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
