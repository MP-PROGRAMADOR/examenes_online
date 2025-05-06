<?php
 
require_once '../config/conexion.php';
$conn = $pdo->getConexion();

// Verificar si se han recibido los parámetros
$examenes_estudiantes_id = $_POST['examenes_estudiantes_id'] ?? null;
$estudiante_id = $_POST['estudiante_id'] ?? null;
$total_preguntas = $_POST['total_preguntas'] ?? null;

if ($examenes_estudiantes_id && $estudiante_id && $total_preguntas) {
    try {
        // Iniciar la transacción para asegurar la integridad de los datos
        $conn->beginTransaction();

        // Actualizar el total de preguntas asignadas al examen del estudiante
        $stmt = $conn->prepare("UPDATE examenes_estudiantes SET total_preguntas = ? WHERE id = ?");
        $stmt->execute([$total_preguntas, $examenes_estudiantes_id]);

        // Verificar si la actualización fue exitosa
        if ($stmt->rowCount() > 0) {
            // Si todo salió bien, confirmamos la transacción
            $conn->commit();
            echo "Total de preguntas asignadas correctamente.";
        } else {
            // Si no hubo cambios, revertimos la transacción
            $conn->rollBack();
            echo "Error al asignar el total de preguntas.";
        }

    } catch (Exception $e) {
        // En caso de error, revertir la transacción
        $conn->rollBack();
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Error: Faltan datos.";
}

?>
 