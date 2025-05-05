<?php
session_start();
require '../config/conexion.php';

$conn = $pdo->getConexion();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Validación de campos obligatorios
        $camposObligatorios = ['escuela_id', 'numero_identificacion', 'nombre', 'apellido', 'fecha_nacimiento', 'categoria_carne'];
        foreach ($camposObligatorios as $campo) {
            if (empty($_POST[$campo])) {
                throw new Exception("Todos los campos obligatorios deben estar completos.");
            }
        }

        // Sanitización y validación
        $escuela_id = filter_var($_POST['escuela_id'], FILTER_VALIDATE_INT);
        $numero_identificacion = htmlspecialchars(trim($_POST['numero_identificacion']));
        $nombre = htmlspecialchars(trim($_POST['nombre']));
        $apellido = htmlspecialchars(trim($_POST['apellido']));
        $fecha_nacimiento = $_POST['fecha_nacimiento'];
        $telefono = !empty($_POST['telefono']) ? htmlspecialchars(trim($_POST['telefono'])) : null;
        $direccion = !empty($_POST['direccion']) ? htmlspecialchars(trim($_POST['direccion'])) : null;
        $categoria_carne_id = filter_var($_POST['categoria_carne'], FILTER_VALIDATE_INT);

        if (!$escuela_id || !$categoria_carne_id) {
            throw new Exception("Escuela o categoría de carné no válida.");
        }

        // Verificar duplicado
        $stmt = $conn->prepare("SELECT COUNT(*) FROM estudiantes WHERE numero_identificacion = :cedula");
        $stmt->execute([':cedula' => $numero_identificacion]);
        if ($stmt->fetchColumn() > 0) {
            throw new Exception("Ya existe un estudiante registrado con esa cédula.");
        }

        // Validación de edad
        $stmt = $conn->prepare("SELECT edad_minima FROM categorias_carne WHERE id = :id");
        $stmt->execute([':id' => $categoria_carne_id]);
        $categoria = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$categoria) {
            throw new Exception("La categoría de carné seleccionada no existe.");
        }

        $edad = (new DateTime($fecha_nacimiento))->diff(new DateTime())->y;
        if ($edad < $categoria['edad_minima']) {
            throw new Exception("Edad insuficiente ($edad años). Se requieren al menos {$categoria['edad_minima']} años.");
        }

        // Validar existencia de la escuela
        $stmt = $conn->prepare("SELECT nombre FROM escuelas_conduccion WHERE id = :id");
        $stmt->execute([':id' => $escuela_id]);
        $escuela = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$escuela) {
            throw new Exception("La escuela seleccionada no existe.");
        }

        // Generar código único
        function generarCodigoAcceso($nombreEscuela, $categoriaId) {
            $prefijo = "E";
            $iniciales = strtoupper(substr(preg_replace('/[^A-Z]/i', '', $nombreEscuela), 0, 2));
            $anio = date('y');
            $random = strtoupper(substr(bin2hex(random_bytes(2)), 0, 2));
            return substr($prefijo . $iniciales . $anio . $categoriaId . $random, 0, 11);
        }

        $codigo_examen = generarCodigoAcceso($escuela['nombre'], $categoria_carne_id);

        // Iniciar transacción
        $conn->beginTransaction();

        // Registrar estudiante
        $stmt = $conn->prepare("
            INSERT INTO estudiantes (
                escuela_id, numero_identificacion, nombre, apellido,
                fecha_nacimiento, telefono, direccion,
                categoria_carne_id, codigo_registro_examen
            ) VALUES (
                :escuela_id, :numero_identificacion, :nombre, :apellido,
                :fecha_nacimiento, :telefono, :direccion,
                :categoria_carne_id, :codigo_examen
            )
        ");
        $stmt->execute([
            ':escuela_id' => $escuela_id,
            ':numero_identificacion' => $numero_identificacion,
            ':nombre' => $nombre,
            ':apellido' => $apellido,
            ':fecha_nacimiento' => $fecha_nacimiento,
            ':telefono' => $telefono,
            ':direccion' => $direccion,
            ':categoria_carne_id' => $categoria_carne_id,
            ':codigo_examen' => $codigo_examen
        ]);

        $estudiante_id = $conn->lastInsertId();

        // Asignar categoría al estudiante en examenes_estudiantes
        $stmt = $conn->prepare("
            INSERT INTO examenes_estudiantes (
                estudiante_id, categoria_carne_id, estado, acceso_habilitado
            ) VALUES (
                :estudiante_id, :categoria_carne_id, 'pendiente', 0
            )
        ");
        $stmt->execute([
            ':estudiante_id' => $estudiante_id,
            ':categoria_carne_id' => $categoria_carne_id
        ]);

        // Confirmar
        $conn->commit();

        $_SESSION['alerta'] = [
            'tipo' => 'success',
            'mensaje' => "Estudiante registrado con éxito. Código: <strong>$codigo_examen</strong>"
        ];
        header("Location: ../admin/estudiantes.php");
        exit;

    } catch (Exception $e) {
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }

        $_SESSION['alerta'] = [
            'tipo' => 'error',
            'mensaje' => "Error: " . $e->getMessage()
        ];
        header("Location: ../admin/registrar_estudiantes.php");
        exit;
    }
} else {
    header("Location: ../admin/registrar_estudiantes.php");
    exit;
}
