<?php

require '../includes/conexion.php';

// Consulta de correos enviados
$stmt = $pdo->query("SELECT c.*, 
       e.nombre AS estudiante_nombre,
       u.nombre AS enviado_por_nombre
FROM correos_enviados c
LEFT JOIN estudiantes e ON c.estudiante_id = e.id
LEFT JOIN usuarios u ON c.enviado_por = u.id
ORDER BY c.enviado_en DESC;
");
$correos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Consulta de correos enviados
$stmt = $pdo->query("SELECT *FROM estudiantes");
$estudiantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

include_once("../includes/header.php");
include_once("../includes/sidebar.php");
?>

<main class="main-content" id="content">
    <div class="card shadow border-0 rounded-4">
        <div
            class="card-header bg-primary text-white d-flex flex-wrap justify-content-between align-items-center rounded-top-4 px-4 py-3">
            <h5 class="mb-0"><i class="bi bi-envelope-paper-fill me-2"></i>Correos Enviados</h5>
            <div class="search-box position-relative">
                <input type="text" class="form-control ps-5" id="customSearch" placeholder="Buscar correo...">
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
                <!-- Botón: Registrar Nuevo Correo -->
                <button type="button" class="btn btn-primary rounded-3 shadow-sm px-4" data-bs-toggle="modal"
                    data-bs-target="#modalNuevoCorreo">
                    <i class="bi bi-envelope-plus-fill me-2"></i>Nuevo Correo
                </button>

            </div>
        </div>

        <div class="table-responsive">
            <table id="correos-table" class="table table-hover align-middle shadow-sm rounded-3 overflow-hidden">
                <thead class="table-light text-center">
                    <?php if (!empty($correos)): ?>
                        <tr>
                            <th><i class="bi bi-hash me-1"></i>ID</th>
                            <th><i class="bi bi-person-fill me-1"></i>Estudiante</th>
                            <th><i class="bi bi-envelope-fill me-1"></i>Tipo</th>
                            <th><i class="bi bi-card-heading me-1"></i>Asunto</th>
                            <th><i class="bi bi-send-check-fill me-1"></i>Enviado por</th>
                            <th><i class="bi bi-calendar-check-fill me-1"></i>Fecha de envío</th>
                            <th><i class="bi bi-gear-fill me-1"></i>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($correos as $correo): ?>
                            <tr>
                                <td class="text-center"><?= htmlspecialchars($correo['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= htmlspecialchars($correo['estudiante_nombre'] ?? 'Desconocido', ENT_QUOTES, 'UTF-8'); ?>
                                </td>
                                <td>
                                    <span class="badge bg-info text-uppercase">
                                        <?= htmlspecialchars($correo['tipo_correo'], ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($correo['asunto'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= htmlspecialchars($correo['enviado_por_nombre'] ?? 'Sistema', ENT_QUOTES, 'UTF-8'); ?>
                                </td>
                                <td><?= htmlspecialchars($correo['enviado_en'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="text-center">
                                    <div class="d-flex gap-2 justify-content-center flex-wrap">
                                        <button class="btn btn-sm btn-outline-primary"
                                            onclick="verDetalleCorreo(<?= $correo['id'] ?>)">
                                            <i class="bi bi-eye-fill me-1"></i>Ver
                                        </button>
                                        <button class="btn btn-sm btn-outline-success"
                                            onclick="reenviarCorreo(<?= $correo['id'] ?>)">
                                            <i class="bi bi-send-fill me-1"></i>Reenviar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-warning text-center m-3">
                            <i class="bi bi-exclamation-circle-fill me-2"></i>⚠️ No hay correos registrados actualmente.
                        </div>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Registrar Nuevo Correo -->
<div class="modal fade" id="modalNuevoCorreo" tabindex="-1" aria-labelledby="modalNuevoCorreoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title" id="modalNuevoCorreoLabel"><i
                        class="bi bi-envelope-plus-fill me-2"></i>Registrar Nuevo Correo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Cerrar"></button>
            </div>
            <form id="formCorreo" method="POST">
                <div class="modal-body px-4 py-3">
                    <!-- Estudiante -->
                    <div class="mb-3">
                        <label for="estudiante_id" class="form-label fw-semibold">Estudiante</label>
                        <select class="form-select" id="estudiante_id" name="estudiante_id" required>
                            <option value="">Seleccione un estudiante</option>
                            <?php foreach ($estudiantes as $est): ?>
                                <option value="<?= $est['id'] ?>">
                                    <?= htmlspecialchars($est['nombre'], ENT_QUOTES, 'UTF-8') ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Tipo de Correo -->
                    <div class="mb-3">
                        <label for="tipo_correo" class="form-label fw-semibold">Tipo de Correo</label>
                        <select class="form-select" id="tipo_correo" name="tipo_correo" required>
                            <option value="">Seleccione el tipo</option>
                            <option value="registro">Registro</option>
                            <option value="invitacion_examen">Invitación a Examen</option>
                            <option value="resultado">Resultado</option>
                            <option value="recordatorio">Recordatorio</option>
                        </select>
                    </div>

                    <!-- Asunto -->
                    <div class="mb-3">
                        <label for="asunto" class="form-label fw-semibold">Asunto</label>
                        <input type="text" class="form-control" id="asunto" name="asunto" maxlength="255" required>
                    </div>

                    <!-- Cuerpo -->
                    <div class="mb-3">
                        <label for="cuerpo" class="form-label fw-semibold">Cuerpo del Correo</label>
                        <textarea class="form-control" id="cuerpo" name="cuerpo" rows="6" required></textarea>
                    </div>

                    <!-- Enviado por (oculto) -->
                    <input type="hidden" name="enviado_por" value="<?= $_SESSION['usuario_id'] ?? 1; ?>">
                </div>

                <div class="modal-footer bg-light rounded-bottom-4 px-4 py-3">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                            class="bi bi-x-lg me-1"></i>Cancelar</button>
                    <button type="submit" class="btn btn-success"><i class="bi bi-send-check-fill me-1"></i>Enviar
                        Correo</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
document.getElementById('formCorreo').addEventListener('submit', function(e) {
    e.preventDefault(); // Evita que se recargue la página

    const form = document.getElementById('formCorreo');
    const formData = new FormData(form);

    fetch('../api/guardar_correo.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            mostrarToast('success' ,data.message);
            form.reset(); // Limpiar formulario
          const modal = bootstrap.Modal.getInstance(document.getElementById('modalNuevoCorreo'));

            modal.hide(); // Cerrar modal
        } else {
            mostrarToast('warning ' , data.message);
        }
    })
    .catch(error => {
        console.error('Error al enviar:', error);
        mostrarToast('danger',' Ocurrió un error al procesar la solicitud');
    });
});
</script>

<?php include_once('../includes/footer.php'); ?>