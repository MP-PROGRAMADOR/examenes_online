<?php include_once("includes/header.php"); ?>

<?php
// Suponiendo que $estudiante está disponible desde la sesión o el contexto actual
$id = $estudiante['id']; 

require '../config/conexion.php'; 
$pdo = $pdo->getConexion();

// Obtener el id de la categoría del carne del estudiante
$stmtCategoria = $pdo->prepare("SELECT categoria_carne_id FROM estudiantes WHERE id = :id ORDER BY id DESC LIMIT 1");
$stmtCategoria->execute(['id' => $id]);
$categoria_carne = $stmtCategoria->fetch(PDO::FETCH_ASSOC); // Usamos fetch para obtener una sola fila

if ($categoria_carne) {
    $id_carne = $categoria_carne['categoria_carne_id'];

    // Obtener el examen asociado a la categoría del carne
    $stmtExamen = $pdo->prepare("SELECT * FROM examenes WHERE categoria_carne_id = :id ORDER BY id DESC LIMIT 1");
    $stmtExamen->execute(['id' => $id_carne]);
    $examen = $stmtExamen->fetch(PDO::FETCH_ASSOC); // Usamos fetch para obtener una sola fila
} 
?>

<!-- Main content -->
<div class="main-content">
  <div class="container-fluid mt-5">
    <div class="card shadow border-0 rounded-4">
      <div class="card-header bg-success text-white d-flex justify-content-between align-items-center rounded-top-4 px-4">
        <h5 class="mb-0"><i class="bi bi-journal-text me-2"></i>Exámenes Disponibles</h5>
      </div>

      <div class="card-body">
        <?php if ($examen): ?>
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
                <tr>
                  <td><?= htmlspecialchars($examen['titulo']) ?></td>
                  <td><?= htmlspecialchars($examen['categoria_carne_id']) ?></td>
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
