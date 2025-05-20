<?php
include_once("../includes/header.php");
include_once("../includes/sidebar.php");
?>
<div class="main-content">
    <!-- Modal de Alerta -->
    <?php if ($alerta): ?>
        <div class="modal fade show" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="false"
            style="display: block;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header <?php echo $alerta['tipo'] == 'success' ? 'bg-success' : 'bg-danger'; ?>">
                        <h5 class="modal-title text-white" id="alertModalLabel">
                            <?php echo $alerta['tipo'] == 'success' ? '¡Éxito!' : 'Error'; ?>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-center"><?php echo $alerta['mensaje']; ?></p>
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
                class="card-header d-flex flex-wrap justify-content-between align-items-center rounded-top-4 px-4 py-3 bg-white border-bottom">
                <h5 class="mb-0 fw-semibold">
                    <i class="bi bi-question-circle-fill me-2 text-primary"></i>Listado de preguntas
                </h5>
                <div class="search-box position-relative">
                    <input type="text" class="form-control ps-5" id="customSearch" placeholder="Buscar pregunta...">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                </div>
                <div class="d-flex flex-wrap gap-5 align-items-center">
                    <div class="d-flex align-items-center">
                        <label for="container-length" class="me-2 fw-medium mb-0">
                            <i class="bi bi-eye-fill me-1"></i>Mostrar:
                        </label>
                        <select id="container-length" class="form-select w-auto shadow-sm">
                            <option value="5">5 registros</option>
                            <option value="10" selected>10 registros</option>
                            <option value="15">15 registros</option>
                            <option value="20">20 registros</option>
                            <option value="25">25 registros</option>
                        </select>
                    </div>
                    <button class="btn btn-primary" onclick="abrirModalRegistroEstudiante()">
                        <i class="bi bi-person-plus-fill me-2"></i>
                        Crear Nuevo
                    </button>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="container-table" class="table table-striped table-hover align-middle">
                        <thead class="table-light">
                            <?php if (!empty($preguntas)): ?>
                                <tr>
                                    <th><i class="bi bi-hash me-1"></i>ID</th>
                                    <th><i class="bi bi-journal-text me-1"></i>Examen</th>
                                    <th><i class="bi bi-chat-left-dots me-1"></i>Pregunta</th>
                                    <th><i class="bi bi-ui-checks me-1"></i>Tipo</th>
                                    <th><i class="bi bi-file-earmark-image me-1"></i>Contenido</th>
                                    <th><i class="bi bi-calendar3 me-1"></i>Registro</th>
                                    <th><i class="bi bi-gear-fill me-1"></i>Acciones</th>
                                </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($preguntas as $pregunta): ?>
                                <tr>
                                    <td class="text-center"><?= htmlspecialchars($pregunta['id']) ?></td>
                                    <td><?= htmlspecialchars($pregunta['examen']) ?></td>
                                    <td>
                                        <?php if ($pregunta['tipo_contenido'] === 'imagen'): ?>
                                            <?php foreach ($pregunta['imagenes'] as $img): ?>
                                                <img src="<?= htmlspecialchars($img) ?>" alt="img" class="img-thumbnail me-1"
                                                    style="width: 70px; height: auto;">
                                            <?php endforeach; ?>
                                            <div class="mt-2 text-muted small">
                                                <i class="bi bi-card-text me-1"></i>
                                                <?= nl2br(htmlspecialchars($pregunta['texto_pregunta'])) ?>
                                            </div>
                                        <?php else: ?>
                                            <i class="bi bi-card-text me-1"></i>
                                            <?= nl2br(htmlspecialchars($pregunta['texto_pregunta'])) ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        $tipos = [
                                            'multiple' => 'Opción Múltiple',
                                            'unica' => 'Respuesta Única',
                                            'vf' => 'Verdadero / Falso'
                                        ];
                                        echo $tipos[$pregunta['tipo_pregunta']] ?? 'Desconocido';
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($pregunta['tipo_contenido'] === 'ilustracion'): ?>
                                            <span class="badge badge-contenido badge-ilustracion"><i
                                                    class="bi bi-image me-1"></i>Ilustración</span>
                                        <?php else: ?>
                                            <span class="badge badge-contenido badge-texto"><i
                                                    class="bi bi-card-text me-1"></i>Texto</span>
                                        <?php endif; ?>

                                    </td>
                                    <td class="text-center">
                                        <i
                                            class="bi bi-clock me-1"></i><?= date('d/m/Y H:i', strtotime($pregunta['fecha_creacion'])) ?>
                                    </td>
                                    <td class="text-center dropdown-actions">
                                        <!-- Botones visibles en escritorio -->
                                        <a href="detalles_preguntas.php?id=<?= urlencode($pregunta['id']) ?>"
                                            class="btn btn-sm btn-outline-success me-1 d-none d-md-inline-flex">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="editar_pregunta.php?id=<?= urlencode($pregunta['id']) ?>"
                                            class="btn btn-sm btn-outline-primary me-1 d-none d-md-inline-flex">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="../php/eliminar_pregunta.php?id=<?= urlencode($pregunta['id']) ?>"
                                            class="btn btn-sm btn-outline-danger d-none d-md-inline-flex"
                                            onclick="return confirm('¿Estás seguro de eliminar esta pregunta? Esta acción no se puede deshacer.')">
                                            <i class="bi bi-trash"></i>
                                        </a>

                                        <!-- Dropdown en móviles -->
                                        <div class="dropdown d-md-none">
                                            <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                                data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="editar_pregunta.php?id=<?= urlencode($pregunta['id']) ?>">
                                                        <i class="bi bi-pencil-square me-2"></i>Editar
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item text-danger"
                                                        href="../php/eliminar_pregunta.php?id=<?= urlencode($pregunta['id']) ?>"
                                                        onclick="return confirm('¿Estás seguro de eliminar esta pregunta? Esta acción no se puede deshacer.')">
                                                        <i class="bi bi-trash-fill me-2"></i>Eliminar
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>

                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="alert alert-warning text-center mt-3">
                                <i class="bi bi-exclamation-circle-fill me-2"></i>⚠️ No hay preguntas registradas
                                actualmente.
                            </div>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Modal Registro / Edición Estudiante -->
