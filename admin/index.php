<?php
session_start();
require_once '../includes/conexion.php';
include_once("../includes/header.php");
include_once("../includes/sidebar.php");

// Verificar si el usuario está autenticado (opcional pero recomendable)
if (!isset($_SESSION['usuario'])) {
  header("Location: ../index.php?mensaje=" . urlencode("Debe iniciar sesión"));
  exit;
}
$balanceCategorias = [];
$estadoExamenes = [
  'pendientes' => 0,
  'en_progreso' => 0,
  'finalizados' => 0,
  'promedio_finalizados' => 0
];

try {
  // Preparar totales
  $totales = [
    'totalEstudiantes' => "SELECT COUNT(*) FROM estudiantes",
    'totalCategorias' => "SELECT COUNT(*) FROM categorias",
    'totalEscuelas' => "SELECT COUNT(*) FROM escuelas_conduccion",
    'totalExamenes' => "SELECT COUNT(*) FROM examenes",
    'totalPreguntas' => "SELECT COUNT(*) FROM preguntas",
    'totalCorreos' => "SELECT COUNT(*) FROM correos_enviados",
    'totalUsuarios' => "SELECT COUNT(*) FROM usuarios"
  ];

  foreach ($totales as $key => $sql) {
    $stmt = $pdo->query($sql);
    $$key = $stmt->fetchColumn() ?: 0;


  }

  


  $sql = "
    SELECT 
      c.nombre AS categoria,
      COUNT(CASE WHEN ec.estado = 'aprobado' THEN 1 END) AS aprobados,
      SUM(ec.estado = 'rechazado') AS reprobados
    FROM estudiante_categorias ec
    JOIN categorias c ON ec.categoria_id = c.id
    GROUP BY c.id
    ORDER BY c.nombre ASC
  ";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $balanceCategorias = $stmt->fetchAll(PDO::FETCH_ASSOC);


   $sql = "
    SELECT 
      estado,
      COUNT(*) AS total,
      AVG(CASE WHEN estado = 'finalizado' THEN calificacion ELSE NULL END) AS promedio
    FROM examenes
    GROUP BY estado
  ";
  $stmt = $pdo->query($sql);
  $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

  foreach ($resultados as $r) {
    $estado = $r['estado'];
    $estadoExamenes[$estado] = intval($r['total']);
    if ($estado === 'finalizado') {
      $estadoExamenes['promedio_finalizados'] = round($r['promedio'] ?? 0, 2);
    }
  }
  
} catch (PDOException $e) {
  error_log("Error al cargar resumen del dashboard: " . $e->getMessage());
  // Asignar valores por defecto seguros
  $totalEstudiantes = $totalCategorias = $totalEscuelas = $totalExamenes = 0;
  $totalPreguntas = $totalCorreos = $totalUsuarios = 0;
}
 

?>

<main class="main-content" id="content">
  <div class="row g-4">
    <?php
    $cards = [
      ['title' => 'Estudiantes', 'value' => $totalEstudiantes, 'icon' => 'bi-people-fill', 'color' => 'primary'],
      ['title' => 'Categorías', 'value' => $totalCategorias, 'icon' => 'bi-layers-fill', 'color' => 'success'],
      ['title' => 'Escuelas', 'value' => $totalEscuelas, 'icon' => 'bi-building', 'color' => 'info'],
      ['title' => 'Exámenes', 'value' => $totalExamenes, 'icon' => 'bi-journal-check', 'color' => 'warning'],
      ['title' => 'Preguntas', 'value' => $totalPreguntas, 'icon' => 'bi-patch-question', 'color' => 'danger'],
      ['title' => 'Correos Enviados', 'value' => $totalCorreos, 'icon' => 'bi-envelope-paper', 'color' => 'secondary'],
      ['title' => 'Usuarios', 'value' => $totalUsuarios, 'icon' => 'bi-person-badge', 'color' => 'dark'],
    ];

    foreach ($cards as $card): ?>
      <div class="col-md-4 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body d-flex align-items-center justify-content-between">
            <div>
              <h6 class="text-muted"><?= htmlspecialchars($card['title']) ?></h6>
              <h3 class="fw-bold mb-0"><?= intval($card['value']) ?></h3>
            </div>
            <div class="ms-3">
              <span class="badge bg-<?= $card['color'] ?> p-3 rounded-circle shadow-sm">
                <i class="bi <?= $card['icon'] ?> fs-4 text-white"></i>
              </span>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <hr>

  <div class="row mt-5 mb-4"">
    <div class=" col-12 col-md-4 col-lg-3">
    <div class="card shadow-sm">
      <div class="card-body">
        <h5 class="card-title mb-3">
          <i class="bi bi-graph-up-arrow me-2 text-warning"></i>Estado Actual de Exámenes
        </h5>
        <canvas id="estadoExamenesChart" height="100"></canvas>
        <div class="text-end mt-2">
          <small class="text-muted">
            Promedio de calificación (finalizados): <strong><?= $estadoExamenes['promedio_finalizados'] ?></strong>
          </small>
        </div>
      </div>
    </div>
  </div>


  <div class="col-12 col-md-4 col-lg-4 mx-auto">
    <div class="card shadow-sm">
      <div class="card-body">
        <h5 class="card-title mb-3"><i class="bi bi-clipboard-data me-2"></i>Balance por Categoría</h5>
        <div class="table-responsive">
          <table class="table table-bordered align-middle text-center">
            <thead class="table-light">
              <tr>
                <th>Categoría</th>
                <th>Aprobados</th>
                <th>Reprobados</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($balanceCategorias as $row): ?>
                <tr>
                  <td><?= htmlspecialchars($row['categoria']) ?></td>
                  <td class="text-success fw-bold"><?= intval($row['aprobados']) ?></td>
                  <td class="text-danger fw-bold"><?= intval($row['reprobados']) ?></td>
                </tr>
              <?php endforeach; ?>
              <?php if (empty($balanceCategorias)): ?>
                <tr>
                  <td colspan="3">No hay datos registrados</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
        <canvas id="categoriaChart" height="100" class="mt-4"></canvas>
      </div>
    </div>
  </div>


  <div class="col-12 col-md-4 col-lg-3 mx-auto">
    <div class="card shadow-sm">
      <div class="card-body">
        <h5 class="card-title text-center ">
          <i class="bi bi-pie-chart-fill me-2 text-primary"></i>Distribución General
        </h5>
        <canvas id="resumenChart" height="100"></canvas>
      </div>
    </div>
  </div>
  </div>

