<?php include_once('../componentes/head_admin.php'); ?>
<?php include_once('../componentes/menu_admin.php'); ?>

<!-- Contenido principal -->
<div class="main-content">
<div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold"><i class="bi bi-speedometer2 me-2"></i>Panel Principal</h3>
        <span class="text-muted fst-italic">Bienvenido/a, SIR 👋</span>
    </div>

    <div class="row g-4">
        <!-- Total Exámenes -->
        <div class="col-md-6 col-xl-3">
            <div class="card shadow-sm border-0 rounded-4 bg-primary text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-white-50">Exámenes</h6>
                        <h3 class="fw-bold">20</h3>
                    </div>
                    <i class="bi bi-journal-check fs-2 text-white-50"></i>
                </div>
            </div>
        </div>

        <!-- Total Preguntas -->
        <div class="col-md-6 col-xl-3">
            <div class="card shadow-sm border-0 rounded-4 bg-success text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-white-50">Preguntas</h6>
                        <h3 class="fw-bold">10</h3>
                    </div>
                    <i class="bi bi-question-circle-fill fs-2 text-white-50"></i>
                </div>
            </div>
        </div>

        <!-- Total Estudiantes -->
        <div class="col-md-6 col-xl-3">
            <div class="card shadow-sm border-0 rounded-4 bg-warning text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-white-50">Estudiantes</h6>
                        <h3 class="fw-bold">50</h3>
                    </div>
                    <i class="bi bi-person-fill fs-2 text-white-50"></i>
                </div>
            </div>
        </div>

        <!-- Total Resultados -->
        <div class="col-md-6 col-xl-3">
            <div class="card shadow-sm border-0 rounded-4 bg-danger text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-white-50">Resultados</h6>
                        <h3 class="fw-bold">700</h3>
                    </div>
                    <i class="bi bi-bar-chart-line-fill fs-2 text-white-50"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Espacio para gráficas o accesos rápidos -->
    <div class="row mt-5">
        <div class="col-12 text-center text-muted">
            <p class="fst-italic">Puedes navegar por el menú lateral para gestionar exámenes, preguntas y estudiantes.</p>
        </div>
    </div>
</div>

<?php include_once('../componentes/footer.php'); ?>