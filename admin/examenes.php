<?php

include_once("../includes/header.php");
include_once("../includes/sidebar.php");
$sql = "SELECT 
ex.id, est.nombre AS nombre_estudiante, cat.nombre AS nombre_categoria,
us.nombre AS asignado_por_nombre, ex.fecha_asignacion, ex.total_preguntas,
ex.estado, ex.calificacion, ex.codigo_acceso
FROM examenes ex
JOIN estudiantes est ON ex.estudiante_id = est.id
JOIN categorias cat ON ex.categoria_id = cat.id
LEFT JOIN usuarios us ON ex.asignado_por = us.id
ORDER BY ex.fecha_asignacion DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$examenes = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!-- Main -->
<div class="main-content">
    <div class="card shadow border-0 rounded-4">
        <div
            class="card-header bg-primary text-white d-flex flex-wrap justify-content-between align-items-center rounded-top-4 px-4 py-3">
            <h5 class="mb-0"><i class="bi bi-file-earmark-text-fill me-2"></i>Gestión de Exámenes</h5>
            <div class="search-box position-relative">
                <input type="text" class="form-control ps-5" id="customSearch" placeholder="Buscar examen...">
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
                <button class="btn btn-success" onclick="abrirModalExamen()">
                    <i class="bi bi-file-earmark-plus-fill me-2"></i>Nuevo Examen
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table id="examenes-table" class="table table-hover align-middle shadow-sm rounded-3 overflow-hidden">
                <thead class="table-light text-center">
                    <?php if (!empty($examenes)): ?>
                        <tr>
                            <th><i class="bi bi-hash me-1"></i> ID</th>
                            <th><i class="bi bi-person-fill me-1"></i> Estudiante</th>
                            <th><i class="bi bi-tags-fill me-1"></i> Categoría</th>
                            <th><i class="bi bi-person-badge-fill me-1"></i> Asignado Por</th>
                            <th><i class="bi bi-calendar-event-fill me-1"></i> Fecha Asignación</th>
                            <th><i class="bi bi-list-ol me-1"></i> Total Preguntas</th>
                            <th><i class="bi bi-toggle-on me-1"></i> Estado</th>
                            <th><i class="bi bi-clipboard-check-fill me-1"></i> Calificación</th>
                            <th><i class="bi bi-key-fill me-1"></i> Código Acceso</th>
                            <th><i class="bi bi-gear-fill me-1"></i> Acciones</th>
                        </tr>

                    </thead>
                    <tbody>
                        <?php foreach ($examenes as $examen): ?>
                            <tr>
                                <td class="text-center"><?= $examen['id'] ?></td>
                                <td><?= $examen['estudiante_id'] ?></td>
                                <td><?= $examen['categoria_id'] ?></td>
                                <td><?= $examen['asignado_por'] ?? '—' ?></td>
                                <td><?= $examen['fecha_asignacion'] ?></td>
                                <td><?= $examen['total_preguntas'] ?></td>
                                <td>
                                    <span
                                        class="badge bg-<?= $examen['estado'] === 'pendiente' ? 'warning' : ($examen['estado'] === 'en_progreso' ? 'primary' : 'success') ?>">
                                        <?= strtoupper($examen['estado']) ?>
                                    </span>
                                </td>
                                <td><?= $examen['calificacion'] !== null ? $examen['calificacion'] : '—' ?></td>
                                <td><code><?= $examen['codigo_acceso'] ?></code></td>
                                <td class="text-center">
                                    <div class="d-flex gap-2 justify-content-center flex-wrap">
                                        <button class="btn btn-sm btn-outline-primary"
                                            onclick="verExamen(<?= $examen['id'] ?>)">
                                            <i class="bi bi-eye-fill me-1"></i> Ver
                                        </button>
                                        <button class="btn btn-sm btn-outline-warning"
                                            onclick="editarExamen(<?= $examen['id'] ?>)">
                                            <i class="bi bi-pencil-fill me-1"></i> Editar
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger"
                                            onclick="eliminarExamen(<?= $examen['id'] ?>)">
                                            <i class="bi bi-trash-fill me-1"></i> Eliminar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-warning text-center m-3">
                            <i class="bi bi-exclamation-circle-fill me-2"></i>⚠️ No hay exámenes registrados actualmente.
                        </div>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="modalExamen" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content rounded-4 shadow">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="tituloModalExamen"><i class="bi bi-journal-plus me-2"></i>Nuevo Examen</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form id="formExamen">
        <div class="modal-body row g-3 px-4 py-3">
          <input type="hidden" name="id" id="examen_id">

          <div class="col-md-6">
            <label for="estudiante_id" class="form-label">Estudiante</label>
            <select class="form-select" id="estudiante_id" name="estudiante_id" required></select>
          </div>

          <div class="col-md-6">
            <label for="categoria_id" class="form-label">Categoría</label>
            <select class="form-select" id="categoria_id" name="categoria_id" required></select>
          </div>

          <div class="col-md-6">
            <label for="total_preguntas" class="form-label">Total de Preguntas</label>
            <input type="number" class="form-control" id="total_preguntas" name="total_preguntas" min="1" required>
          </div>

          <div class="col-md-6">
            <label for="estado" class="form-label">Estado</label>
            <select class="form-select" id="estado" name="estado">
              <option value="pendiente">Pendiente</option>
              <option value="en_progreso">En Progreso</option>
              <option value="finalizado">Finalizado</option>
            </select>
          </div>

          <div class="col-md-6">
            <label for="codigo_acceso" class="form-label">Código de Acceso</label>
            <input type="text" class="form-control" id="codigo_acceso" name="codigo_acceso" required>
          </div>
        </div>

        <div class="modal-footer px-4 py-3">
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-1"></i>Guardar Examen
          </button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>



<script>
    function abrirModalExamen(examen = null) {
  const modal = new bootstrap.Modal(document.getElementById('modalExamen'));
  document.getElementById('formExamen').reset();
  document.getElementById('examen_id').value = '';
  document.getElementById('tituloModalExamen').textContent = examen ? 'Editar Examen' : 'Nuevo Examen';

  if (examen) {
    document.getElementById('examen_id').value = examen.id;
    document.getElementById('estudiante_id').value = examen.estudiante_id;
    document.getElementById('categoria_id').value = examen.categoria_id;
    document.getElementById('total_preguntas').value = examen.total_preguntas;
    document.getElementById('estado').value = examen.estado;
    document.getElementById('codigo_acceso').value = examen.codigo_acceso;
  }

  modal.show();
}

</script>

<?php include_once('../includes/footer.php'); ?>