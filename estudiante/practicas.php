<?php 
include_once('includes/header.php');
?>

<main class="container my-5 flex-grow-1">
    <section class="text-center mb-5">
        <h1 class="display-5 text-primary">
            <i class="fas fa-check-circle me-2"></i> Accede al Examen Oficial
        </h1>
        <p class="lead text-muted">
            Selecciona la modalidad que deseas realizar. El <strong>Examen Teórico</strong> te preparará para obtener tu licencia.
        </p>
    </section>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title text-success">
                        <i class="fas fa-book-open me-2"></i> Examen Teórico Oficial
                    </h5>
                    <p class="text-muted">Conoce las normas de tráfico y pon a prueba tus conocimientos en un entorno realista.</p>
                    <ul class="list-unstyled mb-4">
                        <li><i class="fas fa-gavel me-2 text-secondary"></i> Legislación de tráfico</li>
                        <li><i class="fas fa-road me-2 text-secondary"></i> Señales y normas viales</li>
                        <li><i class="fas fa-clock me-2 text-secondary"></i> Simulación con tiempo real</li>
                    </ul>
                    <a href="./seleccionar_examen.php" class="btn btn-success w-100">
                        <i class="fas fa-play me-2"></i> Comenzar Examen
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title text-info">
                        <i class="fas fa-clipboard-check me-2"></i> Simulacro de Examen Completo
                    </h5>
                    <p class="text-muted">Entrena con un simulacro general para evaluar tus conocimientos antes del examen real.</p>
                    <ul class="list-unstyled mb-4">
                        <li><i class="fas fa-balance-scale me-2 text-secondary"></i> Evaluación por áreas</li>
                        <li><i class="fas fa-percentage me-2 text-secondary"></i> Resultados y rendimiento</li>
                        <li><i class="fas fa-chart-line me-2 text-secondary"></i> Seguimiento de progreso</li>
                    </ul>
                    <a href="./politicas.php" class="btn btn-success w-100">
                        <i class="fas fa-flag-checkered me-2"></i> Iniciar Simulacro
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>
