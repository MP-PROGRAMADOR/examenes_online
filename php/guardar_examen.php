<?php


require '../config/conexion.php';

$conn=$pdo->getConexion();

 


// Inicializar variables
$categoria_carne_id = '';
$titulo = '';
$descripcion = '';
$duracion_minutos = '';
$activo = 1; // Valor por defecto para activo
$errores = [];
$mensaje_exito = '';
$mensaje_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger y sanitizar datos
    $categoria_carne_id = filter_input(INPUT_POST, 'categoria_carne_id', FILTER_SANITIZE_NUMBER_INT);
    $titulo = trim(strip_tags($_POST['titulo']));
    $descripcion = trim(strip_tags($_POST['descripcion']));
    $duracion_minutos = filter_input(INPUT_POST, 'duracion_minutos', FILTER_SANITIZE_NUMBER_INT);
    $activo = isset($_POST['activo']) && $_POST['activo'] == 1 ? 1 : 0;

    // Validar categoría de carné
    if (empty($categoria_carne_id) || $categoria_carne_id <= 0) {
        $errores['categoria_carne_id'] = 'Por favor, seleccione una categoría de carné válida.';
    }

    // Validar título
    if (empty($titulo)) {
        $errores['titulo'] = 'El título del examen es obligatorio.';
    } elseif (strlen($titulo) > 255) {
        $errores['titulo'] = 'El título del examen no puede tener más de 255 caracteres.';
    }

    // Validar duración
    if (empty($duracion_minutos) || $duracion_minutos <= 0) {
        $errores['duracion_minutos'] = 'La duración del examen debe ser un número entero positivo.';
    }

    // Si no hay errores
    if (empty($errores)) {
        try {
            $sql = "INSERT INTO examenes (categoria_carne_id, titulo, descripcion, duracion_minutos )
                    VALUES (:categoria_carne_id, :titulo, :descripcion, :duracion_minutos )";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':categoria_carne_id', $categoria_carne_id, PDO::PARAM_INT);
            $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
            $stmt->bindParam(':duracion_minutos', $duracion_minutos, PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                $mensaje_exito = 'Examen registrado exitosamente.';
                // Redirigir a la lista de exámenes (opcional)
                header('Location: ../admin/examenes.php?mensaje=exito');
                exit();
            } else {
                $mensaje_error = 'Error al guardar el examen.';
                //error_log("Error al insertar examen: " . print_r($stmt->errorInfo(), true));
                echo "fatal errot";
            }
        } catch (PDOException $e) {
            $mensaje_error = 'Error de base de datos: ' . $e->getMessage();
            //error_log("PDOException al insertar examen: " . $e->getMessage());
        }
    }
}

// Incluir el formulario para mostrar errores y mensajes (opcional, si no se redirige)
 
?>