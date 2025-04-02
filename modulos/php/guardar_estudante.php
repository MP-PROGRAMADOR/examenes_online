






<?php
require '../../config/conexion.php';



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $conn=$pdo->getConexion();

        // Verificar que los datos requeridos están presentes y no están vacíos
        if (empty($_POST['escuela_id']) || empty($_POST['numero_identificacion']) || empty($_POST['nombre']) || empty($_POST['apellido']) || empty($_POST['fecha_nacimiento']) || empty($_POST['email']) || empty($_POST['categoria_carne'])) {
            throw new Exception("Todos los campos obligatorios deben ser completados.");
        }

        // Validar y limpiar los datos del formulario
        $escuela_id = filter_var($_POST['escuela_id'], FILTER_VALIDATE_INT);
        if (!$escuela_id) {
            throw new Exception("El ID de la escuela no es válido.");
        }

        $numero_identificacion = htmlspecialchars(trim($_POST['numero_identificacion']));
        $nombre = htmlspecialchars(trim($_POST['nombre']));
        $apellido = htmlspecialchars(trim($_POST['apellido']));
        $fecha_nacimiento = $_POST['fecha_nacimiento'];

        // Validar correo electrónico
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        if (!$email) {
            throw new Exception("El correo electrónico no es válido.");
        }

        $telefono = isset($_POST['telefono']) ? htmlspecialchars(trim($_POST['telefono'])) : null;
        $direccion = isset($_POST['direccion']) ? htmlspecialchars(trim($_POST['direccion'])) : null;
        $categoria_carne = htmlspecialchars(trim($_POST['categoria_carne']));

        // Generar un código aleatorio de 10 caracteres en mayúsculas de manera más segura
        function generarCodigoSeguro($longitud = 10) {
            $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $codigo = '';
            $max = strlen($caracteres) - 1;
            for ($i = 0; $i < $longitud; $i++) {
                $codigo .= $caracteres[random_int(0, $max)];
            }
            return $codigo;
        }

        $codigo_registro_examen = generarCodigoSeguro();

        // Preparar la consulta SQL usando bindParam para mayor seguridad
        $sql = "INSERT INTO estudiantes (escuela_id, numero_identificacion, nombre, apellido, fecha_nacimiento, email, telefono, direccion, categoria_carne, codigo_registro_examen)
                VALUES (:escuela_id, :numero_identificacion, :nombre, :apellido, :fecha_nacimiento, :email, :telefono, :direccion, :categoria_carne, :codigo_registro_examen)";
        
        $stmt = $conn->prepare($sql);

        // Asignar valores a los parámetros con bindParam
        $stmt->bindParam(':escuela_id', $escuela_id, PDO::PARAM_INT);
        $stmt->bindParam(':numero_identificacion', $numero_identificacion, PDO::PARAM_STR);
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':apellido', $apellido, PDO::PARAM_STR);
        $stmt->bindParam(':fecha_nacimiento', $fecha_nacimiento, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':telefono', $telefono, PDO::PARAM_STR);
        $stmt->bindParam(':direccion', $direccion, PDO::PARAM_STR);
        $stmt->bindParam(':categoria_carne', $categoria_carne, PDO::PARAM_STR);
        $stmt->bindParam(':codigo_registro_examen', $codigo_registro_examen, PDO::PARAM_STR);

        // Ejecutar la consulta
        $stmt->execute();

        // Mensaje de éxito con el código generado
        echo "<script>alert('Registro exitoso con código: $codigo_registro_examen'); window.location.href='../admin/estudiantes.php';</script>";

    } catch (Exception $e) {
        // Capturar errores y evitar inyección de JavaScript en el mensaje de error
        echo "<script>alert('Error: " . htmlspecialchars($e->getMessage()) . "'); window.history.back();</script>";
    }
}
?>
