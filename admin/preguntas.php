<?php
session_start();
// Recuperamos el mensaje de alerta de la sesión si existe
$alerta = isset($_SESSION['alerta']) ? $_SESSION['alerta'] : null;
unset($_SESSION['alerta']); // Limpiar la sesión después de usar el mensaje

require '../config/conexion.php';
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
<?php include_once('../componentes/head_admin.php'); ?>
<?php include_once('../componentes/menu_admin.php'); ?>

<div class="main-content">
    <!-- Modal de Alerta -->
    <?php if ($alerta): ?>
        <div class="modal fade show" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="false"
            style="display: block;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header <?php echo $alerta['tipo'] == 'success' ? 'bg-success' : 'bg-danger'; ?>">
                        <h5 class="modal-title text-white" id="alertModalLabel">
                            <?php echo $alerta['tipo'] == 'success' ? '¡Éxito!' : 'Error'; ?>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-center"><?php echo $alerta['mensaje']; ?></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="container-fluid mt-5">
        <div class="card shadow border-0 rounded-4">
            <div
                class="card-header d-flex flex-wrap justify-content-between align-items-center rounded-top-4 px-4 py-3 bg-white border-bottom">
                <h5 class="mb-0 fw-semibold">
                    <i class="bi bi-question-circle-fill me-2 text-primary"></i>Listado de preguntas
                </h5>
                <div class="search-box position-relative">
                    <input type="text" class="form-control ps-5" id="customSearch" placeholder="Buscar pregunta...">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                </div>
                <div class="d-flex flex-wrap gap-5 align-items-center">
                    <div class="d-flex align-items-center">
                        <label for="container-length" class="me-2 fw-medium mb-0">
                            <i class="bi bi-eye-fill me-1"></i>Mostrar:
                        </label>
                        <select id="container-length" class="form-select w-auto shadow-sm">
                            <option value="5">5 registros</option>
                            <option value="10" selected>10 registros</option>
                            <option value="15">15 registros</option>
                            <option value="20">20 registros</option>
                            <option value="25">25 registros</option>
                        </select>
                    </div>
                    <a href="registrar_preguntas.php" class="btn btn-primary fw-semibold shadow-sm">
                        <i class="bi bi-plus-circle me-2"></i>Crear Nuevo
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="container-table" class="table table-striped table-hover align-middle">
                        <thead class="table-light">
                            <?php if (!empty($preguntas)): ?>
                                <tr>
                                    <th><i class="bi bi-hash me-1"></i>ID</th>
                                    <th><i class="bi bi-journal-text me-1"></i>Examen</th>
                                    <th><i class="bi bi-chat-left-dots me-1"></i>Pregunta</th>
                                    <th><i class="bi bi-ui-checks me-1"></i>Tipo</th>
                                    <th><i class="bi bi-file-earmark-image me-1"></i>Contenido</th>
                                    <th><i class="bi bi-calendar3 me-1"></i>Registro</th>
                                    <th><i class="bi bi-gear-fill me-1"></i>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($preguntas as $pregunta): ?>
                                    <tr>
                                        <td class="text-center"><?= htmlspecialchars($pregunta['id']) ?></td>
                                        <td><?= htmlspecialchars($pregunta['examen']) ?></td>
                                        <td>
                                            <?php if ($pregunta['tipo_contenido'] === 'imagen'): ?>
                                                <?php foreach ($pregunta['imagenes'] as $img): ?>
                                                    <img src="<?= htmlspecialchars($img) ?>" alt="img" class="img-thumbnail me-1"
                                                        style="width: 70px; height: auto;">
                                                <?php endforeach; ?>
                                                <div class="mt-2 text-muted small">
                                                    <i class="bi bi-card-text me-1"></i>
                                                    <?= nl2br(htmlspecialchars($pregunta['texto_pregunta'])) ?>
                                                </div>
                                            <?php else: ?>
                                                <i class="bi bi-card-text me-1"></i>
                                                <?= nl2br(htmlspecialchars($pregunta['texto_pregunta'])) ?>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $tipos = [
                                                'multiple' => 'Opción Múltiple',
                                                'unica' => 'Respuesta Única',
                                                'vf' => 'Verdadero / Falso'
                                            ];
                                            echo $tipos[$pregunta['tipo_pregunta']] ?? 'Desconocido';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($pregunta['tipo_contenido'] === 'ilustracion'): ?>
                                                <span class="badge badge-contenido badge-ilustracion"><i
                                                        class="bi bi-image me-1"></i>Ilustración</span>
                                            <?php else: ?>
                                                <span class="badge badge-contenido badge-texto"><i
                                                        class="bi bi-card-text me-1"></i>Texto</span>
                                            <?php endif; ?>

                                        </td>
                                        <td class="text-center">
                                            <i
                                                class="bi bi-clock me-1"></i><?= date('d/m/Y H:i', strtotime($pregunta['fecha_creacion'])) ?>
                                        </td>
                                        <td class="text-center dropdown-actions">
                                            <!-- Botones visibles en escritorio -->
                                            <a href="detalles_preguntas.php?id=<?= urlencode($pregunta['id']) ?>"
                                                class="btn btn-sm btn-outline-success me-1 d-none d-md-inline-flex">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="editar_pregunta.php?id=<?= urlencode($pregunta['id']) ?>"
                                                class="btn btn-sm btn-outline-primary me-1 d-none d-md-inline-flex">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="../php/eliminar_pregunta.php?id=<?= urlencode($pregunta['id']) ?>"
                                                class="btn btn-sm btn-outline-danger d-none d-md-inline-flex"
                                                onclick="return confirm('¿Estás seguro de eliminar esta pregunta? Esta acción no se puede deshacer.')">
                                                <i class="bi bi-trash"></i>
                                            </a>

                                            <!-- Dropdown en móviles -->
                                            <div class="dropdown d-md-none">
                                                <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="editar_pregunta.php?id=<?= urlencode($pregunta['id']) ?>">
                                                            <i class="bi bi-pencil-square me-2"></i>Editar
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item text-danger"
                                                            href="../php/eliminar_pregunta.php?id=<?= urlencode($pregunta['id']) ?>"
                                                            onclick="return confirm('¿Estás seguro de eliminar esta pregunta? Esta acción no se puede deshacer.')">
                                                            <i class="bi bi-trash-fill me-2"></i>Eliminar
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>

                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="alert alert-warning text-center mt-3">
                                    <i class="bi bi-exclamation-circle-fill me-2"></i>⚠️ No hay preguntas registradas
                                    actualmente.
                                </div>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

 

<?php include_once('../componentes/footer.php'); ?>