

<?php

require '../config/conexion.php';
$pdo = $pdo->getConexion();
// Obtener exámenes disponibles
$stmt = $pdo->prepare("
SELECT e.id, e.titulo, e.descripcion, e.duracion_minutos, e.total_preguntas, e.preguntas_aleatorias,
c.nombre AS categoria
FROM examenes e
INNER JOIN categorias_carne c ON e.categoria_carne_id = c.id
ORDER BY e.fecha_creacion DESC
");
$stmt->execute();
$examenes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
 <?php include_once("includes/header.php") ?>
 
  <!-- Main content -->
  <div class="main-content">
    <div class="container-fluid mt-5">
      <div class="card shadow border-0 rounded-4">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center rounded-top-4 px-4">
          <h5 class="mb-0"><i class="bi bi-journal-text me-2"></i>Exámenes Disponibles</h5>
        </div>

        <div class="card-body">
          <?php if (!empty($examenes)): ?>
            <div class="table-responsive">
              <table class="table table-hover align-middle">
                <thead class="table-light">
                  <tr>
                    <th>Título</th>
                    <th>Categoría</th>
                    <th>Duración</th>
                    <th>Preguntas</th>
                    <th>Modo</th>
                    <th>Acción</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($examenes as $examen): ?>
                    <tr>
                      <td><?= htmlspecialchars($examen['titulo']) ?></td>
                      <td><?= htmlspecialchars($examen['categoria']) ?></td>
                      <td><?= htmlspecialchars($examen['duracion_minutos']) ?> min</td>
                      <td><?= htmlspecialchars($examen['total_preguntas']) ?></td>
                      <td>
                        <?= $examen['preguntas_aleatorias'] ? '<span class="badge bg-info">Aleatorio</span>' : '<span class="badge bg-secondary">Orden fijo</span>' ?>
                      </td>
                      <td>
                        <a href="folio_test.php?id=<?= $examen['id'] ?>" class="btn btn-primary btn-sm">
                          <i class="bi bi-play-circle-fill me-1"></i> Iniciar
                        </a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <div class="alert alert-warning text-center">
              <i class="bi bi-exclamation-triangle-fill me-2"></i>No hay exámenes disponibles por el momento.
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
 
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>


 