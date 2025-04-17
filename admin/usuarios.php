<?php
require '../config/conexion.php';

$conn = $pdo->getConexion();
$alerta = ''; // Variable para manejar las alertas

try {
    // Preparar la consulta para obtener los datos
    $sql = "SELECT * FROM usuarios";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Obtener los resultados como un array asociativo
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Verificar si hay un mensaje de éxito o errores
    if (isset($_SESSION['mensaje'])) {
        $alerta = '<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>' . htmlspecialchars($_SESSION['mensaje'], ENT_QUOTES, 'UTF-8') . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>';
        unset($_SESSION['mensaje']); // Limpiar mensaje después de mostrarlo
    } elseif (isset($_SESSION['errores'])) {
        $errores = '';
        foreach ($_SESSION['errores'] as $error) {
            $errores .= '<div>' . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . '</div>';
        }
        $alerta = '<div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>' . $errores . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>';
        unset($_SESSION['errores']); // Limpiar errores después de mostrarlos
    }
} catch (Exception $e) {
    die("Error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
}

include_once("../componentes/head_admin.php");
include_once("../componentes/menu_admin.php");
?>

<!-- Main -->
<div class="main-content">
    <!-- Alertas -->
    <?= $alerta ?> <!-- Mostrar alerta -->

    <div class="container-fluid mt-5">
        <div class="card shadow border-0 rounded-4">
            <div class="card-header bg-primary text-white d-flex flex-wrap justify-content-between align-items-center rounded-top-4 px-4 py-3">
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

<?php include_once('../componentes/footer.php'); ?>
 