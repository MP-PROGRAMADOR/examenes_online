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

        $stmt = $pdo->prepare("
            SELECT 
                e.id AS examen_id,
                e.estudiante_id,
                e.categoria_id,
                e.asignado_por,
                e.fecha_asignacion,
                e.duracion,
                e.total_preguntas,
                e.estado AS estado_examen,
                e.calificacion,
                e.codigo_acceso,

                s.id AS estudiante_id,
                s.dni,
                s.nombre AS estudiante_nombre,
                s.apellidos,
                s.email,
                s.telefono,
                s.fecha_nacimiento,
                s.escuela_id,
                s.direccion,
                s.Doc,
                s.estado AS estado_estudiante,
                s.creado_en,

                c.nombre AS categoria_nombre,
                c.descripcion AS categoria_descripcion,
                c.edad_minima

            FROM examenes e
            INNER JOIN estudiantes s ON s.id = e.estudiante_id
            INNER JOIN categorias c ON c.id = e.categoria_id
            WHERE e.codigo_acceso = :codigo_acceso
              AND e.estado IN ('pendiente', 'INICIO')
               
            LIMIT 1
        ");
        $stmt->execute(['codigo_acceso' => $codigo]);
        $examen = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$examen) {
            throw new Exception('Código inválido o examen no disponible.');
        }

        $_SESSION['estudiante_id'] = $examen['estudiante_id'];
        $_SESSION['estudiante_nombre'] = $examen['estudiante_nombre'] . ' ' . $examen['apellidos'];
        $_SESSION['estudiante'] = $examen;

        $response = [
            'status' => true,
            'message' => 'Bienvenido/a ' . htmlspecialchars($examen['estudiante_nombre']),
            'redirect' => 'politicas.php'
        ];
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
