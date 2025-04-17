<?php
session_start();
require '../config/conexion.php';

$pdo=$pdo->getConexion();

 

// Validaciones
$origen = $_POST['examen_origen'] ?? null;
$destino = $_POST['examen_destino'] ?? null;
$preguntas = $_POST['preguntas_clonar'] ?? [];

if (!$origen || !$destino || empty($preguntas)) {
    $_SESSION['errores'][] = "Debes seleccionar preguntas y un examen destino.";
    header("Location: clonar_preguntas.php?examen_origen=$origen");
    exit;
}

try {
    $pdo->beginTransaction();

    foreach ($preguntas as $id_pregunta) {
        // Obtener pregunta original
        $stmt = $pdo->prepare("SELECT * FROM preguntas WHERE id = ?");
        $stmt->execute([$id_pregunta]);
        $preg = $stmt->fetch(PDO::FETCH_ASSOC);

        // Clonar pregunta
        $stmt = $pdo->prepare("INSERT INTO preguntas (examen_id, texto_pregunta, tipo_pregunta, tipo_contenido)
                               VALUES (?, ?, ?, ?)");
        $stmt->execute([$destino, $preg['texto_pregunta'], $preg['tipo_pregunta'], $preg['tipo_contenido']]);
        $nueva_pregunta_id = $pdo->lastInsertId();

        // Clonar imágenes
        $stmt_img = $pdo->prepare("SELECT * FROM imagenes_pregunta WHERE pregunta_id = ?");
        $stmt_img->execute([$id_pregunta]);
        foreach ($stmt_img as $img) {
            $stmt = $pdo->prepare("INSERT INTO imagenes_pregunta (pregunta_id, ruta_imagen)
                                   VALUES (?, ?)");
            $stmt->execute([$nueva_pregunta_id, $img['ruta_imagen']]);
        }

        // Clonar opciones
        $stmt_op = $pdo->prepare("SELECT * FROM opciones_pregunta WHERE pregunta_id = ?");
        $stmt_op->execute([$id_pregunta]);
        foreach ($stmt_op as $op) {
            $stmt = $pdo->prepare("INSERT INTO opciones_pregunta (pregunta_id, texto_opcion, es_correcta)
                                   VALUES (?, ?, ?)");
            $stmt->execute([$nueva_pregunta_id, $op['texto_opcion'], $op['es_correcta']]);
        }
    }

    $pdo->commit();
    $_SESSION['mensaje'] = "Preguntas clonadas exitosamente.";
} catch (PDOException $e) {
    $pdo->rollBack();
    $_SESSION['errores'][] = "Error al clonar: " . $e->getMessage();
}

header("Location: clonar_preguntas.php?examen_origen=$origen");
exit;
