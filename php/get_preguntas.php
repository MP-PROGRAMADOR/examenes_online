<?php
// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// get_pregunta.php
header('Content-Type: application/json');

// Verificar si el usuario está logueado
if (!isset($_SESSION['estudiante'])) {
    // Redirigir si no está logueado
    header('Location: ../aspirantes/index.php');
    exit;
}

// Incluir archivo de conexión
require_once '../config/conexion.php';
$pdo = $pdo->getConexion(); // Asegúrate de que esta función retorne una instancia válida de PDO

// Validar conexión a la base de datos
if (!$pdo) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión a la base de datos.']);
    exit;
}

$estudiante = $_SESSION['estudiante'];
$estudiante_id = $estudiante['id'];


// Validar entrada
$examenEstudianteId = isset($_GET['examen_estudiante_id']) ? intval($_GET['examen_estudiante_id']) : 0;

if ($examenEstudianteId <= 0) {
    echo json_encode(['error' => 'ID de examen inválido']);
    exit;
}

// Obtener examen asignado
$sqlExamen = "SELECT ee.*, 
                e.id AS examen_id, 
                e.preguntas_aleatorias
              FROM examenes ee
              INNER JOIN examenes e ON ee.categoria_carne_id = e.categoria_carne_id
              WHERE ee.id = :id LIMIT 1";


$stmt = $pdo->prepare($sqlExamen);
$stmt->execute([':id' => $examenEstudianteId]);
$examenEstudiante = $stmt->fetch(PDO::FETCH_ASSOC);





 // id de examen...            
$sqlExamen = "SELECT ee.*, 
                e.id AS examen_id, 
                e.preguntas_aleatorias
              FROM examenes_estudiantes ee
              INNER JOIN examenes e ON ee.categoria_carne_id = e.categoria_carne_id
              WHERE ee.id = :id LIMIT 1";








if (!$examenEstudiante) {
    echo json_encode(['error' => 'Examen no encontrado']);
    exit;
}

// Obtener preguntas del examen
$sqlPreguntas = "SELECT p.*
                 FROM preguntas p
                 WHERE p.examen_id = :examen_id
                 ORDER BY " . ($examenEstudiante['preguntas_aleatorias'] ? 'RAND()' : 'p.id') . "
                 LIMIT 1";
$stmt = $pdo->prepare($sqlPreguntas);
$stmt->execute([':examen_id' => $examenEstudiante['examen_id']]);
$pregunta = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pregunta) {
    echo json_encode(['error' => 'No hay preguntas disponibles']);
    exit;
}

// Obtener opciones
$sqlOpciones = "SELECT id, texto_opcion FROM opciones_pregunta WHERE pregunta_id = :id";
$stmt = $pdo->prepare($sqlOpciones);
$stmt->execute([':id' => $pregunta['id']]);
$opciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener ruta de imagen si es ilustración
$rutaImagen = null;
if ($pregunta['tipo_contenido'] === 'ilustracion') {
    $sqlImagen = "SELECT ruta_imagen FROM imagenes_pregunta WHERE pregunta_id = :id LIMIT 1";
    $stmt = $pdo->prepare($sqlImagen);
    $stmt->execute([':id' => $pregunta['id']]);
    $imagen = $stmt->fetch(PDO::FETCH_ASSOC);
    $rutaImagen = $imagen ? $imagen['ruta_imagen'] : null;
}

// Total preguntas para mostrar número
$sqlTotal = "SELECT COUNT(*) FROM preguntas WHERE examen_id = :id";
$stmt = $pdo->prepare($sqlTotal);
$stmt->execute([':id' => $examenEstudiante['examen_id']]);
$totalPreguntas = $stmt->fetchColumn();

echo json_encode([
    'id' => $pregunta['id'],
    'texto_pregunta' => $pregunta['texto_pregunta'],
    'tipo_pregunta' => $pregunta['tipo_pregunta'],
    'tipo_contenido' => $pregunta['tipo_contenido'],
    'ruta_imagen' => $rutaImagen,
    'opciones' => $opciones,
    'pregunta_actual' => 1, // Puedes llevar un contador real en sesión si deseas
    'total_preguntas' => (int)$totalPreguntas,
    'examen_id' => (int)$examenEstudiante['examen_id']
]);
