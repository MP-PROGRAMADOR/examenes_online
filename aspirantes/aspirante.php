<?php
include_once("includes/header.php");
require '../config/conexion.php';

$pdo = $pdo->getConexion();

// ----------------------------------------
// Validar aqui si hay sesión activa
// ----------------------------------------
 

$estudiante = $_SESSION['estudiante'];
$estudiante_id = $estudiante['id'];

// Inicialización de variables
$estadoExamen = "NO ENCONTRADO";
$intentosCompletados = 0;
$promedio = 0;
$examenesRealizados = [];
$accesoExamen = 0;
$alerta = null;

try {
    // ----------------------------------------
    // Obtener el estado actual del examen
    // ----------------------------------------
    $stmtEstado = $pdo->prepare("
        SELECT estado, acceso_habilitado 
        FROM examenes_estudiantes 
        WHERE estudiante_id = :id 
        ORDER BY id DESC 
        LIMIT 1
    ");
    $stmtEstado->execute(['id' => $estudiante_id]);
    $resultado = $stmtEstado->fetch(PDO::FETCH_ASSOC);

    if ($resultado) {
        $estadoExamen = ucfirst($resultado['estado']);
        $accesoExamen = (int)$resultado['acceso_habilitado'];
    }

    // ----------------------------------------
    // Calcular el promedio de notas
    // ----------------------------------------
    $stmtPromedio = $pdo->prepare("
        SELECT AVG(
            (SELECT COUNT(*) FROM respuestas_estudiante WHERE intento_examen_id = i.id AND es_correcta = 1) /
            NULLIF((SELECT COUNT(*) FROM respuestas_estudiante WHERE intento_examen_id = i.id), 0) * 100
        ) AS promedio
        FROM intentos_examen i
        WHERE estudiante_id = :id AND completado = 1
    ");
    $stmtPromedio->execute(['id' => $estudiante_id]);
    $fila = $stmtPromedio->fetch(PDO::FETCH_ASSOC);
    $promedio = $fila && $fila['promedio'] !== null ? round($fila['promedio']) : 0;

    // ----------------------------------------
    // Contar intentos completados
    // ----------------------------------------
    $stmtIntentos = $pdo->prepare("
        SELECT COUNT(*) AS total 
        FROM intentos_examen 
        WHERE estudiante_id = :id AND completado = 1
    ");
    $stmtIntentos->execute(['id' => $estudiante_id]);
    $intentoRow = $stmtIntentos->fetch(PDO::FETCH_ASSOC);
    $intentosCompletados = $intentoRow['total'];

    // ----------------------------------------
    // Últimos exámenes completados
    // ----------------------------------------
    $stmtUltimos = $pdo->prepare("
        SELECT e.titulo, i.fecha_fin,
            ROUND((
                (SELECT COUNT(*) FROM respuestas_estudiante WHERE intento_examen_id = i.id AND es_correcta = 1) /
                NULLIF((SELECT COUNT(*) FROM respuestas_estudiante WHERE intento_examen_id = i.id), 0) * 100
            )) AS nota,
            (SELECT estado FROM examenes_estudiantes WHERE id = i.examen_estudiante_id) AS estado_examen
        FROM intentos_examen i
        INNER JOIN examenes e ON i.examen_id = e.id
        WHERE i.estudiante_id = :id AND i.completado = 1
        ORDER BY i.fecha_fin DESC
        LIMIT 5
    ");
    $stmtUltimos->execute(['id' => $estudiante_id]);
    $examenesRealizados = $stmtUltimos->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $estadoExamen = "ERROR: " . htmlspecialchars($e->getMessage());
    $alerta = [
        'tipo' => 'danger',
        'mensaje' => 'Ocurrió un error al cargar los datos del examen. Intenta nuevamente más tarde.'
    ];
}

// ----------------------------------------
// VALIDACIÓN DE ACCESO AL EXAMEN
// ----------------------------------------
$sql = "
    SELECT ee.id AS examen_estudiante_id, ee.acceso_habilitado, ee.fecha_proximo_intento,
           e.id AS examen_id, e.titulo
    FROM examenes_estudiantes ee
    INNER JOIN examenes e ON e.categoria_carne_id = ee.categoria_carne_id
    WHERE ee.estudiante_id = ?
    LIMIT 1
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$estudiante_id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

// Evaluar si el estudiante puede acceder al examen
if (!$data) {
    $alerta = [
        'tipo' => 'warning',
        'mensaje' => 'No tienes un examen asignado actualmente. Contacta con el administrador.'
    ];
} elseif ($data['acceso_habilitado'] != 1) {
    $alerta = [
        'tipo' => 'info',
        'mensaje' => 'Tu acceso al examen aún no ha sido habilitado. Vuelve a revisar más tarde.'
    ];
} else {
    // Comprobar si ya se realizó un intento
    $stmt2 = $pdo->prepare("
        SELECT COUNT(*) AS total 
        FROM intentos_examen 
        WHERE estudiante_id = ? AND examen_id = ?
    ");
    $stmt2->execute([$estudiante_id, $data['examen_id']]);
    $intentos = $stmt2->fetch(PDO::FETCH_ASSOC);

    if ($intentos['total'] >= 1) {
        $alerta = [
            'tipo' => 'danger',
            'mensaje' => 'Ya has realizado un intento. Podrás volver a intentarlo el <strong>' . 
                         htmlspecialchars($data['fecha_proximo_intento']) . '</strong>.'
        ];
    }
}
?>

<!-- HTML: Panel -->
<div class="container py-4">

    <!-- Mostrar alerta si existe -->
    <?php if ($alerta): ?>
        <div class="alert alert-<?= $alerta['tipo'] ?> text-center" role="alert">
            <?= $alerta['mensaje'] ?>
        </div>
    <?php endif; ?>

    <!-- Tarjetas resumen -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">Examen</h6>
                        <h4 class="text-primary fw-bold">
                            <?= $accesoExamen == 1 ? htmlspecialchars($estadoExamen) : 'No disponible' ?>
                        </h4>
                    </div>
                    <div class="fs-2 text-primary">📝</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">Completados</h6>
                        <h4 class="text-success fw-bold"><?= $intentosCompletados ?></h4>
                    </div>
                    <div class="fs-2 text-success">✅</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">Promedio</h6>
                        <h4 class="text-warning fw-bold"><?= $promedio ?>%</h4>
                    </div>
                    <div class="fs-2 text-warning">📊</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones disponibles según acceso -->
    <?php if ($accesoExamen == 1): ?>
        <div class="row mt-3">
            <div class="col-md-6 text-center">
                <a href="practicas.php" class="btn btn-lg btn-primary">
                    <i class="bi bi-play-circle me-2"></i> Iniciar práctica
                </a>
            </div>
            <div class="col-md-6 text-center">
                <a href="politicas.php" class="btn btn-lg btn-success">
                    <i class="bi bi-play-circle me-2"></i> Iniciar Examen Oficial
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="text-center mt-4">
            <a href="practicas.php" class="btn btn-lg btn-primary">
                <i class="bi bi-play-circle me-2"></i> Iniciar práctica
            </a>
            <div class="alert alert-warning mt-3 d-inline-block">
                No tienes exámenes habilitados en este momento.
            </div>
        </div>
    <?php endif; ?>

    <!-- Tabla de exámenes realizados -->
    <div class="card shadow-sm mt-5">
        <div class="card-header bg-white">
            <h5 class="mb-0">Últimos exámenes realizados</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Examen</th>
                            <th>Fecha</th>
                            <th>Calificación</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($examenesRealizados)): ?>
                            <?php foreach ($examenesRealizados as $examen): ?>
                                <tr>
                                    <td><?= htmlspecialchars($examen['titulo']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($examen['fecha_fin'])) ?></td>
                                    <td><?= intval($examen['nota']) ?>%</td>
                                    <td>
                                        <?php
                                        $estado = strtolower($examen['estado_examen']);
                                        $badge = match ($estado) {
                                            'aprobado' => 'bg-success',
                                            'reprobado' => 'bg-danger',
                                            default => 'bg-secondary'
                                        };
                                        ?>
                                        <span class="badge <?= $badge ?>"><?= ucfirst($estado) ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">No has completado exámenes aún.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS (Popper incluido) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
