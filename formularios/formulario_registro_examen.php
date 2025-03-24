<!-- Modal -->
<div class="modal fade row justify-content-center" id="preguntaModal" tabindex="-1" aria-labelledby="preguntaModalLabel" aria-hidden="true">
        <div class="modal-dialog col-md-8 grid-margin stretch-card">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="preguntaModalLabel">Registrar Pregunta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form id="preguntaForm">
                        <label for="tipoPregunta">Tipo de Pregunta:</label>
                        <select id="tipoPregunta" name="tipoPregunta" class="form-select">
                            <option value="opcionMultiple">Opción Múltiple</option>
                            <option value="verdaderoFalso">Verdadero/Falso</option>
                            <option value="respuestaCorta">Respuesta Corta</option>
                            <option value="ensayo">Ensayo</option>
                        </select>

                        <label for="textoPregunta">Texto de la Pregunta:</label>
                        <textarea id="textoPregunta" name="textoPregunta" rows="4" class="form-control"></textarea>

                        <div id="opcionesDiv">
                            <label for="opcion1">Opción 1:</label>
                            <input type="text" id="opcion1" name="opcion1" class="form-control">

                            <label for="opcion2">Opción 2:</label>
                            <input type="text" id="opcion2" name="opcion2" class="form-control">

                            <label for="opcion3">Opción 3:</label>
                            <input type="text" id="opcion3" name="opcion3" class="form-control">

                            <label for="opcion4">Opción 4:</label>
                            <input type="text" id="opcion4" name="opcion4" class="form-control">

                            <label for="respuestaCorrecta">Respuesta Correcta:</label>
                            <select id="respuestaCorrecta" name="respuestaCorrecta" class="form-select">
                                <option value="1">Opción 1</option>
                                <option value="2">Opción 2</option>
                                <option value="3">Opción 3</option>
                                <option value="4">Opción 4</option>
                            </select>
                        </div>

                        <label for="respuestaVerdaderoFalso" id="labelVerdaderoFalso" style="display: none;">Respuesta Correcta:</label>
                        <select id="respuestaVerdaderoFalso" name="respuestaVerdaderoFalso" class="form-select" style="display: none;">
                            <option value="verdadero">Verdadero</option>
                            <option value="falso">Falso</option>
                        </select>

                        <label for="respuestaCorta" id="labelRespuestaCorta" style="display: none;">Respuesta Correcta:</label>
                        <input type="text" id="respuestaCorta" name="respuestaCorta" class="form-control" style="display: none;">

                        <label for="respuestaEnsayo" id="labelRespuestaEnsayo" style="display: none;">Respuesta Correcta (Guía):</label>
                        <textarea id="respuestaEnsayo" name="respuestaEnsayo" rows="4" class="form-control" style="display: none;"></textarea>

                        <button type="submit" class="btn btn-primary mt-3">Guardar Pregunta</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

 

