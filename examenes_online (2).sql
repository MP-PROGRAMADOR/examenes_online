-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-03-2025 a las 13:17:47
-- Versión del servidor: 10.4.25-MariaDB
-- Versión de PHP: 8.1.10
-- Verificar si la base de datos "examenes_online" existe
CREATE DATABASE IF NOT EXISTS examenes_online;

-- Usar la base de datos "examenes_online"
USE examenes_online;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Verificar y crear la tabla "preguntas"
DROP TABLE IF EXISTS respuestas_grafico;
DROP TABLE IF EXISTS opciones;
DROP TABLE IF EXISTS respuestas_cortas;
DROP TABLE IF EXISTS respuestas_verdadero_falso;
DROP TABLE IF EXISTS respuestas_ensayo;
DROP TABLE IF EXISTS preguntas;
DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS aspirantes;
DROP TABLE IF EXISTS centro_procedencia;
DROP TABLE IF EXISTS examenes;
DROP TABLE IF EXISTS logs;
DROP TABLE IF EXISTS opciones;

 

CREATE TABLE aspirantes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);

INSERT INTO aspirantes (username, password) VALUES ('usuario', '$2y$10$Qx99.Yy.D5uE59/X5y7y5e.f8X/r7r5.539P.J539.y7r5.539P.J539'); -- La contraseña es "contraseña" hasheada con bcrypt
INSERT INTO aspirantes (username, password) VALUES ('alumno', '$2y$10$Qx99.Yy.D5uE59/X5y7y5e.f8X/r7r5.539P.J539.y7r5.539P.J539'); -- La contraseña es "clave" hasheada con bcrypt

CREATE TABLE preguntas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo_pregunta VARCHAR(20) NOT NULL,
    texto_pregunta TEXT NOT NULL,
    url_grafico VARCHAR(255) NULL
);

-- Verificar y crear la tabla "opciones"
CREATE TABLE opciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pregunta_id INT NOT NULL,
    texto_opcion VARCHAR(255) NOT NULL,
    es_correcta BOOLEAN NOT NULL DEFAULT 0,
    FOREIGN KEY (pregunta_id) REFERENCES preguntas(id)
);

-- Verificar y crear la tabla "respuestas_cortas"
CREATE TABLE respuestas_cortas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pregunta_id INT NOT NULL,
    respuesta_correcta VARCHAR(255) NOT NULL,
    FOREIGN KEY (pregunta_id) REFERENCES preguntas(id)
);

-- Verificar y crear la tabla "respuestas_verdadero_falso"
CREATE TABLE respuestas_verdadero_falso (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pregunta_id INT NOT NULL,
    respuesta_correcta BOOLEAN NOT NULL,
    FOREIGN KEY (pregunta_id) REFERENCES preguntas(id)
);

-- Verificar y crear la tabla "respuestas_ensayo"
CREATE TABLE respuestas_ensayo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pregunta_id INT NOT NULL,
    respuesta_guia TEXT NOT NULL,
    FOREIGN KEY (pregunta_id) REFERENCES preguntas(id)
);

-- Verificar y crear la tabla "respuestas_grafico"
CREATE TABLE respuestas_grafico (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pregunta_id INT NOT NULL,
    respuesta_correcta VARCHAR(255) NOT NULL,
    FOREIGN KEY (pregunta_id) REFERENCES preguntas(id)
);



/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `examenes_online`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `centro_procedencia`
--

CREATE TABLE `centro_procedencia` (
  `id_centro` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `centro_procedencia`
--

INSERT INTO `centro_procedencia` (`id_centro`, `nombre`) VALUES
(1, 'Guinea Circula');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `examenes`
--

CREATE TABLE `examenes` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `tiempo_limite` int(11) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `examinador_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `mensaje` text NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

 
--
-- Estructura de tabla para la tabla `respuestas_usuario`
--

CREATE TABLE `respuestas_usuario` (
  `id` int(11) NOT NULL,
  `respuesta_id` int(11) DEFAULT NULL,
  `pregunta_id` int(11) DEFAULT NULL,
  `opcion_seleccionada` int(11) DEFAULT NULL,
  `respuesta_texto` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `tipo_usuario` enum('admin','examinador','aspirante') NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_centro` int(11) NOT NULL,
  `dip` varchar(255) NOT NULL,
  `edad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `password`, `tipo_usuario`, `fecha_registro`, `id_centro`, `dip`, `edad`) VALUES
(1, 'salvador', '1ca2e75c0b', 'examinador', '2025-03-17 11:04:58', 1, '290125', 25);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `centro_procedencia`
--
ALTER TABLE `centro_procedencia`
  ADD PRIMARY KEY (`id_centro`);

--
-- Indices de la tabla `examenes`
--
ALTER TABLE `examenes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `examinador_id` (`examinador_id`);

--
-- Indices de la tabla `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `opciones`
--
ALTER TABLE `opciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pregunta_id` (`pregunta_id`);

--
-- Indices de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `examen_id` (`examen_id`);

--
-- Indices de la tabla `respuestas`
--
ALTER TABLE `respuestas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `examen_id` (`examen_id`);

--
-- Indices de la tabla `respuestas_usuario`
--
ALTER TABLE `respuestas_usuario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `respuesta_id` (`respuesta_id`),
  ADD KEY `pregunta_id` (`pregunta_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_centro` (`id_centro`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `centro_procedencia`
--
ALTER TABLE `centro_procedencia`
  MODIFY `id_centro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `examenes`
--
ALTER TABLE `examenes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `opciones`
--
ALTER TABLE `opciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `respuestas`
--
ALTER TABLE `respuestas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `respuestas_usuario`
--
ALTER TABLE `respuestas_usuario`
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
-- Filtros para la tabla `examenes`
--
ALTER TABLE `examenes`
  ADD CONSTRAINT `examenes_ibfk_1` FOREIGN KEY (`examinador_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `opciones`
--
ALTER TABLE `opciones`
  ADD CONSTRAINT `opciones_ibfk_1` FOREIGN KEY (`pregunta_id`) REFERENCES `preguntas` (`id`);

--
-- Filtros para la tabla `preguntas`
--
ALTER TABLE `preguntas`
  ADD CONSTRAINT `preguntas_ibfk_1` FOREIGN KEY (`examen_id`) REFERENCES `examenes` (`id`);

--
-- Filtros para la tabla `respuestas`
--
ALTER TABLE `respuestas`
  ADD CONSTRAINT `respuestas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `respuestas_ibfk_2` FOREIGN KEY (`examen_id`) REFERENCES `examenes` (`id`);

--
-- Filtros para la tabla `respuestas_usuario`
--
ALTER TABLE `respuestas_usuario`
  ADD CONSTRAINT `respuestas_usuario_ibfk_1` FOREIGN KEY (`respuesta_id`) REFERENCES `respuestas` (`id`),
  ADD CONSTRAINT `respuestas_usuario_ibfk_2` FOREIGN KEY (`pregunta_id`) REFERENCES `preguntas` (`id`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_centro`) REFERENCES `centro_procedencia` (`id_centro`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
