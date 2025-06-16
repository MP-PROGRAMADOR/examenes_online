<?php
require_once '../includes/conexion.php'; 

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo '<div class="alert alert-danger">ID de examen no válido.</div>';
    exit;
}

$id_examen = intval($_GET['id']);

// 1. Cargar datos generales del examen
$sql = "
SELECT 
    e.id AS examen_id,
    est.nombre, est.apellidos, est.dni,
    c.nombre AS categoria,
    e.total_preguntas,
    e.calificacion
FROM examenes e
JOIN estudiantes est ON est.id = e.estudiante_id
JOIN categorias c ON c.id = e.categoria_id
WHERE e.id = :id
";

$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id_examen]);
$examen = $stmt->fetch();

if (!$examen) {
    echo '<div class="alert alert-warning">No se encontró el examen.</div>';
    exit;
}

// 2. Total de preguntas respondidas
$sqlRespondidas = "
SELECT COUNT(DISTINCT ep.id) AS total_respondidas
FROM examen_preguntas ep
JOIN respuestas_estudiante re ON re.examen_pregunta_id = ep.id
WHERE ep.examen_id = :id
";
$stmt = $pdo->prepare($sqlRespondidas);
$stmt->execute([':id' => $id_examen]);
$totalRespondidas = $stmt->fetchColumn();

// 3. Obtener todas las preguntas con sus opciones y respuestas
$sqlPreguntas = "
SELECT
    ep.id AS examen_pregunta_id,
    p.texto AS texto_pregunta,
    p.tipo,
    GROUP_CONCAT(DISTINCT CASE WHEN op.es_correcta = 1 THEN op.id END) AS correctas,
    GROUP_CONCAT(DISTINCT re.opcion_id) AS seleccionadas
FROM examen_preguntas ep
JOIN preguntas p ON p.id = ep.pregunta_id
LEFT JOIN opciones_pregunta op ON op.pregunta_id = p.id
LEFT JOIN respuestas_estudiante re ON re.examen_pregunta_id = ep.id
WHERE ep.examen_id = :id
GROUP BY ep.id
";

$stmt = $pdo->prepare($sqlPreguntas);
$stmt->execute([':id' => $id_examen]);
$preguntas = $stmt->fetchAll();

// 4. Contar aciertos y fallos
$aciertos = 0;
$fallos = 0;

foreach ($preguntas as $preg) {
    $correctas = array_filter(explode(',', $preg['correctas']));
    $seleccionadas = array_filter(explode(',', $preg['seleccionadas']));

    sort($correctas);
    sort($seleccionadas);

    if ($correctas === $seleccionadas && !empty($correctas)) {
        $aciertos++;
    } elseif (!empty($seleccionadas)) {
        $fallos++;
    }
}

// Estado visual
$estado = ($examen['calificacion'] >= 80) ? 'APROBADO' : 'REPROBADO';
$colorEstado = ($estado === 'APROBADO') ? 'success' : 'danger';
?>

<div class="container my-4">

    <!-- Información del estudiante y examen -->
    <div class="row g-4">
        <!-- Card Estudiante -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white fw-bold">
                    <i class="bi bi-person-circle me-2"></i>Estudiante
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>Nombre:</strong> <?= htmlspecialchars($examen['nombre'] . ' ' . $examen['apellidos']) ?></p>
                    <p class="mb-1"><strong>DNI:</strong> <?= htmlspecialchars($examen['dni']) ?></p>
                    <p class="mb-0"><strong>Categoría:</strong> <?= htmlspecialchars($examen['categoria']) ?></p>
                </div>
            </div>
        </div>

        <!-- Card Calificaciones -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-secondary text-white fw-bold">
                    <i class="bi bi-clipboard-data me-2"></i>Resumen del Examen
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush small">
                        <li class="list-group-item d-flex justify-content-between">
                            Total de preguntas:
                            <span class="fw-semibold"><?= $examen['total_preguntas'] ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            Respondidas:
                            <span class="fw-semibold"><?= $totalRespondidas ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between text-success">
                            Aciertos:
                            <span class="fw-semibold"><?= $aciertos ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between text-danger">
                            Fallos:
                            <span class="fw-semibold"><?= $fallos ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            Calificación:
                            <span class="fw-semibold"><?= round($examen['calificacion']) ?>%</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            Estado:
                            <span class="badge bg-<?= $colorEstado ?> px-3"><?= $estado ?></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Detalle de respuestas -->
    <div class="card shadow-sm border-0 mt-5">
        <div class="card-header bg-info text-white fw-bold">
            <i class="bi bi-question-circle me-2"></i>Detalle de respuestas
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm mb-0 align-middle">
                    <thead class="table-light text-center">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Pregunta</th>
                            <th style="width: 100px;">Correcta</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($preguntas as $i => $preg): ?>
                            <?php
                                $correctas = array_filter(explode(',', $preg['correctas']));
                                $seleccionadas = array_filter(explode(',', $preg['seleccionadas']));
                                sort($correctas); sort($seleccionadas);
                                $esCorrecta = ($correctas === $seleccionadas && !empty($correctas));
                            ?>
                            <tr>
                                <td class="text-center"><?= $i + 1 ?></td>
                                <td><?= htmlspecialchars($preg['texto_pregunta']) ?></td>
                                <td class="text-center">
                                    <?php if ($esCorrecta): ?>
                                        <span class="text-success fw-bold"><i class="bi bi-check-circle-fill fs-5"></i></span>
                                    <?php else: ?>
                                        <span class="text-danger fw-bold"><i class="bi bi-x-circle-fill fs-5"></i></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($preguntas)): ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted">No hay preguntas registradas.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
