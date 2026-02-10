<?php
include_once("../includes/header.php");
include_once("../includes/sidebar.php");
require_once '../includes/conexion.php'; 

// Lógica de expiración automática al cargar
$sqlExpirar = "UPDATE examenes 
               SET estado = 'EXPIRADO' 
               WHERE fecha_asignacion < CURRENT_DATE() 
               AND estado NOT IN ('finalizado', 'EXPIRADO')";
$pdo->exec($sqlExpirar);
?>

<main class="main-content" id="content">
    <div class="card shadow border-0 rounded-4">
        <div class="card-header bg-primary text-white p-3">
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <h3 class="card-title mb-0"><i class="bi bi-clipboard-check"></i> Listado General de Exámenes</h3>
                <div class="ms-auto d-flex gap-2">
                    <input type="text" id="customSearch" class="form-control form-control-sm shadow-sm" placeholder="Buscar por DNI, Nombre o Código...">
                    <select id="container-length" class="form-select form-select-sm w-auto shadow-sm">
                        <option value="10" selected>10 registros</option>
                        <option value="25">25 registros</option>
                        <option value="50">50 registros</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Código Acceso</th>
                            <th>DNI</th>
                            <th>Estudiante</th>
                            <th>Categoría</th>
                            <th>Fecha</th>
                            <th class="text-center">Nota</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="estudiantes-examenes-table-body"></tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div id="pagination-info" class="text-muted fw-bold small"></div>
                <nav><ul class="pagination pagination-sm mb-0" id="pagination-controls"></ul></nav>
            </div>
        </div>
    </div>
</main>

