<?php
// asignar_examen.php
require '../config/conexion.php';

$conn = $pdo->getConexion();
$alerta = ''; // Variable para manejar las alertas


// Procesar formulario
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $estudiante_id = intval($_POST['estudiante_id']);
    $examen_id = intval($_POST['examen_id']);
    $codigo_acceso = bin2hex(random_bytes(5)); // Código aleatorio

    try {
    

        // Verificar si ya tiene un examen asignado
        $stmt = $conn->prepare("SELECT COUNT(*) FROM asignaciones_examen WHERE estudiante_id = ?");
        $stmt->execute([$estudiante_id]);
        if ($stmt->fetchColumn() > 0) {
            $mensaje = 'Este estudiante ya tiene un examen asignado.';
        } else {
            // Insertar asignación
            $stmt = $conn->prepare("INSERT INTO asignaciones_examen (estudiante_id, examen_id, codigo_acceso) VALUES (?, ?, ?)");
            $stmt->execute([$estudiante_id, $examen_id, $codigo_acceso]);
            $mensaje = 'Examen asignado exitosamente con el código: <strong>' . $codigo_acceso . '</strong>';
        }
    } catch (Exception $e) {
        $mensaje = 'Error: ' . $e->getMessage();
    }
}

// Obtener lista de estudiantes y exámenes
 
$estudiantes = $conn->query("
    SELECT e.id, e.nombre, e.apellido, c.nombre AS categoria
    FROM estudiantes e
    JOIN categorias_carne c ON e.categoria_carne_id = c.id
    ORDER BY e.nombre ASC
")->fetchAll(PDO::FETCH_ASSOC);

$examenes = $conn->query("
    SELECT ex.id, ex.titulo, c.nombre AS categoria
    FROM examenes ex
    JOIN categorias_carne c ON ex.categoria_carne_id = c.id
    ORDER BY ex.titulo ASC
")->fetchAll(PDO::FETCH_ASSOC);
?>


<?php
include_once("../componentes/head_admin.php");
include_once("../componentes/menu_admin.php");
?>


<div class="main-content">
    <h2>Asignar Examen a Estudiante</h2>

    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-info"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <form method="POST" class="card p-4 shadow-sm">
        <div class="mb-3">
            <label for="estudiante_id" class="form-label">Estudiante</label>
            <select name="estudiante_id" id="estudiante_id" class="form-select" required>
                <option value="">Seleccione un estudiante</option>
                <?php foreach ($estudiantes as $e): ?>
                    <option value="<?= $e['id'] ?>">
                        <?= htmlspecialchars($e['nombre'] . ' ' . $e['apellido'] . ' - ' . $e['categoria']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="examen_id" class="form-label">Examen</label>
            <select name="examen_id" id="examen_id" class="form-select" required>
                <option value="">Seleccione un examen</option>
                <?php foreach ($examenes as $ex): ?>
                    <option value="<?= $ex['id'] ?>">
                        <?= htmlspecialchars($ex['titulo'] . ' - ' . $ex['categoria']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Asignar Examen</button>
    </form>
</div>
<?php include_once('../componentes/footer.php'); ?>
 