<?php
require_once '../includes/conexion.php';
header('Content-Type: application/json');

// Función para limpiar texto
function limpiarTexto($texto) {
    return trim(htmlspecialchars($texto));
}

// Validar y sanitizar entradas
$estudiante_id    = isset($_POST['estudiante_id']) ? (int) $_POST['estudiante_id'] : null;
$categoria_id     = isset($_POST['categoria_id']) ? (int) $_POST['categoria_id'] : null;
$total_preguntas  = isset($_POST['total_preguntas']) ? (int) $_POST['total_preguntas'] : 0;
$estado           = isset($_POST['estado']) ? limpiarTexto($_POST['estado']) : 'pendiente';
$codigo_acceso    = isset($_POST['codigo_acceso']) ? limpiarTexto($_POST['codigo_acceso']) : '';
$asignado_por     = isset($_POST['usuario_id']) ? (int) $_POST['usuario_id'] : null;

// Validación básica de campos obligatorios
if (!$estudiante_id || !$categoria_id || !$codigo_acceso || !$asignado_por) {
    echo json_encode([
        'status' => false,
        'message' => 'Todos los campos requeridos deben estar completos.'
    ]);
    exit;
}

// Verificar si ya existe un examen para ese estudiante y categoría
$sql_check = "SELECT COUNT(*) FROM examenes WHERE estudiante_id = ? AND categoria_id = ?";
$stmt_check = $pdo->prepare($sql_check);
$stmt_check->execute([$estudiante_id, $categoria_id]);
$existe = $stmt_check->fetchColumn();

if ($existe > 0) {
    echo json_encode([
        'status' => false,
        'message' => 'Este estudiante ya tiene asignado un examen para esta categoría.'
    ]);
    exit;
}

// Insertar el nuevo examen
$sql_insert = "INSERT INTO examenes (estudiante_id, categoria_id, total_preguntas, estado, codigo_acceso, asignado_por, fecha_asignacion)
               VALUES (?, ?, ?, ?, ?, ?, NOW())";
$stmt_insert = $pdo->prepare($sql_insert);
$ok = $stmt_insert->execute([
    $estudiante_id,
    $categoria_id,
    $total_preguntas,
    $estado,
    $codigo_acceso,
    $asignado_por
]);

echo json_encode([
    'status' => $ok,
    'message' => $ok ? 'Examen guardado exitosamente.' : 'Error al guardar el examen.'
]);
