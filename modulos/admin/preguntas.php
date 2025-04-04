<!-- End Navbar -->
<?php

require '../../config/conexion.php';

$conn = $pdo->getConexion();

$mensaje = isset($_GET['mensaje']) ? $_GET['mensaje'] : '';
$preguntas = [];

try {
    $sql = "
    SELECT 
        p.id,
        e.titulo AS examen,
        p.texto_pregunta,
        p.tipo_pregunta,
        p.tipo_contenido,
        p.fecha_creacion
    FROM preguntas p
    JOIN examenes e ON p.examen_id = e.id
    ORDER BY p.fecha_creacion DESC
";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $preguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error al listar preguntas: " . $e->getMessage());
    $mensaje_error_listado = "Error al cargar la lista de preguntas.";
}

// frontend/listar_preguntas.php (sin CSS puro)
?>









<!DOCTYPE html>
<html lang="es">

<?php


include '../componentes/head_admin.php';



?>

<body>


    <?php


    include '../componentes/menu_admin.php';



    ?>





    <div class="content">
        <div class="top-bar">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNavbar"
                    aria-controls="topNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="topNavbar">
                    <ul class="navbar-nav ml-auto">
                        <!-- <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-bell"></i> <span class="badge bg-danger">3</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="alertsDropdown">
                                <li><a class="dropdown-item" href="#">Nueva inscripción de aspirante</a></li>
                                <li><a class="dropdown-item" href="#">Examen teórico finalizado</a></li>
                            </ul>
                        </li> -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle"></i> Admin User
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="perfil.html">Perfil</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="../login/logout.php">Cerrar Sesión</a></li>
                            </ul>
                        </li>
                    </ul>
                    <!--   <ul class="nav nav-tabs card-header-tabs float-end">
                        <li class="nav-item">


                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                Crear Nuevo
                            </button>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Opción
                                Deshabilitada</a>
                        </li>
                    </ul> -->
                </div>
            </nav>
        </div>



        <!-- Contenedor principal con espaciado -->
        <div class="container-fluid py-5">

            <!-- Título centrado -->
            <div class="row mb-4">
                <div class="col mt-5">
                    <h2 class="text-center mb-0">📋 LISTA DE PREGUNTAS</h2>
                </div>
            </div>

            <!-- Botón de Crear Nueva Pregunta -->
            <div class="row justify-content-end mb-3">
                <div class="col-auto">
                    <a href="registrar_preguntas.php" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Crear Nuevo
                    </a>
                </div>
            </div>


            <!-- Verificación si hay resultados -->
            <?php if (empty($preguntas)): ?>
                <div class="alert alert-warning text-center">⚠️ No hay preguntas registradas actualmente.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>#ID</th>
                                <th>Examen</th>
                                <th>Pregunta</th>
                                <th>Tipo de Pregunta</th>
                                <th>Contenido</th>
                                <th>Fecha de Registro</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($preguntas as $pregunta): ?>
                                <tr>
                                    <td><?= htmlspecialchars($pregunta['id'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($pregunta['examen'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= nl2br(htmlspecialchars($pregunta['texto_pregunta'], ENT_QUOTES, 'UTF-8')) ?></td>
                                    <td class="text-center">
                                        <?php
                                        $tipos = [
                                            'multiple_choice' => 'Opción Múltiple',
                                            'respuesta_unica' => 'Respuesta Única',
                                            'verdadero_falso' => 'Verdadero / Falso'
                                        ];
                                        echo $tipos[$pregunta['tipo_pregunta']] ?? 'Desconocido';
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        $contenidos = [
                                            'texto' => 'Solo Texto',
                                            'imagen' => 'Con Ilustración'
                                        ];
                                        echo $contenidos[$pregunta['tipo_contenido']] ?? 'No definido';
                                        ?>
                                    </td>
                                    <td class="text-center"><?= date('d/m/Y H:i', strtotime($pregunta['fecha_creacion'])) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>


        </div>












        <link rel="stylesheet" type="text/css"
            href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>

        <!-- Scripts optimizados -->
        <script src="../../public/js/bootstrap.bundle.min.js"></script>
        <script src="../../public/js/chart.js"></script>


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>

        <!-- Script para mostrar el modal -->
        <script>
            function confirmarEliminar(id, texto) {
                document.getElementById('texto-pregunta-eliminar').textContent = texto;
                document.getElementById('enlace-eliminar').href = 'eliminar_pregunta.php?id=' + encodeURIComponent(id);
                var modal = new bootstrap.Modal(document.getElementById('confirmarEliminarModal'));
                modal.show();
            }
        </script>








</body>

</html>