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

                $response = [
                    'status' => true,
                    'message' => 'Bienvenido, ' . htmlspecialchars($user['nombre']),
                    'redirect' => 'admin/index.php'
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
                SELECT 
                    ex.id AS examen_id,
                    ex.codigo_acceso,
                    ex.estudiante_id,
                    ex.categoria_id,
                    ex.asignado_por,
                    ex.estado AS examen_estado,
                    es.id AS estudiante_id,
                    es.nombre AS estudiante_nombre,
                    es.estado AS estudiante_estado,
                    cat.id AS categoria_id,
                    cat.nombre AS categoria_nombre,
                    us.id AS usuario_id,
                    us.nombre AS usuario_nombre
                FROM examenes ex
                LEFT JOIN estudiantes es ON es.id = ex.estudiante_id
                LEFT JOIN categorias cat ON cat.id = ex.categoria_id
                LEFT JOIN usuarios us ON us.id = ex.asignado_por
                WHERE ex.codigo_acceso = :codigo AND es.estado = 'activo'
                LIMIT 1
            ");
            $stmt->execute(['codigo' => $codigo]);
            $estudiante = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($estudiante) {
                $_SESSION['estudiante_id'] = $estudiante['estudiante_id'];
                $_SESSION['estudiante_nombre'] = $estudiante['estudiante_nombre'];
                $_SESSION['examen_id'] = $estudiante['examen_id'];
                $_SESSION['estudiante'] = $estudiante; // puedes usarlo para detalles

                $response = [
                    'status' => true,
                    'message' => 'Bienvenido/a ' . htmlspecialchars($estudiante['estudiante_nombre']),
                    'redirect' => 'estudiante/index.php'
                ];
            } else {
                throw new Exception('Código inválido o estudiante inactivo.');
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
