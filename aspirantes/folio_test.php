<?php
require_once '../config/conexion.php';
$pdo=$pdo->getConexion();

 
// Validación del examen
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de examen inválido.");
}
$examen_id = intval($_GET['id']);

// Obtener datos del examen
$stmt = $pdo->prepare("SELECT * FROM examenes WHERE id = ?");
$stmt->execute([$examen_id]);
$examen = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$examen) {
    die("Examen no encontrado.");
}

// Obtener preguntas del examen
$order = $examen['preguntas_aleatorias'] ? 'RAND()' : 'id ASC';
$stmt = $pdo->prepare("SELECT * FROM preguntas WHERE examen_id = ? ORDER BY $order");
$stmt->execute([$examen_id]);
$preguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener opciones por pregunta
$opciones_por_pregunta = [];
foreach ($preguntas as $pregunta) {
    $stmt = $pdo->prepare("SELECT * FROM opciones_pregunta WHERE pregunta_id = ?");
    $stmt->execute([$pregunta['id']]);
    $opciones_por_pregunta[$pregunta['id']] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Simulación: <?= htmlspecialchars($examen['titulo']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .opcion:hover { background-color: #f0f0f0; cursor: pointer; }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>🧪 Simulación: <?= htmlspecialchars($examen['titulo']) ?></h3>
        <div class="fs-5 text-danger">
            Tiempo restante: <span id="timer"></span>
        </div>
    </div>

    <form id="form-examen" action="procesar_respuestas.php" method="POST">
        <input type="hidden" name="examen_id" value="<?= $examen_id ?>">

        <?php foreach ($preguntas as $index => $pregunta): ?>
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-light fw-semibold">
                    <?= ($index + 1) ?>. <?= htmlspecialchars($pregunta['texto_pregunta']) ?>
                    <span class="badge bg-info text-dark ms-2"><?= strtoupper($pregunta['tipo_pregunta']) ?></span>
                </div>

                <?php
                // Mostrar imágenes si hay
                $stmt_img = $pdo->prepare("SELECT ruta_imagen FROM imagenes_pregunta WHERE pregunta_id = ?");
                $stmt_img->execute([$pregunta['id']]);
                $imagenes = $stmt_img->fetchAll(PDO::FETCH_ASSOC);
                ?>

                <?php if ($imagenes): ?>
                    <div class="p-3">
                        <?php foreach ($imagenes as $img): ?>
                            <img src="<?= htmlspecialchars($img['ruta_imagen']) ?>" class="img-fluid mb-2" style="max-height:200px;">
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="card-body">
                    <?php foreach ($opciones_por_pregunta[$pregunta['id']] as $opcion): ?>
                        <div class="form-check mb-2">
                            <?php if ($pregunta['tipo_pregunta'] === 'unica'): ?>
                                <input class="form-check-input" type="radio" name="respuestas[<?= $pregunta['id'] ?>][]"
                                    value="<?= $opcion['id'] ?>" id="opcion<?= $opcion['id'] ?>">
                            <?php elseif ($pregunta['tipo_pregunta'] === 'multiple'): ?>
                                <input class="form-check-input" type="checkbox" name="respuestas[<?= $pregunta['id'] ?>][]"
                                    value="<?= $opcion['id'] ?>" id="opcion<?= $opcion['id'] ?>">
                            <?php elseif ($pregunta['tipo_pregunta'] === 'vf'): ?>
                                <input class="form-check-input" type="radio" name="respuestas[<?= $pregunta['id'] ?>][]"
                                    value="<?= $opcion['id'] ?>" id="opcion<?= $opcion['id'] ?>">
                            <?php endif; ?>
                            <label class="form-check-label" for="opcion<?= $opcion['id'] ?>">
                                <?= htmlspecialchars($opcion['texto_opcion']) ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="text-end">
            <button type="submit" class="btn btn-success btn-lg">
                <i class="bi bi-send-check-fill"></i> Finalizar examen
            </button>
        </div>
    </form>
</div>

<!-- Timer Script -->
<script>
    let minutos = <?= $examen['duracion_minutos'] ?>;
    let tiempo = minutos * 60;

    function updateTimer() {
        const minutos = Math.floor(tiempo / 60);
        const segundos = tiempo % 60;
        document.getElementById("timer").textContent =
            `${minutos}:${segundos < 10 ? '0' : ''}${segundos}`;
        if (tiempo > 0) {
            tiempo--;
        } else {
            clearInterval(timerInterval);
            alert("⏰ ¡Tiempo finalizado! Se enviará el examen automáticamente.");
            document.getElementById("form-examen").submit();
        }
    }

    const timerInterval = setInterval(updateTimer, 1000);
    updateTimer();
</script>
</body>
</html>
