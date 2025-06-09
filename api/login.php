<?php
session_start();
require_once '../includes/conexion.php';  // Debe definir $pdo correctamente

header('Content-Type: application/json');

$response = ['status' => false, 'message' => 'Acceso denegado.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = $_POST['tipoUsuario'] ?? '';

    try {
        if ($tipo === 'usuario') {
            $usuario = trim($_POST['usuario'] ?? '');
            $clave = $_POST['password'] ?? '';

            if (!$usuario || !$clave) {
                throw new Exception('Usuario y contraseña requeridos.');
            }


            
            $stmt = $pdo->prepare("SELECT id, nombre, rol, contrasena_hash, email, activo FROM usuarios WHERE email = :usuario LIMIT 1");
            $stmt->execute(['usuario' => $usuario]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                throw new Exception('Credenciales incorrectas.');
            }

            if (!$user['activo']) {
                throw new Exception('Usuario inactivo, contacte al administrador.');
            }

            if (password_verify($clave, $user['contrasena_hash'])) {
                $_SESSION['usuario_id'] = $user['id'];
                $_SESSION['usuario_nombre'] = $user['nombre'];
                $_SESSION['usuario_rol'] = $user['rol'];

                $_SESSION['usuario'] = [
                    'id' => $user['id'],
                    'nombre' => $user['nombre'],
                    'rol' => $user['rol'],
                    'email' => $user['email']
                ];

                // Redirección según el rol
                $redirect = 'admin/index.php'; // valor por defecto

                if ($user['rol'] === 'secretaria') {
                    $redirect = 'secretaria/index.php';
                } elseif ($user['rol'] === 'administrador') {
                    $redirect = 'admin/index.php';
                } // puedes añadir más roles aquí si lo necesitas
                elseif ($user['rol'] === 'examinador') {
                    $redirect = 'examinador/index.php';
                }

                $response = [
                    'status' => true,
                    'message' => 'Bienvenido, ' . htmlspecialchars($user['nombre']),
                    'redirect' => $redirect
                ];
            } else {
                throw new Exception('Credenciales incorrectas.');
            }
        } elseif ($tipo === 'estudiante') {
            $codigo = trim($_POST['usuario'] ?? '');

            if (!$codigo) {
                throw new Exception('Código de acceso requerido.');
            }

            // Consulta con alias para evitar confusión de ids
            $stmt = $pdo->prepare("
                                        SELECT * 
                                        FROM estudiantes 
                                        WHERE usuario = :usuario AND estado = 'activo'
                                    ");

            $stmt->execute(['usuario' => $codigo]); // <-- debe coincidir con :usuario
            $estudiante = $stmt->fetch(PDO::FETCH_ASSOC);


            if ($estudiante) {
                $_SESSION['estudiante_id'] = $estudiante['id'];
                $_SESSION['estudiante_nombre'] = $estudiante['nombre'];
                $_SESSION['estudiante'] = $estudiante; // puedes usarlo para detalles

                $response = [
                    'status' => true,
                    'message' => 'Bienvenido/a ' . htmlspecialchars($estudiante['nombre']),
                    'redirect' => 'aspirante.php'
                ];
            } else {
                throw new Exception('Código inválido.');
            }
        } else {
            throw new Exception('Tipo de usuario inválido.');
        }
    } catch (Exception $e) {
        $response['message'] = $e->getMessage();
    }
} else {
    $response['message'] = 'Método no permitido.';
}

echo json_encode($response);
exit;
