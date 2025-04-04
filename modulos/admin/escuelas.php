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
                    <h2 class="text-center mb-0">LISTA DE ESCUELAS</h2>
                </div>
            </div>

            <div class="row justify-content-end mb-3">
                <div class="col-auto">
                    <a href="registrar_escuelas.php" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Crear Nuevo
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table id="escuelas-table" class="table table-striped table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Dirección</th>
                            <th>Teléfono</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($escuelas)): ?>
                            <?php foreach ($escuelas as $escuela): ?>
                                <tr>
                                    <td><?= htmlspecialchars($escuela['id'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($escuela['nombre'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($escuela['direccion'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($escuela['telefono'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td>
                                        <a href="editar_escuela.php?id=<?= htmlspecialchars($escuela['id'], ENT_QUOTES, 'UTF-8') ?>"
                                            class="btn btn-sm btn-warning me-1"><i class="bi bi-pencil"></i> Editar</a>
                                        <button type="button" class="btn btn-sm btn-danger btn-eliminar-escuela"
                                            data-id="<?= htmlspecialchars($escuela['id'], ENT_QUOTES, 'UTF-8') ?>"
                                            data-nombre="<?= htmlspecialchars($escuela['nombre'], ENT_QUOTES, 'UTF-8') ?>"><i
                                                class="bi bi-trash"></i> Eliminar</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No hay escuelas registradas.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="modal fade" id="confirmarEliminarModal" tabindex="-1"
                aria-labelledby="confirmarEliminarModalLabel" aria-hidden="true">
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
        </script>






























</body>

</html>