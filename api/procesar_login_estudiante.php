<?php
session_start();
header('Content-Type: application/json');
require_once '../includes/conexion.php';  // Define correctamente $pdo

$response = ['status' => false, 'message' => 'Acceso denegado.'];

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido.');
    }

    $tipo = $_POST['tipoUsuario'] ?? '';
    if ($tipo === 'usuario') {
        // === LOGIN DE USUARIOS ADMINISTRATIVOS ===
        $usuario = trim($_POST['usuario'] ?? '');
        $clave = $_POST['password'] ?? '';

        if (!$usuario || !$clave) {
            throw new Exception('Usuario y contraseña requeridos.');
        }

        $stmt = $pdo->prepare("
            SELECT id, nombre, rol, contrasena_hash, email, activo 
            FROM usuarios 
            WHERE email = :usuario 
            LIMIT 1
        ");
        $stmt->execute(['usuario' => $usuario]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($clave, $user['contrasena_hash'])) {
            throw new Exception('Credenciales incorrectas.');
        }

        if (!$user['activo']) {
            throw new Exception('Usuario inactivo, contacte al administrador.');
        }

        // Guardar sesión
        $_SESSION['usuario_id'] = $user['id'];
        $_SESSION['usuario_nombre'] = $user['nombre'];
        $_SESSION['usuario_rol'] = $user['rol'];
        $_SESSION['usuario'] = [
            'id' => $user['id'],
            'nombre' => $user['nombre'],
            'rol' => $user['rol'],
            'email' => $user['email']
        ];

        // Redirección por rol
        $redirect = match ($user['rol']) {
            'secretaria' => 'secretaria/index.php',
            'examinador' => 'examinador/index.php',
            default       => 'admin/index.php',
        };

        $response = [
            'status' => true,
            'message' => 'Bienvenido, ' . htmlspecialchars($user['nombre']),
            'redirect' => $redirect
        ];
    }

    elseif ($tipo === 'estudiante') {
        // === LOGIN DE ESTUDIANTE CON CÓDIGO ===
        $codigo = trim($_POST['usuario'] ?? '');
        if (!$codigo) {
            throw new Exception('Código de acceso requerido.');
        }

        // Buscamos el examen solo por código para saber en qué estado está realmente
        $stmt = $pdo->prepare("
            SELECT 
                e.id AS examen_id,
                e.estudiante_id,
                e.estado AS estado_examen,
                e.codigo_acceso,
                s.nombre AS estudiante_nombre,
                s.apellidos
            FROM examenes e
            INNER JOIN estudiantes s ON s.id = e.estudiante_id
            WHERE e.codigo_acceso = :codigo_acceso
            LIMIT 1
        ");
        
        $stmt->execute(['codigo_acceso' => $codigo]);
        $examen = $stmt->fetch(PDO::FETCH_ASSOC);

        // 1. Validar si el código existe
        if (!$examen) {
            throw new Exception('El código de acceso no existe en nuestra base de datos.');
        }

        // 2. Validar el estado del examen con mensajes específicos
        // Convertimos a minúsculas para evitar problemas de consistencia
        $estado = strtolower($examen['estado_examen']);

        switch ($estado) {
            case 'pendiente':
                // Único estado que permite el ingreso
                $_SESSION['estudiante_id'] = $examen['estudiante_id'];
                $_SESSION['estudiante_nombre'] = $examen['estudiante_nombre'] . ' ' . $examen['apellidos'];
                $_SESSION['estudiante'] = $examen;

                $response = [
                    'status' => true,
                    'message' => 'Acceso concedido. Bienvenido/a ' . htmlspecialchars($examen['estudiante_nombre']),
                    'redirect' => 'politicas.php'
                ];
                break;

            case 'inicio':
                throw new Exception('El examen aun no se ha activado');
                break;

            case 'finalizado':
                throw new Exception('Este examen ya ha sido completado y calificado.');
                break;

            case 'expirado':
                throw new Exception('El tiempo de validez para este código ha expirado.');
                break;

            default:
                throw new Exception('El examen se encuentra en un estado no disponible (' . $estado . ').');
                break;
        }
    }

    else {
        throw new Exception('Tipo de usuario inválido.');
    }

} catch (Exception $e) {
    // Nunca reveles detalles técnicos directamente en producción
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
exit;
