-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 09-06-2025 a las 14:30:55
-- Versión del servidor: 10.4.25-MariaDB
-- Versión de PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `web_examenes`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `edad_minima` tinyint(3) UNSIGNED NOT NULL DEFAULT 15
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `descripcion`, `edad_minima`) VALUES
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
-- Estructura de tabla para la tabla `correos_enviados`
--

CREATE TABLE `correos_enviados` (
  `id` int(11) NOT NULL,
  `estudiante_id` int(11) DEFAULT NULL,
  `tipo_correo` enum('registro','invitacion_examen','resultado','recordatorio') DEFAULT NULL,
  `asunto` varchar(255) DEFAULT NULL,
  `cuerpo` text DEFAULT NULL,
  `enviado_por` int(11) DEFAULT NULL,
  `enviado_en` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `escuelas_conduccion`
--

CREATE TABLE `escuelas_conduccion` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `ciudad` varchar(100) NOT NULL,
  `pais` varchar(100) DEFAULT 'Guinea Ecuatorial'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `escuelas_conduccion`
--

INSERT INTO `escuelas_conduccion` (`id`, `nombre`, `ciudad`, `pais`) VALUES
(1, 'Nana mangue', 'Malabo', 'Guinea Ecuatorial'),
(2, 'babe', 'baney', 'Guinea Ecuatorial');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

CREATE TABLE `estudiantes` (
  `id` int(11) NOT NULL,
  `dni` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `escuela_id` int(11) DEFAULT NULL,
  `estado` enum('activo','inactivo') DEFAULT 'activo',
  `creado_en` datetime DEFAULT current_timestamp(),
  `apellidos` varchar(250) DEFAULT NULL,
  `direccion` varchar(250) DEFAULT NULL,
  `usuario` varchar(100) NOT NULL,
  `Doc` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `estudiantes`
--

INSERT INTO `estudiantes` (`id`, `dni`, `nombre`, `email`, `telefono`, `fecha_nacimiento`, `escuela_id`, `estado`, `creado_en`, `apellidos`, `direccion`, `usuario`, `Doc`) VALUES
(3, '00012589741', 'Bubi', 'marie@gmail.com', '555214782', '2004-05-04', 1, 'activo', '2025-05-20 12:26:08', 'mabale', 'adfg', 'ENA25181', ''),
(4, '000121415', 'jesus', 'jes@gmail.com', '222141516', '2000-01-26', 1, 'activo', '2025-05-26 10:33:06', 'topola', 'Bisinga', 'ENA2546A', ''),
(5, '874653', 'salvador', 'salvadormete4@gmail.com', '33309876543', '2004-02-04', 1, 'activo', '2025-05-28 11:00:13', 'mete bijeri', 'buena esperanza 1', 'ENA25454', ''),
(6, '00948371', 'panchos', 'spaocholojilo@gmail.com', '333098765', '2000-05-09', 1, 'activo', '2025-06-09 09:07:05', 'asitos', 'campo yaunde', 'ENA25104', '0406');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiante_categorias`
--

CREATE TABLE `estudiante_categorias` (
  `id` int(11) NOT NULL,
  `estudiante_id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `estado` enum('pendiente','aprobado','rechazado','en_proceso') DEFAULT 'pendiente',
  `fecha_asignacion` datetime DEFAULT current_timestamp(),
  `fecha_aprobacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `estudiante_categorias`
--

INSERT INTO `estudiante_categorias` (`id`, `estudiante_id`, `categoria_id`, `estado`, `fecha_asignacion`, `fecha_aprobacion`) VALUES
(1, 3, 1, 'pendiente', '2025-05-20 12:26:08', NULL),
(2, 3, 4, 'pendiente', '2025-05-20 16:01:46', NULL),
(3, 3, 6, 'pendiente', '2025-05-20 16:03:46', NULL),
(4, 4, 4, 'pendiente', '2025-05-26 10:33:06', NULL),
(5, 5, 4, 'pendiente', '2025-05-28 11:00:13', NULL),
(6, 6, 1, 'pendiente', '2025-06-09 09:07:05', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `examenes`
--

CREATE TABLE `examenes` (
  `id` int(11) NOT NULL,
  `estudiante_id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `asignado_por` int(11) DEFAULT NULL,
  `fecha_asignacion` datetime DEFAULT current_timestamp(),
  `duracion` tinyint(1) DEFAULT 0,
  `total_preguntas` int(11) NOT NULL,
  `estado` enum('pendiente','en_progreso','finalizado') DEFAULT 'pendiente',
  `calificacion` decimal(5,2) DEFAULT NULL,
  `codigo_acceso` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `examenes`
--

INSERT INTO `examenes` (`id`, `estudiante_id`, `categoria_id`, `asignado_por`, `fecha_asignacion`, `duracion`, `total_preguntas`, `estado`, `calificacion`, `codigo_acceso`) VALUES
(5, 5, 4, 1, '2025-05-28 12:16:31', 7, 9, 'pendiente', '38.89', 'EXAM991276');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `examen_preguntas`
--

CREATE TABLE `examen_preguntas` (
  `id` int(11) NOT NULL,
  `examen_id` int(11) NOT NULL,
  `pregunta_id` int(11) NOT NULL,
  `respondida` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `examen_preguntas`
--

INSERT INTO `examen_preguntas` (`id`, `examen_id`, `pregunta_id`, `respondida`) VALUES
(55, 5, 14, 1),
(56, 5, 1, 0),
(57, 5, 6, 0),
(58, 5, 2, 0),
(59, 5, 9, 0),
(60, 5, 3, 0),
(61, 5, 7, 0),
(62, 5, 5, 0),
(63, 5, 8, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `imagenes_pregunta`
--

CREATE TABLE `imagenes_pregunta` (
  `id` int(11) NOT NULL,
  `pregunta_id` int(11) NOT NULL,
  `ruta_imagen` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `imagenes_pregunta`
--

INSERT INTO `imagenes_pregunta` (`id`, `pregunta_id`, `ruta_imagen`, `descripcion`) VALUES
(5, 14, 'uploads/preguntas/6836ee991d6d2_Screenshot 2025-05-27 133121.png', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opciones_pregunta`
--

CREATE TABLE `opciones_pregunta` (
  `id` int(11) NOT NULL,
  `pregunta_id` int(11) NOT NULL,
  `texto` varchar(255) NOT NULL,
  `es_correcta` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `opciones_pregunta`
--

INSERT INTO `opciones_pregunta` (`id`, `pregunta_id`, `texto`, `es_correcta`) VALUES
(1, 3, 'la misca es una pesca', 0),
(2, 2, 'una copa se usa para almacenar agua', 0),
(3, 1, 'se usa para aumentar la fuerza', 1),
(4, 1, 'para eliminar el sueño', 1),
(5, 1, 'no es un buen nutriente', 0),
(6, 1, 'es de color chocolate', 1),
(7, 5, '30 km/h', 0),
(8, 5, '50 km/h', 1),
(9, 5, '60 km/h', 0),
(10, 5, '70 km/h', 0),
(11, 6, 'Debe detenerse completamente al llegar a una señal de “Ceda el paso”.', 0),
(12, 7, 'Cinturón de seguridad', 1),
(13, 7, 'Radio del coche', 0),
(14, 7, 'Luces durante la noche o en condiciones de baja visibilidad', 1),
(15, 7, 'Espejos retrovisores en buen estado', 1),
(16, 8, 'Puede avanzar con precaución', 0),
(17, 8, 'Tiene prioridad sobre otros vehículos', 0),
(18, 8, 'Debe detenerse completamente', 1),
(19, 8, 'Puede continuar sin detenerse si no hay peatones', 0),
(20, 9, 'Lluvia o pavimento mojado', 1),
(21, 9, 'Neumáticos en mal estado', 1),
(22, 9, 'Luz solar intensa', 0),
(23, 9, 'Conducir a alta velocidad', 1),
(28, 14, 'hola macho', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas`
--

CREATE TABLE `preguntas` (
  `id` int(11) NOT NULL,
  `texto` text NOT NULL,
  `tipo` enum('unica','multiple','vf') NOT NULL,
  `tipo_contenido` enum('texto','ilustracion') NOT NULL,
  `activa` tinyint(1) DEFAULT 1,
  `creado_en` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `preguntas`
--

INSERT INTO `preguntas` (`id`, `texto`, `tipo`, `tipo_contenido`, `activa`, `creado_en`) VALUES
(1, '¿el café?', 'multiple', 'texto', 1, '2025-05-26 10:29:12'),
(2, 'una copa se usa para almacenar agua', 'vf', 'texto', 1, '2025-05-26 10:23:18'),
(3, 'la misca es una pesca', 'vf', 'texto', 1, '2025-05-21 08:59:27'),
(5, '¿Cuál es la velocidad máxima permitida en zona urbana, salvo que se indique lo contrario?', 'multiple', 'texto', 1, '2025-05-28 11:03:12'),
(6, 'Debe detenerse completamente al llegar a una señal de “Ceda el paso”.', 'vf', 'texto', 1, '2025-05-28 11:03:46'),
(7, '¿Qué elementos de seguridad deben usarse obligatoriamente mientras se conduce?', 'multiple', 'texto', 1, '2025-05-28 11:04:58'),
(8, '¿Qué indica una luz roja intermitente en un semáforo?', 'multiple', 'texto', 1, '2025-05-28 11:45:53'),
(9, '¿Qué situaciones pueden aumentar la distancia de frenado de un vehículo?', 'multiple', 'texto', 1, '2025-05-28 11:47:19'),
(14, 'hola macho', 'vf', 'ilustracion', 1, '2025-05-28 12:08:09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pregunta_categoria`
--

CREATE TABLE `pregunta_categoria` (
  `id` int(11) NOT NULL,
  `pregunta_id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `pregunta_categoria`
--

INSERT INTO `pregunta_categoria` (`id`, `pregunta_id`, `categoria_id`) VALUES
(2, 3, 4),
(3, 2, 4),
(4, 1, 4),
(5, 7, 4),
(6, 7, 6),
(7, 5, 4),
(8, 6, 4),
(9, 8, 4),
(10, 8, 5),
(11, 8, 6),
(12, 8, 7),
(13, 8, 9),
(14, 9, 1),
(15, 9, 4),
(16, 9, 6),
(17, 1, 1),
(18, 2, 1),
(19, 3, 1),
(20, 5, 1),
(21, 6, 1),
(22, 7, 1),
(23, 8, 1),
(26, 14, 1),
(27, 14, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuestas_estudiante`
--

CREATE TABLE `respuestas_estudiante` (
  `id` int(11) NOT NULL,
  `examen_pregunta_id` int(11) NOT NULL,
  `opcion_id` int(11) DEFAULT NULL,
  `fecha_respuesta` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `respuestas_estudiante`
--

INSERT INTO `respuestas_estudiante` (`id`, `examen_pregunta_id`, `opcion_id`, `fecha_respuesta`) VALUES
(269, 55, 14, '2025-05-28 12:32:01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contrasena_hash` varchar(255) NOT NULL,
  `rol` enum('admin','examinador','secretaria') NOT NULL,
  `creado_en` datetime DEFAULT current_timestamp(),
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `contrasena_hash`, `rol`, `creado_en`, `activo`) VALUES
(1, 'sir', 'sir@gmail.com', '$2y$10$if.sTKBTytAIvwUjR4B8ouL5Ugr3GMrm4k63R2K10db489fJ5nAsO', 'admin', '2025-05-19 09:07:55', 1),
(2, 'Mete', 'mh@gmail.com', '$2y$10$EJrZhIlE9vLlPETf9ZX.s.0GOdJwVNuJOgpKpy7EYNw3vPylgSWZO', 'examinador', '2025-05-19 13:09:50', 1),
(5, 'maximiliano compe puye', 'maxicom@gmail.com', '$2y$10$Ul6pXCK/6CkeqZI2uxkcre1RAe2802pYkrhmRPfg6PLBFoBFmOMYO', 'secretaria', '2025-06-09 08:49:20', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `correos_enviados`
--
ALTER TABLE `correos_enviados`
  ADD PRIMARY KEY (`id`),
  ADD KEY `enviado_por` (`enviado_por`),
  ADD KEY `estudiante_id` (`estudiante_id`);

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
  ADD UNIQUE KEY `dni` (`dni`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `email_2` (`email`),
  ADD KEY `escuela_id` (`escuela_id`);

--
-- Indices de la tabla `estudiante_categorias`
--
ALTER TABLE `estudiante_categorias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estudiante_id` (`estudiante_id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Indices de la tabla `examenes`
--
ALTER TABLE `examenes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_acceso` (`codigo_acceso`),
  ADD KEY `asignado_por` (`asignado_por`),
  ADD KEY `categoria_id` (`categoria_id`),
  ADD KEY `estudiante_id` (`estudiante_id`);

--
-- Indices de la tabla `examen_preguntas`
--
ALTER TABLE `examen_preguntas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `examen_id` (`examen_id`),
  ADD KEY `pregunta_id` (`pregunta_id`);

--
-- Indices de la tabla `imagenes_pregunta`
--
ALTER TABLE `imagenes_pregunta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pregunta_id` (`pregunta_id`);

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
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pregunta_categoria`
--
ALTER TABLE `pregunta_categoria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pregunta_id` (`pregunta_id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Indices de la tabla `respuestas_estudiante`
--
ALTER TABLE `respuestas_estudiante`
  ADD PRIMARY KEY (`id`),
  ADD KEY `examen_pregunta_id` (`examen_pregunta_id`),
  ADD KEY `opcion_id` (`opcion_id`);

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
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `correos_enviados`
--
ALTER TABLE `correos_enviados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `escuelas_conduccion`
--
ALTER TABLE `escuelas_conduccion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `estudiante_categorias`
--
ALTER TABLE `estudiante_categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `examenes`
--
ALTER TABLE `examenes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `examen_preguntas`
--
ALTER TABLE `examen_preguntas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT de la tabla `imagenes_pregunta`
--
ALTER TABLE `imagenes_pregunta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `opciones_pregunta`
--
ALTER TABLE `opciones_pregunta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `pregunta_categoria`
--
ALTER TABLE `pregunta_categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `respuestas_estudiante`
--
ALTER TABLE `respuestas_estudiante`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=270;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `correos_enviados`
--
ALTER TABLE `correos_enviados`
  ADD CONSTRAINT `correos_enviados_ibfk_1` FOREIGN KEY (`enviado_por`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `correos_enviados_ibfk_2` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD CONSTRAINT `estudiantes_ibfk_1` FOREIGN KEY (`escuela_id`) REFERENCES `escuelas_conduccion` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `estudiante_categorias`
--
ALTER TABLE `estudiante_categorias`
  ADD CONSTRAINT `estudiante_categorias_ibfk_1` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `estudiante_categorias_ibfk_2` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `examenes`
--
ALTER TABLE `examenes`
  ADD CONSTRAINT `examenes_ibfk_1` FOREIGN KEY (`asignado_por`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `examenes_ibfk_2` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `examenes_ibfk_3` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `examen_preguntas`
--
ALTER TABLE `examen_preguntas`
  ADD CONSTRAINT `examen_preguntas_ibfk_1` FOREIGN KEY (`examen_id`) REFERENCES `examenes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `examen_preguntas_ibfk_2` FOREIGN KEY (`pregunta_id`) REFERENCES `preguntas` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `imagenes_pregunta`
--
ALTER TABLE `imagenes_pregunta`
  ADD CONSTRAINT `imagenes_pregunta_ibfk_1` FOREIGN KEY (`pregunta_id`) REFERENCES `preguntas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `opciones_pregunta`
--
ALTER TABLE `opciones_pregunta`
  ADD CONSTRAINT `opciones_pregunta_ibfk_1` FOREIGN KEY (`pregunta_id`) REFERENCES `preguntas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pregunta_categoria`
--
ALTER TABLE `pregunta_categoria`
  ADD CONSTRAINT `pregunta_categoria_ibfk_1` FOREIGN KEY (`pregunta_id`) REFERENCES `preguntas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pregunta_categoria_ibfk_2` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `respuestas_estudiante`
--
ALTER TABLE `respuestas_estudiante`
  ADD CONSTRAINT `respuestas_estudiante_ibfk_1` FOREIGN KEY (`examen_pregunta_id`) REFERENCES `examen_preguntas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `respuestas_estudiante_ibfk_2` FOREIGN KEY (`opcion_id`) REFERENCES `opciones_pregunta` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
