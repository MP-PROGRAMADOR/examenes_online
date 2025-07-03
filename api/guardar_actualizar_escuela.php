<?php
require_once '../includes/conexion.php'; // Asegúrate de que este archivo maneje la conexión PDO correctamente
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir y sanear todas las variables
    $escuela_id      = isset($_POST['escuela_id']) ? (int) $_POST['escuela_id'] : null;
    $nombre          = trim($_POST['nombre'] ?? '');
    $telefono        = trim($_POST['telefono'] ?? '');
    $director        = trim($_POST['director'] ?? '');
    $nif             = trim($_POST['nif'] ?? '');
    $ciudad          = trim($_POST['ciudad'] ?? '');
    $correo          = trim($_POST['correo'] ?? ''); // Puede ser NULL, por eso no es 'required' en el frontend
    $pais            = trim($_POST['pais'] ?? 'Guinea Ecuatorial'); // Valor por defecto
    $ubicacion       = trim($_POST['ubicacion'] ?? '');
    $numero_registro = trim($_POST['numero_registro'] ?? '');

    try {
        // Validaciones básicas para campos obligatorios
        if (empty($nombre) || empty($telefono) || empty($director) || empty($nif) || empty($ciudad) || empty($ubicacion) || empty($numero_registro)) {
            throw new Exception("Todos los campos obligatorios (nombre, teléfono, director, NIF, ciudad, ubicación, número de registro) deben ser completados.");
        }

        // Validación de formato de correo si no está vacío
        if (!empty($correo) && !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("El formato del correo electrónico no es válido.");
        }

        if (!$escuela_id) {
            // --- Lógica para INSERTAR una nueva escuela ---

            // Verificar si ya existe una escuela con el mismo nombre, NIF o número de registro
            $stmt = $pdo->prepare("SELECT id FROM escuelas_conduccion WHERE nombre = ? OR nif = ? OR numero_registro = ?");
            $stmt->execute([$nombre, $nif, $numero_registro]);

            if ($stmt->rowCount() > 0) {
                $existing_school = $stmt->fetch(PDO::FETCH_ASSOC);
                // Aquí podrías ser más específico con el mensaje de error si quieres
                // Por ejemplo, verificar si el nombre es el duplicado, o el NIF, etc.
                throw new Exception("Ya existe una escuela con el mismo nombre, NIF o número de registro.");
            }

            // Preparar y ejecutar la inserción
            $stmt = $pdo->prepare(
                "INSERT INTO escuelas_conduccion (nombre, telefono, director, nif, ciudad, correo, pais, ubicacion, numero_registro)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->execute([
                $nombre,
                $telefono,
                $director,
                $nif,
                $ciudad,
                ($correo === '' ? null : $correo), // Guardar NULL si el correo está vacío
                $pais,
                $ubicacion,
                $numero_registro
            ]);

            echo json_encode(['status' => true, 'message' => 'Escuela registrada correctamente.']);

        } else {
            // --- Lógica para ACTUALIZAR una escuela existente ---

            // Verificar si ya existe otra escuela con el mismo nombre, NIF o número de registro
            // excluyendo la escuela que estamos actualizando (por su ID).
            $stmt = $pdo->prepare("SELECT id FROM escuelas_conduccion WHERE (nombre = ? OR nif = ? OR numero_registro = ?) AND id != ?");
            $stmt->execute([$nombre, $nif, $numero_registro, $escuela_id]);

            if ($stmt->rowCount() > 0) {
                $existing_school = $stmt->fetch(PDO::FETCH_ASSOC);
                throw new Exception("Ya existe otra escuela con el mismo nombre, NIF o número de registro.");
            }

            // Preparar y ejecutar la actualización
            $stmt = $pdo->prepare(
                "UPDATE escuelas_conduccion SET
                 nombre = ?,
                 telefono = ?,
                 director = ?,
                 nif = ?,
                 ciudad = ?,
                 correo = ?,
                 pais = ?,
                 ubicacion = ?,
                 numero_registro = ?
                 WHERE id = ?"
            );
            $stmt->execute([
                $nombre,
                $telefono,
                $director,
                $nif,
                $ciudad,
                ($correo === '' ? null : $correo), // Guardar NULL si el correo está vacío
                $pais,
                $ubicacion,
                $numero_registro,
                $escuela_id
            ]);

            // Opcional: Puedes verificar si se afectaron filas para dar un mensaje más específico
             if ($stmt->rowCount() > 0) {
              echo json_encode(['status' => true, 'message' => 'Escuela actualizada correctamente.']);
            } else {
             echo json_encode(['status' => false, 'message' => 'No se realizaron cambios o la escuela no existe.']);
          }

           // echo json_encode(['status' => true, 'message' => 'Escuela actualizada correctamente.']);
        }

    } catch (Exception $e) {
        // Capturar cualquier excepción y devolver un mensaje de error
        error_log("Error en guardar_actualizar_escuela.php: " . $e->getMessage()); // Para depuración en el servidor
        echo json_encode(['status' => false, 'message' => $e->getMessage()]);
    }

} else {
    // Si la solicitud no es POST, devolver un error
    echo json_encode(['status' => false, 'message' => 'Método de solicitud no permitido.']);
}