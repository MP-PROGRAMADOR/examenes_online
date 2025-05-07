<?php

// Suponiendo que ya tienes $estudiante_id y $examen_id
$stmt = $pdo->prepare("SELECT COUNT(*) AS total_intentos FROM intentos_examen 
    WHERE estudiante_id = ? AND examen_id = ?");
$stmt->execute([$estudiante_id, $examen_id]);
$data = $stmt->fetch();

if ($data['total_intentos'] >= 1) {
    // Consultar fecha de próximo intento
    $stmt = $pdo->prepare("SELECT fecha_proximo_intento FROM examenes_estudiantes 
        WHERE estudiante_id = ? AND categoria_carne_id = ?");
    $stmt->execute([$estudiante_id, $categoria_carne_id]);
    $fila = $stmt->fetch();

    echo '<div class="alert alert-info">
        Ya has realizado un intento. Podrás volver a intentarlo a partir del 
        <strong>' . htmlspecialchars($fila['fecha_proximo_intento']) . '</strong>.
    </div>';
    exit; // Evita que acceda
}
?>










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
        exit;