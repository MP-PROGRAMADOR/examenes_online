<?php
include_once("includes/header.php");
require '../includes/conexion.php';

// -------------------------------
// Verificar sesión activa
// -------------------------------
session_start();
if (!isset($_SESSION['estudiante'])) {
    header("Location: login.php");
    exit;
}
$estudiante = $_SESSION['estudiante'];
$estudiante_id = $estudiante['id'];

// -------------------------------
// Consulta de exámenes finalizados
// -------------------------------
$sql = " 
SELECT e.*, 
       est.nombre AS estudiante_nombre, est.apellidos,
       c.nombre AS categoria_nombre,
       esc.nombre AS escuela_nombre
FROM examenes e
JOIN estudiantes est ON e.estudiante_id = est.id
JOIN categorias c ON e.categoria_id = c.id
LEFT JOIN escuelas_conduccion esc ON est.escuela_id = esc.id
WHERE e.estado = 'finalizado' AND e.estudiante_id = :estudiante_id
ORDER BY e.fecha_asignacion DESC";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':estudiante_id', $estudiante_id, PDO::PARAM_INT);
$stmt->execute();
$examenes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// -------------------------------
// Función para obtener preguntas por examen
// -------------------------------
function obtenerPreguntasExamen($examen_id, $pdo)
{
    $sql = "SELECT pregunta_id, respondida FROM examen_preguntas WHERE examen_id = :examen_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':examen_id', $examen_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>



<!-- Contenido Principal -->
<main class="container p-4">

    <div class="container-fluid mt-5 pt-4">
        <h2 class="text-primary fw-bold mb-4"><i class="bi bi-speedometer2"></i> Panel del Estudiante</h2>
        <div class="row"></div>
        <!-- Resumen de exámenes -->
        <div class="row g-4 mb-5">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-journal-text text-primary"></i> Exámenes asignados</h5>
                        <p class="display-6 fw-bold text-primary">3</p>
                        <small class="text-muted">En espera de completar</small>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-check-circle-fill text-success"></i> Exámenes aprobados
                        </h5>
                        <p class="display-6 fw-bold text-success">2</p>
                        <small class="text-muted">Últimos resultados positivos</small>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-clock-history text-warning"></i> En proceso</h5>
                        <p class="display-6 fw-bold text-warning">1</p>
                        <small class="text-muted">Examen en curso</small>
                    </div>
                </div>
            </div>
        </div>


        <!-- CONTENIDO PRINCIPAL -->
        <div class="row">

            <!-- COLUMNA IZQUIERDA: PERFIL DEL ESTUDIANTE -->
            <div class="col-12 col-md-6 col-lg-6 mb-4">
                <div class="accordion" id="accordionPerfil">
                    <div class="accordion-item border-0 shadow-sm rounded">
                        <h2 class="accordion-header" id="headingPerfil">
                            <button class="accordion-button collapsed bg-white text-dark fw-semibold rounded-top"
                                type="button" data-bs-toggle="collapse" data-bs-target="#collapsePerfil"
                                aria-expanded="false" aria-controls="collapsePerfil">
                                <i class="bi bi-person-circle me-2 text-primary fs-5"></i> Mi Perfil
                            </button>
                        </h2>
                        <div id="collapsePerfil" class="accordion-collapse collapse" aria-labelledby="headingPerfil"
                            data-bs-parent="#accordionPerfil">
                            <div class="accordion-body bg-light rounded-bottom">
                                <h5 class="mb-3 text-secondary">
                                    <i class="bi bi-person-lines-fill me-2 text-info"></i> Detalles del Estudiante
                                </h5>
                                <table class="table table-sm table-hover align-middle mb-0">
                                    <tbody>
                                        <tr>
                                            <th><i class="bi bi-person-fill me-1 text-muted"></i> Nombre completo</th>
                                            <td><?= htmlspecialchars($estudiante['nombre'] . ' ' . $estudiante['apellidos']) ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><i class="bi bi-card-text me-1 text-muted"></i> DNI</th>
                                            <td><?= htmlspecialchars($estudiante['dni']) ?></td>
                                        </tr>
                                        <tr>
                                            <th><i class="bi bi-envelope-at me-1 text-muted"></i> Email</th>
                                            <td><?= htmlspecialchars($estudiante['email']) ?></td>
                                        </tr>
                                        <tr>
                                            <th><i class="bi bi-telephone me-1 text-muted"></i> Teléfono</th>
                                            <td><?= htmlspecialchars($estudiante['telefono']) ?></td>
                                        </tr>
                                        <tr>
                                            <th><i class="bi bi-building me-1 text-muted"></i> Escuela</th>
                                            <td><?= htmlspecialchars($nombreEscuela ?? 'No asignada') ?></td>
                                        </tr>
                                        <tr>
                                            <th><i class="bi bi-geo-alt me-1 text-muted"></i> Dirección</th>
                                            <td><?= htmlspecialchars($estudiante['direccion']) ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- COLUMNA DERECHA: SIDEBAR A MODO DE ACORDEÓN -->
            <div class="col-12 col-md-6 col-lg-6 mb-4">
                <div class="accordion" id="accordionSidebar">

                    <!-- Módulo de Contenido -->
                    <div class="accordion-item border-0 rounded shadow-sm">
                        <h2 class="accordion-header" id="headingContenido">
                            <button class="accordion-button collapsed bg-white text-dark fw-semibold" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapseContenido" aria-expanded="false"
                                aria-controls="collapseContenido">
                                <i class="bi bi-journal-text me-2 text-primary"></i> Gestión de Contenido
                            </button>
                        </h2>
                        <div id="collapseContenido" class="accordion-collapse collapse"
                            aria-labelledby="headingContenido" data-bs-parent="#accordionSidebar">
                            <div class="accordion-body ps-4">
                                <nav class="nav flex-column">
                                    <a href="#"
                                        class="nav-link text-dark d-flex align-items-center mb-1 text-decoration-none">
                                        <i class="bi bi-house-door me-2 text-secondary"></i> Página principal
                                    </a>
                                    <a href="#"
                                        class="nav-link text-dark d-flex align-items-center mb-1 text-decoration-none">
                                        <i class="bi bi-tags me-2 text-secondary"></i> Listado de categorías
                                    </a>
                                    <a href="#"
                                        class="nav-link text-dark d-flex align-items-center mb-1 text-decoration-none">
                                        <i class="bi bi-clock-history me-2 text-warning"></i> Pendientes
                                    </a>
                                    <a href="#"
                                        class="nav-link text-dark d-flex align-items-center mb-1 text-decoration-none">
                                        <i class="bi bi-check2-square me-2 text-success"></i> Completados
                                    </a>
                                    <a href="#"
                                        class="nav-link text-dark d-flex align-items-center mb-1 text-decoration-none">
                                        <i class="bi bi-clipboard-data me-2 text-info"></i> Ver resultados
                                    </a>
                                </nav>

                                <hr class="my-3">

                                <a href="logout.php"
                                    class="btn btn-sm btn-outline-danger d-flex align-items-center fw-bold">
                                    <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión
                                </a>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <div class="row">
            <div class="accordion" id="accordionExamenesRealizados">
                <h2 class="mb-4 text-primary">
                    <i class="bi bi-clipboard-check me-2"></i> Exámenes Realizados
                </h2>

                <?php if (count($examenes) > 0): ?>
                    <div class="accordion" id="accordionExamenes">
                        <?php foreach ($examenes as $index => $examen): ?>
                            <div class="accordion-item mb-3 border-0 shadow-sm rounded">
                                <h2 class="accordion-header" id="heading<?= $index ?>">
                                    <button class="accordion-button collapsed bg-white fw-semibold" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapse<?= $index ?>" aria-expanded="false"
                                        aria-controls="collapse<?= $index ?>">
                                        <i class="bi bi-file-earmark-text me-2 text-primary"></i>
                                        <?= htmlspecialchars($examen['categoria_nombre']) ?> -
                                        <?= htmlspecialchars($examen['fecha_asignacion']) ?> |
                                        Calificación: <strong class="text-success"><?= $examen['calificacion'] ?></strong>
                                    </button>
                                </h2>
                                <div id="collapse<?= $index ?>" class="accordion-collapse collapse"
                                    aria-labelledby="heading<?= $index ?>" data-bs-parent="#accordionExamenes">
                                    <div class="accordion-body bg-light rounded-bottom">
                                        <ul class="list-unstyled mb-3">
                                            <li><i class="bi bi-person-fill me-1 text-muted"></i> <strong>Estudiante:</strong>
                                                <?= htmlspecialchars($examen['estudiante_nombre'] . ' ' . $examen['apellidos']) ?>
                                            </li>
                                            <li><i class="bi bi-building me-1 text-muted"></i> <strong>Escuela:</strong>
                                                <?= htmlspecialchars($examen['escuela_nombre'] ?? 'No asignada') ?>
                                            </li>
                                            <li><i class="bi bi-list-ol me-1 text-muted"></i> <strong>Total de
                                                    preguntas:</strong>
                                                <?= $examen['total_preguntas'] ?>
                                            </li>
                                            <li><i class="bi bi-flag me-1 text-muted"></i> <strong>Estado:</strong>
                                                <?= ucfirst($examen['estado']) ?>
                                            </li>
                                            <li><i class="bi bi-key me-1 text-muted"></i> <strong>Código de acceso:</strong>
                                                <?= htmlspecialchars($examen['codigo_acceso']) ?>
                                            </li>
                                        </ul>

                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th><i class="bi bi-question-circle me-1 text-muted"></i> ID Pregunta
                                                        </th>
                                                        <th><i class="bi bi-check-circle me-1 text-muted"></i> Respondida</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $preguntas = obtenerPreguntasExamen($examen['id'], $pdo);
                                                    foreach ($preguntas as $i => $pregunta):
                                                        ?>
                                                        <tr>
                                                            <td><?= $i + 1 ?></td>
                                                            <td><?= $pregunta['pregunta_id'] ?></td>
                                                            <td>
                                                                <?php if ($pregunta['respondida']): ?>
                                                                    <span class="badge bg-success"><i
                                                                            class="bi bi-check-circle me-1"></i> Sí</span>
                                                                <?php else: ?>
                                                                    <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>
                                                                        No</span>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                    <?php if (empty($preguntas)): ?>
                                                        <tr>
                                                            <td colspan="3" class="text-center text-muted">
                                                                No hay preguntas registradas.
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info d-flex align-items-center" role="alert">
                        <i class="bi bi-info-circle-fill me-2 fs-5"></i>
                        No hay exámenes finalizados registrados.
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>




</main>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>





</html>