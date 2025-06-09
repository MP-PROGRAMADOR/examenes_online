<?php
include_once("../includes/header.php");
include_once("../includes/sidebar.php");

try {
  $limite = isset($_GET['limite']) ? (int) $_GET['limite'] : 10;
  $pagina = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
  $inicio = ($pagina - 1) * $limite;

  // Contar total de estudiantes
  $total_sql = "SELECT COUNT(*) FROM estudiantes";
  $total_stmt = $pdo->query($total_sql);
  $total_registros = $total_stmt->fetchColumn();
  $total_paginas = ceil($total_registros / $limite);

  // Consulta paginada con joins
  // Consulta con categorías agrupadas
  $sql = "
  SELECT 
    e.*, 
    esc.nombre AS escuela, 
    c.nombre AS categoria, 
    ec.categoria_id
  FROM estudiantes e
  LEFT JOIN escuelas_conduccion esc ON e.escuela_id = esc.id
  LEFT JOIN estudiante_categorias ec ON ec.estudiante_id = e.id
  LEFT JOIN categorias c ON ec.categoria_id = c.id
  ORDER BY e.id DESC
  LIMIT :inicio, :limite
";
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
  $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
  $stmt->execute();
  $raw = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Agrupar categorías por estudiante ID
  $estudiantes = [];
  foreach ($raw as $row) {
    $id = $row['id'];
    if (!isset($estudiantes[$id])) {
      $estudiantes[$id] = $row;
      $estudiantes[$id]['categorias'] = [];
    }
    if ($row['categoria']) {
      $estudiantes[$id]['categorias'][] = $row['categoria'];
    }
  }


} catch (PDOException $e) {
  error_log("Error al obtener estudiantes: " . $e->getMessage());
  $estudiantes = [];
}
?>

