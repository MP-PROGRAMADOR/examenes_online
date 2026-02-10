<?php
require_once '../includes/conexion.php';
header('Content-Type: application/json');

// Obtenemos el examen_id ya sea por POST normal o por Beacon
$examen_id = isset($_POST['examen_id']) ? (int)$_POST['examen_id'] : 0;
$motivo = $_POST['motivo'] ?? 'desconocido';

if ($examen_id > 0) {
    try {
        // 1. Calcular aciertos actuales
        $sql = "SELECT COUNT(*) as aciertos 
                FROM respuestas_estudiante re
                JOIN opciones_pregunta op ON re.opcion_id = op.id
                WHERE re.examen_id = ? AND op.es_correcta = 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$examen_id]);
        $aciertos = $stmt->fetch(PDO::FETCH_ASSOC)['aciertos'] ?? 0;

        // 2. Obtener total preguntas
        $stmtT = $pdo->prepare("SELECT total_preguntas FROM examenes WHERE id = ?");
        $stmtT->execute([$examen_id]);
        $total = $stmtT->fetch(PDO::FETCH_ASSOC)['total_preguntas'] ?? 1;

        $calificacion = ($aciertos / $total) * 100;

        // 3. ACTUALIZAR Y CERRAR
        // Usamos una sentencia que solo actualice si el examen no estaba ya finalizado
        $upd = $pdo->prepare("UPDATE examenes SET estado = 'finalizado', calificacion = ? WHERE id = ? AND estado != 'finalizado'");
        $upd->execute([$calificacion, $examen_id]);

        echo json_encode(['status' => true]);
    } catch (Exception $e) {
        // En Beacon los errores no se ven, pero es bueno tener el catch
        echo json_encode(['status' => false]);
    }
}