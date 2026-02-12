<?php
include_once("../includes/header.php");
include_once("../includes/sidebar.php");

// Asegúrate de que $rol esté definido si es necesario para la visibilidad de botones
// Por ejemplo, si tienes un sistema de sesión:
// session_start();
// $rol = $_SESSION['rol'] ?? '';

// La consulta PHP inicial para obtener preguntas se elimina
// Ahora los datos se obtendrán vía AJAX en JavaScript
?>

<main class="main-content" id="content">
    <div class="card shadow border-0 rounded-4">
        <div class="card-header bg-primary text-white d-flex flex-wrap justify-content-between align-items-center rounded-top-4 px-4 py-3">
            <h5 class="mb-0"><i class="bi bi-question-circle-fill me-2"></i>Gestión de Preguntas</h5>

            <div class="search-box position-relative">
                <input type="text" class="form-control ps-5" id="customSearch" placeholder="Buscar pregunta...">
                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
            </div>

            <div class="d-flex flex-wrap gap-5 align-items-center">
                <div class="d-flex align-items-center">
                    <label for="preguntas-length" class="me-2 text-white fw-medium mb-0">Mostrar:</label>
                    <select id="preguntas-length" class="form-select w-auto shadow-sm">
                        <option value="5">5 registros</option>
                        <option value="10" selected>10 registros</option>
                        <option value="15">15 registros</option>
                        <option value="20">20 registros</option>
                        <option value="25">25 registros</option>
                    </select>
                </div>
                <button class="btn btn-success fw-semibold shadow-sm" onclick="abrirModalRegistro()">
                    <i class="bi bi-plus-circle-fill me-2"></i> Nueva Pregunta
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table id="preguntas-table" class="table table-hover align-middle mb-0 text-center">
                <thead class="table-light">
                    <tr>
                        <th><i class="bi bi-hash me-1"></i>ID</th>
                        <th><i class="bi bi-chat-left-dots-fill me-1"></i>Texto</th>
                        <th><i class="bi bi-ui-checks-grid me-1"></i>Tipo</th>
                        <th><i class="bi bi-image-fill me-1"></i>Contenido</th>
                        <th><i class="bi bi-toggle-on me-1"></i>Estado</th>
                        <th><i class="bi bi-calendar-event-fill me-1"></i>Creada</th>
                        <th><i class="bi bi-gear-fill me-1"></i>Acciones</th>
                    </tr>
                </thead>
                <tbody id="preguntas-table-body">
                    </tbody>
            </table>
        </div>
        <div id="no-preguntas-message" class="alert alert-warning text-center m-3 d-none">
            <i class="bi bi-exclamation-circle-fill me-2"></i>⚠️ No hay preguntas registradas actualmente o que coincidan con la búsqueda.
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

