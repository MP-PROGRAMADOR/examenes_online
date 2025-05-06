<?php
require '../config/conexion.php';

$conn = $pdo->getConexion();

// Inicializar variables
$categoria_carne_id = '';
$titulo = '';
$descripcion = ''; 
$errores = [];
$mensaje_exito = '';
$mensaje_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger y sanitizar datos del formulario
    $categoria_carne_id = filter_input(INPUT_POST, 'categoria_carne_id', FILTER_SANITIZE_NUMBER_INT);
    $titulo = trim(strip_tags($_POST['titulo']));
    $descripcion = trim(strip_tags($_POST['descripcion']));
    // Aunque 'duracion_minutos' no existe en la tabla, lo validamos por si lo agregas más adelante
    $duracion_minutos = filter_input(INPUT_POST, 'duracion_minutos', FILTER_SANITIZE_NUMBER_INT);

    // Validaciones
    if (empty($categoria_carne_id) || $categoria_carne_id <= 0) {
        $errores['categoria_carne_id'] = 'Seleccione una categoría válida.';
    }

    if (empty($titulo)) {
        $errores['titulo'] = 'El título es obligatorio.';
    } elseif (strlen($titulo) > 255) {
        $errores['titulo'] = 'El título no puede superar los 255 caracteres.';
    }

    // Si no hay errores, procesar la inserción
    if (empty($errores)) {
        try {
            // Insertar examen sin 'total_preguntas', que se actualizará después
            $sql = "INSERT INTO examenes (categoria_carne_id, titulo, descripcion) 
                    VALUES (:categoria_carne_id, :titulo, :descripcion)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':categoria_carne_id', $categoria_carne_id, PDO::PARAM_INT);
            $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);

            if ($stmt->execute()) {
                // Obtener ID del examen recién insertado
                $examen_id = $conn->lastInsertId();

                // Contar preguntas asociadas (probablemente será 0 al principio)
                $sqlContar = "SELECT COUNT(*) FROM preguntas WHERE examen_id = :examen_id";
                $stmtContar = $conn->prepare($sqlContar);
                $stmtContar->bindParam(':examen_id', $examen_id, PDO::PARAM_INT);
                $stmtContar->execute();
                $total_preguntas = $stmtContar->fetchColumn();

                // Actualizar campo total_preguntas del examen
                $sqlActualizar = "UPDATE examenes 
                                  SET total_preguntas = :total_preguntas 
                                  WHERE id = :examen_id";
                $stmtActualizar = $conn->prepare($sqlActualizar);
                $stmtActualizar->bindParam(':total_preguntas', $total_preguntas, PDO::PARAM_INT);
                $stmtActualizar->bindParam(':examen_id', $examen_id, PDO::PARAM_INT);
                $stmtActualizar->execute();

                // Redirigir tras éxito
                header('Location: ../admin/examenes.php?mensaje=exito');
                exit();
            } else {
                $mensaje_error = 'Error al guardar el examen.';
            }
        } catch (PDOException $e) {
            $mensaje_error = 'Error de base de datos: ' . $e->getMessage();
        }
    }
}

// Aquí puedes incluir el HTML para mostrar errores si no rediriges
?>
