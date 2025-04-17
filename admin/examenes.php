<?php
require '../config/conexion.php';

$conn = $pdo->getConexion();
$mensaje = $_GET['mensaje'] ?? '';
$examenes = [];
$alerta = null;

if (!empty($mensaje)) {
    $mensaje = htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8');

    if ($mensaje === 'exito') {
        $alerta = ['tipo' => 'success', 'mensaje' => 'Operación realizada con éxito.'];
    } elseif ($mensaje === 'error') {
        $alerta = ['tipo' => 'danger', 'mensaje' => 'Ha ocurrido un error, por favor intente nuevamente.'];
    } else {
        $alerta = ['tipo' => 'warning', 'mensaje' => 'Mensaje desconocido recibido.'];
    }
}

try {
    $sql = "SELECT
                e.id,
                e.titulo,
                e.descripcion,
                e.duracion_minutos, 
                e.fecha_creacion,
                cc.nombre AS categoria_nombre
            FROM examenes e
            INNER JOIN categorias_carne cc ON e.categoria_carne_id = cc.id
            ORDER BY e.fecha_creacion DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $examenes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error al listar exámenes: " . $e->getMessage());
    $alerta = ['tipo' => 'danger', 'mensaje' => 'Error al cargar la lista de exámenes.'];
}

include '../componentes/head_admin.php';
include '../componentes/menu_admin.php';
?>

<div class="main-content">
    <?php if ($alerta): ?>
        <div class="modal fade show" id="alertModal" tabindex="-1" aria-hidden="false" style="display: block;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-<?= $alerta['tipo'] ?>">
                        <h5 class="modal-title text-white"><?= ucfirst($alerta['tipo']) ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center">
                        <?= $alerta['mensaje'] ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="container-fluid mt-5">
        <div class="card shadow-sm border-0 rounded-4">
            <div
                class="card-header bg-primary text-white d-flex flex-wrap justify-content-between align-items-center rounded-top-4 px-4 py-3">
                <h5 class="mb-0"><i class="bi bi-clipboard2-check-fill me-2"></i>Listado de Exámenes</h5>
                <div class="search-box position-relative">
                    <input type="text" class="form-control ps-5" id="customSearch" placeholder="Buscar examen...">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                </div>
                <div class="d-flex flex-wrap gap-4 align-items-center">
                    <div class="d-flex align-items-center">
                        <label for="container-length" class="me-2 text-white fw-medium mb-0"><i
                                class="bi bi-list-ul me-1"></i>Mostrar:</label>
                        <select id="container-length" class="form-select w-auto shadow-sm">
                            <option value="5">5 registros</option>
                            <option value="10" selected>10 registros</option>
                            <option value="15">15 registros</option>
                            <option value="20">20 registros</option>
                            <option value="25">25 registros</option>
                        </select>
                    </div>
                    <a href="registrar_examenes.php" class="btn btn-light fw-semibold shadow-sm">
                        <i class="bi bi-plus-circle me-2"></i>Crear Nuevo
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="container-table" class="table table-striped table-hover align-middle">
                        <thead class="table-light">
                            <?php if (!empty($examenes)): ?>
                                <tr>
                                    <th><i class="bi bi-hash me-1"></i>ID</th>
                                    <th><i class="bi bi-card-text me-1"></i>Título</th>
                                    <th><i class="bi bi-tags me-1"></i>Categoría</th>
                                    <th><i class="bi bi-clock me-1"></i>Duración</th>
                                    <th><i class="bi bi-text-paragraph me-1"></i>Descripción</th>
                                    <th><i class="bi bi-calendar3 me-1"></i>Creado</th>
                                    <th><i class="bi bi-gear-fill me-1"></i>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($examenes as $examen): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($examen['id']) ?></td>
                                        <td><?= htmlspecialchars($examen['titulo']) ?></td>
                                        <td><?= htmlspecialchars($examen['categoria_nombre']) ?></td>
                                        <td><?= htmlspecialchars($examen['duracion_minutos']) ?> min</td>
                                        <td><?= htmlspecialchars($examen['descripcion']) ?></td>
                                        <td><?= date('d/m/Y', strtotime($examen['fecha_creacion'])) ?></td>
                                        <td>
                                            <!-- Contenedor responsive para los botones -->
                                            <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-md-start">
                                                <a href="preguntas_por_examen.php?id=<?= $examen['id'] ?>"
                                                    class="btn btn-sm btn-outline-primary" title="Ver">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="clonar_preguntas.php?examen_origen=<?= $examen['id'] ?>"
                                                    class="btn btn-sm btn-secondary">
                                                    <i class="bi bi-files"></i> Clonar preguntas
                                                </a>
                                                <a href="editar_examen.php?id=<?= $examen['id'] ?>"
                                                    class="btn btn-sm btn-outline-warning" title="Editar">
                                                    <i class="bi bi-pencil-fill"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                    onclick="confirmarEliminar(<?= $examen['id'] ?>, '<?= addslashes(htmlspecialchars($examen['titulo'])) ?>')"
                                                    title="Eliminar">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </div>

                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <div class="alert alert-warning mb-0">
                                            <i class="bi bi-exclamation-circle-fill me-2"></i>No hay exámenes registrados
                                            actualmente.
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación -->
<div class="modal fade" id="confirmarEliminarModal" tabindex="-1" aria-labelledby="confirmarEliminarModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-header bg-danger text-white rounded-top-4">
                <h5 class="modal-title"><i class="bi bi-exclamation-octagon-fill me-2"></i>Confirmar Eliminación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                ¿Está seguro de que desea eliminar el examen "<span id="nombre-examen-eliminar"
                    class="fw-bold text-danger"></span>"?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Cancelar
                </button>
                <a href="#" id="enlace-eliminar" class="btn btn-danger">
                    <i class="bi bi-trash-fill me-1"></i>Eliminar
                </a>
            </div>
        </div>
    </div>
</div>

<?php include_once('../componentes/footer.php'); ?>