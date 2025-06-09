<?php
include_once("../includes/header.php");
include_once("../includes/sidebar.php");

// Definir variables de paginación y límite por defecto para evitar errores
$limite = isset($_GET['limite']) && in_array((int) $_GET['limite'], [5, 10, 15, 20, 25]) ? (int) $_GET['limite'] : 10;
$pagina = isset($_GET['pagina']) && is_numeric($_GET['pagina']) && $_GET['pagina'] > 0 ? (int) $_GET['pagina'] : 1;

// Contar total de exámenes para paginación
$countSql = "SELECT COUNT(*) FROM examenes";
$countStmt = $pdo->prepare($countSql);
$countStmt->execute();
$total_examenes = $countStmt->fetchColumn();
$total_paginas = ceil($total_examenes / $limite);

// Calcular offset para la consulta con límite y paginación
$offset = ($pagina - 1) * $limite;

$sql = "SELECT 
            ex.id,  
            CONCAT(est.nombre, ' ', est.apellidos) AS estudiante, 
            cat.nombre AS categoria,
            us.nombre AS usuario, 
            ex.fecha_asignacion, 
            ex.total_preguntas,
            ex.estado, 
            ex.calificacion, 
            ex.codigo_acceso
        FROM examenes ex
        JOIN estudiantes est ON ex.estudiante_id = est.id
        JOIN categorias cat ON ex.categoria_id = cat.id
        LEFT JOIN usuarios us ON ex.asignado_por = us.id
        ORDER BY ex.fecha_asignacion DESC
        LIMIT :limite OFFSET :offset";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$examenes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="main-content" id="content">
  <div class="card shadow-sm mb-4">
    <div
      class="card-header bg-primary text-white d-flex flex-wrap align-items-center justify-content-between gap-3 p-3 rounded-top">
      <h5 class="mb-0 d-flex align-items-center">
        <i class="bi bi-file-earmark-text-fill me-2"></i>Gestión de Exámenes
      </h5>

      <div class="d-flex align-items-center gap-3 flex-grow-1 flex-wrap justify-content-end">
        <div class="position-relative">
          <input type="text" class="form-control ps-5 form-control-sm shadow-sm" id="customSearch"
            placeholder="Buscar examen...">
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

        <button class="btn btn-success btn-sm shadow-sm" onclick="abrirModalExamen()">
          <i class="bi bi-file-earmark-plus-fill me-2"></i>Nuevo Examen
        </button>
      </div>
    </div>

    <!-- TABLA -->
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle mb-0">
          <thead class="table-primary text-center">
            <tr>
              <th><i class="bi bi-hash me-1"></i> ID</th>
              <th><i class="bi bi-person-fill me-1"></i> Estudiante</th>
              <th><i class="bi bi-tags-fill me-1"></i> Categoría</th>
              <th><i class="bi bi-person-badge-fill me-1"></i> Asignado Por</th>
              <th><i class="bi bi-calendar-event-fill me-1"></i> Fecha</th>
              <th><i class="bi bi-list-ol me-1"></i> Preguntas</th>
              <th><i class="bi bi-toggle-on me-1"></i> Estado</th>
              <th><i class="bi bi-clipboard-check-fill me-1"></i> Calificación</th>
              <th><i class="bi bi-key-fill me-1"></i> Código</th>
              <th><i class="bi bi-gear-fill me-1"></i> Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($examenes)): ?>
              <?php foreach ($examenes as $examen): ?>
                <tr>
                  <td class="text-center"><?= htmlspecialchars($examen['id']) ?></td>
                  <td><?= htmlspecialchars($examen['estudiante']) ?></td>
                  <td><?= htmlspecialchars($examen['categoria']) ?></td>
                  <td><?= htmlspecialchars($examen['usuario'] ?? '—') ?></td>
                  <td><?= htmlspecialchars($examen['fecha_asignacion']) ?></td>
                  <td class="text-center"><?= htmlspecialchars($examen['total_preguntas']) ?></td>
                  <td class="text-center">
                    <span
                      class="badge bg-<?= $examen['estado'] === 'pendiente' ? 'warning' : ($examen['estado'] === 'en_progreso' ? 'primary' : 'success') ?>">
                      <?= strtoupper($examen['estado']) ?>
                    </span>
                  </td>
                  <td class="text-center">
                    <?= $examen['calificacion'] !== null ? htmlspecialchars($examen['calificacion']) : '—' ?>
                  </td>
                  <td class="text-center"><code><?= htmlspecialchars($examen['codigo_acceso']) ?></code></td>
                  <td class="text-center">
                    <div class="d-flex gap-2 justify-content-center flex-wrap">
                      <button class="btn btn-sm btn-outline-primary" onclick="verExamen(<?= $examen['id'] ?>)">
                        <i class="bi bi-eye-fill me-1"></i> Ver
                      </button>
                      <button class="btn btn-sm btn-outline-warning" onclick="editarExamen(<?= $examen['id'] ?>)">
                        <i class="bi bi-pencil-fill me-1"></i> Editar
                      </button>
                      <button class="btn btn-sm btn-outline-danger" onclick="eliminarExamen(<?= $examen['id'] ?>)">
                        <i class="bi bi-trash-fill me-1"></i> Eliminar
                      </button>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="10">
                  <div class="alert alert-warning text-center m-0 rounded-0">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>No hay exámenes registrados actualmente.
                  </div>
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <?php if ($total_paginas > 1): ?>
        <nav aria-label="Paginación de exámenes" class="my-3">
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

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script>
    $(document).ready(function () {
      function filterTable() {
        const search = $("#customSearch").val().toLowerCase();
        let count = 0;

        $("table tbody tr").each(function () {
          // Ignorar fila de "No resultados" para no contarla ni mostrarla
          if ($(this).attr('id') === 'no-results') return;

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
                                <td colspan="10">
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

      // Filtro en tiempo real
      $("#customSearch").on("input", filterTable);

      // Redirige al cambiar la cantidad
      $('#container-length').on('change', function () {
        const selectedLimit = $(this).val();
        // Cambia la URL para página 1 y límite seleccionado
        window.location.href = `?pagina=1&limite=${selectedLimit}`;
      });

      filterTable();
    });
  </script>
  </div>



  <!-- Modal -->
  <div class="modal fade" id="modalExamen" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content rounded-4 shadow">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="tituloModalExamen"><i class="bi bi-journal-plus me-2"></i>Nuevo Examen</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <!-- FORMULARIO COMPLETO -->
        <form id="formExamen">
          <div class="modal-body row g-3 px-4 py-3">
            <input type="hidden" name="examen_id" id="examen_id">
            <input type="hidden" name="usuario_id" id="usuario_id" value="<?= (int) $_SESSION['usuario']['id'] ?>">
            <input type="hidden" name="estudiante_id" id="estudiante_id" required>

            <div class="mb-2">
              <label for="buscador_estudiantes" class="form-label">Buscar Estudiante</label>
              <input type="text" class="form-control" id="buscador_estudiantes"
                placeholder="Escribe nombre o apellido...">
            </div>
            <div id="lista_estudiantes" class="border rounded p-2" style="max-height: 200px; overflow-y: auto;"></div>

            <div class="col-md-6">
              <label for="categoria_id" class="form-label">Categoría</label>
              <select class="form-select" id="categoria_id" name="categoria_id" required></select>
            </div>

            <div class="col-md-6">
              <label for="total_preguntas" class="form-label">Total de Preguntas</label>
              <input type="number" class="form-control" id="total_preguntas" value="5" name="total_preguntas" min="5"
                required>
              <span id="preguntas_disponibles" class="text-fs-2"></span>
            </div>

            <div class="col-md-6">
              <label for="fecha_examen" class="form-label"><i class="bi bi-calendar-event me-1"></i>Fecha a
                examinar</label>
              <input type="date" id="fecha_examen" name="fecha_examen" class="form-control" required>
            </div>

            <div class="mt-3">
              <h5 class="text-primary"><i class="bi bi-list-ul me-1"></i>Lista de Estudiantes Añadidos</h5>
              <ul class="list-group" id="lista_seleccionados" name="lista_seleccionados"></ul>
            </div>

            <div class="col-md-6">
              <button type="button" id="btn_anadir_estudiante" class="btn btn-success">
                <i class="bi bi-plus-circle me-1"></i>Añadir a la lista
              </button>
            </div>
          </div>

          <div class="modal-footer px-4 py-3">
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-save me-1"></i>Guardar Examen
            </button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Examen -->
<div class="modal fade" id="modalExamenVer" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalExamenTitulo">Ver Examen</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body" id="modalExamenContenido">
        <div class="text-center py-5">
          <div class="spinner-border text-primary" role="status"></div>
        </div>
      </div>
    </div>
  </div>
</div>



<script>
 
 
function editarExamen(id) {
  const modal = new bootstrap.Modal(document.getElementById('modalExamenVer'));
  const contenido = document.getElementById('modalExamenContenido');
  const titulo = document.getElementById('modalExamenTitulo');

  // Mostrar modal y loader
  titulo.textContent = 'Editar Examen';
  contenido.innerHTML = `
    <div class="text-center py-5">
      <div class="spinner-border text-primary" role="status"></div>
    </div>
  `;
  modal.show();

  fetch(`../api/obtener_examen.php?id=${id}`)
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        const examen = data.examen;

        // Aquí puedes renderizar el formulario editable dentro del modal
        contenido.innerHTML = `
          <form id="formEditarExamen">
            <input type="hidden" name="id" value="${examen.id}">
            
            <div class="mb-3">
              <label class="form-label">Estudiante:</label>
              <input type="text" class="form-control" value="${examen.estudiante}" disabled>
            </div>

            <div class="mb-3">
              <label class="form-label">Categoría:</label>
              <input type="text" class="form-control" value="${examen.categoria}" disabled>
            </div>

            <div class="mb-3">
              <label class="form-label">Fecha de Asignación:</label>
              <input type="date" name="fecha_asignacion" class="form-control" value="${examen.fecha_asignacion}">
            </div>

            <div class="mb-3">
              <label class="form-label">Estado:</label>
              <select name="estado" class="form-select">
                <option value="pendiente" ${examen.estado === 'pendiente' ? 'selected' : ''}>Pendiente</option>
                <option value="en_progreso" ${examen.estado === 'en_progreso' ? 'selected' : ''}>En Progreso</option>
                <option value="finalizado" ${examen.estado === 'finalizado' ? 'selected' : ''}>Finalizado</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Calificación:</label>
              <input type="number" step="0.01" name="calificacion" class="form-control" value="${examen.calificacion ?? ''}">
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-success">Guardar Cambios</button>
            </div>
          </form>
        `;
      } else {
        contenido.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
      }
    })
    .catch(err => {
      contenido.innerHTML = `<div class="alert alert-danger">Error al cargar examen</div>`;
      console.error(err);
    });
}

    
  function verExamen(id) {
    const formData = new FormData()
    formData.append('id', id)
    
    const modalExamen = new bootstrap.Modal(document.getElementById('modalExamenVer'))
    document.getElementById('modalExamenTitulo').innerText = 'Detalles del Examen'
    document.getElementById('modalExamenContenido').innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>'
    modalExamen.show()

    fetch('../api/ver_examen.php', {
      method: 'POST',
      body: formData
    })
    .then(res => res.text())
    .then(html => {
      document.getElementById('modalExamenContenido').innerHTML = html
    })
    .catch(() => {
      document.getElementById('modalExamenContenido').innerHTML = '<div class="alert alert-danger">Error al cargar los datos.</div>'
    })
  }
 


  function eliminarExamen(id) {
    if (!mostrarConfirmacionToast('¿Estás seguro de eliminar este examen? Esta acción no se puede deshacer.')) return

    const formData = new FormData()
    formData.append('id', id)

    fetch('../api/eliminar_examen.php', {
      method: 'POST',
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        mostrarToast('success', 'Examen eliminado correctamente')
        location.reload()
      } else {
        mostrarToast('info','Error: ' + data.message)
      }
    })
    .catch(() => {
      mostrarToast( 'danger','Error al eliminar examen')
    })
  }

