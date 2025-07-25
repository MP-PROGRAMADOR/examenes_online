<?php
require_once 'header.php'; // Asegúrate de que esta ruta sea correcta
?>

<span class="mt-5"></span>
<span class="mt-5"></span>
<main class="main-content" id="content">
  <div class="card shadow-sm mb-4">
    <div
      class="card-header bg-gradient-primary text-white d-flex flex-wrap align-items-center justify-content-between gap-3 p-3 rounded-top">
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
            <option value="5">5</option>
            <option value="10" selected>10</option>
            <option value="15">15</option>
            <option value="20">20</option>
            <option value="25">25</option>
          </select>
        </div>

        <button class="btn btn-light fw-semibold shadow-sm" onclick="abrirModalRegistroEstudiante()">
          <i class="bi bi-person-plus-fill me-1"></i>Nuevo
        </button>
      </div>
    </div>

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
             
              <th>Documento</th>
              <th>Estado</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody id="listarEstudiante">

          </tbody>
        </table>
      </div>

      <nav aria-label="Paginación de estudiantes" class="my-3">
        <ul class="pagination justify-content-center">
        </ul>
      </nav>
    </div>
  </div>

  <div class="modal fade" id="modalEstudiante" tabindex="-1" aria-labelledby="modalEstudianteLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content border-0 shadow rounded-4">
        <div class="modal-header bg-primary text-white rounded-top">
          <h5 class="modal-title" id="modalEstudianteLabel">
            <i class="bi bi-person-plus-fill me-2"></i><span id="modalEstudianteTitulo">Registrar
              Estudiante</span>
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <form id="formularioEstudiante" method="POST" class="needs-validation" novalidate>
          <div class="row modal-body p-4 ">
            <input type="hidden" name="estudiante_id" id="estudiante_id">

            <div class="mb-3 col-12 col-md-6">
              <label for="dni_estudiante" class="form-label fw-semibold">
                <i class="bi bi-card-text me-2 text-primary"></i>DNI <span class="text-danger">*</span>
              </label>
              <input type="text" class="form-control shadow-sm" id="dni_estudiante" name="dni" required>
              <div class="invalid-feedback">Por favor ingresa el DNI del estudiante.</div>
            </div>

            <div class="mb-3 col-12 col-md-6">
              <label for="nombre_estudiante" class="form-label fw-semibold">
                <i class="bi bi-person-fill me-2 text-primary"></i>Nombres <span class="text-danger">*</span>
              </label>
              <input type="text" class="form-control shadow-sm" id="nombre_estudiante" name="nombre" required>
              <div class="invalid-feedback">Por favor ingresa el nombre.</div>
            </div>

            <div class="mb-3 col-12 col-md-6">
              <label for="apellidos_estudiante" class="form-label fw-semibold">
                <i class="bi bi-person-vcard me-2 text-primary"></i>Apellidos
              </label>
              <input type="text" class="form-control shadow-sm" id="apellidos_estudiante" name="apellidos" required>
            </div>

            <div class="mb-3 col-12 col-md-6">
              <label for="email_estudiante" class="form-label fw-semibold">
                <i class="bi bi-envelope-fill me-2 text-primary"></i>Email (Opcional)
              </label>
              <input type="email" class="form-control shadow-sm" id="email_estudiante" name="email">
            </div>

            <div class="mb-3 col-12 col-md-6">
              <label for="telefono_estudiante" class="form-label fw-semibold">
                <i class="bi bi-telephone-fill me-2 text-primary"></i>Teléfono
              </label>
              <input type="text" class="form-control shadow-sm" id="telefono_estudiante" name="telefono" required>
            </div>

            <div class="mb-3 col-12 col-md-6">
              <label for="fecha_nacimiento" class="form-label fw-semibold">
                <i class="bi bi-calendar-date-fill me-2 text-primary"></i>Fecha de Nacimiento
              </label>
              <input type="date" class="form-control shadow-sm" id="fecha_nacimiento" name="fecha_nacimiento" required>
            </div>

            <div class="mb-3 col-12 col-md-6">
              <label for="direccion_estudiante" class="form-label fw-semibold">
                <i class="bi bi-geo-alt-fill me-2 text-primary"></i>Dirección
              </label>
              <textarea class="form-control shadow-sm" id="direccion_estudiante" name="direccion" rows="2"
                required></textarea>
            </div>

            <div class="mb-3 col-12 col-md-6">
              <label for="escuela_id" class="form-label fw-semibold">
                <i class="bi bi-building me-2 text-primary"></i>Escuela de Conducción
              </label>
              <select class="form-select shadow-sm" id="escuela_id" name="escuela_id" required>
                <option value="">Selecciona una escuela</option>
              </select>
            </div>


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
              <label for="num" class="form-label fw-semibold">
                <i class="bi bi-file-earmark-text me-2 text-primary"></i>Número de Documento <span
                  class="text-danger">*</span>
              </label>
              <input type="text" class="form-control shadow-sm" id="num" name="num" required>
              <div class="invalid-feedback">Por favor ingresa el número de documento.</div>
            </div>


            <div class="form-check form-switch mb-3 col-12 col-md-6 d-none" id="activo-estudiante-container">
              <input class="form-check-input" type="checkbox" id="activo_estudiante" name="estado" value="activo">
              <label class="form-check-label fw-semibold" for="activo_estudiante">Estudiante
                activo</label>
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

  <div class="modal fade" id="modalAsignarCategoria" tabindex="-1" aria-labelledby="modalLabelCategoria"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">
            <i class="bi bi-card-checklist me-2 text-white"></i>
            Categorías asignadas al estudiante:
            <span id="nombreEstudiante" class="fw-semibold"></span>
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div id="seccionNuevaCategoria" class="mb-4 d-none">
            <form id="formNuevaCategoria">
              <p class="h3" id="edad"></p>
              <input type="hidden" name="estudiante_id" id="nuevo_estudiante_id">
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


   <script src="../js/jquery-3.7.1.min.js"></script>
   <script src="../js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


  <script>
    // Variables globales para el estado de la paginación y búsqueda
    let currentPage = 1;
    let currentLimit = parseInt($('#container-length').val()); // Obtener el valor inicial del select
    let currentSearchTerm = '';

    // Función para mostrar Toast (manteniendo tu función existente o reemplazándola con SweetAlert2)
    function mostrarToast(icon, title) {
      Swal.fire({
        icon: icon,
        title: title,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
      });
    }

    // Función para mostrar confirmación (usando SweetAlert2)
    function mostrarConfirmacionToast(text, callback) {
      Swal.fire({
        title: '¿Estás seguro?',
        text: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, continuar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
          callback();
        }
      });
    }

    // Función para cargar estudiantes desde el backend
    function cargarEstudiantes() {
      // Mostrar un spinner o mensaje de carga si lo deseas
      const tbody = $("table tbody");
      tbody.html(`
                <tr>
                    <td colspan="13" class="text-center text-muted">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        Cargando estudiantes...
                    </td>
                </tr>
            `);
      const paginationContainer = $(".pagination");
      paginationContainer.empty(); // Limpiar paginación mientras carga

      const url = `../api/listar_estudiante_secretaria.php?pagina=${currentPage}&limite=${currentLimit}&busqueda=${encodeURIComponent(currentSearchTerm)}`;

      $.ajax({
        url: url,
        method: 'GET',
        dataType: 'json',
        success: function (response) {
          if (response.status) {
            renderizarTabla(response.data);
            renderizarPaginacion(response.total_paginas, response.pagina_actual);
          } else {
            mostrarToast('error', response.message);
            renderizarTabla([]); // Vaciar tabla en caso de error
            renderizarPaginacion(0, 1);
          }
        },
        error: function (xhr, status, error) {
          console.error("Error al cargar estudiantes:", status, error, xhr.responseText);
          mostrarToast('error', 'Error de Conexión: No se pudo conectar con el servidor para obtener los estudiantes.');
          renderizarTabla([]); // Vaciar tabla en caso de error de conexión
          renderizarPaginacion(0, 1);
        }
      });
    }

    // Función para renderizar las filas de la tabla
    function renderizarTabla(estudiantes) {
      const tbody = $("#listarEstudiante");
      tbody.empty(); // Limpiar la tabla existente

      if (estudiantes.length > 0) {
        estudiantes.forEach(function (est) {
          const row = `
                        <tr>
                            <td class="text-center">${est.id}</td>
                            <td>${htmlspecialchars(est.apellidos)} ${htmlspecialchars(est.nombre)}</td>
                            <td>${htmlspecialchars(est.dni)}</td>
                            <td>${htmlspecialchars(est.escuela_nombre || '—')}</td> <td>${htmlspecialchars(est.email)}</td>
                            <td>${htmlspecialchars(est.fecha_nacimiento)}</td>
                            <td>${htmlspecialchars(est.telefono)}</td>
                            <td>${htmlspecialchars(est.direccion)}</td>
                            <td>${htmlspecialchars(est.categoria_nombre || '—')}</td> 
                            
                            <td>${htmlspecialchars(est.Doc)}</td>
                            <td class="text-center">
                                ${est.estado === 'activo' ? `
                                    <button class="btn btn-outline-success btn-sm d-flex align-items-center gap-2 px-3 py-1 rounded-pill shadow-sm"
                                        title="Haz clic para desactivar"
                                        onclick="cambiarEstadoEstudiante(${est.id}, 'inactivo')">
                                        <i class="bi bi-toggle-on fs-5"></i>
                                        Activo
                                    </button>` : `
                                    <button class="btn btn-outline-danger btn-sm d-flex align-items-center gap-2 px-3 py-1 rounded-pill shadow-sm"
                                        title="Haz clic para activar" onclick="cambiarEstadoEstudiante(${est.id}, 'activo')">
                                        <i class="bi bi-toggle-off fs-5"></i>
                                        Inactivo
                                    </button>`
            }
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-warning me-1" title="Editar"
                                    onclick="editarEstudiante(${est.id})">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-primary d-flex align-items-center gap-2 shadow-sm"
                                    onclick="abrirModalCategorias(${est.id}, '${htmlspecialchars(est.nombre, 'js')}', '${htmlspecialchars(est.fecha_nacimiento, 'js')}')"
                                    title="Ver detalles de categorias del estudiante">
                                    <i class="bi bi-eye"></i> Categorias
                                </button>
                            </td>
                        </tr>
                    `;
          tbody.append(row);
        });
      } else {
        // Mostrar mensaje de no resultados si no hay datos
        tbody.append(`
                    <tr>
                        <td colspan="13">
                            <div class="alert alert-info text-center m-0 rounded-0">
                                <i class="bi bi-info-circle-fill me-2"></i>No se encontraron resultados.
                            </div>
                        </td>
                    </tr>
                `);
      }
    }

    // Función para renderizar los enlaces de paginación
    function renderizarPaginacion(totalPaginas, paginaActual) {
      const paginationContainer = $(".pagination");
      paginationContainer.empty();

      if (totalPaginas <= 1) {
        paginationContainer.hide(); // Oculta la paginación si solo hay una página
        return;
      } else {
        paginationContainer.show();
      }

      // Botón "Anterior"
      paginationContainer.append(`
                <li class="page-item ${paginaActual <= 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${paginaActual - 1}">Anterior</a>
                </li>
            `);

      // Números de página
      // Lógica para mostrar un rango limitado de páginas (ej. 2 antes, 2 después de la actual)
      const maxPagesToShow = 5; // Número máximo de botones de página a mostrar
      let startPage = Math.max(1, paginaActual - Math.floor(maxPagesToShow / 2));
      let endPage = Math.min(totalPaginas, startPage + maxPagesToShow - 1);

      if (endPage - startPage + 1 < maxPagesToShow) {
        startPage = Math.max(1, endPage - maxPagesToShow + 1);
      }

      if (startPage > 1) {
        paginationContainer.append(`
                    <li class="page-item">
                        <a class="page-link" href="#" data-page="1">1</a>
                    </li>
                `);
        if (startPage > 2) {
          paginationContainer.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
        }
      }

      for (let i = startPage; i <= endPage; i++) {
        paginationContainer.append(`
                    <li class="page-item ${paginaActual === i ? 'active' : ''}">
                        <a class="page-link" href="#" data-page="${i}">${i}</a>
                    </li>
                `);
      }

      if (endPage < totalPaginas) {
        if (endPage < totalPaginas - 1) {
          paginationContainer.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
        }
        paginationContainer.append(`
                    <li class="page-item">
                        <a class="page-link" href="#" data-page="${totalPaginas}">${totalPaginas}</a>
                    </li>
                `);
      }


      // Botón "Siguiente"
      paginationContainer.append(`
                <li class="page-item ${paginaActual >= totalPaginas ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${paginaActual + 1}">Siguiente</a>
                </li>
            `);
    }

    // --- Event Listeners para la interacción ---

    $(document).ready(function () {
      // Evento para el buscador en tiempo real
      $("#customSearch").on("input", function () {
        currentSearchTerm = $(this).val();
        currentPage = 1; // Resetear a la primera página en cada búsqueda
        cargarEstudiantes();
      });

      // Evento para el selector de "Mostrar X entradas"
      $('#container-length').on('change', function () {
        currentLimit = parseInt($(this).val());
        currentPage = 1; // Resetear a la primera página cuando cambia el límite
        cargarEstudiantes();
      });

      // Evento para los enlaces de paginación (delegación para elementos generados dinámicamente)
      $(document).on('click', '.pagination .page-link', function (e) {
        e.preventDefault(); // Prevenir el comportamiento por defecto del enlace
        const newPage = $(this).data('page');
        // Solo cambiar de página si es una página válida y no es la actual o está deshabilitada
        if (newPage && newPage !== currentPage && !$(this).parent().hasClass('disabled')) {
          currentPage = newPage;
          cargarEstudiantes();
        }
      });

      // Cargar los estudiantes inicialmente al cargar la página
      cargarEstudiantes();
    });

    // --- Funciones auxiliares y de manejo de modales (tus funciones existentes adaptadas) ---

    // Helper para escapar HTML en JS, previene XSS
    function htmlspecialchars(str, type = 'html') {
      if (typeof str !== 'string') return str;
      let div = document.createElement('div');
      div.appendChild(document.createTextNode(str));
      if (type === 'js') { // Para usar dentro de atributos JS como onclick
        return div.innerHTML.replace(/'/g, "\\'").replace(/"/g, '\\"');
      }
      return div.innerHTML;
    }

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
      const select = document.getElementById("categorias_id");

      try {
        const res = await fetch('../api/obtener_categorias.php');
        const result = await res.json();

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

          select.disabled = !tieneOpciones;
          if (!tieneOpciones) {
            mostrarToast('warning', 'No hay ninguna categoría habilitada para la edad ingresada.');
          }
        } else {
          mostrarToast('warning', result.message || 'No se pudieron cargar las categorías.');
        }
      } catch (error) {
        console.error('Error cargando categorías:', error);
        mostrarToast('danger', 'Error al conectar con el servidor al cargar categorías.');
      }
    }

    // Escuchar cambios en el campo de fecha de nacimiento del modal de registro/edición
    document.getElementById("fecha_nacimiento").addEventListener("change", function () {
      const edad = calcularEdad(this.value);
      if (!isNaN(edad)) {
        filtrarCategoriasPorEdad(edad);
      } else {
        document.getElementById("categorias_id").innerHTML = "<option value=''>Seleccione una categoría</option>";
        document.getElementById("categorias_id").disabled = true;
      }
    });

    // Abrir modal de registro de estudiante
    function abrirModalRegistroEstudiante() {
      document.getElementById('modalEstudianteTitulo').textContent = 'Registrar Estudiante';
      document.getElementById('modalEstudianteBotonTexto').textContent = 'Registrar';
      document.getElementById('formularioEstudiante').reset();
      document.getElementById('estudiante_id').value = '';
      document.getElementById('activo-estudiante-container').classList.add('d-none');
      // Restablecer la validez del formulario
      document.getElementById('formularioEstudiante').classList.remove('was-validated');

      // Asegurarse de que el select de categorías esté deshabilitado hasta que se ingrese una fecha
      document.getElementById("categorias_id").innerHTML = "<option value=''>Seleccione una categoría</option>";
      document.getElementById("categorias_id").disabled = true;


      const modal = new bootstrap.Modal(document.getElementById('modalEstudiante'));
      modal.show();

      configurarSubmitEstudiante();
    }


    // Editar estudiante (obtiene datos y abre modal)
    async function editarEstudiante(id) {
      try {
        const response = await fetch(`../api/obtener_estudiante.php?id=${id}`); // Necesitarás crear este endpoint
        const result = await response.json();

        if (result.status) {
          const estudiante = result.data;
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
          document.getElementById('num').value = estudiante.Doc || ''; // Asumiendo que 'Doc' es el campo 'num' en tu formulario

          // Cargar y seleccionar escuela
          if (estudiante.escuela_id) {
            $('#escuela_id').val(estudiante.escuela_id); // Usar jQuery para select en caso de que lo uses
          }

          // Mostrar switch de estado
          document.getElementById('activo-estudiante-container').classList.remove('d-none');
          document.getElementById('activo_estudiante').checked = (estudiante.estado === 'activo' || estudiante.estado === 1);

          // Cargar categorías disponibles y seleccionar la del estudiante si aplica
          if (estudiante.fecha_nacimiento) {
            const edad = calcularEdad(estudiante.fecha_nacimiento);
            await filtrarCategoriasPorEdad(edad); // Espera a que las categorías se carguen
            // Después de cargar, selecciona la categoría si el estudiante la tiene
            // Nota: Tu PHP de lista principal solo trae UNA categoría si hay multiples.
            // Para editar, idealmente necesitarías traer TODAS las categorías asignadas al estudiante
            // para una gestión completa. Por ahora, solo selecciona la primera o la principal.
            // Si 'categoria_id' es el ID de la categoría principal o la primera.
            if (estudiante.categoria_id) { // Asumiendo que obtienes el ID de la categoría para edición
              document.getElementById('categorias_id').value = estudiante.categoria_id;
            }
          }

          // No estás pidiendo el campo usuario para editar, solo para mostrar.
          // document.getElementById('usuario_estudiante').value = estudiante.usuario || '';

          // Restablecer la validez del formulario
          document.getElementById('formularioEstudiante').classList.remove('was-validated');

          const modal = new bootstrap.Modal(document.getElementById('modalEstudiante'));
          modal.show();

          configurarSubmitEstudiante();

        } else {
          mostrarToast('error', result.message || 'Error al obtener datos del estudiante para editar.');
        }
      } catch (error) {
        console.error("Error al cargar datos del estudiante para edición:", error);
        mostrarToast('danger', 'Error de conexión al intentar obtener datos para edición.');
      }
    }


    // Envío del formulario de registro/actualización de estudiante
    function configurarSubmitEstudiante() {
      const form = document.getElementById('formularioEstudiante');

      form.onsubmit = async function (e) {
        e.preventDefault();
        if (!form.checkValidity()) {
          form.classList.add('was-validated');
          return;
        }

        const formData = new FormData(form);
        // Si el checkbox de estado no está marcado, asegúrate de enviar 'inactivo'
        if (!document.getElementById('activo_estudiante').checked) {
          formData.set('estado', 'inactivo');
        } else {
          formData.set('estado', 'activo'); // Asegurarse de que el valor sea 'activo'
        }

        // Si el campo usuario no está en el formulario, es posible que debas añadirlo manualmente aquí
        // o revisar si tu API lo espera de otra forma.
        // formData.append('usuario', document.getElementById('usuario_estudiante').value); 


        try {
          const response = await fetch('../api/guardar_actualizar_estudiantes.php', {
            method: 'POST',
            body: formData
          });

          const resultado = await response.json();

          if (resultado.status) {
            mostrarToast('success', resultado.message);
            bootstrap.Modal.getInstance(document.getElementById('modalEstudiante')).hide();
            cargarEstudiantes(); // Recargar la tabla con los datos actualizados
          } else {
            mostrarToast('warning', resultado.message || 'Error inesperado al guardar estudiante.');
          }
        } catch (error) {
          console.error("Error al guardar/actualizar estudiante:", error);
          mostrarToast('danger', 'Error de red o del servidor al guardar estudiante.');
        }
      };
    }

    // Cambiar estado del estudiante (activo/inactivo)
    function cambiarEstadoEstudiante(id, nuevoEstado) {
      mostrarConfirmacionToast(
        `¿Estás seguro de cambiar el estado del estudiante con ID ${id} a "${nuevoEstado}"?`,
        async () => {
          try {
            const response = await fetch('../api/cambiar_estado_estudiante.php', { // Necesitarás crear este endpoint
              method: 'POST',
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
              },
              body: `id=${id}&estado=${nuevoEstado}`
            });
            const result = await response.json();
            if (result.status) {
              mostrarToast('success', result.message);
              cargarEstudiantes(); // Recargar la tabla
            } else {
              mostrarToast('error', result.message || 'Error al cambiar estado.');
            }
          } catch (error) {
            console.error("Error al cambiar estado:", error);
            mostrarToast('danger', 'Error de conexión al cambiar estado.');
          }
        }
      );
    }

    // Eliminar estudiante
    function eliminarEstudiante(id, nombre) {
      mostrarConfirmacionToast(
        `¿Estás seguro de que deseas eliminar al estudiante "${nombre}"? Esta acción no se puede deshacer.`,
        async () => {
          try {
            const response = await fetch('../api/eliminar_estudiante.php', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
              },
              body: `id=${id}`
            });
            const result = await response.json();
            if (result.status) {
              mostrarToast('success', result.message);
              cargarEstudiantes(); // Recargar la tabla
            } else {
              mostrarToast('error', result.message || 'Error al eliminar estudiante.');
            }
          } catch (error) {
            console.error("Error al eliminar estudiante:", error);
            mostrarToast('danger', 'Error de conexión al eliminar estudiante.');
          }
        }
      );
    }

    // Cargar opciones dinámicas de escuela
    async function cargarEscuelas() {
      try {
        const res = await fetch('../api/obtener_escuelas.php');
        const result = await res.json();

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
          mostrarToast('warning', result.message || 'No se pudo cargar la lista de escuelas.');
        }
      } catch (error) {
        console.error('Error cargando escuelas:', error);
        mostrarToast('danger', 'Error al conectar con el servidor para escuelas.');
      }
    }

    document.addEventListener('DOMContentLoaded', cargarEscuelas);


    // --- Lógica para el Modal de Categorías Asignadas ---
    let estudianteEdadGlobal = null; // Mantener para el modal de categorías

    function abrirModalCategorias(estudianteId, nombreEstudiante, fecha_nacimiento) {
      const modalCategorias = new bootstrap.Modal(document.getElementById('modalAsignarCategoria'));
      modalCategorias.show();

      estudianteEdadGlobal = calcularEdad(fecha_nacimiento); // Calcula la edad del estudiante
      document.getElementById('nuevo_estudiante_id').value = estudianteId;
      document.getElementById('nombreEstudiante').textContent = nombreEstudiante;
      document.getElementById('edad').textContent = `${estudianteEdadGlobal} Años`; // Muestra la edad en el modal

      cargarCategoriasAsignadas(estudianteId);
      ocultarFormularioNuevaCategoria(); // Asegurarse de que el formulario de nueva categoría esté oculto al abrir
    }

    async function cargarCategoriasAsignadas(estudianteId) {
      const tbody = document.getElementById('tablaCategoriasEstudiante');
      tbody.innerHTML = `<tr><td colspan="5" class="text-center text-muted">
                                    <div class="spinner-border text-secondary spinner-border-sm me-2" role="status"></div>
                                    Cargando categorías...
                                </td></tr>`;
      try {
        const res = await fetch(`../api/obtener_categorias_estudiante.php?estudiante_id=${estudianteId}`);
        const data = await res.json();

        tbody.innerHTML = ''; // Limpiar después de la carga

        if (data.status && data.data.length > 0) {
          data.data.forEach(cat => {
            tbody.innerHTML += `
                            <tr>
                                <td>${cat.id}</td>
                                <td>${htmlspecialchars(cat.categoria)}</td>
                                <td><span class="badge bg-${estadoColor(cat.estado)}">${htmlspecialchars(cat.estado)}</span></td>
                                <td>${htmlspecialchars(cat.fecha_asignacion)}</td> 
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
          tbody.innerHTML = `<tr><td colspan="5" class="text-center text-muted">No tiene categorías asignadas.</td></tr>`;
        }
      } catch (err) {
        console.error("Error al cargar categorías asignadas:", err);
        tbody.innerHTML = `<tr><td colspan="5" class="text-center text-danger">Error al cargar categorías.</td></tr>`;
        mostrarToast('error', 'Error al cargar categorías asignadas.');
      }
    }

    function estadoColor(estado) {
      switch (estado) {
        case 'aprobado':
          return 'success';
        case 'rechazado':
          return 'danger';
        case 'en_proceso':
          return 'warning';
        default:
          return 'secondary';
      }
    }

    function mostrarFormularioNuevaCategoria() {
      const seccion = document.getElementById('seccionNuevaCategoria');
      seccion.classList.remove('d-none');
      cargarCategoriasDisponiblesParaAsignar(); // Llama a la función específica para el modal
    }

    function ocultarFormularioNuevaCategoria() {
      document.getElementById('seccionNuevaCategoria').classList.add('d-none');
      document.getElementById('formNuevaCategoria').reset(); // Limpiar el formulario
    }

    // Carga categorías disponibles para el SELECT dentro del modal de asignación
    async function cargarCategoriasDisponiblesParaAsignar() {
      const select = document.getElementById('selectNuevaCategoria');
      select.innerHTML = '<option value="">-- Seleccionar --</option>'; // Limpiar opciones anteriores

      try {
        const res = await fetch('../api/obtener_categorias.php');
        const result = await res.json();

        if (result.status && Array.isArray(result.data)) {
          let hasOptions = false;
          result.data.forEach(cat => {
            // Filtra categorías por la edad global del estudiante
            if (estudianteEdadGlobal !== null && estudianteEdadGlobal >= cat.edad_minima) {
              const option = document.createElement('option');
              option.value = cat.id;
              option.textContent = cat.nombre;
              select.appendChild(option);
              hasOptions = true;
            }
          });

          if (!hasOptions) {
            select.innerHTML = '<option value="">No hay categorías disponibles para esta edad</option>';
            select.disabled = true;
          } else {
            select.disabled = false;
          }
        } else {
          select.innerHTML = '<option value="">Error al cargar categorías</option>';
          select.disabled = true;
          mostrarToast('warning', result.message || 'No se pudieron cargar las categorías disponibles.');
        }
      } catch (error) {
        console.error('Error cargando categorías disponibles para asignación:', error);
        select.innerHTML = '<option value="">Error de conexión</option>';
        select.disabled = true;
        mostrarToast('danger', 'Error al conectar para cargar categorías disponibles.');
      }
    }


    // Manejar el submit del formulario para asignar nueva categoría
    document.getElementById('formNuevaCategoria').addEventListener('submit', async function (e) {
      e.preventDefault();

      const form = e.target;
      if (!form.checkValidity()) {
        form.classList.add('was-validated');
        return;
      }

      const formData = new FormData(form);
      const estudiante_id = formData.get('estudiante_id'); // Asegúrate de que este ID esté presente en el formulario

      try {
        const response = await fetch('../api/guardar_categoria_estudiante.php', {
          method: 'POST',
          body: formData
        });
        const data = await response.json();

        if (data.status) {
          mostrarToast('success', data.message);
          ocultarFormularioNuevaCategoria();
          cargarCategoriasAsignadas(estudiante_id); // Recarga la tabla de categorías del modal
          cargarEstudiantes(); // Recarga la tabla principal para actualizar la columna de categoría
        } else {
          mostrarToast('warning', data.message || 'Error al asignar la categoría.');
        }
      } catch (error) {
        console.error("Error al guardar categoría del estudiante:", error);
        mostrarToast('danger', 'Error de red o servidor al guardar la categoría.');
      }
    });


    // Eliminar categoría asignada a un estudiante
    function eliminarCategoriaAsignada(asignacion_id, estudiante_id) {
      mostrarConfirmacionToast(`¿Estás seguro de eliminar esta asignación de categoría?`, async () => {
        try {
          const response = await fetch('../api/eliminar_categoria_estudiante.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `asignacion_id=${asignacion_id}`
          });
          const data = await response.json();

          if (data.status) {
            mostrarToast('success', data.message);
            cargarCategoriasAsignadas(estudiante_id); // Recargar la tabla de categorías del modal
            cargarEstudiantes(); // Recargar la tabla principal
          } else {
            mostrarToast('warning', data.message || 'Error al eliminar la asignación.');
          }
        } catch (error) {
          console.error("Error al eliminar categoría asignada:", error);
          mostrarToast('danger', 'Error de red o servidor al eliminar la asignación.');
        }
      });
    }
  </script>
</main>


<script src="../js/alerta.js"></script>
</body>

</html>