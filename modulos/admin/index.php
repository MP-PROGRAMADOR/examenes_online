<?php

include_once("../componentes/head_admin.php");
include_once("../componentes/menu_admin.php");
?>


<!-- Main -->
<div class="flex-grow-1 d-flex flex-column overflow-hidden">
  <nav class="navbar navbar-expand-lg border-bottom shadow-sm sticky-top">
    <div class="container-fluid">
      <button class="btn btn-outline-light d-lg-none" type="button" data-bs-toggle="offcanvas"
        data-bs-target="#sidebar">
        <i class="bi bi-list"></i>
      </button>
      <span class="navbar-brand fw-bold"><?= $titulo ?? 'Panel de Administración' ?></span>
      <div class="ms-auto d-flex gap-2 align-items-center">
        <span class="fw-semibold">Admin</span>
        <a href="#" class="btn btn-outline-danger btn-sm"><i class="bi bi-box-arrow-right"></i> Cerrar
          sesión</a>
      </div>
    </div>
  </nav>



  <!-- Contenido principal -->
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">Resumen</h4>
      <button id="collapseSidebar" class="btn btn-outline-secondary d-none d-lg-inline"><i
          class="bi bi-layout-sidebar-inset"></i></button>
    </div>

    <div class="row g-3 mb-4">
      <div class="col-md-4">
        <div class="card shadow-sm border-start border-primary border-4">
          <div class="card-body">
            <h6 class="card-title">Total de Exámenes</h6>
            <h3><i class="bi bi-journal-text me-2"></i>245</h3>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card shadow-sm border-start border-success border-4">
          <div class="card-body">
            <h6 class="card-title">Estudiantes Activos</h6>
            <h3><i class="bi bi-people me-2"></i>132</h3>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card shadow-sm border-start border-warning border-4">
          <div class="card-body">
            <h6 class="card-title">Escuelas Registradas</h6>
            <h3><i class="bi bi-building me-2"></i>12</h3>
          </div>
        </div>
      </div>
    </div>

    <div class="row g-3">
      <div class="col-lg-6">
        <div class="card shadow-sm">
          <div class="card-header"><i class="bi bi-calendar-event"></i> Calendario</div>
          <div class="card-body">
            <input type="date" class="form-control">
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="card shadow-sm">
          <div class="card-header"><i class="bi bi-table"></i> Últimos Exámenes</div>
          <div class="card-body table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Estudiante</th>
                  <th>Fecha</th>
                  <th>Resultado</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1</td>
                  <td>Juan Pérez</td>
                  <td>2025-04-06</td>
                  <td>Aprobado</td>
                </tr>
                <tr>
                  <td>2</td>
                  <td>María López</td>
                  <td>2025-04-05</td>
                  <td>Reprobado</td>
                </tr>
                <tr>
                  <td>3</td>
                  <td>Carlos Ruiz</td>
                  <td>2025-04-04</td>
                  <td>Aprobado</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>



  </main>


</div>
<?=
  include_once("../componentes/footer.php");
?>