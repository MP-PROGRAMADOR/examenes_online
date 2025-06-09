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

try {
    // Preparar totales
    $totales = [
        'totalEstudiantes' => "SELECT COUNT(*) FROM estudiantes",
        'totalCategorias'  => "SELECT COUNT(*) FROM categorias",
        'totalEscuelas'    => "SELECT COUNT(*) FROM escuelas_conduccion",
        'totalExamenes'    => "SELECT COUNT(*) FROM examenes",
        'totalPreguntas'   => "SELECT COUNT(*) FROM preguntas",
        'totalCorreos'     => "SELECT COUNT(*) FROM correos_enviados",
        'totalUsuarios'    => "SELECT COUNT(*) FROM usuarios"
    ];

    foreach ($totales as $key => $sql) {
        $stmt = $pdo->query($sql);
        $$key = $stmt->fetchColumn() ?: 0;
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
        ['title' => 'Estudiantes',       'value' => $totalEstudiantes, 'icon' => 'bi-people-fill',      'color' => 'primary'],
        ['title' => 'Categorías',        'value' => $totalCategorias,  'icon' => 'bi-layers-fill',      'color' => 'success'],
        ['title' => 'Escuelas',          'value' => $totalEscuelas,    'icon' => 'bi-building',         'color' => 'info'],
        ['title' => 'Exámenes',          'value' => $totalExamenes,    'icon' => 'bi-journal-check',    'color' => 'warning'],
        ['title' => 'Preguntas',         'value' => $totalPreguntas,   'icon' => 'bi-patch-question',   'color' => 'danger'],
        ['title' => 'Correos Enviados',  'value' => $totalCorreos,     'icon' => 'bi-envelope-paper',   'color' => 'secondary'],
        ['title' => 'Usuarios',          'value' => $totalUsuarios,    'icon' => 'bi-person-badge',     'color' => 'dark'],
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

  <div class="row mt-5">
  <div class="col-md-4 mx-auto">
    <div class="card shadow-sm">
      <div class="card-body">
        <h5 class="card-title text-center mb-4">
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
  const ctx = document.getElementById('resumenChart').getContext('2d');
  console.log(`Ancho: ${window.innerWidth}px, Alto: ${window.innerHeight}px`);

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
   
});

window.addEventListener('resize', () => {
  console.log(`Ancho: ${window.innerWidth}px, Alto: ${window.innerHeight}px`);
});

 
</script>

<?php include_once('../includes/footer.php'); ?>
