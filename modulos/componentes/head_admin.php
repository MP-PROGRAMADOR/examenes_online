<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Plataforma de Exámenes Online</title>
    <link href="../../public/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../public/css/all.min.css" rel="stylesheet">
   

    

    <!-- Incluir DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="../../public/css/jquery.dataTables.css">

    <!-- Incluir DataTables JS -->
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>

    <link rel="stylesheet" href="admin_styles.css">
    <style>
        body {
            background-color: #f4f6f9;
            color: #333;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .sidebar {
            background-color: #2c3e50;
            color: #fff;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            padding-top: 20px;
        }

        .sidebar-logo {
            padding: 15px;
            text-align: center;
            font-size: 1.5em;
            font-weight: bold;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu-item {
            padding: 10px 15px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .sidebar-menu-item:hover {
            background-color: #34495e;
        }

        .sidebar-menu-item a {
            color: #fff;
            text-decoration: none;
            display: block;
        }

        .content {
            margin-left: 250px;
            padding: 30px;
        }

        .top-bar {
            background-color: #fff;
            border-bottom: 1px solid #eee;
            padding: 15px;
            position: fixed;
            top: 0;
            left: 250px;
            right: 0;
            z-index: 100;
        }

        .top-bar .navbar {
            margin: 0;
        }

        .widget {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0.15rem 0.5rem rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .widget-icon {
            font-size: 2em;
            margin-bottom: 10px;
        }

        .widget-value {
            font-size: 1.5em;
            font-weight: bold;
        }

        .widget-title {
            color: #777;
            font-size: 0.9em;
        }

        .btn-primary {
            background-color: #3498db;
            border-color: #3498db;
        }

        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
        }
        /**
        
        */

       
    </style>
</head>