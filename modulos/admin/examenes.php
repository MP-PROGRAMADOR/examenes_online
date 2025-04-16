<?php
require '../../config/conexion.php';

$conn = $pdo->getConexion();
$mensaje = $_GET['mensaje'] ?? '';
$examenes = [];

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
    $mensaje_error_listado = "Error al cargar la lista de exámenes.";
}

include '../componentes/head_admin.php';
include '../componentes/menu_admin.php';
?>

<div class="main-content">
    <!-- Mensajes de estado -->
    <?php if ($mensaje === 'exito'): ?>
        <div class="alert alert-success"><i class="bi bi-check-circle-fill me-2"></i>Examen registrado exitosamente.</div>
    <?php elseif ($mensaje === 'editado'): ?>
        <div class="alert alert-success"><i class="bi bi-pencil-square me-2"></i>Examen editado exitosamente.</div>
    <?php elseif ($mensaje === 'eliminado'): ?>
        <div class="alert alert-success"><i class="bi bi-trash3-fill me-2"></i>Examen eliminado exitosamente.</div>
    <?php elseif (isset($_GET['mensaje']) && strpos($_GET['mensaje'], 'error') === 0): ?>
        <div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i>Error:
            <?= htmlspecialchars(str_replace('error_', '', $_GET['mensaje'])) ?>
        </div>
    <?php endif; ?>

    <?php if (isset($mensaje_error_listado)): ?>
        <div class="alert alert-danger"><i class="bi bi-exclamation-circle-fill me-2"></i><?= htmlspecialchars($mensaje_error_listado) ?></div>
    <?php endif; ?>

    <!-- Tabla de exámenes -->
    <div class="container-fluid mt-5">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-primary text-white d-flex flex-wrap justify-content-between align-items-center rounded-top-4 px-4 py-3">
                <h5 class="mb-0"><i class="bi bi-clipboard2-check-fill me-2"></i>Listado de Exámenes</h5>
                <div class="search-box position-relative">
                    <input type="text" class="form-control ps-5" id="customSearch" placeholder="Buscar examen...">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                </div>
                <div class="d-flex flex-wrap gap-5 align-items-center">
                    <div class="d-flex align-items-center">
                        <label for="container-length" class="me-2 text-white fw-medium mb-0"><i class="bi bi-list-ul me-1"></i>Mostrar:</label>
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
                                    <td><?= htmlspecialchars(date('d/m/Y', strtotime($examen['fecha_creacion']))) ?></td>
                                    <td>
                                            <div class="btn-group d-none d-md-inline-flex">
                                                <a href="editar_examen.php?id=<?= htmlspecialchars($examen['id']) ?>" class="btn btn-sm btn-outline-warning">
                                                    <i class="bi bi-pencil-fill me-1"></i>Editar
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                    onclick="confirmarEliminar(<?= htmlspecialchars($examen['id'], ENT_QUOTES, 'UTF-8') ?>, '<?= htmlspecialchars(addslashes($examen['titulo']), ENT_QUOTES, 'UTF-8') ?>')">
                                                    <i class="bi bi-trash-fill me-1"></i>Eliminar
                                                </button>
                                            </div>

                                            <!-- Dropdown para móviles -->
                                            <div class="dropdown d-md-none">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots-vertical"></i> Acciones
                                                </button>
                                                <ul class="dropdown-menu w-100">
                                                    <li>
                                                        <a class="dropdown-item w-100" href="editar_examen.php?id=<?= htmlspecialchars($examen['id']) ?>">
                                                            <i class="bi bi-pencil-fill me-2"></i>Editar
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <button class="dropdown-item w-100 text-danger" onclick="confirmarEliminar(<?= htmlspecialchars($examen['id'], ENT_QUOTES, 'UTF-8') ?>, '<?= htmlspecialchars(addslashes($examen['titulo']), ENT_QUOTES, 'UTF-8') ?>')">
                                                            <i class="bi bi-trash-fill me-2"></i>Eliminar
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                                <div class="alert alert-warning text-center mt-4">
                                    <i class="bi bi-exclamation-circle-fill me-2"></i>No hay exámenes registrados actualmente.
                                </div>
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
                <h5 class="modal-title"><i class="bi bi-exclamation-octagon-fill me-2"></i>Confirmar Eliminación
                </h5>
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

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    function confirmarEliminar(id, titulo) {
        document.getElementById('nombre-examen-eliminar').innerText = titulo;
        document.getElementById('enlace-eliminar').href = 'eliminar_examen.php?id=' + id;
        new bootstrap.Modal(document.getElementById('confirmarEliminarModal')).show();
    }

    $(document).ready(function () {
        const table = $('#container-table').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            lengthChange: false,
            info: true,
            lengthMenu: [5, 10, 15, 20, 25],
            language: {
                search: "",
                searchPlaceholder: "Buscar...",
                lengthMenu: "Mostrar _MENU_ registros",
                info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                infoEmpty: "Sin registros",
                zeroRecords: "No se encontraron resultados",
                paginate: {
                    previous: "<i class='bi bi-chevron-left'></i>",
                    next: "<i class='bi bi-chevron-right'></i>"
                }
            },
            order: [[0, 'asc']],
            dom: 'lrtip'
        });

        $('#customSearch').on('keyup', function () {
            table.search(this.value).draw();
        });

        $('#container-length').on('change', function () {
            table.page.len($(this).val()).draw();
        });

        // Estilos adicionales
        $('<style>').prop('type', 'text/css').html(`
                        .card-header {
                            border-bottom: none;
                            border-radius: 0;
                            background-color: #0d6efd !important;
                        }
                        .card-header h5 {
                            margin-bottom: 0;
                        }
                        .card {
                            border-radius: 12px;
                            overflow: hidden;
                        }
                        table.dataTable thead th {
                            background-color: #0d6efd !important;
                            color: #fff !important;
                            font-weight: 600;
                            text-transform: uppercase;
                            font-size: 0.875rem;
                            letter-spacing: 0.5px;
                            border-top: none;
                            border-bottom: none;
                        }
                        table.dataTable tbody tr:hover {
                            background-color: #f1f3f5;
                        }
                        .search-box input {
                            padding-left: 2.5rem;
                            border-radius: 20px;
                            border: 1px solid #ced4da;
                            font-size: 0.9rem;
                        }
                        .search-box i {
                            left: 15px;
                            color: #6c757d;
                            font-size: 1rem;
                        }
                        .dataTables_wrapper .dataTables_paginate .paginate_button {
                            padding: 0.5rem 0.9rem;
                            margin: 0 3px;
                            background-color: #f8f9fa;
                            border: 1px solid #dee2e6;
                            border-radius: 8px;
                            font-size: 0.875rem;
                            transition: all 0.2s ease-in-out;
                            color: #333;
                        }
                        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
                            background-color: #0d6efd;
                            color: #fff !important;
                            border-color: #0d6efd;
                            font-weight: 600;
                        }
                        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
                            background-color: #e2e6ea;
                            color: #000 !important;
                        }
                        table.dataTable {
                            border-collapse: separate;
                            border-spacing: 0;
                            border-radius: 0;
                        }
                `).appendTo('head');

    });
</script>

<?php include_once('../componentes/footer.php'); ?>