<?php
include_once("../includes/header.php");
include_once("../includes/sidebar.php");

// Consulta para obtener las preguntas
$sql = "SELECT * FROM preguntas";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$preguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Main Content -->
<div class="main-content">
  <div class="card shadow border-0 rounded-4">
    <!-- Header -->
    <div class="card-header bg-primary text-white d-flex flex-wrap justify-content-between align-items-center rounded-top-4 px-4 py-3">
      <h5 class="mb-0"><i class="bi bi-question-circle-fill me-2"></i>Gestión de Preguntas</h5>
      
      <!-- Buscador -->
      <div class="search-box position-relative">
        <input type="text" class="form-control ps-5" id="buscarPregunta" placeholder="Buscar pregunta...">
        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
      </div>

      <!-- Filtros y botón -->
      <div class="d-flex flex-wrap gap-5 align-items-center">
        <div class="d-flex align-items-center">
          <label for="preguntas-length" class="me-2 text-white fw-medium mb-0">Mostrar:</label>
          <select id="preguntas-length" class="form-select w-auto shadow-sm">
            <option value="5">5 registros</option>
            <option value="10" selected>10 registros</option>
            <option value="15">15 registros</option>
            <option value="20">20 registros</option>
            <option value="25">25 registros</option>
          </select>
        </div>
        <button class="btn btn-light text-primary fw-semibold shadow-sm" onclick="abrirModalRegistro()">
          <i class="bi bi-plus-circle-fill me-2"></i>Nueva Pregunta
        </button>
      </div>
    </div>

    <!-- Tabla -->
    <div class="table-responsive">
      <?php if (!empty($preguntas)): ?>
        <table id="preguntas-table" class="table table-hover align-middle mb-0 text-center">
          <thead class="table-light">
            <tr>
              <th><i class="bi bi-hash me-1"></i>ID</th>
              <th><i class="bi bi-chat-left-dots-fill me-1"></i>Texto</th>
              <th><i class="bi bi-ui-checks-grid me-1"></i>Tipo</th>
              <th><i class="bi bi-image-fill me-1"></i>Contenido</th>
              <th><i class="bi bi-toggle-on me-1"></i>Estado</th>
              <th><i class="bi bi-calendar-event-fill me-1"></i>Creada</th>
              <th><i class="bi bi-gear-fill me-1"></i>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($preguntas as $pregunta): ?>
              <tr>
                <td><?= htmlspecialchars($pregunta['id']); ?></td>
                <td class="text-start"><?= htmlspecialchars($pregunta['texto']); ?></td>
                <td>
                  <span class="badge bg-info text-uppercase"><?= strtoupper($pregunta['tipo']); ?></span>
                </td>
                <td>
                  <span class="badge bg-secondary"><?= htmlspecialchars($pregunta['tipo_contenido']); ?></span>
                </td>
                <td>
                  <?php if ($pregunta['activa']): ?>
                    <button class="btn btn-outline-success btn-sm rounded-pill shadow-sm px-3 py-1"
                      onclick="cambiarEstadoPregunta(<?= $pregunta['id'] ?>, false)" title="Desactivar">
                      <i class="bi bi-toggle-on fs-5"></i> Activa
                    </button>
                  <?php else: ?>
                    <button class="btn btn-outline-danger btn-sm rounded-pill shadow-sm px-3 py-1"
                      onclick="cambiarEstadoPregunta(<?= $pregunta['id'] ?>, true)" title="Activar">
                      <i class="bi bi-toggle-off fs-5"></i> Inactiva
                    </button>
                  <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($pregunta['creado_en']); ?></td>
                <td>
                  <div class="d-flex justify-content-center gap-2 flex-wrap">
                    <button class="btn btn-sm btn-outline-primary" onclick="abrirModalCategorias(<?= (int) $pregunta['id'] ?>)" title="Ver categorías">
                      <i class="bi bi-eye"></i> Categorías
                    </button>
                    <?php if ($rol === 'admin'): ?>
                      <button class="btn btn-sm btn-outline-danger" onclick="eliminarPregunta(<?= $pregunta['id'] ?>)" title="Eliminar pregunta">
                        <i class="bi bi-trash"></i>
                      </button>
                    <?php endif; ?>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?>
        <div class="alert alert-warning text-center m-3">
          <i class="bi bi-exclamation-circle-fill me-2"></i>No hay preguntas registradas actualmente.
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>




