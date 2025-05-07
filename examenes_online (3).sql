-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 07-05-2025 a las 15:22:37
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `examenes_online`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias_carne`
--

CREATE TABLE `categorias_carne` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `edad_minima` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categorias_carne`
--

INSERT INTO `categorias_carne` (`id`, `nombre`, `descripcion`, `edad_minima`) VALUES
(1, 'A', 'Motocicletas con o sin sidecar', 18),
(2, 'A1', 'Motocicletas ligeras hasta 125cc y 11kW', 16),
(3, 'A2', 'Motocicletas de potencia media hasta 35 kW', 18),
(4, 'B', 'Vehículos hasta 3.500 kg y 8 pasajeros', 18),
(5, 'B+E', 'Vehículos B con remolque mayor a 750 kg', 18),
(6, 'C', 'Vehículos pesados de más de 3.500 kg', 21),
(7, 'C1', 'Camiones entre 3.500 y 7.500 kg', 18),
(8, 'C+E', 'Camiones con remolque mayor a 750 kg', 21),
(9, 'D', 'Autobuses de más de 8 pasajeros', 24),
(10, 'D1', 'Autobuses pequeños hasta 16 pasajeros', 21),
(11, 'D+E', 'Autobuses con remolque mayor a 750 kg', 24),
(12, 'AM', 'Ciclomotores hasta 50cc y 45 km/h', 15),
(13, 'T', 'Vehículos agrícolas como tractores', 16);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuraciones_sistema`
--

CREATE TABLE `configuraciones_sistema` (
  `id` int(11) NOT NULL,
  `clave` varchar(100) NOT NULL,
  `valor` text NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `escuelas_conduccion`
--

CREATE TABLE `escuelas_conduccion` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `escuelas_conduccion`
--

INSERT INTO `escuelas_conduccion` (`id`, `nombre`, `direccion`, `telefono`, `email`) VALUES
(1, 'Nana mangue', 'Malabo', '222545658', NULL),
(2, 'Guinea Circula', 'Malabo', '222121415', NULL),
(3, 'Don pastor', 'Bata', '222141223', NULL),
(4, 'Mpa Sipaco', 'Bata', '555233669', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

CREATE TABLE `estudiantes` (
  `id` int(11) NOT NULL,
  `escuela_id` int(11) NOT NULL,
  `numero_identificacion` varchar(50) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellido` varchar(255) NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `categoria_carne_id` int(11) NOT NULL,
  `codigo_registro_examen` varchar(100) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `estudiantes`
--

