<?php
session_start(); 
require '../config/conexion.php';

$pdo=$pdo->getConexion();

 

// Obtener todos los exámenes
$stmt = $pdo->query("SELECT id, titulo FROM examenes");
$examenes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Si se ha enviado un examen origen, mostrar preguntas
$preguntas = [];
if (isset($_GET['examen_origen'])) {
    $stmt = $pdo->prepare("SELECT * FROM preguntas WHERE examen_id = ?");
    $stmt->execute([$_GET['examen_origen']]);
    $preguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
include '../componentes/head_admin.php';
include '../componentes/menu_admin.php';
?>

 

<div class="main-content">
    <div class="container-fluid mt-5">
        <div class="card shadow border-0 rounded-4">
            <div class="card-header bg-primary text-white rounded-top-4 px-4 d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-files me-2"></i>Clonar Preguntas entre Exámenes</h5>
            </div>
            <div class="card-body">
                <form method="GET" class="row mb-4">
                    <div class="col-md-6">
                        <label for="examen_origen" class="form-label">Seleccionar examen de origen:</label>
                        <select name="examen_origen" id="examen_origen" class="form-select" required>
                            <option value="">-- Selecciona uno --</option>
                            <?php foreach ($examenes as $examen): ?>
                                <option value="<?= $examen['id'] ?>" <?= ($_GET['examen_origen'] ?? '') == $examen['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($examen['titulo']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-search me-1"></i> Buscar</button>
                    </div>
                </form>

                <?php if (!empty($preguntas)): ?>
                    <form action="procesar_clonado.php" method="POST">
                        <input type="hidden" name="examen_origen" value="<?= htmlspecialchars($_GET['examen_origen']) ?>">

                        <div class="mb-3">
                            <label for="examen_destino" class="form-label">Seleccionar examen destino:</label>
                            <select name="examen_destino" id="examen_destino" class="form-select" required>
                                <option value="">-- Selecciona uno --</option>
                                <?php foreach ($examenes as $examen): ?>
                                    <?php if ($examen['id'] != $_GET['examen_origen']): ?>
                                        <option value="<?= $examen['id'] ?>">
                                            <?= htmlspecialchars($examen['titulo']) ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <h6 class="mb-3">Selecciona las preguntas que deseas clonar:</h6>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th><input type="checkbox" id="checkAll"></th>
                                        <th>ID</th>
                                        <th>Texto</th>
                                        <th>Tipo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($preguntas as $pregunta): ?>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="preguntas_clonar[]" value="<?= $pregunta['id'] ?>">
                                            </td>
                                            <td><?= $pregunta['id'] ?></td>
                                            <td><?= htmlspecialchars($pregunta['texto_pregunta']) ?></td>
                                            <td><?= htmlspecialchars($pregunta['tipo_pregunta']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <button type="submit" class="btn btn-success mt-3"><i class="bi bi-files me-1"></i> Clonar seleccionadas</button>
                    </form>
                <?php elseif (isset($_GET['examen_origen'])): ?>
                    <div class="alert alert-warning mt-3">
                        <i class="bi bi-exclamation-circle me-2"></i>No se encontraron preguntas para este examen.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('checkAll')?.addEventListener('change', function () {
        document.querySelectorAll('input[name="preguntas_clonar[]"]').forEach(cb => {
            cb.checked = this.checked;
        });
    });
</script>
<?php include_once('../componentes/footer.php'); ?>