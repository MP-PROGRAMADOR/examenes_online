<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= $titulo ?? 'Panel de Administración' ?></title>

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />

  <style>
    * {
      box-sizing: border-box;
    }

    body, html {
      margin: 0;
      padding: 0;
      height: 100%;
      font-family: 'Segoe UI', sans-serif;
      background-color: #f5f6f8;
    }

    .navbar {
      background-color: #0d6efd;
      color: #fff;
      z-index: 1030;
    }

    .navbar .navbar-brand,
    .navbar .btn,
    .navbar span {
      color: #fff !important;
    }

    .wrapper {
      display: flex;
      height: 100vh;
      overflow: hidden;
    }

    .sidebar {
      width: 250px;
      background-color: #ffffff;
      border-right: 1px solid #dee2e6;
      overflow-y: auto;
      transition: all 0.3s ease;
      box-shadow: 2px 0 8px rgba(0, 0, 0, 0.05);
    }

    .sidebar h5 {
      padding: 1rem 1.5rem;
      margin-bottom: 0;
      color: #0d6efd;
    }

    .sidebar .nav-link {
      color: #495057;
      padding: 0.75rem 1.5rem;
      font-weight: 500;
      transition: all 0.2s ease;
      display: flex;
      align-items: center;
      border-radius: 0.375rem;
      margin: 0.25rem 1rem;
    }

    .sidebar .nav-link i {
      margin-right: 0.5rem;
    }

    .sidebar .nav-link:hover {
      background-color: #f1f3f5;
      color: #0d6efd;
    }

    .sidebar .nav-link.active {
      background-color: #e7f1ff;
      color: #0b5ed7;
      font-weight: bold;
    }

    .sidebar .submenu .nav-link {
      padding-left: 2.5rem;
      font-size: 0.95rem;
    }

    .main-content {
      flex-grow: 1;
      overflow-y: auto;
      padding: 1.5rem;
    }

    @media (max-width: 768px) {
      .sidebar {
        position: fixed;
        top: 56px;
        left: -250px;
        height: calc(100% - 56px);
        z-index: 1020;
      }

      .sidebar.show {
        left: 0;
      }

      .main-content {
        padding: 1rem;
      }
    }

    .card h3 {
      font-weight: 600;
    }

    /* Scroll personalizado */
    .main-content::-webkit-scrollbar {
      width: 8px;
    }

    .main-content::-webkit-scrollbar-thumb {
      background-color: #adb5bd;
      border-radius: 4px;
    }

    .main-content::-webkit-scrollbar-track {
      background-color: transparent;
    }
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