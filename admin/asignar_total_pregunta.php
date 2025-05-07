<?php
include '../componentes/head_admin.php';
include '../componentes/menu_admin.php';

require_once '../config/conexion.php';
// Verifica si la clase $pdo existe y se puede obtener conexión
$conn = method_exists($pdo, 'getConexion') ? $pdo->getConexion() : null;
 

// Obtener los parámetros
$examenes_estudiantes_id = $_GET['id'] ?? null;
 

$categoria_carne = null;
$examen_estudiante = null;
$examen = null;
$estudiante = null;
$total_preguntas = 0;

if ($examenes_estudiantes_id) {
    try {
        // Obtener datos del examen_estudiante
        $stmt = $conn->prepare("SELECT 
                                        ee.*,
                                        e.titulo AS nombre_examen,
                                        e.total_preguntas AS total_pregunta_examen,
                                        est.nombre AS nombre_estudiante,
                                        est.id AS id_estudiante,
                                        est.apellido AS apellido_estudiante
                                         FROM examenes_estudiantes ee
                                         LEFT JOIN examenes e ON ee.categoria_carne_id = e.categoria_carne_id
                                         LEFT JOIN estudiantes est ON ee.estudiante_id = est.id
                                          WHERE ee.id = ?");
        $stmt->execute([$examenes_estudiantes_id]);
        $examen_estudiante = $stmt->fetch(PDO::FETCH_ASSOC);
        //print_r($examen_estudiante);
        
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }
}else{
    echo 'error';
}

$categoriaSeleccionada = $examen['categoria_carne_id'] ?? '';
$titulo = $examen['titulo'] ?? '';
$descripcion = $examen['descripcion'] ?? ''; 
?>

<div class="main-content">
    <div class="container-fluid mt-5 pt-2">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-6">
                <div class="card shadow rounded-4 p-4">
                    <div class="card-header bg-warning text-white rounded-3 mb-4">
                        <h4 class="mb-0 d-flex align-items-center">
                            <i class="bi bi-journal-bookmark me-2 fs-4"></i>
                            Asignar Preguntas a Estudiante
                        </h4>
                    </div>
                    <div class="card-body">
                        <!-- Formulario con validación Bootstrap -->
                        <form action="../php/guardar_total_pregunta.php" method="POST" class="needs-validation" novalidate>
                           
                                <input type="hidden" name="examenes_estudiantes_id" value="<?= htmlspecialchars($examen_estudiante['id']) ?>">
                                <input type="hidden" name="estudiante_id" value="<?= htmlspecialchars($examen_estudiante['id_estudiante']) ?>">
                          

                            <!-- Nombre del Estudiante (lectura) -->
                            <div class="mb-3">
                                <label for="estudiante" class="form-label fw-semibold">
                                    <i class="bi bi-person-circle me-2 text-primary"></i>Estudiante:
                                </label>
                                <input type="text" class="form-control shadow-sm" id="estudiante" value="<?= htmlspecialchars($examen_estudiante['nombre_estudiante'] . ' ' . $examen_estudiante['apellido_estudiante']) ?>" readonly>
                            </div>

                            <!-- Examen -->
                            <div class="mb-3">
                                <label for="examen" class="form-label fw-semibold">
                                    <i class="bi bi-journal-text me-2 text-primary"></i>Examen:
                                </label>
                                <input type="text" class="form-control shadow-sm" id="examen" value="<?= htmlspecialchars($examen_estudiante['nombre_examen']) ?>" readonly>
                            </div>

                            <!-- Total de preguntas -->
                            <div class="mb-3">
                                <label for="total_preguntas" class="form-label fw-semibold">
                                    <i class="bi bi-list-check me-2 text-primary"></i>Total de Preguntas a Asignar:
                                </label>
                                <input type="number" class="form-control shadow-sm" id="total_preguntas" name="total_preguntas" value="" required min="1" max="<?= htmlspecialchars($examen_estudiante['total_pregunta_examen']) ?>" step="1">
                                <div class="invalid-feedback">Por favor, ingrese un número válido de preguntas (no mayor a <?= htmlspecialchars($examen_estudiante['total_pregunta_examen']) ?>).</div>
                            </div>

                            <!-- Botones -->
                            <div class="d-flex justify-content-between flex-column flex-sm-row gap-2">
                                <a href="examenes_estudiantes.php" class="btn btn-outline-secondary w-100">
                                    <i class="bi bi-arrow-left-circle me-2"></i>Volver
                                </a>
                                <button type="submit" class="btn btn-warning w-100">
                                    <i class="bi bi-save2-fill me-2"></i>Asignar Preguntas
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script de validación Bootstrap -->
<script>
    // Validación visual Bootstrap 5
    (() => {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>

<?php include_once('../componentes/footer.php'); ?>
