<?php
require_once '../includes/conexion.php';
header('Content-Type: application/json');

if (!isset($_GET['id'])) {
  echo json_encode(['error' => 'ID de examen no proporcionado']);
  exit;
}

$id = intval($_GET['id']);

try { 

  // Obtener datos del examen con JOINs correctos según estructura actual
  $stmt = $pdo->prepare("
    SELECT 
      e.id,
      e.fecha_asignacion,
      e.estado,
      e.calificacion,
      e.codigo_acceso,
      e.duracion,
      e.total_preguntas,
      
      est.id AS estudiante_id,
      CONCAT(est.apellidos, ' ', est.nombre) AS estudiante,
      est.dni,
      est.email,
      est.telefono,
      
      c.id AS categoria_id,
      c.nombre AS categoria,
      
      u.nombre AS asignado_por

    FROM examenes e
    INNER JOIN estudiantes est ON e.estudiante_id = est.id
    INNER JOIN categorias c ON e.categoria_id = c.id
    LEFT JOIN usuarios u ON e.asignado_por = u.id
    WHERE e.id = ?
  ");

  $stmt->execute([$id]);
  $examen = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$examen) {
    echo json_encode(['error' => 'Examen no encontrado']);
    exit;
  }

  echo json_encode(['success' => true, 'examen' => $examen]);

} catch (Exception $e) {
  echo json_encode(['error' => 'Error en el servidor: ' . $e->getMessage()]);
}
