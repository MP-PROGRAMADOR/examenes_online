<?php
// Suponiendo que $pdo ya está inicializado y es accesible (ej. en config.php o similar)
require_once '../includes/conexion.php';

// Incluye el archivo de cabecera


// Definir variables de paginación y límite por defecto
$limite = isset($_GET['limite']) && in_array((int) $_GET['limite'], [5, 10, 15, 20, 25]) ? (int) $_GET['limite'] : 10;
$pagina = isset($_GET['pagina']) && is_numeric($_GET['pagina']) && $_GET['pagina'] > 0 ? (int) $_GET['pagina'] : 1;

// Contar total de exámenes para paginación
$countSql = "SELECT COUNT(*) FROM examenes";
$countStmt = $pdo->prepare($countSql);
$countStmt->execute();
$total_examenes = $countStmt->fetchColumn();
$total_paginas = ceil($total_examenes / $limite);

// Calcular offset para la consulta con límite y paginación
$offset = ($pagina - 1) * $limite;

$sql = "SELECT 
            ex.id,  
            CONCAT(est.nombre, ' ', est.apellidos) AS estudiante, 
            est.id AS estudiante_id, -- Necesitamos el ID del estudiante para el modal de edición
            cat.nombre AS categoria,
            cat.id AS categoria_id, -- Necesitamos el ID de la categoría para el modal de edición
            us.nombre AS usuario, 
            ex.fecha_asignacion, 
            ex.total_preguntas,
            ex.estado, 
            ex.calificacion, 
            ex.codigo_acceso
        FROM examenes ex
        JOIN estudiantes est ON ex.estudiante_id = est.id
        JOIN categorias cat ON ex.categoria_id = cat.id
        LEFT JOIN usuarios us ON ex.asignado_por = us.id
        ORDER BY ex.fecha_asignacion DESC
        LIMIT :limite OFFSET :offset";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$examenes = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once 'header.php';
?>

<span class="mt-5"></span>
<span class="mt-5"></span>
<main class="main-content" id="content">
    <div class="card shadow-sm mb-4">
        <div
            class="card-header bg-primary text-white d-flex flex-wrap align-items-center justify-content-between gap-3 p-3 rounded-top">
            <h5 class="mb-0 d-flex align-items-center">
                <i class="bi bi-file-earmark-text-fill me-2"></i>Gestión de Exámenes
            </h5>

            <div class="d-flex align-items-center gap-3 flex-grow-1 flex-wrap justify-content-end">

                <div class="position-relative">
                    <input type="text" class="form-control ps-5 form-control-sm shadow-sm" id="customSearch"
                        placeholder="Buscar examen...">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <label for="container-length" class="mb-0 fw-semibold text-white">Mostrar:</label>
                    <select id="container-length" class="form-select form-select-sm w-auto shadow-sm">
                        <?php foreach ([5, 10, 15, 20, 25] as $op): ?>
                            <option value="<?= $op ?>" <?= $limite == $op ? 'selected' : '' ?>><?= $op ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <?php
                if (isset($_SESSION['error'])) {
                    echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['error']) . '</div>';
                    unset($_SESSION['error']);
                }
                if (isset($_SESSION['success'])) {
                    echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['success']) . '</div>';
                    unset($_SESSION['success']);
                }
                ?>
                <table class="table table-striped table-bordered align-middle mb-0" id="examenesTable">
                    <thead class="table-primary text-center">
                        <tr>
                            <th><i class="bi bi-hash me-1"></i> ID</th>
                            <th><i class="bi bi-person-fill me-1"></i> Estudiante</th>
                            <th><i class="bi bi-tags-fill me-1"></i> Categoría</th>
                            <th><i class="bi bi-person-badge-fill me-1"></i> Asignado Por</th>
                            <th><i class="bi bi-calendar-event-fill me-1"></i> Fecha</th>
                            <th><i class="bi bi-list-ol me-1"></i> Preguntas</th>
                            <th><i class="bi bi-toggle-on me-1"></i> Estado</th>
                            <th><i class="bi bi-clipboard-check-fill me-1"></i> Calificación</th>
                            <th><i class="bi bi-key-fill me-1"></i> Código</th>
                            <th><i class="bi bi-gear-fill me-1"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($examenes)): ?>
                            <?php foreach ($examenes as $examen): ?>
                                <tr>
                                    <td class="text-center"><?= htmlspecialchars($examen['id']) ?></td>
                                    <td><?= htmlspecialchars($examen['estudiante']) ?></td>
                                    <td><?= htmlspecialchars($examen['categoria']) ?></td>
                                    <td><?= htmlspecialchars($examen['usuario'] ?? '—') ?></td>
                                    <td><?= htmlspecialchars($examen['fecha_asignacion']) ?></td>
                                    <td class="text-center"><?= htmlspecialchars($examen['total_preguntas']) ?></td>
                                    <td class="text-center">
                                        <span
                                            class="badge bg-<?= $examen['estado'] === 'pendiente' ? 'warning' : ($examen['estado'] === 'en_progreso' ? 'primary' : 'success') ?>">
                                            <?= strtoupper(str_replace('_', ' ', $examen['estado'])) ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <?= $examen['calificacion'] !== null ? htmlspecialchars($examen['calificacion']) : '—' ?>
                                    </td>
                                    <td class="text-center"><code><?= htmlspecialchars($examen['codigo_acceso']) ?></code></td>
                                    <td class="text-center">
                                        <a href="../libreria/imprimir_codigo.php?id=<?= $examen['id'] ?>"
                                            class="btn btn-sm btn-outline-secondary mb-1">
                                            <i class="bi bi-printer-fill me-1"></i> Imprimir Código
                                        </a>
                                        <?php if ($examen['calificacion'] !== null && $examen['calificacion'] > 0): ?>
                                            <button class="btn btn-sm btn-outline-warning mb-1"
                                                onclick="imprimirExamen(<?= $examen['id'] ?>)">
                                                <i class="bi bi-printer-fill me-1"></i> Imprimir Examen
                                            </button>
                                        <?php endif; ?>

                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr id="no-results-row">
                                <td colspan="10">
                                    <div class="alert alert-warning text-center m-0 rounded-0">
                                        <i class="bi bi-exclamation-circle-fill me-2"></i>No hay exámenes registrados
                                        actualmente.
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($total_paginas > 1): ?>
                <nav aria-label="Paginación de exámenes" class="my-3">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?= $pagina <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="?pagina=<?= $pagina - 1 ?>&limite=<?= $limite ?>">Anterior</a>
                        </li>
                        <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                            <li class="page-item <?= $pagina == $i ? 'active' : '' ?>">
                                <a class="page-link" href="?pagina=<?= $i ?>&limite=<?= $limite ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?= $pagina >= $total_paginas ? 'disabled' : '' ?>">
                            <a class="page-link" href="?pagina=<?= $pagina + 1 ?>&limite=<?= $limite ?>">Siguiente</a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</main>

