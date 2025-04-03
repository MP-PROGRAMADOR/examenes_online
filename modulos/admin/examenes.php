<!-- End Navbar -->
<?php

require '../../config/conexion.php';

$conn = $pdo->getConexion();

$mensaje = isset($_GET['mensaje']) ? $_GET['mensaje'] : '';
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

// frontend/listar_examenes.php (sin CSS puro)
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



        <div class="container-fluid py-5">

            <div class="row mb-4">
                <div class="col mt-5">
                    <h2 class="text-center mb-0">LISTA DE EXAMEN</h2>
                </div>
            </div>

            <div class="row justify-content-end mb-3">
                <div class="col-auto">
                    <a href="registrar_examenes.php" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Crear Nuevo
                    </a>
                </div>
            </div>

            <?php if ($mensaje === 'exito'): ?>
                <div class="alert alert-success">Examen registrado exitosamente.</div>
            <?php endif; ?>
            <?php if ($mensaje === 'editado'): ?>
                <div class="alert alert-success">Examen editado exitosamente.</div>
            <?php endif; ?>
            <?php if ($mensaje === 'eliminado'): ?>
                <div class="alert alert-success">Examen eliminado exitosamente.</div>
            <?php endif; ?>
            <?php if (isset($_GET['mensaje']) && strpos($_GET['mensaje'], 'error') === 0): ?>
                <div class="alert alert-danger">Error:
                    <?php echo htmlspecialchars(str_replace('error_', '', $_GET['mensaje'])); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($mensaje_error_listado)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($mensaje_error_listado); ?></div>
            <?php endif; ?>

            

            <?php if (!empty($examenes)): ?>
                <table class="table table-striped table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Categoría</th>
                            <th>Duración (minutos)</th>
                             
                            <th>Descripcion</th>
                            <th>Fecha de Creación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($examenes as $examen): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($examen['id']); ?></td>
                                <td><?php echo htmlspecialchars($examen['titulo']); ?></td>
                                <td><?php echo htmlspecialchars($examen['categoria_nombre']); ?></td>
                                <td><?php echo htmlspecialchars($examen['duracion_minutos']); ?></td> 
                                <td><?php echo htmlspecialchars($examen['descripcion']); ?></td> 
                                <td><?php echo htmlspecialchars($examen['fecha_creacion']); ?></td>
                                <td>
                                    <a href="editar_examen.php?id=<?php echo htmlspecialchars($examen['id']); ?>"
                                        class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Editar</a>
                                    <button type="button" class="btn btn-sm btn-danger"
                                        onclick="confirmarEliminar(<?php echo htmlspecialchars($examen['id']); ?>, '<?php echo htmlspecialchars($examen['titulo']); ?>')"><i
                                            class="bi bi-trash"></i> Eliminar</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="lead">No hay exámenes registrados.</p>
            <?php endif; ?>
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
                        ¿Está seguro de que desea eliminar el examen "<span id="nombre-examen-eliminar"></span>"?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <a href="#" id="enlace-eliminar" class="btn btn-danger">Eliminar</a>
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

    




















<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script>
        function confirmarEliminar(id, titulo) {
            document.getElementById('nombre-examen-eliminar').innerText = titulo;
            document.getElementById('enlace-eliminar').href = 'eliminar_examen.php?id=' + id;
            const modal = new bootstrap.Modal(document.getElementById('confirmarEliminarModal'));
            modal.show();
        }
    </script>







</body>

</html>