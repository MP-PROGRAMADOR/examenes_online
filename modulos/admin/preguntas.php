<!-- End Navbar -->
<?php

require '../../config/conexion.php';

$conn = $pdo->getConexion();

$mensaje = isset($_GET['mensaje']) ? $_GET['mensaje'] : '';
$preguntas = [];

try {
    $sql = "SELECT
                p.id,
                p.texto_pregunta,
                p.tipo_pregunta, 
                p.fecha_creacion,
                e.titulo AS examen_titulo
            FROM preguntas p
            INNER JOIN examenes e ON p.examen_id = e.id
            ORDER BY p.fecha_creacion DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $preguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error al listar preguntas: " . $e->getMessage());
    $mensaje_error_listado = "Error al cargar la lista de preguntas.";
}

// frontend/listar_preguntas.php (sin CSS puro)
?>









<!DOCTYPE html>
<html lang="es">

<?php


include '../componentes/head_admin.php';



?>

<body>


    <?php


    include '../componentes/menu_admin.php';



    ?>





    <div class="content">
        <div class="top-bar">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNavbar"
                    aria-controls="topNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="topNavbar">
                    <ul class="navbar-nav ml-auto">
                        <!-- <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-bell"></i> <span class="badge bg-danger">3</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="alertsDropdown">
                                <li><a class="dropdown-item" href="#">Nueva inscripción de aspirante</a></li>
                                <li><a class="dropdown-item" href="#">Examen teórico finalizado</a></li>
                            </ul>
                        </li> -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle"></i> Admin User
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="perfil.html">Perfil</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="../login/logout.php">Cerrar Sesión</a></li>
                            </ul>
                        </li>
                    </ul>
                    <!--   <ul class="nav nav-tabs card-header-tabs float-end">
                        <li class="nav-item">


                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                Crear Nuevo
                            </button>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Opción
                                Deshabilitada</a>
                        </li>
                    </ul> -->
                </div>
            </nav>
        </div>



        <!-- Contenedor principal con espaciado -->
        <div class="container-fluid py-5">

            <!-- Título centrado -->
            <div class="row mb-4">
                <div class="col mt-5">
                    <h2 class="text-center mb-0">LISTA DE PREGUNTAS</h2>
                </div>
            </div>

            <!-- Botón de Crear Nueva Pregunta -->
            <div class="row justify-content-end mb-3">
                <div class="col-auto">
                    <a href="registrar_preguntas.php" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Crear Nuevo
                    </a>
                </div>
            </div>

            <!-- Mensajes de acción -->
            <?php if (isset($mensaje) && $mensaje === 'exito'): ?>
                <div class="alert alert-success">Pregunta guardada exitosamente.</div>
            <?php endif; ?>

            <?php if (isset($mensaje) && $mensaje === 'editado'): ?>
                <div class="alert alert-success">Pregunta editada exitosamente.</div>
            <?php endif; ?>

            <?php if (isset($mensaje) && $mensaje === 'eliminado'): ?>
                <div class="alert alert-success">Pregunta eliminada exitosamente.</div>
            <?php endif; ?>

            <?php if (isset($_GET['mensaje']) && strpos($_GET['mensaje'], 'error') === 0): ?>
                <div class="alert alert-danger">
                    Error: <?php echo htmlspecialchars(str_replace('error_', '', $_GET['mensaje']), ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($mensaje_error_listado)): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($mensaje_error_listado, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>

            <!-- Tabla de preguntas -->
            <?php if (!empty($preguntas)): ?>
                <table class="table table-striped table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Examen</th>
                            <th>Pregunta</th>
                            <th>Tipo</th>
                            <th>Fecha de Creación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($preguntas as $pregunta): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($pregunta['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($pregunta['examen_titulo'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($pregunta['texto_pregunta'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars(str_replace('_', ' ', $pregunta['tipo_pregunta']), ENT_QUOTES, 'UTF-8'); ?>
                                </td>
                                <td><?php echo htmlspecialchars($pregunta['fecha_creacion'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>
                                    <a href="editar_pregunta.php?id=<?php echo urlencode($pregunta['id']); ?>"
                                        class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i> Editar
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger"
                                        onclick="confirmarEliminar(
                                <?php echo htmlspecialchars(json_encode($pregunta['id']), ENT_QUOTES, 'UTF-8'); ?>,
                                '<?php echo htmlspecialchars(addslashes(mb_substr($pregunta['texto_pregunta'], 0, 50, 'UTF-8')), ENT_QUOTES, 'UTF-8'); ?>...')">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="lead">No hay preguntas registradas.</p>
            <?php endif; ?>
        </div>

        <!-- Modal de confirmación -->
        <div class="modal fade" id="confirmarEliminarModal" tabindex="-1" aria-labelledby="confirmarEliminarModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Encabezado -->
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmarEliminarModalLabel">Confirmar Eliminación</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <!-- Cuerpo -->
                    <div class="modal-body">
                        ¿Está seguro de que desea eliminar la pregunta con texto:
                        "<span id="texto-pregunta-eliminar"></span>"?
                    </div>
                    <!-- Pie -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <a href="#" id="enlace-eliminar" class="btn btn-danger">Eliminar</a>
                    </div>
                </div>
            </div>
        </div>












        <link rel="stylesheet" type="text/css"
            href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>

        <!-- Scripts optimizados -->
        <script src="../../public/js/bootstrap.bundle.min.js"></script>
        <script src="../../public/js/chart.js"></script>


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>

        <!-- Script para mostrar el modal -->
        <script>
            function confirmarEliminar(id, texto) {
                document.getElementById('texto-pregunta-eliminar').textContent = texto;
                document.getElementById('enlace-eliminar').href = 'eliminar_pregunta.php?id=' + encodeURIComponent(id);
                var modal = new bootstrap.Modal(document.getElementById('confirmarEliminarModal'));
                modal.show();
            }
        </script>








</body>

</html>