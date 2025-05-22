<?php
include_once("includes/header.php");
require '../includes/conexion.php';

 

// ----------------------------------------
// Validar aqui si hay sesión activa
// ---------------------------------------- 

$estudiante = $_SESSION['estudiante'];
$estudiante_id = $estudiante['id'];

// Inicialización de variables
$estadoExamen = null;
$intentosCompletados = 0;
$promedio = 0;
$examenesRealizados = [];
$accesoExamen = 0;
$alerta = null;

try { 
    // Obtener el último examen del estudiante
    $stmtEstado = $pdo->prepare("SELECT 
                                    ee.estado, 
                                    ee.acceso_habilitado, 
                                    ee.calificacion,
                                    ee.fecha_realizacion,
                                    ee.fecha_proximo_intento,
                                    ee.total_preguntas,
                                    ee.intentos_examen,
                                    cc.nombre AS nombre_categoria_carne,
                                    e.titulo AS nombre_examen 
                                FROM examenes_estudiantes ee
                                LEFT JOIN examenes e ON e.categoria_carne_id = ee.categoria_carne_id
                                LEFT JOIN categorias_carne cc ON cc.id = ee.categoria_carne_id
                                WHERE ee.estudiante_id = :id 
                                ORDER BY ee.id DESC  
                                ");
    $stmtEstado->execute(['id' => $estudiante_id]);
    $examen = $stmtEstado->fetch(PDO::FETCH_ASSOC);

    if ($examen) {
        $estadoExamen = $examen['estado'];
        $calificacionExamen = $examen['calificacion'];
        $accesoExamen = (int) $examen['acceso_habilitado'];
        $categoria = $examen['nombre_categoria_carne'];
        $nombre_examen = $examen['nombre_examen'];

        // Puedes usar también los demás campos si lo deseas
// Ejemplo:
// $intentosRestantes = (int)$examen['intentos_examen'];
    }


    /* -------- DAR PERMISOS SI LA FECHA ASPIRÓ ---------- */
    $ahora = date('Y-m-d H:i:s');
    $intento = $examen['fecha_proximo_intento'];

    // Si la fecha ya pasó, se habilita el acceso
    if ($intento <= $ahora) {
        $update = $pdo->prepare(" UPDATE 
            examenes_estudiantes 
            SET acceso_habilitado = 1, 
            intentos_examen = intentos_examen + 1 
            WHERE estudiante_id = :id
        ");
        $update->execute(['id' => $estudiante_id]);
    }


} catch (PDOException $e) {

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
                        <h6 class="text-muted">Calificacion</h6>
                        <h4 class="text-success fw-bold"><?= htmlspecialchars($estadoExamen) ?></h4>
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
                        <h4 class="text-warning fw-bold"><?= htmlspecialchars($calificacionExamen) ?>%</h4>
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
                            <th>Tipo Carné</th>
                            <th>Examen</th>
                            <th>total preguntas</th>
                            <th>Fecha realizado</th>
                            <?php if (strtolower($examen['estado']) == 'reprobado'): ?>
                                <th>Fecha proximo intento</th>
                            <?php endif; ?>
                            <th>Calificación</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($examen)): ?>

                            <tr>
                                <td>Nivel <?= htmlspecialchars($examen['nombre_categoria_carne']) ?></td>
                                <td><?= htmlspecialchars($examen['nombre_examen']) ?></td>
                                <td><?= intval($examen['total_preguntas']) ?></td>
                                <td><?= date('d/m/Y', strtotime($examen['fecha_realizacion'])) ?></td>
                                <?php if (strtolower($examen['estado']) == 'reprobado'): ?>
                                    <td>
                                        <span class="contador-intento"
                                            data-fecha="<?= date('Y-m-d H:i:s', strtotime($examen['fecha_proximo_intento'])) ?>">
                                            Calculando...
                                        </span>
                                    </td>
                                <?php endif; ?>
                                <td><?= intval($examen['calificacion']) ?>%</td>
                                <td>
                                    <?php
                                    $estado = strtolower($examen['estado']);
                                    $badge = match ($estado) {
                                        'aprobado' => 'bg-success',
                                        'reprobado' => 'bg-danger',
                                        default => 'bg-secondary'
                                    };
                                    ?>
                                    <span class="badge <?= $badge ?>"><?= ucfirst($estado) ?></span>
                                </td>
                            </tr>

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

<script>
function iniciarCuentaRegresiva() {
    const elementos = document.querySelectorAll('.contador-intento');

    elementos.forEach(el => {
        const fechaObjetivo = new Date(el.dataset.fecha).getTime();

        function actualizarContador() {
            const ahora = new Date().getTime();
            const diferencia = fechaObjetivo - ahora;

            if (diferencia <= 0) {
                el.textContent = "¡Ya puedes volver a intentarlo!";
                return;
            }

            const dias = Math.floor(diferencia / (1000 * 60 * 60 * 24));
            const horas = Math.floor((diferencia % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutos = Math.floor((diferencia % (1000 * 60 * 60)) / (1000 * 60));
            const segundos = Math.floor((diferencia % (1000 * 60)) / 1000);

            el.textContent = `${dias}d ${horas}h ${minutos}m ${segundos}s`;

            setTimeout(actualizarContador, 1000);
        }

        actualizarContador();
    });
}

document.addEventListener('DOMContentLoaded', iniciarCuentaRegresiva);
</script>

</body>