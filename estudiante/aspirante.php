<?php
include_once("includes/header.php");
require '../includes/conexion.php';


/* capturar mensaje por mal uso del examen */

if (isset($_GET['motivo'])) {
    $_SESSION['examen_cancelado'] = [
        'motivo' => str_replace('_', ' ', $_GET['motivo']),
        'fecha' => date('Y-m-d H:i:s')
    ];
}




// -------------------------------
// Verificar sesión activa
// -------------------------------

if (!isset($_SESSION['estudiante'])) {
    header("Location: login.php");
    exit;
}
$estudiante = $_SESSION['estudiante'];
$estudiante_id = $estudiante['id'];



$nota_aprobacion = 5.0;

// Consultas usando PDO ($pdo)
$examenesAsignados = $pdo->prepare("SELECT COUNT(*) FROM examenes WHERE estudiante_id = ? AND estado = 'pendiente'");
$examenesAsignados->execute([$estudiante_id]);
$totalAsignados = $examenesAsignados->fetchColumn();

$examenesAprobados = $pdo->prepare("SELECT COUNT(*) FROM examenes WHERE estudiante_id = ? AND estado = 'finalizado' AND calificacion >= ?");
$examenesAprobados->execute([$estudiante_id, $nota_aprobacion]);
$totalAprobados = $examenesAprobados->fetchColumn();

$examenesEnProceso = $pdo->prepare("SELECT COUNT(*) FROM examenes WHERE estudiante_id = ? AND estado = 'en_progreso'");
$examenesEnProceso->execute([$estudiante_id]);
$totalEnProceso = $examenesEnProceso->fetchColumn();

$examenesReprobados = $pdo->prepare("SELECT COUNT(*) FROM examenes WHERE estudiante_id = ? AND estado = 'finalizado' AND calificacion < ?");
$examenesReprobados->execute([$estudiante_id, $nota_aprobacion]);
$totalReprobados = $examenesReprobados->fetchColumn();

$categoriasAsignadas = $pdo->prepare("SELECT COUNT(*) FROM estudiante_categorias WHERE estudiante_id = ?");
$categoriasAsignadas->execute([$estudiante_id]);
$totalCategorias = $categoriasAsignadas->fetchColumn();

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

