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
                        e.id AS estudiante_id,
                        e.dni,
                        CONCAT(e.nombre,' ',e.apellidos),                        
                        e.email,
                        e.usuario,
                        ec.categoria_id,
                        ec.estado AS estado_categoria,
                        ec.fecha_asignacion AS fecha_asignacion_categoria,
                        ex.id AS examen_id,
                        ex.fecha_asignacion AS fecha_asignacion_examen,
                        ex.total_preguntas,
                        ex.estado AS estado_examen,
                        ex.calificacion,
                        ex.codigo_acceso
                        FROM estudiantes e
                        INNER JOIN estudiante_categorias ec ON e.id = ec.estudiante_id
                        LEFT JOIN examenes ex ON ec.estudiante_id = ex.estudiante_id AND ec.categoria_id = ex.categoria_id
                        WHERE e.usuario = ?
                        ORDER BY ec.categoria_id, ex.fecha_asignacion DESC;

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
                    'redirect' => 'aspirante.php'
                ];
            } elseif($estudiante['estado'] !== 'activo') {
                throw new Exception('Tu cuenta esta inactiva contacta con tu administrador.');
                
            }else  {
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
