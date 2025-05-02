<?php
session_start();

// Verificar si se proporcionó un ID de pregunta
$id_pregunta = isset($_GET['id']) ? intval($_GET['id']) : null;
if (!$id_pregunta) {
    $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'ID de pregunta no proporcionado.'];
    header("Location: preguntas.php");
    exit;
}

include '../componentes/head_admin.php';
include '../componentes/menu_admin.php';
include '../config/conexion.php';
$conn = $pdo->getConexion();

try {
    // Obtener examenes
    $stmt = $conn->prepare("SELECT id, titulo FROM examenes");
    $stmt->execute();
    $examenes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Obtener la pregunta
    $stmt = $conn->prepare("SELECT * FROM preguntas WHERE id = ?");
    $stmt->execute([$id_pregunta]);
    $pregunta = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pregunta) {
        $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'La pregunta no existe.'];
        header("Location: preguntas.php");
        exit;
    }

    // Obtener imágenes
    $stmt = $conn->prepare("SELECT ruta_imagen FROM imagenes_pregunta WHERE pregunta_id = ?");
    $stmt->execute([$id_pregunta]);
    $imagenes = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Obtener opciones
    $stmt = $conn->prepare("SELECT texto_opcion, es_correcta FROM opciones_pregunta WHERE pregunta_id = ?");
    $stmt->execute([$id_pregunta]);
    $opciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'Error al cargar datos: ' . $e->getMessage()];
    header("Location: preguntas.php");
    exit;
}
?>

<div class="main-content">
    <div class="container-fluid mt-5 pt-2">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="card shadow rounded-4 p-4">
                    <div class="card-header bg-warning text-dark rounded-3 mb-4">
                        <h4 class="mb-0 d-flex align-items-center">
                            <i class="bi bi-pencil-square me-2 fs-4"></i>
                            Editar Pregunta de Examen
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="../php/actualizar_pregunta.php" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                            <input type="hidden" name="pregunta_id" value="<?= $pregunta['id']; ?>">

                            <!-- Examen -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Examen <span class="text-danger">*</span></label>
                                <select name="examen_id" class="form-select" required>
                                    <option value="">Seleccione...</option>
                                    <?php foreach ($examenes as $exam): ?>
                                        <option value="<?= $exam['id']; ?>" <?= $exam['id'] == $pregunta['examen_id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($exam['titulo']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Tipo de contenido -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Tipo de contenido</label>
                                <select name="tipo_contenido" class="form-select">
                                    <option value="">Seleccione...</option>
                                    <option value="texto" <?= $pregunta['tipo_contenido'] === 'texto' ? 'selected' : '' ?>>Solo texto</option>
                                    <option value="ilustracion" <?= $pregunta['tipo_contenido'] === 'ilustracion' ? 'selected' : '' ?>>Con ilustración</option>
                                </select>
                            </div>

                            <!-- Texto de la Pregunta -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Texto de la Pregunta</label>
                                <textarea name="texto_pregunta" class="form-control" rows="3" required><?= htmlspecialchars($pregunta['texto_pregunta']); ?></textarea>
                            </div>

                            <!-- Imágenes existentes -->
                            <?php if (!empty($imagenes)): ?>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Imágenes actuales</label>
                                    <?php foreach ($imagenes as $img): ?>
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control" value="<?= htmlspecialchars($img); ?>" readonly>
                                            <button type="button" class="btn btn-danger btn-remover-img">
                                                <i class="bi bi-x-circle-fill"></i>
                                            </button>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <!-- Nuevas imágenes -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Agregar nuevas imágenes</label>
                                <input type="file" name="imagenes[]" class="form-control" accept="image/*" multiple>
                            </div>

                            <!-- Tipo de Respuesta -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Tipo de respuesta</label>
                                <select name="tipo_pregunta" class="form-select" required>
                                    <option value="">Seleccione...</option>
                                    <option value="multiple" <?= $pregunta['tipo_pregunta'] === 'multiple' ? 'selected' : '' ?>>Opción múltiple</option>
                                    <option value="unica" <?= $pregunta['tipo_pregunta'] === 'unica' ? 'selected' : '' ?>>Única respuesta</option>
                                    <option value="vf" <?= $pregunta['tipo_pregunta'] === 'vf' ? 'selected' : '' ?>>Verdadero o Falso</option>
                                </select>
                            </div>

                            <!-- Opciones -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Opciones</label>
                                <?php foreach ($opciones as $op): ?>
                                    <div class="input-group mb-2">
                                        <input type="text" name="opciones[]" class="form-control" value="<?= htmlspecialchars($op['texto_opcion']); ?>" required>
                                        <div class="input-group-text">
                                            <input class="form-check-input mt-0" type="<?= $pregunta['tipo_pregunta'] === 'multiple' ? 'checkbox' : 'radio' ?>"
                                                   name="correctas[]" <?= $op['es_correcta'] ? 'checked' : '' ?>>
                                        </div>
                                        <button type="button" class="btn btn-danger btn-remover-opcion">
                                            <i class="bi bi-x-circle-fill"></i>
                                        </button>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <!-- Botones -->
                            <div class="d-flex justify-content-between">
                                <a href="preguntas.php" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left-circle"></i> Volver
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save2-fill"></i> Guardar Cambios
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once('../componentes/footer.php'); ?>
