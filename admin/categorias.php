<?php
include_once("../includes/header.php");
include_once("../includes/sidebar.php");

try {
    // Número de registros por página
    $limite = isset($_GET['limite']) ? (int) $_GET['limite'] : 10;
    $pagina = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
    $inicio = ($pagina - 1) * $limite;

    // Contar total de registros para la paginación
    $total_sql = "SELECT COUNT(*) FROM categorias";
    $total_stmt = $pdo->query($total_sql);
    $total_registros = $total_stmt->fetchColumn();
    $total_paginas = ceil($total_registros / $limite);

    // SQL MEJORADO: Unimos con la tabla intermedia pregunta_categoria
    // Usamos LEFT JOIN para no excluir categorías que tengan 0 preguntas
    $sql = "SELECT c.*, COUNT(pc.pregunta_id) AS total_preguntas 
            FROM categorias c
            LEFT JOIN pregunta_categoria pc ON c.id = pc.categoria_id
            GROUP BY c.id
            LIMIT :inicio, :limite";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
    $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
    $stmt->execute();
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error: " . $e->getMessage());
    $categorias = [];
}
?>

<main class="main-content" id="content">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white d-flex flex-wrap align-items-center justify-content-between gap-3 p-3 rounded-top">
            <h5 class="mb-0 d-flex align-items-center">
                <i class="bi bi-tags-fill me-2"></i>Listado de Categorías
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
                    <thead class="table-primary">
                        <tr>
                            <th><i class="bi bi-hash"></i> ID</th>
                            <th><i class="bi bi-tag-fill"></i> Nombre</th>
                            <th><i class="bi bi-card-text"></i> Descripción</th>
                            <th><i class="bi bi-person"></i> Edad Mínima</th>
                            <th><i class="bi bi-person"></i> Cantidad Preguntas</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($categorias)): ?>
                            <?php foreach ($categorias as $categoria): ?>
                                <tr>
                                    <td><?= htmlspecialchars($categoria['id']) ?></td>
                                    <td><?= htmlspecialchars($categoria['nombre']) ?></td>
                                    <td><?= htmlspecialchars($categoria['descripcion']) ?></td>
                                    <td><?= htmlspecialchars($categoria['edad_minima']) ?> años</td>
                                   <td class="text-center">
                                        <span class="badge rounded-pill bg-info text-dark">
                                            <?= (int)$categoria['total_preguntas'] ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">
                                    <div class="alert alert-warning text-center m-0 rounded-0">
                                        <i class="bi bi-exclamation-circle-fill me-2"></i>No hay categorías registradas actualmente.
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($total_paginas > 1): ?>
                <nav aria-label="Paginación de categorías" class="my-3">
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

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            function filterTable() {
                const search = $("#customSearch").val().toLowerCase();
                let count = 0;

                $("table tbody tr").each(function() {
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

            $('#container-length').on('change', function() {
                const selectedLimit = $(this).val();
                window.location.href = `?pagina=1&limite=${selectedLimit}`;
            });

            filterTable();
        });
    </script>
</main>

<?php include_once('../includes/footer.php'); ?>