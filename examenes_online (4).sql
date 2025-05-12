-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-05-2025 a las 10:22:47
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

drop database if exists examenes_online;
create database examenes_online;
use examenes_online;


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
(6, 4, 'TEORÍA DEDICADA NIVEL B', 'normativas de circulación', 10, 1, '2025-05-07 07:57:12'),
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
  `intentos_examen` int(11) NOT NULL DEFAULT 0,
  `calificacion` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `examenes_estudiantes`
--

INSERT INTO `examenes_estudiantes` (`id`, `estudiante_id`, `categoria_carne_id`, `fecha_asignacion`, `fecha_realizacion`, `fecha_proximo_intento`, `estado`, `acceso_habilitado`, `creado_en`, `total_preguntas`, `intentos_examen`, `calificacion`) VALUES
(3, 4, 1, '2025-05-07', '2025-05-07 13:02:53', NULL, 'pendiente', 1, '2025-05-07 08:10:58', 4, 4, NULL),
(4, 5, 4, '2025-05-07', '2025-05-09 08:52:44', '2025-05-08', 'reprobado', 1, '2025-05-07 08:12:27', 5, 22, 60);

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

CREATE TABLE `intentos_examen` (
  `id` int(11) NOT NULL,
  `examen_estudiante_id` int(11) DEFAULT NULL,
  `estudiante_id` int(11) NOT NULL,
  `examen_id` int(11) NOT NULL,
  `fecha_inicio` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_fin` timestamp NULL DEFAULT NULL,
  `completado` tinyint(1) DEFAULT 0,
  `codigo_acceso_utilizado` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(13, 17, 'detenerse para observar y luego levantar con primera', 1),
(14, 17, 'ir a toda pastilla', 0),
(15, 17, 'mirar por el retrovisor mientras das la curva', 0),
(32, 32, 'en una rotonda tiene prioridad el que ya esta en ella', 1),
(33, 33, 'para adelantar debes llevar un velocidad superior ', 1),
(34, 34, 'no siempre es recomendable poner el cinturón de seguridad', 1),
(35, 35, 'antes de nada debes atarte el cinturón de seguridad', 1),
(36, 36, 'en una curva siempre debes reducir la velocidad', 1),
(37, 37, 'para una mejor conducción, evite adelantamientos en curvas', 1),
(38, 38, 'usa siempre gafas oscuras frente el volante', 1),
(59, 44, 'salir directamente de primera a quinta', 1),
(60, 44, 'fumar al volante', 1),
(61, 44, 'estar sereno frente el volante', 0),
(62, 44, 'controlar la velocidad', 0),
(63, 44, 'cumplir con las normas ', 0),
(64, 44, 'comer al volante', 1),
(65, 45, 'Indica tener prioridad', 0),
(66, 45, 'indica que debo avanzar', 0),
(67, 45, 'indica que sin importar si veo o no a nadie debo detenerme', 1),
(68, 45, 'me dice que continúe por otro lado', 0),
(69, 46, 'tener la documentación siempre en vigor', 1),
(70, 46, 'respetar las señales', 1),
(71, 46, 'fumar frente el volante', 0),
(72, 46, 'ir a 120km/s en en la ciudad', 0),
(73, 46, 'saltar el semáforo cuando esta en rojo ', 0),
(74, 46, 'ir siempre con el cinturón de seguridad', 1),
(75, 46, 'ceder paso aunque tengas preferencia', 1);

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
(17, 5, 'entes de tomar una curva, es recomendable:', 'texto', 'unica', '2025-05-07 09:05:47'),
(32, 6, 'en una rotonda tiene prioridad el que ya esta en ella', 'texto', 'vf', '2025-05-08 10:56:41'),
(33, 6, 'para adelantar debes llevar un velocidad superior ', 'texto', 'vf', '2025-05-08 10:57:20'),
(34, 6, 'no siempre es recomendable poner el cinturón de seguridad', 'texto', 'vf', '2025-05-08 10:57:55'),
(35, 6, 'antes de nada debes atarte el cinturón de seguridad', 'texto', 'vf', '2025-05-08 10:58:26'),
(36, 6, 'en una curva siempre debes reducir la velocidad', 'texto', 'vf', '2025-05-08 10:58:58'),
(37, 6, 'para una mejor conducción, evite adelantamientos en curvas', 'texto', 'vf', '2025-05-08 10:59:30'),
(38, 6, 'usa siempre gafas oscuras frente el volante', 'texto', 'vf', '2025-05-08 12:01:32'),
(44, 6, 'malas practicas', 'texto', 'multiple', '2025-05-08 13:01:50'),
(45, 6, 'la señal stop', 'texto', 'unica', '2025-05-08 13:06:15'),
(46, 6, 'buenas practicas', 'texto', 'multiple', '2025-05-08 13:09:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuestas_estudiante`
--

CREATE TABLE `respuestas_estudiante` (
  `id` int(11) NOT NULL,
  `examenes_estudiantes_id` int(11) NOT NULL,
  `pregunta_id` int(11) NOT NULL,
  `opcion_seleccionada_id` int(11) DEFAULT NULL,
  `respuesta_texto` text DEFAULT NULL,
  `es_correcta` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

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
-- Indices de la tabla `intentos_examen`
--
ALTER TABLE `intentos_examen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estudiante_id` (`estudiante_id`),
  ADD KEY `examen_id` (`examen_id`),
  ADD KEY `examen_estudiante_id` (`examen_estudiante_id`);

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
  ADD KEY `intento_examen_id` (`examenes_estudiantes_id`),
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

--
-- AUTO_INCREMENT de la tabla `intentos_examen`
--
ALTER TABLE `intentos_examen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `logs_sistema`
--
ALTER TABLE `logs_sistema`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `opciones_pregunta`
--
ALTER TABLE `opciones_pregunta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT de la tabla `respuestas_estudiante`
--
ALTER TABLE `respuestas_estudiante`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=190;

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
  ADD CONSTRAINT `fk_respuesta_examen_estudiante` FOREIGN KEY (`examenes_estudiantes_id`) REFERENCES `examenes_estudiantes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `respuestas_estudiante_ibfk_2` FOREIGN KEY (`pregunta_id`) REFERENCES `preguntas` (`id`),
  ADD CONSTRAINT `respuestas_estudiante_ibfk_3` FOREIGN KEY (`opcion_seleccionada_id`) REFERENCES `opciones_pregunta` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
