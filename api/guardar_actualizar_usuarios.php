<?php
require_once '../includes/conexion.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = isset($_POST['usuario_id']) ? (int) $_POST['usuario_id'] : null;
    $nombre = trim($_POST['nombre'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $contrasena = $_POST['contrasena'] ?? '';
    $rol = $_POST['rol'] ?? 'operador';

    try {
        // Validación de campos requeridos
        if (empty($nombre) || empty($email)) {
            throw new Exception("Nombre y correo electrónico son obligatorios.");
        }

        // Validación de formato de email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Correo electrónico no tiene un formato válido.");
        }

        // Validación de rol
        $roles_validos = ['admin', 'examinador', 'operador'];
        if (!in_array($rol, $roles_validos)) {
            throw new Exception("Rol inválido. Debe ser admin, examinador u operador.");
        }

        // Actualización de usuario existente
        if ($usuario_id) {
            // Verificar si el email ya está en uso por otro usuario
            $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ?");
            $stmt->execute([$email, $usuario_id]);
            if ($stmt->rowCount() > 0) {
                throw new Exception("El correo electrónico ya está en uso por otro usuario.");
            }

            if (!empty($contrasena)) {
                if (strlen($contrasena) < 6) {
                    throw new Exception("La nueva contraseña debe tener al menos 6 caracteres.");
                }

                $hash = password_hash($contrasena, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, email = ?, contrasena_hash = ?, rol = ? WHERE id = ?");
                $stmt->execute([$nombre, $email, $hash, $rol, $usuario_id]);
            } else {
                $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, email = ?, rol = ? WHERE id = ?");
                $stmt->execute([$nombre, $email, $rol, $usuario_id]);
            }

            echo json_encode(['status' => true, 'message' => 'Usuario actualizado correctamente']);
        } else {
            // Validación de contraseña para nuevos usuarios
            if (empty($contrasena)) {
                throw new Exception("La contraseña es obligatoria para nuevos usuarios.");
            }
            if (strlen($contrasena) < 6) {
                throw new Exception("La contraseña debe tener al menos 6 caracteres.");
            }

            // Verificar si el email ya existe
            $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->rowCount() > 0) {
                throw new Exception("El correo electrónico ya está registrado.");
            }

            $hash = password_hash($contrasena, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, contrasena_hash, rol) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nombre, $email, $hash, $rol]);

            echo json_encode(['status' => true, 'message' => 'Usuario registrado correctamente']);
        }
    } catch (Exception $e) {
        echo json_encode(['status' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => false, 'message' => 'Método no permitido']);
}

