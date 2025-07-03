<?php
include_once("../includes/header.php");
include_once("../includes/sidebar.php");
?>

<main class="main-content" id="content">
    <div class="card shadow border-0 rounded-4">
        <div class="content-wrapper">
            <section class="content">
                <div class="card">
                    <div class="card-header bg-primary">
                        <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
                            <h3 class="card-title text-white"><i class="bi bi-clipboard-check"></i>
                                Listado de Estudiantes con Exámenes Realizados</h3>
                            <div class="d-flex align-items-center ms-auto w-30">
                                <label for="customSearch" class="me-2 text-white fw-medium mb-0">Buscar:</label>
                                <input type="text" id="customSearch" class="form-control form-control-sm shadow-sm"
                                    placeholder="Buscar estudiante...">
                            </div>
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
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>DNI</th>
                                        <th>Nombre Completo</th>
                                        <th>Email</th>
                                        <th>Teléfono</th>
                                        <th>Última Categoría</th>
                                        <th>Fecha Últ. Examen</th>
                                        <th>Calificación Últ. Examen</th>
                                        <th>Estado Últ. Examen</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="estudiantes-examenes-table-body">
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap">
                            <div id="pagination-info" class="text-white"></div>
                            <nav>
                                <ul class="pagination mb-0" id="pagination-controls">
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</main>
<div class="modal fade" id="modalVerExamen" tabindex="-1" aria-labelledby="modalVerExamenLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalVerExamenLabel">Detalle del Examen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="examen-detalle-header" class="mb-4">
                </div>
                <div id="examen-preguntas-container">
                </div>
                <div class="d-flex justify-content-end mt-3">
                    <p class="mb-0"><strong>Calificación Final: </strong> <span id="calificacion-final"></span></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>