// obtener examenes asignados con estado pendiente
$stmt = $pdo->prepare("SELECT e.id, c.nombre AS categoria, e.total_preguntas, e.codigo_acceso
                       FROM examenes e
                       JOIN categorias c ON c.id = e.categoria_id
                       WHERE e.estudiante_id = ? AND e.estado = 'pendiente'");
$stmt->execute([$estudiante_id]);
$examenesPendiantes = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>



<!-- Contenido Principal -->
<main class="container p-4">

    <div class="container-fluid mt-5 pt-4">
        <h2 class="text-primary fw-bold mb-4"><i class="bi bi-speedometer2"></i> Panel del Estudiante</h2>
        <div class="row"></div>
        <!-- Resumen de exámenes -->
        <div class="row g-3 mb-5">

            <div class="col-md-6 col-lg-3">
                <div class="card shadow-sm border-0 card-compact h-100">
                    <div class="card-body">
                        <h5 class="card-title text-primary">
                            <i class="bi bi-journal-text"></i> Exámenes asignados
                        </h5>
                        <p class="display-6 fw-bold text-primary"><?= $totalAsignados ?></p>
                        <small class="text-muted">En espera de completar</small>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card shadow-sm border-0 card-compact h-100">
                    <div class="card-body">
                        <h5 class="card-title text-success">
                            <i class="bi bi-check-circle-fill"></i> Exámenes aprobados
                        </h5>
                        <p class="display-6 fw-bold text-success"><?= $totalAprobados ?></p>
                        <small class="text-muted">Últimos resultados positivos</small>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card shadow-sm border-0 card-compact h-100">
                    <div class="card-body">
                        <h5 class="card-title text-warning">
                            <i class="bi bi-clock-history"></i> En proceso
                        </h5>
                        <p class="display-6 fw-bold text-warning"><?= $totalEnProceso ?></p>
                        <small class="text-muted">Examen en curso</small>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card shadow-sm border-0 card-compact h-100">
                    <div class="card-body">
                        <h5 class="card-title text-danger">
                            <i class="bi bi-x-circle-fill"></i> Exámenes reprobados
                        </h5>
                        <p class="display-6 fw-bold text-danger"><?= $totalReprobados ?></p>
                        <small class="text-muted">Resultados negativos</small>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm border-0 h-100" style="cursor:pointer;" data-bs-toggle="modal"
                    data-bs-target="#categoriasModal">
                    <div class="card-body">
                        <h5 class="card-title text-info">
                            <i class="bi bi-award"></i> Categorías asignadas
                        </h5>
                        <p class="display-6 fw-bold text-info" id="totalCategorias"><?= $totalCategorias ?></p>
                        <small class="text-muted">Programas en los que está inscrito</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm border-0 card-compact h-100 justify-content-center alin-item-center">
                    <!-- Botón para iniciar el flujo -->
                    <button id="btnIniciarExamen" class="btn btn-outline-primary btn-lg">
                        <i class="bi bi-play-circle"></i> Iniciar Examen
                    </button>

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
                                    <a href="#" style="cursor:pointer;" data-bs-toggle="modal"
                                        data-bs-target="#categoriasModal"
                                        class="nav-link text-dark d-flex align-items-center mb-1 text-decoration-none">
                                        <i class="bi bi-tags me-2 text-secondary"></i> Listado de categorías
                                    </a>
                                    <a href="#" id=""
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

                                <a href="cerrar_sesion.php"
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


    <!-- MODAL MOSTAR CATEGORIAS  -->
    <!-- Modal Categorías -->
    <div class="modal fade" id="categoriasModal" tabindex="-1" aria-labelledby="categoriasModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="categoriasModalLabel">
                        <i class="bi bi-award-fill"></i> Categorías asignadas
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Cerrar"></button>
                </div>
                <div class="modal-body p-0">
                    <div id="loadingCategorias" class="text-center py-3">
                        <div class="spinner-border text-info" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                    </div>
                    <div id="errorCategorias" class="alert alert-danger m-3 d-none"></div>

                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-info">
                                <tr>
                                    <th><i class="bi bi-card-text"></i> Categoría</th>
                                    <th><i class="bi bi-calendar-event"></i> Fecha de asignación</th>
                                </tr>
                            </thead>
                            <tbody id="listaCategorias">
                                <!-- Se llenará con JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <small class="text-muted me-auto">Total categorías asignadas:
                        <span><?= $totalCategorias ?></span></small>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>


    <!-- mostrar examenes asignados siempre que sean mas de uno -->
    <!-- Modal 1: Selección -->
    <div class="modal fade" id="modalSeleccionExamen" tabindex="-1" aria-labelledby="modalSeleccionExamenLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalSeleccionExamenLabel"><i class="bi bi-journal-text"></i>
                        Seleccionar Examen</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="loadingExamenes" class="text-center py-3">
                        <div class="spinner-border text-primary" role="status"><span
                                class="visually-hidden">Cargando...</span></div>
                    </div>
                    <div id="errorExamenes" class="alert alert-danger d-none"></div>
                    <div id="listaExamenes" class="list-group">
                        <!-- JS: exámenes disponibles -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal 2: Instrucciones -->
    <div class="modal fade" id="modalInstrucciones" tabindex="-1" aria-labelledby="modalInstruccionesLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="modalInstruccionesLabel"><i class="bi bi-info-circle"></i> Instrucciones
                        del examen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="detalleExamen">
                        <!-- JS: info del examen -->
                    </div>
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" id="aceptarCondiciones">
                        <label class="form-check-label" for="aceptarCondiciones">
                            Acepto las condiciones de uso del examen.
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="btnComenzarExamen" class="btn btn-success" disabled>
                        <i class="bi bi-check-circle-fill"></i> Comenzar examen
                    </button>
                </div>
            </div>
        </div>
    </div>



    <?php if (isset($_SESSION['examen_cancelado'])): ?>
        <div id="motivoCancelado" data-motivo="<?= htmlspecialchars($_SESSION['examen_cancelado']['motivo']) ?>"
            data-fecha="<?= htmlspecialchars($_SESSION['examen_cancelado']['fecha']) ?>">
        </div>
        <?php unset($_SESSION['examen_cancelado']); ?>
    <?php endif; ?>

    <!-- modal de alerta mensaje por motivo de mal uso de las instrucciones en el examen -->
   <div class="modal fade" id="modalSalir" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title"><i class="bi bi-exclamation-triangle"></i> Salir del Examen</h5>
      </div>
      <div class="modal-body">
        <!-- Se reemplaza dinámicamente -->
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>



</main>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>


    const estudianteId = <?= (int) $estudiante_id ?>;
    const modalCategorias = document.getElementById('categoriasModal');
    const listaCategorias = document.getElementById('listaCategorias');
    const loadingCategorias = document.getElementById('loadingCategorias');
    const errorCategorias = document.getElementById('errorCategorias');

    modalCategorias.addEventListener('show.bs.modal', () => {
        listaCategorias.innerHTML = '';
        errorCategorias.classList.add('d-none');
        loadingCategorias.style.display = 'block';

        fetch(`../api/obtener_categorias_estudiante.php?estudiante_id=${estudianteId}`)
            .then(response => response.json())
            .then(data => {
                loadingCategorias.style.display = 'none';

                if (!data.status) {
                    errorCategorias.textContent = data.message || 'Error desconocido al cargar categorías.';
                    errorCategorias.classList.remove('d-none');
                    return;
                }

                if (!data.data || data.data.length === 0) {
                    listaCategorias.innerHTML = `<tr><td colspan="2" class="text-center text-muted">No hay categorías asignadas.</td></tr>`;
                } else {
                    data.data.forEach(cat => {
                        const fecha = new Date(cat.fecha_asignacion);
                        const fechaFormateada = fecha.toLocaleDateString(undefined, {
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric'
                        });

                        const tr = document.createElement('tr');
                        tr.innerHTML = `
              <td><i class="bi bi-tag-fill text-info me-2"></i>${cat.categoria}</td>
              <td><i class="bi bi-calendar3 text-secondary me-2"></i>${fechaFormateada}</td>
            `;
                        listaCategorias.appendChild(tr);
                    });
                }
            })
            .catch(() => {
                loadingCategorias.style.display = 'none';
                errorCategorias.textContent = 'Error cargando categorías.';
                errorCategorias.classList.remove('d-none');
            });
    });

    /*  seccion de condiciones de evaluacion */

    const btnIniciarExamen = document.getElementById('btnIniciarExamen');
    const modalSeleccion = new bootstrap.Modal(document.getElementById('modalSeleccionExamen'));
    const modalInstrucciones = new bootstrap.Modal(document.getElementById('modalInstrucciones'));
    const listaExamenes = document.getElementById('listaExamenes');
    const loadingExamenes = document.getElementById('loadingExamenes');
    const errorExamenes = document.getElementById('errorExamenes');
    const detalleExamen = document.getElementById('detalleExamen');
    const aceptarCondiciones = document.getElementById('aceptarCondiciones');
    const btnComenzar = document.getElementById('btnComenzarExamen');

    let examenSeleccionado = null;

    btnIniciarExamen.addEventListener('click', () => {
        listaExamenes.innerHTML = '';
        errorExamenes.classList.add('d-none');
        loadingExamenes.style.display = 'block';
        modalSeleccion.show();

        fetch(`../api/obtener_examen_pendiente.php?estudiante_id=<?= (int) $estudiante_id ?>`)
            .then(res => res.json())
            .then(data => {
                loadingExamenes.style.display = 'none';
                if (!data.status || data.data.length === 0) {
                    errorExamenes.textContent = data.message || 'No hay exámenes disponibles.';
                    errorExamenes.classList.remove('d-none');
                    return;
                }

                // Mostrar lista de exámenes
                data.data.forEach(examen => {
                    const item = document.createElement('button');
                    item.className = 'list-group-item list-group-item-action';
                    item.innerHTML = `<i class="bi bi-journal-code text-primary me-2"></i><strong>${examen.nombre}</strong> - ${examen.descripcion}`;
                    item.addEventListener('click', () => {
                        examenSeleccionado = examen;
                        mostrarInstrucciones(examen);
                    });
                    listaExamenes.appendChild(item);
                });
            })
            .catch(() => {
                loadingExamenes.style.display = 'none';
                errorExamenes.textContent = 'Error al cargar exámenes.';
                errorExamenes.classList.remove('d-none');
            });
    });

    function mostrarInstrucciones(examen) {
        modalSeleccion.hide();
        aceptarCondiciones.checked = false;
        btnComenzar.disabled = true;

        detalleExamen.innerHTML = `
      <p><i class="bi bi-question-circle-fill text-primary"></i> <strong>Categoría de Examen:</strong> ${examen.nombre}</p>
      <p><i class="bi bi-clock-history text-primary"></i> <strong>Duración:</strong> ${examen.duracion} minutos</p>
      <p><i class="bi bi-list-ol text-primary"></i> <strong>Total de preguntas:</strong> ${examen.total_preguntas}</p>
      <div class="alert alert-info">
        <i class="bi bi-shield-exclamation"></i> Durante el examen no podrás recargar la página, abrir otras pestañas ni cambiar de ventana. El examen finalizará automáticamente en caso de irregularidades.
      </div>
    `;
        modalInstrucciones.show();
    }

    aceptarCondiciones.addEventListener('change', () => {
        btnComenzar.disabled = !aceptarCondiciones.checked;
    });

    btnComenzar.addEventListener('click', () => {
        if (!examenSeleccionado) return;
        // Redirigir al examen (puedes cambiar la URL base)
        window.location.href = `evaluacion.php?examen_id=${examenSeleccionado.id}`;
    });

    /* mostrar alerta modal al no cumplir las normativas de examen */

 
document.addEventListener('DOMContentLoaded', () => {
  const motivoDiv = document.getElementById('motivoCancelado');
  if (motivoDiv) {
    const motivo = motivoDiv.dataset.motivo;
    const fecha = motivoDiv.dataset.fecha;

    const modalTitle = document.querySelector('#modalSalir .modal-title');
    const modalBody = document.querySelector('#modalSalir .modal-body');

    modalTitle.innerHTML = `<i class="bi bi-exclamation-triangle"></i> Examen Cancelado`;
    modalBody.innerHTML = `
      <div class="alert alert-warning mb-0">
        <strong>Motivo:</strong> ${motivo}<br>
        <small>Ocurrió el: ${fecha}</small>
      </div>
    `;

    const modal = new bootstrap.Modal(document.getElementById('modalSalir'));
    modal.show();
  }
});
 

</script>



</body>


</html>