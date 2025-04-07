<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?? 'Panel de Administración' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --bg-dark: #1e1e2f;
            --bg-dark-alt: #2c2c3e;
            --text-light: #f8f9fa;
            --highlight: rgba(255, 255, 255, 0.1);
            --section-label: #adb5bd;
            --hover-link-bg: rgba(255, 255, 255, 0.1);
            --hover-link-shadow: 0 0 10px rgba(13, 110, 253, 0.3);
            --font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            font-family: var(--font-family);
        }

        body.dark-mode {
            background-color: var(--bg-dark);
            color: var(--text-light);
        }

        body.dark-mode .card,
        body.dark-mode .offcanvas,
        body.dark-mode .navbar,
        body.dark-mode .table,
        body.dark-mode .form-control {
            background-color: var(--bg-dark-alt);
            color: var(--text-light);
            border-color: #444;
        }

        .nav-link {
            transition: all 0.3s;
            border-radius: 0.375rem;
        }

        .nav-link:hover {
            background-color: var(--hover-link-bg);
            box-shadow: var(--hover-link-shadow);
            color: var(--primary-color);
        }

        .nav-link.active {
            background-color: var(--highlight);
            border-left: 4px solid var(--primary-color);
        }

        .offcanvas-body h5 {
            color: var(--primary-color);
        }

        .btn-outline-light {
            border-color: var(--primary-color);
            color: var(--text-light);
        }

        .btn-outline-light:hover {
            background-color: var(--primary-color);
            color: white;
        }

        .navbar {
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            color: white !important;
        }

        .navbar .navbar-brand,
        .navbar .btn,
        .navbar span {
            color: white !important;
        }

        .navbar .btn-outline-danger {
            border-color: #ffc107;
            color: white;
        }

        .navbar .btn-outline-danger:hover {
            background-color: #ffc107;
            color: black;
        }

        .nav-item.mt-3.text-uppercase.text-muted.small.px-3 {
            color: var(--section-label) !important;
        }

        .collapse .nav-link {
            background-color: rgba(255, 255, 255, 0.03);
        }

        .collapse .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .collapse .nav-link.active {
            background-color: rgba(255, 255, 255, 0.15);
            border-left: 4px solid var(--primary-color);
        }

        body[data-rol="admin"] .navbar {
            background: linear-gradient(90deg, #6610f2, var(--secondary-color));
        }

        body[data-rol="editor"] .navbar {
            background: linear-gradient(90deg, #20c997, var(--secondary-color));
        }

        body[data-rol="viewer"] .navbar {
            background: linear-gradient(90deg, #fd7e14, var(--secondary-color));
        }
    </style>
    <!-- BOOSTRAP ICON CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body class="d-flex vh-100 overflow-hidden dark-mode">

   