<script>
    let currentPage = 1;
    let recordsPerPage = parseInt(document.getElementById('container-length').value);
    let searchTerm = '';
    let totalPages = 1;

    document.addEventListener('DOMContentLoaded', function () {
        fetchEstudiantesConExamenes();

        const customSearchInput = document.getElementById('customSearch');
        let searchTimeout;
        customSearchInput.addEventListener('keyup', function () {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                searchTerm = this.value;
                currentPage = 1;
                fetchEstudiantesConExamenes();
            }, 300);
        });

        document.getElementById('container-length').addEventListener('change', function () {
            recordsPerPage = parseInt(this.value);
            currentPage = 1;
            fetchEstudiantesConExamenes();
        });
    });

    async function fetchEstudiantesConExamenes() {
        const tableBody = document.getElementById('estudiantes-examenes-table-body');
        const paginationInfo = document.getElementById('pagination-info');
        const paginationControls = document.getElementById('pagination-controls');

        tableBody.innerHTML = `<tr><td colspan="10" class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-2">Cargando estudiantes...</p>
        </td></tr>`;
        paginationInfo.textContent = '';
        paginationControls.innerHTML = '';

        try {
            const response = await fetch(`../api/obtener_estudiantes_con_examenes.php?page=${currentPage}&limit=${recordsPerPage}&search=${encodeURIComponent(searchTerm)}`);
            const data = await response.json();

            if (data.status) {
                totalPages = data.totalPages;
                renderEstudiantesTable(data.estudiantes);
                renderPagination(data.currentPage, data.totalPages, data.totalRecords, data.perPage);
            } else {
                tableBody.innerHTML = `<tr><td colspan="10"><div class="alert alert-danger text-center mt-3 mb-3">${data.message || 'Error al cargar los datos de los estudiantes.'}</div></td></tr>`;
                paginationInfo.textContent = '';
                paginationControls.innerHTML = '';
            }
        } catch (error) {
            console.error('Error al obtener estudiantes con exámenes:', error);
            tableBody.innerHTML = `<tr><td colspan="10"><div class="alert alert-danger text-center mt-3 mb-3">Error de conexión al servidor. Inténtelo de nuevo.</div></td></tr>`;
            paginationInfo.textContent = '';
            paginationControls.innerHTML = '';
        }
    }

    function renderEstudiantesTable(estudiantes) {
        const tableBody = document.getElementById('estudiantes-examenes-table-body');
        tableBody.innerHTML = '';

        if (estudiantes.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="10"><div class="alert alert-warning text-center mt-3 mb-3">
                <i class="bi bi-exclamation-circle-fill me-2"></i>⚠️ No se encontraron estudiantes con exámenes realizados.
            </div></td></tr>`;
            return;
        }

        estudiantes.forEach(estudiante => {
            const row = `
                <tr>
                    <td>${estudiante.estudiante_id}</td>
                    <td>${escapeHTML(estudiante.dni)}</td>
                    <td>${escapeHTML(estudiante.estudiante_nombre)} ${escapeHTML(estudiante.apellidos)}</td>
                    <td>${escapeHTML(estudiante.email || '')}</td>
                    <td>${escapeHTML(estudiante.telefono || '')}</td>
                    <td>${escapeHTML(estudiante.ultima_categoria_examen || 'N/A')}</td>
                    <td>${estudiante.ultima_fecha_examen ? new Date(estudiante.ultima_fecha_examen).toLocaleString() : 'N/A'}</td>
                    <td>${estudiante.ultima_calificacion_examen !== null ? parseFloat(estudiante.ultima_calificacion_examen).toFixed(2) : 'N/A'}</td>
                    <td>${escapeHTML(estudiante.ultimo_estado_examen || 'N/A')}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-info" onclick="abrirModalVerExamen(${estudiante.ultimo_examen_id})">
                            <i class="bi bi-eye me-1"></i> Ver Examen
                        </button>
                    </td>
                </tr>
            `;
            tableBody.insertAdjacentHTML('beforeend', row);
        });
    }

    // Funciones de paginación (copiar de escuelas.php)
    function renderPagination(currPage, totalPages, totalRecords, perPage) {
        const paginationInfo = document.getElementById('pagination-info');
        const paginationControls = document.getElementById('pagination-controls');
        paginationControls.innerHTML = '';

        if (totalRecords === 0) {
            paginationInfo.textContent = 'Mostrando 0 de 0 registros';
            return;
        }

        const startRecord = (currPage - 1) * perPage + 1;
        const endRecord = Math.min(currPage * perPage, totalRecords);
        paginationInfo.textContent = `Mostrando ${startRecord} a ${endRecord} de ${totalRecords} registros`;

        const prevLi = document.createElement('li');
        prevLi.className = `page-item ${currPage === 1 ? 'disabled' : ''}`;
        prevLi.innerHTML = `<a class="page-link" href="#" aria-label="Previous" onclick="changePage(${currPage - 1})"><span aria-hidden="true">&laquo;</span></a>`;
        paginationControls.appendChild(prevLi);

        const maxPagesToShow = 5;
        let startPage = Math.max(1, currPage - Math.floor(maxPagesToShow / 2));
        let endPage = Math.min(totalPages, startPage + maxPagesToShow - 1);

        if (endPage - startPage + 1 < maxPagesToShow) {
            startPage = Math.max(1, totalPages - maxPagesToShow + 1);
        }

        for (let i = startPage; i <= endPage; i++) {
            const li = document.createElement('li');
            li.className = `page-item ${i === currPage ? 'active' : ''}`;
            li.innerHTML = `<a class="page-link" href="#" onclick="changePage(${i})">${i}</a>`;
            paginationControls.appendChild(li);
        }

        const nextLi = document.createElement('li');
        nextLi.className = `page-item ${currPage === totalPages ? 'disabled' : ''}`;
        nextLi.innerHTML = `<a class="page-link" href="#" aria-label="Next" onclick="changePage(${currPage + 1})"><span aria-hidden="true">&raquo;</span></a>`;
        paginationControls.appendChild(nextLi);
    }

    function changePage(page) {
        if (page >= 1 && page <= totalPages) {
            currentPage = page;
            fetchEstudiantesConExamenes();
        }
    }

    // --- Lógica del Modal de Detalle de Examen ---
    async function abrirModalVerExamen(examenId) {
        const modalVerExamen = new bootstrap.Modal(document.getElementById('modalVerExamen'));
        const examenDetalleHeader = document.getElementById('examen-detalle-header');
        const examenPreguntasContainer = document.getElementById('examen-preguntas-container');
        const calificacionFinalSpan = document.getElementById('calificacion-final');

        // Limpiar contenido previo y mostrar carga
        examenDetalleHeader.innerHTML = `
            <div class="d-flex justify-content-center align-items-center" style="min-height: 100px;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="ms-2 mb-0">Cargando detalles del examen...</p>
            </div>
        `;
        examenPreguntasContainer.innerHTML = '';
        calificacionFinalSpan.textContent = '';

        modalVerExamen.show();

        try {
            const response = await fetch(`../api/obtener_detalle_examen.php?examen_id=${examenId}`);
            const data = await response.json();

            if (data.status) {
                const examen = data.examen;
                document.getElementById('modalVerExamenLabel').textContent = `Detalle del Examen: ${escapeHTML(examen.estudiante_nombre)} ${escapeHTML(examen.estudiante_apellidos)}`;

                // Renderizar el encabezado del examen
                examenDetalleHeader.innerHTML = `
                    <p><strong>Estudiante:</strong> ${escapeHTML(examen.estudiante_nombre)} ${escapeHTML(examen.estudiante_apellidos)} (${escapeHTML(examen.estudiante_dni)})</p>
                    <p><strong>Categoría:</strong> ${escapeHTML(examen.categoria_nombre)}</p>
                    <p><strong>Fecha de Asignación:</strong> ${new Date(examen.fecha_asignacion).toLocaleString()}</p>
                    <p><strong>Código de Acceso:</strong> ${escapeHTML(examen.codigo_acceso)}</p>
                    <p><strong>Estado:</strong> ${escapeHTML(examen.estado)}</p>
                    ${examen.asignado_por_nombre ? `<p><strong>Asignado por:</strong> ${escapeHTML(examen.asignado_por_nombre)}</p>` : ''}
                `;

                // Renderizar las preguntas
                renderExamenPreguntas(examen.preguntas, examenPreguntasContainer);

                // Mostrar calificación final
                calificacionFinalSpan.textContent = examen.calificacion !== null ? parseFloat(examen.calificacion).toFixed(2) : 'N/A';

            } else {
                examenDetalleHeader.innerHTML = `<div class="alert alert-danger">${data.message || 'Error al cargar el detalle del examen.'}</div>`;
                examenPreguntasContainer.innerHTML = '';
            }
        } catch (error) {
            console.error('Error al obtener detalle del examen:', error);
            examenDetalleHeader.innerHTML = `<div class="alert alert-danger">Error de conexión al servidor al cargar el examen.</div>`;
            examenPreguntasContainer.innerHTML = '';
        }
    }

    function renderExamenPreguntas(preguntas, containerElement) {
        containerElement.innerHTML = ''; // Limpiar el contenedor

        if (preguntas.length === 0) {
            containerElement.innerHTML = '<div class="alert alert-info">Este examen no contiene preguntas.</div>';
            return;
        }

        preguntas.forEach((pregunta, index) => {
            const preguntaCard = document.createElement('div');
            preguntaCard.classList.add('card', 'mb-3');

            // Determinar si la pregunta fue respondida correctamente o incorrectamente
            const cardBorderClass = pregunta.acierto ? 'border-success' : 'border-danger';
            const aciertoIcon = pregunta.acierto ? '<i class="bi bi-check-circle-fill text-success me-2"></i>Acierto' : '<i class="bi bi-x-circle-fill text-danger me-2"></i>Error';

            preguntaCard.innerHTML = `
                <div class="card-header d-flex justify-content-between align-items-center ${cardBorderClass}">
                    <h6 class="mb-0">Pregunta ${index + 1}: <span class="badge bg-secondary">${escapeHTML(pregunta.tipo.charAt(0).toUpperCase() + pregunta.tipo.slice(1))}</span></h6>
                    <div>${aciertoIcon}</div>
                </div>
                <div class="card-body">
                    <p class="card-text"><strong>${escapeHTML(pregunta.texto)}</strong></p>
                    ${pregunta.imagen_ruta ? `<img src="../api/${escapeHTML(pregunta.imagen_ruta)}" class="img-fluid mb-3" alt="Imagen de pregunta" style="max-height: 200px;">` : ''}
                    <ul class="list-group">
                        ${pregunta.opciones.map(opcion => {
                let itemClass = 'list-group-item';
                let icon = '';
                let selectedBadge = '';

                // Si el estudiante seleccionó esta opción
                if (pregunta.respuestas_estudiante_ids.includes(opcion.id)) {
                    selectedBadge = '<span class="badge bg-primary ms-2">Tu respuesta</span>';
                    if (opcion.es_correcta) {
                        itemClass += ' list-group-item-success'; // Respuesta correcta seleccionada
                        icon = '<i class="bi bi-check-circle-fill text-success me-2"></i>';
                    } else {
                        itemClass += ' list-group-item-danger'; // Respuesta incorrecta seleccionada
                        icon = '<i class="bi bi-x-circle-fill text-danger me-2"></i>';
                    }
                } else if (opcion.es_correcta) {
                    // Si no fue seleccionada por el estudiante pero era correcta
                    itemClass += ' list-group-item-info'; // Opción correcta no seleccionada
                    icon = '<i class="bi bi-star-fill text-info me-2"></i>'; // Indicador de la respuesta correcta
                }

                return `
                                <li class="${itemClass}">
                                    ${icon} ${escapeHTML(opcion.texto)} ${selectedBadge}
                                </li>
                            `;
            }).join('')}
                    </ul>
                </div>
            `;
            containerElement.appendChild(preguntaCard);
        });
    }

    // Función de utilidad para escapar HTML (prevención XSS)
    function escapeHTML(str) {
        const div = document.createElement('div');
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    }

    // Inicialización de Bootstrap Validation (si aplica para otros formularios en esta página)
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