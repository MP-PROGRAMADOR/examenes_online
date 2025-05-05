<?php

include_once("includes/header.php");
require '../config/conexion.php';

$pdo = $pdo->getConexion();
$id_estudiante =$_SESSION['estudiante_id'];
$estadoExamen = "NO ENCONTRADO";
$intentosCompletados = 0;
$promedio = 0;
$examenesRealizados = [];
$codigo =  $_SESSION['codigo_registro_examen'];
try {
    // Estado actual del examen
    $stmtEstado = $pdo->prepare("SELECT estado FROM examenes_estudiantes WHERE estudiante_id = :id_estudiante ORDER BY id DESC LIMIT 1");
    $stmtEstado->execute(['id_estudiante' => $id_estudiante]);
    $resultado = $stmtEstado->fetch(PDO::FETCH_ASSOC);
    if ($resultado) {
        $estadoExamen = ucfirst($resultado['estado']);
    }

    // Intentos completados
    $stmtIntentos = $pdo->prepare("SELECT COUNT(*) as total FROM intentos_examen WHERE estudiante_id = :id_estudiante AND completado = 1");
    $stmtIntentos->execute(['id_estudiante' => $id_estudiante]);
    $fila = $stmtIntentos->fetch(PDO::FETCH_ASSOC);
    $intentosCompletados = $fila ? $fila['total'] : 0;

    // Promedio de notas
    $stmtPromedio = $pdo->prepare("
        SELECT AVG(
            (SELECT COUNT(*) FROM respuestas_estudiante 
             WHERE intento_examen_id = i.id AND es_correcta = 1) /
            (SELECT COUNT(*) FROM respuestas_estudiante 
             WHERE intento_examen_id = i.id) * 100
        ) AS promedio
        FROM intentos_examen i
        WHERE estudiante_id = :id_estudiante AND completado = 1
    ");
    $stmtPromedio->execute(['id_estudiante' => $id_estudiante]);
    $fila = $stmtPromedio->fetch(PDO::FETCH_ASSOC);
    $promedio = $fila && $fila['promedio'] !== null ? round($fila['promedio']) : 0;

    // Últimos exámenes
    $stmtUltimos = $pdo->prepare("
        SELECT e.titulo, i.fecha_fin,
            ROUND((
                (SELECT COUNT(*) FROM respuestas_estudiante 
                 WHERE intento_examen_id = i.id AND es_correcta = 1) /
                (SELECT COUNT(*) FROM respuestas_estudiante 
                 WHERE intento_examen_id = i.id) * 100
            )) AS nota,
            (SELECT estado FROM examenes_estudiantes 
             WHERE id = i.examen_estudiante_id) AS estado_examen
        FROM intentos_examen i
        INNER JOIN examenes e ON i.examen_id = e.id
        WHERE i.estudiante_id = :id_estudiante AND i.completado = 1
        ORDER BY i.fecha_fin DESC
        LIMIT 5
    ");
    $stmtUltimos->execute(['id_estudiante' => $id_estudiante]);
    $examenesRealizados = $stmtUltimos->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $estadoExamen = "ERROR: " . $e->getMessage();
}
?>

<!-- Contenido principal -->
<div class="container py-4">

    <!-- Cards estadísticas -->
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card stat-card shadow-sm">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted">Estado actual <?php $codigo?></h6>
                        <h3 class="fw-bold text-primary"><?= htmlspecialchars($estadoExamen) ?></h3>
                    </div>
                    <div class="card-icon text-primary">📝</div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card shadow-sm border-left-success">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted">Completados</h6>
                        <h3 class="fw-bold text-success"><?= htmlspecialchars($intentosCompletados) ?></h3>
                    </div>
                    <div class="card-icon text-success">✅</div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card shadow-sm border-left-warning">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted">Promedio</h6>
                        <h3 class="fw-bold text-warning"><?= $promedio ?>%</h3>
                    </div>
                    <div class="card-icon text-warning">📊</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Últimos exámenes -->
    <div class="card mt-5 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">Últimos exámenes realizados</h5>
        </div>
        <div class="card-body p-0">
            <table class="table mb-0 table-striped">
                <thead>
                    <tr>
                        <th>Examen</th>
                        <th>Fecha</th>
                        <th>Calificación</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($examenesRealizados) > 0): ?>
                        <?php foreach ($examenesRealizados as $examen): ?>
                            <tr>
                                <td><?= htmlspecialchars($examen['titulo']) ?></td>
                                <td><?= date('d/m/Y', strtotime($examen['fecha_fin'])) ?></td>
                                <td><?= $examen['nota'] ?>%</td>
                                <td>
                                    <?php
                                        $estado = strtolower($examen['estado_examen']);
                                        $badge = match($estado) {
                                            'aprobado' => 'bg-success',
                                            'reprobado' => 'bg-danger',
                                            default => 'bg-warning text-dark',
                                        };
                                    ?>
                                    <span class="badge <?= $badge ?>"><?= ucfirst($estado) ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center">No hay exámenes completados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Acceso directo a simulación -->
    <div class="text-end mt-4">
        <a href="politicas.php" class="btn btn-primary btn-lg">
            🚀 Comenzar simulación de examen
        </a>
    </div>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
