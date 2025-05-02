<?php
require_once '../config/conexion.php';

$conn = $pdo->getConexion();

try {
    if (
        empty($_POST['categoria_carne_id']) ||
        empty($_POST['titulo']) ||
        empty($_POST['duracion_minutos'])
    ) {
        // Si faltan datos, redirige sin guardar
        header('Location: ../admin/examenes.php?mensaje=error');
        exit();
    }

    // Obtener datos del formulario
    $id = $_POST['id'] ?? null;
    $categoria_carne_id = (int) $_POST['categoria_carne_id'];
    $titulo = trim($_POST['titulo']);
    $descripcion = trim($_POST['descripcion'] ?? '');
    $duracion_minutos = (int) $_POST['duracion_minutos'];

    if ($duracion_minutos <= 0) {
        header('Location: ../admin/examenes.php?mensaje=error');
        exit();
    }

    if ($id) {
        // Actualizar examen
        $sql = "UPDATE examenes SET 
                    categoria_carne_id = :categoria_carne_id,
                    titulo = :titulo,
                    descripcion = :descripcion,
                    duracion_minutos = :duracion_minutos
                WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':categoria_carne_id' => $categoria_carne_id,
            ':titulo' => $titulo,
            ':descripcion' => $descripcion,
            ':duracion_minutos' => $duracion_minutos,
            ':id' => $id
        ]);
    } else {
        // Crear examen
        $sql = "INSERT INTO examenes 
                    (categoria_carne_id, titulo, descripcion, duracion_minutos) 
                VALUES 
                    (:categoria_carne_id, :titulo, :descripcion, :duracion_minutos)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':categoria_carne_id' => $categoria_carne_id,
            ':titulo' => $titulo,
            ':descripcion' => $descripcion,
            ':duracion_minutos' => $duracion_minutos
        ]);
    }

    // Redirigir siempre aquí tras guardar correctamente
    header('Location: ../admin/examenes.php?mensaje=exito');
    exit();

} catch (PDOException $e) {
    error_log("Error al guardar examen: " . $e->getMessage());
    header('Location: ../admin/examenes.php?mensaje=error');
    exit();
}
