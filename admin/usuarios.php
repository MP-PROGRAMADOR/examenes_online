<?php
require '../includes/conexion.php';
// Asegúrate de que $rol esté definido si es necesario para la visibilidad del botón de eliminar
// Por ejemplo, si tienes un sistema de sesión:
// session_start();
// $rol = $_SESSION['rol'] ?? '';

include_once("../includes/header.php");
include_once("../includes/sidebar.php");
?>

<main class="main-content" id="content">
    <div class="card shadow border-0 rounded-4">
        <div class="card-header bg-primary text-white d-flex flex-wrap justify-content-between align-items-center rounded-top-4 px-4 py-3">
            <h5 class="mb-0"><i class="bi bi-people-fill me-2"></i>Gestión de Usuarios</h5>
            <div class="search-box position-relative">
                <input type="text" class="form-control ps-5" id="customSearch" placeholder="Buscar usuario...">
                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
            </div>
            <div class="d-flex flex-wrap gap-5 align-items-center">
                <div class="d-flex align-items-center">
                    <label for="container-length" class="me-2 text-white fw-medium mb-0">Mostrar:</label>
                    <select id="container-length" class="form-select w-auto shadow-sm">
                        <option value="5">5 registros</option>
                        <option value="10" selected>10 registros</option>
                        <option value="15">15 registros</option>
                        <option value="20">20 registros</option>
                        <option value="25">25 registros</option>
                    </select>
                </div>
                <button class="btn btn-primary" onclick="abrirModalRegistro()">
                    <i class="bi bi-person-plus-fill me-2"></i>Nuevo Usuario
                </button>
            </div>
        </div>
        <div class="table-responsive">
            <table id="usuarios-table" class="table table-hover align-middle shadow-sm rounded-3 overflow-hidden">
                <thead class="table-light text-center">
                    <tr>
                        <th><i class="bi bi-hash me-1"></i>ID</th>
                        <th><i class="bi bi-person-fill me-1"></i>Nombre</th>
                        <th><i class="bi bi-envelope-fill me-1"></i>Email</th>
                        <th><i class="bi bi-shield-lock-fill me-1"></i>Contraseña</th>
                        <th><i class="bi bi-person-badge-fill me-1"></i>Rol</th>
                        <th><i class="bi bi-calendar-check-fill me-1"></i>Fecha</th>
                        <th><i class="bi bi-toggle-on me-1"></i>Activo</th>
                        <th><i class="bi bi-gear-fill me-1"></i>Acciones</th>
                    </tr>
                </thead>
                <tbody id="usuarios-table-body">
                    </tbody>
            </table>
        </div>
        <div id="no-usuarios-message" class="alert alert-warning text-center m-3 d-none">
            <i class="bi bi-exclamation-circle-fill me-2"></i>⚠️ No hay usuarios registrados actualmente o que coincidan con la búsqueda.
        </div>
        <div class="card-footer d-flex justify-content-between align-items-center p-3">
            <div id="pagination-info"></div>
            <nav>
                <ul class="pagination mb-0" id="pagination-controls">
                    </ul>
            </nav>
        </div>
    </div>
</main>

