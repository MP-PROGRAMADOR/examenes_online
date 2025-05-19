<?php

include_once("../includes/header.php");
include_once("../includes/sidebar.php");
try {
 
    // Consultar todas las categorías
    $sql = "SELECT * FROM categorias";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error en la consulta de categorías: " . $e->getMessage());
    // En caso de error al recuperar categorías, se agrega el mensaje de error

}

?>

<div class="main-content">

    <div class="container-fluid mt-5">
        <div class="card shadow border-0 rounded-4">

            <div
                class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top-4 px-4">
                <h5 class="mb-0"><i class="bi bi-tags-fill me-2"></i>Listado de Categorías</h5>
                <div class="search-box position-relative">
                    <input type="text" class="form-control ps-5" id="customSearch" placeholder="Buscar categoría...">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                </div>
                <div class="mb-0 d-flex justify-content-end align-items-center">
                    <label for="container-length" class="me-2 text-white fw-medium mb-0">Mostrar:</label>
                    <select id="container-length" class="form-select w-auto shadow-sm">
                        <option value="5">5 registros</option>
                        <option value="10" selected>10 registros</option>
                        <option value="15">15 registros</option>
                        <option value="20">20 registros</option>
                        <option value="25">25 registros</option>
                    </select>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="container-table" class="table table-striped table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th><i class="bi bi-hash"></i> ID</th>
                                <th><i class="bi bi-tag-fill"></i> Nombre</th>
                                <th><i class="bi bi-card-text"></i> Descripción</th>
                                <th><i class="bi bi-person"></i> Edad Mínima</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($categorias)): ?>
                                <?php foreach ($categorias as $categoria): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($categoria['id'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($categoria['nombre'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($categoria['descripcion'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($categoria['edad_minima'], ENT_QUOTES, 'UTF-8') ?> años</td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="alert alert-warning text-center">
                                    <i class="bi bi-exclamation-circle-fill me-2"></i>⚠️ No hay categorías registradas
                                    actualmente.
                                </div>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
      // Verificamos si ya se cargaron
        if (localStorage.getItem('categoriasCargadas') === 'true') {
            console.log('✅ Categorías ya estaban cargadas.');
            return;
        } 
        const formData = new FormData();
        formData.append('accion', 'cargar_categorias');

        fetch('../api/cargar_categorias.php', {
            method: 'POST',
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                if (data.status) {
                    //mostrarToast('success', data.message);
                   // setTimeout(() => location.reload(), 1200);
                } else {
                    mostrarToast('warning', data.message);
                }
            })
            .catch(error => {
                console.error('Error al cargar categorías:', error);
                mostrarToast('danger', 'Ocurrió un error al cargar las categorias.');
            });
    });

</script>

<?php include_once('../includes/footer.php'); ?>