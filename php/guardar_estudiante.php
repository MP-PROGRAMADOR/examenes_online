<?php
session_start();
require '../config/conexion.php';

$conn = $pdo->getConexion();
$alerta = null; // Variable para almacenar mensajes

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Validar campos obligatorios
        if (
            empty($_POST['escuela_id']) ||
            empty($_POST['numero_identificacion']) ||
            empty($_POST['nombre']) ||
            empty($_POST['apellido']) ||
            empty($_POST['fecha_nacimiento']) ||
            empty($_POST['categoria_carne'])
        ) {
            throw new Exception("Todos los campos obligatorios deben estar completos.");
        }

        // Sanitización
        $escuela_id = filter_var($_POST['escuela_id'], FILTER_VALIDATE_INT);
        $numero_identificacion = htmlspecialchars(trim($_POST['numero_identificacion']));
        $nombre = htmlspecialchars(trim($_POST['nombre']));
        $apellido = htmlspecialchars(trim($_POST['apellido']));
        $fecha_nacimiento = $_POST['fecha_nacimiento'];
        $telefono = !empty($_POST['telefono']) ? htmlspecialchars(trim($_POST['telefono'])) : null;
        $direccion = !empty($_POST['direccion']) ? htmlspecialchars(trim($_POST['direccion'])) : null;
        $categoria_carne_id = filter_var($_POST['categoria_carne'], FILTER_VALIDATE_INT); // Usar el ID de la categoría de carne

        // Verificar si ya existe estudiante con la misma cédula
        $stmt = $conn->prepare("SELECT COUNT(*) FROM estudiantes WHERE numero_identificacion = :cedula");
        $stmt->bindParam(':cedula', $numero_identificacion);
        $stmt->execute();
        if ($stmt->fetchColumn() > 0) {
            throw new Exception("Ya existe un estudiante registrado con esa cédula.");
        }

        // Calcular edad
        $fechaNacimientoObj = new DateTime($fecha_nacimiento);
        $hoy = new DateTime();
        $edad = $fechaNacimientoObj->diff($hoy)->y;

        // Validación de tipo de carné y obtener la edad mínima desde la base de datos
        $stmtCategoria = $conn->prepare("SELECT edad_minima FROM categorias_carne WHERE id = :categoria_id");
        $stmtCategoria->bindParam(':categoria_id', $categoria_carne_id);
        $stmtCategoria->execute();
        $categoriaData = $stmtCategoria->fetch(PDO::FETCH_ASSOC);

        if (!$categoriaData) {
            throw new Exception("Tipo de carné no válido.");
        }

        // Edad mínima de la categoría
        $edadMinima = $categoriaData['edad_minima'];

        // Validar edad mínima
        if ($edad < $edadMinima) {
            throw new Exception("Edad insuficiente ($edad años). Se requieren al menos {$edadMinima} años para la categoría.");
        }

        // Verificar existencia de escuela
        $stmtEscuela = $conn->prepare("SELECT nombre FROM escuelas_conduccion WHERE id = :id");
        $stmtEscuela->bindParam(':id', $escuela_id);
        $stmtEscuela->execute();
        $escuela = $stmtEscuela->fetch(PDO::FETCH_ASSOC);

        if (!$escuela) {
            die("no existe la escuela.");
        }

        // Función para generar código único
        function generarCodigoAcceso($nombreEscuela, $categoria)
        {
            $prefijo = "E";
            $iniciales = strtoupper(substr(preg_replace('/[^A-Z]/i', '', $nombreEscuela), 0, 2));
            $anio = date('y');
            $random = strtoupper(substr(bin2hex(random_bytes(2)), 0, 2));
            return substr($prefijo . $iniciales . $anio . $categoria . $random, 0, 11);
        }

        $codigo_registro_examen = generarCodigoAcceso($escuela['nombre'], $categoria_carne_id);

        // Insertar estudiante
        $sql = "INSERT INTO estudiantes (
                    escuela_id, numero_identificacion, nombre, apellido,
                    fecha_nacimiento, telefono, direccion,
                    categoria_carne_id, codigo_registro_examen
                ) VALUES (
                    :escuela_id, :numero_identificacion, :nombre, :apellido,
                    :fecha_nacimiento, :telefono, :direccion,
                    :categoria_carne_id, :codigo_registro_examen
                )";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':escuela_id', $escuela_id);
        $stmt->bindParam(':numero_identificacion', $numero_identificacion);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':fecha_nacimiento', $fecha_nacimiento);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':categoria_carne_id', $categoria_carne_id);
        $stmt->bindParam(':codigo_registro_examen', $codigo_registro_examen);
        $stmt->execute();

        // Mensaje de éxito
        $alerta = [
            'tipo' => 'success',
            'mensaje' => "Estudiante registrado correctamente. Código generado: <strong>$codigo_registro_examen</strong>"
        ];
        echo "<div class='alert alert-success'>{$alerta['mensaje']}</div>";
        exit();
    } catch (Exception $e) {
        // Mostrar el error si ocurre
        die("error: " . $e->getMessage());
    }
}
?>
