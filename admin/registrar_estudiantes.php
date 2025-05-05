<?php

session_start();
require '../config/conexion.php';

$conn = $pdo->getConexion();
$categoria_carne = [];
$escuelas = [];
$alerta = null;

// Recuperar alerta de sesión si existe
if (isset($_SESSION['alerta'])) {
    $alerta = $_SESSION['alerta'];
    unset($_SESSION['alerta']); // Limpiar para no mostrar siempre
}

try {
    // Cargar datos
    $stmt = $conn->prepare("SELECT id, nombre, edad_minima FROM categorias_carne ORDER BY nombre ASC");
    $stmt->execute();
    $categoria_carne = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT id, nombre FROM escuelas_conduccion ORDER BY nombre ASC");
    $stmt->execute();
    $escuelas = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $alerta = [
        'tipo' => 'error',
        'mensaje' => 'Hubo un problema al cargar las escuelas o categorías. Intente nuevamente.'
    ];
} finally {
    $stmt = null;
    $pdo->closeConexion();
}


include '../componentes/head_admin.php';
include '../componentes/menu_admin.php';
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

    <div class="container-fluid mt-5 pt-2">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-6">
                <div class="card shadow rounded-4 p-4">
                    <div class="card-header bg-primary text-white rounded-3 mb-4">
                        <h4 class="mb-0 d-flex align-items-center">
                            <i class="bi bi-person-plus-fill me-2 fs-4"></i>Registrar Estudiante
                        </h4>
                    </div>
                    <div class="card-body">


                        <form action="../php/guardar_estudiante.php" method="POST" class="needs-validation" novalidate>
                            <div class="row">
                                <!-- Columna izquierda -->
                                <div class="col-md-6">
                                    <!-- Escuela -->
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">
                                            <i class="bi bi-building me-2 text-primary"></i>Escuela de Conducción <span
                                                class="text-danger">*</span>
                                        </label>
                                        <select name="escuela_id" class="form-select" required>
                                            <option value="">Seleccione una escuela</option>
                                            <?php foreach ($escuelas as $escuela): ?>
                                                <option value="<?= $escuela['id'] ?>"><?= $escuela['nombre'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback">Por favor, selecciona una escuela.</div>
                                    </div>

                                    <!-- Identificación -->
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">
                                            <i class="bi bi-card-heading me-2 text-primary"></i>Identificación <span
                                                class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="numero_identificacion" class="form-control" required>
                                        <div class="invalid-feedback">Ingrese el número de identificación.</div>
                                    </div>

                                    <!-- Nombre -->
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">
                                            <i class="bi bi-person me-2 text-primary"></i>Nombre <span
                                                class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="nombre" class="form-control" required>
                                        <div class="invalid-feedback">Ingrese el nombre del estudiante.</div>
                                    </div>

                                    <!-- Apellido -->
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">
                                            <i class="bi bi-person-fill me-2 text-primary"></i>Apellido <span
                                                class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="apellido" class="form-control" required>
                                        <div class="invalid-feedback">Ingrese el apellido del estudiante.</div>
                                    </div>
                                </div>

                                <!-- Columna derecha -->
                                <div class="col-md-6">
                                    <!-- Fecha de nacimiento -->
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">
                                            <i class="bi bi-calendar-date me-2 text-primary"></i>Fecha de Nacimiento
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" name="fecha_nacimiento" class="form-control"
                                            id="fecha_nacimiento" required>
                                        <div class="invalid-feedback">Ingrese una fecha válida.</div>
                                    </div>

                                    <!-- Teléfono -->
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">
                                            <i class="bi bi-telephone-fill me-2 text-primary"></i>Teléfono
                                        </label>
                                        <input type="tel" name="telefono" class="form-control" pattern="\d{9,15}">
                                        <div class="invalid-feedback">Ingrese un número de teléfono válido.</div>
                                    </div>

                                    <!-- Dirección -->
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">
                                            <i class="bi bi-geo-alt-fill me-2 text-primary"></i>Dirección
                                        </label>
                                        <input type="text" name="direccion" class="form-control">
                                    </div>

                                    <!-- Categoría de Carné -->
                                    <div class="mb-3">
                                        <label for="categoria_carne" class="form-label fw-semibold">
                                            <i class="bi bi-card-list me-2 text-primary"></i>Categoría de Carné <span
                                                class="text-danger">*</span>
                                        </label>
                                        <select name="categoria_carne" id="categoria_carne" class="form-select" disabled
                                            required>
                                            <option value="">Seleccione una categoría</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Por favor selecciona una categoría de carné.
                                        </div>
                                        <div id="mensaje_categoria_vacia" class="text-danger mt-2 fw-semibold"
                                            style="display: none;">
                                            No hay ninguna categoría habilitada para la edad ingresada.
                                        </div>
                                        <div id="examen-alerta" class="mt-2" style="display: none;">
                                            <div
                                                class="alert alert-warning d-flex justify-content-between align-items-center">
                                                No existe un examen para esta categoría.
                                                <a href="registrar_examenes.php" id="btn-registrar-examen"
                                                    class="btn btn-sm btn-outline-primary">
                                                    Registrar Examen
                                                </a>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>

                            <!-- Botones -->
                            <div class="d-flex justify-content-between flex-column flex-sm-row gap-2 mt-3">
                                <a href="estudiantes.php" class="btn btn-outline-secondary w-100">
                                    <i class="bi bi-arrow-left-circle me-2"></i>Volver
                                </a>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-person-plus-fill me-2"></i>Registrar Estudiante
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<body data-alerta="<?= $alerta ? 'true' : 'false' ?>">

    <script>
        document.getElementById('sidebarToggle').addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('show');
        });


        // Función para cerrar el modal automáticamente después de 5 segundos
        window.onload = function () {
            const alertModal = new bootstrap.Modal(document.getElementById('alertModal'), {
                keyboard: false,
                backdrop: 'static'
            });

            // Si hay un mensaje en la sesión, mostramos el modal y lo cerramos después de 5 segundos
            <?php if ($alerta): ?>
                alertModal.show();
                setTimeout(function () {
                    alertModal.hide();
                }, 5000); // Cierra el modal después de 5 segundos
            <?php endif; ?>
        }
    </script>

    <script>
        // Calcular edad a partir de la fecha de nacimiento
        document.addEventListener("DOMContentLoaded", function () {
            // Función para calcular la edad a partir de la fecha de nacimiento
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

            // Función para filtrar las categorías según la edad
            function filtrarCategoriasPorEdad(edad) {
                const categorias = <?php echo json_encode($categoria_carne); ?>;
                const select = document.getElementById("categoria_carne");
                const mensaje = document.getElementById("mensaje_categoria_vacia");

                select.innerHTML = "<option value=''>Seleccione una categoría</option>";
                let tieneOpciones = false;

                categorias.forEach(categoria => {
                    if (edad >= categoria.edad_minima) {
                        const option = document.createElement("option");
                        option.value = categoria.id;
                        option.textContent = categoria.nombre;
                        select.appendChild(option);
                        tieneOpciones = true;
                    }
                });

                if (tieneOpciones) {
                    select.disabled = false;
                    mensaje.style.display = "none";
                } else {
                    select.disabled = true;
                    mensaje.style.display = "block";
                }
            }

            // Escuchar cambios en el campo de fecha de nacimiento
            document.getElementById("fecha_nacimiento").addEventListener("change", function () {
                const edad = calcularEdad(this.value);
                filtrarCategoriasPorEdad(edad);
            });

            // Verificar si el modal de alerta debe mostrarse y cerrarlo después de 5 segundos
            const alertModal = new bootstrap.Modal(document.getElementById('alertModal'), {
                keyboard: false,
                backdrop: 'static'
            });

            if (document.body.dataset.alerta === "true") {
                alertModal.show();
                setTimeout(function () {
                    alertModal.hide();
                }, 5000);
            }
        });

    </script>


<script>
document.getElementById("categoria_carne").addEventListener("change", function () {
    const categoriaId = this.value;

    if (categoriaId === "") {
        document.getElementById("examen-alerta").style.display = "none";
        return;
    }

    fetch(`../php/verificar_examen_categoria.php?categoria_id=${categoriaId}`)
        .then(res => res.json())
        .then(data => {
            if (data.existe) {
                document.getElementById("examen-alerta").style.display = "none";
            } else {
                document.getElementById("examen-alerta").style.display = "block";
            }
        })
        .catch(err => {
            console.error("Error al verificar examen:", err);
            document.getElementById("examen-alerta").style.display = "none";
        });
});
</script>

    <?php include_once('../componentes/footer.php'); ?>