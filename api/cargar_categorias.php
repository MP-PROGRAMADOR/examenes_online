<?php
header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'cargar_categorias') {
        require '../includes/conexion.php';

        // Verificar si las categorías ya están cargadas
        $consulta = $pdo->prepare("SELECT COUNT(*) FROM categorias");
        $consulta->execute();
        $total = $consulta->fetchColumn();

        if ($total > 0) {
            echo json_encode([
                'status' => true,
                'message' => "Las categorías ya estaban cargadas."
            ]);
            exit;
        }

        // Categorías predeterminadas
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

        $stmt = $pdo->prepare("INSERT INTO categorias (nombre, descripcion, edad_minima) VALUES (?, ?, ?)");

        foreach ($categorias as $cat) {
            $stmt->execute([$cat[0], $cat[1], $cat[2]]);
        }

        echo json_encode([
            'status' => true,
            'message' => " Categorías insertadas correctamente."
        ]);
    } else {
        echo json_encode([
            'status' => false,
            'message' => ' Petición no válida.'
        ]);
    }
} catch (PDOException $e) {
    error_log("Error al insertar categorías: " . $e->getMessage());
    echo json_encode([
        'status' => false,
        'message' => " Error al insertar categorías. Intente nuevamente."
    ]);
}
