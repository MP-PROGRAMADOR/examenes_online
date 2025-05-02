<?php include_once("includes/header.php") ?>
<!-- Contenido principal -->
<div class="container py-4">

    <!-- Cards estadísticas -->
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card stat-card shadow-sm">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted">Exámenes disponibles</h6>
                        <h3 class="fw-bold text-primary">4</h3>
                    </div>
                    <div class="card-icon text-primary">📝</div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card shadow-sm border-left-success">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted">Completados</h6>
                        <h3 class="fw-bold text-success">9</h3>
                    </div>
                    <div class="card-icon text-success">✅</div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card shadow-sm border-left-warning">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted">Promedio</h6>
                        <h3 class="fw-bold text-warning">83%</h3>
                    </div>
                    <div class="card-icon text-warning">📊</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Últimos exámenes -->
    <div class="card mt-5 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">Últimos exámenes realizados</h5>
        </div>
        <div class="card-body p-0">
            <table class="table mb-0 table-striped">
                <thead>
                    <tr>
                        <th>Examen</th>
                        <th>Fecha</th>
                        <th>Calificación</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Reglamento básico</td>
                        <td>01/05/2025</td>
                        <td>85%</td>
                        <td><span class="badge bg-success">Aprobado</span></td>
                    </tr>
                    <tr>
                        <td>Señales de tránsito</td>
                        <td>25/04/2025</td>
                        <td>70%</td>
                        <td><span class="badge bg-warning text-dark">Aprobado</span></td>
                    </tr>
                    <tr>
                        <td>Teoría vial</td>
                        <td>18/04/2025</td>
                        <td>55%</td>
                        <td><span class="badge bg-danger">Reprobado</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Acceso directo a simulación -->
    <div class="text-end mt-4">
        <a href="politicas.php" class="btn btn-primary btn-lg">
            🚀 Comenzar simulación de examen
        </a>
    </div>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
