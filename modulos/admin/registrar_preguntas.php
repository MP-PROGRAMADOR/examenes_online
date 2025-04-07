<?php include '../includes/header.php'; ?>

<div class="container mt-4">
    <h2>Registrar Nueva Pregunta</h2>
    <?php if (!empty($mensaje_error)): ?>
        <div class="alert alert-danger"><?= $mensaje_error ?></div>
    <?php endif; ?>
    <form action="procesar_pregunta.php" method="POST" enctype="multipart/form-data" id="formPregunta">
        <div class="mb-3">
            <label for="examen_id" class="form-label">Examen:</label>
            <select name="examen_id" id="examen_id" class="form-select" required>
                <option value="">Seleccione un examen</option>
                <!-- Suponiendo que traes la lista de exámenes de la base de datos -->
                <?php
                $stmt = $conn->query("SELECT id, nombre FROM examenes");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value=\"{$row['id']}\">{$row['nombre']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="texto_pregunta" class="form-label">Texto de la pregunta:</label>
            <textarea name="texto_pregunta" id="texto_pregunta" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label for="imagen" class="form-label">Imagen (opcional):</label>
            <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
        </div>

        <div class="mb-3">
            <label for="tipo_pregunta" class="form-label">Tipo de Pregunta:</label>
            <select name="tipo_pregunta" id="tipo_pregunta" class="form-select" required>
                <option value="">Seleccione un tipo</option>
                <option value="multiple_choice">Opción múltiple</option>
                <option value="verdadero_falso">Verdadero / Falso</option>
                <option value="respuesta_unica">Respuesta única</option>
            </select>
        </div>

        <div id="opciones_container" class="mb-3" style="display: none;">
            <label class="form-label">Opciones:</label>
            <div id="opciones_dinamicas">
                <!-- Las opciones se generan dinámicamente con JS -->
            </div>
            <button type="button" class="btn btn-secondary btn-sm mt-2" id="agregarOpcion">+ Agregar opción</button>
        </div>

        <div id="verdadero_falso_container" class="mb-3" style="display: none;">
            <label class="form-label">Seleccione la respuesta correcta:</label>
            <div>
                <input type="radio" name="es_correcta_vf" value="verdadero" id="vf_verdadero">
                <label for="vf_verdadero">Verdadero</label>
            </div>
            <div>
                <input type="radio" name="es_correcta_vf" value="falso" id="vf_falso">
                <label for="vf_falso">Falso</label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Pregunta</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tipoPregunta = document.getElementById('tipo_pregunta');
    const opcionesContainer = document.getElementById('opciones_container');
    const opcionesDinamicas = document.getElementById('opciones_dinamicas');
    const agregarOpcionBtn = document.getElementById('agregarOpcion');
    const vfContainer = document.getElementById('verdadero_falso_container');

    function limpiarOpciones() {
        opcionesDinamicas.innerHTML = '';
    }

    function crearOpcion(index) {
        const div = document.createElement('div');
        div.classList.add('input-group', 'mb-2');

        const input = document.createElement('input');
        input.type = 'text';
        input.name = 'opcion[]';
        input.placeholder = `Opción ${index + 1}`;
        input.className = 'form-control';
        input.required = true;

        const radio = document.createElement('input');
        radio.type = 'radio';
        radio.name = 'es_correcta';
        radio.value = index + 1;
        radio.className = 'form-check-input ms-2 mt-2';

        const radioLabel = document.createElement('span');
        radioLabel.textContent = ' Correcta';
        radioLabel.className = 'ms-1 mt-2';

        div.appendChild(input);
        div.appendChild(radio);
        div.appendChild(radioLabel);

        return div;
    }

    function actualizarOpcionesIniciales() {
        limpiarOpciones();
        for (let i = 0; i < 2; i++) {
            opcionesDinamicas.appendChild(crearOpcion(i));
        }
    }

    tipoPregunta.addEventListener('change', () => {
        const tipo = tipoPregunta.value;
        limpiarOpciones();
        opcionesContainer.style.display = 'none';
        vfContainer.style.display = 'none';

        if (tipo === 'multiple_choice' || tipo === 'respuesta_unica') {
            opcionesContainer.style.display = 'block';
            actualizarOpcionesIniciales();
        } else if (tipo === 'verdadero_falso') {
            vfContainer.style.display = 'block';
        }
    });

    agregarOpcionBtn.addEventListener('click', () => {
        const currentCount = opcionesDinamicas.children.length;
        opcionesDinamicas.appendChild(crearOpcion(currentCount));
    });
});
</script>

<?php include '../includes/footer.php'; ?>
