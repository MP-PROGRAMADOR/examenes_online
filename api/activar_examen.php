<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
require_once '../includes/conexion.php';

header('Content-Type: application/json');

$id = $_POST['id'] ?? null;
$estado = $_POST['estado'] ?? null; // Recibe 'INICIO' o 'pendiente'

if (!$id || !in_array($estado, ['INICIO', 'pendiente'])) {
    echo json_encode(['status' => false, 'message' => 'Datos inválidos']);
    exit;
}

try {
    // 1. Obtenemos el estado actual y la FECHA del examen
    $check = $pdo->prepare("SELECT estado, fecha_asignacion FROM examenes WHERE id = ?");
    $check->execute([$id]);
    $examen = $check->fetch();

    if (!$examen) {
        echo json_encode(['status' => false, 'message' => 'Examen no encontrado']);
        exit;
    }

    // 2. VALIDACIÓN DE FECHA (Solo si intentamos activar el examen a 'pendiente')
    if ($estado === 'pendiente') {
        $fechaExamen = $examen['fecha_asignacion'];
        $fechaHoy = date('Y-m-d');

        // Si la fecha del examen es menor a hoy, está vencido
        if ($fechaExamen < $fechaHoy) {
            echo json_encode([
                'status' => false, 
                'message' => 'No puedes activar este examen porque la fecha ya expiró (' . $fechaExamen . ').'
            ]);
            exit;
        }
    }

    // 3. Validación de estado finalizado (tu lógica anterior)
    if ($examen['estado'] === 'finalizado' && $estado === 'INICIO') {
        echo json_encode(['status' => false, 'message' => 'No se puede inactivar un examen ya finalizado.']);
        exit;
    }

    // 4. Proceder con el cambio de estado si pasó todas las pruebas
    $stmt = $pdo->prepare("UPDATE examenes SET estado = ? WHERE id = ?");
    $stmt->execute([$estado, $id]);

    echo json_encode(['status' => true, 'message' => 'Estado actualizado con éxito']);

} catch (PDOException $e) {
    echo json_encode(['status' => false, 'message' => 'Error de base de datos']);
}