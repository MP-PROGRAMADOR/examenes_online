

<?php
include '../conexion/conexion.php'; // Conexión a la BD
 
// Obtener los datos del formulario
$tipoPregunta = $_POST['tipoPregunta'];
$textoPregunta = $_POST['textoPregunta'];
$urlGrafico = isset($_POST['urlGrafico']) ? $_POST['urlGrafico'] : null;

// Insertar la pregunta en la tabla 'preguntas'
$sql = "INSERT INTO preguntas (tipo_pregunta, texto_pregunta, url_grafico) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $tipoPregunta, $textoPregunta, $urlGrafico);

if ($stmt->execute()) {
    $preguntaId = $conn->insert_id; // Obtener el ID de la pregunta insertada

    // Insertar las respuestas según el tipo de pregunta
    if ($tipoPregunta === 'opcionMultiple') {
        // ... (código para opciones múltiples) ...
    } elseif ($tipoPregunta === 'verdaderoFalso') {
        // ... (código para verdadero/falso) ...
    } elseif ($tipoPregunta === 'respuestaCorta') {
        // ... (código para respuesta corta) ...
    } elseif ($tipoPregunta === 'ensayo') {
        // ... (código para ensayo) ...
    } elseif ($tipoPregunta === 'grafico') {
        $respuestaCorrecta = $_POST['respuestaGrafico'];
        $sql = "INSERT INTO respuestas_grafico (pregunta_id, respuesta_correcta) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $preguntaId, $respuestaCorrecta);
        $stmt->execute();
    }

    echo json_encode(array('mensaje' => 'Pregunta registrada correctamente'));
} else {
    echo json_encode(array('mensaje' => 'Error al registrar la pregunta: ' . $stmt->error));
}

$stmt->close();
$conn->close();
?>