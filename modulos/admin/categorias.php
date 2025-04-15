<?php
session_start();
$_SESSION['errores'] = [];
$_SESSION['mensaje'] = null;
require '../../config/conexion.php';

try {
    $conn = $pdo->getConexion();

    $consulta = $conn->prepare("SELECT COUNT(*) FROM categorias_carne");
    $consulta->execute();
    $total = $consulta->fetchColumn();

    if ($total > 0) {
        //  $_SESSION['mensaje'] = "✅ Las categorías ya están cargadas correctamente.";
    } else {
        $categorias = [
            ['A', 'Motocicletas con o sin sidecar'],
            ['A1', 'Motocicletas ligeras hasta 125cc y 11kW'],
            ['A2', 'Motocicletas de potencia media hasta 35 kW'],
            ['B', 'Vehículos hasta 3.500 kg y 8 pasajeros'],
            ['B+E', 'Vehículos B con remolque mayor a 750 kg'],
            ['C', 'Vehículos pesados de más de 3.500 kg'],
            ['C1', 'Camiones entre 3.500 y 7.500 kg'],
            ['C+E', 'Camiones con remolque mayor a 750 kg'],
            ['D', 'Autobuses de más de 8 pasajeros'],
            ['D1', 'Autobuses pequeños hasta 16 pasajeros'],
            ['D+E', 'Autobuses con remolque mayor a 750 kg'],
            ['AM', 'Ciclomotores hasta 50cc y 45 km/h'],
            ['T', 'Vehículos agrícolas como tractores'],
        ];

        $stmt = $conn->prepare("INSERT INTO categorias_carne (nombre, descripcion) VALUES (?, ?)");
        foreach ($categorias as $cat) {
            $stmt->execute([$cat[0], $cat[1]]);
        }
        $_SESSION['mensaje'] = "✅ Categorías insertadas correctamente.";
    }
} catch (PDOException $e) {
    error_log("Error: " . $e->getMessage());
    $_SESSION['errores'][] = "❌ Error al insertar categorías. Intente nuevamente.";
}

try {
    $sql = "SELECT * FROM categorias_carne";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error en la consulta: " . $e->getMessage());
    $_SESSION['errores'][] = "❌ Ocurrió un error al recuperar las categorías.";
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
            <div
                class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top-4 px-4">
                <h5 class="mb-0"><i class="bi bi-tags-fill me-2"></i>Listado de Categorías</h5>
                <div class="search-box position-relative">
                    <input type="text" class="form-control ps-5" id="customSearch" placeholder="Buscar categoría...">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                </div>
                <div class="mb-0 d-flex justify-content-end align-items-center">
                    <label for="categorias-length" class="me-2 mb-0 fw-medium text-muted text-white">Mostrar:</label>
                    <select id="categorias-length" class="form-select w-auto shadow-sm">
                        <option value="5">5 registros</option>
                        <option value="10" selected>10 registros</option>
                        <option value="25">25 registros</option>
                        <option value="50">50 registros</option>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <!-- Select personalizado de longitud -->


                <div class="table-responsive">
                    <table id="container-table" class="table table-striped table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th><i class="bi bi-hash"></i> ID</th>
                                <th><i class="bi bi-tag-fill"></i> Nombre</th>
                                <th><i class="bi bi-card-text"></i> Descripción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($categorias)): ?>
                                <?php foreach ($categorias as $categoria): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($categoria['id']) ?></td>
                                        <td><?= htmlspecialchars($categoria['nombre']) ?></td>
                                        <td><?= htmlspecialchars($categoria['descripcion']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted fst-italic">
                                        <i class="bi bi-info-circle-fill me-1"></i>No hay categorías registradas.
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

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- DataTable personalizado -->
<script>
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
            dom: 'lrtip' // Oculta buscador original
        });

        // Buscador personalizado
        $('#customSearch').on('keyup', function () {
            table.search(this.value).draw();
        });

        // Select personalizado de longitud
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