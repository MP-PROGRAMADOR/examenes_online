<?php
declare(strict_types=1);
session_start();
require_once '../config/conexion.php'; // Asegúrate de que esta ruta sea correcta

header('Content-Type: application/json');

try {
    // Validar sesión activa del estudiante
    if (!isset($_SESSION['estudiante']['id'])) {
        throw new Exception('Sesión de estudiante no iniciada.');
    }

    $estudiante_id = (int) $_SESSION['estudiante']['id'];

    // Obtener y validar datos JSON
    $datos = json_decode(file_get_contents('php://input'), true);

    if (
        !is_array($datos) ||
        !isset($datos['pregunta_id'], $datos['opciones'], $datos['tipo']) ||
        !is_array($datos['opciones']) ||
        empty($datos['opciones'])
    ) {
        throw new Exception('Datos incompletos o mal formateados.');
    }

    $pregunta_id = (int) $datos['pregunta_id'];
    $opciones = array_map('intval', $datos['opciones']);
    $tipo = trim($datos['tipo']);

    // Conexión PDO
    $pdo = $pdo->getConexion();

    // Validar existencia de la pregunta
    $stmt = $pdo->prepare("SELECT id FROM preguntas WHERE id = :id");
    $stmt->execute([':id' => $pregunta_id]);
    if (!$stmt->fetch()) {
        throw new Exception('La pregunta no existe.');
    }

    // Lógica para tipo Verdadero/Falso
    if ($tipo === 'vf') {
        $respuesta = strtolower($opciones[0] ?? '');
        $respuesta_texto = $respuesta === 'v' ? 'Verdadero' : 'Falso';

        $sql = "SELECT es_correcta FROM opciones_pregunta 
                WHERE pregunta_id = :pregunta_id AND texto_opcion = :texto";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':pregunta_id' => $pregunta_id,
            ':texto' => $respuesta_texto
        ]);

        $es_correcta = ($fila = $stmt->fetch()) ? (int)$fila['es_correcta'] : 0;

        $insert = "INSERT INTO respuestas_estudiante 
                   (pregunta_id, respuesta_texto, es_correcta) 
                   VALUES (:pregunta_id, :respuesta_texto, :es_correcta)";
        $stmt = $pdo->prepare($insert);
        $stmt->execute([
            ':pregunta_id' => $pregunta_id,
            ':respuesta_texto' => $respuesta_texto,
            ':es_correcta' => $es_correcta
        ]);

        echo json_encode(['ok' => true]);
        exit;
    }

    // Obtener intento activo del estudiante
    $stmt = $pdo->prepare("SELECT id FROM examenes_estudiantes 
                           WHERE estudiante_id = :id AND intentos_examen = 1 
                           ORDER BY id DESC LIMIT 1");
    $stmt->execute([':id' => $estudiante_id]);
    $intento = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$intento) {
        throw new Exception('No se encontró un intento activo para este estudiante.');
    }

    $examen_estudiante_id = (int)$intento['id'];

    // Obtener opciones correctas
    $stmt = $pdo->prepare("SELECT id FROM opciones_pregunta 
                           WHERE pregunta_id = :pregunta_id AND es_correcta = 1");
    $stmt->execute([':pregunta_id' => $pregunta_id]);
    $correctas = $stmt->fetchAll(PDO::FETCH_COLUMN);

    sort($correctas);
    sort($opciones);

    $es_correcta = $correctas === $opciones ? 1 : 0;

    // Insertar respuestas seleccionadas
    $insert = "INSERT INTO respuestas_estudiante 
               (examenes_estudiantes_id, pregunta_id, opcion_seleccionada_id, es_correcta) 
               VALUES (:examen_id, :pregunta_id, :opcion_id, :es_correcta)";
    $stmt = $pdo->prepare($insert);

    foreach ($opciones as $opcion_id) {
        $stmt->execute([
            ':examen_id' => $examen_estudiante_id,
            ':pregunta_id' => $pregunta_id,
            ':opcion_id' => $opcion_id,
            ':es_correcta' => $es_correcta
        ]);
    }

    echo json_encode(['ok' => true]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Error en base de datos: ' . $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
