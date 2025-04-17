<?php
require '../config/conexion.php';

$pdo = $pdo->getConexion();

// Validación del ID de la pregunta
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de pregunta inválido.");
}

$pregunta_id = intval($_GET['id']);

try {
    // Consulta principal de la pregunta
    $sql_pregunta = "SELECT * FROM preguntas WHERE id = ?";
    $stmt = $pdo->prepare($sql_pregunta);
    $stmt->execute([$pregunta_id]);
    $pregunta = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pregunta) {
        die("Pregunta no encontrada.");
    }

    // Consulta de imágenes asociadas
    $sql_imagenes = "SELECT * FROM imagenes_pregunta WHERE pregunta_id = ?";
    $stmt_img = $pdo->prepare($sql_imagenes);
    $stmt_img->execute([$pregunta_id]);
    $imagenes = $stmt_img->fetchAll(PDO::FETCH_ASSOC);

    // Consulta de opciones asociadas
    $sql_opciones = "SELECT * FROM opciones_pregunta WHERE pregunta_id = ?";
    $stmt_op = $pdo->prepare($sql_opciones);
    $stmt_op->execute([$pregunta_id]);
    $opciones = $stmt_op->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error al obtener los datos: " . $e->getMessage());
}

include_once('../componentes/head_admin.php');
include_once('../componentes/menu_admin.php');
?>

<!-- Container Principal -->
<div class="main-content">

    <div class="card shadow-lg border-light">
        <!-- Encabezado con tema del contenido -->
        <div class="card-header bg-primary text-white text-center">
            <h2 class="mb-0"><i class="bi bi-file-earmark-text"></i> Detalles de la Pregunta</h2>
        </div>

        <div class="card-body">
            <!-- Información de la Pregunta -->
            <h5 class="card-title text-primary"><i class="bi bi-question-circle"></i> Texto de la Pregunta</h5>
            <div class="bg-light p-3 rounded mb-4" style="background-color: #f8f9fa;">
                <p class="lead text-center"><?= htmlspecialchars($pregunta['texto_pregunta']) ?: '<em>Pregunta sin texto</em>' ?></p>
            </div>

            <hr class="my-4">

            <!-- Información adicional -->
            <p><strong><i class="bi bi-info-circle"></i> Tipo de contenido:</strong>
                <?= ucfirst($pregunta['tipo_contenido']) ?></p>
            <p><strong><i class="bi bi-tags"></i> Tipo de pregunta:</strong> <?= ucfirst($pregunta['tipo_pregunta']) ?></p>
            <p><strong><i class="bi bi-calendar-check"></i> Fecha de creación:</strong>
                <?= date('d-m-Y', strtotime($pregunta['fecha_creacion'])) ?></p>

            <!-- Ilustraciones asociadas -->
            <?php if (!empty($imagenes)): ?>
                <hr>
                <h6 class="text-success"><i class="bi bi-images"></i> Ilustraciones Asociadas:</h6>
                <div class="row">
                    <?php foreach ($imagenes as $img): ?>
                        <div class="col-md-4 mb-3">
                            <img src="<?= htmlspecialchars($img['ruta_imagen']) ?>" alt="Imagen de la pregunta" class="img-fluid rounded shadow-sm">
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Opciones -->
            <?php if (!empty($opciones)): ?>
                <hr>
                <h6 class="text-info"><i class="bi bi-list-ol"></i> Opciones:</h6>
                <ul class="list-group list-group-flush">
                    <?php foreach ($opciones as $op): ?>
                        <li class="list-group-item <?= $op['es_correcta'] ? 'list-group-item-success' : '' ?>">
                            <?= htmlspecialchars($op['texto_opcion']) ?>
                            <?php if ($op['es_correcta']): ?>
                                <span class="badge bg-success float-end"><i class="bi bi-check-circle"></i> Correcta</span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p><em>Sin opciones registradas.</em></p>

                <?php if ($pregunta['tipo_pregunta'] === 'vf'): ?>
                    <?php
                        // Verifica si alguna opción era verdadera (es_correcta = 1)
                        $stmt_vf = $pdo->prepare("SELECT COUNT(*) FROM opciones_pregunta WHERE pregunta_id = ? AND es_correcta = 1");
                        $stmt_vf->execute([$pregunta_id]);
                        $es_verdadero = $stmt_vf->fetchColumn() > 0;
                    ?>
                    <hr>
                    <h6 class="text-info">
                        <i class="bi bi-shield-check" data-bs-toggle="tooltip" data-bs-placement="top" title="Este tipo de pregunta es Verdadero o Falso"></i>
                        Respuesta Verdadero/Falso:
                    </h6>
                    <p class="lead">
                        <?= $es_verdadero
                            ? '<span class="text-success"><i class="bi bi-check-circle-fill"></i> Verdadero</span>'
                            : '<span class="text-danger"><i class="bi bi-x-circle-fill"></i> Falso</span>' ?>
                    </p>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <div class="card-footer d-flex justify-content-between">
            <a href="preguntas.php" class="btn btn-outline-secondary px-4 py-2"><i class="bi bi-arrow-left-circle"></i> Volver al Listado</a>
            <a href="editar_pregunta.php?id=<?= $pregunta_id ?>" class="btn btn-outline-primary px-4 py-2"><i class="bi bi-pencil-square"></i> Editar Pregunta</a>
        </div>

    </div>

</div>

<!-- JS -->
<script src="../assets/js/modal_alerta.js"></script>
<script>
    // Inicializar tooltips de Bootstrap
    document.addEventListener('DOMContentLoaded', function () {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>

<?php include_once('../componentes/footer.php'); ?>