<div class="modal fade" id="modalVerExamen" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalle del Examen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="examen-detalle-header" class="mb-4"></div>
                <div id="examen-preguntas-container"></div>
                <div class="d-flex justify-content-end mt-3">
                    <p class="mb-0"><strong>Calificación Final: </strong> <span id="calificacion-final" class="badge bg-primary fs-6"></span></p>
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
    let recordsPerPage = 10;
    let searchTerm = '';
    let totalPages = 1;

    document.addEventListener('DOMContentLoaded', () => {
        fetchEstudiantesConExamenes();
        
        document.getElementById('customSearch').addEventListener('input', (e) => {
            searchTerm = e.target.value;
            currentPage = 1;
            fetchEstudiantesConExamenes();
        });

        document.getElementById('container-length').addEventListener('change', (e) => {
            recordsPerPage = e.target.value;
            currentPage = 1;
            fetchEstudiantesConExamenes();
        });
    });

    // Función PDF
    window.imprimirExamen = (id) => window.open(`../libreria/imprimir_detalles_examen.php?id=${id}`, '_blank');

    async function fetchEstudiantesConExamenes() {
        const tableBody = document.getElementById('estudiantes-examenes-table-body');
        tableBody.innerHTML = '<tr><td colspan="9" class="text-center py-4"><div class="spinner-border text-primary"></div></td></tr>';

        try {
            const resp = await fetch(`../api/obtener_estudiantes_con_examenes.php?page=${currentPage}&limit=${recordsPerPage}&search=${encodeURIComponent(searchTerm)}`);
            const data = await resp.json();
            
            if (data.status) {
                totalPages = data.totalPages;
                renderTable(data.estudiantes);
                renderPagination(data.currentPage, data.totalPages, data.totalRecords, data.perPage);
            }
        } catch (e) { 
            tableBody.innerHTML = '<tr><td colspan="9" class="text-center text-danger">Error de carga</td></tr>';
        }
    }

    function renderTable(data) {
        const tableBody = document.getElementById('estudiantes-examenes-table-body');
        tableBody.innerHTML = '';

        if (data.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="9" class="text-center">Sin resultados</td></tr>';
            return;
        }

        data.forEach(est => {
            const estado = (est.ultimo_estado_examen || 'pendiente').toUpperCase();
            let badgeColor = '#6c757d'; let txtColor = 'white';

            switch (estado) {
                case 'FINALIZADO': badgeColor = '#198754'; break;
                case 'EXPIRADO':   badgeColor = '#dc3545'; break;
                case 'PENDIENTE':  badgeColor = '#ffc107'; txtColor = 'black'; break;
                case 'INICIO':
                case 'PROCESO':    badgeColor = '#0dcaf0'; txtColor = 'black'; break;
            }

            const nota = est.ultima_calificacion_examen !== null ? parseFloat(est.ultima_calificacion_examen).toFixed(2) : '--';
            const notaClase = est.ultima_calificacion_examen >= 50 ? 'text-success' : 'text-danger';

            tableBody.innerHTML += `
                <tr>
                    <td class="text-muted small">#${est.ultimo_examen_id}</td>
                    <td><code class="fw-bold fs-6 text-primary">${est.codigo_acceso}</code></td>
                    <td class="font-monospace small">${est.dni}</td>
                    <td><strong>${est.estudiante_nombre} ${est.apellidos}</strong></td>
                    <td><span class="badge bg-light text-dark border">${est.ultima_categoria_examen}</span></td>
                    <td class="small">${new Date(est.ultima_fecha_examen).toLocaleDateString()}</td>
                    <td class="text-center fw-bold ${notaClase}">${nota}</td>
                    <td class="text-center">
                        <span class="badge rounded-pill" style="background-color:${badgeColor}; color:${txtColor}; min-width:85px">
                            ${estado}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <button class="btn btn-sm btn-outline-primary" onclick="abrirModalVerExamen(${est.ultimo_examen_id})"><i class="bi bi-eye-fill"></i></button>
                            <button class="btn btn-sm btn-outline-danger" onclick="imprimirExamen(${est.ultimo_examen_id})"><i class="bi bi-file-earmark-pdf-fill"></i></button>
                        </div>
                    </td>
                </tr>`;
        });
    }

    async function abrirModalVerExamen(examenId) {
        const modal = new bootstrap.Modal(document.getElementById('modalVerExamen'));
        const header = document.getElementById('examen-detalle-header');
        const container = document.getElementById('examen-preguntas-container');
        header.innerHTML = '<div class="text-center"><div class="spinner-border text-primary"></div></div>';
        container.innerHTML = '';
        modal.show();

        try {
            const resp = await fetch(`../api/obtener_detalle_examen.php?examen_id=${examenId}`);
            const data = await resp.json();
            if (data.status) {
                const ex = data.examen;
                header.innerHTML = `<p><strong>Estudiante:</strong> ${ex.estudiante_nombre} ${ex.estudiante_apellidos}</p>
                                    <p><strong>Categoría:</strong> ${ex.categoria_nombre} | <strong>Estado:</strong> ${ex.estado}</p>`;
                document.getElementById('calificacion-final').textContent = ex.calificacion || '0.00';
                renderExamenPreguntas(ex.preguntas, container);
            }
        } catch (e) { header.innerHTML = 'Error al cargar.'; }
    }

    function renderExamenPreguntas(preguntas, container) {
        preguntas.forEach((p, idx) => {
            const card = document.createElement('div');
            card.className = `card mb-3 border-${p.acierto ? 'success' : 'danger'}`;
            card.innerHTML = `<div class="card-header d-flex justify-content-between">
                <span>Pregunta ${idx + 1}</span><span>${p.acierto ? '✅' : '❌'}</span>
            </div>
            <div class="card-body">
                <p><strong>${p.texto}</strong></p>
                <ul class="list-group">${p.opciones.map(opt => {
                    let cls = p.respuestas_estudiante_ids.includes(opt.id) ? (opt.es_correcta ? 'list-group-item-success' : 'list-group-item-danger') : (opt.es_correcta ? 'list-group-item-info' : '');
                    return `<li class="list-group-item ${cls}">${opt.texto} ${p.respuestas_estudiante_ids.includes(opt.id) ? '<b>(Tuya)</b>' : ''}</li>`;
                }).join('')}</ul>
            </div>`;
            container.appendChild(card);
        });
    }

    function renderPagination(curr, total, count, per) {
        const info = document.getElementById('pagination-info');
        const controls = document.getElementById('pagination-controls');
        info.textContent = `Mostrando ${(curr-1)*per+1} a ${Math.min(count, curr*per)} de ${count}`;
        controls.innerHTML = `<li class="page-item ${curr===1?'disabled':''}"><a class="page-link" href="#" onclick="changePage(${curr-1})">&laquo;</a></li>`;
        for (let i=1; i<=total; i++) {
            if (i >= curr-2 && i <= curr+2) 
                controls.innerHTML += `<li class="page-item ${i===curr?'active':''}"><a class="page-link" href="#" onclick="changePage(${i})">${i}</a></li>`;
        }
        controls.innerHTML += `<li class="page-item ${curr===total?'disabled':''}"><a class="page-link" href="#" onclick="changePage(${curr+1})">&raquo;</a></li>`;
    }

    function changePage(p) { if(p>0 && p<=totalPages) { currentPage = p; fetchEstudiantesConExamenes(); } }
</script>