<?php
include_once("../includes/header.php");
include_once("../includes/sidebar_examinador.php");

// Definir variables de paginación y límite por defecto para evitar errores
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
  cat.nombre AS categoria,
  us.nombre AS usuario, 
  ex.fecha_asignacion, 
  ex.total_preguntas,
  ex.estado, 
  ex.calificacion, 
  ex.codigo_acceso,

  -- Aciertos: conteo de respuestas correctas
  (
    SELECT COUNT(*) 
    FROM respuestas_estudiante re
    JOIN opciones_pregunta op ON re.opcion_id = op.id
    WHERE op.es_correcta = 1 AND re.examen_pregunta_id IN (
      SELECT id FROM examen_preguntas WHERE examen_id = ex.id
    )
  ) AS aciertos,

  -- Fallos: total respondidas menos acertadas
  (
    SELECT COUNT(*) 
    FROM respuestas_estudiante re
    WHERE re.examen_pregunta_id IN (
      SELECT id FROM examen_preguntas WHERE examen_id = ex.id
    )
  ) -
  (
    SELECT COUNT(*) 
    FROM respuestas_estudiante re
    JOIN opciones_pregunta op ON re.opcion_id = op.id
    WHERE op.es_correcta = 1 AND re.examen_pregunta_id IN (
      SELECT id FROM examen_preguntas WHERE examen_id = ex.id
    )
  ) AS fallos

FROM examenes ex
JOIN estudiantes est ON ex.estudiante_id = est.id
JOIN categorias cat ON ex.categoria_id = cat.id
LEFT JOIN usuarios us ON ex.asignado_por = us.id
WHERE ex.estado = 'finalizado'
ORDER BY ex.fecha_asignacion DESC
LIMIT :limite OFFSET :offset
";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$examenes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="main-content" id="content">
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
                                    <td class="text-center"><?= htmlspecialchars($examen['id']) ?></td>
                                    <td><?= htmlspecialchars($examen['estudiante']) ?></td>
                                    <td><?= htmlspecialchars($examen['categoria']) ?></td>
                                    <td class="text-center"><?= htmlspecialchars($examen['total_preguntas']) ?></td>
                                    <td class="text-center text-success fw-semibold"><?= $examen['aciertos'] ?></td>
<td class="text-center text-danger fw-semibold"><?= $examen['fallos'] ?></td>

                                    <td class="text-center">
                                        <span
                                            class="badge bg-<?= $examen['estado'] === 'pendiente' ? 'warning' : ($examen['estado'] === 'en_progreso' ? 'primary' : 'success') ?>">
                                            <?= strtoupper($examen['estado']) ?>
                                        </span>
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
                                                onclick="verExamen(<?= $examen['id'] ?>)">
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
    </script>
    </div>


    <?php include_once('../includes/footer.php'); ?>