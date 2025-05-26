<?php 
session_start();


// Verificar si hay sesión activa
if (!isset($_SESSION['estudiante'])) {
    header("Location: index.php");
    exit();
}

// Acceder a los datos del estudiante
$estudiante = $_SESSION['estudiante'];
$nombre = $estudiante['nombre'] . ' ' . $estudiante['apellidos'];
$codigo = $estudiante['usuario'];

// Conexión
require_once '../includes/conexion.php';


// Cabeceras
header('Content-Type: application/json; charset=utf-8');



if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    echo json_encode(['status' => false, 'message' => 'Metodo no permitiso']);
    exit;

}
//$examen_id = (int) $_GET['examen_id'];

if (!isset($_POST['examen_id']) || !ctype_digit($_POST['examen_id'])) {
    echo json_encode(['status' => false, 'message' => 'ID Examen invalido', $_POST['examen_id']]);
    exit;
}
// Validación básica

$examen_id = (int) $_POST['examen_id'];



try {
    $pdo->beginTransaction();

    // Obtener categoría y total de preguntas del examen
    $stmt = $pdo->prepare("SELECT categoria_id, total_preguntas FROM examenes WHERE id = ?");
    $stmt->execute([$examen_id]);
    $examen = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$examen) {
        throw new Exception("Examen no encontrado.");
    }

    $categoria_id = $examen['categoria_id'];
    $total = intval($examen['total_preguntas']);

    // Verificar si ya hay preguntas asignadas
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM examen_preguntas WHERE examen_id = ?");
    $stmt->execute([$examen_id]);
    $ya_asignadas = $stmt->fetchColumn();

    if ($ya_asignadas == 0) {
        // Seleccionar preguntas aleatorias activas de la categoría
        $stmt = $pdo->prepare("
            SELECT p.id 
            FROM preguntas p
            INNER JOIN pregunta_categoria pc ON p.id = pc.pregunta_id
            WHERE pc.categoria_id = ? AND p.activa = 1
            ORDER BY RAND()
            LIMIT ?
        ");
        $stmt->execute([$categoria_id, $total]);
        $preguntas_seleccionadas = $stmt->fetchAll(PDO::FETCH_COLUMN);

        if (count($preguntas_seleccionadas) < $total) {
            throw new Exception("No hay suficientes preguntas disponibles.");
        }

        // Insertar preguntas seleccionadas al examen
        $stmt = $pdo->prepare("INSERT INTO examen_preguntas (examen_id, pregunta_id) VALUES (?, ?)");
        foreach ($preguntas_seleccionadas as $pregunta_id) {
            $stmt->execute([$examen_id, $pregunta_id]);
        }
    }

    // Obtener preguntas y sus opciones
    $stmt = $pdo->prepare("
        SELECT ep.id AS examen_pregunta_id, p.id AS pregunta_id, p.texto, p.tipo, p.tipo_contenido
        FROM examen_preguntas ep
        INNER JOIN preguntas p ON ep.pregunta_id = p.id
        WHERE ep.examen_id = ?
    ");
    $stmt->execute([$examen_id]);
    $preguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Armar las preguntas con opciones e imagenes
    $preguntas_completas = [];

    foreach ($preguntas as $pregunta) {
        // Obtener opciones
        $stmt = $pdo->prepare("SELECT id, texto FROM opciones_pregunta WHERE pregunta_id = ?");
        $stmt->execute([$pregunta['pregunta_id']]);
        $opciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Obtener imagenes si tiene ilustración
        $imagenes = [];
        if ($pregunta['tipo_contenido'] === 'ilustracion') {
            $stmt = $pdo->prepare("SELECT ruta_imagen, descripcion FROM imagenes_pregunta WHERE pregunta_id = ?");
            $stmt->execute([$pregunta['pregunta_id']]);
            $imagenes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        $preguntas_completas[] = [
            'examen_pregunta_id' => $pregunta['examen_pregunta_id'],
            'pregunta_id'        => $pregunta['pregunta_id'],
            'texto'              => $pregunta['texto'],
            'tipo'               => $pregunta['tipo'],
            'tipo_contenido'     => $pregunta['tipo_contenido'],
            'opciones'           => $opciones,
            'imagenes'           => $imagenes
        ];
    }

    $pdo->commit();

    echo json_encode([
        'status' => true,
        'preguntas' => $preguntas_completas,
        'duracion' => $examen['total_preguntas'] * 40 // segundos por pregunta
    ]);

} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['status' =>false, 'message'=> 'Error: ' . $e->getMessage()]);
}
