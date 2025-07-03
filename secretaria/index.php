
  <?php
  
 // Suponiendo que $pdo ya está inicializado y es accesible 
 require_once '../includes/conexion.php'; 
 
 

try {
    $totalEstudiantes = $pdo->query("SELECT COUNT(*) FROM estudiantes")->fetchColumn();
    $totalCategorias = $pdo->query("SELECT COUNT(*) FROM categorias")->fetchColumn();
    $totalEscuelas   = $pdo->query("SELECT COUNT(*) FROM escuelas_conduccion")->fetchColumn();
    $totalExamenes   = $pdo->query("SELECT COUNT(*) FROM examenes")->fetchColumn();
    $totalPreguntas  = $pdo->query("SELECT COUNT(*) FROM preguntas")->fetchColumn();
    $totalCorreos    = $pdo->query("SELECT COUNT(*) FROM correos_enviados")->fetchColumn();
    $totalUsuarios   = $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
 


require_once 'header.php';
?>

 <span class="mt-5"></span>
<span class="mt-5"></span>
<main class="main-content" id="content">
  <div class="row g-4">

    <!-- CARD TEMPLATE -->
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

    foreach ($cards as $card) {
        echo '
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted">'. $card['title'] .'</h6>
                        <h3 class="fw-bold mb-0">'. $card['value'] .'</h3>
                    </div>
                    <div class="ms-3">
                        <span class="badge bg-'. $card['color'] .' p-3 rounded-circle shadow-sm">
                            <i class="bi '. $card['icon'] .' fs-4 text-white"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>';
    }
    ?>
  </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>