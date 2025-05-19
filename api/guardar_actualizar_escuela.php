<?php
require_once '../includes/conexion.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir variables
    $escuela_id = isset($_POST['escuela_id']) ? (int) $_POST['escuela_id'] : null;
    $nombre = trim($_POST['nombre'] ?? '');
    $ciudad = trim($_POST['ciudad'] ?? '');
    $pais = trim($_POST['pais'] ?? 'Guinea Ecuatorial'); // Valor por defecto

   try {
    if (empty($nombre) || empty($ciudad)) {
        throw new Exception("El nombre y la ciudad son obligatorios.");
    }

    if (!$escuela_id) {
        // Insertar nueva escuela
        $stmt = $pdo->prepare("SELECT id FROM escuelas_conduccion WHERE nombre = ? AND ciudad = ?");
        $stmt->execute([$nombre, $ciudad]);

        if ($stmt->rowCount() > 0) {
            throw new Exception("Ya existe una escuela con ese nombre en la ciudad indicada.");
        }

        $stmt = $pdo->prepare("INSERT INTO escuelas_conduccion (nombre, ciudad, pais) VALUES (?, ?, ?)");
        $stmt->execute([$nombre, $ciudad, $pais]);

        echo json_encode(['status' => true, 'message' => 'Escuela registrada correctamente']);
    } else {
        // Actualizar escuela existente
        $stmt = $pdo->prepare("SELECT id FROM escuelas_conduccion WHERE nombre = ? AND ciudad = ? AND id != ?");
        $stmt->execute([$nombre, $ciudad, $escuela_id]);

        if ($stmt->rowCount() > 0) {
            throw new Exception("Ya existe otra escuela con ese nombre en la misma ciudad.");
        }

        $stmt = $pdo->prepare("UPDATE escuelas_conduccion SET nombre = ?, ciudad = ?, pais = ? WHERE id = ?");
        $stmt->execute([$nombre, $ciudad, $pais, $escuela_id]);

        echo json_encode(['status' => true, 'message' => 'Escuela actualizada correctamente']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => false, 'message' => $e->getMessage()]);
}

} else {
    echo json_encode(['status' => false, 'message' => 'Método no permitido']);
}
