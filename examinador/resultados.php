<?php
include_once("../includes/header.php");
include_once("../includes/sidebar_examinador.php");

// Definir variables de paginación y límite por defecto
$limite = isset($_GET['limite']) && in_array((int) $_GET['limite'], [5, 10, 15, 20, 25]) ? (int) $_GET['limite'] : 10;
$pagina = isset($_GET['pagina']) && is_numeric($_GET['pagina']) && $_GET['pagina'] > 0 ? (int) $_GET['pagina'] : 1;

// Contar total de exámenes
$countSql = "SELECT COUNT(*) FROM examenes";
$countStmt = $pdo->prepare($countSql);
$countStmt->execute();
$total_examenes = $countStmt->fetchColumn();
$total_paginas = ceil($total_examenes / $limite);
$offset = ($pagina - 1) * $limite;

$sql = "
SELECT 
    e.id AS examen_id,
    CONCAT(est.nombre, ' ', est.apellidos) AS estudiante,
    c.nombre AS categoria,
    e.total_preguntas,
    e.calificacion,
    e.fecha_asignacion,

    SUM(
        CASE 
            WHEN (
                (
                    SELECT COUNT(*) 
                    FROM respuestas_estudiante re2
                    JOIN opciones_pregunta op2 ON op2.id = re2.opcion_id AND op2.es_correcta = 1
                    WHERE re2.examen_pregunta_id = ep.id
                ) = 
                (
                    SELECT COUNT(*) 
                    FROM opciones_pregunta op3
                    WHERE op3.pregunta_id = ep.pregunta_id AND op3.es_correcta = 1
                )
                AND
                (
                    SELECT COUNT(*) 
                    FROM respuestas_estudiante re3
                    JOIN opciones_pregunta op4 ON op4.id = re3.opcion_id AND op4.es_correcta = 0
                    WHERE re3.examen_pregunta_id = ep.id
                ) = 0
            )
            THEN 1 ELSE 0 
        END
    ) AS aciertos,

    SUM(CASE WHEN ep.respondida = 1 THEN 1 ELSE 0 END)
    -
    SUM(
        CASE 
            WHEN (
                (
                    SELECT COUNT(*) 
                    FROM respuestas_estudiante re2
                    JOIN opciones_pregunta op2 ON op2.id = re2.opcion_id AND op2.es_correcta = 1
                    WHERE re2.examen_pregunta_id = ep.id
                ) = 
                (
                    SELECT COUNT(*) 
                    FROM opciones_pregunta op3
                    WHERE op3.pregunta_id = ep.pregunta_id AND op3.es_correcta = 1
                )
                AND
                (
                    SELECT COUNT(*) 
                    FROM respuestas_estudiante re3
                    JOIN opciones_pregunta op4 ON op4.id = re3.opcion_id AND op4.es_correcta = 0
                    WHERE re3.examen_pregunta_id = ep.id
                ) = 0
            )
            THEN 1 ELSE 0 
        END
    ) AS fallos

FROM examenes e
JOIN estudiantes est ON est.id = e.estudiante_id
JOIN categorias c ON c.id = e.categoria_id
JOIN examen_preguntas ep ON ep.examen_id = e.id

GROUP BY e.id, est.nombre, est.apellidos, c.nombre, e.total_preguntas, e.calificacion, e.fecha_asignacion
ORDER BY e.fecha_asignacion DESC
LIMIT :limite OFFSET :offset
";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$examenes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>



