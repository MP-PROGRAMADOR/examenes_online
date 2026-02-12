<?php
include_once("../includes/header.php");

try {
    // Preparar y ejecutar la consulta para obtener las escuelas
    $sql = "SELECT * FROM escuelas_conduccion";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // Obtener todas las escuelas
    $escuelas = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Manejo de errores: si ocurre un error en la consulta, se captura y se muestra un mensaje
    error_log("Error en la consulta: " . $e->getMessage());
    // Aquí puedes decidir si quieres mostrar un mensaje al usuario, por ejemplo:
    // $alerta = ['tipo' => 'error', 'mensaje' => 'Ocurrió un error al recuperar las escuelas. Por favor, inténtelo de nuevo más tarde.'];
}

// Suponiendo que $rol se define en header.php o en algún lugar accesible.
// Si no es así, asegúrate de definirla, por ejemplo:
// $rol = 'admin'; // O el rol del usuario logueado
?>
 
<div class="d-flex">
    <?php include_once("../includes/sidebar.php"); ?>

    <div id="content" class="main-content">
        <div class="container-fluid mt-5">
            <div class="card shadow border-0 rounded-4">
                <div class="card-header bg-primary text-white d-flex flex-wrap justify-content-between align-items-center rounded-top-4 px-4 py-3">
                    <h5 class="mb-0"><i class="bi bi-buildings-fill me-2"></i>Listado de Escuelas</h5>
                    <div class="search-box position-relative">
                        <input type="text" class="form-control ps-5" id="customSearch" placeholder="Buscar Escuela...">
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
                        <button class="btn btn-success" onclick="abrirModalRegistro()">
                            <i class="bi bi-person-plus-fill me-2"></i>Crear Nueva
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="escuelas-table" class="table table-striped table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th><i class="bi bi-hash me-1 text-secondary"></i>ID</th>
                                <th><i class="bi bi-building me-1 text-secondary"></i>Nombre</th>
                                <th><i class="bi bi-phone me-1 text-secondary"></i>Teléfono</th>
                                <th><i class="bi bi-person me-1 text-secondary"></i>Director</th>
                                <th><i class="bi bi-card-heading me-1 text-secondary"></i>NIF</th>
                                <th><i class="bi bi-geo-alt-fill me-1 text-secondary"></i>Ciudad</th>
                                <th><i class="bi bi-envelope me-1 text-secondary"></i>Correo</th>
                                <th><i class="bi bi-flag me-1 text-secondary"></i>País</th>
                                <th><i class="bi bi-pin-map me-1 text-secondary"></i>Ubicación</th>
                                <th><i class="bi bi-journal-text me-1 text-secondary"></i>Nº Registro</th>
                                <th><i class="bi bi-gear-fill me-1 text-secondary"></i>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="escuelas-table-body">
                            <tr>
                                <td colspan="11" class="text-center">Cargando escuelas...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-light d-flex justify-content-between align-items-center px-4 py-3 rounded-bottom-4">
                    <div id="pagination-info"></div>
                    <nav aria-label="Page navigation">
                        <ul class="pagination mb-0" id="pagination-controls">
                            </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEscuela" tabindex="-1" aria-labelledby="modalEscuelaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header bg-primary text-white rounded-top">
                <h5 class="modal-title" id="modalEscuelaLabel">
                    <i class="bi bi-person-plus-fill me-2"></i><span id="modalTitulo">Registrar Escuela</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Cerrar"></button>
            </div>
            <form id="formularioEditarRegistrar" method="POST" class="needs-validation" novalidate>
                <div class="modal-body p-4">
                    <input type="hidden" name="escuela_id" id="escuela_id">

                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-semibold">
                            <i class="bi bi-building me-2 text-primary"></i>Nombre de la escuela <span
                                class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control shadow-sm" id="nombre" name="nombre" required>
                        <div class="invalid-feedback">Por favor ingresa el nombre de la escuela.</div>
                    </div>

                    <div class="mb-3">
                        <label for="telefono" class="form-label fw-semibold">
                            <i class="bi bi-phone me-2 text-primary"></i>Teléfono <span class="text-danger">*</span>
                        </label>
                        <input type="tel" class="form-control shadow-sm" id="telefono" name="telefono" required>
                        <div class="invalid-feedback">Por favor ingresa un número de teléfono válido.</div>
                    </div>

                    <div class="mb-3">
                        <label for="director" class="form-label fw-semibold">
                            <i class="bi bi-person-square me-2 text-primary"></i>Director <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control shadow-sm" id="director" name="director" required>
                        <div class="invalid-feedback">Por favor ingresa el nombre del director.</div>
                    </div>

                    <div class="mb-3">
                        <label for="nif" class="form-label fw-semibold">
                            <i class="bi bi-person-vcard me-2 text-primary"></i>NIF <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control shadow-sm" id="nif" name="nif" required>
                        <div class="invalid-feedback">Por favor ingresa el NIF.</div>
                    </div>

                    <div class="mb-3">
                        <label for="ciudad" class="form-label fw-semibold">
                            <i class="bi bi-geo-alt-fill me-2 text-primary"></i>Ciudad <span
                                class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control shadow-sm" id="ciudad" name="ciudad" required>
                        <div class="invalid-feedback">Por favor ingresa una ciudad válida.</div>
                    </div>

                    <div class="mb-3">
                        <label for="correo" class="form-label fw-semibold">
                            <i class="bi bi-envelope-fill me-2 text-primary"></i>Correo Electrónico
                        </label>
                        <input type="email" class="form-control shadow-sm" id="correo" name="correo">
                        <div class="invalid-feedback">Por favor ingresa un correo electrónico válido.</div>
                    </div>

                    <div class="mb-3">
                        <label for="pais" class="form-label fw-semibold">
                            <i class="bi bi-flag-fill me-2 text-primary"></i>País <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control shadow-sm" id="pais" name="pais" value="Guinea Ecuatorial" required>
                        <div class="invalid-feedback">Por favor ingresa el país.</div>
                    </div>

                    <div class="mb-3">
                        <label for="ubicacion" class="form-label fw-semibold">
                            <i class="bi bi-map me-2 text-primary"></i>Ubicación <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control shadow-sm" id="ubicacion" name="ubicacion" required>
                        <div class="invalid-feedback">Por favor ingresa la ubicación.</div>
                    </div>

                    <div class="mb-3">
                        <label for="numero_registro" class="form-label fw-semibold">
                            <i class="bi bi-journal-text me-2 text-primary"></i>Número de Registro <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control shadow-sm" id="numero_registro" name="numero_registro" required>
                        <div class="invalid-feedback">Por favor ingresa el número de registro.</div>
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

    // Variables globales para la paginación y búsqueda
    let currentPage = 1;
    let recordsPerPage = parseInt(document.getElementById('container-length').value);
    let searchTerm = '';
    const userRole = "<?php echo $rol; ?>"; // Pasa el rol del usuario desde PHP a JavaScript
    let totalPages = 1; // Añadimos una variable global para totalPages

    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar la primera carga de datos
        fetchEscuelas();

        // Event listener para el buscador
        const customSearchInput = document.getElementById('customSearch');
        let searchTimeout;
        customSearchInput.addEventListener('keyup', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                searchTerm = this.value;
                currentPage = 1; // Resetear a la primera página en cada nueva búsqueda
                fetchEscuelas();
            }, 300); // Pequeño delay para evitar peticiones excesivas
        });

        // Event listener para el selector de cantidad de registros
        document.getElementById('container-length').addEventListener('change', function() {
            recordsPerPage = parseInt(this.value);
            currentPage = 1; // Resetear a la primera página cuando cambia la cantidad de registros
            fetchEscuelas();
        });

        // Event listener para el submit del formulario de registro/edición
        const form = document.getElementById('formularioEditarRegistrar');
        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return;
            }

            const formData = new FormData(form);

            try {
                const response = await fetch('../api/guardar_actualizar_escuela.php', {
                    method: 'POST',
                    body: formData
                });

                const resultado = await response.json();

                if (resultado.status) {
                    mostrarToast('success', resultado.message);
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalEscuela'));
                    modal.hide();
                    // Recargar la tabla con los datos actualizados sin recargar la página completa
                    fetchEscuelas();
                } else {
                    mostrarToast('warning', resultado.message || 'Error inesperado');
                }

            } catch (error) {
                mostrarToast('danger', 'Error de red o del servidor al guardar/actualizar.');
                console.error(error);
            }
            form.classList.remove('was-validated'); // Limpiar validación
        });
    });

    // Función principal para obtener y renderizar las escuelas
    async function fetchEscuelas() {
        const tableBody = document.getElementById('escuelas-table-body');
        const paginationInfo = document.getElementById('pagination-info');
        const paginationControls = document.getElementById('pagination-controls');

        // Mostrar un mensaje de carga
        tableBody.innerHTML = `<tr><td colspan="11" class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-2">Cargando escuelas...</p>
        </td></tr>`;
        paginationInfo.textContent = '';
        paginationControls.innerHTML = '';

        try {
            const response = await fetch(`../api/buscar_escuelas.php?page=${currentPage}&limit=${recordsPerPage}&search=${encodeURIComponent(searchTerm)}`);
            const data = await response.json();

            if (data.status) {
                // Actualizar la variable global totalPages con el valor recibido del backend
                totalPages = data.totalPages;
                renderTable(data.escuelas);
                renderPagination(data.currentPage, data.totalPages, data.totalRecords, data.perPage);
            } else {
                tableBody.innerHTML = `<tr><td colspan="11"><div class="alert alert-danger text-center mt-3 mb-3">${data.message || 'Error al cargar los datos de las escuelas.'}</div></td></tr>`;
                paginationInfo.textContent = '';
                paginationControls.innerHTML = '';
            }
        } catch (error) {
            console.error('Error al obtener las escuelas:', error);
            tableBody.innerHTML = `<tr><td colspan="11"><div class="alert alert-danger text-center mt-3 mb-3">Error de conexión al servidor. Inténtelo de nuevo.</div></td></tr>`;
            paginationInfo.textContent = '';
            paginationControls.innerHTML = '';
        }
    }

    // Función para renderizar la tabla con los datos recibidos
    function renderTable(escuelas) {
        const tableBody = document.getElementById('escuelas-table-body');
        tableBody.innerHTML = ''; // Limpiar contenido anterior

        if (escuelas.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="11"><div class="alert alert-warning text-center mt-3 mb-3">
                <i class="bi bi-exclamation-circle-fill me-2"></i>⚠️ No se encontraron escuelas con los criterios de búsqueda.
            </div></td></tr>`;
            return;
        }

        escuelas.forEach(escuela => {
            const row = `
                <tr>
                    <td>${escuela.id}</td>
                    <td>${escapeHTML(escuela.nombre)}</td>
                    <td>${escapeHTML(escuela.telefono)}</td>
                    <td>${escapeHTML(escuela.director)}</td>
                    <td>${escapeHTML(escuela.nif)}</td>
                    <td>${escapeHTML(escuela.ciudad)}</td>
                    <td>${escapeHTML(escuela.correo || '')}</td>
                    <td>${escapeHTML(escuela.pais)}</td>
                    <td>${escapeHTML(escuela.ubicacion)}</td>
                    <td>${escapeHTML(escuela.numero_registro)}</td>
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center flex-wrap">
                            <button class="btn btn-sm btn-outline-warning" onclick='abrirModalEdicion(${JSON.stringify(escuela)})'>
                                <i class="bi bi-pencil-square"></i> 
                            </button>
                            ${userRole === 'admin' ? `
                            <button class="btn btn-sm btn-outline-danger" onclick="eliminarEscuela(${escuela.id}, '${escapeHTML(escuela.nombre)}')">
                                <i class="bi bi-trash"></i> 
                            </button>
                            ` : ''}
                        </div>
                    </td>
                </tr>
            `;
            tableBody.insertAdjacentHTML('beforeend', row);
        });
    }

    // Función para renderizar los controles de paginación
    function renderPagination(currPage, totalPages, totalRecords, perPage) {
        const paginationInfo = document.getElementById('pagination-info');
        const paginationControls = document.getElementById('pagination-controls');
        paginationControls.innerHTML = ''; // Limpiar controles anteriores

        if (totalRecords === 0) {
            paginationInfo.textContent = 'Mostrando 0 de 0 registros';
            return;
        }

        const startRecord = (currPage - 1) * perPage + 1;
        const endRecord = Math.min(currPage * perPage, totalRecords);
        paginationInfo.textContent = `Mostrando ${startRecord} a ${endRecord} de ${totalRecords} registros`;

        // Botón "Anterior"
        const prevLi = document.createElement('li');
        // Usamos currPage para la lógica de deshabilitar
        prevLi.className = `page-item ${currPage === 1 ? 'disabled' : ''}`;
        prevLi.innerHTML = `<a class="page-link" href="#" aria-label="Previous" onclick="changePage(${currPage - 1})"><span aria-hidden="true">&laquo;</span></a>`;
        paginationControls.appendChild(prevLi);

        // Números de página
        const maxPagesToShow = 5; // Número máximo de botones de página visibles
        let startPage = Math.max(1, currPage - Math.floor(maxPagesToShow / 2));
        let endPage = Math.min(totalPages, startPage + maxPagesToShow - 1);

        // Ajustar el rango si no hay suficientes páginas después del inicio
        if (endPage - startPage + 1 < maxPagesToShow) {
            startPage = Math.max(1, totalPages - maxPagesToShow + 1);
        }

        for (let i = startPage; i <= endPage; i++) {
            const li = document.createElement('li');
            li.className = `page-item ${i === currPage ? 'active' : ''}`;
            li.innerHTML = `<a class="page-link" href="#" onclick="changePage(${i})">${i}</a>`;
            paginationControls.appendChild(li);
        }

        // Botón "Siguiente"
        const nextLi = document.createElement('li');
        // Usamos currPage para la lógica de deshabilitar
        nextLi.className = `page-item ${currPage === totalPages ? 'disabled' : ''}`;
        nextLi.innerHTML = `<a class="page-link" href="#" aria-label="Next" onclick="changePage(${currPage + 1})"><span aria-hidden="true">&raquo;</span></a>`;
        paginationControls.appendChild(nextLi);
    }

    // Función para cambiar de página
    function changePage(page) {
        // Validar que la página solicitada esté dentro del rango válido
        if (page >= 1 && page <= totalPages) { // Usamos la variable global totalPages
            currentPage = page;
            fetchEscuelas();
        }
    }

    // Función para abrir modal en modo registro
    function abrirModalRegistro() {
        document.getElementById('modalTitulo').textContent = 'Registrar Escuela';
        document.getElementById('modalBotonTexto').textContent = 'Registrar';
        // Limpiar todos los campos del formulario al abrir en modo registro
        document.getElementById('escuela_id').value = '';
        document.getElementById('nombre').value = '';
        document.getElementById('telefono').value = '';
        document.getElementById('director').value = '';
        document.getElementById('nif').value = '';
        document.getElementById('ciudad').value = '';
        document.getElementById('correo').value = '';
        document.getElementById('pais').value = 'Guinea Ecuatorial'; // Valor por defecto
        document.getElementById('ubicacion').value = '';
        document.getElementById('numero_registro').value = '';

        // Remover clases de validación si existen de una apertura anterior
        document.getElementById('formularioEditarRegistrar').classList.remove('was-validated');

        const modal = new bootstrap.Modal(document.getElementById('modalEscuela'));
        modal.show();
    }

    // Función para abrir modal en modo edición, recibe un objeto escuela con los datos
    function abrirModalEdicion(escuela) {
        document.getElementById('modalTitulo').textContent = 'Editar Escuela';
        document.getElementById('modalBotonTexto').textContent = 'Actualizar';
        // Rellenar todos los campos del formulario con los datos de la escuela
        document.getElementById('escuela_id').value = escuela.id;
        document.getElementById('nombre').value = escuela.nombre;
        document.getElementById('telefono').value = escuela.telefono;
        document.getElementById('director').value = escuela.director;
        document.getElementById('nif').value = escuela.nif;
        document.getElementById('ciudad').value = escuela.ciudad;
        document.getElementById('correo').value = escuela.correo;
        document.getElementById('pais').value = escuela.pais;
        document.getElementById('ubicacion').value = escuela.ubicacion;
        document.getElementById('numero_registro').value = escuela.numero_registro;

        // Remover clases de validación si existen de una apertura anterior
        document.getElementById('formularioEditarRegistrar').classList.remove('was-validated');

        const modal = new bootstrap.Modal(document.getElementById('modalEscuela'));
        modal.show();
    }

    function eliminarEscuela(idEscuela, escuelaNombre) {
        mostrarConfirmacionToast(
            `¿Estás seguro de que deseas eliminar la escuela ${escuelaNombre}?`,
            () => {
                const formData = new FormData();
                formData.append('id', idEscuela);

                fetch('../api/eliminar_escuela.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status) {
                        mostrarToast('success', data.message);
                        fetchEscuelas(); // Recargar la tabla después de eliminar
                    } else {
                        mostrarToast('warning', data.message);
                    }
                })
                .catch(error => {
                    mostrarToast('danger', 'Ocurrió un error al intentar eliminar la escuela.');
                    console.error('Error al eliminar escuela:', error);
                });
            }
        );
    }

    // Función de utilidad para escapar HTML (prevención XSS)
    function escapeHTML(str) {
        const div = document.createElement('div');
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    }

    // Validación Bootstrap (se mantiene al inicio del script)
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
<?php include_once('../includes/footer.php'); ?>