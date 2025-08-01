<?php
include_once("../includes/header.php");
include_once("../includes/sidebar_examinador.php");

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
        WHERE ex.fecha_asignacion >= NOW()
        ORDER BY ex.fecha_asignacion DESC
        LIMIT :limite OFFSET :offset";


$stmt = $pdo->prepare($sql);
$stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$examenes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

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
            <th><i class="bi bi-toggle-on me-1"></i> Estado</th>
            <th><i class="bi bi-tags-fill me-1"></i> Categoría</th>
            <th><i class="bi bi-calendar-event-fill me-1"></i> Fecha</th>
            <th><i class="bi bi-list-ol me-1"></i> Preguntas</th>
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
                <td class="text-center">
                  <?php if ($examen['estado'] === 'INICIO'): ?>
                    <button
                      class="btn btn-outline-danger btn-sm d-flex align-items-center gap-2 px-3 py-1 rounded-pill shadow-sm"
                      title="Haz clic para activar"
                      onclick="cambiarEstadoEstudiante(<?= (int) $examen['id'] ?>, '<?= htmlspecialchars($examen['estudiante']) ?>', 'pendiente')">
                      <i class="bi bi-toggle-off fs-5"></i>
                      Inactivo
                    </button>
                  <?php else: ?>
                    <button
                      class="btn btn-outline-success btn-sm d-flex align-items-center gap-2 px-3 py-1 rounded-pill shadow-sm"
                      title="Haz clic para activar"
                      onclick="cambiarEstadoEstudiante(<?= (int) $examen['id'] ?>, '<?= htmlspecialchars($examen['estudiante']) ?>', 'INICIO')">
                      <i class="bi bi-toggle-on fs-5"></i>
                      Activo
                    </button>
                  <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($examen['categoria']) ?></td>
                <td><?= htmlspecialchars($examen['fecha_asignacion']) ?></td>
                <td class="text-center"><?= htmlspecialchars($examen['total_preguntas']) ?></td>
                <td class="text-center"><code><?= htmlspecialchars($examen['codigo_acceso']) ?></code></td>




                <td class="text-center">
                  <a href="../libreria/imprimir_codigo.php?id=<?= $examen['id'] ?>"
                    class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-printer-fill me-1"></i> Imprimir
                  </a>


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



      <div class="modal-body row g-3 px-4 py-3">
        <input type="hidden" name="examen_id" id="examen_id">
        <input type="hidden" name="usuario_id" id="usuario_id" value="<?= (int) $_SESSION['usuario']['id'] ?>">


        <div class="mb-2">
          <label for="buscador_estudiantes" class="form-label">Buscar Estudiante</label>
          <input type="text" class="form-control" id="buscador_estudiantes" placeholder="Escribe nombre o apellido...">
        </div>
        <div id="lista_estudiantes" class="border rounded p-2" style="max-height: 200px; overflow-y: auto;"></div>
        <input type="hidden" name="estudiante_id" id="estudiante_id" required>


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
          <label for="fecha_examen" class="form-label">
            <i class="bi bi-calendar-event me-1"></i>Fecha a examinar
          </label>
          <input type="date" id="fecha_examen" name="fecha_examen" class="form-control" required>
        </div>

      </div>


      <form id="formExamen">

        <div class="mt-3">
          <h5 class="text-primary"><i class="bi bi-list-ul me-1"></i>Lista de Estudiantes Añadidos</h5>
          <ul class="list-group" id="lista_seleccionados" name="lista_seleccionados"></ul>
        </div>


        <div class="col-md-6">
          <button type="button" id="btn_anadir_estudiante" class="btn btn-success">
            <i class="bi bi-plus-circle me-1"></i>Añadir a la lista
          </button>
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
 
<script>
  let estudiantesData = []; // <- Aquí guardaremos los estudiantes

  function cambiarEstadoEstudiante(id, nombre, nuevoEstado) {
    console.log(id)
    console.log(nuevoEstado)
    mostrarConfirmacionToast(
      `¿Estás seguro de que deseas ${nuevoEstado == "INICIO" ? "activar" : "desactivar"} el examen del estudiante: ${nombre} ?`,
      () => {

        const formData = new FormData();
        formData.append('id', id);
        formData.append('estado', nuevoEstado);

        fetch('../api/activar_examen.php', {
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
    const form = document.getElementById("formExamen");

    form.addEventListener("submit", async (e) => {
      e.preventDefault();

      const formData = new FormData(form);














      // Generar código único
      formData.append("codigo_acceso", generarCodigo());
      formData.append("lista_seleccionados",)

      try {
        const res = await fetch("../api/guardar_examen.php", {
          method: "POST",
          body: formData
        });

        const data = await res.json(); // Asegúrate de usar "data", no "result"

        if (data.status) {
          mostrarToast('success', data.message || "Examen guardado correctamente");
          setTimeout(() => location.reload(), 1200);
        } else {
          mostrarToast('warning', "Error: " + (data.message || "No se pudo guardar el examen."));
        }

      } catch (error) {
        console.error("Error en la solicitud:", error);
        mostrarToast('error', "Error en la conexión con el servidor.");
      }
    });


    function generarCodigo() {
      return "EXAM" + Date.now().toString().slice(-6);
    }
  });










  document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('form_examen'); // Cambia esto al id real

    if (!form) {
      console.error('No se encontró el formulario');
      return;
    }

    form.addEventListener('submit', async (e) => {
      e.preventDefault();

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
      mostrarToast('error', 'Debes buscar y seleccionar un estudiante válido.');

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