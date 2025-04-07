<!-- End Navbar -->
<?php

require '../../config/conexion.php';

$conn = $pdo->getConexion();


try {
    // Conectar a la base de datos


    // Preparar la consulta para obtener los datos
    $sql = "SELECT * FROM usuarios";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Obtener los resultados como un array asociativo
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
}


?>

<?php
include_once("../componentes/head_admin.php");
include_once("../componentes/menu_admin.php");
?>


<!-- Main -->
<div class="flex-grow-1 d-flex flex-column overflow-hidden">
    <nav class="navbar navbar-expand-lg border-bottom shadow-sm sticky-top">
        <div class="container-fluid">
            <button class="btn btn-outline-light d-lg-none" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#sidebar">
                <i class="bi bi-list"></i>
            </button>
            <span class="navbar-brand fw-bold"><?= $titulo ?? 'Panel de Administración' ?></span>
            <div class="ms-auto d-flex gap-2 align-items-center">
                <span class="fw-semibold">Admin</span>
                <a href="#" class="btn btn-outline-danger btn-sm"><i class="bi bi-box-arrow-right"></i> Cerrar
                    sesión</a>
            </div>
        </div>
    </nav>



    <!-- Contenido principal -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">

        <div class="row mb-4">
            <div class="col mt-5">
                <h2 class="text-center mb-0">LISTA DE USUARIOS</h2>
            </div>
        </div>

        <div class="row justify-content-end mb-3">
            <div class="col-auto">
                <a href="registrar_usuarios.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Crear Nuevo
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table id="usuarios-table" class="table table-striped table-bordered">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">ID</th>
                        <th class="text-center">Nombre</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Contraseña</th>
                        <th class="text-center">Rol</th>
                        <th class="text-center">Fecha de creación</th>
                        <th class="text-center">Activo</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($usuarios)): ?>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($usuario['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($usuario['nombre_usuario'], ENT_QUOTES, 'UTF-8'); ?>
                                </td>
                                <td><?php echo htmlspecialchars($usuario['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($usuario['password'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($usuario['rol'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($usuario['fecha_creacion'], ENT_QUOTES, 'UTF-8'); ?>
                                </td>
                                <td><?php echo $usuario['activo'] ? '<span class="badge bg-success">Sí</span>' : '<span class="badge bg-danger">No</span>'; ?>
                                </td>
                                <td>
                                    <a href="editar_usuario.php?id=<?php echo htmlspecialchars($usuario['id'], ENT_QUOTES, 'UTF-8'); ?>"
                                        class="btn btn-sm btn-warning me-1"><i class="bi bi-pencil"></i> Editar</a>
                                    <button type="button" class="btn btn-sm btn-danger btn-eliminar-usuario"
                                        data-id="<?php echo htmlspecialchars($usuario['id'], ENT_QUOTES, 'UTF-8'); ?>"
                                        data-nombre="<?php echo htmlspecialchars($usuario['nombre_usuario'], ENT_QUOTES, 'UTF-8'); ?>"><i
                                            class="bi bi-trash"></i> Eliminar</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">No hay usuarios registrados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
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

    </main>



</div>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
    crossorigin="anonymous"></script>


<script>
    $(document).ready(function () {
        $('#usuarios-table').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
            }
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
    });
</script>





























</body>

</html>