<?php
include '../componentes/head_admin.php';
include '../componentes/menu_admin.php';
?>




<div class="main-content">

<div class="row justify-content-center align-items-center ">
    <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card p-5 mt-4 shadow-sm">
            <div class="container">
                <h2 class="mb-4 text-center">Crear Nueva Categoría de Carné</h2>
                <form action="../php/guardar_categorias.php" method="POST" class="needs-validation" novalidate>
                    <!-- Campo Nombre -->
                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-bold">Nombre de la Categoría:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ej: B, C1, A" required>
                        <div class="invalid-feedback">Por favor, ingrese el nombre de la categoría.</div>
                    </div>

                    <!-- Campo Descripción -->
                    <div class="mb-3">
                        <label for="descripcion" class="form-label fw-bold">Descripción (Opcional):</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"
                            placeholder="Descripción detallada de la categoría."></textarea>
                    </div>

                    <!-- Botones -->
                    <div class="d-grid gap-5 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Guardar Categoría
                        </button>
                        <!-- Puedes descomentar esto si quieres un botón de cancelar -->
                        
                        <a href="categorias.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Cancelar
                        </a>
                       
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</div>
 




<?php include_once('../componentes/footer.php'); ?>