<!DOCTYPE html>
<html lang="es">


<?php


include '../componentes/head_admin.php';



?>

<body>

    <?php


    include '../componentes/menu_admin.php';



    ?>

    <div class="content ">
        <div class="top-bar">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNavbar"
                    aria-controls="topNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="topNavbar">
                    <ul class="navbar-nav ml-auto">
                        <!--  <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-bell"></i> <span class="badge bg-danger">3</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="alertsDropdown">
                                <li><a class="dropdown-item" href="#">Nueva inscripción de aspirante</a></li>
                                <li><a class="dropdown-item" href="#">Examen teórico finalizado</a></li>
                            </ul>
                        </li> -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle"></i> Admin User
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="perfil.html">Perfil</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="../login/logout.php">Cerrar Sesión</a></li>
                            </ul>
                        </li>

                    </ul>
                    <!--   <ul class="nav nav-tabs card-header-tabs float-end">

                        <li class="nav-item">
                            <a href="listar.php" class="btn btn-primary " type="button"><i class="fas fa-list me-2"></i> Visualizar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Opción
                                Deshabilitada</a>
                        </li>
                    </ul> -->
                </div>
            </nav>
        </div>
        <div class="container-fluid  mt-5 pt-2">

            <div class="row d-flex justify-content-center align-items-center">
                <div class="card p-5 mt-5 w-50">
                    <div class="form-container">
                    <div class="container">
        <h2 class="mb-4">Crear Nueva Pregunta</h2>
        <form action="procesar_pregunta.php" method="POST" class="needs-validation" novalidate>
            <div class="mb-3 form-group required">
                <label for="examen_id" class="form-label">Examen:</label>
                <select class="form-select" id="examen_id" name="examen_id" required>
                    <option value="">Seleccione un examen</option>
                    <?php
                    require_once '../../config/conexion.php';
                    $conn = $pdo->getConexion();
                    try {
                        $sqlExamenes = "SELECT id, titulo FROM examenes ORDER BY titulo ASC";
                        $stmtExamenes = $conn->prepare($sqlExamenes);
                        $stmtExamenes->execute();
                        $examenes = $stmtExamenes->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($examenes as $examen) {
                            echo '<option value="' . htmlspecialchars($examen['id']) . '">' . htmlspecialchars($examen['titulo']) . '</option>';
                        }
                    } catch (PDOException $e) {
                        echo '<option value="" disabled>Error al cargar los exámenes</option>';
                        error_log("Error al obtener exámenes: " . $e->getMessage());
                    }
                    ?>
                </select>
                <div class="invalid-feedback">Por favor, seleccione el examen al que pertenece la pregunta.</div>
            </div>
            <div class="mb-3 form-group required">
                <label for="texto_pregunta" class="form-label">Texto de la Pregunta:</label>
                <textarea class="form-control" id="texto_pregunta" name="texto_pregunta" rows="4" placeholder="Ingrese el texto de la pregunta" required></textarea>
                <div class="invalid-feedback">Por favor, ingrese el texto de la pregunta.</div>
            </div>
            <div class="mb-3 form-group required">
                <label for="tipo_pregunta" class="form-label">Tipo de Pregunta:</label>
                <select class="form-select" id="tipo_pregunta" name="tipo_pregunta" required onchange="toggleOpciones(this.value)">
                    <option value="">Seleccione el tipo de pregunta</option>
                    <option value="multiple_choice">Opción Múltiple</option>
                    <option value="verdadero_falso">Verdadero/Falso</option>
                    <option value="respuesta_unica">Respuesta Única</option>
                </select>
                <div class="invalid-feedback">Por favor, seleccione el tipo de pregunta.</div>
            </div>

            <div id="opciones-container">
                </div>

             

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-2"></i>Guardar Pregunta</button>
                <!-- <a href="listar_preguntas.php" class="btn btn-secondary"><i class="bi bi-arrow-left me-2"></i>Cancelar</a> -->
            </div>
        </form>
    </div>

    
                    </div>
                </div>
            </div>
        </div>


        <!-- Scripts optimizados -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script>
        (() => {
            'use strict'
            const forms = document.querySelectorAll('.needs-validation')
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()

        function toggleOpciones(tipo) {
            const opcionesContainer = document.getElementById('opciones-container');
            opcionesContainer.innerHTML = ''; // Limpiar cualquier opción anterior

            if (tipo === 'multiple_choice' || tipo === 'respuesta_unica') {
                const numOpciones = (tipo === 'multiple_choice') ? 4 : 1; // Ejemplo: 4 opciones para múltiple choice, 1 para única (se gestionará como múltiple con solo una correcta)
                for (let i = 1; i <= numOpciones; i++) {
                    const divOpcion = document.createElement('div');
                    divOpcion.classList.add('mb-3', 'form-group', 'required');
                    const labelOpcion = document.createElement('label');
                    labelOpcion.classList.add('form-label');
                    labelOpcion.setAttribute('for', `opcion${i}`);
                    labelOpcion.textContent = `Opción ${i}:`;
                    const inputOpcion = document.createElement('input');
                    inputOpcion.type = 'text';
                    inputOpcion.classList.add('form-control');
                    inputOpcion.id = `opcion${i}`;
                    inputOpcion.name = `opcion[${i}]`;
                    inputOpcion.required = true;
                    const invalidFeedbackOpcion = document.createElement('div');
                    invalidFeedbackOpcion.classList.add('invalid-feedback');
                    invalidFeedbackOpcion.textContent = `Por favor, ingrese la opción ${i}.`;

                    const divCorrecta = document.createElement('div');
                    divCorrecta.classList.add('form-check');
                    const inputCorrecta = document.createElement('input');
                    inputCorrecta.type = 'radio';
                    inputCorrecta.classList.add('form-check-input');
                    inputCorrecta.name = 'es_correcta';
                    inputCorrecta.value = i;
                    inputCorrecta.id = `correcta${i}`;
                    if (i === 1 && tipo === 'respuesta_unica') {
                        inputCorrecta.checked = true; // Marcar la primera como correcta por defecto para respuesta única
                    }
                    const labelCorrecta = document.createElement('label');
                    labelCorrecta.classList.add('form-check-label');
                    labelCorrecta.setAttribute('for', `correcta${i}`);
                    labelCorrecta.textContent = 'Correcta';

                    divOpcion.appendChild(labelOpcion);
                    divOpcion.appendChild(inputOpcion);
                    divOpcion.appendChild(invalidFeedbackOpcion);
                    divCorrecta.appendChild(inputCorrecta);
                    divCorrecta.appendChild(labelCorrecta);
                    divOpcion.appendChild(divCorrecta);

                    opcionesContainer.appendChild(divOpcion);
                }
            } else if (tipo === 'verdadero_falso') {
                const divVF = document.createElement('div');
                divVF.classList.add('mb-3');
                const divVerdadero = document.createElement('div');
                divVerdadero.classList.add('form-check');
                const inputVerdadero = document.createElement('input');
                inputVerdadero.type = 'radio';
                inputVerdadero.classList.add('form-check-input');
                inputVerdadero.name = 'es_correcta_vf';
                inputVerdadero.value = 'verdadero';
                inputVerdadero.id = 'verdadero';
                const labelVerdadero = document.createElement('label');
                labelVerdadero.classList.add('form-check-label');
                labelVerdadero.setAttribute('for', 'verdadero');
                labelVerdadero.textContent = 'Verdadero';

                const divFalso = document.createElement('div');
                divFalso.classList.add('form-check');
                const inputFalso = document.createElement('input');
                inputFalso.type = 'radio';
                inputFalso.classList.add('form-check-input');
                inputFalso.name = 'es_correcta_vf';
                inputFalso.value = 'falso';
                inputFalso.id = 'falso';
                const labelFalso = document.createElement('label');
                labelFalso.classList.add('form-check-label');
                labelFalso.setAttribute('for', 'falso');
                labelFalso.textContent = 'Falso';

                divVerdadero.appendChild(inputVerdadero);
                divVerdadero.appendChild(labelVerdadero);
                divFalso.appendChild(inputFalso);
                divFalso.appendChild(labelFalso);
                divVF.appendChild(divVerdadero);
                divVF.appendChild(divFalso);
                opcionesContainer.appendChild(divVF);
            }
        }
    </script>

</body>

</html>