<div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="modalUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header bg-primary text-white rounded-top">
                <h5 class="modal-title" id="modalUsuarioLabel">
                    <i class="bi bi-person-plus-fill me-2"></i><span id="modalTitulo">Registrar Usuario</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formularioEditarRegistrar" method="POST" class="needs-validation" novalidate>
                <div class="modal-body p-4">
                    <input type="hidden" name="usuario_id" id="usuario_id">
                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-semibold">
                            <i class="bi bi-person-circle me-2 text-primary"></i>Nombre Completo <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control shadow-sm" id="nombre" name="nombre" required>
                        <div class="invalid-feedback">Por favor ingresa el nombre completo.</div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">
                            <i class="bi bi-envelope-fill me-2 text-primary"></i>Correo Electrónico <span class="text-danger">*</span>
                        </label>
                        <input type="email" class="form-control shadow-sm" id="email" name="email" required>
                        <div class="invalid-feedback">Ingresa un correo electrónico válido.</div>
                    </div>
                    <div class="mb-3 position-relative">
                        <label for="contrasena" class="form-label fw-semibold">
                            <i class="bi bi-lock-fill me-2 text-primary"></i>Contraseña <small class="text-muted">(dejar vacío para no
                                cambiar)</small>
                        </label>
                        <div class="input-group">
                            <input type="password" class="form-control shadow-sm" id="contrasena" name="contrasena" minlength="6"
                                placeholder="Nueva contraseña">
                            <button type="button" class="btn btn-outline-secondary" id="toggle-password">
                                <i class="bi bi-eye-fill"></i>
                            </button>
                        </div>
                        <div class="invalid-feedback">La contraseña debe tener al menos 6 caracteres.</div>
                    </div>
                    <div class="mb-3">
                        <label for="rol" class="form-label fw-semibold">
                            <i class="bi bi-person-gear me-2 text-primary"></i>Rol <span class="text-danger">*</span>
                        </label>
                        <select class="form-select shadow-sm" id="rol" name="rol" required>
                            <option value="">Seleccionar Rol</option>
                            <option value="admin">Administrador</option>
                            <option value="examinador">Examinador</option>
                            <option value="secretaria">Secretaria</option>
                        </select>
                        <div class="invalid-feedback">Selecciona un rol para el usuario.</div>
                    </div>
                    <div class="form-check form-switch mb-3 d-none" id="activo-container">
                        <input class="form-check-input" type="checkbox" id="activo" name="activo" value="1">
                        <label class="form-check-label fw-semibold" for="activo">Usuario activo</label>
                    </div>
                </div>
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save2-fill me-2"></i><span id="modalBotonTexto">Registrar</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Variables globales para la paginación y búsqueda en el frontend
    let allUsers = []; // Aquí se almacenarán TODOS los usuarios cargados
    let filteredUsers = []; // Aquí se almacenarán los usuarios después de aplicar el filtro de búsqueda
    let currentPage = 1;
    let recordsPerPage = 10;
    let searchTerm = '';

    (() => {
        'use strict';

        // Validación Bootstrap
        const forms = document.querySelectorAll('.needs-validation');
        forms.forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            });
        });

        // Toggle mostrar/ocultar contraseña
        const togglePasswordBtn = document.getElementById('toggle-password');
        togglePasswordBtn.addEventListener('click', () => {
            const pwdInput = document.getElementById('contrasena');
            const icon = togglePasswordBtn.querySelector('i');
            if (pwdInput.type === 'password') {
                pwdInput.type = 'text';
                icon.classList.replace('bi-eye-fill', 'bi-eye-slash-fill');
            } else {
                pwdInput.type = 'password';
                icon.classList.replace('bi-eye-slash-fill', 'bi-eye-fill');
            }
        });

        // Inicializar carga de usuarios al cargar la página
        // La primera carga trae TODOS los usuarios
        fetchAndStoreAllUsers();

        // Event listener para el selector de registros por página
        document.getElementById('container-length').addEventListener('change', (event) => {
            recordsPerPage = parseInt(event.target.value);
            currentPage = 1; // Volver a la primera página al cambiar la cantidad de registros
            applyFiltersAndRenderTable(); // Reaplicar filtros y renderizar
        });

        // Event listener para el campo de búsqueda
        document.getElementById('customSearch').addEventListener('input', (event) => {
            searchTerm = event.target.value.trim().toLowerCase(); // Convertir a minúsculas para búsqueda insensible a mayúsculas/minúsculas
            currentPage = 1; 
            applyFiltersAndRenderTable(); // Reaplicar filtros y renderizar
        });

    })();

    // --- NUEVAS FUNCIONES PARA EL ENFOQUE DE FRONTEND ---

    // Función para obtener TODOS los usuarios una vez y almacenarlos
    async function fetchAndStoreAllUsers() {
        const tableBody = document.getElementById('usuarios-table-body');
        tableBody.innerHTML = `<tr><td colspan="8" class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div></td></tr>`;

        try {
            const url = `../api/ontener_usuarios_fetch_lista_admin.php`; // Ya no pasamos parámetros de paginación o búsqueda aquí
            const response = await fetch(url);
            const data = await response.json();

            if (data.status) {
                allUsers = data.data; // Almacenar todos los usuarios
                applyFiltersAndRenderTable(); // Aplicar filtro inicial y renderizar
            } else {
                mostrarToast('danger', data.message);
                tableBody.innerHTML = `<tr><td colspan="8" class="text-center text-danger py-4">${data.message}</td></tr>`;
                document.getElementById('no-usuarios-message').classList.remove('d-none');
                document.getElementById('pagination-controls').innerHTML = '';
                document.getElementById('pagination-info').innerHTML = '';
            }
        } catch (error) {
            console.error('Error al obtener todos los usuarios:', error);
            mostrarToast('danger', 'Error de red o del servidor al cargar usuarios.');
            tableBody.innerHTML = `<tr><td colspan="8" class="text-center text-danger py-4">Error de conexión al servidor al cargar todos los usuarios.</td></tr>`;
            document.getElementById('no-usuarios-message').classList.remove('d-none');
            document.getElementById('pagination-controls').innerHTML = '';
            document.getElementById('pagination-info').innerHTML = '';
        }
    }

    // Función para aplicar filtros y renderizar la tabla y paginación
    function applyFiltersAndRenderTable() {
        const tableBody = document.getElementById('usuarios-table-body');
        const noUsersMessage = document.getElementById('no-usuarios-message');
        
        // 1. Aplicar búsqueda (filtrar allUsers)
        if (searchTerm) {
            filteredUsers = allUsers.filter(user =>
                user.nombre.toLowerCase().includes(searchTerm) ||
                user.email.toLowerCase().includes(searchTerm) ||
                user.rol.toLowerCase().includes(searchTerm)
            );
        } else {
            filteredUsers = [...allUsers]; // Si no hay búsqueda, todos los usuarios son 'filtrados'
        }

        // Mostrar u ocultar mensaje de "no usuarios"
        if (filteredUsers.length === 0) {
            tableBody.innerHTML = ''; // Limpiar tabla
            noUsersMessage.classList.remove('d-none');
            renderPagination(0, 0, 0); // Ocultar paginación
            return;
        } else {
            noUsersMessage.classList.add('d-none');
        }

        // 2. Aplicar paginación (a los filteredUsers)
        const startIndex = (currentPage - 1) * recordsPerPage;
        const endIndex = startIndex + recordsPerPage;
        const usersToDisplay = filteredUsers.slice(startIndex, endIndex);

        // 3. Renderizar la tabla con los usuarios a mostrar
        tableBody.innerHTML = '';
        usersToDisplay.forEach(usuario => {
            const row = `
                <tr>
                    <td class="text-center">${usuario.id}</td>
                    <td>${escapeHtml(usuario.nombre)}</td>
                    <td>${escapeHtml(usuario.email)}</td>
                    <td><span class="text-muted small fst-italic">••••••••</span></td>
                    <td><span class="badge bg-secondary text-uppercase">${escapeHtml(usuario.rol)}</span></td>
                    <td>${escapeHtml(usuario.creado_en)}</td>
                    <td class="text-center">
                        ${usuario.activo == 1 ? `
                            <button class="btn text-success btn-sm d-flex align-items-center gap-2 px-3 shadow-sm"
                                title="Haz clic para desactivar" onclick="cambiarEstadoUsuario(${usuario.id}, false)">
                                <i class="bi bi-toggle-on fs-5"></i> Activo
                            </button>` : `
                            <button class="btn text-danger btn-sm d-flex align-items-center gap-2 px-3 shadow-sm"
                                title="Haz clic para activar" onclick="cambiarEstadoUsuario(${usuario.id}, true)">
                                <i class="bi bi-toggle-off fs-5"></i> Inactivo
                            </button>`
                        }
                    </td>
                    <td class="text-center">
                        <div class="d-flex gap-2 justify-content-center flex-wrap">
                            <button class="btn btn-sm btn-outline-warning" onclick="abrirModalEdicion({
                                id: ${usuario.id},
                                nombre: '${escapeHtml(usuario.nombre)}',
                                email: '${escapeHtml(usuario.email)}',
                                rol: '${escapeHtml(usuario.rol)}',
                                activo: ${usuario.activo}
                            })">
                                <i class="bi bi-pencil-square"></i> 
                            </button>
                            <?php if (isset($rol) && $rol === 'admin'): ?>
                                <button class="btn btn-sm btn-outline-danger eliminar-usuario-btn"
                                    onclick="eliminarUsuario(${usuario.id}, '${escapeHtml(usuario.nombre)}')"
                                    title="Eliminar Usuario">
                                    <i class="bi bi-trash"></i> 
                                </button>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            `;
            tableBody.innerHTML += row;
        });

        // 4. Renderizar controles de paginación
        const totalPages = Math.ceil(filteredUsers.length / recordsPerPage);
        renderPagination(totalPages, currentPage, filteredUsers.length);
    }

    // Función para renderizar los controles de paginación (igual que antes, pero usa filteredUsers.length)
    function renderPagination(totalPages, currentPage, totalRecords) {
        const paginationControls = document.getElementById('pagination-controls');
        const paginationInfo = document.getElementById('pagination-info');
        paginationControls.innerHTML = ''; // Limpiar controles existentes

        if (totalRecords === 0) {
            paginationInfo.textContent = 'Mostrando 0 registros';
            return;
        }

        // Información de paginación
        const startRecord = (currentPage - 1) * recordsPerPage + 1;
        const endRecord = Math.min(currentPage * recordsPerPage, totalRecords);
        paginationInfo.textContent = `Mostrando del ${startRecord} al ${endRecord} de ${totalRecords} registros`;

        // Botón "Anterior"
        const prevLi = document.createElement('li');
        prevLi.classList.add('page-item');
        if (currentPage === 1) prevLi.classList.add('disabled');
        prevLi.innerHTML = `<a class="page-link" href="#" aria-label="Previous" onclick="changePage(${currentPage - 1})"><span aria-hidden="true">&laquo;</span></a>`;
        paginationControls.appendChild(prevLi);

        // Números de página
        for (let i = 1; i <= totalPages; i++) {
            const pageLi = document.createElement('li');
            pageLi.classList.add('page-item');
            if (i === currentPage) pageLi.classList.add('active');
            pageLi.innerHTML = `<a class="page-link" href="#" onclick="changePage(${i})">${i}</a>`;
            paginationControls.appendChild(pageLi);
        }

        // Botón "Siguiente"
        const nextLi = document.createElement('li');
        nextLi.classList.add('page-item');
        if (currentPage === totalPages) nextLi.classList.add('disabled');
        nextLi.innerHTML = `<a class="page-link" href="#" aria-label="Next" onclick="changePage(${currentPage + 1})"><span aria-hidden="true">&raquo;</span></a>`;
        paginationControls.appendChild(nextLi);
    }

    // Función para cambiar de página (igual que antes)
    function changePage(page) {
        const totalPages = Math.ceil(filteredUsers.length / recordsPerPage);
        if (page < 1 || page > totalPages) {
            return;
        }
        currentPage = page;
        applyFiltersAndRenderTable();
    }

    // Helper para escapar HTML en las strings de JavaScript
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return String(text).replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    // --- FUNCIONES DE MODAL Y ACCIONES (CON UN PEQUEÑO AJUSTE) ---

    // Función para abrir modal en modo registro
    function abrirModalRegistro() {
        document.getElementById('modalTitulo').textContent = 'Registrar Usuario';
        document.getElementById('modalBotonTexto').textContent = 'Registrar';
        document.getElementById('usuario_id').value = '';
        document.getElementById('nombre').value = '';
        document.getElementById('email').value = '';
        document.getElementById('contrasena').value = '';
        document.getElementById('rol').value = '';
        document.getElementById('activo-container').classList.add('d-none');
        document.getElementById('formularioEditarRegistrar').classList.remove('was-validated'); // Limpiar validación previa

        const modal = new bootstrap.Modal(document.getElementById('modalUsuario'));
        modal.show();
    }

    // Función para abrir modal en modo edición
    function abrirModalEdicion(usuario) {
        document.getElementById('modalTitulo').textContent = 'Editar Usuario';
        document.getElementById('modalBotonTexto').textContent = 'Actualizar';
        document.getElementById('usuario_id').value = usuario.id;
        document.getElementById('nombre').value = usuario.nombre;
        document.getElementById('email').value = usuario.email;
        document.getElementById('contrasena').value = ''; // Siempre limpiar la contraseña
        document.getElementById('rol').value = usuario.rol;

        if ('activo' in usuario) {
            document.getElementById('activo-container').classList.remove('d-none');
            document.getElementById('activo').checked = usuario.activo == 1 || usuario.activo === true;
        } else {
            document.getElementById('activo-container').classList.add('d-none');
            document.getElementById('activo').checked = false;
        }
        document.getElementById('formularioEditarRegistrar').classList.remove('was-validated');

        const modal = new bootstrap.Modal(document.getElementById('modalUsuario'));
        modal.show();
    }

    // Manejo del formulario de registro/edición (único listener)
    // Importante: Después de un registro/edición/eliminación exitosa, necesitas volver a cargar
    // TODOS los usuarios desde el servidor para que el array 'allUsers' esté actualizado.
    document.getElementById('formularioEditarRegistrar').addEventListener('submit', async function (e) {
        e.preventDefault();

        const form = e.target;
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }

        const formData = new FormData(form);

        try {
            const response = await fetch('../api/guardar_actualizar_usuarios.php', {
                method: 'POST',
                body: formData
            });
            const resultado = await response.json();

            if (resultado.status) {
                mostrarToast('success', resultado.message);
                const modal = bootstrap.Modal.getInstance(document.getElementById('modalUsuario'));
                modal.hide();
                fetchAndStoreAllUsers(); // ¡Volver a cargar TODOS los usuarios!
            } else {
                mostrarToast('warning', resultado.message || 'Error inesperado');
            }
        } catch (error) {
            mostrarToast('danger', 'Error de red o del servidor');
            console.error(error);
        }
    });

    function cambiarEstadoUsuario(idUsuario, nuevoEstado) {
        mostrarConfirmacionToast(
            `¿Estás seguro de que deseas ${nuevoEstado ? 'activar' : 'desactivar'} este usuario?`,
            async () => {
                const formData = new FormData();
                formData.append('id', idUsuario);
                formData.append('estado', nuevoEstado ? 1 : 0);

                try {
                    const res = await fetch('../api/cambiar_estado_usuario.php', {
                        method: 'POST',
                        body: formData
                    });
                    const data = await res.json();
                    if (data.status) {
                        mostrarToast('success', data.message);
                        fetchAndStoreAllUsers(); // ¡Volver a cargar TODOS los usuarios!
                    } else {
                        mostrarToast('warning', data.message);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    mostrarToast('danger', 'Ocurrió un error al cambiar el estado.');
                }
            }
        );
    }

    function eliminarUsuario(idUsuario, usuario) {
        mostrarConfirmacionToast(
            `¿Estás seguro de que deseas eliminar el usuario ${usuario}?`,
            async () => {
                const formData = new FormData();
                formData.append('id', idUsuario);

                try {
                    const res = await fetch('../api/eliminar_usuario.php', {
                        method: 'POST',
                        body: formData
                    });
                    const data = await res.json();
                    if (data.status) {
                        mostrarToast('success', data.message);
                        fetchAndStoreAllUsers(); // ¡Volver a cargar TODOS los usuarios!
                    } else {
                        mostrarToast('warning', data.message);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    mostrarToast('danger', 'Ocurrió un error al eliminar el usuario.');
                }
            }
        );
    }

    // Funciones de Toast y Confirmación (mantén las tuyas o usa estas básicas)
    function mostrarToast(type, message) {
        console.log(`Toast (${type}): ${message}`);
        // Implementación de toast (ej. con Bootstrap Toast)
        // Necesitas tener el HTML para el toast en tu página
    }

    function mostrarConfirmacionToast(message, onConfirm) {
        if (confirm(message)) {
            onConfirm();
        }
    }
</script>

<?php include_once('../includes/footer.php'); ?>