<!-- Modal Pregunta -->
<div class="modal fade" id="modalPregunta" tabindex="-1" aria-labelledby="modalPreguntaLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" style="margin-top: 4rem; max-width: 50vw;">
    <form id="formPregunta" enctype="multipart/form-data" class="needs-validation w-100" novalidate>
      <div class="modal-content shadow-lg rounded-4 border-0">
        <div class="modal-header bg-gradient bg-primary text-white rounded-top-4 px-4 py-3">
          <h5 class="modal-title d-flex align-items-center gap-2" id="modalPreguntaLabel">
            <i class="bi bi-patch-question-fill fs-4"></i> Nueva Pregunta
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body px-4 py-3 bg-light">
          <input type="hidden" name="pregunta_id" id="pregunta_id">

          <!-- Tipo contenido -->
          <div class="mb-3">
            <label class="form-label fw-semibold"><i class="bi bi-file-earmark-text me-1"></i> Tipo de contenido</label>
            <select name="tipo_contenido" id="tipo_contenido" class="form-select rounded-pill shadow-sm" required>
              <option value="texto">Texto</option>
              <option value="ilustracion">Ilustración</option>
            </select>
          </div>

          <!-- Texto obligatorio -->
          <div class="mb-3" id="textoPreguntaContainer">
            <label for="texto" class="form-label fw-semibold"><i class="bi bi-card-text me-1"></i> Texto de la pregunta</label>
            <textarea name="texto" id="texto" class="form-control shadow-sm rounded-3" rows="2" required></textarea>
          </div>

          <!-- Galería de imágenes -->
          <div class="mb-3 d-none" id="imagenesPreguntaContainer">
            <label class="form-label fw-semibold"><i class="bi bi-images me-1"></i> Imágenes</label>
            <div id="contenedorImagenes" class="border rounded-3 p-2 bg-white shadow-sm"></div>
            <button type="button" class="btn btn-outline-primary btn-sm mt-2 rounded-pill" id="agregarImagen">
              <i class="bi bi-plus-circle me-1"></i> Añadir imagen
            </button>
          </div>

          <!-- Tipo de pregunta -->
          <div class="mb-3">
            <label class="form-label fw-semibold"><i class="bi bi-ui-checks me-1"></i> Tipo de pregunta</label>
            <select name="tipo" id="tipo" class="form-select rounded-pill shadow-sm" required>
              <option value="unica">Opción única</option>
              <option value="multiple">Opción múltiple</option>
              <option value="vf">Verdadero / Falso</option>
            </select>
          </div>

          <!-- Opciones -->
          <div class="mb-3">
            <label class="form-label fw-semibold"><i class="bi bi-list-check me-1"></i> Opciones</label>
            <div id="contenedorOpciones" class="border rounded-3 p-2 bg-white shadow-sm"></div>
            <button type="button" class="btn btn-outline-primary btn-sm mt-2 rounded-pill" id="agregarOpcion">
              <i class="bi bi-plus-circle me-1"></i> Añadir opción
            </button>
          </div>

          <!-- Asignar categoría -->
          <div class="mb-3" id="divCategoria">
            <div class="mb-3">
              <label class="form-label fw-semibold"><i class="bi bi-tags me-1"></i> ¿Asignar categoría?</label><br>
              <button type="button" id="toggleCategoria"
                class="btn btn-outline-dark d-flex align-items-center gap-2 px-3 py-1 rounded-pill shadow-sm"
                onclick="toggleAsignarCategoria()">
                <i id="iconCategoria" class="bi bi-toggle-off fs-5"></i>
                <span id="textoCategoria">No</span>
              </button>
              <input type="hidden" name="asignar_categoria" id="asignar_categoria" value="no">
            </div>

            <div id="contenedorCategorias" class="mt-3 d-none">
              <label for="categoria_id" class="form-label fw-semibold"><i class="bi bi-folder-check me-1"></i> Categorías</label>
              <div id="listaCategorias" class="d-flex flex-wrap gap-2"></div>
            </div>
          </div>

        </div>
        <div class="modal-footer bg-white border-top-0 px-4 py-3">
          <button type="submit" class="btn btn-primary rounded-pill shadow-sm" id="modalBotonTexto">
            <i class="bi bi-save2 me-1"></i> Guardar Pregunta
          </button>
          <button type="button" class="btn btn-outline-secondary rounded-pill shadow-sm" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-1"></i> Cancelar
          </button>
        </div>
      </div>
    </form>
  </div>
</div>


