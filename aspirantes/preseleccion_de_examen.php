<?php 
//seguridad de sessiones paginacion
session_start();
error_reporting(0);
$versesion = $_SESSION['usuario_rol']; 

if ($versesion == '' || $versesion == null) {
    header('location: ../login/login.php');
    die();
}
if ($versesion == 'docente') {
    header('../examinador/index_examinador.php');
    die();
}
if ($versesion == 'admin') {
    header('../admin/index_admin.php');
    die();
}

?>
<!DOCTYPE html>
 <html lang="es">

 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Plataforma de Exámenes Online - Autoescuela GE</title>
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
     <link rel="stylesheet" href="../css/styles_empezar.css">
     <style>
         /* Estilos adicionales para mejorar la apariencia */
         .header-shadow {
             box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
         }

         .navbar-brand i {
             margin-right: 0.2em;
         }

        
         .carousel-inner {
             padding: 1rem;
         }

         .carousel-item {
             background-color: #f8f9fa;
             border-radius: 0.25rem;
             padding: .2rem;
             text-align: center;
         }
 
        

         

          
        
        
         /* Estilos para las cards de motivación */
        

         
     </style>
 </head>

 <body class="d-flex flex-column min-vh-100">
     <header class="bg-white header-shadow">
         <nav class="navbar navbar-expand-lg navbar-light container">
             <a class="navbar-brand" href="#">
                 <i class="fas fa-graduation-cap fa-lg"></i> Plataforma de Exámenes Online
             </a>
             <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                 aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                 <span class="navbar-toggler-icon"></span>
             </button>
             <div class="collapse navbar-collapse" id="navbarNav">
                 <ul class="navbar-nav ml-auto">
                     <li class="nav-item active">
                         <a class="nav-link" href="../index.php"><i class="fas fa-home"></i> Inicio</a>
                     </li>
                 </ul>
             </div>
         </nav>
     </header>

     <main class="container my-5 flex-grow-1">
         <section class="text-center mb-5">
             <h1 class="display-4 text-primary"><i class="fas fa-check-circle fa-lg mr-2"></i> ¡Accede al Examen Oficial
                 con Confianza!</h1>
             <p class="lead text-muted">Selecciona la opción que deseas realizar. Recuerda, el **Examen Teórico** es el
                 examen oficial que te preparará para obtener tu licencia.</p>
         </section>
         <div class="row">
             <div class="col-md-6">
                 <div class="card exam-card">
                     <div class="card-header bg-white text-info">
                         <h5 class="card-title mb-0"><i class="fas fa-book-open"></i> Examen Teórico Oficial</h5>
                     </div>
                     <div id="examenOficialCarousel" class="carousel slide" data-ride="carousel">
                         <ol class="carousel-indicators">
                             <li data-target="#examenOficialCarousel" data-slide-to="0" class="active"></li>
                             <li data-target="#examenOficialCarousel" data-slide-to="1"></li>
                             <li data-target="#examenOficialCarousel" data-slide-to="2"></li>
                         </ol>
                         <div class="carousel-inner">
                             <div class="carousel-item active">
                                 <div class="card shadow carousel-card bg-light text-dark">
                                     <div class="card-body">
                                         <i class="fas fa-file-alt fa-4x"></i>
                                         <h5 class="card-title font-weight-bold">Examen Teórico Oficial - Enfoque en la
                                             Norma</h5>
                                         <p class="card-text">Prepárate para el **examen oficial** de conducir,
                                             centrándote en la comprensión profunda de las normas y reglamentos.</p>
                                         <ul class="list-unstyled">
                                             <li><i class="fas fa-gavel mr-2"></i> Legislación y normativas de tráfico.
                                             </li>
                                             <li><i class="fas fa-road mr-2"></i> Señales de tráfico y su significado.
                                             </li>
                                             <li><i class="fas fa-exclamation-circle mr-2"></i> Prioridades y reglas de
                                                 circulación.</li>
                                         </ul>
                                         <a href="./seleccionar_examen.php" class="btn-light btn-lg border bg-success"><i
                                                 class="fas fa-play mr-2"></i> Comenzar Examen Oficial</a>
                                     </div>
                                 </div>
                             </div>
                             <div class="carousel-item">
                                 <div class="card shadow carousel-card bg-light text-dark">
                                     <div class="card-body">
                                         <i class="fas fa-brain fa-4x"></i>
                                         <h5 class="card-title font-weight-bold">Examen Teórico Oficial - Enfoque
                                             Práctico</h5>
                                         <p class="card-text">Aborda el **examen oficial** pensando en situaciones reales
                                             de conducción y cómo aplicar las normas en la práctica.</p>
                                         <ul class="list-unstyled">
                                             <li><i class="fas fa-car mr-2"></i> Conducción segura y eficiente.</li>
                                             <li><i class="fas fa-user-shield mr-2"></i> Seguridad vial y prevención de
                                                 accidentes.</li>
                                             <li><i class="fas fa-first-aid mr-2"></i> Primeros auxilios básicos en caso
                                                 de accidente.</li>
                                         </ul>
                                         <a href="./seleccionar_examen.php" class="btn-light btn-lg border bg-success"><i
                                                 class="fas fa-play mr-2"></i> Comenzar Examen Oficial</a>
                                     </div>
                                 </div>
                             </div>
                             <div class="carousel-item">
                                 <div class="card shadow carousel-card bg-light text-dark">
                                     <div class="card-body">
                                         <i class="fas fa-clock fa-4x"></i>
                                         <h5 class="card-title font-weight-bold">Examen Teórico Oficial - Simulación Real
                                         </h5>
                                         <p class="card-text">Experimenta el **examen oficial** en un entorno simulado
                                             con tiempo limitado, preparándote para la presión del día de la prueba.</p>
                                         <ul class="list-unstyled">
                                             <li><i class="fas fa-stopwatch mr-2"></i> Gestión del tiempo durante el
                                                 examen.</li>
                                             <li><i class="fas fa-check-circle mr-2"></i> Familiarización con el formato
                                                 de las preguntas.</li>
                                             <li><i class="fas fa- нервы mr-2"></i> Estrategias para mantener la calma y
                                                 la concentración.</li>
                                         </ul>
                                         <a href="./seleccionar_examen.php" class="btn btn-light btn-lg b"><i
                                                 class="fas fa-play mr-2"></i> Comenzar Examen Oficial</a>
                                     </div>
                                 </div>
                             </div>
                         </div>
                         <a class="carousel-control-prev" href="#examenOficialCarousel" role="button" data-slide="prev">
                             <span class="carousel-control-prev-icon bg-secondary rounded-circle"
                                 aria-hidden="true"></span>
                             <span class="sr-only">Anterior</span>
                         </a>
                         <a class="carousel-control-next" href="#examenOficialCarousel" role="button" data-slide="next">
                             <span class="carousel-control-next-icon bg-secondary rounded-circle"
                                 aria-hidden="true"></span>
                             <span class="sr-only">Siguiente</span>
                         </a>
                     </div>
                 </div>
             </div>

             <div class="col-md-6">
                <div class="card exam-card">
                    <div class="card-header style=" background-color: #28a745;" text-white>
                        <h5 class="card-title mb-0 text-info"><i class="fas fa-clipboard-check"></i> Simulacro de Examen Completo
                        </h5>
                    </div>
                    <div id="simulacroCarousel" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#simulacroCarousel" data-slide-to="0" class="active"></li>
                            <li data-target="#simulacroCarousel" data-slide-to="1"></li>
                            <li data-target="#simulacroCarousel" data-slide-to="2"></li>
                            <li data-target="#simulacroCarousel" data-slide-to="3"></li>
                        </ol>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <div class="card shadow carousel-card bg-light text-dar">
                                    <div class="card-body">
                                        <i class="fas fa-clipboard-check fa-4x"></i>
                                        <h5 class="card-title font-weight-bold">Simulacro Completo - Evaluación General
                                        </h5>
                                        <p class="card-text">Evalúa tu conocimiento general de todos los temas del examen con este simulacro completo.</p>
                                        <ul class="list-unstyled">
                                            <li><i class="fas fa-balance-scale mr-2"></i> Preguntas variadas de todas las áreas temáticas.</li>
                                            <li><i class="fas fa-percentage mr-2"></i> Cálculo de tu porcentaje de aciertos.</li>
                                            <li><i class="fas fa-chart-line mr-2"></i> Seguimiento de tu progreso a lo largo del tiempo.</li>
                                        </ul>
                                        <a href="./politicas_del_simulacro.php" class="btn-light btn-lg border bg-success"><i class="fas fa-flag-checkered mr-2"></i> Iniciar Simulacro</a>
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="card shadow carousel-card bg-light text-dar">
                                    <div class="card-body">
                                        <i class="fas fa-search fa-4x"></i>
                                        <h5 class="card-title font-weight-bold">Simulacro Completo - Detección de Áreas Débiles
                                        </h5>
                                        <p class="card-text">Identifica tus áreas de oportunidad con un simulacro diseñado para mostrar dónde necesitas reforzar tus estudios.</p>
                                        <ul class="list-unstyled">
                                            <li><i class="fas fa-map-marked-alt mr-2"></i> Identificación de temas con menor rendimiento.</li>
                                            <li><i class="fas fa-lightbulb mr-2"></i> Sugerencias de estudio personalizado.</li>
                                            <li><i class="fas fa-redo mr-2"></i> Opción para enfocar la práctica en áreas específicas.</li>
                                        </ul>
                                        <a href="./politicas_del_simulacro.php" class="btn-light btn-lg border bg-success"><i class="fas fa-flag-checkered mr-2"></i> Iniciar Simulacro</a>
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="card shadow carousel-card bg-light text-dar">
                                    <div class="card-body">
                                        <i class="fas fa-chart-bar fa-4x"></i>
                                        <h5 class="card-title font-weight-bold">Simulacro Completo - Análisis de Rendimiento
                                        </h5>
                                        <p class="card-text">Analiza detalladamente tu rendimiento después de cada simulacro para entender tus fortalezas y debilidades.</p>
                                        <ul class="list-unstyled">
                                            <li><i class="fas fa-percentage mr-2"></i> Porcentaje de respuestas correctas e incorrectas.</li>
                                            <li><i class="fas fa-clock mr-2"></i> Tiempo promedio por pregunta.</li>
                                            <li><i class="fas fa-star mr-2"></i> Resumen de tu desempeño por tema.</li>
                                        </ul>
                                        <a href="./politicas_del_simulacro.php" class="btn-light btn-lg border bg-success"><i class="fas fa-flag-checkered mr-2"></i> Iniciar Simulacro</a>
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="card shadow carousel-card bg-light text-dar">
                                    <div class="card-body">
                                        <i class="fas fa-brain fa-4x"></i>
                                        <h5 class="card-title font-weight-bold">Simulacro Completo - Estrategias de Examen</h5>
                                        <p class="card-text">Aprende y practica estrategias efectivas para abordar las preguntas del examen y optimizar tu tiempo.</p>
                                        <ul class="list-unstyled">
                                            <li><i class="fas fa-hourglass-half mr-2"></i> Técnicas para gestionar el tiempo durante el examen.</li>
                                            <li><i class="fas fa-list-alt mr-2"></i> Cómo identificar rápidamente la información clave en las preguntas.</li>
                                            <li><i class="fas fa-check-double mr-2"></i> Estrategias para revisar tus respuestas de manera eficiente.</li>
                                        </ul>
                                        <a href="./politicas_del_simulacro.php" class="btn-light btn-lg border bg-success"><i class="fas fa-flag-checkered mr-2"></i> Iniciar Simulacro</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a class="carousel-control-prev" href="#simulacroCarousel" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon bg-secondary rounded-circle" aria-hidden="true"></span>
                            <span class="sr-only">Anterior</span>
                        </a>
                        <a class="carousel-control-next" href="#simulacroCarousel" role="button" data-slide="next">
                            <span class="carousel-control-next-icon bg-secondary rounded-circle" aria-hidden="true"></span>
                            <span class="sr-only">Siguiente</span>
                        </a>
                    </div>
                </div>
            </div>
            <section class="my-5">
                <div class="card bg-info text-white shadow">
                    <div class="card-body">
                        <h2 class="card-title"><i class="fas fa-exclamation-triangle mr-2"></i> Importante: Preparación con
                            Disciplina</h2>
                        <p class="card-text">Recuerda que la clave del éxito en tu examen de conducir reside en la
                            disciplina y la constancia en tu preparación. Dedica tiempo regularmente al estudio y la
                            práctica.</p>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-calendar-alt mr-2"></i> Establece un horario de estudio regular.</li>
                            <li><i class="fas fa-lightbulb mr-2"></i> Repasa los temas con atención y profundidad.</li>
                            <li><i class="fas fa-question-circle mr-2"></i> No dudes en preguntar a tu instructor cualquier
                                duda.</li>
                        </ul>
                    </div>
                </div>
            </section>
       
            <section class="my-5 container">
                <div class="card bg-success text-white shadow">
                    <div class="card-body">
                        <h2 class="card-title"><i class="fas fa-thumbs-up mr-2"></i> Actitud Positiva: ¡Tú Puedes Lograrlo!
                        </h2>
                        <p class="card-text">Mantén una actitud positiva y confía en tus capacidades. Visualiza el éxito y
                            aborda el examen con seguridad.</p>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-smile mr-2"></i> Cree en tu preparación y en tu potencial.</li>
                            <li><i class="fas fa-heartbeat mr-2"></i> Controla los nervios y la ansiedad.</li>
                            <li><i class="fas fa-trophy mr-2"></i> Recuerda tu objetivo y mantente motivado.</li>
                        </ul>
                    </div>
                </div>
            </section>
        </main>
       
        <footer class="bg-dark py-4">
            <div class="container">
                <div class="row">
                    <div class="col-md-8">
                        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                                <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                            </ol>
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <div class="card carousel-card">
                                        <div class="card-body">
                                            <h5 class="card-title text-info"><i class="fas fa-check-circle"></i> Practica
                                                sin límites</h5>
                                            <p class="card-text">Accede a una amplia base de preguntas y practica cuantas
                                                veces necesites para dominar el temario.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <div class="card carousel-card">
                                        <div class="card-body">
                                            <h5 class="card-title text-success"><i class="fas fa-chart-line"></i>
                                                Seguimiento de tu progreso</h5>
                                            <p class="card-text">Analiza tus resultados y observa tu evolución a medida que
                                                avanzas en tu preparación.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <div class="card carousel-card">
                                        <div class="card-body">
                                            <h5 class="card-title text-warning"><i class="fas fa-clock"></i> A tu ritmo</h5>
                                            <p class="card-text">Estudia y practica cuando y donde quieras, adaptándonos a
                                                tu horario.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button"
                                data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Anterior</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button"
                                data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Siguiente</span>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card footer-card text-center">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-play-circle"></i> ¿Listo para comenzar?</h5>
                                <p class="card-text">¡Inicia tu camino hacia el éxito en el examen de conducir!</p>
       
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <h5><i class="fas fa-info-circle mr-2"></i> Sobre Nosotros</h5>
                            <p class="text-muted">Somos una plataforma dedicada a ayudarte a superar tu examen de conducir.
                                Nuestro objetivo es proporcionarte las herramientas necesarias para que te prepares de
                                manera efectiva y segura.</p>
                        </div>
                        <div class="col-md-6 text-md-right">
                            <h5>Síguenos</h5>
                            <a href="#" class="text-white mr-3"><i class="fab fa-facebook fa-lg"></i></a>
                            <a href="#" class="text-white mr-3"><i class="fab fa-twitter fa-lg"></i></a>
                            <a href="#" class="text-white"><i class="fab fa-instagram fa-lg"></i></a>
                        </div>
                    </div>
                    <hr class="bg-secondary my-3">
                    <p class="text-center mb-0">&copy; 2024 Autoescuela Online GE - ¡Disciplina y Actitud al Volante!</p>
                </div>
            </div>
        </footer>
       
       
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script>
            // No hay JavaScript específico en este HTML que afecte directamente la funcionalidad principal.
            // El JavaScript que se incluye son las dependencias de Bootstrap y posiblemente un archivo 'script.js' externo.
            // Si hubiera lógica específica para los carousels o alguna otra interacción, estaría aquí o en 'script.js'.
       
            $(document).ready(function() {
                // Inicializar los carousels de Bootstrap
                $('#examenOficialCarousel').carousel();
                $('#simulacroCarousel').carousel();
                $('#carouselExampleIndicators').carousel();
            });
        </script>
        </body>
       
        </html>
