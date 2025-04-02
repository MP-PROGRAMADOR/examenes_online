<?php


require '../../config/conexion/conexion.php';

$conn = $pdo->getConexion();



if($_SERVER['REQUEST_METHOD'] === 'POST'){

 

    if(!$usuario){
        echo 'porfavor el usuario es necesario';
    }



    if(!$password){
        echo 'porfavor el password es obligatorio';
    }

    if($usuario!=""){
        $sql="SELECT `usuarios`.`nombre_usuario`, `usuarios`.`password_usuario`, `roles`.`nombre` FROM `usuarios` LEFT JOIN `roles` ON `usuarios`.`id_rol` = `roles`.`id_rol` where nombre_usuario='${usuario}'";
        $sentencia = $conn->prepare($sql);
        $sentencia->bindParam(":email", $email, PDO::PARAM_STR);
        $sentencia->execute();
       $result= $sentencia->fetch(PDO::FETCH_ASSOC);
       

     

      if($resultado->num_rows){


        // verificar si el password es corecto

        $usuario= mysqli_fetch_assoc($resultado);

        $auth= password_verify($password, $usuario['password_usuario'] );

        var_dump($auth);

        echo 'usuario verificado';

        if($auth){
            // cuando existe el usuario y la contrasena

        

        $tipo_user=$usuario['nombre'];

        // cuando el nombre de usuario existe

        if($tipo_user=="DOCTOR"){

            session_start();

            $_SESSION['usuario']=$_POST['usuario'];
           

            header('Location: DOCTOR/index.php');

        }


        if($tipo_user=="ENFERMERA"){
            
            session_start();

           $_SESSION['usuario']=$_POST['usuario'];

            header('Location: ENFERMERA/index.php');

        }
        if($tipo_user=="LABORATORIO"){

            session_start();

            $_SESSION['usuario']=$_POST['usuario'];
            
            header('Location: LABORATORIO/pruebas.php');

        } 
        if($tipo_user=="ADMINISTRADOR"){

            session_start();

            $_SESSION['usuario']=$_POST['usuario'];
            
            header('Location: ADMINISTRADOR/index.php');

        }
        if($tipo_user=="PEDIATRIA"){

            session_start();

            $_SESSION['usuario']=$_POST['usuario'];
            
            header('Location: PEDIATRIA/doctor.php');

        }




        }else{

            echo 'la contrasena es incorecta';
        }

      }else{
        echo 'este usuario no existe';
      }
     
        }

    }

    $hora_actual = date("H");



    $hora = date("H:i:s");
    $hora2 = 13;
    $hora3 = 20;
     
    if($hora_actual < $hora2){
        $saludo= "Buenos días";
    }
    else if($hora_actual > $hora2 AND $hora_actual < $hora3){
        $saludo ="Buenas Tardes";
    }
    else{
        $saludo= "Buenas Noches";
    }


    $fecha_actual="";
    $vector = array(
      1 => $fecha_actual . " Nada nuevo hay bajo el sol, pero cuántas cosas viejas hay que no conocemos.",
      2 => $fecha_actual . " El verdadero amigo es aquel que está a tu lado cuando preferiría estar en otra parte.",
      3 => $fecha_actual . " La sabiduría es la hija de la experiencia.",
      4 => $fecha_actual . " Nunca hay viento favorable para el que no sabe hacia dónde va.",
      6 => $fecha_actual . " El único modo de hacer un gran trabajo es amar lo que haces - Steve Jobs",
      5 => $fecha_actual . " La felicidad es el verdadero sentimiento de plenitud que se consigue con el trabajo duro",
      7 => $fecha_actual . " Sé un punto de referencia de calidad. Algunas personas no están acostumbradas a un ambiente donde la excelencia es aceptada",
      8 => $fecha_actual . " La felicidad es el verdadero sentimiento de plenitud que se consigue con el trabajo duro",
      9 => $fecha_actual . " Si no haces que ocurran  cosas, las cosas te ocurrirán a ti",
      10 => $fecha_actual . " Trabajar en lo correcto es mucho más importante que trabajar duro",
      11 => $fecha_actual . " Los líderes son encantadores, generan mucha empatía, se ponen en el lugar del resto para saber cómo piensa y que le deben decir, utilizan bastante su inteligencia emocional",
      12 => $fecha_actual . " El trabajo obsesivo produce la locura, tanto como la pereza completa, pero con esta combinación se puede vivir",
      13 => $fecha_actual . " En medio de la dificultad yace la oportunidad",
      14 => $fecha_actual . " Los obstáculos son esas cosas espantosas que ves cuando quitas la mirada de tus metas",
      15 => $fecha_actual . " El hombre que mueve montañas comienza cargando pequeñas piedras",
      16 => $fecha_actual . " El fracaso no es lo opuesto al éxito: es parte del éxito",
      17 => $fecha_actual . " La habilidad es lo que eres capaz de hacer. La motivación determina lo que haces. La actitud determina qué tan bien lo haces",
      18 => $fecha_actual . " Somos lo que hacemos repetidamente. La excelencia, entonces, no es un acto, sino un hábito",
      19 => $fecha_actual . " No tienes que mirar toda la escalera. Para empezar, solo concéntrate en dar el primer paso",
      20 => $fecha_actual . " La felicidad no está en la mera posesión del dinero; radica en la alegría del logro, en la emoción del esfuerzo creativo",
      21 => $fecha_actual . " Haz lo único que crees que no puedes hacer. Falla en eso. Intenta otra vez. Hazlo mejor la segunda vez. Las únicas personas que nunca se caen son aquellas que nunca se suben a la cuerda floja",
      22 => $fecha_actual . " Nunca hay tiempo suficiente para hacerlo bien, pero siempre hay tiempo suficiente para hacerlo de nuevo",
      23 => $fecha_actual . " Enfócate en ser productivo en vez de enfocarte en estar ocupado",
      24 => $fecha_actual . " Trabajar en lo correcto es probablemente más importante que trabajar duro",
      25 => $fecha_actual . " El hombre no puede descubrir nuevos océanos a menos que tenga el coraje de perder de vista la costa",
      26 => $fecha_actual . " No aprendes a caminar siguiendo reglas. Aprendes haciendo y cayéndote",
      27 => $fecha_actual . " Los obstáculos no tienen por qué detenerte. Si te topas con una pared, no te des la vuelta y te rindas. Descubre cómo escalarla, atravesarla o sortearla",
      28 => $fecha_actual . " Nadie puede descubrirte hasta que tú lo hagas. Explota tus talentos, habilidades y fortalezas y haz que el mundo se siente y se dé cuenta",
      29 => $fecha_actual . " Si hay algo que te asusta, entonces podría significar que vale la pena intentarlo",
      30 => $fecha_actual . " El trabajo en equipo es el secreto que hace que gente común consiga resultados poco comunes",
      );
      $numero= rand(1,30);


    