</main>

<script>
  document.addEventListener('DOMContentLoaded', function () {


  });

  window.addEventListener('resize', () => {
    console.log(`Ancho: ${window.innerWidth}px, Alto: ${window.innerHeight}px`);
  });


</script>


<script>
  document.addEventListener('DOMContentLoaded', () => {
    const categoriaCtx = document.getElementById('categoriaChart').getContext('2d');

    const categoriaLabels = <?= json_encode(array_column($balanceCategorias, 'categoria')) ?>;
    const dataAprobados = <?= json_encode(array_map('intval', array_column($balanceCategorias, 'aprobados'))) ?>;
    const dataReprobados = <?= json_encode(array_map('intval', array_column($balanceCategorias, 'reprobados'))) ?>;

    new Chart(categoriaCtx, {
      type: 'bar',
      data: {
        labels: categoriaLabels,
        datasets: [
          {
            label: 'Aprobados',
            backgroundColor: '#198754',
            data: dataAprobados
          },
          {
            label: 'Reprobados',
            backgroundColor: '#dc3545',
            data: dataReprobados
          }
        ]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'top'
          },
          tooltip: {
            mode: 'index',
            intersect: false
          }
        },
        scales: {
          x: {
            stacked: false
          },
          y: {
            beginAtZero: true,
            stepSize: 1
          }
        }
      }
    });


    /* ----------- Balances estadistico de los card ---------------- */

    const ctx = document.getElementById('resumenChart').getContext('2d');

    new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: [
          'Estudiantes',
          'Categorías',
          'Escuelas',
          'Exámenes',
          'Preguntas',
          'Correos',
          'Usuarios'
        ],
        datasets: [{
          data: [
            <?= $totalEstudiantes ?? 0 ?>,
            <?= $totalCategorias ?? 0 ?>,
            <?= $totalEscuelas ?? 0 ?>,
            <?= $totalExamenes ?? 0 ?>,
            <?= $totalPreguntas ?? 0 ?>,
            <?= $totalCorreos ?? 0 ?>,
            <?= $totalUsuarios ?? 0 ?>
          ],
          backgroundColor: [
            '#0d6efd', // primary
            '#198754', // success
            '#0dcaf0', // info
            '#ffc107', // warning
            '#dc3545', // danger
            '#6c757d', // secondary
            '#212529'  // dark
          ],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              boxWidth: 20
            }
          },
          tooltip: {
            callbacks: {
              label: function (context) {
                const label = context.label || '';
                const value = context.formattedValue || '0';
                return `${label}: ${value}`;
              }
            }
          }
        }
      }
    });

/* ---------------tabla de deteccion de examenes --------------- */
 const estadoCtx = document.getElementById('estadoExamenesChart').getContext('2d');

  new Chart(estadoCtx, {
    type: 'bar',
    data: {
      labels: ['Pendientes', 'En Progreso', 'Finalizados'],
      datasets: [{
        label: 'Cantidad de Exámenes',
        data: [
          <?= $estadoExamenes['pendientes'] ?>,
          <?= $estadoExamenes['en_progreso'] ?>,
          <?= $estadoExamenes['finalizados'] ?>
        ],
        backgroundColor: ['#ffc107', '#0dcaf0', '#198754'],
        borderColor: ['#d39e00', '#0bb2d4', '#157347'],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            label: function (context) {
              return ` ${context.label}: ${context.parsed.y}`;
            }
          }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          stepSize: 1
        }
      }
    }
  });

  });
</script>

<?php include_once('../includes/footer.php'); ?>