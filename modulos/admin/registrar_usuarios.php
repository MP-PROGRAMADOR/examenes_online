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
                            Registrar Usuario
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="../php/guardar_usuarios.php" method="POST" class="needs-validation" novalidate>
                            
                            <!-- Nombre de Usuario -->
                            <div class="mb-3">
                                <label for="nombre_usuario" class="form-label fw-semibold">
                                    <i class="bi bi-person-circle me-2 text-primary"></i>Nombre de Usuario <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control shadow-sm" id="nombre_usuario" name="nombre_usuario" placeholder="Ej. jlopez92" required>
                                <div class="invalid-feedback">
                                    Por favor ingresa un nombre de usuario.
                                </div>
                            </div>

                            <!-- Contraseña -->
                            <div class="mb-3 position-relative">
                                <label for="password" class="form-label fw-semibold">
                                    <i class="bi bi-lock-fill me-2 text-primary"></i>Contraseña <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control shadow-sm" id="password" name="password" required minlength="6">
                                    <button type="button" class="btn btn-outline-secondary" id="toggle-password">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback">
                                    La contraseña debe tener al menos 6 caracteres.
                                </div>
                            </div>

                            <!-- Correo Electrónico -->
                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">
                                    <i class="bi bi-envelope-fill me-2 text-primary"></i>Correo Electrónico <span class="text-danger">*</span>
                                </label>
                                <input type="email" class="form-control shadow-sm" id="email" name="email" placeholder="Ej. usuario@correo.com" required>
                                <div class="invalid-feedback">
                                    Ingresa un correo electrónico válido.
                                </div>
                            </div>

                            <!-- Rol -->
                            <div class="mb-4">
                                <label for="rol" class="form-label fw-semibold">
                                    <i class="bi bi-person-gear me-2 text-primary"></i>Rol <span class="text-danger">*</span>
                                </label>
                                <select class="form-select shadow-sm" id="rol" name="rol" required>
                                    <option value="">Seleccionar Rol</option>
                                    <option value="admin">Administrador</option>
                                    <option value="docente">Docente</option>
                                </select>
                                <div class="invalid-feedback">
                                    Selecciona un rol para el usuario.
                                </div>
                            </div>

                            <!-- Botones -->
                            <div class="d-flex justify-content-between flex-column flex-sm-row gap-2">
                                <a href="usuarios.php" class="btn btn-outline-secondary w-100">
                                    <i class="bi bi-arrow-left-circle me-2"></i>Volver
                                </a>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-person-plus-fill me-2"></i>Registrar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script para mostrar/ocultar la contraseña -->
<script>
    document.getElementById('toggle-password').addEventListener('click', function () {
        const passwordField = document.getElementById('password');
        const eyeIcon = this.querySelector('i');
        
        // Cambiar tipo de campo y icono
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            eyeIcon.classList.remove('bi-eye-fill');
            eyeIcon.classList.add('bi-eye-slash-fill');
        } else {
            passwordField.type = 'password';
            eyeIcon.classList.remove('bi-eye-slash-fill');
            eyeIcon.classList.add('bi-eye-fill');
        }
    });
</script>

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
