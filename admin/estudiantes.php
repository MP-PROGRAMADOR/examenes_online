<?php

require_once('../config/conexion.php');
$conn = $pdo->getConexion();

// Inicializamos la variable alerta como un array vacío (sin alerta)
$alerta = [];

try {
    // Conectar a la base de datos y ejecutar consulta con JOIN
    $sql = "  SELECT 
                    e.id, e.numero_identificacion, e.nombre, e.apellido, e.fecha_nacimiento, 
                    e.telefono, e.direccion, e.categoria_carne_id, e.codigo_registro_examen, 
                    c.nombre AS categoria_carne,
                    es.nombre AS escuela_nombre
                FROM estudiantes e
                LEFT JOIN categorias_carne c ON e.categoria_carne_id = c.id
                LEFT JOIN escuelas_conduccion es ON e.escuela_id = es.id
            ";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Obtener los resultados
    $estudiantes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // Manejo de errores en la conexión
    $alerta = [
        'tipo' => 'error',
        'mensaje' => 'Error al obtener los estudiantes: ' . htmlspecialchars($e->getMessage())
    ];
}

include_once('../componentes/head_admin.php');
include_once('../componentes/menu_admin.php');
?>

<div class="main-content">
    <!-- Modal de Alerta -->
    <?php if (!empty($alerta)): ?>
        <div class="modal fade show" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="false"
            style="display: block;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header <?= $alerta['tipo'] == 'success' ? 'bg-success' : 'bg-danger'; ?>">
                        <h5 class="modal-title text-white" id="alertModalLabel">
                            <?= $alerta['tipo'] == 'success' ? '¡Éxito!' : 'Error'; ?>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-center"><?= $alerta['mensaje']; ?></p>
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
                class="card-header bg-primary text-white d-flex flex-wrap justify-content-between align-items-center rounded-top-4 px-4 py-3">
                <h5 class="mb-0"><i class="bi bi-people-fill me-2"></i>Listado de estudiantes</h5>
                <div class="search-box position-relative">
                    <input type="text" class="form-control ps-5" id="customSearch" placeholder="Buscar estudiante...">
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
                    <a href="registrar_estudiantes.php" class="btn btn-light fw-semibold shadow-sm">
                        <i class="bi bi-plus-circle me-2"></i>Crear Nuevo
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="container-table" class="table table-striped table-hover align-middle">
                        <thead class="table-light text-center">
                            <?php if (!empty($estudiantes)): ?>
                                <tr>
                                    <th><i class="bi bi-hash me-1"></i>ID</th>
                                    <th><i class="bi bi-building me-1"></i>Escuela</th>
                                    <th><i class="bi bi-credit-card-2-front-fill me-1"></i>Identificación</th>
                                    <th><i class="bi bi-person-badge-fill me-1"></i>Nombre</th>
                                    <th><i class="bi bi-person-badge me-1"></i>Apellido</th>
                                    <th><i class="bi bi-calendar-heart-fill me-1"></i>Nacimiento</th>
                                    <th><i class="bi bi-telephone-forward-fill me-1"></i>Teléfono</th>
                                    <th><i class="bi bi-geo-alt-fill me-1"></i>Dirección</th>
                                    <th><i class="bi bi-card-heading me-1"></i>Categoría Carné</th>
                                    <th><i class="bi bi-upc-scan me-1"></i>Código Registro</th>
                                    <th><i class="bi bi-tools me-1"></i>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($estudiantes as $estudiante): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($estudiante['id']) ?></td>
                                        <td><?= htmlspecialchars($estudiante['escuela_nombre']) ?></td>
                                        <td><?= htmlspecialchars($estudiante['numero_identificacion']) ?></td>
                                        <td><?= htmlspecialchars($estudiante['nombre']) ?></td>
                                        <td><?= htmlspecialchars($estudiante['apellido']) ?></td>
                                        <td><?= htmlspecialchars($estudiante['fecha_nacimiento']) ?></td>
                                        <td><?= htmlspecialchars($estudiante['telefono']) ?></td>
                                        <td><?= htmlspecialchars($estudiante['direccion']) ?></td>
                                        <td><?= htmlspecialchars($estudiante['categoria_carne']) ?></td>
                                        <td><?= htmlspecialchars($estudiante['codigo_registro_examen']) ?></td>
                                        <td>
                                            <!-- Acción para dispositivos móviles -->
                                            <div class="dropdown d-block d-md-none">
                                                <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                                    id="dropdownMenuButton<?= $estudiante['id'] ?>" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <i class="bi bi-list"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end"
                                                    aria-labelledby="dropdownMenuButton<?= $estudiante['id'] ?>">
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="editar_estudiante.php?id=<?= $estudiante['id'] ?>">
                                                            <i class="bi bi-pencil-square me-2"></i>Editar
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <button class="dropdown-item btn-eliminar-estudiante"
                                                            data-id="<?= $estudiante['id'] ?>"
                                                            data-nombre="<?= htmlspecialchars($estudiante['nombre'] . ' ' . $estudiante['apellido']) ?>">
                                                            <i class="bi bi-trash3-fill me-2"></i>Eliminar
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>

                                            <!-- Botones de acción para escritorio -->
                                            <div class="d-none d-md-inline-flex gap-1">
                                                <a href="editar_estudiante.php?id=<?= $estudiante['id'] ?>"
                                                    class="btn btn-sm btn-outline-warning">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-danger btn-eliminar-estudiante"
                                                    data-id="<?= $estudiante['id'] ?>"
                                                    data-nombre="<?= htmlspecialchars($estudiante['nombre'] . ' ' . $estudiante['apellido']) ?>">
                                                    <i class="bi bi-trash3-fill"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="11" class="text-center text-warning">
                                        <i class="bi bi-info-circle-fill me-2"></i>No hay estudiantes registrados
                                        actualmente.
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

<!-- Modal de confirmación de eliminación -->
<div class="modal fade" id="confirmarEliminarModal" tabindex="-1" aria-labelledby="confirmarEliminarModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmarEliminarModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Está seguro de que desea eliminar al estudiante <span id="nombre-estudiante-eliminar"></span>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btn-confirmar-eliminar">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<?php include_once('../componentes/footer.php'); ?>