<?php

include_once("../includes/header.php");
include_once("../includes/sidebar_examinador.php");
?>
<?php


try {
  $totalEstudiantes = $pdo->query("SELECT COUNT(*) FROM estudiantes")->fetchColumn();
  $totalCategorias = $pdo->query("SELECT COUNT(*) FROM categorias")->fetchColumn();
  $totalEscuelas = $pdo->query("SELECT COUNT(*) FROM escuelas_conduccion")->fetchColumn();
  $totalExamenes = $pdo->query("SELECT COUNT(*) FROM examenes")->fetchColumn();
  $totalPreguntas = $pdo->query("SELECT COUNT(*) FROM preguntas")->fetchColumn();
  $totalCorreos = $pdo->query("SELECT COUNT(*) FROM correos_enviados")->fetchColumn();
  $totalUsuarios = $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
}
?>

<main class="container-fluid">
  <div class="row g-4">

    <!-- CARD TEMPLATE -->
    <?php
    $cards = [
      ['title' => 'Estudiantes', 'value' => $totalEstudiantes, 'icon' => 'bi-people-fill', 'color' => 'primary'],
      ['title' => 'Categorías', 'value' => $totalCategorias, 'icon' => 'bi-layers-fill', 'color' => 'success'],
      ['title' => 'Exámenes', 'value' => $totalExamenes, 'icon' => 'bi-journal-check', 'color' => 'warning'],
    ];

    foreach ($cards as $card) {
      echo '
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted">' . $card['title'] . '</h6>
                        <h3 class="fw-bold mb-0">' . $card['value'] . '</h3>
                    </div>
                    <div class="ms-3">
                        <span class="badge bg-' . $card['color'] . ' p-3 rounded-circle shadow-sm">
                            <i class="bi ' . $card['icon'] . ' fs-4 text-white"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>';
    }
    ?>
  </div>

  <div class="row mt-5 mb-4"">
    <div class=" col-12 col-md-6">
    <div class="card shadow-sm">
      <div class="card-body">
        <h5 class="card-title mb-3">
          <i class="bi bi-graph-up-arrow me-2 text-warning"></i>Estado Actual de Exámenes
        </h5>
        <canvas id="estadoExamenesChart" height="100"></canvas>
        <div class="text-end mt-2">
          <small class="text-muted">
            Promedio de calificación (finalizados):
            <!-- <strong><?= $estadoExamenes['promedio_finalizados'] ?></strong> -->
          </small>
        </div>
      </div>
    </div>
  </div>


  <div class="col-12 col-md-6 ">
    <div class="card shadow-sm">
      <div class="card-body">
        <h5 class="card-title text-center ">
          <i class="bi bi-pie-chart-fill me-2 text-primary"></i>Distribución General
        </h5>
        <canvas id="balanceChart" height="100"></canvas>
      </div>
    </div>
  </div>


  </div>


</main>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<script>
  document.addEventListener('DOMContentLoaded', function () {
    fetch('../api/obtener_balances.php')
      .then(res => res.json())
      .then(data => {
        if (data.status) {
          const labels = data.data.map(c => c.categoria);
          const aprobados = data.data.map(c => c.total_aprobados);
          const porcentaje = data.data.map(c => c.porcentaje_aprobados);

          const ctx = document.getElementById('balanceChart').getContext('2d');
          new Chart(ctx, {
            type: 'bar',
            data: {
              labels: labels,
              datasets: [{
                label: 'Aprobados',
                data: aprobados,
                backgroundColor: '#198754'
              }, {
                label: '% Aprobación',
                data: porcentaje,
                backgroundColor: '#0d6efd',
                type: 'line',
                yAxisID: 'y1'
              }]
            },
            options: {
              responsive: true,
              interaction: {
                mode: 'index',
                intersect: false
              },
              scales: {
                y: {
                  beginAtZero: true,
                  title: {
                    display: true,
                    text: 'Cantidad'
                  }
                },
                y1: {
                  beginAtZero: true,
                  position: 'right',
                  grid: { drawOnChartArea: false },
                  title: {
                    display: true,
                    text: '% Aprobación'
                  }
                }
              },
              plugins: {
                tooltip: {
                  callbacks: {
                    label: function (context) {
                      if (context.dataset.label.includes('%')) {
                        return `${context.dataset.label}: ${context.raw}%`;
                      }
                      return `${context.dataset.label}: ${context.raw}`;
                    }
                  }
                }
              }
            }
          });
        } else {
          console.warn(data.message);
        }
      });
  });
</script>

<?php include_once('../includes/footer.php'); ?>