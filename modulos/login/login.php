 
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
                    <p>Realiza exámenes online de forma fácil y segura.</p>
                </div>
            </div>
            <div class="carousel-slide">
                <img src="imagen3.jpg" alt="Imagen de estudiantes colaborando">
                <div class="carousel-caption">
                    <h3>Conecta con tu Educación</h3>
                    <p>Únete a nuestra plataforma y mejora tu rendimiento académico.</p>
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