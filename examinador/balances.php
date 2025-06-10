<?php
include_once("../includes/header.php");
include_once("../includes/sidebar_examinador.php");


// Paginación
$pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$limite = isset($_GET['limite']) ? intval($_GET['limite']) : 10;
$offset = ($pagina - 1) * $limite;

// Obtener total de filas
$total_sql = "SELECT COUNT(*) FROM (SELECT c.id FROM estudiante_categorias ec JOIN categorias c ON ec.categoria_id = c.id GROUP BY c.id) AS total";
$total_stmt = $pdo->query($total_sql);
$total_filas = $total_stmt->fetchColumn();
$total_paginas = ceil($total_filas / $limite);

// Consulta principal con paginación
$sql = "   SELECT 
    c.id AS categoria_id,
    c.nombre AS categoria,

    COUNT(DISTINCT ec.estudiante_id) AS total_inscritos,

    (
        SELECT COUNT(DISTINCT ec2.estudiante_id)
        FROM estudiante_categorias ec2
        WHERE ec2.categoria_id = c.id AND ec2.estado = 'aprobado'
    ) AS total_aprobados,

    (
        SELECT COUNT(DISTINCT ec3.estudiante_id)
        FROM estudiante_categorias ec3
        LEFT JOIN examenes e3 ON e3.estudiante_id = ec3.estudiante_id AND e3.categoria_id = ec3.categoria_id
        WHERE ec3.categoria_id = c.id AND (e3.id IS NULL OR e3.calificacion IS NULL)
    ) AS pendientes_examen,

    (
        SELECT COUNT(DISTINCT ec4.estudiante_id)
        FROM estudiante_categorias ec4
        JOIN examenes e4 ON e4.estudiante_id = ec4.estudiante_id AND e4.categoria_id = ec4.categoria_id
        WHERE ec4.categoria_id = c.id AND e4.estado = 'finalizado' AND e4.calificacion < 80
    ) AS total_reprobados

FROM categorias c
JOIN estudiante_categorias ec ON ec.categoria_id = c.id
GROUP BY c.id
ORDER BY c.nombre
LIMIT :limite OFFSET :offset 
";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main  id="content">
    <div class="card shadow-sm mb-4">
        <div
            class="card-header bg-primary text-white d-flex flex-wrap align-items-center justify-content-between gap-3 p-3 rounded-top">
            <h5 class="mb-0 d-flex align-items-center">
                <i class="bi bi-graph-up-arrow me-2"></i>Resultados por Categoría
            </h5>
            <div class="d-flex align-items-center gap-3 flex-grow-1 flex-wrap justify-content-end">
                <div class="position-relative">
                    <input type="text" class="form-control ps-5 form-control-sm shadow-sm" id="customSearch"
                        placeholder="Buscar categoría...">
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
                <table class="table table-striped table-bordered align-middle mb-0">
                    <thead class="table-primary text-center">
                        <tr>
                            <th><i class="bi bi-tags-fill me-1"></i>Categoría</th>
                            <th><i class="bi bi-person-check-fill me-1"></i>Aprobados</th>
                            <th><i class="bi bi-people-fill me-1"></i>Inscritos</th>
                            <th><i class="bi bi-clock-history me-1"></i>Pendientes</th>
                            <th><i class="bi bi-x-circle me-1"></i>Reprobados</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($resultados)): ?>
                            <?php foreach ($resultados as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['categoria']) ?></td>
                                    <td class="text-center text-success fw-semibold"><?= $row['total_aprobados'] ?></td>
                                    <td class="text-center"><?= $row['total_inscritos'] ?></td>
                                    <td class="text-warning fw-semibold"><?= $row['pendientes_examen'] ?></td>
                                    <td class="text-danger fw-semibold"><?= $row['total_reprobados'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">
                                    <div class="alert alert-warning text-center m-0 rounded-0">
                                        <i class="bi bi-exclamation-circle-fill me-2"></i>No hay resultados disponibles.
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($total_paginas > 1): ?>
                <nav aria-label="Paginación de resultados" class="my-3">
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
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function () {
        function filterTable() {
            const search = $("#customSearch").val().toLowerCase();
            let count = 0;

            $("table tbody tr").each(function () {
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
                                <td colspan="4">
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

        $("#customSearch").on("input", filterTable);

        $('#container-length').on('change', function () {
            const selectedLimit = $(this).val();
            window.location.href = `?pagina=1&limite=${selectedLimit}`;
        });

        filterTable();
    });
</script>
<?php include_once('../includes/footer.php'); ?>