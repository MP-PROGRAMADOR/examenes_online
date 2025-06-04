<?php
include_once("../includes/header.php"); 

try {
    // Preparar y ejecutar la consulta para obtener las escuelas
    $sql = "SELECT * FROM escuelas_conduccion";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // Obtener todas las escuelas
    $escuelas = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Manejo de errores: si ocurre un error en la consulta, se captura y se muestra un mensaje
    error_log("Error en la consulta: " . $e->getMessage());
    // $alerta = ['tipo' => 'error', 'mensaje' => 'Ocurrió un error al recuperar las escuelas.'.$e->getMessage()];
}


?>

<div class="d-flex">
  <?php include_once("../includes/sidebar.php"); ?>
  
 

<div class="main-content">

    <div class="container-fluid mt-5">
        <div class="card shadow border-0 rounded-4">
            <div
                class="card-header bg-primary text-white d-flex flex-wrap justify-content-between align-items-center rounded-top-4 px-4 py-3">
                <h5 class="mb-0"><i class="bi bi-buildings-fill me-2"></i>Listado de Escuelas</h5>
                <div class="search-box position-relative">
                    <input type="text" class="form-control ps-5" id="customSearch" placeholder="Buscar Escuela...">
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
                    <button class="btn btn-primary" onclick="abrirModalRegistro()">
                        <i class="bi bi-person-plus-fill me-2"></i>Crear Nueva
                    </button>

                </div>
            </div>

            <div class="table-responsive">
                <table id="escuelas-table" class="table table-striped table-bordered">
                    <thead class="table-light">
                        <?php if (!empty($escuelas)): ?>
                            <tr>
                                <th><i class="bi bi-hash me-1 text-secondary"></i>ID</th>
                                <th><i class="bi bi-building me-1 text-secondary"></i>Nombre</th>
                                <th><i class="bi bi-geo-alt-fill me-1 text-secondary"></i>Ciudad</th> 
                                <th><i class="bi bi-gear-fill me-1 text-secondary"></i>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($escuelas as $escuela): ?>
                                <tr>
                                    <td><?= htmlspecialchars($escuela['id'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($escuela['nombre'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($escuela['ciudad'], ENT_QUOTES, 'UTF-8') ?></td> 
                                    
                                    <td class="text-center">
                                        <div class="d-flex gap-2 justify-content-center flex-wrap">
                                            <button class="btn btn-sm btn-outline-warning" onclick="abrirModalEdicion({
                                                id: <?= (int) $escuela['id']; ?>,
                                                nombre: '<?= addslashes(htmlspecialchars($escuela['nombre'], ENT_QUOTES, 'UTF-8')); ?>',
                                                ciudad: '<?= addslashes(htmlspecialchars($escuela['ciudad'], ENT_QUOTES, 'UTF-8')); ?>',
                                                
                                                })">
                                                <i class="bi bi-pencil-square me-1"></i> Editar
                                            </button>
                                            <?php if (($rol === 'admin')): ?>
                                                <button class="btn btn-sm btn-outline-danger eliminar-usuario-btn"
                                                    onclick="eliminarEscuela(<?= htmlspecialchars($escuela['id'], ENT_QUOTES, 'UTF-8') ?>, '<?= htmlspecialchars($escuela['nombre'], ENT_QUOTES, 'UTF-8') ?>')"
                                                    title="Eliminar escuela">
                                                    <i class="bi bi-trash me-1"></i>Eliminar
                                                </button>
                                            <?php endif; ?>

                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="alert alert-warning text-center">
                                <i class="bi bi-exclamation-circle-fill me-2"></i>⚠️ No hay escuelas registradas
                                actualmente.
                            </div>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



<!-- Modal Registro / Edición (reutilizado) -->
<div class="modal fade" id="modalEscuela" tabindex="-1" aria-labelledby="modalEscuelaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header bg-primary text-white rounded-top">
                <h5 class="modal-title" id="modalEscuelaLabel">
                    <i class="bi bi-person-plus-fill me-2"></i><span id="modalTitulo">Registrar Escuela</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Cerrar"></button>
            </div>
            <form id="formularioEditarRegistrar" method="POST" class="needs-validation" novalidate>
                <div class="modal-body p-4">
                    <input type="hidden" name="escuela_id" id="escuela_id">

                    <!-- Nombre -->
                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-semibold">
                            <i class="bi bi-person-circle me-2 text-primary"></i>Nombre de la escuela <span
                                class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control shadow-sm" id="nombre" name="nombre" required>
                        <div class="invalid-feedback">Por favor ingresa el nombre completo.</div>
                    </div>

                    <!-- ciudad -->
                    <div class="mb-3">
                        <label for="ciudad" class="form-label fw-semibold">
                            <i class="bi bi-envelope-fill me-2 text-primary"></i>Ciudad <span
                                class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control shadow-sm" id="ciudad" name="ciudad" required>
                        <div class="invalid-feedback">Por favor ingresa una ciudad valida.</div>
                    </div>


                    <div class="modal-footer bg-light p-3">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-2"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save2-fill me-2"></i><span id="modalBotonTexto">Registrar</span>
                        </button>
                    </div>
            </form>
        </div>
    </div>
</div>


<script>
    (() => {
        'use strict';

        // Validación Bootstrap
        const forms = document.querySelectorAll('.needs-validation');
        forms.forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            });
        });


    })();



    // Función para abrir modal en modo registro
    function abrirModalRegistro() {
        document.getElementById('modalTitulo').textContent = 'Registrar escuela';
        document.getElementById('modalBotonTexto').textContent = 'Registrar';
        document.getElementById('escuela_id').value = '';
        document.getElementById('nombre').value = '';
        document.getElementById('ciudad').value = '';

        const modal = new bootstrap.Modal(document.getElementById('modalEscuela'));
        modal.show();

        document.getElementById('formularioEditarRegistrar').addEventListener('submit', async function (e) {
            e.preventDefault(); // Evita que el formulario se envíe por defecto

            const form = e.target;
            const formData = new FormData(form); // Captura todos los datos del formulario

            try {
                const response = await fetch('../api/guardar_actualizar_escuela.php', {
                    method: 'POST',
                    body: formData
                });

                const resultado = await response.json();

                if (resultado.status) {
                    mostrarToast('success', resultado.message);
                    console.log(resultado.message)
                    // Opcional: cerrar modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalEscuela'));
                    modal.hide();
                    // Recargar tabla o lista de escuelas si corresponde
                    setTimeout(() => location.reload(), 1200);
                } else {
                    mostrarToast('warning', resultado.message || 'Error inesperado');
                }

            } catch (error) {
                mostrarToast('danger', 'Error de red o del servidor');
                console.error(error);
            }
        });


    }

    // Función para abrir modal en modo edición, recibe un objeto escuela con los datos
    function abrirModalEdicion(escuela) {
        document.getElementById('modalTitulo').textContent = 'Editar escuela';
        document.getElementById('modalBotonTexto').textContent = 'Actualizar';
        document.getElementById('escuela_id').value = escuela.id;
        document.getElementById('nombre').value = escuela.nombre;
        document.getElementById('ciudad').value = escuela.ciudad;


        const modal = new bootstrap.Modal(document.getElementById('modalEscuela'));
        modal.show();

        /* ----- capturamos y enviamos la actualizacion al backend--------- */
        document.getElementById('formularioEditarRegistrar').addEventListener('submit', async function (e) {
            e.preventDefault(); // Evita que el formulario se envíe por defecto

            const form = e.target;
            const formData = new FormData(form); // Captura todos los datos del formulario

            try {
                const response = await fetch('../api/guardar_actualizar_escuela.php', {
                    method: 'POST',
                    body: formData
                });

                const resultado = await response.json();

                if (resultado.status) {
                    mostrarToast('success', resultado.message);
                    console.log(resultado.message)
                    // Opcional: cerrar modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalEscuela'));
                    modal.hide();
                    // Recargar tabla o lista de escuelas si corresponde
                    setTimeout(() => location.reload(), 1200);
                } else {
                    mostrarToast('warning', resultado.message || 'Error inesperado');
                }

            } catch (error) {
                mostrarToast('danger', 'Error de red o del servidor');
                console.error(error);
            }
        });



    }


    function eliminarEscuela(idEscuela, escuela) {
        mostrarConfirmacionToast(
            `¿Estás seguro de que deseas eliminar la escuela ${escuela}?`,
            () => {
                const formData = new FormData();
                formData.append('id', idEscuela);

                fetch('../api/eliminar_escuela.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status) {
                            mostrarToast('success', data.message);
                            setTimeout(() => location.reload(), 1200);
                        } else {
                            mostrarToast('warning', data.message);
                        }
                    })
                    .catch(error => {
                         
                        mostrarToast('danger', 'Ocurrió un error al intentar eliminar la escuela.');
                    });
            }
        );
    }



</script>


<?php include_once('../includes/footer.php'); ?>