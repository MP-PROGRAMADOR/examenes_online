 // Validar si ya fue respondida
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM respuestas_estudiante WHERE examen_pregunta_id = ?");
    $stmt->execute([$examen_pregunta_id]);
    if ($stmt->fetchColumn() > 0) {
        throw new Exception('La pregunta ya fue respondida');
    }

    // Insertar opciones seleccionadas
    $stmtInsert = $pdo->prepare("INSERT INTO respuestas_estudiante (examen_pregunta_id, opcion_id) VALUES (?, ?)");
    foreach ($opciones as $opcion_id) {
        $stmtInsert->execute([$examen_pregunta_id, $opcion_id]);
    }

    // Marcar como respondida
    $stmt = $pdo->prepare("UPDATE examen_preguntas SET respondida = 1 WHERE id = ?");
    $stmt->execute([$examen_pregunta_id]);

    // Verificar cuántas preguntas han sido respondidas
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM examen_preguntas WHERE examen_id = ? AND respondida = 1");
    $stmt->execute([$examen_id]);
    $respondidas = $stmt->fetchColumn();

    // Obtener total de preguntas del examen
    $stmt = $pdo->prepare("SELECT total_preguntas FROM examenes WHERE id = ?");
    $stmt->execute([$examen_id]);
    $total = $stmt->fetchColumn();

    if ($respondidas == $total) {
        // Calcular la nota
        $stmt = $pdo->prepare("
            SELECT ep.id, p.tipo,
                   GROUP_CONCAT(DISTINCT ro.opcion_id ORDER BY ro.opcion_id) AS seleccionadas,
                   GROUP_CONCAT(DISTINCT o.id ORDER BY o.id) AS correctas
            FROM examen_preguntas ep
            INNER JOIN preguntas p ON ep.pregunta_id = p.id
            LEFT JOIN respuestas_estudiante ro ON ep.id = ro.examen_pregunta_id
            LEFT JOIN opciones_pregunta o ON p.id = o.pregunta_id AND o.es_correcta = 1
            WHERE ep.examen_id = ?
            GROUP BY ep.id
        ");
        $stmt->execute([$examen_id]);
        $aciertos = 0;
        $total_p = 0;

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $total_p++;
            $sel = $row['seleccionadas'] ?? '';
            $cor = $row['correctas'] ?? '';
            if ($sel === $cor) $aciertos++;
        }

        $calificacion = ($aciertos / $total_p) * 100;

        $stmt = $pdo->prepare("UPDATE examenes SET estado = 'finalizado', calificacion = ? WHERE id = ?");
        $stmt->execute([$calificacion, $examen_id]);
    }