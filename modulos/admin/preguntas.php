<?php
// Conexión a la base de datos
require '../../config/conexion.php';
$conn = $pdo->getConexion();

// Inicializar mensaje y array de preguntas
$mensaje = isset($_GET['mensaje']) ? $_GET['mensaje'] : '';
$preguntas = [];

try {
    // Consulta para obtener preguntas junto con su examen
    $sql = "
        SELECT 
            p.id,
            e.titulo AS examen,
            p.texto_pregunta,
            p.tipo_pregunta,
            p.tipo_contenido,
            p.fecha_creacion
        FROM preguntas p
        JOIN examenes e ON p.examen_id = e.id
        ORDER BY p.fecha_creacion DESC
    ";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $preguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Para preguntas con imagen: obtenemos imágenes relacionadas
    foreach ($preguntas as &$pregunta) {
        if ($pregunta['tipo_contenido'] === 'imagen') {
            $stmtImg = $conn->prepare("SELECT ruta_imagen FROM imagenes_pregunta WHERE pregunta_id = ?");
            $stmtImg->execute([$pregunta['id']]);
            $pregunta['imagenes'] = $stmtImg->fetchAll(PDO::FETCH_COLUMN);
        } else {
            $pregunta['imagenes'] = [];
        }
    }
} catch (PDOException $e) {
    error_log("Error al listar preguntas: " . $e->getMessage());
    $mensaje_error_listado = "Error al cargar la lista de preguntas.";
}
?>

<!DOCTYPE html>
<html lang="es">
<?php include '../componentes/head_admin.php'; ?>
<body>
<?php include '../componentes/menu_admin.php'; ?>

<div class="content">
    <div class="container-fluid py-5">
        <div class="row mb-4">
            <div class="col mt-5">
                <h2 class="text-center mb-0">📋 LISTA DE PREGUNTAS</h2>
            </div>
        </div>

        <div class="row justify-content-end mb-3">
            <div class="col-auto">
                <a href="registrar_preguntas.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Crear Nuevo
                </a>
            </div>
        </div>

        
        
        
        
        
        
        
        
        
        <?php if (empty($preguntas)): ?>
            <div class="alert alert-warning text-center">⚠️ No hay preguntas registradas actualmente.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                    
                    <!-- Añadimos esta columna en el encabezado -->
                    <thead class="table-dark text-center">
                        <tr>
                            <th>#ID</th>
                            <th>Examen</th>
                            <th>Pregunta</th>
                            <th>Tipo de Pregunta</th>
                            <th>Contenido</th>
                            <th>Fecha de Registro</th>
                            <th>Acciones</th> <!-- NUEVA COLUMNA -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($preguntas as $pregunta): ?>
                            <tr>
                                <td><?= htmlspecialchars($pregunta['id'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($pregunta['examen'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td>
                                    <?php if ($pregunta['tipo_contenido'] === 'imagen'): ?>
                                        <?php foreach ($pregunta['imagenes'] as $img): ?>
                                            <img src="<?= htmlspecialchars($img, ENT_QUOTES, 'UTF-8') ?>" alt="img" style="width: 80px; height: auto; margin: 2px; border-radius: 5px;">
                                        <?php endforeach; ?>
                                        <div class="mt-2 fw-bold text-secondary small">Texto: <?= nl2br(htmlspecialchars($pregunta['texto_pregunta'], ENT_QUOTES, 'UTF-8')) ?></div>
                                    <?php else: ?>
                                        <?= nl2br(htmlspecialchars($pregunta['texto_pregunta'], ENT_QUOTES, 'UTF-8')) ?>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php
                                    $tipos = [
                                        'multiple_choice' => 'Opción Múltiple',
                                        'respuesta_unica' => 'Respuesta Única',
                                        'verdadero_falso' => 'Verdadero / Falso'
                                    ];
                                    echo $tipos[$pregunta['tipo_pregunta']] ?? 'Desconocido';
                                    ?>
                                </td>
                                <td class="text-center">
                                    <?= $pregunta['tipo_contenido'] === 'imagen' ? '🖼️ Ilustración' : '📝 Texto' ?>
                                </td>
                                <td class="text-center">
                                    <?= date('d/m/Y H:i', strtotime($pregunta['fecha_creacion'])) ?>
                                </td>
                                <td class="text-center">
                                    <a href="editar_pregunta.php?id=<?= urlencode($pregunta['id']) ?>" class="btn btn-sm btn-warning me-1">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a href="../php/eliminar_pregunta.php?id=<?= urlencode($pregunta['id']) ?>" class="btn btn-sm btn-danger"
                                       onclick="return confirm('¿Estás seguro de eliminar esta pregunta? Esta acción no se puede deshacer.')">
                                        <i class="bi bi-trash-fill"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                     
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Scripts necesarios para tabla -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

<script>
    // Inicialización de la tabla con DataTables
    document.addEventListener('DOMContentLoaded', function () {
        $('.table').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
            }
        });
    });
</script>

</body>
</html>