?> 











<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso a Exámenes Online - Plataforma Educativa</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            /* Un fondo suave */
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .login-container {
            display: flex;
            width: 80%;
            /* Ajusta el ancho total del contenedor */
            max-width: 1200px;
            /* Ancho máximo para pantallas grandes */
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            /* Importante para el carrusel */
        }

        .carousel-container {
            flex: 1;
            position: relative;
            overflow: hidden;
        }

        .carousel-slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 0.8s ease-in-out;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            background-size: cover;
            background-position: center;
        }

        .carousel-slide.active {
            opacity: 1;
        }

        .carousel-slide img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
            /* Asegura que el texto esté encima */
        }

        .carousel-caption {
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.5);
            /* Fondo semitransparente para el texto */
            border-radius: 5px;
        }

        .carousel-caption h3 {
            margin-bottom: 10px;
            font-size: 2em;
            font-weight: bold;
        }

        .carousel-caption p {
            font-size: 1.1em;
        }

        .carousel-prev,
        .carousel-next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.3);
            color: white;
            border: none;
            padding: 15px;
            font-size: 1.5em;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .carousel-prev {
            left: 20px;
        }

        .carousel-next {
            right: 20px;
        }

        .carousel-prev:hover,
        .carousel-next:hover {
            background: rgba(0, 0, 0, 0.5);
        }

        .login-form {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .login-form h2 {
            margin-bottom: 30px;
            color: #333;
            font-size: 2.5em;
            font-weight: bold;
        }

        .form-group {
            width: 80%;
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1em;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #007bff;
            /* Color primario */
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .login-form button {
            width: 80%;
            padding: 14px 20px;
            background-color: #007bff;
            /* Color primario */
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1em;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .login-form button:hover {
            background-color: #0056b3;
        }

        .form-links {
            margin-top: 20px;
            font-size: 0.9em;
            color: #777;
        }

        .form-links a {
            color: #007bff;
            text-decoration: none;
            margin: 0 10px;
        }

        .form-links a:hover {
            text-decoration: underline;
        }
        .error-alert {
            color: red;
            margin-bottom: 10px;
            border: 1px solid red;
            padding: 10px;
            background-color: #ffe0e0;
            border-radius: 5px;
        }

        /* Estilos para pantallas más pequeñas (opcional) */
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }

            .carousel-container {
                height: 300px;
                /* Altura fija para el carrusel en pantallas pequeñas */
            }

            .login-form {
                padding: 30px;
            }

            .form-group {
                width: 90%;
            }

            .login-form button {
                width: 90%;
            }
        }
    </style>
</head>
<body>
    <?php 
        
    ?>
    <div class="login-container">
        <div class="carousel-container">
            <div class="carousel-slide">
                <img src="imagen1.jpg" alt="Imagen de estudio y aprendizaje">
                <div class="carousel-caption">
                    <h3>Potencia tu Aprendizaje</h3>
                    <p>Accede a exámenes diseñados para evaluar tu conocimiento.</p>
                </div>
            </div>
            <div class="carousel-slide">
                <img src="imagen2.jpg" alt="Imagen de examen en pantalla">
                <div class="carousel-caption">
                    <h3>Evalúa tu Progreso</h3>
                    <p><?php  echo $numero;  ?></p>
                </div>
            </div>
            <div class="carousel-slide">
                <img src="imagen3.jpg" alt="Imagen de estudiantes colaborando">
                <div class="carousel-caption">
                    <h3>Conecta con tu Educación</h3>
                    <p><?php  echo $numero;  ?></p>
                </div>
            </div>
            <button class="carousel-prev">&#10094;</button>
            <button class="carousel-next">&#10095;</button>
        </div>
        <div class="login-form">
            <h2>Acceso a Exámenes Online</h2>
            <p class="form-subtitle">Inicia sesión para continuar con tus evaluaciones.</p>
            <form action="controller_login.php" method="post">
                <div class="form-group">
                    <label for="email">Correo Electrónico:</label>
                    <input type="email" id="email" name="email" required placeholder="Ingresa tu correo institucional">
                </div>
                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" required placeholder="Ingresa tu contraseña">
                </div>
                <button type="submit">Acceder al Examen</button>
                <div class="form-links">
                    <a href="#">¿Olvidaste tu contraseña?</a>
                    <a href="#">Contactar con los responsables</a>
                </div>
            </form>
        </div>
        
    </div>
    <script src="script.js"></script>
    <script>
        const carouselSlides = document.querySelectorAll('.carousel-slide');
        const prevButton = document.querySelector('.carousel-prev');
        const nextButton = document.querySelector('.carousel-next');

        let currentSlide = 0;

        function showSlide(index) {
            carouselSlides.forEach((slide, i) => {
                slide.classList.remove('active');
                if (i === index) {
                    slide.classList.add('active');
                }
            });
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % carouselSlides.length;
            showSlide(currentSlide);
        }

        function prevSlide() {
            currentSlide = (currentSlide - 1 + carouselSlides.length) % carouselSlides.length;
            showSlide(currentSlide);
        }

        // Mostrar la primera slide al cargar la página
        showSlide(currentSlide);

        // Event listeners para los botones
        nextButton.addEventListener('click', nextSlide);
        prevButton.addEventListener('click', prevSlide);

        // Opcional: Autoplay del carrusel
        // setInterval(nextSlide, 5000); // Cambia cada 5 segundos
    </script>
</body>
</html>


<!--
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Elegante con Carrusel</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            /* Un fondo suave */
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .login-container {
            display: flex;
            width: 80%;
            /* Ajusta el ancho total del contenedor */
            max-width: 1200px;
            /* Ancho máximo para pantallas grandes */
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            /* Importante para el carrusel */
        }

        .carousel-container {
            flex: 1;
            position: relative;
            overflow: hidden;
        }

        .carousel-slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 0.8s ease-in-out;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            background-size: cover;
            background-position: center;
        }

        .carousel-slide.active {
            opacity: 1;
        }

        .carousel-slide img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
            /* Asegura que el texto esté encima */
        }

        .carousel-caption {
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.5);
            /* Fondo semitransparente para el texto */
            border-radius: 5px;
        }

        .carousel-caption h3 {
            margin-bottom: 10px;
            font-size: 2em;
            font-weight: bold;
        }

        .carousel-caption p {
            font-size: 1.1em;
        }

        .carousel-prev,
        .carousel-next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.3);
            color: white;
            border: none;
            padding: 15px;
            font-size: 1.5em;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .carousel-prev {
            left: 20px;
        }

        .carousel-next {
            right: 20px;
        }

        .carousel-prev:hover,
        .carousel-next:hover {
            background: rgba(0, 0, 0, 0.5);
        }

        .login-form {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .login-form h2 {
            margin-bottom: 30px;
            color: #333;
            font-size: 2.5em;
            font-weight: bold;
        }

        .form-group {
            width: 80%;
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1em;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #007bff;
            /* Color primario */
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .login-form button {
            width: 80%;
            padding: 14px 20px;
            background-color: #007bff;
            /* Color primario */
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1em;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .login-form button:hover {
            background-color: #0056b3;
        }

        .form-links {
            margin-top: 20px;
            font-size: 0.9em;
            color: #777;
        }

        .form-links a {
            color: #007bff;
            text-decoration: none;
            margin: 0 10px;
        }

        .form-links a:hover {
            text-decoration: underline;
        }

        /* Estilos para pantallas más pequeñas (opcional) */
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }

            .carousel-container {
                height: 300px;
                /* Altura fija para el carrusel en pantallas pequeñas */
            }

            .login-form {
                padding: 30px;
            }

            .form-group {
                width: 90%;
            }

            .login-form button {
                width: 90%;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="carousel-container">
            <div class="carousel-slide">
                <img src="imagen1.jpg" alt="Imagen 1">
                <div class="carousel-caption">
                    <h3>Bienvenido a Nuestra Plataforma</h3>
                    <p>Descubre una nueva experiencia.</p>
                </div>
            </div>
            <div class="carousel-slide">
                <img src="imagen2.jpg" alt="Imagen 2">
                <div class="carousel-caption">
                    <h3>Conéctate y Explora</h3>
                    <p>Encuentra lo que necesitas.</p>
                </div>
            </div>
            <div class="carousel-slide">
                <img src="imagen3.jpg" alt="Imagen 3">
                <div class="carousel-caption">
                    <h3>Únete a Nuestra Comunidad</h3>
                    <p>Forma parte de algo grande.</p>
                </div>
            </div>
            <button class="carousel-prev">&#10094;</button>
            <button class="carousel-next">&#10095;</button>
        </div>
        <div class="login-form">
            <h2>Iniciar Sesión</h2>
            <form>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required placeholder="Ingresa tu email">
                </div>
                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" required placeholder="Ingresa tu contraseña">
                </div>
                <button type="submit">Entrar</button>
                <div class="form-links">
                    <a href="#">¿Olvidaste tu contraseña?</a>
                    <a href="#">Registrarse</a>
                </div>
            </form>
        </div>
    </div>
    <script src="script.js"></script>

    <script>
        const carouselSlides = document.querySelectorAll('.carousel-slide');
        const prevButton = document.querySelector('.carousel-prev');
        const nextButton = document.querySelector('.carousel-next');

        let currentSlide = 0;

        function showSlide(index) {
            carouselSlides.forEach((slide, i) => {
                slide.classList.remove('active');
                if (i === index) {
                    slide.classList.add('active');
                }
            });
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % carouselSlides.length;
            showSlide(currentSlide);
        }

        function prevSlide() {
            currentSlide = (currentSlide - 1 + carouselSlides.length) % carouselSlides.length;
            showSlide(currentSlide);
        }

        // Mostrar la primera slide al cargar la página
        showSlide(currentSlide);

        // Event listeners para los botones
        nextButton.addEventListener('click', nextSlide);
        prevButton.addEventListener('click', prevSlide);

        // Opcional: Autoplay del carrusel
        // setInterval(nextSlide, 5000); // Cambia cada 5 segundos
    </script>
</body>

</html>
    -->