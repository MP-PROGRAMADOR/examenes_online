<?php
session_start();
require_once '../config/conexion.php';
$pdo = $pdo->getConexion();

header('Content-Type: application/json');

// Variables de entrada
$examen_id = $_GET['examen_id'] ?? 0;
$estudiante = $_SESSION['estudiante'];
$estudiante_id = $estudiante['id'];

// Verificar si el estudiante ya tiene un intento hoy para este examen
$sql = "SELECT ee.id, ee.estado, ee.fecha_asignacion, ee.fecha_proximo_intento
        FROM examenes_estudiantes ee
        WHERE ee.estudiante_id = :estudiante_id
          AND ee.categoria_carne_id = (
              SELECT categoria_carne_id FROM examenes WHERE id = :examen_id
          )
          AND DATE(ee.fecha_asignacion) = CURDATE()";
$stmt = $pdo->prepare($sql);
$stmt->execute(['estudiante_id' => $estudiante_id, 'examen_id' => $examen_id]);
$intento_diario = $stmt->fetch();

if ($intento_diario) {
    if ($intento_diario['estado'] == 'pendiente' || $intento_diario['estado'] == 'aprobado') {
        // Si el examen está en curso o ya ha sido aprobado, permitimos continuar
        $fecha_proximo_intento = $intento_diario['fecha_proximo_intento'];
    } else if ($intento_diario['estado'] == 'reprobado') {
        // Si el examen fue reprobado, establecer la fecha del próximo intento a 7 días
        $fecha_proximo_intento = date('Y-m-d', strtotime('+7 days', strtotime($intento_diario['fecha_asignacion'])));
        
        // Actualizar la fecha del próximo intento
        $sql_update = "UPDATE examenes_estudiantes 
                       SET fecha_proximo_intento = :fecha_proximo_intento 
                       WHERE id = :intento_id";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->execute(['fecha_proximo_intento' => $fecha_proximo_intento, 'intento_id' => $intento_diario['id']]);
    }
} else {
    // Si no existe un intento para hoy, se crea uno nuevo
    $sql_create = "INSERT INTO intentos_examen (examen_estudiante_id, estudiante_id, fecha_inicio, codigo_acceso_utilizado)
                   VALUES (:estudiante_id, :examen_id, (SELECT categoria_carne_id FROM examenes WHERE id = :examen_id), CURDATE())";
    $stmt_create = $pdo->prepare($sql_create);
    $stmt_create->execute(['estudiante_id' => $estudiante_id, 'examen_id' => $examen_id]);

    // También podemos establecer la fecha del próximo intento si es necesario (por ejemplo, en un examen no aprobado)
    $fecha_proximo_intento = null; // El valor puede cambiar dependiendo de la lógica que desees
}

// Obtener el total de preguntas permitidas desde examenes_estudiantes
$sql = "SELECT total_preguntas 
        FROM examenes_estudiantes 
        WHERE estudiante_id = :estudiante_id 
          AND categoria_carne_id = (
              SELECT categoria_carne_id FROM examenes WHERE id = :examen_id
          )
        ORDER BY creado_en DESC 
        LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute(['estudiante_id' => $estudiante_id, 'examen_id' => $examen_id]);
$examen_estudiante = $stmt->fetch();

if (!$examen_estudiante) {
    echo json_encode(['error' => 'No se ha encontrado asignación del examen al estudiante']);
    exit;
}

$total_preguntas = $examen_estudiante['total_preguntas'];

// Contar respuestas ya registradas por el estudiante para este examen
$sql = "SELECT COUNT(*) 
        FROM respuestas_estudiante re
        JOIN intentos_examen ie ON re.intento_examen_id = ie.id
        JOIN preguntas p ON re.pregunta_id = p.id
        WHERE ie.estudiante_id = :estudiante_id AND p.examen_id = :examen_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['estudiante_id' => $estudiante_id, 'examen_id' => $examen_id]);
$respuestas_registradas = $stmt->fetchColumn();

if ($respuestas_registradas >= $total_preguntas) {
    echo json_encode(['fin' => true]);
    exit;
}

// Obtener preguntas ya respondidas
$sql = "SELECT re.pregunta_id 
        FROM respuestas_estudiante re
        JOIN intentos_examen ie ON re.intento_examen_id = ie.id
        JOIN preguntas p ON re.pregunta_id = p.id
        WHERE ie.estudiante_id = :estudiante_id AND p.examen_id = :examen_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['estudiante_id' => $estudiante_id, 'examen_id' => $examen_id]);
$respondidas = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

// Obtener una nueva pregunta no respondida
if (!empty($respondidas)) {
    $placeholders = implode(',', array_fill(0, count($respondidas), '?'));
    $sql = "SELECT p.id AS pregunta_id, p.texto_pregunta, p.tipo_contenido, p.tipo_pregunta, pi.ruta_imagen
            FROM preguntas p
            LEFT JOIN imagenes_pregunta pi ON p.id = pi.pregunta_id
            WHERE p.examen_id = ?
            AND p.id NOT IN ($placeholders)
            ORDER BY RAND() LIMIT 1";
    $params = array_merge([$examen_id], $respondidas);
} else {
    $sql = "SELECT p.id AS pregunta_id, p.texto_pregunta, p.tipo_contenido, p.tipo_pregunta, pi.ruta_imagen
            FROM preguntas p
            LEFT JOIN imagenes_pregunta pi ON p.id = pi.pregunta_id
            WHERE p.examen_id = ?
            ORDER BY RAND() LIMIT 1";
    $params = [$examen_id];
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$pregunta = $stmt->fetch();

if (!$pregunta) {
    echo json_encode(['fin' => true]);
    exit;
}

// Obtener opciones de la pregunta
$sql = "SELECT * FROM opciones_pregunta WHERE pregunta_id = :pregunta_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['pregunta_id' => $pregunta['pregunta_id']]);
$opciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Devolver respuesta
$response = [
    'pregunta' => [
        'id' => $pregunta['pregunta_id'],
        'texto_pregunta' => $pregunta['texto_pregunta'],
        'tipo_pregunta' => $pregunta['tipo_pregunta'],
        'tipo_contenido' => $pregunta['tipo_contenido'],
        'ruta_imagen' => $pregunta['ruta_imagen'],
        'opciones' => $opciones
    ]
];

echo json_encode($response);
?>