<main class="main-content" id="content">
  <div class="card shadow-sm mb-4">
    <div
      class="card-header bg-primary text-white d-flex flex-wrap align-items-center justify-content-between gap-3 p-3 rounded-top">
      <h5 class="mb-0 d-flex align-items-center">
        <i class="bi bi-people-fill me-2"></i>Listado de Estudiantes
      </h5>

      <div class="d-flex align-items-center gap-3 flex-grow-1 flex-wrap justify-content-end">
        <div class="position-relative">
          <input type="text" class="form-control ps-5 form-control-sm shadow-sm" id="customSearch"
            placeholder="Buscar estudiante...">
          <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
        </div>

        <div class="d-flex align-items-center gap-2">
          <label for="container-length" class="mb-0 fw-semibold text-white">Mostrar:</label>
          <select id="container-length" class="form-select form-select-sm w-auto shadow-sm">
            <?php foreach ([5, 10, 15, 20, 25] as $op): ?>
              <option value="<?= $op ?>" <?= $limite == $op ? 'selected' : '' ?>><?= $op ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <button class="btn btn-light fw-semibold shadow-sm" onclick="abrirModalRegistroEstudiante()">
          <i class="bi bi-person-plus-fill me-1"></i>Nuevo
        </button>
      </div>
    </div>

    <!-- Tabla -->
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle mb-0">
          <thead class="table-primary text-center">
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>DNI</th>
              <th>Escuela</th>
              <th>Email</th>
              <th>Nacimiento</th>
              <th>Teléfono</th>
              <th>Dirección</th>
              <th>Categoría</th>
              <th>Código</th>
              <th>Estado</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($estudiantes)): ?>
              <?php foreach ($estudiantes as $est): ?>
                <tr>
                  <td class="text-center"><?= htmlspecialchars($est['id']) ?></td>
                  <td><?= htmlspecialchars($est['nombre'] . ' ' . $est['apellidos']) ?></td>
                  <td><?= htmlspecialchars($est['dni']) ?></td>
                  <td><?= htmlspecialchars($est['escuela'] ?? '—') ?></td>
                  <td><?= htmlspecialchars($est['email'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($est['fecha_nacimiento']) ?></td>
                  <td><?= htmlspecialchars($est['telefono']) ?></td>
                  <td><?= htmlspecialchars($est['direccion']) ?></td>
                  <td>
                    <?php if (!empty($est['categorias'])): ?>
                      <ul class="list-unstyled mb-0">
                        <?php foreach ($est['categorias'] as $cat): ?>
                          <li><i class="bi bi-bookmark-check-fill text-success me-1"></i> <?= htmlspecialchars($cat) ?></li>
                        <?php endforeach; ?>
                      </ul>
                    <?php else: ?>
                      <span class="text-muted">—</span>
                    <?php endif; ?>
                  </td>

                  <td><?= htmlspecialchars($est['usuario']) ?></td>

                  <td class="text-center">
                    <?php if ($est['estado'] === 'activo'): ?>
                      <button
                        class="btn btn-outline-success btn-sm d-flex align-items-center gap-2 px-3 py-1 rounded-pill shadow-sm"
                        title="Haz clic para desactivar"
                        onclick="cambiarEstadoEstudiante(<?= $est['id'] ?>, '<?= $est['nombre'] ?>', 'inactivo')">
                        <i class="bi bi-toggle-on fs-5"></i>
                        Activo
                      </button>
                    <?php else: ?>
                      <button
                        class="btn btn-outline-danger btn-sm d-flex align-items-center gap-2 px-3 py-1 rounded-pill shadow-sm"
                        title="Haz clic para activar"
                        onclick="cambiarEstadoEstudiante(<?= $est['id'] ?>, '<?= $est['nombre'] ?>', 'activo')">
                        <i class="bi bi-toggle-off fs-5"></i>
                        Inactivo
                      </button>
                    <?php endif; ?>
                  </td>

                  <td class="text-center">
                    <button class="btn btn-sm btn-outline-warning me-1" title="Editar"
                      onclick='abrirModalEdicionEstudiante(<?= json_encode($est, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'">
                                  <i class=" bi bi-pencil-square"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger" title="Eliminar"
                      onclick="eliminarEstudiante(<?= $est['id'] ?>)">
                      <i class="bi bi-trash"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-primary d-flex align-items-center gap-2   shadow-sm"
                      onclick="abrirModalCategorias(<?= $est['id'] ?>, '<?= htmlspecialchars($est['nombre'], ENT_QUOTES, 'UTF-8') ?>',  '<?= htmlspecialchars($est['fecha_nacimiento'], ENT_QUOTES, 'UTF-8') ?>')"
                      title="Ver detalles de categorias del estudiante">
                      <i class="bi bi-eye "></i> Categorias
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="12">
                  <div class="alert alert-warning text-center m-0 rounded-0">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>No hay estudiantes registrados.
                  </div>
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <?php if ($total_paginas > 1): ?>
        <nav aria-label="Paginación de estudiantes" class="my-3">
          <ul class="pagination justify-content-center">
            <li class="page-item <?= $pagina <= 1 ? 'disabled' : '' ?>">
              <a class="page-link" href="?pagina=<?= $pagina - 1 ?>&limite=<?= $limite ?>">Anterior</a>
            </li>
            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
              <li class="page-item <?= $pagina == $i ? 'active' : '' ?>">
                <a class="page-link" href="?pagina=<?= $i ?>&limite=<?= $limite ?>"><?= $i ?></a>
              </li>
            <?php endfor; ?>
            <li class="page-item <?= $pagina >= $total_paginas ? 'disabled' : '' ?>">
              <a class="page-link" href="?pagina=<?= $pagina + 1 ?>&limite=<?= $limite ?>">Siguiente</a>
            </li>
          </ul>
        </nav>
      <?php endif; ?>
    </div>
  </div>

</main>

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
            <input type="text" class="form-control shadow-sm" id="apellidos_estudiante" name="apellidos" required>
          </div>

          <!-- Email -->
          <div class="mb-3 col-12 col-md-6">
            <label for="email_estudiante" class="form-label fw-semibold">
              <i class="bi bi-envelope-fill me-2 text-primary"></i>Email (Opcional)
            </label>
            <input type="email" class="form-control shadow-sm" id="email_estudiante" name="email" required>
          </div>

          <!-- Teléfono -->
          <div class="mb-3 col-12 col-md-6">
            <label for="telefono_estudiante" class="form-label fw-semibold">
              <i class="bi bi-telephone-fill me-2 text-primary"></i>Teléfono
            </label>
            <input type="text" class="form-control shadow-sm" id="telefono_estudiante" name="telefono" required>
          </div>

          <!-- Fecha de nacimiento -->
          <div class="mb-3 col-12 col-md-6">
            <label for="fecha_nacimiento" class="form-label fw-semibold">
              <i class="bi bi-calendar-date-fill me-2 text-primary"></i>Fecha de Nacimiento
            </label>
            <input type="date" class="form-control shadow-sm" id="fecha_nacimiento" name="fecha_nacimiento" required>
          </div>

          <!-- Dirección -->
          <div class="mb-3 col-12 col-md-6">
            <label for="direccion_estudiante" class="form-label fw-semibold">
              <i class="bi bi-geo-alt-fill me-2 text-primary"></i>Dirección
            </label>
            <textarea class="form-control shadow-sm" id="direccion_estudiante" name="direccion" rows="2"
              required></textarea>
          </div>

          <!-- Escuela de conducción -->
          <div class="mb-3 col-12 col-md-6">
            <label for="escuela_id" class="form-label fw-semibold">
              <i class="bi bi-building me-2 text-primary"></i>Escuela de Conducción
            </label>
            <select class="form-select shadow-sm" id="escuela_id" name="escuela_id" required>
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



          <div class="mb-3 col-12 col-md-6">
            <label for="categorias_id" class="form-label fw-semibold">
              <i class="bi bi-file-earmark-text me-2 text-primary"></i>Número de Documento <span
                class="text-danger">*</span>
            </label>
            <input type="text" class="form-control shadow-sm" id="num" name="num">
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
        <!-- Sección de Asignación Nueva -->
        <div id="seccionNuevaCategoria" class="mb-4 d-none">
          <form id="formNuevaCategoria">
            <p class="h3" id="edad"></p>
            <input type="hidden" name="estudiante_id" id="nuevo_estudiante_id" value="">
            <div class="row g-2 align-items-center">
              <div class="col-md-8">
                <label class="form-label">Selecciona nueva categoría:</label>
                <select class="form-select" name="categoria_id" id="selectNuevaCategoria" required>
                  <option value="">-- Seleccionar --</option>
                </select>
              </div>
              <div class="col-md-4">
                <button type="submit" class="btn btn-success w-100 mt-4">
                  <i class="bi bi-plus-circle me-1"></i>Guardar Categoría
                </button>
              </div>
            </div>
          </form>
        </div>

        <!-- Tabla de Categorías Asignadas -->
        <div class="d-flex justify-content-end mb-2">
          <button class="btn btn-sm btn-outline-primary" onclick="mostrarFormularioNuevaCategoria()">
            <i class="bi bi-plus-circle me-1"></i> Nueva Categoría
          </button>
        </div>
        <div class="table-responsive">
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
</div>


<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
  $(document).ready(function () {
    function filterTable() {
      const search = $("#customSearch").val().toLowerCase();
      let count = 0;

      $("table tbody tr").each(function () {
        const rowText = $(this).text().toLowerCase();
        if (rowText.includes(search)) {
          $(this).show();
          count++;
        } else {
          $(this).hide();
        }
      });

      if (count === 0) {
        if ($("#no-results").length === 0) {
          $("table tbody").append(`
                            <tr id="no-results">
                                <td colspan="12">
                                    <div class="alert alert-info text-center m-0 rounded-0">
                                        <i class="bi bi-info-circle-fill me-2"></i>No se encontraron resultados.
                                    </div>
                                </td>
                            </tr>
                        `);
        }
      } else {
        $("#no-results").remove();
      }
    }

    $("#customSearch").on("input", filterTable);

    $('#container-length').on('change', function () {
      const selectedLimit = $(this).val();
      window.location.href = `?pagina=1&limite=${selectedLimit}`;
    });

    filterTable();
  });


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
  document.addEventListener('DOMContentLoaded', cargarEscuelas, cargarCategoriasEstudiante);

  let estudianteEdadGlobal = null;

  function abrirModalCategorias(estudianteId, nombreEstudiante, fecha_nacimiento) {
    // Mostrar modal
    const modal = new bootstrap.Modal(document.getElementById('modalAsignarCategoria'));
    modal.show();
    estudianteEdadGlobal = calcularEdad(fecha_nacimiento);
    // Mostrar nombre  

    document.getElementById('nombreEstudiante').textContent = nombreEstudiante;
    document.getElementById('edad').textContent = ` ${estudianteEdadGlobal} Años`;
    document.getElementById('nuevo_estudiante_id').value = estudianteId;
    cargarCategoriasEstudiante(estudianteId)


  }

  // Cargar categorías asignadas
  function cargarCategoriasEstudiante(estudianteId) {
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
                                
                                
                                <button class="btn btn-sm btn-outline-danger" title="Eliminar asignación"
                                  onclick="eliminarCategoriaAsignada(${cat.id}, ${cat.estudiante_id})">
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
      case 'pendiente': return 'primary';
      case 'rechazado': return 'danger';
      case 'en_proceso': return 'warning';
      default: return 'secondary';
    }
  }


  let estudianteIdGlobal = null;

  function asignarCategoriaEstudiante(estudiante_id, nombre_estudiante) {
    mostrarConfirmacionToast(`¿Deseas asignar categoría a ${nombre_estudiante}?`, () => {
      estudianteIdGlobal = estudiante_id;
      document.getElementById('nombreEstudianteModal').textContent = nombre_estudiante;
      document.getElementById('nuevo_estudiante_id').value = estudiante_id;
      ocultarFormularioNuevaCategoria();
      mostrarCategoriasEstudiante(estudiante_id);
      const modal = new bootstrap.Modal(document.getElementById('modalCategoriasEstudiante'));
      modal.show();
    });
  }

  function mostrarFormularioNuevaCategoria() {
    const seccion = document.getElementById('seccionNuevaCategoria');
    seccion.classList.remove('d-none');
    cargarCategoriasDisponibles();
  }

  function ocultarFormularioNuevaCategoria() {
    document.getElementById('seccionNuevaCategoria').classList.add('d-none');
  }


  function cargarCategoriasDisponibles() {
    fetch('../api/obtener_categorias.php')
      .then(res => res.json())
      .then(data => {
        const select = document.getElementById('selectNuevaCategoria');
        select.innerHTML = '<option value="">-- Seleccionar --</option>';

        data.data.forEach(cat => {
          // Suponiendo que cada categoría tenga campos: edad_minima y edad_maxima
          if (estudianteEdadGlobal >= cat.edad_minima) {
            select.innerHTML += `<option value="${cat.id}">${cat.nombre}</option>`;
          }
        });

        if (select.options.length === 1) {
          select.innerHTML += '<option disabled>No hay categorías disponibles para esta edad</option>';
        }
      });
  }





  document.getElementById('formNuevaCategoria').addEventListener('submit', function (e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    console.log(form);
    fetch('../api/guardar_categoria_estudiante.php', {
      method: 'POST',
      body: formData
    })
      .then(res => res.json())
      .then(data => {
        if (data.status) {
          mostrarToast('success', 'Categoría asignada correctamente');
          ocultarFormularioNuevaCategoria();
          // mostrarCategoriasEstudiante('success', formData.get('estudiante_id')); // Refresca tabla
          setTimeout(() => location.reload(), 1200);
        } else {
          mostrarToast('warning', data.message || 'Error al guardar la categoría');
        }
      })
      .catch(error => {
        console.error(error);
        mostrarToast('Error de red o servidor al guardar', 'danger');
      });
  });


  function eliminarCategoriaAsignada(asignacion_id, estudiante_id) {
    mostrarConfirmacionToast(`¿Estás seguro de eliminar esta asignación?`, () => {
      fetch('../api/eliminar_categoria_estudiante.php', {
        method: 'POST',
        body: new URLSearchParams({ asignacion_id })
      })
        .then(res => res.json())
        .then(data => {
          if (data.status) {
            // abrirModalCategorias(estudianteIdGlobal);
            cargarCategoriasEstudiante(estudiante_id)
            mostrarToast('success', data.message);
          } else {
            mostrarToast('warning', data.message || 'Error al eliminar.');
          }
        });
    });
  }



  function cambiarEstadoEstudiante(id, nombre, nuevoEstado) {
    mostrarConfirmacionToast(
      `¿Estás seguro de que deseas ${nuevoEstado == "activo" ? "activar" : "desactivar"} al estudiante: ${nombre} ?`,
      () => {

        const formData = new FormData();
        formData.append('id', id);
        formData.append('estado', nuevoEstado);

        fetch('../api/cambiar_estado_estudiante.php', {
          method: 'POST',
          body: formData
        })
          .then(res => res.json())
          .then(data => {
            if (data.status) {
              // Recargar la página o actualizar solo el botón
              mostrarToast('success', data.message)
              setTimeout((e) => { location.reload(); }, 500)
            } else {
              mostrarToast('warning', 'Error: ' + (data.message || 'No se pudo cambiar el estado.'));
            }
          })
          .catch(error => {
            console.error('Error AJAX:', error);
            mostrarToast('danger', 'Ocurrió un error al cambiar el estado.');
          });
      })
  }

</script>





<?php include_once('../includes/footer.php'); ?>