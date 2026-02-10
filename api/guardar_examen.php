<?php
require_once '../includes/conexion.php';
if(session_status() === PHP_SESSION_NONE) session_start();
header('Content-Type: application/json');

function limpiarTexto($texto) {
    return trim(htmlspecialchars($texto, ENT_QUOTES, 'UTF-8'));
}

$erroresValidacion = [];
$codigo_acceso = isset($_POST['codigo_acceso']) ? limpiarTexto($_POST['codigo_acceso']) : '';
$usuario_id = isset($_POST['usuario_id']) ? (int)$_POST['usuario_id'] : 0;
$lista_cruda = $_POST['lista_estudiantes'] ?? null;

// Validaciones iniciales
if ($codigo_acceso === '') $erroresValidacion[] = 'Código de acceso vacío.';
if ($usuario_id <= 0) $erroresValidacion[] = 'Usuario inválido.';
if (!$lista_cruda) $erroresValidacion[] = 'Lista de estudiantes vacía.';

$lista = json_decode($lista_cruda, true);
if (!is_array($lista)) $erroresValidacion[] = 'Formato de lista inválido.';

if (count($erroresValidacion) > 0) {
    echo json_encode(['status' => false, 'message' => 'Error de validación', 'errores' => $erroresValidacion]);
    exit;
}

$tiempo_por_pregunta = 45; // segundos
$exitos = 0;

try {
    // Iniciamos transacción para que se guarden TODOS o NINGUNO
    $pdo->beginTransaction();

    foreach ($lista as $index => $item) {
        $estudiante_id = (int)$item['estudiante_id'];
        $categoria_id = (int)$item['categoria_id'];
        $total_preguntas = (int)$item['total_preguntas'];
        $fecha_examen = limpiarTexto($item['fecha_examen']);

        if (!$estudiante_id || !$categoria_id || $total_preguntas <= 0 || !$fecha_examen) {
            throw new Exception("Datos incompletos en el registro #" . ($index + 1));
        }

        // Calcular duración
        $duracion = ceil(($total_preguntas * $tiempo_por_pregunta) / 60);
        
        // ESTADO INICIAL: 'INICIO' (Aparecerá como inactivo en la tabla)
        $estado_inicial = 'INICIO';

        $sql_insert = "INSERT INTO examenes 
            (estudiante_id, categoria_id, total_preguntas, estado, duracion, codigo_acceso, asignado_por, fecha_asignacion) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt_insert = $pdo->prepare($sql_insert);
        $stmt_insert->execute([
            $estudiante_id,
            $categoria_id,
            $total_preguntas,
            $estado_inicial,
            $duracion,
            $codigo_acceso,
            $usuario_id,
            $fecha_examen
        ]);
        
        $exitos++;
    }

    $pdo->commit();
    echo json_encode([
        'status' => true, 
        'message' => "Se registraron $exitos exámenes correctamente en estado Inactivo."
    ]);

} catch (Exception $e) {
    // Si algo falla, revertimos todo para no dejar datos a medias
    if ($pdo->inTransaction()) $pdo->rollBack();
    
    echo json_encode([
        'status' => false,
        'message' => 'Error al procesar el registro: ' . $e->getMessage()
    ]);
}