<?php
require_once '../includes/conexion.php';
header('Content-Type: application/json');

// Función para limpiar texto
function limpiarTexto($texto) {
    return trim(htmlspecialchars($texto, ENT_QUOTES, 'UTF-8'));
}

// Validar campos principales
$erroresValidacion = [];

$codigo_acceso = isset($_POST['codigo_acceso']) ? limpiarTexto($_POST['codigo_acceso']) : '';
$usuario_id = isset($_POST['usuario_id']) ? (int)$_POST['usuario_id'] : 0;
$lista_cruda = $_POST['lista_estudiantes'] ?? null;

// Validaciones básicas
if ($codigo_acceso === '') $erroresValidacion[] = 'Código de acceso vacío.';
if ($usuario_id <= 0) $erroresValidacion[] = 'Usuario inválido.';
if ($lista_cruda === null) $erroresValidacion[] = 'Lista de estudiantes no enviada.';
if (!is_string($lista_cruda)) $erroresValidacion[] = 'Lista de estudiantes no es una cadena JSON.';

 



$lista = json_decode($lista_cruda, true);
if (!is_array($lista)) $erroresValidacion[] = 'No se pudo decodificar la lista de estudiantes.';

if (count($erroresValidacion) > 0) {
    echo json_encode([
        'status' => false,
        'message' => 'Validación fallida.',
        'errores' => $erroresValidacion
    ]);
    exit;
}

// Tiempo asignado por pregunta (segundos)
$tiempo_por_pregunta = 45;
$exitos = 0;
$errores = [];

foreach ($lista as $index => $item) {
    // Validar y sanitizar cada campo
    $estudiante_id = isset($item['estudiante_id']) ? (int)$item['estudiante_id'] : null;
    $categoria_id = isset($item['categoria_id']) ? (int)$item['categoria_id'] : null;
    $total_preguntas = isset($item['total_preguntas']) ? (int)$item['total_preguntas'] : 0;
    $fecha_examen = isset($item['fecha_examen']) ? limpiarTexto($item['fecha_examen']) : null;

    // Validaciones por registro
    if (!$estudiante_id || !$categoria_id || $total_preguntas <= 0 || !$fecha_examen) {
        $errores[] = "Registro #" . ($index + 1) . ": campos incompletos.";
        continue;
    }

    // Validación de formato de fecha
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_examen)) {
        $errores[] = "Registro #" . ($index + 1) . ": fecha inválida ($fecha_examen).";
        continue;
    }

    // Validar si ya existe un examen con esa combinación
    $sql_check = "SELECT COUNT(*) FROM examenes WHERE estudiante_id = ? AND categoria_id = ?";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([$estudiante_id, $categoria_id]);
    $existe = $stmt_check->fetchColumn();

    if ($existe > 0) {
        $errores[] = "Registro #" . ($index + 1) . ": el estudiante ya tiene examen asignado para esa categoría.";
        continue;
    }

    // Calcular duración total
    $duracion = ceil(($total_preguntas * $tiempo_por_pregunta) / 60); // en minutos
    $estado = 'inicio';

    // Insertar en la base de datos
    $sql_insert = "INSERT INTO examenes 
        (estudiante_id, categoria_id, total_preguntas, estado, duracion, codigo_acceso, asignado_por, fecha_asignacion) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt_insert = $pdo->prepare($sql_insert);
    $ok = $stmt_insert->execute([
        $estudiante_id,
        $categoria_id,
        $total_preguntas,
        $estado,
        $duracion,
        $codigo_acceso,
        $usuario_id,
        $fecha_examen
    ]);

    if ($ok) {
        $exitos++;
    } else {
        $errores[] = "Registro #" . ($index + 1) . ": error al guardar examen en la base de datos.";
    }
}

echo json_encode([
    'status' => $exitos > 0,
    'message' => $exitos > 0
        ? "Se guardaron correctamente $exitos examen(es)."
        : "No se pudo guardar ningún examen.",
    'errores' => $errores
]);
