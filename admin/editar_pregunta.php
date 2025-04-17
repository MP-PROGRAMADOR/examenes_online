<?php
// =====================================
// ARCHIVO: editar_pregunta.php
// Descripción: Formulario para editar una pregunta y sus imágenes asociadas.
// =====================================

// Conexión a la base de datos
require_once '../config/conexion.php';

// =====================
// VALIDACIÓN DEL ID
// =====================
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('ID de pregunta no válido.');
}

$id = (int) $_GET['id'];

// =====================
// CONSULTA DE LA PREGUNTA
// =====================
try {
    $pdo = $pdo->getConexion(); 
    // Obtener datos de la pregunta
    $stmt = $pdo->prepare("SELECT * FROM preguntas WHERE id = ?");
    $stmt->execute([$id]);
    $pregunta = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pregunta) {
        die('La pregunta no existe.');
    }

    // Obtener imágenes asociadas
    $stmtImg = $pdo->prepare("SELECT * FROM imagenes_pregunta WHERE pregunta_id = ?");
    $stmtImg->execute([$id]);
    $imagenes = $stmtImg->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>

<!-- ============================== -->
<!-- HTML: FORMULARIO DE EDICIÓN -->
<!-- ============================== -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Pregunta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5 mb-5">
    <h4 class="mb-4">Editar Pregunta</h4>

    <form action="actualizar_pregunta.php" method="POST" enctype="multipart/form-data">
        <!-- ID oculto -->
        <input type="hidden" name="id" value="<?= htmlspecialchars($pregunta['id']) ?>">

        <!-- Texto de la pregunta -->
        <div class="mb-3">
            <label for="texto_pregunta" class="form-label">Texto de la pregunta</label>
            <textarea name="texto_pregunta" id="texto_pregunta" rows="4" class="form-control" required><?= htmlspecialchars($pregunta['texto_pregunta']) ?></textarea>
        </div>

        <!-- Tipo de pregunta -->
        <div class="mb-3">
            <label for="tipo_pregunta" class="form-label">Tipo de pregunta</label>
            <select name="tipo_pregunta" id="tipo_pregunta" class="form-select" required>
                <option value="multiple_choice" <?= $pregunta['tipo_pregunta'] === 'multiple_choice' ? 'selected' : '' ?>>Opción múltiple</option>
                <option value="respuesta_unica" <?= $pregunta['tipo_pregunta'] === 'respuesta_unica' ? 'selected' : '' ?>>Respuesta única</option>
                <option value="verdadero_falso" <?= $pregunta['tipo_pregunta'] === 'verdadero_falso' ? 'selected' : '' ?>>Verdadero / Falso</option>
            </select>
        </div>

        <!-- Tipo de contenido -->
        <div class="mb-3">
            <label for="tipo_contenido" class="form-label">Tipo de contenido</label>
            <select name="tipo_contenido" id="tipo_contenido" class="form-select" required>
                <option value="texto" <?= $pregunta['tipo_contenido'] === 'texto' ? 'selected' : '' ?>>Solo texto</option>
                <option value="imagen" <?= $pregunta['tipo_contenido'] === 'imagen' ? 'selected' : '' ?>>Con ilustración</option>
            </select>
        </div>

        <!-- Imágenes existentes -->
        <?php if (!empty($imagenes)): ?>
            <div class="mb-3">
                <label class="form-label">Imágenes actuales</label>
                <div class="d-flex flex-wrap gap-3">
                    <?php foreach ($imagenes as $img): ?>
                        <div class="border p-2 text-center">
                            <img src="<?= htmlspecialchars($img['ruta_imagen']) ?>" alt="Imagen" class="img-thumbnail mb-2" style="max-width: 100px;">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="eliminar_imagenes[]" value="<?= $img['id'] ?>" id="img<?= $img['id'] ?>">
                                <label class="form-check-label text-danger small" for="img<?= $img['id'] ?>">Eliminar</label>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Nuevas imágenes -->
        <div class="mb-4">
            <label for="nuevas_imagenes" class="form-label">Agregar nuevas imágenes (opcional)</label>
            <input type="file" name="nuevas_imagenes[]" id="nuevas_imagenes" class="form-control" multiple accept="image/*">
        </div>

        <!-- Botones -->
        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="listar_preguntas.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

</body>
</html>