<div class="card shadow-sm mb-4">
    <div
        class="card-header bg-primary text-white d-flex flex-wrap align-items-center justify-content-center gap-3 p-3 rounded-top">
        <h5 class="mb-0 d-flex align-items-center">
            <i class="bi bi-file-earmark-text-fill me-2"></i>Gestión de Resultados
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

    <!-- TABLA -->
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle mb-0">
                <thead class="table-primary text-center">
                    <tr>
                        <th><i class="bi bi-hash me-1"></i> ID</th>
                        <th><i class="bi bi-person-fill me-1"></i> Estudiante</th>
                        <th><i class="bi bi-tags-fill me-1"></i> Categoría</th>
                        <th><i class="bi bi-list-ol me-1"></i> Preguntas</th>
                        <th><i class="bi bi-check-circle-fill me-1"></i> Aciertos</th>
                        <th><i class="bi bi-x-circle-fill me-1"></i> Fallos</th>

                        <th><i class="bi bi-toggle-on me-1"></i> Estado</th>
                        <th><i class="bi bi-clipboard-check-fill me-1"></i> Calificación</th>
                        <th><i class="bi bi-gear-fill me-1"></i> Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($examenes)): ?>
                        <?php foreach ($examenes as $examen): ?>
                            <tr>
                                <td class="text-center"><?= htmlspecialchars($examen['examen_id']) ?></td>
                                <td><?= htmlspecialchars($examen['estudiante']) ?></td>
                                <td><?= htmlspecialchars($examen['categoria']) ?></td>
                                <td class="text-center"><?= htmlspecialchars($examen['total_preguntas']) ?></td>
                                <td class="text-center text-success fw-semibold"><?= $examen['aciertos'] ?></td>
                                <td class="text-center text-danger fw-semibold"><?= $examen['fallos'] ?></td>

                                <td class="text-center">
                                    <?php
                                    $estado = 'REPROBADO';
                                    $colorEstado = 'danger';
                                    if (!is_null($examen['calificacion']) && $examen['calificacion'] >= 80) {
                                        $estado = 'APROBADO';
                                        $colorEstado = 'success';
                                    }
                                    ?>
                                    <span class="badge bg-<?= $colorEstado ?>"><?= $estado ?></span>
                                </td>

                                <td class="text-center">
                                    <?php if ($examen['calificacion'] !== null): ?>
                                        <?php
                                        $calificacion = floatval($examen['calificacion']);
                                        $porcentaje = round($calificacion); // Asumimos que ya está en porcentaje. Si no, multiplícalo por 100
                                        $color = 'text-danger';

                                        if ($porcentaje >= 80) {
                                            $color = 'text-primary';
                                        } elseif ($porcentaje >= 60) {
                                            $color = 'text-success';
                                        }
                                        ?>
                                        <span class="<?= $color ?> fw-semibold"><?= $porcentaje ?>%</span>
                                    <?php else: ?>
                                        —
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex gap-2 justify-content-center flex-wrap">
                                        <button class="btn btn-sm btn-outline-primary"
                                            onclick="verExamen(<?= $examen['examen_id'] ?>)">
                                            <i class="bi bi-eye-fill me-1"></i> Ver
                                        </button>
                                    </div>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
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


<!-- Modal para ver detalle del examen -->
<div class="modal fade" id="modalDetalleExamen" tabindex="-1" aria-labelledby="modalDetalleExamenLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalDetalleExamenLabel"><i class="bi bi-eye-fill me-2"></i>Detalle del
                    Examen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div id="detalleContenido" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function () {
        function filterTable() {
            const search = $("#customSearch").val().toLowerCase();
            let count = 0;

            $("table tbody tr").each(function () {
                // Ignorar fila de "No resultados" para no contarla ni mostrarla
                if ($(this).attr('id') === 'no-results') return;

                const rowText = $(this).text().toLowerCase();
                if (rowText.includes(search)) {
                    $(this).show();
                    count++;
                } else {
                    $(this).hide();
                }
            });

            if (count === 0) {
                if ($("#no-results").length === 0) {
                    $("table tbody").append(`
                            <tr id="no-results">
                                <td colspan="10">
                                    <div class="alert alert-info text-center m-0 rounded-0">
                                        <i class="bi bi-info-circle-fill me-2"></i>No se encontraron resultados.
                                    </div>
                                </td>
                            </tr>
                        `);
                }
            } else {
                $("#no-results").remove();
            }
        }

        // Filtro en tiempo real
        $("#customSearch").on("input", filterTable);

        // Redirige al cambiar la cantidad
        $('#container-length').on('change', function () {
            const selectedLimit = $(this).val();
            // Cambia la URL para página 1 y límite seleccionado
            window.location.href = `?pagina=1&limite=${selectedLimit}`;
        });

        filterTable();
    });


    function verExamen(idExamen) {
        const modal = new bootstrap.Modal(document.getElementById('modalDetalleExamen'));
        modal.show();

        const detalle = document.getElementById('detalleContenido');
        detalle.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
        </div>`;

        fetch(`../api/detalles_examen.php?id=${idExamen}`)
            .then(response => response.text())
            .then(html => {
                detalle.innerHTML = html;
            })
            .catch(() => {
                detalle.innerHTML = `<div class="alert alert-danger">Error al cargar el examen.</div>`;
            });
    }

</script>



<?php include_once('../includes/footer.php'); ?>