INSERT INTO `estudiantes` (`id`, `escuela_id`, `numero_identificacion`, `nombre`, `apellido`, `fecha_nacimiento`, `telefono`, `direccion`, `categoria_carne_id`, `codigo_registro_examen`, `fecha_registro`) VALUES
(4, 2, '000174142', 'sir', 'topola', '2000-05-17', '555232558', 'Malabo', 1, 'EGU25136', '2025-05-07 08:10:58'),
(5, 4, '000144778', 'Mh', 'Bijeri', '2003-01-08', '222312556', 'Malabo', 4, 'EMP2542D', '2025-05-07 08:12:27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `examenes`
--

CREATE TABLE `examenes` (
  `id` int(11) NOT NULL,
  `categoria_carne_id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `total_preguntas` int(11) DEFAULT NULL,
  `preguntas_aleatorias` tinyint(1) DEFAULT 1,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `examenes`
--

INSERT INTO `examenes` (`id`, `categoria_carne_id`, `titulo`, `descripcion`, `total_preguntas`, `preguntas_aleatorias`, `fecha_creacion`) VALUES
(5, 1, 'TEORÍA DEDICADA NIVEL A', 'reforzamiento', 4, 1, '2025-05-07 07:56:25'),
(6, 4, 'TEORÍA DEDICADA NIVEL B', 'normativas de circulación', 3, 1, '2025-05-07 07:57:12'),
(7, 6, 'TEORÍA DEDICADA C', 'habilidades de manejo en circulación', 0, 1, '2025-05-07 07:57:58');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `examenes_estudiantes`
--

CREATE TABLE `examenes_estudiantes` (
  `id` int(11) NOT NULL,
  `estudiante_id` int(11) NOT NULL,
  `categoria_carne_id` int(11) NOT NULL,
  `fecha_asignacion` date NOT NULL DEFAULT curdate(),
  `fecha_realizacion` datetime DEFAULT NULL,
  `fecha_proximo_intento` date DEFAULT NULL,
  `estado` enum('pendiente','aprobado','reprobado') DEFAULT 'pendiente',
  `acceso_habilitado` tinyint(1) DEFAULT 0,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_preguntas` int(11) DEFAULT NULL,
  `intentos_examen` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `examenes_estudiantes`
--

INSERT INTO `examenes_estudiantes` (`id`, `estudiante_id`, `categoria_carne_id`, `fecha_asignacion`, `fecha_realizacion`, `fecha_proximo_intento`, `estado`, `acceso_habilitado`, `creado_en`, `total_preguntas`, `intentos_examen`) VALUES
(3, 4, 1, '2025-05-07', '2025-05-07 13:02:53', NULL, 'pendiente', 1, '2025-05-07 08:10:58', 4, 1),
(4, 5, 4, '2025-05-07', NULL, NULL, 'pendiente', 1, '2025-05-07 08:12:27', NULL, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `imagenes_pregunta`
--

CREATE TABLE `imagenes_pregunta` (
  `id` int(11) NOT NULL,
  `pregunta_id` int(11) NOT NULL,
  `ruta_imagen` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `intentos_examen`
--
 
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs_sistema`
--

CREATE TABLE `logs_sistema` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `accion` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `ip_origen` varchar(45) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opciones_pregunta`
--

CREATE TABLE `opciones_pregunta` (
  `id` int(11) NOT NULL,
  `pregunta_id` int(11) NOT NULL,
  `texto_opcion` text NOT NULL,
  `es_correcta` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `opciones_pregunta`
--

INSERT INTO `opciones_pregunta` (`id`, `pregunta_id`, `texto_opcion`, `es_correcta`) VALUES
(10, 15, 'Detenerse ', 1),
(11, 15, 'Ceder paso', 1),
(12, 15, 'Aumentar la velocidad', 0),
(13, 17, 'detenerse para observar y luego levantar con primera', 1),
(14, 17, 'ir a toda pastilla', 0),
(15, 17, 'mirar por el retrovisor mientras das la curva', 0),
(16, 18, 'es una tecnica para reducir la velocidad a la que va un vehículo', 1),
(17, 18, 'son pastillas de freno ', 1),
(18, 18, 'es eficiente para la reducción de la velocidad', 0),
(19, 19, 'es un elemento de la tabla periódica', 1),
(20, 19, 'es un perro', 1),
(21, 19, 'es un lenguaje de programación', 0),
(22, 19, 'es un lenguaje de alto nivel', 0),
(23, 21, 'es un nombre', 1),
(24, 21, 'es un adjetivo', 0),
(25, 21, 'es un mono', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas`
--

CREATE TABLE `preguntas` (
  `id` int(11) NOT NULL,
  `examen_id` int(11) NOT NULL,
  `texto_pregunta` text DEFAULT NULL,
  `tipo_contenido` enum('texto','ilustracion') NOT NULL,
  `tipo_pregunta` enum('unica','multiple','vf') NOT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `preguntas`
--

INSERT INTO `preguntas` (`id`, `examen_id`, `texto_pregunta`, `tipo_contenido`, `tipo_pregunta`, `fecha_creacion`) VALUES
(15, 5, 'el símbolo de stop en una villa indica:', 'texto', 'multiple', '2025-05-07 09:00:08'),
(16, 5, 'la luz de intermitente es fundamental en la circulación', 'texto', 'vf', '2025-05-07 09:01:01'),
(17, 5, 'entes de tomar una curva, es recomendable:', 'texto', 'unica', '2025-05-07 09:05:47'),
(18, 5, 'el freno motor', 'texto', 'multiple', '2025-05-07 09:07:53'),
(19, 6, 'Qué es js?', 'texto', 'multiple', '2025-05-07 09:14:17'),
(20, 6, 'php es un lenguaje de escritorio', 'texto', 'vf', '2025-05-07 09:15:04'),
(21, 6, 'la peste', 'texto', 'unica', '2025-05-07 09:15:55');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuestas_estudiante`
--

CREATE TABLE `respuestas_estudiante` (
  `id` int(11) NOT NULL,
  `intento_examen_id` int(11) NOT NULL,
  `pregunta_id` int(11) NOT NULL,
  `opcion_seleccionada_id` int(11) DEFAULT NULL,
  `respuesta_texto` text DEFAULT NULL,
  `es_correcta` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre_usuario` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('admin','docente') NOT NULL DEFAULT 'docente',
  `activo` tinyint(1) DEFAULT 1,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre_usuario`, `email`, `password`, `rol`, `activo`, `fecha_creacion`) VALUES
(1, 'sir', 'admin@gmail.com', '$2y$10$Dxhgt4jilwDqUmGWAcZqJ.dkWCl0EAqwLHGgqPIF7RLP43rrRtFb2', 'admin', 1, '2025-05-07 08:20:12');

--
-- Índices para tablas volcadas
--
-- --------------------------------------------------------
ALTER TABLE respuestas_estudiante
CHANGE intento_examen_id examenes_estudiantes_id INT(11) NOT NULL;

ALTER TABLE respuestas_estudiante
ADD CONSTRAINT fk_respuesta_examen_estudiante
FOREIGN KEY (examenes_estudiantes_id) REFERENCES examenes_estudiantes(id)
ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Indices de la tabla `categorias_carne`
--
ALTER TABLE `categorias_carne`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `configuraciones_sistema`
--
ALTER TABLE `configuraciones_sistema`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clave` (`clave`);

--
-- Indices de la tabla `escuelas_conduccion`
--
ALTER TABLE `escuelas_conduccion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_identificacion` (`numero_identificacion`),
  ADD UNIQUE KEY `codigo_registro_examen` (`codigo_registro_examen`),
  ADD KEY `escuela_id` (`escuela_id`),
  ADD KEY `categoria_carne_id` (`categoria_carne_id`);

--
-- Indices de la tabla `examenes`
--
ALTER TABLE `examenes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_carne_id` (`categoria_carne_id`);

--
-- Indices de la tabla `examenes_estudiantes`
--
ALTER TABLE `examenes_estudiantes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estudiante_id` (`estudiante_id`),
  ADD KEY `categoria_carne_id` (`categoria_carne_id`);

--
-- Indices de la tabla `imagenes_pregunta`
--
ALTER TABLE `imagenes_pregunta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pregunta_id` (`pregunta_id`);

--
 
-- Indices de la tabla `logs_sistema`
--
ALTER TABLE `logs_sistema`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `opciones_pregunta`
--
ALTER TABLE `opciones_pregunta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pregunta_id` (`pregunta_id`);

--
-- Indices de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `examen_id` (`examen_id`);

--
-- Indices de la tabla `respuestas_estudiante`
--
ALTER TABLE `respuestas_estudiante`
  ADD PRIMARY KEY (`id`),
  ADD KEY `intento_examen_id` (`intento_examen_id`),
  ADD KEY `pregunta_id` (`pregunta_id`),
  ADD KEY `opcion_seleccionada_id` (`opcion_seleccionada_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias_carne`
--
ALTER TABLE `categorias_carne`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `configuraciones_sistema`
--
ALTER TABLE `configuraciones_sistema`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `escuelas_conduccion`
--
ALTER TABLE `escuelas_conduccion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `examenes`
--
ALTER TABLE `examenes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `examenes_estudiantes`
--
ALTER TABLE `examenes_estudiantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `imagenes_pregunta`
--
ALTER TABLE `imagenes_pregunta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
 
-- AUTO_INCREMENT de la tabla `logs_sistema`
--
ALTER TABLE `logs_sistema`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `opciones_pregunta`
--
ALTER TABLE `opciones_pregunta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `respuestas_estudiante`
--
ALTER TABLE `respuestas_estudiante`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD CONSTRAINT `estudiantes_ibfk_1` FOREIGN KEY (`escuela_id`) REFERENCES `escuelas_conduccion` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `estudiantes_ibfk_2` FOREIGN KEY (`categoria_carne_id`) REFERENCES `categorias_carne` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `examenes`
--
ALTER TABLE `examenes`
  ADD CONSTRAINT `examenes_ibfk_1` FOREIGN KEY (`categoria_carne_id`) REFERENCES `categorias_carne` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `examenes_estudiantes`
--
ALTER TABLE `examenes_estudiantes`
  ADD CONSTRAINT `examenes_estudiantes_ibfk_1` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `examenes_estudiantes_ibfk_2` FOREIGN KEY (`categoria_carne_id`) REFERENCES `categorias_carne` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `imagenes_pregunta`
--
ALTER TABLE `imagenes_pregunta`
  ADD CONSTRAINT `imagenes_pregunta_ibfk_1` FOREIGN KEY (`pregunta_id`) REFERENCES `preguntas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `logs_sistema`
--
ALTER TABLE `logs_sistema`
  ADD CONSTRAINT `logs_sistema_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `opciones_pregunta`
--
ALTER TABLE `opciones_pregunta`
  ADD CONSTRAINT `opciones_pregunta_ibfk_1` FOREIGN KEY (`pregunta_id`) REFERENCES `preguntas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `preguntas`
--
ALTER TABLE `preguntas`
  ADD CONSTRAINT `preguntas_ibfk_1` FOREIGN KEY (`examen_id`) REFERENCES `examenes` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `respuestas_estudiante`
--
ALTER TABLE `respuestas_estudiante`
  ADD CONSTRAINT `respuestas_estudiante_ibfk_2` FOREIGN KEY (`pregunta_id`) REFERENCES `preguntas` (`id`),
  ADD CONSTRAINT `respuestas_estudiante_ibfk_3` FOREIGN KEY (`opcion_seleccionada_id`) REFERENCES `opciones_pregunta` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
