
<?php
require '../config/conexion.php';

$conn=$pdo->getConexion();
// Obtener los datos del formulario
$examen_destino = $_POST['examen_destino'];
$preguntas_clonar = $_POST['preguntas_clonar'] ?? [];

if (empty($preguntas_clonar)) {
    die("No se seleccionaron preguntas para clonar.");
}

try {
    // Iniciar una transacción
    $conn->beginTransaction();

    foreach ($preguntas_clonar as $id_pregunta) {
        // Obtener la pregunta original
        $stmt = $conn->prepare("SELECT texto_pregunta, tipo_contenido, tipo_pregunta FROM preguntas WHERE id = ?");
        $stmt->execute([$id_pregunta]);
        $pregunta = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($pregunta) {
            // Insertar la nueva pregunta clonada en el examen destino
            $insert_pregunta = $conn->prepare("INSERT INTO preguntas (examen_id, texto_pregunta, tipo_contenido, tipo_pregunta) VALUES (?, ?, ?, ?)");
            $insert_pregunta->execute([$examen_destino, $pregunta['texto_pregunta'], $pregunta['tipo_contenido'], $pregunta['tipo_pregunta']]);

            // Obtener el ID de la nueva pregunta clonada
            $nueva_pregunta_id = $conn->lastInsertId();

            // Obtener las opciones de la pregunta original
            $opciones = $conn->prepare("SELECT texto_opcion, es_correcta FROM opciones_pregunta WHERE pregunta_id = ?");
            $opciones->execute([$id_pregunta]);

            // Insertar cada opción en la nueva pregunta clonada
            $insert_opcion = $conn->prepare("INSERT INTO opciones_pregunta (pregunta_id, texto_opcion, es_correcta) VALUES (?, ?, ?)");
            while ($opcion = $opciones->fetch(PDO::FETCH_ASSOC)) {
                $insert_opcion->execute([$nueva_pregunta_id, $opcion['texto_opcion'], $opcion['es_correcta']]);
            }
        }
    }

    // Commit de la transacción
    $conn->commit();

    // ✅ Redirigir a la lista de exámenes con mensaje de éxito
    header('Location: ../admin/examenes.php?mensaje=exito');
    exit();

} catch (Exception $e) {
    // En caso de error, se revierte la transacción
    $conn->rollBack();
    die("Error al clonar las preguntas: " . $e->getMessage());
}
?>