<div class="modal fade" id="modalPregunta" tabindex="-1" aria-labelledby="modalPreguntaLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" style="margin-top: 4rem; max-width: 50vw;">
        <form id="formPregunta" enctype="multipart/form-data" class="needs-validation w-100" novalidate>
            <div class="modal-content shadow-lg rounded-4 border-0">
                <div class="modal-header bg-gradient bg-primary text-white rounded-top-4 px-4 py-3">
                    <h5 class="modal-title d-flex align-items-center gap-2" id="modalPreguntaLabel">
                        <i class="bi bi-patch-question-fill fs-4"></i> Nueva Pregunta
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4 py-3 bg-light">
                    <input type="hidden" name="pregunta_id" id="pregunta_id">

                    <div class="mb-3">
                        <label class="form-label fw-semibold"><i class="bi bi-file-earmark-text me-1"></i> Tipo de contenido</label>
                        <select name="tipo_contenido" id="tipo_contenido" class="form-select rounded-pill shadow-sm" required>
                            <option value="texto">Texto</option>
                            <option value="ilustracion">Ilustración</option>
                        </select>
                    </div>

                    <div class="mb-3" id="textoPreguntaContainer">
                        <label for="texto" class="form-label fw-semibold"><i class="bi bi-card-text me-1"></i> Texto de la pregunta</label>
                        <textarea name="texto" id="texto" class="form-control shadow-sm rounded-3" rows="2" required></textarea>
                    </div>

                    <div class="mb-3 d-none" id="imagenesPreguntaContainer">
                        <label class="form-label fw-semibold"><i class="bi bi-images me-1"></i> Imágenes</label>
                        <div id="contenedorImagenes" class="border rounded-3 p-2 bg-white shadow-sm"></div>
                        <button type="button" class="btn btn-outline-primary btn-sm mt-2 rounded-pill" id="agregarImagen">
                            <i class="bi bi-plus-circle me-1"></i> Añadir imagen
                        </button>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold"><i class="bi bi-ui-checks me-1"></i> Tipo de pregunta</label>
                        <select name="tipo" id="tipo" class="form-select rounded-pill shadow-sm" required>
                            <option value="unica">Opción única</option>
                            <option value="multiple">Opción múltiple</option>
                            <option value="vf">Verdadero / Falso</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold"><i class="bi bi-list-check me-1"></i> Opciones</label>
                        <div id="contenedorOpciones" class="border rounded-3 p-2 bg-white shadow-sm"></div>
                        <button type="button" class="btn btn-outline-primary btn-sm mt-2 rounded-pill" id="agregarOpcion">
                            <i class="bi bi-plus-circle me-1"></i> Añadir opción
                        </button>
                    </div>

                    <div class="mb-3" id="divCategoria">
                        <div class="mb-3">
                            <label class="form-label fw-semibold"><i class="bi bi-tags me-1"></i> ¿Asignar categoría?</label><br>
                            <button type="button" id="toggleCategoria"
                                class="btn btn-outline-dark d-flex align-items-center gap-2 px-3 py-1 rounded-pill shadow-sm"
                                onclick="toggleAsignarCategoria()">
                                <i id="iconCategoria" class="bi bi-toggle-off fs-5"></i>
                                <span id="textoCategoria">No</span>
                            </button>
                            <input type="hidden" name="asignar_categoria" id="asignar_categoria" value="no">
                        </div>

                        <div id="contenedorCategorias" class="mt-3 d-none">
                            <label for="categoria_id" class="form-label fw-semibold"><i class="bi bi-folder-check me-1"></i> Categorías</label>
                            <div id="listaCategorias" class="d-flex flex-wrap gap-2"></div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer bg-white border-top-0 px-4 py-3">
                    <button type="submit" class="btn btn-primary rounded-pill shadow-sm" id="modalBotonTexto">
                        <i class="bi bi-save2 me-1"></i> Guardar Pregunta
                    </button>
                    <button type="button" class="btn btn-outline-secondary rounded-pill shadow-sm" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> Cancelar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalCategoriasPregunta" tabindex="-1" aria-labelledby="tituloCategorias"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" style="margin-top: 3vh;">
        <div class="modal-content shadow-lg rounded-4 border-0">
            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title" id="tituloCategorias">
                    <i class="bi bi-tags-fill me-2"></i> Categorías asignadas
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div id="tablaCategoriasPregunta" class="table-responsive mb-4"></div>

                <div class="text-end mb-3">
                    <button class="btn btn-success w-100 w-md-auto" onclick="mostrarSelectorNuevaCategoria()">
                        <i class="bi bi-plus-circle-fill me-1"></i> Nueva categoría
                    </button>
                </div>

                <div id="contenedorNuevaCategoria" class="d-none">
                    <div class="d-flex flex-column flex-md-row justify-content-end align-items-stretch gap-3">
                        <select id="selectNuevaCategoria" class="form-select w-100 w-md-auto"></select>
                        <button class="btn btn-primary" onclick="asignarNuevaCategoria()">
                            <i class="bi bi-check2-circle me-1"></i> Asignar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Variables globales para la paginación y búsqueda en el frontend
    let allQuestions = []; // Aquí se almacenarán TODAS las preguntas cargadas
    let filteredQuestions = []; // Aquí se almacenarán las preguntas después de aplicar el filtro de búsqueda
    let currentPage = 1;
    let recordsPerPage = 10;
    let searchTerm = ''; // Variable para el término de búsqueda

    const contenedorCategorias = document.getElementById('contenedorCategorias');
    const listaCategorias = document.getElementById('listaCategorias');

    // Autoinvocada para inicializar listeners y la carga inicial
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

        // Event listener para el selector de registros por página
        document.getElementById('preguntas-length').addEventListener('change', (event) => {
            recordsPerPage = parseInt(event.target.value);
            currentPage = 1; // Volver a la primera página al cambiar la cantidad de registros
            applyFiltersAndRenderTable(); // Reaplicar filtros y renderizar
        });

        // Event listener para el campo de búsqueda
        document.getElementById('customSearch').addEventListener('input', (event) => { // Cambiado a customSearch
            searchTerm = event.target.value.trim().toLowerCase();
            currentPage = 1; // Volver a la primera página al realizar una nueva búsqueda
            applyFiltersAndRenderTable(); // Reaplicar filtros y renderizar
        });

        // Inicializar carga de preguntas al cargar la página
        fetchAndStoreAllQuestions();

    })();

    // --- NUEVAS FUNCIONES PARA EL ENFOQUE DE FRONTEND ---

    // Función para obtener TODAS las preguntas una vez y almacenarlas
    async function fetchAndStoreAllQuestions() {
        const tableBody = document.getElementById('preguntas-table-body');
        tableBody.innerHTML = `<tr><td colspan="7" class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div></td></tr>`;
        
        try {
            const url = `../api/obtener_preguntas_fetch_lista_admin.php`; // No se pasan parámetros de paginación/búsqueda
            const response = await fetch(url);
            const data = await response.json();

            if (data.status) {
                allQuestions = data.data; // Almacenar todas las preguntas
                applyFiltersAndRenderTable(); // Aplicar filtro inicial y renderizar
            } else {
                mostrarToast('danger', data.message);
                tableBody.innerHTML = `<tr><td colspan="7" class="text-center text-danger py-4">${data.message}</td></tr>`;
                document.getElementById('no-preguntas-message').classList.remove('d-none');
                document.getElementById('pagination-controls').innerHTML = '';
                document.getElementById('pagination-info').innerHTML = '';
            }
        } catch (error) {
            console.error('Error al obtener todas las preguntas:', error);
            mostrarToast('danger', 'Error de red o del servidor al cargar preguntas.');
            tableBody.innerHTML = `<tr><td colspan="7" class="text-center text-danger py-4">Error de conexión al servidor al cargar todas las preguntas.</td></tr>`;
            document.getElementById('no-preguntas-message').classList.remove('d-none');
            document.getElementById('pagination-controls').innerHTML = '';
            document.getElementById('pagination-info').innerHTML = '';
        }
    }

    // Función para aplicar filtros y renderizar la tabla y paginación
    function applyFiltersAndRenderTable() {
        const tableBody = document.getElementById('preguntas-table-body');
        const noQuestionsMessage = document.getElementById('no-preguntas-message');

        // 1. Aplicar búsqueda (filtrar allQuestions)
        if (searchTerm) {
            filteredQuestions = allQuestions.filter(pregunta =>
                pregunta.texto.toLowerCase().includes(searchTerm) ||
                pregunta.tipo.toLowerCase().includes(searchTerm) ||
                pregunta.tipo_contenido.toLowerCase().includes(searchTerm)
            );
        } else {
            filteredQuestions = [...allQuestions]; // Si no hay búsqueda, todas las preguntas son 'filtradas'
        }

        // Mostrar u ocultar mensaje de "no preguntas"
        if (filteredQuestions.length === 0) {
            tableBody.innerHTML = ''; // Limpiar tabla
            noQuestionsMessage.classList.remove('d-none');
            renderPagination(0, 0, 0); // Ocultar paginación
            return;
        } else {
            noQuestionsMessage.classList.add('d-none');
        }

        // 2. Aplicar paginación (a las filteredQuestions)
        const startIndex = (currentPage - 1) * recordsPerPage;
        const endIndex = startIndex + recordsPerPage;
        const questionsToDisplay = filteredQuestions.slice(startIndex, endIndex);

        // 3. Renderizar la tabla con las preguntas a mostrar
        tableBody.innerHTML = '';
        questionsToDisplay.forEach(pregunta => {
            const row = `
                <tr>
                    <td>${pregunta.id}</td>
                    <td class="text-start">${escapeHtml(pregunta.texto)}</td>
                    <td>
                        <span class="badge bg-info text-uppercase">${escapeHtml(pregunta.tipo)}</span>
                    </td>
                    <td>
                        <span class="badge bg-secondary">${escapeHtml(pregunta.tipo_contenido)}</span>
                    </td>
                    <td>
                        ${pregunta.activa == 1 ? `
                            <button class="btn text-success btn-sm px-3 py-1"
                                onclick="cambiarEstadoPregunta(${pregunta.id}, false)" title="Desactivar">
                                <i class="bi bi-toggle-on fs-5"></i> Activa
                            </button>` : `
                            <button class="btn text-danger btn-sm px-3 py-1"
                                onclick="cambiarEstadoPregunta(${pregunta.id}, true)" title="Activar">
                                <i class="bi bi-toggle-off fs-5"></i> Inactiva
                            </button>`
                        }
                    </td>
                    <td>${escapeHtml(pregunta.creado_en)}</td>
                    <td>
                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                            <button class="btn btn-sm btn-outline-primary" onclick="abrirModalCategorias(${pregunta.id})" title="Ver categorías">
                                <i class="bi bi-eye"></i> Categorías
                            </button>
                            <?php if (isset($rol) && $rol === 'admin'): ?>
                                <button class="btn btn-sm btn-outline-danger" onclick="eliminarPregunta(${pregunta.id})" title="Eliminar pregunta">
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
        const totalPages = Math.ceil(filteredQuestions.length / recordsPerPage);
        renderPagination(totalPages, currentPage, filteredQuestions.length);
    }

    // Función para renderizar los controles de paginación (adaptada para preguntas)
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

    // Función para cambiar de página
    function changePage(page) {
        const totalPages = Math.ceil(filteredQuestions.length / recordsPerPage);
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

    // --- FUNCIONES DE MODAL Y ACCIONES (AJUSTADAS PARA LA NUEVA RECARGA) ---

    /* funcion para abrir modal modo registro pregunta*/
    function abrirModalRegistro() {
        document.getElementById('modalPreguntaLabel').textContent = 'Registrar Pregunta';
        document.getElementById('modalBotonTexto').textContent = 'Registrar';
        document.getElementById('formPregunta').reset(); // Limpiar el formulario
        document.getElementById('formPregunta').classList.remove('was-validated'); // Limpiar validación
        
        // Resetear contenedores de imágenes y opciones
        document.getElementById('contenedorImagenes').innerHTML = '';
        document.getElementById('contenedorOpciones').innerHTML = '';
        
        // Resetear el estado de asignación de categoría
        const toggleBtn = document.getElementById('toggleCategoria');
        const iconCat = document.getElementById('iconCategoria');
        const textoCat = document.getElementById('textoCategoria');
        const inputAsignarCat = document.getElementById('asignar_categoria');
        const contCat = document.getElementById('contenedorCategorias');

        inputAsignarCat.value = 'no';
        iconCat.className = 'bi bi-toggle-off fs-5';
        textoCat.textContent = 'No';
        toggleBtn.classList.remove('btn-outline-success');
        toggleBtn.classList.add('btn-outline-dark');
        contCat.classList.add('d-none');
        listaCategorias.innerHTML = ''; // Limpiar categorías seleccionadas

        // Disparar eventos de cambio para inicializar los selectores
        document.getElementById('tipo_contenido').dispatchEvent(new Event('change'));
        document.getElementById('tipo').dispatchEvent(new Event('change'));


        const modal = new bootstrap.Modal(document.getElementById('modalPregunta'));
        modal.show();
    }

    // Manejo del formulario de registro/edición (único listener)
    document.getElementById('formPregunta').addEventListener('submit', async function (e) {
        e.preventDefault();

        const form = e.target;
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }

        const formData = new FormData(form);

        // Ajuste para el checkbox de 'activo' en el modal de edición/registro
        // Si tienes un campo 'activa' en el formulario de la pregunta y no está visible, asegúrate que su valor se envía
        // En este código no veo un checkbox de 'activa' en el modal de pregunta, solo el estado de 'activa' en la tabla.
        // Si lo agregas en el modal, asegúrate de que el FormData lo capture.

        try {
            const response = await fetch('../api/guardar_actualizar_preguntas.php', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();

            if (data.status) {
                mostrarToast('success', data.message);
                const modal = bootstrap.Modal.getInstance(document.getElementById('modalPregunta'));
                modal.hide();
                fetchAndStoreAllQuestions(); // ¡Volver a cargar TODAS las preguntas!
            } else {
                mostrarToast('warning', data.message || 'Ocurrió un error al guardar la pregunta');
            }
        } catch (err) {
            console.error('Error en fetch:', err);
            mostrarToast('danger', 'Error de red. No se pudo conectar con el servidor');
        }
    });

    // Función para mostrar u ocultar el selector de categorías
    function toggleAsignarCategoria() {
        const input = document.getElementById('asignar_categoria');
        const btn = document.getElementById('toggleCategoria');
        const icon = document.getElementById('iconCategoria');
        const texto = document.getElementById('textoCategoria');
        const asignar = input.value === 'no'; // vamos a activar

        input.value = asignar ? 'si' : 'no';
        icon.className = asignar ? 'bi bi-toggle-on fs-5' : 'bi bi-toggle-off fs-5';
        texto.textContent = asignar ? 'Sí' : 'No';

        btn.classList.toggle('btn-outline-success', asignar);
        btn.classList.toggle('btn-outline-dark', !asignar); // Cambiado de btn-outline-danger a btn-outline-dark

        contenedorCategorias.classList.toggle('d-none', !asignar);

        if (asignar) {
            cargarCategorias();
        }
    }

    // Función para cargar categorías desde backend
    function cargarCategorias(categoriasSeleccionadas = []) {
        fetch('../api/obtener_categorias.php')
            .then(res => res.json())
            .then(categorias => {
                listaCategorias.innerHTML = '';
                if (categorias.status && categorias.data.length > 0) {
                    categorias.data.forEach(cat => {
                        const div = document.createElement('div');
                        div.className = 'form-check form-check-inline';
                        div.innerHTML = `
                            <input class="form-check-input" type="checkbox" name="categorias[]" id="cat_${cat.id}" value="${cat.id}" ${categoriasSeleccionadas.includes(cat.id) ? 'checked' : ''}>
                            <label class="form-check-label" for="cat_${cat.id}">${escapeHtml(cat.nombre)}</label>
                        `;
                        listaCategorias.appendChild(div);
                    });
                } else {
                     listaCategorias.innerHTML = '<div class="alert alert-info py-2 px-3 small">No hay categorías disponibles.</div>';
                }
            })
            .catch(err => {
                console.error('Error al obtener categorías:', err);
                listaCategorias.innerHTML = '<div class="alert alert-danger py-2 px-3 small">No se pudieron cargar las categorías.</div>';
            });
    }

    document.addEventListener('DOMContentLoaded', () => {
        const tipoContenido = document.getElementById('tipo_contenido');
        const textoPreguntaContainer = document.getElementById('textoPreguntaContainer');
        const imagenesPreguntaContainer = document.getElementById('imagenesPreguntaContainer');
        const contenedorImagenes = document.getElementById('contenedorImagenes');
        const agregarImagenBtn = document.getElementById('agregarImagen');
        const tipoPregunta = document.getElementById('tipo');
        const contenedorOpciones = document.getElementById('contenedorOpciones');
        const agregarOpcionBtn = document.getElementById('agregarOpcion');

        let contadorOpciones = 0; // Se resetea al abrir el modal

        // Mostrar u ocultar input imágenes
        tipoContenido.addEventListener('change', () => {
            const isIlustracion = tipoContenido.value === 'ilustracion';
            imagenesPreguntaContainer.classList.toggle('d-none', !isIlustracion);
            // El texto es obligatorio, así que siempre está visible, pero se limpia si cambia a ilustración
            // if (isIlustracion) {
            //     document.getElementById('texto').value = '';
            // }
            // textoPreguntaContainer.classList.remove('d-none'); // El texto siempre visible
        });

        // Crear inputs de imágenes
        agregarImagenBtn.addEventListener('click', () => {
            const div = document.createElement('div');
            div.className = 'input-group mb-2';
            div.innerHTML = `
                <input type="file" name="imagenes[]" class="form-control" required>
                <button type="button" class="btn btn-outline-danger btnEliminarImagen"><i class="bi bi-x-lg"></i></button>
            `;
            contenedorImagenes.appendChild(div);
        });

        contenedorImagenes.addEventListener('click', e => {
            if (e.target.closest('.btnEliminarImagen')) {
                e.target.closest('.input-group').remove();
            }
        });

        // Crear opciones (ajustado para que el ID sea único y dinámico)
        const crearOpcionHTML = (texto = '', checked = false, id = null) => {
            const index = id !== null ? id : contadorOpciones++; // Usa ID existente o nuevo contador
            const div = document.createElement('div');
            div.className = 'input-group mb-2';
            div.innerHTML = `
                <div class="input-group-text">
                    <input type="checkbox" name="opciones[${index}][es_correcta]" class="form-check-input mt-0" ${checked ? 'checked' : ''}>
                </div>
                <input type="text" name="opciones[${index}][texto]" class="form-control" placeholder="Texto de la opción" value="${escapeHtml(texto)}" required>
                <button type="button" class="btn btn-outline-danger btnEliminarOpcion"><i class="bi bi-x-lg"></i></button>
            `;
            contenedorOpciones.appendChild(div);
        };

        const cargarVF = (esCorrecta = '') => {
            contenedorOpciones.innerHTML = '';
            contenedorOpciones.innerHTML = `
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="es_correcta_vf" id="vf_verdadero" value="verdadero" ${esCorrecta === 'verdadero' ? 'checked' : ''} required>
                    <label class="form-check-label" for="vf_verdadero">Verdadero</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="es_correcta_vf" id="vf_falso" value="falso" ${esCorrecta === 'falso' ? 'checked' : ''} required>
                    <label class="form-check-label" for="vf_falso">Falso</label>
                </div>
            `;
            agregarOpcionBtn.classList.add('d-none');
        };

        tipoPregunta.addEventListener('change', () => {
            contenedorOpciones.innerHTML = '';
            agregarOpcionBtn.classList.remove('d-none');
            contadorOpciones = 0; // Resetear el contador al cambiar el tipo de pregunta
            if (tipoPregunta.value === 'vf') {
                cargarVF();
            } else {
                for (let i = 0; i < 2; i++) crearOpcionHTML();
            }
        });

        agregarOpcionBtn.addEventListener('click', () => crearOpcionHTML());

        contenedorOpciones.addEventListener('click', e => {
            if (e.target.closest('.btnEliminarOpcion')) {
                e.target.closest('.input-group').remove();
            }
        });

        // Reiniciar formulario al abrir (ya manejado en abrirModalRegistro)
        // const modal = document.getElementById('modalPregunta');
        // modal.addEventListener('show.bs.modal', () => { ... });
    });

    function eliminarPregunta(id) {
        mostrarConfirmacionToast('¿Estás seguro de que deseas eliminar esta pregunta?',
            async () => {
                const formData = new FormData();
                formData.append('id', id);

                try {
                    const res = await fetch('../api/eliminar_pregunta.php', {
                        method: 'POST',
                        body: formData
                    });
                    const data = await res.json();
                    if (data.status) {
                        mostrarToast('success', data.message);
                        fetchAndStoreAllQuestions(); // ¡Volver a cargar TODAS las preguntas!
                    } else {
                        mostrarToast('warning', data.message);
                    }
                } catch (err) {
                    console.error('Error en la solicitud:', err);
                    mostrarToast('danger', 'Error al eliminar la pregunta');
                }
            });
    }

    function cambiarEstadoPregunta(idPregunta, nuevoEstado) {
        mostrarConfirmacionToast(
            `¿Estás seguro de que deseas ${nuevoEstado ? 'activar' : 'desactivar'} esta pregunta?`,
            async () => {
                const formData = new FormData();
                formData.append('id', idPregunta);
                formData.append('estado', nuevoEstado ? 1 : 0);

                try {
                    const res = await fetch('../api/cambiar_estado_pregunta.php', {
                        method: 'POST',
                        body: formData
                    });
                    const data = await res.json();
                    if (data.status) {
                        mostrarToast('success', data.message);
                        fetchAndStoreAllQuestions(); // ¡Volver a cargar TODAS las preguntas!
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

    let idPreguntaActual = 0;

    function abrirModalCategorias(preguntaId) {
        idPreguntaActual = preguntaId;
        cargarCategoriasPregunta(preguntaId);
        const modal = new bootstrap.Modal(document.getElementById('modalCategoriasPregunta'));
        modal.show();
    }

    function cargarCategoriasPregunta(preguntaId) {
        fetch(`../api/categorias_pregunta.php?id=${preguntaId}`)
            .then(res => res.json())
            .then(data => {
                const contenedor = document.getElementById('tablaCategoriasPregunta');
                if (!data.status) {
                    contenedor.innerHTML = `<div class="alert alert-danger d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        ${escapeHtml(data.message)}
                    </div>`;
                    return;
                }

                if (data.data.length === 0) {
                    contenedor.innerHTML = `<div class="alert alert-secondary d-flex align-items-center" role="alert">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        Esta pregunta no tiene categorías asignadas.
                    </div>`;
                    return;
                }

                let html = `
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th scope="col"><i class="bi bi-hash"></i> ID</th>
                                <th scope="col"><i class="bi bi-tag-fill"></i> Nombre</th>
                                <th scope="col" class="text-end"><i class="bi bi-gear-fill"></i> Acciones</th>
                            </tr>
                        </thead>
                        <tbody>`;

                data.data.forEach(cat => {
                    html += `
                        <tr>
                            <td>${cat.id}</td>
                            <td>${escapeHtml(cat.nombre)}</td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1"
                                    onclick="eliminarCategoriaPregunta(${cat.rel_id})">
                                    <i class="bi bi-trash-fill"></i> Eliminar
                                </button>
                            </td>
                        </tr>`;
                });

                html += `</tbody></table>`;
                contenedor.innerHTML = html;
            })
            .catch(err => {
                console.error('Error en cargarCategoriasPregunta:', err);
                const contenedor = document.getElementById('tablaCategoriasPregunta');
                 contenedor.innerHTML = `<div class="alert alert-danger d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Error al cargar las categorías asignadas.
                    </div>`;
            });
    }

    function mostrarSelectorNuevaCategoria() {
        const contenedor = document.getElementById('contenedorNuevaCategoria');
        contenedor.classList.remove('d-none');

        fetch('../api/obtener_categorias.php')
            .then(res => res.json())
            .then(data => {
                const select = document.getElementById('selectNuevaCategoria');
                select.innerHTML = '<option value="">Seleccione una categoría</option>';
                if (data.status && data.data.length > 0) {
                    data.data.forEach(cat => {
                        select.innerHTML += `<option value="${cat.id}">${escapeHtml(cat.nombre)}</option>`;
                    });
                } else {
                    // Si no hay categorías, puedes deshabilitar el botón de asignar o mostrar un mensaje
                    select.innerHTML = '<option value="">No hay categorías para asignar</option>';
                    select.disabled = true;
                    // Opcionalmente, puedes ocultar el botón de asignar si no hay categorías
                    // document.querySelector('#contenedorNuevaCategoria .btn-primary').disabled = true;
                }
            })
            .catch(err => {
                console.error('Error al cargar selector de categorías:', err);
                const select = document.getElementById('selectNuevaCategoria');
                select.innerHTML = '<option value="">Error al cargar categorías</option>';
                select.disabled = true;
            });
    }

    function asignarNuevaCategoria() {
        const categoriaId = document.getElementById('selectNuevaCategoria').value;
        if (!categoriaId) {
            mostrarToast('warning', 'Selecciona una categoría válida para asignar.');
            return;
        }

        const formData = new FormData();
        formData.append('accion', 'asignar');
        formData.append('pregunta_id', idPreguntaActual);
        formData.append('categoria_id', categoriaId);

        fetch('../api/categorias_pregunta.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status) {
                    mostrarToast('success', data.message);
                    cargarCategoriasPregunta(idPreguntaActual); // Recargar las categorías de la pregunta actual
                    document.getElementById('contenedorNuevaCategoria').classList.add('d-none'); // Ocultar el selector
                } else {
                    mostrarToast('warning', data.message || 'Error al asignar categoría');
                }
            })
            .catch(err => {
                console.error('Error en asignarNuevaCategoria:', err);
                mostrarToast('danger', 'Error de red al asignar la categoría.');
            });
    }


    function eliminarCategoriaPregunta(rel_id) {
        mostrarConfirmacionToast('¿Estás seguro de que deseas eliminar esta categoría de la pregunta?', () => {
            const formData = new FormData();
            formData.append('accion', 'eliminar');
            formData.append('rel_id', rel_id);

            fetch('../api/categorias_pregunta.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status) {
                        mostrarToast('success', data.message);
                        cargarCategoriasPregunta(idPreguntaActual); // Recargar las categorías de la pregunta actual
                    } else {
                        mostrarToast('warning', data.message || 'Error al eliminar');
                    }
                })
                .catch(err => {
                    console.error('Error en eliminarCategoriaPregunta:', err);
                    mostrarToast('danger', 'Error de red al eliminar la categoría.');
                });
        });
    }


    // Funciones de Toast y Confirmación (mantén las tuyas o usa estas básicas)
    function mostrarToast(type, message) {
        console.log(`Toast (${type}): ${message}`);
        // Implementación de toast (ej. con Bootstrap Toast)
        // Necesitas tener el HTML para el toast en tu página
        // Ejemplo de uso básico con Bootstrap Toast:
        const toastEl = document.getElementById('liveToast'); // Asegúrate de tener un Toast con este ID
        if (toastEl) {
            const toastBody = toastEl.querySelector('.toast-body');
            if (toastBody) {
                toastBody.textContent = message;
                toastEl.classList.remove('bg-success', 'bg-danger', 'bg-warning');
                toastEl.classList.add(`bg-${type}`, 'text-white');
                const toast = new bootstrap.Toast(toastEl);
                toast.show();
            }
        }
    }

    function mostrarConfirmacionToast(message, onConfirm) {
        if (confirm(message)) {
            onConfirm();
        }
    }
</script>

<?php include_once('../includes/footer.php'); ?>