<?php
require '../config/conexion.php';
$conexion = $pdo->getConexion();

// Obtener el ID del examen
$examen_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($examen_id <= 0) {
    header('Location: error.php?mensaje=Examen no válido');
    exit;
}

// Obtener datos del examen
$stmt = $conexion->prepare("SELECT id, titulo FROM examenes WHERE id = ?");
$stmt->execute([$examen_id]);
$examen = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$examen) {
    header('Location: error.php?mensaje=Examen no encontrado');
    exit;
}

// Obtener preguntas asociadas con sus imágenes y opciones
$stmt = $conexion->prepare("SELECT p.id AS pregunta_id, p.texto_pregunta, p.tipo_contenido, p.tipo_pregunta, 
           i.ruta_imagen, o.id AS opcion_id, o.texto_opcion, o.es_correcta
    FROM preguntas p
    LEFT JOIN imagenes_pregunta i ON i.pregunta_id = p.id
    LEFT JOIN opciones_pregunta o ON o.pregunta_id = p.id
    WHERE p.examen_id = ? 
    ORDER BY p.fecha_creacion DESC");
$stmt->execute([$examen_id]);
$preguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Agrupar imágenes y opciones por pregunta
$preguntasAgrupadas = [];
foreach ($preguntas as $pregunta) {
    $pregunta_id = $pregunta['pregunta_id'];
    if (!isset($preguntasAgrupadas[$pregunta_id])) {
        $preguntasAgrupadas[$pregunta_id] = [
            'id' => $pregunta['pregunta_id'],
            'texto_pregunta' => $pregunta['texto_pregunta'],
            'tipo_contenido' => $pregunta['tipo_contenido'],
            'tipo_pregunta' => $pregunta['tipo_pregunta'],
            'imagenes' => [],
            'opciones' => []
        ];
    }

    if ($pregunta['ruta_imagen']) {
        $preguntasAgrupadas[$pregunta_id]['imagenes'][] = $pregunta['ruta_imagen'];
    }

    if ($pregunta['opcion_id']) {
        $preguntasAgrupadas[$pregunta_id]['opciones'][] = [
            'texto_opcion' => $pregunta['texto_opcion'],
            'es_correcta' => $pregunta['es_correcta']
        ];
    }
}

include '../componentes/head_admin.php';
include '../componentes/menu_admin.php';
?>

<div class="main-content">
 <!-- Botón flotante de volver -->
<a href="examenes.php" class="btn btn-secondary btn-volver-fixed">
    <i class="bi bi-arrow-left-circle"></i> Volver
</a>


    <h2 class="mb-4 text-dark">Preguntas del Examen: <strong><?= htmlspecialchars($examen['titulo']) ?></strong></h2>

    <a href="registrar_preguntas.php?examen_id=<?= $examen_id ?>" class="btn btn-primary mb-4">
        <i class="bi bi-plus-circle"></i> Agregar Nueva Pregunta
    </a>

    <?php if (empty($preguntasAgrupadas)): ?>
        <div class="alert alert-info">
            Este examen no tiene preguntas registradas.
        </div>
    <?php else: ?>
        <?php foreach ($preguntasAgrupadas as $pregunta): ?>
            <div class="card mb-3 shadow-sm rounded border-light">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <strong>Pregunta #<?= $pregunta['id'] ?></strong>
                    <div>
                        <button class="btn btn-danger btn-sm" onclick="confirmarEliminacion(<?= $pregunta['id'] ?>)">
                            <i class="bi bi-x-circle"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Título de la pregunta con fondo más visible -->
                    <h5 class="card-title text-dark"><i class="bi bi-question-circle"></i> Texto de la pregunta:</h5>
                    <p class="mb-3 p-3 rounded" style="background-color: rgba(0, 0, 0, 0.05);">
                        <?= htmlspecialchars($pregunta['texto_pregunta']) ?: '<em>No disponible</em>' ?>
                    </p>

                    <!-- Mostrar imágenes asociadas a la pregunta -->
                    <?php if (!empty($pregunta['imagenes'])): ?>
                        <div class="mb-3">
                            <strong>Imágenes asociadas:</strong><br>
                            <div class="row">
                                <?php foreach ($pregunta['imagenes'] as $img): ?>
                                    <div class="col-12 col-md-4 mb-3">
                                        <img src="<?= htmlspecialchars($img) ?>" class="img-fluid rounded shadow-sm" alt="Imagen">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Mostrar las opciones de la pregunta -->
                    <?php if (!empty($pregunta['opciones'])): ?>
                        <h6 class="text-dark"><i class="bi bi-list-ul"></i> Opciones:</h6>
                        <ul class="list-group mb-3">
                            <?php foreach ($pregunta['opciones'] as $op): ?>
                                <li class="list-group-item <?= $op['es_correcta'] ? 'list-group-item-success' : '' ?>">
                                    <?= htmlspecialchars($op['texto_opcion']) ?>
                                    <?= $op['es_correcta'] ? '<span class="badge bg-success float-end"><i class="bi bi-check-circle"></i> Correcta</span>' : '' ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <!-- Pregunta tipo Verdadero/Falso -->
                    <?php if ($pregunta['tipo_pregunta'] === 'vf'): ?>
                        <hr>
                        <h6 class="text-dark">
                            <i class="bi bi-shield-check"></i> Respuesta Verdadero/Falso:
                        </h6>
                        <?php
                            $stmt_vf = $conexion->prepare("SELECT COUNT(*) FROM opciones_pregunta WHERE pregunta_id = ? AND es_correcta = 1");
                            $stmt_vf->execute([$pregunta['id']]);
                            $es_verdadero = $stmt_vf->fetchColumn() > 0;
                        ?>
                        <p class="lead">
                            <?= $es_verdadero
                                ? '<span class="text-success"><i class="bi bi-check-circle-fill"></i> Verdadero</span>'
                                : '<span class="text-danger"><i class="bi bi-x-circle-fill"></i> Falso</span>' ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
    function confirmarEliminacion(id) {
        if (confirm('¿Estás seguro de que deseas eliminar esta pregunta?')) {
            window.location.href = 'eliminar_pregunta.php?id=' + id;
        }
    }
</script>
<?php include_once('../componentes/footer.php'); ?>