<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="liveToast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive"
        aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                aria-label="Close"></button>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/alerta.js"></script>
<script>
    let estudiantesData = []; // Para almacenar todos los estudiantes
    let categoriaData = {}; // Para almacenar las preguntas disponibles por categoría

    // Función para mostrar Toast (mensajes de alerta)
    function mostrarToast(type, message) {
        const toastLiveExample = document.getElementById('liveToast');
        const toastBody = toastLiveExample.querySelector('.toast-body');
        toastBody.textContent = message;

        toastLiveExample.classList.remove('bg-success', 'bg-danger', 'bg-warning', 'bg-info');
        if (type === 'success') {
            toastLiveExample.classList.add('bg-success');
        } else if (type === 'error') {
            toastLiveExample.classList.add('bg-danger');
        } else if (type === 'warning') {
            toastLiveExample.classList.add('bg-warning');
        } else if (type === 'info') {
            toastLiveExample.classList.add('bg-info');
        }

        const toast = new bootstrap.Toast(toastLiveExample);
        toast.show();
    }

    // --- Funcionalidad de filtrado de tabla (Vanilla JS) ---
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('customSearch');
        const tableBody = document.querySelector('#examenesTable tbody');
        const noResultsRow = document.getElementById('no-results-row'); // Asegúrate de que esta fila exista en tu HTML inicial si no hay datos

        function filterTable() {
            const searchText = searchInput.value.toLowerCase();
            let rowCount = 0;

            Array.from(tableBody.rows).forEach(row => {
                // Ignorar la fila de "No hay exámenes" si está presente
                if (row.id === 'no-results-row-dynamic') { // ID para la fila dinámica, para evitar colisiones
                    row.style.display = 'none'; // Asegúrate de ocultarla si ya existe
                    return;
                }

                const rowText = row.textContent.toLowerCase();
                if (rowText.includes(searchText)) {
                    row.style.display = ''; // Muestra la fila
                    rowCount++;
                } else {
                    row.style.display = 'none'; // Oculta la fila
                }
            });

            // Manejo de "No se encontraron resultados"
            let dynamicNoResultsRow = document.getElementById('no-results-row-dynamic');
            if (rowCount === 0) {
                if (!dynamicNoResultsRow) {
                    dynamicNoResultsRow = tableBody.insertRow();
                    dynamicNoResultsRow.id = 'no-results-row-dynamic';
                    const cell = dynamicNoResultsRow.insertCell(0);
                    cell.colSpan = 10; // Ajusta según el número de columnas de tu tabla
                    cell.innerHTML = `
                        <div class="alert alert-info text-center m-0 rounded-0">
                            <i class="bi bi-info-circle-fill me-2"></i>No se encontraron resultados.
                        </div>
                    `;
                }
                dynamicNoResultsRow.style.display = '';
            } else {
                if (dynamicNoResultsRow) {
                    dynamicNoResultsRow.style.display = 'none'; // Oculta si hay resultados
                }
            }
        }

        searchInput.addEventListener('input', filterTable);

        // --- Redirige al cambiar la cantidad de elementos a mostrar ---
        const containerLengthSelect = document.getElementById('container-length');
        containerLengthSelect.addEventListener('change', function () {
            const selectedLimit = this.value;
            window.location.href = `?pagina=1&limite=${selectedLimit}`;
        });

        // Asegúrate de que la fila de "no-results" original no interfiera con la dinámica
        if (noResultsRow) {
            filterTable(); // Llama al filtro inicial al cargar la página para manejar la visibilidad
        }
    });

    // Simular la impresión del examen (puedes ajustar la URL real)
    window.imprimirExamen = function (examenId) {
        // Aquí puedes redirigir a una página para generar el PDF del examen
        window.open(`../libreria/imprimir_detalles_examen.php?id=${examenId}`, '_blank');
    };

   
</script>
</body>

</html>