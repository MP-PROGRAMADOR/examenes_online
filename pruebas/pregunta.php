<?php
include '../modulos/componentes/head_admin.php';
include '../modulos/componentes/menu_admin.php';
require_once '../config/conexion.php';
 
$conn = $pdo->getConexion();
try {
    $stmt = $conn->prepare("SELECT id, titulo FROM examenes");
    $stmt->execute();
    $examenes = $stmt->fetchAll(PDO::FETCH_ASSOC);
}catch(PDOException $e){
    $mensaje_error = "hubo un error en la consulta ".$e;
}
?>
<div class="main-content">
    <div class="container-fluid mt-5 pt-2">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card shadow rounded-4 p-4">
                    <div class="card-header bg-primary text-white rounded-3 mb-4">
                        <h4 class="mb-0 d-flex align-items-center">
                            <i class="bi bi-pencil-square me-2"></i>
                            Registrar Examen
                        </h4>
                    </div>
                    <div class="card-body">
                        <!-- Mensaje de error -->
                        <?php if (!empty($mensaje_error)): ?>
                            <div class="alert alert-danger"><?= $mensaje_error ?></div>
                        <?php endif; ?>

            <div class="col-md-6">
                <label for="tipo_pregunta" class="form-label">Tipo de pregunta</label>
                <select name="tipo_pregunta" id="tipo_pregunta" class="form-select" required>
                    <option value="">Seleccione</option>
                    <option value="multiple_choice">Opción múltiple</option>
                    <option value="respuesta_unica">Respuesta única</option>
                    <option value="verdadero_falso">Verdadero / Falso</option>
                </select>
            </div>
        </div>

        <div class="row mb-3" id="contenedor_tipo_contenido" style="display:none;">
            <div class="col-md-6">
                <label for="tipo_contenido" class="form-label">Contenido</label>
                <select name="tipo_contenido" id="tipo_contenido" class="form-select">
                    <option value="">Seleccione</option>
                    <option value="texto">Solo texto</option>
                    <option value="ilustracion">Con ilustración</option>
                </select>
            </div>
        </div>

        <div class="mb-3" id="campo_texto_pregunta" style="display:none;">
            <label for="texto_pregunta" class="form-label">Texto de la pregunta</label>
            <textarea name="texto_pregunta" id="texto_pregunta" rows="3" class="form-control"></textarea>
        </div>

        <div class="mb-3" id="campo_imagenes" style="display:none;">
            <label for="imagenes" class="form-label">Ilustraciones</label>
            <input type="file" name="imagenes[]" class="form-control" multiple accept="image/*">
            <div class="form-text">Puede subir varias imágenes (jpg, png, gif).</div>
        </div>

        <div class="mb-3" id="campo_opciones" style="display:none;">
            <label class="form-label">Opciones</label>
            <div id="opciones_container"></div>
            <button type="button" class="btn btn-secondary btn-sm mt-2" id="agregar_opcion">Agregar opción</button>
        </div>

        <div class="mb-3" id="campo_verdadero_falso" style="display:none;">
            <label class="form-label">Respuesta correcta</label>
            <select name="es_correcta_vf" class="form-select">
                <option value="">Seleccione</option>
                <option value="verdadero">Verdadero</option>
                <option value="falso">Falso</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Guardar pregunta</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const tipoPregunta = document.getElementById('tipo_pregunta');
    const tipoContenido = document.getElementById('tipo_contenido');
    const contenedorContenido = document.getElementById('contenedor_tipo_contenido');
    const campoTexto = document.getElementById('campo_texto_pregunta');
    const campoImagenes = document.getElementById('campo_imagenes');
    const campoOpciones = document.getElementById('campo_opciones');
    const campoVF = document.getElementById('campo_verdadero_falso');
    const opcionesContainer = document.getElementById('opciones_container');
    const btnAgregarOpcion = document.getElementById('agregar_opcion');

    tipoPregunta.addEventListener('change', () => {
        const tipo = tipoPregunta.value;

        campoOpciones.style.display = 'none';
        campoVF.style.display = 'none';
        contenedorContenido.style.display = tipo ? 'block' : 'none';
        opcionesContainer.innerHTML = '';

        if (tipo === 'multiple_choice' || tipo === 'respuesta_unica') {
            campoOpciones.style.display = 'block';
            agregarOpcion();
            agregarOpcion();
        } else if (tipo === 'verdadero_falso') {
            campoVF.style.display = 'block';
        }
    });

    tipoContenido.addEventListener('change', () => {
        const contenido = tipoContenido.value;
        campoTexto.style.display = contenido ? 'block' : 'none';
        campoImagenes.style.display = contenido === 'ilustracion' ? 'block' : 'none';
    });

    btnAgregarOpcion.addEventListener('click', () => agregarOpcion());

    function agregarOpcion() {
        const numOpciones = opcionesContainer.children.length + 1;
        const div = document.createElement('div');
        div.className = 'input-group mb-2';

        div.innerHTML = `
            <span class="input-group-text">${numOpciones}</span>
            <input type="text" name="opcion[]" class="form-control" placeholder="Opción ${numOpciones}" required>
            <div class="input-group-text">
                <input type="radio" name="es_correcta" value="${numOpciones}" required>
            </div>
        `;
        opcionesContainer.appendChild(div);
    }
});
</script>
<?php include_once('../modulos/componentes/footer.php'); ?>