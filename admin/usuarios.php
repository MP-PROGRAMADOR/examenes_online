<?php
require '../includes/conexion.php';

 
try {
  // Preparar la consulta para obtener los datos
  $sql = "SELECT * FROM usuarios";
  $stmt = $pdo->prepare($sql);
 
if( $stmt->execute()){
  // Obtener los resultados como un array asociativo
  $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

}

  
} catch (Exception $e) {
  die("Error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
}

include_once("../includes/header.php");
include_once("../includes/sidebar.php");
?>

<!-- Main -->
<div class="main-content">
  
  <div class="card shadow border-0 rounded-4">
    <div
      class="card-header bg-primary text-white d-flex flex-wrap justify-content-between align-items-center rounded-top-4 px-4 py-3">
      <h5 class="mb-0"><i class="bi bi-people-fill me-2"></i>Gestión de Usuarios</h5>
      <div class="search-box position-relative">
        <input type="text" class="form-control ps-5" id="customSearch" placeholder="Buscar usuario...">
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
          <i class="bi bi-person-plus-fill me-2"></i>Nuevo Usuario
        </button>

      </div>
    </div>
    <div class="table-responsive">
      <table id="usuarios-table" class="table table-hover align-middle shadow-sm rounded-3 overflow-hidden">
        <thead class="table-light text-center">
          <?php if (!empty($usuarios)): ?>
            <tr>
              <th><i class="bi bi-hash me-1"></i>ID</th>
              <th><i class="bi bi-person-fill me-1"></i>Nombre</th>
              <th><i class="bi bi-envelope-fill me-1"></i>Email</th>
              <th><i class="bi bi-shield-lock-fill me-1"></i>Contraseña</th>
              <th><i class="bi bi-person-badge-fill me-1"></i>Rol</th>
              <th><i class="bi bi-calendar-check-fill me-1"></i>Fecha</th>
              <th><i class="bi bi-toggle-on me-1"></i>Activo</th>
              <th><i class="bi bi-gear-fill me-1"></i>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($usuarios as $usuario): ?>
              <tr>
                <td class="text-center"><?= htmlspecialchars($usuario['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?= htmlspecialchars($usuario['nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?= htmlspecialchars($usuario['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><span class="text-muted small fst-italic">••••••••</span></td>
                <td>
                  <span class="badge bg-secondary text-uppercase">
                    <?= htmlspecialchars($usuario['rol'], ENT_QUOTES, 'UTF-8'); ?>
                  </span>
                </td>
                <td><?= htmlspecialchars($usuario['creado_en'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td class="text-center">
                  <?php if ($usuario['activo']): ?>
                    <button
                      class="btn btn-outline-success btn-sm d-flex align-items-center gap-2 px-3 py-1 rounded-pill shadow-sm"
                      title="Haz clic para desactivar" onclick="cambiarEstadoUsuario(<?= $usuario['id'] ?>, false)">
                      <i class="bi bi-toggle-on fs-5"></i>
                      Activo
                    </button>
                  <?php else: ?>
                    <button
                      class="btn btn-outline-danger btn-sm d-flex align-items-center gap-2 px-3 py-1 rounded-pill shadow-sm"
                      title="Haz clic para activar" onclick="cambiarEstadoUsuario(<?= $usuario['id'] ?>, true)">
                      <i class="bi bi-toggle-off fs-5"></i>
                      Inactivo
                    </button>
                  <?php endif; ?>

                </td>
                <td class="text-center">
                  <div class="d-flex gap-2 justify-content-center flex-wrap">
                    <button class="btn btn-sm btn-outline-warning" onclick="abrirModalEdicion({
                          id: <?= (int) $usuario['id']; ?>,
                          nombre: '<?= addslashes(htmlspecialchars($usuario['nombre'], ENT_QUOTES, 'UTF-8')); ?>',
                          email: '<?= addslashes(htmlspecialchars($usuario['email'], ENT_QUOTES, 'UTF-8')); ?>',
                          rol: '<?= addslashes(htmlspecialchars($usuario['rol'], ENT_QUOTES, 'UTF-8')); ?>'
                        })">
                      <i class="bi bi-pencil-square me-1"></i> Editar
                    </button> 
                    <?php if (($rol === 'admin')): ?>
                      <button class="btn btn-sm btn-outline-danger eliminar-usuario-btn"
                        onclick="eliminarUsuario(<?= htmlspecialchars($usuario['id'], ENT_QUOTES, 'UTF-8') ?>, '<?= htmlspecialchars($usuario['nombre'], ENT_QUOTES, 'UTF-8') ?>')"
                        title="Eliminar Usuario">
                        <i class="bi bi-trash me-1"></i>Eliminar
                      </button>
                    <?php endif; ?>

                  </div>
                </td>

              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="alert alert-warning text-center m-3">
              <i class="bi bi-exclamation-circle-fill me-2"></i>⚠️ No hay usuarios registrados actualmente.
            </div>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

 

<!-- Modal Registro / Edición (reutilizado) -->
<div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="modalUsuarioLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow rounded-4">
      <div class="modal-header bg-primary text-white rounded-top">
        <h5 class="modal-title" id="modalUsuarioLabel">
          <i class="bi bi-person-plus-fill me-2"></i><span id="modalTitulo">Registrar Usuario</span>
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <form id="formularioEditarRegistrar" method="POST" class="needs-validation" novalidate>
        <div class="modal-body p-4">
          <input type="hidden" name="usuario_id" id="usuario_id">

          <!-- Nombre -->
          <div class="mb-3">
            <label for="nombre" class="form-label fw-semibold">
              <i class="bi bi-person-circle me-2 text-primary"></i>Nombre Completo <span class="text-danger">*</span>
            </label>
            <input type="text" class="form-control shadow-sm" id="nombre" name="nombre" required>
            <div class="invalid-feedback">Por favor ingresa el nombre completo.</div>
          </div>

          <!-- Email -->
          <div class="mb-3">
            <label for="email" class="form-label fw-semibold">
              <i class="bi bi-envelope-fill me-2 text-primary"></i>Correo Electrónico <span class="text-danger">*</span>
            </label>
            <input type="email" class="form-control shadow-sm" id="email" name="email" required>
            <div class="invalid-feedback">Ingresa un correo electrónico válido.</div>
          </div>

          <!-- Contraseña -->
          <div class="mb-3 position-relative">
            <label for="contrasena" class="form-label fw-semibold">
              <i class="bi bi-lock-fill me-2 text-primary"></i>Contraseña <small class="text-muted">(dejar vacío para no
                cambiar)</small>
            </label>
            <div class="input-group">
              <input type="password" class="form-control shadow-sm" id="contrasena" name="contrasena" minlength="6"
                placeholder="Nueva contraseña">
              <button type="button" class="btn btn-outline-secondary" id="toggle-password">
                <i class="bi bi-eye-fill"></i>
              </button>
            </div>
            <div class="invalid-feedback">La contraseña debe tener al menos 6 caracteres.</div>
          </div>

          <!-- Rol -->
          <div class="mb-3">
            <label for="rol" class="form-label fw-semibold">
              <i class="bi bi-person-gear me-2 text-primary"></i>Rol <span class="text-danger">*</span>
            </label>
            <select class="form-select shadow-sm" id="rol" name="rol" required>
              <option value="">Seleccionar Rol</option>
              <option value="admin">Administrador</option>
              <option value="examinador">Examinador</option>
              <option value="secretaria">Secretaria</option>
            </select>
            <div class="invalid-feedback">Selecciona un rol para el usuario.</div>
          </div>

          <!-- Activo (solo visible en edición) -->
          <div class="form-check form-switch mb-3 d-none" id="activo-container">
            <input class="form-check-input" type="checkbox" id="activo" name="activo" value="1">
            <label class="form-check-label fw-semibold" for="activo">Usuario activo</label>
          </div>
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

    // Toggle mostrar/ocultar contraseña
    const togglePasswordBtn = document.getElementById('toggle-password');
    togglePasswordBtn.addEventListener('click', () => {
      const pwdInput = document.getElementById('contrasena');
      const icon = togglePasswordBtn.querySelector('i');
      if (pwdInput.type === 'password') {
        pwdInput.type = 'text';
        icon.classList.replace('bi-eye-fill', 'bi-eye-slash-fill');
      } else {
        pwdInput.type = 'password';
        icon.classList.replace('bi-eye-slash-fill', 'bi-eye-fill');
      }
    });

  })();

  // Función para abrir modal en modo registro
  function abrirModalRegistro() {
    document.getElementById('modalTitulo').textContent = 'Registrar Usuario';
    document.getElementById('modalBotonTexto').textContent = 'Registrar';
    document.getElementById('usuario_id').value = '';
    document.getElementById('nombre').value = '';
    document.getElementById('email').value = '';
    document.getElementById('contrasena').value = '';
    document.getElementById('rol').value = '';
    // Ocultar checkbox activo en registro
    document.getElementById('activo-container').classList.add('d-none');

    const modal = new bootstrap.Modal(document.getElementById('modalUsuario'));
    modal.show();

    document.getElementById('formularioEditarRegistrar').addEventListener('submit', async function (e) {
      e.preventDefault(); // Evita que el formulario se envíe por defecto

      const form = e.target;
      const formData = new FormData(form); // Captura todos los datos del formulario

      try {
        const response = await fetch('../api/guardar_actualizar_usuarios.php', {
          method: 'POST',
          body: formData
        });

        const resultado = await response.json();

        if (resultado.status) {
          mostrarToast('success', resultado.message);
         // console.log(resultado.message)
          // Opcional: cerrar modal
          const modal = bootstrap.Modal.getInstance(document.getElementById('modalUsuario'));
          modal.hide();
          // Recargar tabla o lista de usuarios si corresponde
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

  // Función para abrir modal en modo edición, recibe un objeto usuario con los datos
  function abrirModalEdicion(usuario) {
    document.getElementById('modalTitulo').textContent = 'Editar Usuario';
    document.getElementById('modalBotonTexto').textContent = 'Actualizar';
    document.getElementById('usuario_id').value = usuario.id;
    document.getElementById('nombre').value = usuario.nombre;
    document.getElementById('email').value = usuario.email;
    document.getElementById('contrasena').value = '';
    document.getElementById('rol').value = usuario.rol;

    // Mostrar checkbox activo solo si el campo está definido
    if ('activo' in usuario) {
      document.getElementById('activo-container').classList.remove('d-none');
      document.getElementById('activo').checked = usuario.activo == 1 || usuario.activo === true;
    } else {
      document.getElementById('activo-container').classList.add('d-none');
      document.getElementById('activo').checked = false;
    }

    const modal = new bootstrap.Modal(document.getElementById('modalUsuario'));
    modal.show();
    
    /* ----- capturamos y enviamos la actualizacion al backend--------- */
    document.getElementById('formularioEditarRegistrar').addEventListener('submit', async function (e) {
      e.preventDefault(); // Evita que el formulario se envíe por defecto

      const form = e.target;
      const formData = new FormData(form); // Captura todos los datos del formulario

      try {
        const response = await fetch('../api/guardar_actualizar_usuarios.php', {
          method: 'POST',
          body: formData
        });

        const resultado = await response.json();

        if (resultado.status) {
          mostrarToast('success', resultado.message);
          
          // Opcional: cerrar modal
          const modal = bootstrap.Modal.getInstance(document.getElementById('modalUsuario'));
          modal.hide();
          // Recargar tabla o lista de usuarios si corresponde
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
 
  function cambiarEstadoUsuario(idUsuario, nuevoEstado) {
    mostrarConfirmacionToast(
      `¿Estás seguro de que deseas ${nuevoEstado ? 'activar' : 'desactivar'} este usuario?`,
      () => {
        const formData = new FormData();
        formData.append('id', idUsuario);
        formData.append('estado', nuevoEstado ? 1 : 0);

        fetch('../api/cambiar_estado_usuario.php', {
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

  function eliminarUsuario(idUsuario, usuario) {
    mostrarConfirmacionToast(
      `¿Estás seguro de que deseas eliminar el usuario ${usuario}?`,
      () => {
        const formData = new FormData();
        formData.append('id', idUsuario);

        fetch('../api/eliminar_usuario.php', {
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



</script>


<?php include_once('../includes/footer.php'); ?>