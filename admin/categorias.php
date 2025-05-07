<?php
session_start();
$alerta = null; // Inicializar la variable de alerta
require '../config/conexion.php';

try {
    $conn = $pdo->getConexion();

    // Verificar si las categorías ya están cargadas
    $consulta = $conn->prepare("SELECT COUNT(*) FROM categorias_carne");
    $consulta->execute();
    $total = $consulta->fetchColumn();

    if ($total > 0) {
        // Si las categorías ya están cargadas, no es necesario insertarlas de nuevo
       /*  $alerta = [
            'tipo' => 'success',
            'mensaje' => "✅ Las categorías ya están cargadas correctamente."
        ]; */
    } else {
        // Si no hay categorías, insertamos las predeterminadas
        $categorias = [
            ['A', 'Motocicletas con o sin sidecar', 18],
            ['A1', 'Motocicletas ligeras hasta 125cc y 11kW', 16],
            ['A2', 'Motocicletas de potencia media hasta 35 kW', 18],
            ['B', 'Vehículos hasta 3.500 kg y 8 pasajeros', 18],
            ['B+E', 'Vehículos B con remolque mayor a 750 kg', 18],
            ['C', 'Vehículos pesados de más de 3.500 kg', 21],
            ['C1', 'Camiones entre 3.500 y 7.500 kg', 18],
            ['C+E', 'Camiones con remolque mayor a 750 kg', 21],
            ['D', 'Autobuses de más de 8 pasajeros', 24],
            ['D1', 'Autobuses pequeños hasta 16 pasajeros', 21],
            ['D+E', 'Autobuses con remolque mayor a 750 kg', 24],
            ['AM', 'Ciclomotores hasta 50cc y 45 km/h', 15],
            ['T', 'Vehículos agrícolas como tractores', 16],
        ];

        // Preparamos la consulta para insertar categorías
        $stmt = $conn->prepare("INSERT INTO categorias_carne (nombre, descripcion, edad_minima) VALUES (?, ?, ?)");

        // Ejecutamos la inserción de cada categoría
        foreach ($categorias as $cat) {
            $stmt->execute([$cat[0], $cat[1], $cat[2]]);
        }

        // Mensaje de éxito después de insertar las categorías
        $alerta = [
            'tipo' => 'success',
            'mensaje' => "✅ Categorías insertadas correctamente."
        ];
    }
} catch (PDOException $e) {
    error_log("Error al insertar categorías: " . $e->getMessage());
    // En caso de error, establecer mensaje de error en la alerta
    $alerta = [
        'tipo' => 'error',
        'mensaje' => "❌ Error al insertar categorías. Intente nuevamente."
    ];
}

try {
    // Consultar todas las categorías
    $sql = "SELECT * FROM categorias_carne";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error en la consulta de categorías: " . $e->getMessage());
    // En caso de error al recuperar categorías, se agrega el mensaje de error
    $alerta = [
        'tipo' => 'error',
        'mensaje' => "❌ Ocurrió un error al recuperar las categorías."
    ];
}

include '../componentes/head_admin.php';
include '../componentes/menu_admin.php';
?>

<div class="main-content">

    <!-- Modal de Alerta -->
    <?php if ($alerta): ?>
        <div class="modal fade show" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="false"
            style="display: block;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header <?php echo $alerta['tipo'] == 'success' ? 'bg-success' : 'bg-danger'; ?>">
                        <h5 class="modal-title text-white" id="alertModalLabel">
                            <?php echo $alerta['tipo'] == 'success' ? '¡Éxito!' : 'Error'; ?>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-center"><?php echo $alerta['mensaje']; ?></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="container-fluid mt-5">
        <div class="card shadow border-0 rounded-4">

            <div
                class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top-4 px-4">
                <h5 class="mb-0"><i class="bi bi-tags-fill me-2"></i>Listado de Categorías</h5>
                <div class="search-box position-relative">
                    <input type="text" class="form-control ps-5" id="customSearch" placeholder="Buscar categoría...">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                </div>
                <div class="mb-0 d-flex justify-content-end align-items-center">
                    <label for="container-length" class="me-2 text-white fw-medium mb-0">Mostrar:</label>
                    <select id="container-length" class="form-select w-auto shadow-sm">
                        <option value="5">5 registros</option>
                        <option value="10" selected>10 registros</option>
                        <option value="15">15 registros</option>
                        <option value="20">20 registros</option>
                        <option value="25">25 registros</option>
                    </select>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="container-table" class="table table-striped table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th><i class="bi bi-hash"></i> ID</th>
                                <th><i class="bi bi-tag-fill"></i> Nombre</th>
                                <th><i class="bi bi-card-text"></i> Descripción</th>
                                <th><i class="bi bi-person"></i> Edad Mínima</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($categorias)): ?>
                                <?php foreach ($categorias as $categoria): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($categoria['id'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($categoria['nombre'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($categoria['descripcion'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($categoria['edad_minima'], ENT_QUOTES, 'UTF-8') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="alert alert-warning text-center">
                                    <i class="bi bi-exclamation-circle-fill me-2"></i>⚠️ No hay categorías registradas
                                    actualmente.
                                </div>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<?php include_once('../componentes/footer.php'); ?>
