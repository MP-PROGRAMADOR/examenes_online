<!DOCTYPE html>
<html lang="es">


<?php


include '../componentes/head_admin.php';



?>

<body>

    <?php


    include '../componentes/menu_admin.php';



    ?>

    <div class="content ">
        <div class="top-bar">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNavbar"
                    aria-controls="topNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="topNavbar">
                    <ul class="navbar-nav ml-auto">
                        <!--  <li class="nav-item dropdown">
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
                            <a href="listar.php" class="btn btn-primary " type="button"><i class="fas fa-list me-2"></i> Visualizar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Opción
                                Deshabilitada</a>
                        </li>
                    </ul> -->
                </div>
            </nav>
        </div>
        <div class="container-fluid  mt-5 pt-2">

            <div class="row d-flex justify-content-center align-items-center">
                <div class="card p-5 mt-5 w-50">
                    <div class="form-container">
                        <h2 class="mb-4">Registrar Nuevo Examen</h2>
                        <form action="../php/guardar_examen.php" method="POST" class="needs-validation" novalidate>
                            <div class="mb-3 form-group required">
                                <label for="categoria_carne_id" class="form-label">Categoría de Carné:</label>
                                <select class="form-select" id="categoria_carne_id" name="categoria_carne_id" required>
                                    <option value="">Seleccione una categoría</option>
                                    <?php
                                    // Incluir archivo de conexión (asegúrate de que $conn esté definida aquí)
                                    require_once '../../config/conexion.php';
                                    $conn = $pdo->getConexion();

                                    try {
                                        $sql = "SELECT id, nombre FROM categorias_carne ORDER BY nombre ASC";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->execute();
                                        $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                        foreach ($categorias as $categoria) {
                                            echo '<option value="' . htmlspecialchars($categoria['id']) . '">' . htmlspecialchars($categoria['nombre']) . '</option>';
                                        }
                                    } catch (PDOException $e) {
                                        echo '<option value="" disabled>Error al cargar las categorías</option>';
                                        error_log("Error al obtener categorías: " . $e->getMessage());
                                    } finally {
                                        $stmt = null;
                                        $pdo->closeConexion();
                                    }
                                    ?>
                                </select>
                                <div class="invalid-feedback">Por favor, seleccione una categoría de carné.</div>
                            </div>
                            <div class="mb-3 form-group required">
                                <label for="titulo" class="form-label">Título del Examen:</label>
                                <input type="text" class="form-control" id="titulo" name="titulo"
                                    placeholder="Ej: Examen Teórico General" required>
                                <div class="invalid-feedback">Por favor, ingrese el título del examen.</div>
                            </div>
                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción (Opcional):</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="3"
                                    placeholder="Descripción detallada del examen."></textarea>
                            </div>
                            <div class="mb-3 form-group required">
                                <label for="duracion_minutos" class="form-label">Duración (en minutos):</label>
                                <input type="number" class="form-control" id="duracion_minutos" name="duracion_minutos"
                                    placeholder="Ej: 30" min="1" required>
                                <div class="invalid-feedback">Por favor, ingrese la duración del examen en minutos.
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-2"></i>Guardar
                                    Examen</button>
                                <a href="listar_examenes.php" class="btn btn-secondary"><i
                                        class="bi bi-arrow-left me-2"></i>Cancelar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- Scripts optimizados -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


</body>

</html>