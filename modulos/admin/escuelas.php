<!-- End Navbar -->
<?php

require '../../config/conexion.php';

$conn = $pdo->getConexion();



try {
    $sql = "SELECT id, nombre, telefono, direccion FROM escuelas_conduccion";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $escuelas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error en la consulta: " . $e->getMessage());
    echo "Ocurrió un error al recuperar los Escuelas.";
    exit;
}

include '../componentes/head_admin.php';
include '../componentes/menu_admin.php';
?>


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
                                            <a href="editar_escuela.php?id=<?= htmlspecialchars($escuela['id']) ?>" class="btn btn-sm btn-outline-warning">
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
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" href="editar_escuela.php?id=<?= htmlspecialchars($escuela['id']) ?>">
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

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function () {
        $('#escuelas-table').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
            }
        });

        // Manejo del botón de eliminar con modal de confirmación
        $('.btn-eliminar-escuela').on('click', function () {
            const escuelaId = $(this).data('id');
            const escuelaNombre = $(this).data('nombre');
            $('#nombre-escuela-eliminar').text(escuelaNombre);
            $('#btn-confirmar-eliminar').data('id', escuelaId);
            $('#confirmarEliminarModal').modal('show');
        });

        $('#btn-confirmar-eliminar').on('click', function () {
            const escuelaId = $(this).data('id');
            // Redirigir o enviar una petición AJAX para eliminar la escuela
            window.location.href = 'eliminar_escuela.php?id=' + escuelaId; // Ejemplo de redirección
            $('#confirmarEliminarModal').modal('hide');
        });
    });
    /* document.addEventListener("DOMContentLoaded", function () {
    const botonesEliminar = document.querySelectorAll(".btn-eliminar-escuela");

    botonesEliminar.forEach(boton => {
        boton.addEventListener("click", function () {
            const escuelaId = this.dataset.id;
            const escuelaNombre = this.dataset.nombre;

            const confirmacion = confirm(`¿Estás seguro que deseas eliminar la escuela "${escuelaNombre}"?`);

            if (confirmacion) {
                window.location.href = `eliminar_escuela.php?id=${escuelaId}`;
            }
        });
    });
}); */
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
</script>

<?php include_once('../componentes/footer.php'); ?>





























</body>

</html>