<div class="modal fade" id="modalEstudiante" tabindex="-1" aria-labelledby="modalEstudianteLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow rounded-4">
      <div class="modal-header bg-primary text-white rounded-top">
        <h5 class="modal-title" id="modalEstudianteLabel">
          <i class="bi bi-person-plus-fill me-2"></i><span id="modalEstudianteTitulo">Registrar Estudiante</span>
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <form id="formularioEstudiante" method="POST" class="needs-validation" novalidate>
        <div class="row modal-body p-4 ">
          <input type="hidden" name="estudiante_id" id="estudiante_id">

          <!-- DNI -->
          <div class="mb-3 col-12 col-md-6">
            <label for="dni_estudiante" class="form-label fw-semibold">
              <i class="bi bi-card-text me-2 text-primary"></i>DNI <span class="text-danger">*</span>
            </label>
            <input type="text" class="form-control shadow-sm" id="dni_estudiante" name="dni" required>
            <div class="invalid-feedback">Por favor ingresa el DNI del estudiante.</div>
          </div>

          <!-- Nombres -->
          <div class="mb-3 col-12 col-md-6">
            <label for="nombre_estudiante" class="form-label fw-semibold">
              <i class="bi bi-person-fill me-2 text-primary"></i>Nombres <span class="text-danger">*</span>
            </label>
            <input type="text" class="form-control shadow-sm" id="nombre_estudiante" name="nombre" required>
            <div class="invalid-feedback">Por favor ingresa el nombre.</div>
          </div>

          <!-- Apellidos -->
          <div class="mb-3 col-12 col-md-6">
            <label for="apellidos_estudiante" class="form-label fw-semibold">
              <i class="bi bi-person-vcard me-2 text-primary"></i>Apellidos
            </label>
            <input type="text" class="form-control shadow-sm" id="apellidos_estudiante" name="apellidos">
          </div>

          <!-- Email -->
          <div class="mb-3 col-12 col-md-6">
            <label for="email_estudiante" class="form-label fw-semibold">
              <i class="bi bi-envelope-fill me-2 text-primary"></i>Email (Opcional)
            </label>
            <input type="email" class="form-control shadow-sm" id="email_estudiante" name="email">
          </div>

          <!-- Teléfono -->
          <div class="mb-3 col-12 col-md-6">
            <label for="telefono_estudiante" class="form-label fw-semibold">
              <i class="bi bi-telephone-fill me-2 text-primary"></i>Teléfono
            </label>
            <input type="text" class="form-control shadow-sm" id="telefono_estudiante" name="telefono">
          </div>

          <!-- Fecha de nacimiento -->
          <div class="mb-3 col-12 col-md-6">
            <label for="fecha_nacimiento" class="form-label fw-semibold">
              <i class="bi bi-calendar-date-fill me-2 text-primary"></i>Fecha de Nacimiento
            </label>
            <input type="date" class="form-control shadow-sm" id="fecha_nacimiento" name="fecha_nacimiento">
          </div>

          <!-- Dirección -->
          <div class="mb-3 col-12 col-md-6">
            <label for="direccion_estudiante" class="form-label fw-semibold">
              <i class="bi bi-geo-alt-fill me-2 text-primary"></i>Dirección
            </label>
            <textarea class="form-control shadow-sm" id="direccion_estudiante" name="direccion" rows="2"></textarea>
          </div>

          <!-- Escuela de conducción -->
          <div class="mb-3 col-12 col-md-6">
            <label for="escuela_id" class="form-label fw-semibold">
              <i class="bi bi-building me-2 text-primary"></i>Escuela de Conducción
            </label>
            <select class="form-select shadow-sm" id="escuela_id" name="escuela_id">
              <option value="">Selecciona una escuela</option>
              <!-- Opciones se llenan dinámicamente desde backend -->
            </select>
          </div>


          <!-- Categoría de Carné -->
          <div class="mb-3 col-12 col-md-6">
            <label for="categorias_id" class="form-label fw-semibold">
              <i class="bi bi-card-list me-2 text-primary"></i>Categoría de Carné <span class="text-danger">*</span>
            </label>
            <select name="categoria_id" id="categorias_id" class="form-select" disabled required>
              <option value="">Seleccione una categoría</option>
            </select>
            <div class="invalid-feedback">
              Por favor selecciona una categoría de carné.
            </div>
          </div>

          <!-- Usuario -->
          <!--  <div class="mb-3 col-12 col-md-6">
            <label for="usuario_estudiante" class="form-label fw-semibold">
              <i class="bi bi-person-badge-fill me-2 text-primary"></i>Usuario <span class="text-danger">*</span>
            </label>
            <input type="text" class="form-control shadow-sm" id="usuario_estudiante" name="usuario" required>
            <div class="invalid-feedback">Por favor asigna un nombre de usuario único.</div>
          </div> -->


          <!-- Estado (solo en edición) -->
          <div class="form-check form-switch mb-3 col-12 col-md-6 d-none" id="activo-estudiante-container">
            <input class="form-check-input" type="checkbox" id="activo_estudiante" name="estado" value="activo">
            <label class="form-check-label fw-semibold" for="activo_estudiante">Estudiante activo</label>
          </div>
        </div>

        <div class="modal-footer bg-light p-3">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-2"></i>Cancelar
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-save2-fill me-2"></i><span id="modalEstudianteBotonTexto">Registrar</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>



<script>   

  // Abrir modal de registro
  function abrirModalRegistroEstudiante() {
    document.getElementById('modalEstudianteTitulo').textContent = 'Registrar Estudiante';
    document.getElementById('modalEstudianteBotonTexto').textContent = 'Registrar';
    document.getElementById('formularioEstudiante').reset();
    document.getElementById('estudiante_id').value = '';
    document.getElementById('activo-estudiante-container').classList.add('d-none');

    const modal = new bootstrap.Modal(document.getElementById('modalEstudiante'));
    modal.show();

    configurarSubmitEstudiante();
  }
</script>
<?php include_once('../includes/footer.php'); ?>