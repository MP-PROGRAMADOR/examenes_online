 <?php
 

include_once("../includes/header.php");
include_once("../includes/sidebar.php");
try { 
    // Consulta con JOINs para obtener todos los datos necesarios
    $stmt = $pdo->prepare("
        SELECT 
            e.id,
            e.dni,
            e.nombre,
            e.apellidos,
            e.direccion,
            e.email,
            e.telefono,
            e.fecha_nacimiento,
            e.estado,
            esc.nombre AS escuela,
            c.nombre AS categoria,
            ex.codigo_acceso
        FROM estudiantes e
        LEFT JOIN escuelas_conduccion esc ON e.escuela_id = esc.id
        LEFT JOIN estudiante_categorias ec ON ec.estudiante_id = e.id
        LEFT JOIN categorias c ON ec.categoria_id = c.id
        LEFT JOIN examenes ex ON ex.estudiante_id = e.id AND ex.categoria_id = c.id
        ORDER BY e.id DESC
    ");
    $stmt->execute();
    $estudiantes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al obtener estudiantes: " . $e->getMessage());
}
?>

 
 
 
 <div class="main-content   mt-5">
        <div class="card shadow border-0 rounded-4">
            <div
                class="card-header bg-primary text-white d-flex flex-wrap justify-content-between align-items-center rounded-top-4 px-4 py-3">
                <h5 class="mb-0"><i class="bi bi-people-fill me-2"></i>Listado de estudiantes</h5>
                <div class="search-box position-relative">
                    <input type="text" class="form-control ps-5" id="customSearch" placeholder="Buscar estudiante...">
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
                    <a href="registrar_estudiantes.php" class="btn btn-light fw-semibold shadow-sm">
                        <i class="bi bi-plus-circle me-2"></i>Crear Nuevo
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="container-table" class="table table-striped table-hover align-middle">
                        <thead class="table-light text-center">
                            <?php if (!empty($estudiantes)): ?>

 
                                <tr>
                                    <th><i class="bi bi-hash me-1"></i>ID</th>
                                    <th><i class="bi bi-person-badge-fill me-1"></i>Nombre</th> 
                                    <th><i class="bi bi-credit-card-2-front-fill me-1"></i>Identificación</th>
                                    <th><i class="bi bi-building me-1"></i>Escuela</th>
                                    <th><i class="bi bi-building me-1"></i>email</th>
                                    <th><i class="bi bi-calendar-heart-fill me-1"></i>Nacimiento</th>
                                    <th><i class="bi bi-telephone-forward-fill me-1"></i>Teléfono</th>
                                    <th><i class="bi bi-geo-alt-fill me-1"></i>Dirección</th>
                                    <th><i class="bi bi-card-heading me-1"></i>Categoría Carné</th>
                                    <th><i class="bi bi-upc-scan me-1"></i>Código Registro</th>
                                    <th><i class="bi bi-person-badge me-1"></i>estado</th>
                                    <th><i class="bi bi-tools me-1"></i>Acciones</th>
                                </tr>
                            </thead> 
                            <tbody>
                                 <?php foreach ($estudiantes as $estudiante): ?>
              <tr>
                <td class="text-center"><?= htmlspecialchars($estudiante['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?= htmlspecialchars($estudiante['nombre'], ENT_QUOTES, 'UTF-8'); ?> <?= htmlspecialchars($estudiante['apellidos'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?= htmlspecialchars($estudiante['dni'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?= htmlspecialchars($estudiante['escuela'], ENT_QUOTES, 'UTF-8'); ?> </td>
                <td><?= !empty(htmlspecialchars($estudiante['email'], ENT_QUOTES, 'UTF-8')) ? htmlspecialchars($estudiante['email'], ENT_QUOTES, 'UTF-8') : 'Sin definir' ?> </td> 
                <td><?= htmlspecialchars($estudiante['fecha_nacimiento'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?= htmlspecialchars($estudiante['telefono'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?= htmlspecialchars($estudiante['direccion'], ENT_QUOTES, 'UTF-8'); ?> </td>
                <td><?= htmlspecialchars($estudiante['categoria'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?= htmlspecialchars($estudiante['codigo_acceso'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td class="text-center">
                  <?php if ($estudiante['activo']): ?>
                    <button
                      class="btn btn-outline-success btn-sm d-flex align-items-center gap-2 px-3 py-1 rounded-pill shadow-sm"
                      title="Haz clic para desactivar" onclick="cambiarEstadoestudiante(<?= $estudiante['id'] ?>, false)">
                      <i class="bi bi-toggle-on fs-5"></i>
                      Activo
                    </button>
                  <?php else: ?>
                    <button
                      class="btn btn-outline-danger btn-sm d-flex align-items-center gap-2 px-3 py-1 rounded-pill shadow-sm"
                      title="Haz clic para activar" onclick="cambiarEstadoestudiante(<?= $estudiante['id'] ?>, true)">
                      <i class="bi bi-toggle-off fs-5"></i>
                      Inactivo
                    </button>
                  <?php endif; ?>

                </td>
                <td class="text-center">
                  <div class="d-flex gap-2 justify-content-center flex-wrap">
                    <button class="btn btn-sm btn-outline-warning" onclick="abrirModalEdicion({
                          id: <?= (int) $estudiante['id']; ?>,
                          nombre: '<?= addslashes(htmlspecialchars($estudiante['nombre'], ENT_QUOTES, 'UTF-8')); ?>',
                          email: '<?= addslashes(htmlspecialchars($estudiante['email'], ENT_QUOTES, 'UTF-8')); ?>',
                          rol: '<?= addslashes(htmlspecialchars($estudiante['rol'], ENT_QUOTES, 'UTF-8')); ?>'
                        })">
                      <i class="bi bi-pencil-square me-1"></i> Editar
                    </button> 
                    <?php if (($rol === 'admin')): ?>
                      <button class="btn btn-sm btn-outline-danger eliminar-estudiante-btn"
                        onclick="eliminarEstudiante(<?= htmlspecialchars($estudiante['id'], ENT_QUOTES, 'UTF-8') ?>, '<?= htmlspecialchars($estudiante['nombre'], ENT_QUOTES, 'UTF-8') ?>')"
                        title="Eliminar estudiante">
                        <i class="bi bi-trash me-1"></i>Eliminar
                      </button>
                    <?php endif; ?>

                  </div>
                </td>

              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="alert alert-warning text-center m-3">
              <i class="bi bi-exclamation-circle-fill me-2"></i>⚠️ No hay estudiantes registrados actualmente.
            </div>
          <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <?php include_once('../includes/footer.php'); ?>