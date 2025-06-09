<?php
require_once '../includes/conexion.php';
header('Content-Type: application/json');

// Función para limpiar texto
function limpiarTexto($texto) {
    return trim(htmlspecialchars($texto));
}

// Recibimos JSON desde el frontend
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data || !isset($data['examenes']) || !is_array($data['examenes'])) {
    echo json_encode([
        'status' => false,
        'message' => 'Datos inválidos o no recibidos.'
    ]);
    exit;
}

$examenes = $data['examenes'];
$codigo_acceso = isset($data['codigo_acceso']) ? limpiarTexto($data['codigo_acceso']) : '';
$asignado_por = isset($data['usuario_id']) ? (int) $data['usuario_id'] : null;
$tiempo_por_pregunta = 45; // segundos

if (!$codigo_acceso || !$asignado_por) {
    echo json_encode([
        'status' => false,
        'message' => 'Faltan datos obligatorios: código de acceso o usuario.'
    ]);
    exit;
}

$exitos = 0;
$errores = [];

foreach ($examenes as $index => $item) {
    $estudiante_id = isset($item['estudiante_id']) ? (int) $item['estudiante_id'] : null;
    $categoria_id = isset($item['categoria_id']) ? (int) $item['categoria_id'] : null;
    $total_preguntas = isset($item['total_preguntas']) ? (int) $item['total_preguntas'] : 0;
    $fecha_examen = isset($item['fecha_examen']) ? limpiarTexto($item['fecha_examen']) : null;
    $estado = 'INICIO';

    // Validar cada registro
    if (!$estudiante_id || !$categoria_id || $total_preguntas <= 0 || !$fecha_examen) {
        $errores[] = "Registro #$index inválido o incompleto.";
        continue;
    }

    // Validar formato de fecha YYYY-MM-DD
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_examen)) {
        $errores[] = "Fecha inválida en registro #$index.";
        continue;
    }

    // Verificar si ya existe examen para ese estudiante y categoría
    $sql_check = "SELECT COUNT(*) FROM examenes WHERE estudiante_id = ? AND categoria_id = ?";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([$estudiante_id, $categoria_id]);
    $existe = $stmt_check->fetchColumn();

    if ($existe > 0) {
        $errores[] = "El estudiante ID $estudiante_id ya tiene examen para la categoría ID $categoria_id.";
        continue;
    }

    $duracion = ceil(($total_preguntas * $tiempo_por_pregunta) / 60);

    $sql_insert = "INSERT INTO examenes (estudiante_id, categoria_id, total_preguntas, estado, duracion, codigo_acceso, asignado_por, fecha_asignacion) 
                   VALUES (?, ?, ?, ?, ?, ?, ?,?)";

    $stmt_insert = $pdo->prepare($sql_insert);
    $ok = $stmt_insert->execute([
        $estudiante_id,
        $categoria_id,
        $total_preguntas,
        $estado,
        $duracion,
        $codigo_acceso,
        $asignado_por,
        $fecha_examen
    ]);

    if ($ok) {
        $exitos++;
    } else {
        $errores[] = "Error al guardar examen para estudiante ID $estudiante_id y categoría ID $categoria_id.";
    }
}

echo json_encode([
    'status' => $exitos > 0,
    'message' => $exitos > 0 ? "Se guardaron $exitos examen(es)." : "No se guardó ningún examen.",
    'errores' => $errores
]);
?>
