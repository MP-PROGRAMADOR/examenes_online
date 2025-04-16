<!-- End Navbar -->
<?php
require '../../config/conexion.php';

$conn = $pdo->getConexion();
try {
    // Preparar la consulta para obtener los datos
    $sql = "SELECT * FROM usuarios";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Obtener los resultados como un array asociativo
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
}

include_once("../componentes/head_admin.php");
include_once("../componentes/menu_admin.php");
?>


<!-- Main -->
<div class="main-content">
    <!-- Alertas -->
    <?php if (!empty($_SESSION['mensaje'])): ?>
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($_SESSION['mensaje']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['errores'])): ?>
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?php foreach ($_SESSION['errores'] as $error): ?>
                <div><?= htmlspecialchars($error) ?></div>
            <?php endforeach; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="container-fluid mt-5">
        <div class="card shadow border-0 rounded-4">
            <div
                class="card-header bg-primary text-white d-flex flex-wrap justify-content-between align-items-center rounded-top-4 px-4 py-3">
                <h5 class="mb-0"><i class="bi bi-people-fill me-2"></i>Gestión de Usuarios</h5>
                <div class="search-box position-relative">
                    <input type="text" class="form-control ps-5" id="customSearch" placeholder="Buscar usuario...">
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
                    <a href="registrar_usuarios.php" class="btn btn-light fw-semibold shadow-sm">
                        <i class="bi bi-plus-circle me-2"></i>Crear Nuevo
                    </a>
                </div>
            </div>
            <div class="table-responsive">
                <table id="usuarios-table" class="table table-hover align-middle shadow-sm rounded-3 overflow-hidden">
                    <thead class="table-light text-center">
                        <?php if (!empty($usuarios)): ?>
                            <tr>
                                <th><i class="bi bi-hash me-1"></i>ID</th>
                                <th><i class="bi bi-person-fill me-1"></i>Nombre</th>
                                <th><i class="bi bi-envelope-fill me-1"></i>Email</th>
                                <th><i class="bi bi-shield-lock-fill me-1"></i>Contraseña</th>
                                <th><i class="bi bi-person-badge-fill me-1"></i>Rol</th>
                                <th><i class="bi bi-calendar-check-fill me-1"></i>Fecha</th>
                                <th><i class="bi bi-toggle-on me-1"></i>Activo</th>
                                <th><i class="bi bi-gear-fill me-1"></i>Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td class="text-center"><?= htmlspecialchars($usuario['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= htmlspecialchars($usuario['nombre_usuario'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= htmlspecialchars($usuario['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><span class="text-muted small fst-italic">••••••••</span></td>
                                    <td>
                                        <span class="badge bg-secondary text-uppercase">
                                            <?= htmlspecialchars($usuario['rol'], ENT_QUOTES, 'UTF-8'); ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($usuario['fecha_creacion'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td class="text-center">
                                        <?= $usuario['activo']
                                            ? '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Sí</span>'
                                            : '<span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>No</span>'; ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-none d-md-flex gap-2 justify-content-center">
                                            <a href="editar_usuario.php?id=<?= htmlspecialchars($usuario['id'], ENT_QUOTES, 'UTF-8'); ?>"
                                                class="btn btn-sm btn-outline-primary" title="Editar">
                                                <i class="bi bi-pencil-square me-1"></i>Editar
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger btn-eliminar-usuario"
                                                data-id="<?= htmlspecialchars($usuario['id'], ENT_QUOTES, 'UTF-8'); ?>"
                                                data-nombre="<?= htmlspecialchars($usuario['nombre_usuario'], ENT_QUOTES, 'UTF-8'); ?>"
                                                title="Eliminar">
                                                <i class="bi bi-trash3 me-1"></i>Eliminar
                                            </button>
                                        </div>

                                        <!-- Dropdown para móviles -->
                                        <div class="d-block d-md-none dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                                data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                                <li>
                                                    <a class="dropdown-item w-100 text-primary"
                                                        href="editar_usuario.php?id=<?= htmlspecialchars($usuario['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                                        <i class="bi bi-pencil-square me-2"></i>Editar
                                                    </a>
                                                </li>
                                                <li>
                                                    <button class="dropdown-item w-100 text-danger btn-eliminar-usuario"
                                                        data-id="<?= htmlspecialchars($usuario['id'], ENT_QUOTES, 'UTF-8'); ?>"
                                                        data-nombre="<?= htmlspecialchars($usuario['nombre_usuario'], ENT_QUOTES, 'UTF-8'); ?>">
                                                        <i class="bi bi-trash3 me-2"></i>Eliminar
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="alert alert-warning text-center">
                                <i class="bi bi-exclamation-circle-fill me-2"></i>⚠️ No hay usuarios registrados
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
                ¿Está seguro de que desea eliminar al usuario <span id="nombre-usuario-eliminar"></span>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btn-confirmar-eliminar">Eliminar</button>
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
                        .search-box {
                            position: relative;
                            display: inline-block;
                            width: 100%;
                            max-width: 300px;
                        }

                        .search-box input {
                            padding-left: 2.5rem;
                            padding-right: 1rem;
                            border-radius: 50px;
                            border: 1px solid #dee2e6;
                            background-color: #f8f9fa;
                            font-size: 0.9rem;
                            transition: all 0.3s ease-in-out;
                            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
                        }

                        .search-box input:focus {
                            border-color: #0d6efd;
                            background-color: #fff;
                            outline: none;
                            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.15);
                        }

                        .search-box i {
                            position: absolute;
                            left: 15px;
                            top: 50%;
                            transform: translateY(-50%);
                            color: #6c757d;
                            font-size: 1rem;
                            pointer-events: none;
                            transition: color 0.3s ease-in-out;
                        }

                        .search-box input:focus + i {
                            color: #0d6efd;
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


    // Manejo del botón de eliminar con modal de confirmación
    $('.btn-eliminar-usuario').on('click', function () {
        const usuarioId = $(this).data('id');
        const usuarioNombre = $(this).data('nombre');
        $('#nombre-usuario-eliminar').text(usuarioNombre);
        $('#btn-confirmar-eliminar').data('id', usuarioId);
        $('#confirmarEliminarModal').modal('show');
    });

    $('#btn-confirmar-eliminar').on('click', function () {
        const usuarioId = $(this).data('id');
        // Redirigir o enviar una petición AJAX para eliminar el usuario
        window.location.href = 'eliminar_usuario.php?id=' + usuarioId; // Ejemplo de redirección
        $('#confirmarEliminarModal').modal('hide');
    });

</script>
<?php include_once('../componentes/footer.php'); ?>





























</body>

</html>