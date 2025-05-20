<?php


include_once("../includes/header.php");
include_once("../includes/sidebar.php");
try {
  // Consulta con JOINs para obtener todos los datos necesarios
  $stmt = $pdo->prepare("
        SELECT 
            e.*,
            esc.nombre AS escuela,
            c.nombre AS categoria 
        FROM estudiantes e
        LEFT JOIN escuelas_conduccion esc ON e.escuela_id = esc.id
        LEFT JOIN estudiante_categorias ec ON ec.estudiante_id = e.id
        LEFT JOIN categorias c ON ec.categoria_id = c.id 
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
        <button class="btn btn-primary" onclick="abrirModalRegistroEstudiante()">
          <i class="bi bi-person-plus-fill me-2"></i>
          Crear Nuevo
        </button>
        <!-- <a href="registrar_estudiantes.php" class="btn btn-light fw-semibold shadow-sm">
          <i class="bi bi-plus-circle me-2"></i>
        </a> -->
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
                  <td><?= htmlspecialchars($estudiante['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                    <?= htmlspecialchars($estudiante['apellidos'], ENT_QUOTES, 'UTF-8'); ?>
                  </td>
                  <td><?= htmlspecialchars($estudiante['dni'], ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><?= htmlspecialchars($estudiante['escuela'], ENT_QUOTES, 'UTF-8'); ?> </td>
                  <td>
                    <?= !empty(htmlspecialchars($estudiante['email'], ENT_QUOTES, 'UTF-8')) ? htmlspecialchars($estudiante['email'], ENT_QUOTES, 'UTF-8') : 'Sin definir' ?>
                  </td>
                  <td><?= htmlspecialchars($estudiante['fecha_nacimiento'], ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><?= htmlspecialchars($estudiante['telefono'], ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><?= htmlspecialchars($estudiante['direccion'], ENT_QUOTES, 'UTF-8'); ?> </td>
                  <td><?= htmlspecialchars($estudiante['categoria'], ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><?= htmlspecialchars($estudiante['usuario'], ENT_QUOTES, 'UTF-8'); ?></td>
                  <td class="text-center">
                    <?php if ($estudiante['estado'] === 'activo'): ?>
                      <button
                        class="btn btn-outline-success btn-sm d-flex align-items-center gap-2 px-3 py-1 rounded-pill shadow-sm"
                        title="Haz clic para desactivar"
                        onclick="cambiarEstadoEstudiante(<?= $estudiante['id'] ?>, 'inactivo')">
                        <i class="bi bi-toggle-on fs-5"></i>
                        Activo
                      </button>
                    <?php else: ?>
                      <button
                        class="btn btn-outline-danger btn-sm d-flex align-items-center gap-2 px-3 py-1 rounded-pill shadow-sm"
                        title="Haz clic para activar" onclick="cambiarEstadoEstudiante(<?= $estudiante['id'] ?>, 'activo')">
                        <i class="bi bi-toggle-off fs-5"></i>
                        Inactivo
                      </button>
                    <?php endif; ?>
                  </td>

                  <td class="text-center">
                    <div class="d-flex gap-2 justify-content-center flex-wrap">
                      <button class="btn btn-sm btn-outline-warning" onclick="abrirModalEdicionEstudiante({
                          id: <?= (int) $estudiante['id']; ?>,
                          nombre: '<?= addslashes(htmlspecialchars($estudiante['nombre'], ENT_QUOTES, 'UTF-8')); ?>',
                          apellidos: '<?= addslashes(htmlspecialchars($estudiante['apellidos'], ENT_QUOTES, 'UTF-8')); ?>',
                          escuela: '<?= addslashes(htmlspecialchars($estudiante['escuela'], ENT_QUOTES, 'UTF-8')); ?>',
                          fecha_nacimiento: '<?= addslashes(htmlspecialchars($estudiante['fecha_nacimiento'], ENT_QUOTES, 'UTF-8')); ?>',
                          telefono: '<?= addslashes(htmlspecialchars($estudiante['telefono'], ENT_QUOTES, 'UTF-8')); ?>',
                          direccion: '<?= addslashes(htmlspecialchars($estudiante['direccion'], ENT_QUOTES, 'UTF-8')); ?>',
                          categoria: '<?= addslashes(htmlspecialchars($estudiante['categoria'], ENT_QUOTES, 'UTF-8')); ?>',
                          usuario: '<?= addslashes(htmlspecialchars($estudiante['usuario'], ENT_QUOTES, 'UTF-8')); ?>',
                          email: '<?= addslashes(htmlspecialchars($estudiante['email'], ENT_QUOTES, 'UTF-8')); ?>',
                          dni: '<?= addslashes(htmlspecialchars($estudiante['dni'], ENT_QUOTES, 'UTF-8')); ?>'
                        })">
                        <i class="bi bi-pencil-square me-1"></i> Editar
                      </button>
                      <button class="btn btn-sm btn-outline-primary d-flex align-items-center gap-2   shadow-sm"
                        onclick="abrirModalCategorias(<?= $estudiante['id'] ?>, '<?= htmlspecialchars($estudiante['nombre'], ENT_QUOTES, 'UTF-8') ?>')"
                        title="Ver detalles de categorias del estudiante">
                        <i class="bi bi-eye "></i> Categorias
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

<!-- MODAL listado de categorías asignadas al estudiante basado en su ID -->
<div class="modal fade" id="modalAsignarCategoria" tabindex="-1" aria-labelledby="modalLabelCategoria"
  aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <!-- Encabezado del modal -->
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">
          <i class="bi bi-card-checklist me-2 text-white"></i>
           Categorías asignadas al estudiante: 
          <span id="nombreEstudiante" class="fw-semibold"></span>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div> 

      <!-- Cuerpo del modal -->
      <div class="modal-body">
        <table class="table table-bordered table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th><i class="bi bi-hash text-secondary me-1"></i>ID</th>
              <th><i class="bi bi-award-fill text-primary me-1"></i>Categoría</th>
              <th><i class="bi bi-check2-circle text-success me-1"></i>Estado</th>
              <th><i class="bi bi-calendar-event text-info me-1"></i>Fecha Asignación</th>
              <th><i class="bi bi-tools text-dark me-1"></i>Acciones</th>
            </tr>
          </thead>
          <tbody id="tablaCategoriasEstudiante">
            <tr>
              <td colspan="5" class="text-center text-muted">
                <i class="bi bi-hourglass-split me-2"></i>Cargando...
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>




<script>

  // Función para calcular edad a partir de fecha de nacimiento
  function calcularEdad(fechaNacimiento) {
    const hoy = new Date();
    const nacimiento = new Date(fechaNacimiento);
    let edad = hoy.getFullYear() - nacimiento.getFullYear();
    const m = hoy.getMonth() - nacimiento.getMonth();
    if (m < 0 || (m === 0 && hoy.getDate() < nacimiento.getDate())) {
      edad--;
    }
    return edad;
  }

  // Función para cargar y filtrar categorías por edad
  async function filtrarCategoriasPorEdad(edad) {
    const select = document.getElementById("categorias_id"); //capturamos el id del campo categoria

    try {
      const res = await fetch('../api/obtener_categorias.php');
      const result = await res.json();

      // Limpiar el select
      select.innerHTML = "<option value=''>Seleccione una categoría</option>";

      if (result.status && Array.isArray(result.data)) {
        let tieneOpciones = false;

        result.data.forEach(categoria => {
          if (edad >= categoria.edad_minima) {
            const option = document.createElement("option");
            option.value = categoria.id;
            option.textContent = categoria.nombre;
            select.appendChild(option);
            tieneOpciones = true;
          }
        });

        if (tieneOpciones) {
          select.disabled = false; //habilitar si existen categias para la edad ingresada en el campo fecha de nacimiento

        } else {
          select.disabled = true; //deshabilitar el campo categorias si no hay edad
          mostrarToast('warning', 'No hay ninguna categoría habilitada para la edad ingresada.')
        }

      } else {
        mostrarToast('warning', result.message || 'No se pudieron cargar las categorías.');
      }

    } catch (error) {
      console.error('Error cargando categorías:', error);
      mostrarToast('danger', 'Error al conectar con el servidor.');
    }
  }

  // Escuchar cambios en el campo de fecha de nacimiento
  document.getElementById("fecha_nacimiento").addEventListener("change", function () {
    const edad = calcularEdad(this.value);
    if (!isNaN(edad)) {
      filtrarCategoriasPorEdad(edad);
    }
  });

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

  // Abrir modal de edición
  function abrirModalEdicionEstudiante(estudiante) {
    document.getElementById('modalEstudianteTitulo').textContent = 'Editar Estudiante';
    document.getElementById('modalEstudianteBotonTexto').textContent = 'Actualizar';

    document.getElementById('estudiante_id').value = estudiante.id || '';
    document.getElementById('dni_estudiante').value = estudiante.dni || '';
    document.getElementById('nombre_estudiante').value = estudiante.nombre || '';
    document.getElementById('apellidos_estudiante').value = estudiante.apellidos || '';
    document.getElementById('email_estudiante').value = estudiante.email || '';
    document.getElementById('telefono_estudiante').value = estudiante.telefono || '';
    document.getElementById('fecha_nacimiento').value = estudiante.fecha_nacimiento || '';
    document.getElementById('direccion_estudiante').value = estudiante.direccion || '';
    document.getElementById('usuario_estudiante').value = estudiante.usuario || '';
    document.getElementById('contrasena_estudiante').value = '';

    // Seleccionar escuela si está presente
    if (estudiante.escuela_id) {
      document.getElementById('escuela_id').value = estudiante.escuela_id;
    }

    // Mostrar switch activo solo si se edita
    if ('estado' in estudiante) {
      document.getElementById('activo-estudiante-container').classList.remove('d-none');
      document.getElementById('activo_estudiante').checked = estudiante.estado === 'activo' || estudiante.estado === 1;
    }

    // Mostrar opciones de categoría según edad si fecha está presente
    if (estudiante.fecha_nacimiento) {
      const edad = calcularEdad(estudiante.fecha_nacimiento);
      filtrarCategoriasPorEdad(edad);
    }

    if (estudiante.escuela_id) {
      document.getElementById('escuela_id').value = estudiante.escuela_id;
    }

    const modal = new bootstrap.Modal(document.getElementById('modalEstudiante'));
    modal.show();

    configurarSubmitEstudiante();
  }

  // Envío del formulario
  function configurarSubmitEstudiante() {
    const form = document.getElementById('formularioEstudiante');

    form.onsubmit = async function (e) {
      e.preventDefault();
      if (!form.checkValidity()) {
        form.classList.add('was-validated');
        return;
      }

      const formData = new FormData(form);

      try {
        const response = await fetch('../api/guardar_actualizar_estudiantes.php', {
          method: 'POST',
          body: formData
        });

        const resultado = await response.json();

        if (resultado.status) {
          mostrarToast('success', resultado.message);
          bootstrap.Modal.getInstance(document.getElementById('modalEstudiante')).hide();
          setTimeout(() => location.reload(), 1200);
        } else {
          mostrarToast('warning', resultado.message || 'Error inesperado');
        }
      } catch (error) {
        console.error(error);
        mostrarToast('danger', 'Error de red o del servidor');
      }
    };
  }

  // Eliminar estudiante
  function eliminarEstudiante(id, nombre) {
    mostrarConfirmacionToast(
      `¿Estás seguro de que deseas eliminar al estudiante "${nombre}"?`,
      () => {
        const formData = new FormData();
        formData.append('id', id);

        fetch('../api/eliminar_estudiante.php', {
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
          .catch(err => {
            console.error(err);
            mostrarToast('danger', 'Error al intentar eliminar el estudiante.');
          });
      }
    );
  }

  // Cargar opciones dinámicas de escuela
  async function cargarEscuelas() {
    try {
      const res = await fetch('../api/obtener_escuelas.php');
      const result = await res.json();

      // Validar que la respuesta sea exitosa
      if (result.status) {
        const select = document.getElementById('escuela_id');
        select.innerHTML = '<option value="">Selecciona una escuela</option>';

        result.data.forEach(escuela => {
          const option = document.createElement('option');
          option.value = escuela.id;
          option.textContent = escuela.nombre;
          select.appendChild(option);
        });
      } else {
        // En caso de que status sea false, muestra el mensaje del backend
        mostrarToast('warning', result.message || 'No se pudo cargar la lista de escuelas.');
      }
    } catch (error) {
      console.error('Error cargando escuelas:', error);
      mostrarToast('danger', 'Error al conectar con el servidor.');
    }
  }



  // Ejecutar al cargar la página
  document.addEventListener('DOMContentLoaded', cargarEscuelas);

  // modal de confirmacion al dar clic sobre el boton categoria
  /*  function confirmarAsignacionCategoria(estudianteId, nombreEstudiante) {
     mostrarConfirmacionToast(
       `¿Deseas asignar una categoría a ${nombreEstudiante}?`,
       () => abrirModalCategorias(estudianteId, nombreEstudiante)
     );
   } */

  function abrirModalCategorias(estudianteId, nombreEstudiante) {
    // Mostrar modal
    const modal = new bootstrap.Modal(document.getElementById('modalAsignarCategoria'));
    modal.show();

    // Mostrar nombre
    document.getElementById('nombreEstudiante').textContent = nombreEstudiante;

    // Cargar categorías asignadas
    fetch(`../api/obtener_categorias_estudiante.php?estudiante_id=${estudianteId}`)
      .then(res => res.json())
      .then(data => {
        const tbody = document.getElementById('tablaCategoriasEstudiante');
        tbody.innerHTML = '';

        if (data.status && data.data.length > 0) {
          data.data.forEach(cat => {
            tbody.innerHTML += `
                            <tr>
                              <td>${cat.id}</td>
                              <td>${cat.categoria}</td>
                              <td><span class="badge bg-${estadoColor(cat.estado)}">${cat.estado}</span></td>
                              <td>${cat.fecha_asignacion}</td> 
                              
                              <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary me-1" title="Asignar nueva categoría"
                                  onclick="asignarNuevaCategoria(${cat.estudiante_id})">
                                  <i class="bi bi-plus-circle"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-warning me-1" title="Editar asignación"
                                  onclick="editarCategoriaAsignada(${cat.id})">
                                  <i class="bi bi-pencil-square"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" title="Eliminar asignación"
                                  onclick="eliminarCategoriaAsignada(${cat.id})">
                                  <i class="bi bi-trash"></i>
                                </button>
                              </td>
                            </tr>
                            `;

          });
        } else {
          tbody.innerHTML = `<tr><td colspan="5" class="text-center text-muted">No tiene categorías asignadas</td></tr>`;
        }
      })
      .catch(err => {
        console.error(err);
        document.getElementById('tablaCategoriasEstudiante').innerHTML =
          `<tr><td colspan="4" class="text-center text-danger">Error al cargar datos</td></tr>`;
      });
  }

  function estadoColor(estado) {
    switch (estado) {
      case 'aprobado': return 'success';
      case 'rechazado': return 'danger';
      case 'en_proceso': return 'warning';
      default: return 'secondary';
    }
  }





/* function mostrarCategoriasEstudiante(estudiante_id) {
  fetch(`api/obtener_categorias_estudiante.php?estudiante_id=${estudiante_id}`)
    .then(res => res.json())
    .then(data => {
      const tbody = document.getElementById('categoriasEstudianteBody');
      tbody.innerHTML = '';
      data.forEach(cat => {
        tbody.innerHTML += `
          <tr>
            <td>${cat.id}</td>
            <td>${cat.categoria}</td>
            <td><span class="badge bg-${estadoColor(cat.estado)}">${cat.estado}</span></td>
            <td>${cat.fecha_asignacion}</td>
            <td class="text-center">
              <button class="btn btn-sm btn-outline-primary me-1" title="Nueva categoría"
                onclick="asignarNuevaCategoria(${cat.estudiante_id})">
                <i class="bi bi-plus-circle"></i>
              </button>
              <button class="btn btn-sm btn-outline-warning me-1" title="Editar asignación"
                onclick="editarCategoriaAsignada(${cat.id})">
                <i class="bi bi-pencil-square"></i>
              </button>
              <button class="btn btn-sm btn-outline-danger" title="Eliminar asignación"
                onclick="eliminarCategoriaAsignada(${cat.id})">
                <i class="bi bi-trash"></i>
              </button>
            </td>
          </tr>
        `;
      });
      const modal = new bootstrap.Modal(document.getElementById('modalCategoriaEstudiante'));
      modal.show();
    });
}
 */
function asignarCategoriaEstudiante(estudiante_id, nombre_estudiante) {
  mostrarConfirmacionToast(`¿Deseas asignar categoría a ${nombre_estudiante}?`, () => {
    mostrarCategoriasEstudiante(estudiante_id);
  });
}

function asignarNuevaCategoria(estudiante_id) {
  // Aquí podrías abrir otro modal con un <select> de categorías disponibles y un botón de "Guardar"
  alert(`Abrir modal para asignar nueva categoría a estudiante ID ${estudiante_id}`);
}

function editarCategoriaAsignada(asignacion_id) {
  alert(`Abrir modal de edición para asignación ID ${asignacion_id}`);
}

function eliminarCategoriaAsignada(asignacion_id) {
  if (confirm("¿Estás seguro de eliminar esta asignación?")) {
    fetch(`api/eliminar_categoria_asignada.php`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id: asignacion_id })
    })
      .then(res => res.json())
      .then(resp => {
        if (resp.status) {
          alert("Asignación eliminada.");
          document.querySelector(`#modalCategoriaEstudiante`).querySelector('.modal-body').scrollTop = 0;
          mostrarCategoriasEstudiante(resp.estudiante_id);
        } else {
          alert("Error: " + resp.message);
        }
      });
  }
}




</script>





<?php include_once('../includes/footer.php'); ?>