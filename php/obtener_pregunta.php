 

<?php
session_start();
require_once '../config/conexion.php';

header('Content-Type: application/json');

// Manejo de errores global con try-catch
try {
    // Validación de existencia de variables necesarias
    $examen_id = filter_input(INPUT_GET, 'examen_id', FILTER_VALIDATE_INT); 
    
    $estudiante = $_SESSION['estudiante'];
    $estudiante_id = $estudiante['id'];
    // Verificación de datos obligatorios
    if (!$examen_id || !$estudiante_id) {
        throw new Exception('Datos incompletos. examen_id o id_estudiante ausentes.');
    }

    // Obtener conexión PDO desde clase Conexion
    $pdo = $pdo->getConexion();

    // Buscar intento activo del estudiante para la misma categoría del examen actual
    $sql = "SELECT * FROM examenes_estudiantes 
            WHERE estudiante_id = :estudiante_id 
            AND categoria_carne_id = (
                SELECT categoria_carne_id FROM examenes WHERE id = :examen_id
            )
            AND intentos_examen = 1 
            LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':estudiante_id' => $estudiante_id,
        ':examen_id' => $examen_id
    ]);

    $intento = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$intento) {
        throw new Exception('No tiene intento activo para este examen.');
    }

    $intento_id = (int)$intento['id'];
    $total_permitido = (int)$intento['total_preguntas'];

    // Si no ha comenzado el examen, establecer la fecha de inicio
    if (is_null($intento['fecha_realizacion'])) {
        $update = $pdo->prepare("UPDATE examenes_estudiantes SET fecha_realizacion = NOW() WHERE id = :id");
        $update->execute([':id' => $intento_id]);
    }

    // Contar cuántas preguntas ya ha respondido el estudiante
    $sql = "SELECT COUNT(*) FROM respuestas_estudiante WHERE examenes_estudiantes_id = :intento_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':intento_id' => $intento_id]);
    $cant_respondidas = (int)$stmt->fetchColumn();

    // Verificar si ya alcanzó el límite de preguntas
    if ($cant_respondidas >= $total_permitido) {
        echo json_encode(['finalizado' => true]);
        exit;
    }

    // Seleccionar aleatoriamente una pregunta no respondida aún
    $sql = "SELECT p.*
            FROM preguntas p
            WHERE p.examen_id = :examen_id
            AND p.id NOT IN (
                SELECT pregunta_id FROM respuestas_estudiante 
                WHERE examenes_estudiantes_id = :intento_id
            )
            ORDER BY RAND()
            LIMIT 1";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':examen_id' => $examen_id,
        ':intento_id' => $intento_id
    ]);
    $pregunta = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si ya no hay preguntas disponibles
    if (!$pregunta) {
        echo json_encode(['finalizado' => true]);
        exit;
    }

    // Obtener opciones de respuesta asociadas a la pregunta
    $sql = "SELECT id, texto_opcion FROM opciones_pregunta WHERE pregunta_id = :pregunta_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':pregunta_id' => $pregunta['id']]);
    $opciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Obtener imagen asociada a la pregunta si existe
    $sql = "SELECT ruta_imagen FROM imagenes_pregunta WHERE pregunta_id = :pregunta_id LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':pregunta_id' => $pregunta['id']]);
    $imagen = $stmt->fetchColumn() ?: null;

    // Enviar respuesta al frontend con todos los datos de la pregunta
    echo json_encode([
        'pregunta_id' => $pregunta['id'],
        'texto_pregunta' => $pregunta['texto_pregunta'],
        'tipo_pregunta' => $pregunta['tipo_pregunta'],
        'tipo_contenido' => $pregunta['tipo_contenido'],
        'opciones' => $opciones,
        'ruta_imagen' => $imagen,
        'pregunta_actual' => $cant_respondidas + 1,
        'total_preguntas' => $total_permitido
    ]);

} catch (PDOException $e) {
    // Error con la base de datos
    echo json_encode(['error' => 'Error en base de datos: ' . $e->getMessage()]);
    http_response_code(500);
} catch (Exception $e) {
    // Otro tipo de error
    echo json_encode(['error' => $e->getMessage()]);
    http_response_code(400);
}