<!-- MODAL listado de categorías asignadas al estudiante basado en su ID -->
<div class="modal fade" id="modalCategoriasPregunta" tabindex="-1" aria-labelledby="tituloCategorias"
  aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable" style="margin-top: 3vh;">
    <div class="modal-content shadow-lg rounded-4 border-0">
      <div class="modal-header bg-primary text-white rounded-top-4">
        <h5 class="modal-title" id="tituloCategorias">
          <i class="bi bi-tags-fill me-2"></i> Categorías asignadas
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <!-- Tabla -->
        <div id="tablaCategoriasPregunta" class="table-responsive mb-4"></div>

        <!-- Botón + Nueva categoría -->
        <div class="text-end mb-3">
          <button class="btn btn-success w-100 w-md-auto" onclick="mostrarSelectorNuevaCategoria()">
            <i class="bi bi-plus-circle-fill me-1"></i> Nueva categoría
          </button>
        </div>

        <!-- Contenedor selector -->
        <div id="contenedorNuevaCategoria" class="d-none">
          <div class="d-flex flex-column flex-md-row justify-content-end align-items-stretch gap-3">
            <select id="selectNuevaCategoria" class="form-select w-100 w-md-auto"></select>
            <button class="btn btn-primary" onclick="asignarNuevaCategoria()">
              <i class="bi bi-check2-circle me-1"></i> Asignar
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  const contenedorCategorias = document.getElementById('contenedorCategorias');
  const listaCategorias = document.getElementById('listaCategorias');

  /* funcion para abrir modal modo registro pregunta*/
  function abrirModalRegistro() {
    document.getElementById('modalPreguntaLabel').textContent = 'Registrar Pregunta';
    document.getElementById('modalBotonTexto').textContent = 'Registrar';
    const modal = new bootstrap.Modal(document.getElementById('modalPregunta'));
    modal.show();
    document.getElementById('formPregunta').addEventListener('submit', function(e) {
      e.preventDefault(); // prevenir envío tradicional

      const form = e.target;
      const formData = new FormData(form);

      fetch('../api/guardar_actualizar_preguntas.php', {
          method: 'POST',
          body: formData
        })
        .then(res => res.json())
        .then(data => {
          if (data.status) {
            // ✅ Éxito
            mostrarToast(
              'success', 'Pregunta guardada correctamente');
            form.reset(); // opcional
            // Aquí podrías cerrar el modal si deseas
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalPregunta'));
            if (modal) modal.hide();
            // Recargar tabla o lista de usuarios si corresponde
            setTimeout(() => location.reload(), 1200);
          } else {
            // ⚠️ Error con mensaje del backend
            mostrarToast('warning', data.message || 'Ocurrió un error al guardar la pregunta');
          }
        })
        .catch(err => {
          console.error('Error en fetch:', err);
          mostrarToast(
            'danger',
            'Error de red. No se pudo conectar con el servidor'
          );
        });
    });


  }



  // Función para mostrar u ocultar el selector de categorías
  function toggleAsignarCategoria() {
    const input = document.getElementById('asignar_categoria');
    const btn = document.getElementById('toggleCategoria');
    const icon = document.getElementById('iconCategoria');
    const texto = document.getElementById('textoCategoria');
    const asignar = input.value === 'no'; // vamos a activar

    input.value = asignar ? 'si' : 'no';
    icon.className = asignar ? 'bi bi-toggle-on fs-5' : 'bi bi-toggle-off fs-5';
    texto.textContent = asignar ? 'Sí' : 'No';

    btn.classList.toggle('btn-outline-success', asignar);
    btn.classList.toggle('btn-outline-danger', !asignar);

    contenedorCategorias.classList.toggle('d-none', !asignar);

    if (asignar) {
      cargarCategorias();
    }
  }







  // Función para cargar categorías desde backend
  function cargarCategorias(categoriasSeleccionadas = []) {
    fetch('../api/obtener_categorias.php')
      .then(res => res.json())
      .then(categorias => {
        listaCategorias.innerHTML = '';
        categorias.data.forEach(cat => {
          const div = document.createElement('div');
          div.className = 'form-check form-check-inline';
          div.innerHTML = `
          <input class="form-check-input" type="checkbox" name="categorias[]" id="cat_${cat.id}" value="${cat.id}" ${categoriasSeleccionadas.includes(cat.id) ? 'checked' : ''}>
          <label class="form-check-label" for="cat_${cat.id}">${cat.nombre}</label>
        `;
          listaCategorias.appendChild(div);
        });
      })
      .catch(err => {
        console.error('Error al obtener categorías:', err);
        listaCategorias.innerHTML = '<div class="text-danger">No se pudieron cargar las categorías.</div>';
      });
  }









  document.addEventListener('DOMContentLoaded', () => {
    const tipoContenido = document.getElementById('tipo_contenido');
    const textoPreguntaContainer = document.getElementById('textoPreguntaContainer');
    const imagenesPreguntaContainer = document.getElementById('imagenesPreguntaContainer');
    const contenedorImagenes = document.getElementById('contenedorImagenes');
    const agregarImagenBtn = document.getElementById('agregarImagen');
    const tipoPregunta = document.getElementById('tipo');
    const contenedorOpciones = document.getElementById('contenedorOpciones');
    const agregarOpcionBtn = document.getElementById('agregarOpcion');
    const selectCategoria = document.getElementById('categoria_id');
    const catSi = document.getElementById('cat_si');
    const catNo = document.getElementById('cat_no');

    let contadorOpciones = 0;

    // Mostrar u ocultar input imágenes
    tipoContenido.addEventListener('change', () => {
      const isIlustracion = tipoContenido.value === 'ilustracion';
      imagenesPreguntaContainer.classList.toggle('d-none', !isIlustracion);
      textoPreguntaContainer.classList.remove('d-none'); // El texto siempre visible
    });


    // Crear inputs de imágenes
    agregarImagenBtn.addEventListener('click', () => {
      const div = document.createElement('div');
      div.className = 'input-group mb-2';
      div.innerHTML = `
      <input type="file" name="imagenes[]" class="form-control" required>
      <button type="button" class="btn btn-outline-danger btnEliminarImagen"><i class="bi bi-x-lg"></i></button>
    `;
      contenedorImagenes.appendChild(div);
    });

    contenedorImagenes.addEventListener('click', e => {
      if (e.target.closest('.btnEliminarImagen')) {
        e.target.closest('.input-group').remove();
      }
    });



    // Crear opciones
    const crearOpcionHTML = (texto = '', checked = false) => {
      const index = contadorOpciones++;

      const div = document.createElement('div');
      div.className = 'input-group mb-2';
      div.innerHTML = `
    <div class="input-group-text">
      <input type="checkbox" name="opciones[${index}][es_correcta]" class="form-check-input mt-0" ${checked ? 'checked' : ''}>
    </div>
    <input type="text" name="opciones[${index}][texto]" class="form-control" placeholder="Texto de la opción" value="${texto}" required>
    <button type="button" class="btn btn-outline-danger btnEliminarOpcion"><i class="bi bi-x-lg"></i></button>
  `;
      contenedorOpciones.appendChild(div);
    };




    const cargarVF = () => {
      contenedorOpciones.innerHTML = '';
      contenedorOpciones.innerHTML = `
      <div class="form-check">
        <input class="form-check-input" type="radio" name="es_correcta_vf" id="vf_verdadero" value="verdadero">
        <label class="form-check-label" for="vf_verdadero">Verdadero</label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="radio" name="es_correcta_vf" id="vf_falso" value="falso">
        <label class="form-check-label" for="vf_falso">Falso</label>
      </div>
    `;
      agregarOpcionBtn.classList.add('d-none');
    };

    tipoPregunta.addEventListener('change', () => {
      contenedorOpciones.innerHTML = '';
      agregarOpcionBtn.classList.remove('d-none');
      contadorOpciones = 0;
      if (tipoPregunta.value === 'vf') {
        cargarVF();
      } else {
        for (let i = 0; i < 2; i++) crearOpcionHTML();
      }
    });

    agregarOpcionBtn.addEventListener('click', () => crearOpcionHTML());

    contenedorOpciones.addEventListener('click', e => {
      if (e.target.closest('.btnEliminarOpcion')) {
        e.target.closest('.input-group').remove();
      }
    });

    // Reiniciar formulario al abrir
    const modal = document.getElementById('modalPregunta');
    modal.addEventListener('show.bs.modal', () => {
      document.getElementById('formPregunta').reset();
      contenedorImagenes.innerHTML = '';
      contenedorOpciones.innerHTML = '';
      selectCategoria.classList.add('d-none');
      catNo.checked = true;
      contadorOpciones = 0;
      tipoContenido.dispatchEvent(new Event('change'));
      tipoPregunta.dispatchEvent(new Event('change'));
    });



  });

  function eliminarPregunta(id) {
    mostrarConfirmacionToast('¿Estás seguro de que deseas eliminar esta pregunta?',
      () => {

        const formData = new FormData();
        formData.append('id', id);

        fetch('../api/eliminar_pregunta.php', {
            method: 'POST',
            body: formData
          })
          .then(res => res.json())
          .then(data => {
            if (data.status) {
              mostrarToast('success', data.message);
              // Aquí puedes actualizar tu tabla o lista de preguntas
              setTimeout(() => location.reload(), 1200);
            } else {
              mostrarToast('warning', data.message);
            }
          })
          .catch(err => {
            console.error('Error en la solicitud:', err);
            mostrarToast('danger', 'Error al eliminar la pregunta');
          });
      })


  }

  function cambiarEstadoPregunta(idPregunta, nuevoEstado) {
    mostrarConfirmacionToast(
      `¿Estás seguro de que deseas ${nuevoEstado ? 'activar' : 'desactivar'} esta pregunta?`,
      () => {
        const formData = new FormData();
        formData.append('id', idPregunta);
        formData.append('estado', nuevoEstado ? 1 : 0);

        fetch('../api/cambiar_estado_pregunta.php', {
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
            console.error('Error:', error);
            mostrarToast('danger', 'Ocurrió un error al cambiar el estado.');
          });
      }
    );
  }



  let idPreguntaActual = 0;

  function abrirModalCategorias(preguntaId) {
    idPreguntaActual = preguntaId;
    cargarCategoriasPregunta(preguntaId);
    const modal = new bootstrap.Modal(document.getElementById('modalCategoriasPregunta'));
    modal.show();
  }

  function cargarCategoriasPregunta(preguntaId) {
    fetch(`../api/categorias_pregunta.php?id=${preguntaId}`)
      .then(res => res.json())
      .then(data => {
        const contenedor = document.getElementById('tablaCategoriasPregunta');
        if (!data.status) {
          contenedor.innerHTML = `<div class="alert alert-danger d-flex align-items-center" role="alert">
          <i class="bi bi-exclamation-triangle-fill me-2"></i>
          ${data.message}
        </div>`;
          return;
        }

        if (data.data.length === 0) {
          contenedor.innerHTML = `<div class="alert alert-secondary d-flex align-items-center" role="alert">
          <i class="bi bi-info-circle-fill me-2"></i>
          Esta pregunta no tiene categorías asignadas.
        </div>`;
          return;
        }

        let html = `
      <table class="table table-striped table-hover align-middle">
        <thead class="table-primary">
          <tr>
            <th scope="col"><i class="bi bi-hash"></i> ID</th>
            <th scope="col"><i class="bi bi-tag-fill"></i> Nombre</th>
            <th scope="col" class="text-end"><i class="bi bi-gear-fill"></i> Acciones</th>
          </tr>
        </thead>
        <tbody>`;

        data.data.forEach(cat => {
          html += `
          <tr>
            <td>${cat.id}</td>
            <td>${cat.nombre}</td>
            <td class="text-end">
              <button class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1"
                      onclick="eliminarCategoriaPregunta(${cat.rel_id})">
                <i class="bi bi-trash-fill"></i> Eliminar
              </button>
            </td>
          </tr>`;
        });

        html += `</tbody></table>`;
        contenedor.innerHTML = html;
      });
  }

  function mostrarSelectorNuevaCategoria() {
    const contenedor = document.getElementById('contenedorNuevaCategoria');
    contenedor.classList.remove('d-none');

    fetch('../api/obtener_categorias.php')
      .then(res => res.json())
      .then(data => {
        const select = document.getElementById('selectNuevaCategoria');
        select.innerHTML = '<option value="">Seleccione una categoría</option>';
        data.data.forEach(cat => {
          select.innerHTML += `<option value="${cat.id}">${cat.nombre}</option>`;
        });
      });
  }

  function asignarNuevaCategoria() {
    const categoriaId = document.getElementById('selectNuevaCategoria').value;
    if (!categoriaId) return alert('Selecciona una categoría válida');

    const formData = new FormData();
    formData.append('accion', 'asignar');
    formData.append('pregunta_id', idPreguntaActual);
    formData.append('categoria_id', categoriaId);

    fetch('../api/categorias_pregunta.php', {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.status) {
          mostrarToast('success', data.message);
          cargarCategoriasPregunta(idPreguntaActual);
          document.getElementById('contenedorNuevaCategoria').classList.add('d-none');
        } else {
          mostrarToast('warning', data.message || 'Error al asignar categoría');
        }
      });
  }


  function eliminarCategoriaPregunta(rel_id) {
    mostrarConfirmacionToast('¿Eliminar esta categoría de la pregunta?', () => {
      const formData = new FormData();
      formData.append('accion', 'eliminar');
      formData.append('rel_id', rel_id);

      fetch('../api/categorias_pregunta.php', {
          method: 'POST',
          body: formData
        })
        .then(res => res.json())
        .then(data => {
          if (data.status) {
            mostrarToast('success', data.message);
            cargarCategoriasPregunta(idPreguntaActual);
          } else {
            mostrarToast('warning', data.message || 'Error al eliminar');
          }
        });
    });
  }
</script>


<?php include_once('../includes/footer.php'); ?>