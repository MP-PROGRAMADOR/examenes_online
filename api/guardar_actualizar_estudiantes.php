<?php
require_once '../includes/conexion.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $estudiante_id = isset($_POST['estudiante_id']) ? (int) $_POST['estudiante_id'] : null;
    $dni = trim($_POST['dni'] ?? '');
    $nombre = trim($_POST['nombre'] ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');
    $email = trim($_POST['email'] ?? null);
    $usuario = trim($_POST['usuario'] ?? '');
    $telefono = trim($_POST['telefono'] ?? null);
    $num = trim($_POST['num'] ?? null);
    $fecha_nacimiento = trim($_POST['fecha_nacimiento'] ?? null);
    $direccion = trim($_POST['direccion'] ?? null);
    $escuela_id = !empty($_POST['escuela_id']) ? (int) $_POST['escuela_id'] : null;
    $estado = isset($_POST['activo']) && $_POST['activo'] === 'on' ? 'activo' : 'inactivo';

    // Categoría a asignar
    $categoria_id = isset($_POST['categoria_id']) ? (int) $_POST['categoria_id'] : null;

    try {
        if (empty($dni) || empty($nombre) || empty($apellidos) || empty($categoria_id)) {
            throw new Exception("Faltan campos obligatorios: DNI, nombre, apellidos o categoría.");
        }

        // Validar duplicado de usuario (excepto si se generará luego)
        if (!empty($usuario)) {
            $queryCheck = "SELECT id FROM estudiantes WHERE dni = ? AND id != ?";
            $stmtCheck = $pdo->prepare($queryCheck);
            $stmtCheck->execute([$dni, $estudiante_id]);
            if ($stmtCheck->rowCount() > 0) {
                throw new Exception("Ya existe un estudiante con ese Dip.");
            }
        }

        // Transacción
        $pdo->beginTransaction();

        if (!$estudiante_id) {
            // Registro nuevo
            $sql = "INSERT INTO estudiantes (dni, nombre, apellidos, email,  telefono, fecha_nacimiento, direccion, escuela_id, estado, Doc)
                    VALUES (?, ?, ?, ?, ?, ?, ?,  ?, ?,?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $dni,
                $nombre,
                $apellidos,
                $email,
                $telefono,
                $fecha_nacimiento,
                $direccion,
                $escuela_id,
                'activo',
                $num
            ]);

            $estudiante_id = $pdo->lastInsertId();

            // Obtener nombre escuela para generar código de usuario
            $escuela_nombre = '';
            if ($escuela_id) {
                $stmtEscuela = $pdo->prepare("SELECT nombre FROM escuelas_conduccion WHERE id = ?");
                $stmtEscuela->execute([$escuela_id]);
                $escuela = $stmtEscuela->fetch(PDO::FETCH_ASSOC);
                $escuela_nombre = $escuela ? $escuela['nombre'] : '';
            }

            // Función generadora
            function generarCodigoAcceso($nombreEscuela, $categoriaId)
            {
                $prefijo = "E";
                $iniciales = strtoupper(substr(preg_replace('/[^A-Z]/i', '', $nombreEscuela), 0, 2));
                $anio = date('y');
                $random = strtoupper(substr(bin2hex(random_bytes(2)), 0, 2));
                return substr($prefijo . $iniciales . $anio . $categoriaId . $random, 0, 11);
            }

            $usuarioGenerado = generarCodigoAcceso($escuela_nombre, $categoria_id);

            // Actualizar usuario generado
            $pdo->prepare("UPDATE estudiantes SET usuario = ? WHERE id = ?")->execute([$usuarioGenerado, $estudiante_id]);

        } else {
            // Actualización
            $sql = "UPDATE estudiantes SET dni = ?, nombre = ?, apellidos = ?, email = ?, usuario = ?, telefono = ?, fecha_nacimiento = ?, direccion = ?, escuela_id = ?, estado = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $dni,
                $nombre,
                $apellidos,
                $email,
                $usuario,
                $telefono,
                $fecha_nacimiento,
                $direccion,
                $escuela_id,
                $estado,
                $estudiante_id
            ]);
        }

        // Verificar si ya tiene una asignación de categoría
        $stmtCat = $pdo->prepare("SELECT id FROM estudiante_categorias WHERE estudiante_id = ?");
        $stmtCat->execute([$estudiante_id]);

        if ($stmtCat->rowCount() > 0) {
            // Actualiza la categoría
            $pdo->prepare("UPDATE estudiante_categorias SET categoria_id = ?, estado = 'pendiente', fecha_asignacion = CURRENT_TIMESTAMP WHERE estudiante_id = ?")
                ->execute([$categoria_id, $estudiante_id]);
        } else {
            // Inserta nueva categoría
            $pdo->prepare("INSERT INTO estudiante_categorias (estudiante_id, categoria_id) VALUES (?, ?)")
                ->execute([$estudiante_id, $categoria_id]);
        }

        $pdo->commit();
        echo json_encode(['status' => true, 'message' => $estudiante_id ? 'Estudiante actualizado' : 'Estudiante registrado']);
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['status' => false, 'message' => ' estoy: '. $e->getMessage()]);
    }
 
} else {
    echo json_encode(['status' => false, 'message' => 'Método no permitido']);
}
