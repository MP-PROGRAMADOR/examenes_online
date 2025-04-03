<!-- End Navbar -->
<?php

require '../../config/conexion.php';

$conn = $pdo->getConexion();


try {
    // Conectar a la base de datos


    // Preparar la consulta para obtener los datos
    $sql = "SELECT id, escuela_id, numero_identificacion, nombre, apellido, fecha_nacimiento, telefono, direccion, categoria_carne, codigo_registro_examen FROM estudiantes";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Obtener los resultados como un array asociativo
    $estudiantes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
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
                        <!--  <li class="nav-item dropdown">
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
                    <!-- <ul class="nav nav-tabs card-header-tabs float-end">
                         <li class="nav-item">


                             <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">
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
                    <h2 class="text-center mb-0">LISTA DE ESTUDIANTES</h2>
                </div>
            </div>

            <div class="row justify-content-end mb-3">
                <div class="col-auto">
                    <a href="registrar_estudiantes.php" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Crear Nuevo
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table id="estudiantes-table" class="table table-striped table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Escuela ID</th>
                            <th>Número Identificación</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Fecha Nacimiento</th>
                          
                            <th>Teléfono</th>
                            <th>Dirección</th>
                            <th>Categoría Carné</th>
                            <th>Código Registro Examen</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($estudiantes)): ?>
                            <?php foreach ($estudiantes as $estudiante): ?>
                                <tr>
                                    <td><?= htmlspecialchars($estudiante['id'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($estudiante['escuela_id'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($estudiante['numero_identificacion'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($estudiante['nombre'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($estudiante['apellido'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($estudiante['fecha_nacimiento'], ENT_QUOTES, 'UTF-8') ?></td>
                                   
                                    <td><?= htmlspecialchars($estudiante['telefono'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($estudiante['direccion'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($estudiante['categoria_carne'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($estudiante['codigo_registro_examen'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td>
                                        <a href="editar_estudiante.php?id=<?= htmlspecialchars($estudiante['id'], ENT_QUOTES, 'UTF-8') ?>"
                                            class="btn btn-sm btn-warning me-1"><i class="bi bi-pencil"></i> Editar</a>
                                        <button type="button" class="btn btn-sm btn-danger btn-eliminar-estudiante"
                                            data-id="<?= htmlspecialchars($estudiante['id'], ENT_QUOTES, 'UTF-8') ?>"
                                            data-nombre="<?= htmlspecialchars($estudiante['nombre'] . ' ' . $estudiante['apellido'], ENT_QUOTES, 'UTF-8') ?>"><i
                                                class="bi bi-trash"></i> Eliminar</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="12" class="text-center">No hay estudiantes registrados.</td>
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
                            ¿Está seguro de que desea eliminar al estudiante <span
                                id="nombre-estudiante-eliminar"></span>?
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

        <script src="../../public/js/bootstrap.bundle.min.js"></script>
        <script src="../../public/js/chart.js"></script>

        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>

        <script>
            $(document).ready(function () {
                $('#estudiantes-table').DataTable({
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
                    },
                    // Opcional: Configuración adicional de DataTables
                    // columnDefs: [
                    //     { "width": "5%", "targets": 0 }, // Ejemplo: Ancho de la columna ID
                    //     { "orderable": false, "targets": [11] } // Ejemplo: Deshabilitar ordenamiento en la columna de acciones
                    // ]
                });

                // Manejo del botón de eliminar con modal de confirmación
                $('.btn-eliminar-estudiante').on('click', function () {
                    const estudianteId = $(this).data('id');
                    const estudianteNombre = $(this).data('nombre');
                    $('#nombre-estudiante-eliminar').text(estudianteNombre);
                    $('#btn-confirmar-eliminar').data('id', estudianteId);
                    $('#confirmarEliminarModal').modal('show');
                });

                $('#btn-confirmar-eliminar').on('click', function () {
                    const estudianteId = $(this).data('id');
                    // Redirigir o enviar una petición AJAX para eliminar el estudiante
                    window.location.href = 'eliminar_estudiante.php?id=' + estudianteId; // Ejemplo de redirección
                    $('#confirmarEliminarModal').modal('hide');
                });
            });
        </script>


        <!-- Scripts optimizados -->


</body>

</html>