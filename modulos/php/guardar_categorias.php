<?php
// sembrar_categorias_carne.php

require '../../config/conexion.php';

 

try {
    $pdo = $pdo->getConexion();

    // Verificar si ya existen categorías para evitar duplicados
    $consulta = $pdo->query("SELECT COUNT(*) FROM categorias_carne");
    $total = $consulta->fetchColumn();

    if ($total > 0) {
        echo "⚠️ Ya existen categorías registradas. No se insertaron duplicados.";
        exit;
    }

    $categorias = [
        ['A', 'Motocicletas con o sin sidecar'],
        ['A1', 'Motocicletas ligeras hasta 125cc y 11kW'],
        ['A2', 'Motocicletas de potencia media hasta 35 kW'],
        ['B', 'Vehículos hasta 3.500 kg y 8 pasajeros'],
        ['B+E', 'Vehículos B con remolque mayor a 750 kg'],
        ['C', 'Vehículos pesados de más de 3.500 kg'],
        ['C1', 'Camiones entre 3.500 y 7.500 kg'],
        ['C+E', 'Camiones con remolque mayor a 750 kg'],
        ['D', 'Autobuses de más de 8 pasajeros'],
        ['D1', 'Autobuses pequeños hasta 16 pasajeros'],
        ['D+E', 'Autobuses con remolque mayor a 750 kg'],
        ['AM', 'Ciclomotores hasta 50cc y 45 km/h'],
        ['T', 'Vehículos agrícolas como tractores'],
    ];

    $stmt = $pdo->prepare("INSERT INTO categorias_carne (nombre, descripcion) VALUES (?, ?)");

    foreach ($categorias as $cat) {
        $stmt->execute([$cat[0], $cat[1]]);
    }

    echo "✅ Categorías insertadas correctamente.";
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>