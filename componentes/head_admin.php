<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= $titulo ?? 'Panel de Administración' ?></title>

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
  <!-- Google Font: Inter -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
 -->
<!-- jQuery + DataTables -->
<!-- <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script> -->
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
<!-- <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  -->
<!-- CSS -->
<link rel="stylesheet" href="../assets/css/dataTable.css">
<link rel="stylesheet" href="../assets/css/admin.css">
<link rel="stylesheet" href="../assets/css/btnexamen.css">
  <style>
  
      
  </style>
</head>

<body>
<nav class="navbar navbar-expand-lg sticky-top">
  <div class="container-fluid">
    <button class="btn d-lg-none" id="sidebarToggle">
      <i class="bi bi-list fs-4"></i>
    </button>
    <span class="navbar-brand fw-semibold ms-2"><?= $titulo ?? 'Panel de Administración' ?></span>
    <div class="ms-auto d-flex align-items-center gap-2">
      <span class="text-white fw-semibold">Admin</span>
      <a href="../login/logout.php" class="btn btn-outline-light btn-sm">
        <i class="bi bi-box-arrow-right"></i> Cerrar sesión
      </a>
    </div>
  </div>
</nav>

<!-- Layout principal -->
<div class="wrapper">