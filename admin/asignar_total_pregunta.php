<?php
include '../componentes/head_admin.php';
include '../componentes/menu_admin.php';

require_once '../config/conexion.php';
$conn = $pdo->getConexion();

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
        $stmt = $conn->prepare("SELECT * FROM examenes_estudiantes WHERE id = ?");
        $stmt->execute([$examenes_estudiantes_id]);
        $examen_estudiante = $stmt->fetch(PDO::FETCH_ASSOC);
      //  print_r($examen_estudiante);
        if ($examen_estudiante) {
            // Obtener datos del examen
            $stmt_examen = $conn->prepare("SELECT * FROM examenes WHERE id = ?");
            $stmt_examen->execute([$examen_estudiante['categoria_carne_id']]); // Corregido de 'categoria_carne_id' a 'examen_id'
            $examen = $stmt_examen->fetch(PDO::FETCH_ASSOC);

            // Obtener datos del estudiante
            $stmt_estudiante = $conn->prepare("SELECT * FROM estudiantes WHERE id = ?");
            $stmt_estudiante->execute([$examen_estudiante['estudiante_id']]);
            $estudiante = $stmt_estudiante->fetch(PDO::FETCH_ASSOC);

            // Verificar si el examen y estudiante existen
            if ($examen) {
                $total_preguntas = $examen['total_preguntas'] ?? 0;
            }
        } else {
            echo "Examen Estudiante no encontrado.";
            exit;
        }
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
                            <?php if ($examenes_estudiantes_id && $estudiante['id']): ?>
                                <input type="hidden" name="examenes_estudiantes_id" value="<?= htmlspecialchars($examenes_estudiantes_id) ?>">
                                <input type="hidden" name="estudiante_id" value="<?= htmlspecialchars($estudiante['id']) ?>">
                            <?php endif; ?>

                            <!-- Nombre del Estudiante (lectura) -->
                            <div class="mb-3">
                                <label for="estudiante" class="form-label fw-semibold">
                                    <i class="bi bi-person-circle me-2 text-primary"></i>Estudiante:
                                </label>
                                <input type="text" class="form-control shadow-sm" id="estudiante" value="<?= htmlspecialchars($estudiante['nombre'] . ' ' . $estudiante['apellido']) ?>" readonly>
                            </div>

                            <!-- Examen -->
                            <div class="mb-3">
                                <label for="examen" class="form-label fw-semibold">
                                    <i class="bi bi-journal-text me-2 text-primary"></i>Examen:
                                </label>
                                <input type="text" class="form-control shadow-sm" id="examen" value="<?= htmlspecialchars($titulo) ?>" readonly>
                            </div>

                            <!-- Total de preguntas -->
                            <div class="mb-3">
                                <label for="total_preguntas" class="form-label fw-semibold">
                                    <i class="bi bi-list-check me-2 text-primary"></i>Total de Preguntas a Asignar:
                                </label>
                                <input type="number" class="form-control shadow-sm" id="total_preguntas" name="total_preguntas" value="" required min="1" max="<?= htmlspecialchars($total_preguntas) ?>" step="1">
                                <div class="invalid-feedback">Por favor, ingrese un número válido de preguntas (no mayor a <?= htmlspecialchars($total_preguntas) ?>).</div>
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
