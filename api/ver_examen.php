<?php
require '../includes/conexion.php';
$id = $_POST['id'];
$stmt = $pdo->prepare("SELECT e.*, es.nombre AS estudiante, c.nombre AS categoria 
                       FROM examenes e
                       JOIN estudiantes es ON e.estudiante_id = es.id
                       JOIN categorias c ON e.categoria_id = c.id
                       WHERE e.id = ?");
$stmt->execute([$id]);
$examen = $stmt->fetch();

if ($examen): ?>
  <ul class="list-group">
    <li class="list-group-item"><strong>Estudiante:</strong> <?= htmlspecialchars($examen['estudiante']) ?></li>
    <li class="list-group-item"><strong>Categoría:</strong> <?= htmlspecialchars($examen['categoria']) ?></li>
    <li class="list-group-item"><strong>Fecha:</strong> <?= htmlspecialchars($examen['fecha_asignacion']) ?></li>
    <li class="list-group-item"><strong>Preguntas:</strong> <?= htmlspecialchars($examen['total_preguntas']) ?></li>
    <li class="list-group-item"><strong>Estado:</strong> <?= strtoupper($examen['estado']) ?></li>
    <li class="list-group-item"><strong>Calificación:</strong> <?= $examen['calificacion'] ?? '—' ?></li>
    <li class="list-group-item"><strong>Código de Acceso:</strong> <code><?= htmlspecialchars($examen['codigo_acceso']) ?></code></li>
  </ul>
<?php else: ?>
  <div class="alert alert-danger">Examen no encontrado.</div>
<?php endif; ?>