</script>




  <script>
    let estudiantesData = []; // <- Aquí guardaremos los estudiantes
  </script>







  <script>
    function abrirModalExamen(examen = null) {
      const modal = new bootstrap.Modal(document.getElementById('modalExamen'));
      document.getElementById('formExamen').reset();
      document.getElementById('examen_id').value = '';
      document.getElementById('tituloModalExamen').textContent = examen ? 'Editar Examen' : 'Nuevo Examen';

      if (examen) {
        document.getElementById('examen_id').value = examen.id;
        document.getElementById('estudiante_id').value = examen.estudiante_id;
        document.getElementById('categoria_id').value = examen.categoria_id;
        document.getElementById('total_preguntas').value = examen.total_preguntas;
        document.getElementById('estado').value = examen.estado;
        document.getElementById('codigo_acceso').value = examen.codigo_acceso;
      }





      if (examen) {
        // Esperar a que se carguen los estudiantes antes de marcar el seleccionado
        setTimeout(() => {
          const radio = document.querySelector(`input[name="estudiante_radio"][value="${examen.estudiante_id}"]`);
          if (radio) {
            radio.checked = true;
            document.getElementById("estudiante_id").value = examen.estudiante_id;
          }
        }, 300); // Ajusta si tu AJAX es más lento
      }





      modal.show();
    }




    document.addEventListener("DOMContentLoaded", () => {



      const estudianteSelect = document.getElementById("estudiante_id");
      const categoriaSelect = document.getElementById("categoria_id");
      const totalPreguntasInput = document.getElementById("total_preguntas");
      const fechaExamen = document.getElementById('fecha_examen').value;



      configurarBuscadorEstudiantes();

      // Cargar estudiantes al cargar la página



      function renderEstudiantes(filtro = '') {
        const contenedor = document.getElementById("lista_estudiantes");
        contenedor.innerHTML = '';

        if (filtro.trim() === '') return;

        const filtroLower = filtro.toLowerCase();
        const coincidencias = estudiantesData.filter(est =>
          (`${est.nombre} ${est.apellidos}`).toLowerCase().includes(filtroLower)
        ).slice(0, 3); // Máximo 3

        if (coincidencias.length === 0) {
          contenedor.innerHTML = '<p class="text-muted">No se encontraron coincidencias.</p>';
          return;
        }

        coincidencias.forEach(est => {
          const item = `
      <div class="form-check">
        <input class="form-check-input" type="radio" name="estudiante_radio" value="${est.id}" id="est_${est.id}">
        <label class="form-check-label" for="est_${est.id}">
          ${est.nombre} ${est.apellidos}
        </label>
      </div>
    `;
          contenedor.innerHTML += item;
        });








        // Cuando se selecciona un estudiante
        contenedor.querySelectorAll('input[name="estudiante_radio"]').forEach(radio => {
          radio.addEventListener('change', function () {
            document.getElementById('estudiante_id').value = this.value;
            cargarCategorias(this.value); // Cargar categorías para el estudiante seleccionado
          });
        });
      }

      function configurarBuscadorEstudiantes() {
        fetch("../api/obtener_estudiantes.php")
          .then(res => res.json())
          .then(data => {
            estudiantesData = data.data;
          });

        document.getElementById("buscador_estudiantes").addEventListener("input", function () {
          renderEstudiantes(this.value);
        });
      }










      // Cargar categorías según el estudiante seleccionado
      estudianteSelect.addEventListener("change", () => {
        const estudianteId = estudianteSelect.value;
        categoriaSelect.innerHTML = `<option value="">Seleccione</option>`;

        if (!estudianteId) return;

        fetch(`../api/obtener_categorias_estudiante.php?estudiante_id=${estudianteId}`)
          .then(res => res.json())
          .then(data => {
            if (data.status) {
              mostrarToast('success', data.message);
              data.data.forEach(c => {
                categoriaSelect.innerHTML += `<option value="${c.categoria_id}">${c.categoria}</option>`;
              });
            } else {
              mostrarToast('warning', data.message);

            }
          });
      });

      // Validar total de preguntas según la categoría seleccionada
      totalPreguntasInput.addEventListener("input", async () => {
        const categoriaId = categoriaSelect.value;
        const valor = parseInt(totalPreguntasInput.value, 10);

        if (!categoriaId || isNaN(valor)) return;

        const res = await fetch(`../api/obtener_total_categorias_estudiante.php?categoria_id=${categoriaId}`);
        const data = await res.json();
        let preguntasDisponible = document.getElementById('preguntas_disponibles');
        preguntasDisponible.textContent = `Preguntas disponibles: ${data.data}`;
        if (valor > data.data) {
          mostrarToast('warning', `No puedes ingresar más de ${data.data} preguntas para esta categoría`);
          totalPreguntasInput.value = data.data.total;
        }
      });

      // Generar código de acceso automáticamente y eliminar campo manual


      const form = document.getElementById('formExamen'); // Cambia esto al id real

      if (!form) {
        console.error('No se encontró el formulario');
        return;
      }

      form.addEventListener('submit', async (e) => {
        e.preventDefault();
        console.log(JSON.stringify(listaTemporal));
        // Suponiendo que listaTemporal está declarada y llena
        const formData = new FormData(form);
        formData.append('codigo_acceso', generarCodigo());
     formData.append('lista_estudiantes', JSON.stringify(listaTemporal));


        try {
          const res = await fetch('../api/guardar_examen.php', {
            method: 'POST',
            body: formData
          });
          const data = await res.json();
          console.log(data);
          if (data.status) {
            mostrarToast('success', data.message || 'Examen guardado correctamente');
            setTimeout(() => location.reload(), 1200);
          } else {
            mostrarToast('warning', 'Error: ' + (data.message || 'No se pudo guardar el examen.'));
          }
        } catch (error) {
          console.error('Error en la solicitud:', error);
          mostrarToast('error', 'Error en la conexión con el servidor.');
        }
      });

      function generarCodigo() {
        return 'EXAM' + Date.now().toString().slice(-6);
      }





    });







    /* -------------- */












    function cargarCategorias(estudianteId) {
      const categoriaSelect = document.getElementById("categoria_id");
      categoriaSelect.innerHTML = `<option value="">Seleccione</option>`;

      if (!estudianteId) return;

      fetch(`../api/obtener_categorias_estudiante.php?estudiante_id=${estudianteId}`)
        .then(res => res.json())
        .then(data => {
          if (data.status) {
            mostrarToast('success', data.message);
            data.data.forEach(c => {
              categoriaSelect.innerHTML += `<option value="${c.categoria_id}">${c.categoria}</option>`;
            });
          } else {
            mostrarToast('warning', data.message);
          }
        });
    }
  </script>






  <script>
    const btnAnadir = document.getElementById('btn_anadir_estudiante');
    const listaSeleccionados = document.getElementById('lista_seleccionados');

    const listaTemporal = []; // Lista que simula los datos a guardar

    btnAnadir.addEventListener('click', () => {
      const estudianteId = document.querySelector('input[name="estudiante_radio"]:checked')?.value;



      // Buscar el estudiante
      const estudiante = estudiantesData.find(e => e.id == estudianteId);



      // Validar si existe el estudiante
      if (!estudiante) {
        mostrarToast('warning', 'Debes buscar y seleccionar un estudiante válido.');

        return;
      }

      const categoriaId = document.getElementById('categoria_id').value;
      const categoriaNombre = document.getElementById('categoria_id').options[document.getElementById('categoria_id').selectedIndex]?.text;
      const totalPreguntas = document.getElementById('total_preguntas').value;
      const fechaExamen = document.getElementById('fecha_examen').value;




      if (!categoriaId || !totalPreguntas) {
        mostrarToast('warning', 'Completa todos los campos antes de añadir.');
        return;
      }

      // Evitar duplicados
      const yaExiste = listaTemporal.some(e => e.estudiante_id == estudianteId && e.categoria_id == categoriaId);
      if (yaExiste) {
        mostrarToast('info', 'Este estudiante ya fue añadido con esta categoría.');
        return;
      }

      // Crear objeto con los datos
      const datos = {
        estudiante_id: estudianteId,
        nombre: estudiante.nombre + ' ' + estudiante.apellidos,
        categoria_id: categoriaId,
        categoria: categoriaNombre,
        total_preguntas: totalPreguntas,
        fecha_examen: fechaExamen
      };

      listaTemporal.push(datos);
      actualizarListaVisual();
    });


    function actualizarListaVisual() {
      listaSeleccionados.innerHTML = '';
      listaTemporal.forEach((item, index) => {
        const li = document.createElement('li');
        li.className = 'list-group-item d-flex justify-content-between align-items-center';
        li.innerHTML = `
      <div>
    <strong>${item.nombre}</strong> - ${item.categoria} (${item.total_preguntas} preguntas)<br>
    <small class="text-muted">Fecha: ${item.fecha_examen}</small>
  </div>
  <button type="button" class="btn btn-sm btn-danger" onclick="eliminarSeleccionado(${index})">
    <i class="bi bi-trash"></i>
  </button>
    `;





        listaSeleccionados.appendChild(li);
      });
    }

    window.eliminarSeleccionado = function (index) {
      listaTemporal.splice(index, 1);
      actualizarListaVisual();
    };
  </script>




  <?php include_once('../includes/footer.php'); ?>