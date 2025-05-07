<?php
// Incluir la conexión a la base de datos
require '../config/conexion.php';

// Verifica si la clase $pdo existe y se puede obtener conexión
$conn = method_exists($pdo, 'getConexion') ? $pdo->getConexion() : null;
$alerta = null;
$permisos = [];

if ($conn) {
    try {
        // Consulta adaptada a la estructura actual: estudiantes + examenes_estudiantes + examenes
        $sql = "SELECT 
        e.titulo AS nombre_examen,
        est.nombre AS nombre_estudiante,
        est.apellido AS apellido_estudiante,
        cc.nombre AS categoria_carne,
        ee.id,
        ee.estudiante_id,
        ee.categoria_carne_id,
        ee.fecha_asignacion,
        ee.fecha_realizacion,
        ee.fecha_proximo_intento,
        ee.estado,
        ee.acceso_habilitado,
        ee.creado_en
    FROM examenes_estudiantes ee
    JOIN estudiantes est ON ee.estudiante_id = est.id
    JOIN categorias_carne cc ON est.categoria_carne_id = cc.id
    JOIN examenes e ON e.categoria_carne_id = ee.categoria_carne_id
    WHERE e.categoria_carne_id = est.categoria_carne_id";




        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $permisos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        error_log("Error en la consulta: " . $e->getMessage());
        $alerta = ['tipo' => 'error', 'mensaje' => 'Ocurrió un error al recuperar los permisos. ' . $e->getMessage()];
    }
} else {
    $alerta = ['tipo' => 'error', 'mensaje' => 'No se pudo establecer la conexión con la base de datos.'];
}

include '../componentes/head_admin.php';
include '../componentes/menu_admin.php';
?>

<div class="main-content">
    <?php if ($alerta): ?>
        <div class="modal fade show" id="alertModal" tabindex="-1" aria-hidden="false" style="display: block;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header <?= $alerta['tipo'] == 'success' ? 'bg-success' : 'bg-danger'; ?>">
                        <h5 class="modal-title text-white"><?= $alerta['tipo'] == 'success' ? '¡Éxito!' : 'Error'; ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center">
                        <?= htmlspecialchars($alerta['mensaje'], ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="container-fluid mt-5">
        <div class="card shadow border-0 rounded-4">
            <div
                class="card-header bg-primary text-white d-flex flex-wrap justify-content-between align-items-center px-4 py-3">
                <h5 class="mb-0"><i class="bi bi-shield-lock-fill me-2"></i>Permisos de Examen</h5>
                <div class="search-box position-relative">
                    <input type="text" class="form-control ps-5" id="customSearch" placeholder="Buscar permiso...">
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
                        </select>
                    </div>

                </div>
            </div>

            <div class="table-responsive">
                <table id="permisos-table" class="table table-striped table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th><i class="bi bi-hash me-1 text-secondary"></i>ID</th>
                            <th><i class="bi bi-person-fill me-1 text-secondary"></i>Estudiante</th>
                            <th><i class="bi bi-person-fill me-1 text-secondary"></i>Categoría Carné</th>
                            <th><i class="bi bi-file-earmark-text-fill me-1 text-secondary"></i>Examen</th>
                            <th><i class="bi bi-calendar-check-fill me-1 text-secondary"></i>Fecha de Asignación</th>
                            <th><i class="bi bi-calendar-check-fill me-1 text-secondary"></i>Fecha de Realización</th>
                            <th><i class="bi bi-calendar-check-fill me-1 text-secondary"></i>Fecha de Próximo Intento
                            </th>
                            <th><i class="bi bi-file-earmark-text-fill me-1 text-secondary"></i>Estado</th>
                            <th><i class="bi bi-check-circle me-1 text-secondary"></i>Acceso Habilitado</th>
                            <th><i class="bi bi-calendar-plus me-1 text-secondary"></i>Creado En</th>
                              <th><i class="bi bi-gear-fill me-1 text-secondary"></i>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($permisos)): ?>
                            <?php foreach ($permisos as $permiso): ?>
                                <tr>
                                    <td><?= htmlspecialchars($permiso['id']) ?></td>
                                    <td><?= htmlspecialchars($permiso['nombre_estudiante']) . ' ' . htmlspecialchars($permiso['apellido_estudiante']) ?>
                                    </td>
                                    <td><?= htmlspecialchars($permiso['categoria_carne']) ?></td>
                                    <td><?= htmlspecialchars($permiso['nombre_examen']) ?></td>
                                    <td><?= htmlspecialchars($permiso['fecha_asignacion']) ?></td>
                                    <td><?= htmlspecialchars($permiso['fecha_realizacion'])  ? htmlspecialchars($permiso['fecha_realizacion'])  : "Por definir" ?></td>
                                    <td><?= htmlspecialchars($permiso['fecha_proximo_intento']) ? htmlspecialchars($permiso['fecha_proximo_intento']) : "Por definir"  ?></td>
                                    <td><?= htmlspecialchars($permiso['estado']) ?></td>
                                    <td> 
                                        <button class="btn toggle-acceso shadow-sm fw-semibold px-3 py-1 rounded-pill
                                         <?= $permiso['acceso_habilitado'] ? 'btn-primary' : 'btn-outline-danger' ?>" data-id="<?= $permiso['id'] ?>"
                                            data-status="<?= $permiso['acceso_habilitado'] ?>">
                                            <?= $permiso['acceso_habilitado'] ? '<i class="bi bi-unlock-fill me-1"></i>Activo' : '<i class="bi bi-lock-fill me-1"></i>Inactivo' ?>
                                        </button> 
                                    </td>
                                    <td><?= htmlspecialchars($permiso['creado_en']) ?></td>
                                    <td>  
                                        <a class="btn btn-sm btn-outline-primary"    href="asignar_total_pregunta.php?id=<?= $permiso['id'] ?>">Asignar Total Preguntas</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="text-center text-warning fw-semibold">⚠️ No hay permisos asignados
                                    actualmente.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>
 



<script>
    document.addEventListener('click', async function (event) {
        const btn = event.target.closest('.toggle-acceso');
        if (!btn) return;

        const id = btn.dataset.id;
        const currentStatus = btn.dataset.status;
        const newStatus = currentStatus === '1' ? '0' : '1';

        // Cambia a estado de carga
        const originalHTML = btn.innerHTML;
        btn.classList.add('loading');
        btn.innerHTML = '<span class="spinner-border spinner-border-sm text-light" role="status" aria-hidden="true"></span>';

        try {
            const res = await fetch('../php/activar_acceso_examen.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id, acceso_habilitado: newStatus })
            });

            const data = await res.json();

            if (data.success) {
                // Actualiza el estado y estilos del botón
                btn.dataset.status = newStatus;

                if (newStatus === '1') {
                    btn.classList.remove('btn-outline-danger');
                    btn.classList.add('btn-primary');
                    btn.innerHTML = '<i class="bi bi-unlock-fill me-1"></i>Activo';
                } else {
                    btn.classList.remove('btn-primary');
                    btn.classList.add('btn-outline-danger');
                    btn.innerHTML = '<i class="bi bi-lock-fill me-1"></i>Inactivo';
                }

            } else {
                alert('❌ Error: No se pudo actualizar el estado.');
                btn.innerHTML = originalHTML;
            }
        } catch (error) {
            console.error('Error en la solicitud:', error);
            alert('❌ Error al conectar con el servidor.');
            btn.innerHTML = originalHTML;
        }

        btn.classList.remove('loading');
    });
</script>


<?php include_once('../componentes/footer.php'); ?>