<?php
// Incluir la conexión a la base de datos
require '../config/conexion.php';

$conn = $pdo->getConexion();

// Inicializar la variable de alerta (por defecto estará vacía)
$alerta = null;

try {
    // Preparar y ejecutar la consulta para obtener las escuelas
    $sql = "SELECT id, nombre, telefono, direccion FROM escuelas_conduccion";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Obtener todas las escuelas
    $escuelas = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Manejo de errores: si ocurre un error en la consulta, se captura y se muestra un mensaje
    error_log("Error en la consulta: " . $e->getMessage());
    $alerta = ['tipo' => 'error', 'mensaje' => 'Ocurrió un error al recuperar las escuelas.'.$e->getMessage()];
}

include '../componentes/head_admin.php';
include '../componentes/menu_admin.php';
?>

<div class="main-content">
    <!-- Modal de Alerta -->
    <?php if ($alerta): ?>
        <div class="modal fade show" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="false"
            style="display: block;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Cabecera del Modal con color según el tipo de alerta -->
                    <div class="modal-header <?php echo $alerta['tipo'] == 'success' ? 'bg-success' : 'bg-danger'; ?>">
                        <h5 class="modal-title text-white" id="alertModalLabel">
                            <!-- Título según el tipo de alerta -->
                            <?php echo $alerta['tipo'] == 'success' ? '¡Éxito!' : 'Error'; ?>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Mensaje de la alerta -->
                        <p class="text-center"><?php echo htmlspecialchars($alerta['mensaje'], ENT_QUOTES, 'UTF-8'); ?></p>
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
            <div class="card-header bg-primary text-white d-flex flex-wrap justify-content-between align-items-center rounded-top-4 px-4 py-3">
                <h5 class="mb-0"><i class="bi bi-buildings-fill me-2"></i>Listado de Escuelas</h5>
                <div class="search-box position-relative">
                    <input type="text" class="form-control ps-5" id="customSearch" placeholder="Buscar escuela...">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                </div>
                <div class="d-flex flex-wrap gap-5 align-items-center">
                    <div class="d-flex align-items-center">
                        <label for="container-length" class="me-2 text-white fw-medium mb-0">Mostrar:</label>
                        <select id="container-length" class="form-select w-auto shadow-sm">
                            <option value="5">5 registros</option>
                            <option value="10" selected>10 registros</option>
                            <option value="15">15 registros</option>
                            <option value="20">20 registros</option>
                            <option value="25">25 registros</option>
                        </select>
                    </div>
                    <a href="registrar_escuelas.php" class="btn btn-light fw-semibold shadow-sm">
                        <i class="bi bi-plus-circle me-2"></i>Crear Nuevo
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table id="escuelas-table" class="table table-striped table-bordered">
                    <thead class="table-light">
                        <?php if (!empty($escuelas)): ?>
                            <tr>
                                <th><i class="bi bi-hash me-1 text-secondary"></i>ID</th>
                                <th><i class="bi bi-building me-1 text-secondary"></i>Nombre</th>
                                <th><i class="bi bi-geo-alt-fill me-1 text-secondary"></i>Dirección</th>
                                <th><i class="bi bi-telephone-fill me-1 text-secondary"></i>Teléfono</th>
                                <th><i class="bi bi-gear-fill me-1 text-secondary"></i>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($escuelas as $escuela): ?>
                                <tr>
                                    <td><?= htmlspecialchars($escuela['id'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($escuela['nombre'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($escuela['direccion'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($escuela['telefono'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td>
                                        <div class="d-none d-md-flex gap-2">
                                            <a href="editar_escuela.php?id=<?= htmlspecialchars($escuela['id']) ?>"
                                                class="btn btn-sm btn-outline-warning">
                                                <i class="bi bi-pencil-square"></i> Editar
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger btn-eliminar-escuela"
                                                data-id="<?= htmlspecialchars($escuela['id']) ?>"
                                                data-nombre="<?= htmlspecialchars($escuela['nombre']) ?>">
                                                <i class="bi bi-trash-fill"></i> Eliminar
                                            </button>
                                        </div>

                                        <!-- Dropdown para móviles -->
                                        <div class="dropdown d-md-none">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                                data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="editar_escuela.php?id=<?= htmlspecialchars($escuela['id']) ?>">
                                                        <i class="bi bi-pencil-square me-2 text-warning"></i>Editar
                                                    </a>
                                                </li>
                                                <li>
                                                    <button class="dropdown-item btn-eliminar-escuela"
                                                        data-id="<?= htmlspecialchars($escuela['id']) ?>"
                                                        data-nombre="<?= htmlspecialchars($escuela['nombre']) ?>">
                                                        <i class="bi bi-trash-fill me-2 text-danger"></i>Eliminar
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="alert alert-warning text-center">
                                <i class="bi bi-exclamation-circle-fill me-2"></i>⚠️ No hay escuelas registradas
                                actualmente.
                            </div>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmarEliminarModal" tabindex="-1" aria-labelledby="confirmarEliminarModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmarEliminarModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Está seguro de que desea eliminar la escuela <span id="nombre-escuela-eliminar"></span>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btn-confirmar-eliminar">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<?php include_once('../componentes/footer.php'); ?>
