<?php
session_start(); 
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
<?php include_once('../componentes/head_admin.php'); ?>
<?php include_once('../componentes/menu_admin.php'); ?>


<div class="main-content"> 
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
                <h5 class="mb-0"><i class="bi bi-clipboard2-check-fill me-2"></i>Listado de preguntas</h5>
                <div class="search-box position-relative">
                    <input type="text" class="form-control ps-5" id="customSearch" placeholder="Buscar pregunta...">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                </div>
                <div class="d-flex flex-wrap gap-5 align-items-center">
                    <div class="d-flex align-items-center">
                        <label for="container-length" class="me-2 text-white fw-medium mb-0">
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
                    <a href="registrar_preguntas.php" class="btn btn-light fw-semibold shadow-sm">
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
                                    <th><i class="bi bi-list-check me-1"></i>Tipo</th>
                                    <th><i class="bi bi-image me-1"></i>Contenido</th>
                                    <th><i class="bi bi-calendar3 me-1"></i>Registro</th>
                                    <th><i class="bi bi-tools me-1"></i>Acciones</th>
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
                                                    <img src="<?= htmlspecialchars($img) ?>" alt="img"
                                                         class="img-thumbnail me-1" style="width: 70px; height: auto;">
                                                <?php endforeach; ?>
                                                <div class="mt-2 text-muted small">
                                                    <i class="bi bi-text-left me-1"></i>
                                                    <?= nl2br(htmlspecialchars($pregunta['texto_pregunta'])) ?>
                                                </div>
                                            <?php else: ?>
                                                <i class="bi bi-text-left me-1"></i>
                                                <?= nl2br(htmlspecialchars($pregunta['texto_pregunta'])) ?>
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
                                            <i class="bi bi-clock me-1"></i><?= date('d/m/Y H:i', strtotime($pregunta['fecha_creacion'])) ?>
                                        </td>
                                        <td class="text-center">
                                            <!-- Botones en dropdown para móviles -->
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a href="editar_pregunta.php?id=<?= urlencode($pregunta['id']) ?>"
                                                           class="dropdown-item w-100">
                                                            <i class="bi bi-pencil-square me-2"></i>Editar
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="../php/eliminar_pregunta.php?id=<?= urlencode($pregunta['id']) ?>"
                                                           class="dropdown-item w-100 text-danger"
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
                                    <i class="bi bi-exclamation-circle-fill me-2"></i>⚠️ No hay preguntas registradas actualmente.
                                </div>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
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

    // Función para cerrar el modal automáticamente después de 5 segundos
    window.onload = function () {
            const alertModal = new bootstrap.Modal(document.getElementById('alertModal'), {
                keyboard: false,
                backdrop: 'static'
            });

            // Si hay un mensaje en la sesión, mostramos el modal y lo cerramos después de 5 segundos
            <?php if ($alerta): ?>
                alertModal.show();
                setTimeout(function () {
                    alertModal.hide();
                }, 5000); // Cierra el modal después de 5 segundos
            <?php endif; ?>
        }
</script>

<?php include_once('../componentes/footer.php'); ?>