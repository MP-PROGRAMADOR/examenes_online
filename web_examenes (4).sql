-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-07-2025 a las 09:50:21
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
-- Base de datos: `web_examenes`
--

drop database if exists web_examenes;
create database web_examenes;
use web_examenes;
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `edad_minima` tinyint(3) UNSIGNED NOT NULL DEFAULT 15
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `escuelas_conduccion`
--

CREATE TABLE `escuelas_conduccion` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `telefono` varchar(25) NOT NULL,
  `director` varchar(100) NOT NULL,
  `nif` varchar(100) NOT NULL,
  `ciudad` varchar(100) NOT NULL,
  `correo` varchar(25) DEFAULT NULL,
  `pais` varchar(100) DEFAULT 'Guinea Ecuatorial',
  `ubicacion` varchar(50) NOT NULL,
  `numero_registro` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `escuelas_conduccion`
--

INSERT INTO `escuelas_conduccion` (`id`, `nombre`, `telefono`, `director`, `nif`, `ciudad`, `correo`, `pais`, `ubicacion`, `numero_registro`) VALUES
(1, 'Nana mangue', '', '', '', 'Malabo', NULL, 'Guinea Ecuatorial', '', ''),
(2, 'babe', '', '', '', 'baney', NULL, 'Guinea Ecuatorial', '', '');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estudiantes`
--

INSERT INTO `estudiantes` (`id`, `dni`, `nombre`, `email`, `telefono`, `fecha_nacimiento`, `escuela_id`, `estado`, `creado_en`, `apellidos`, `direccion`, `usuario`, `Doc`) VALUES
(3, '00012589741', 'Bubi', 'marie@gmail.com', '555214782', '2004-05-04', 1, 'activo', '2025-05-20 12:26:08', 'mabale', 'adfg', 'ENA25181', ''),
(4, '000121415', 'jesus', 'jes@gmail.com', '222141516', '2000-01-26', 1, 'inactivo', '2025-05-26 10:33:06', 'topola', 'Bisinga', 'ENA2546A', ''),
(5, '874653', 'salvador', 'salvadormete4@gmail.com', '33309876543', '2004-02-04', 1, 'activo', '2025-05-28 11:00:13', 'mete bijeri', 'buena esperanza 1', 'ENA25454', ''),
(6, '00948371', 'panchos', 'spaocholojilo@gmail.com', '333098765', '2000-05-09', 1, 'activo', '2025-06-09 09:07:05', 'asitos', 'campo yaunde', 'ENA25104', '0406'),
(7, '114477', 'Serafina', 'bapori@gmail.com', '555477895', '2004-06-10', 1, 'activo', '2025-06-10 14:00:56', 'Bapori', 'Lampert', 'ENA2562D', '401'),
(8, '258963', 'Marta', 'tortosa@gmail.com', '55514152', '2002-06-12', 1, 'activo', '2025-06-10 15:48:48', 'Tortosa', 'Malabo 2 de semu', 'ENA2568D', '4012');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estudiante_categorias`
--

INSERT INTO `estudiante_categorias` (`id`, `estudiante_id`, `categoria_id`, `estado`, `fecha_asignacion`, `fecha_aprobacion`) VALUES
(2, 3, 4, 'pendiente', '2025-05-20 16:01:46', NULL),
(4, 4, 4, 'pendiente', '2025-05-26 10:33:06', NULL),
(5, 5, 4, 'pendiente', '2025-05-28 11:00:13', NULL),
(6, 6, 1, 'pendiente', '2025-06-09 09:07:05', NULL),
(11, 4, 12, 'pendiente', '2025-06-09 14:24:48', NULL),
(12, 7, 6, 'en_proceso', '2025-06-10 14:00:56', '2025-06-14 10:06:59'),
(13, 8, 6, 'aprobado', '2025-06-10 15:48:48', '2025-06-11 12:24:22'),
(14, 7, 4, 'pendiente', '2025-06-16 11:03:29', NULL);

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
  `estado` enum('pendiente','en_progreso','finalizado','INICIO') DEFAULT 'pendiente',
  `calificacion` decimal(5,2) DEFAULT NULL,
  `codigo_acceso` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `examenes`
--

INSERT INTO `examenes` (`id`, `estudiante_id`, `categoria_id`, `asignado_por`, `fecha_asignacion`, `duracion`, `total_preguntas`, `estado`, `calificacion`, `codigo_acceso`) VALUES
(11, 5, 4, 1, '2025-06-15 00:00:00', 4, 5, 'INICIO', NULL, 'EXAM009514'),
(12, 4, 12, 1, '2025-06-15 00:00:00', 4, 5, 'pendiente', NULL, 'EXAM247591'),
(13, 8, 6, 1, '2025-06-12 00:00:00', 4, 5, 'finalizado', 80.00, 'EXAM551027'),
(14, 7, 6, 1, '2025-06-21 00:00:00', 8, 10, 'finalizado', 75.83, 'EXAM821332'),
(15, 7, 6, 1, '2025-06-16 00:00:00', 4, 5, 'INICIO', NULL, 'EXAM602946');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `examen_preguntas`
--

CREATE TABLE `examen_preguntas` (
  `id` int(11) NOT NULL,
  `examen_id` int(11) NOT NULL,
  `pregunta_id` int(11) NOT NULL,
  `respondida` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `examen_preguntas`
--

INSERT INTO `examen_preguntas` (`id`, `examen_id`, `pregunta_id`, `respondida`) VALUES
(89, 11, 23, 1),
(90, 11, 28, 1),
(91, 11, 57, 0),
(92, 11, 55, 0),
(93, 11, 42, 0),
(94, 13, 53, 1),
(95, 13, 45, 1),
(96, 13, 52, 1),
(97, 13, 57, 1),
(98, 13, 25, 1),
(99, 14, 53, 1),
(100, 14, 52, 1),
(101, 14, 23, 1),
(102, 14, 39, 1),
(103, 14, 28, 1),
(104, 14, 50, 1),
(105, 14, 24, 1),
(106, 14, 57, 1),
(107, 14, 26, 1),
(108, 14, 56, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `imagenes_pregunta`
--

CREATE TABLE `imagenes_pregunta` (
  `id` int(11) NOT NULL,
  `pregunta_id` int(11) NOT NULL,
  `ruta_imagen` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `imagenes_pregunta`
--

INSERT INTO `imagenes_pregunta` (`id`, `pregunta_id`, `ruta_imagen`, `descripcion`) VALUES
(6, 53, 'uploads/preguntas/6849597861fc9_Screenshot 2025-06-11 112250.png', NULL),
(7, 54, 'uploads/preguntas/68495a32ad2c0_Screenshot 2025-06-11 112712.png', NULL),
(8, 55, 'uploads/preguntas/68495a7ef0945_Screenshot 2025-06-11 112833.png', NULL),
(9, 56, 'uploads/preguntas/68495b3c979ae_Screenshot 2025-06-11 113106.png', NULL),
(10, 57, 'uploads/preguntas/68495bc7d5ede_Screenshot 2025-06-11 113301.png', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opciones_pregunta`
--

CREATE TABLE `opciones_pregunta` (
  `id` int(11) NOT NULL,
  `pregunta_id` int(11) NOT NULL,
  `texto` varchar(255) NOT NULL,
  `es_correcta` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `opciones_pregunta`
--

INSERT INTO `opciones_pregunta` (`id`, `pregunta_id`, `texto`, `es_correcta`) VALUES
(40, 20, 'Que se puede adelantar con precaución.', 0),
(41, 20, 'Que está prohibido cruzarla o adelantar.', 1),
(42, 20, 'Que se permite girar a la izquierda.', 0),
(43, 20, 'Que la vía es de un solo sentido.', 0),
(44, 21, 'Ciclomotores de hasta 50 cc.', 0),
(45, 21, 'Motocicletas hasta 125 cc y 11 kW de potencia.', 1),
(46, 21, 'Motocicletas sin limitación de potencia.', 0),
(47, 21, 'Vehículos de hasta 3.500 kg.', 0),
(48, 22, 'De hasta 4.500 kg de MMA.', 0),
(49, 22, 'Solo motocicletas de hasta 125 cc.', 0),
(50, 22, 'De hasta 3.500 kg de MMA y hasta 9 plazas.', 1),
(51, 22, 'Autobuses sin pasajeros.', 0),
(52, 23, 'Permiso C.', 0),
(53, 23, 'Permiso B+E.', 0),
(54, 23, 'Permiso C+E.', 1),
(55, 23, 'Permiso D.', 0),
(56, 24, '9 plazas, incluido el conductor.', 1),
(57, 24, '6 plazas, sin incluir el conductor.', 0),
(58, 24, '15 plazas máximo.', 0),
(59, 24, '5 toneladas de peso.', 0),
(60, 25, 'Permiso B.', 0),
(61, 25, 'Permiso T.', 1),
(62, 25, 'Permiso A2.', 0),
(63, 25, 'Permiso D1.', 0),
(64, 26, 'Solo de noche.', 0),
(65, 26, 'Al estacionar en una calle iluminada.', 0),
(66, 26, 'Al circular por túneles o condiciones de baja visibilidad.', 1),
(67, 26, 'Nunca es obligatorio.', 0),
(68, 27, 'Automóviles ligeros.', 0),
(69, 27, 'Ciclomotores de dos o tres ruedas hasta 50 cc.', 1),
(70, 27, 'Motocicletas de hasta 125 cc.', 0),
(71, 27, 'Vehículos de transporte escolar.', 0),
(72, 28, 'Estado de los neumáticos.', 1),
(73, 28, 'Superficie de la calzada.', 1),
(74, 28, 'Uso del cinturón de seguridad.', 0),
(75, 28, 'Velocidad del vehículo.', 1),
(76, 29, 'Haber recibido formación específica.', 1),
(77, 29, 'Llevar señalización especial de transporte escolar.', 1),
(78, 30, 'Haber recibido formación específica.', 1),
(79, 29, 'Ser mayor de 18 años.', 0),
(80, 30, 'Llevar señalización especial de transporte escolar.', 1),
(81, 29, 'Comprobar el estado del vehículo antes del viaje.', 1),
(82, 30, 'Ser mayor de 18 años.', 0),
(83, 30, 'Comprobar el estado del vehículo antes del viaje.', 1),
(84, 31, 'Mejora los reflejos.', 0),
(85, 31, 'Reduce la capacidad de reacción.', 1),
(86, 31, 'Disminuye la concentración.', 1),
(87, 31, 'Produce somnolencia.', 1),
(88, 32, 'Casco homologado.', 1),
(89, 32, 'Guantes protectores.', 1),
(90, 32, 'Ropa reflectante.', 1),
(91, 32, 'Sandalias o calzado abierto.', 0),
(92, 33, 'Permiso de conducir vigente.', 1),
(93, 33, 'Permiso de circulación del vehículo.', 1),
(94, 33, 'Fotocopia del DNI.', 0),
(95, 33, 'Certificado médico original.', 0),
(96, 34, 'Usar casco homologado.', 1),
(97, 34, 'Circular con las luces apagadas.', 0),
(98, 34, 'Mantener distancia de seguridad.', 1),
(99, 34, 'Conducir con auriculares puestos.', 0),
(100, 35, 'Sobrepeso en el eje trasero.', 1),
(101, 35, 'Neumáticos nuevos.', 0),
(102, 35, 'Calzada mojada.', 1),
(103, 35, 'Temperatura ambiente elevada.', 0),
(104, 36, 'Funcionamiento del aire acondicionado.', 0),
(105, 36, 'Correcto cierre de puertas y funcionamiento de luces.', 1),
(106, 36, 'Estado de la suspensión neumática.', 1),
(107, 36, 'Niveles de aceite del motor del coche personal.', 0),
(108, 37, 'Motocicletas de hasta 35 kW de potencia.', 1),
(109, 37, 'Motocicletas sin limitación de potencia.', 0),
(110, 37, 'Scooters de hasta 50 cc.', 0),
(111, 37, 'Motocicletas con relación potencia/peso hasta 0,2 kW/kg.', 1),
(112, 38, 'Dispositivos de alumbrado y señalización.', 1),
(113, 38, 'Seguro obligatorio.', 1),
(114, 38, 'Estar matriculado como turismo.', 0),
(115, 38, 'Tener cinturones de seguridad.', 0),
(116, 39, 'Aumento de la capacidad de reacción.', 0),
(117, 39, 'Disminución de la concentración.', 1),
(118, 39, 'Alteración de la percepción del riesgo.', 1),
(119, 39, 'Mayor rendimiento visual.', 0),
(120, 40, 'Cuadradas con fondo azul.', 0),
(121, 40, 'Triangulares con borde rojo.', 1),
(122, 40, 'Circulares con borde rojo.', 0),
(123, 40, 'Cuadradas con símbolos negros sobre fondo blanco.', 1),
(124, 41, 'Un remolque de hasta 750 kg.', 0),
(125, 41, 'Un remolque que supere los 750 kg, sin exceder los 3.500 kg de MMA combinada.', 1),
(126, 41, 'Un remolque de hasta 4.250 kg siempre que el conjunto total no exceda los 7.000 kg.', 0),
(127, 41, 'Un remolque que supere los 750 kg, con una MMA del conjunto hasta 7.000 kg.', 1),
(128, 42, 'Abrir el capó rápidamente.', 0),
(129, 42, 'Usar el extintor si es seguro hacerlo.', 1),
(130, 42, 'Apagar el motor y cortar el contacto.', 1),
(131, 42, 'Echar agua sobre el motor.', 0),
(132, 43, 'El conductor con permiso B puede conducir una furgoneta de hasta 3.500 kg de MMA.', 1),
(133, 44, 'El casco es obligatorio solo fuera de zonas urbanas para motociclistas.', 0),
(134, 45, 'El permiso C permite conducir vehículos destinados al transporte de mercancías de más de 3.500 kg de MMA.', 1),
(135, 46, 'Un tractor agrícola no necesita seguro obligatorio para circular por vías públicas.', 0),
(136, 47, 'El permiso D autoriza a conducir autobuses con más de 9 plazas.', 1),
(137, 48, 'La carga mal distribuida puede afectar la estabilidad del camión.', 1),
(138, 49, 'El permiso AM permite conducir motocicletas de hasta 125 cc.', 0),
(139, 50, 'Una señal triangular con borde rojo indica peligro.', 1),
(140, 51, 'La fatiga mejora los reflejos del conductor.', 0),
(141, 52, 'Está permitido utilizar el teléfono móvil mientras se conduce si se usa con manos libres.', 1),
(142, 53, 'Próximamente curva a la derecha.', 1),
(143, 53, 'Ninguna es correcta.', 0),
(144, 53, 'Próximamente curva a la izquierda.', 0),
(145, 53, 'Próximamente aparecerá tránsito sobre la izquierda.', 0),
(146, 54, 'El siguiente símbolo indica que se trata de un carril que debe ser liberado cuando se aproxima un vehículo en emergencia.', 1),
(147, 55, 'La señal que se muestra es una señal preventiva, de máximo peligro, que anuncia la existencia de un tramo de vía con fuerte pendiente descendente.', 1),
(148, 56, 'La señal A', 0),
(149, 56, 'La señal B', 1),
(150, 56, 'La señal C', 0),
(151, 57, 'Inicio de doble mano.', 0),
(152, 57, 'Encrucijada (bifurcación).', 1),
(153, 57, 'Estrechamiento (en una sola mano).', 0);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `preguntas`
--

INSERT INTO `preguntas` (`id`, `texto`, `tipo`, `tipo_contenido`, `activa`, `creado_en`) VALUES
(20, '¿Qué indica una línea continua en el centro de la calzada?', 'unica', 'texto', 1, '2025-06-11 10:38:19'),
(21, '¿Qué tipo de vehículos puedes conducir con el permiso A1?', 'unica', 'texto', 1, '2025-06-11 10:40:33'),
(22, 'El permiso de conducir tipo B autoriza a conducir vehículos...', 'unica', 'texto', 1, '2025-06-11 10:41:56'),
(23, '¿Qué tipo de permiso necesitas para conducir un camión con remolque pesado (más de 750 kg)?', 'unica', 'texto', 1, '2025-06-11 10:43:06'),
(24, 'El permiso D autoriza a conducir vehículos destinados al transporte de personas con más de...', 'unica', 'texto', 1, '2025-06-11 10:44:29'),
(25, '¿Qué tipo de permiso se requiere para conducir tractores agrícolas con remolques?', 'unica', 'texto', 1, '2025-06-11 10:45:36'),
(26, '¿Cuándo se debe usar obligatoriamente el alumbrado de cruce?', 'unica', 'texto', 1, '2025-06-11 10:46:52'),
(27, 'El permiso AM permite conducir...', 'unica', 'texto', 1, '2025-06-11 10:48:06'),
(28, '¿Qué factores pueden aumentar la distancia de frenado de un vehículo?', 'multiple', 'texto', 1, '2025-06-11 10:50:46'),
(29, '¿Qué condiciones debe cumplir el conductor de un autobús antes de iniciar el transporte escolar?', 'multiple', 'texto', 1, '2025-06-11 10:52:47'),
(30, '¿Qué condiciones debe cumplir el conductor de un autobús antes de iniciar el transporte escolar?', 'multiple', 'texto', 1, '2025-06-11 10:52:47'),
(31, '¿Cuáles son los efectos del alcohol en la conducción?', 'multiple', 'texto', 1, '2025-06-11 10:54:53'),
(32, '¿Qué equipo de protección es recomendable usar al conducir una motocicleta?', 'multiple', 'texto', 1, '2025-06-11 10:56:27'),
(33, '¿Qué documentación debe llevar obligatoriamente un conductor de turismo?', 'multiple', 'texto', 1, '2025-06-11 11:00:25'),
(34, '¿Qué acciones aumentan la seguridad del conductor de motocicleta?', 'multiple', 'texto', 1, '2025-06-11 11:01:45'),
(35, '¿Qué condiciones afectan negativamente la frenada de un camión?', 'multiple', 'texto', 1, '2025-06-11 11:03:02'),
(36, '¿Qué debe comprobar un conductor antes de iniciar un servicio de transporte de personas?', 'multiple', 'texto', 1, '2025-06-11 11:04:18'),
(37, '¿Qué vehículos se pueden conducir con el permiso A2?', 'multiple', 'texto', 1, '2025-06-11 11:05:37'),
(38, '¿Qué características debe tener un remolque agrícola para circular legalmente?', 'multiple', 'texto', 1, '2025-06-11 11:06:38'),
(39, '¿Cuáles son efectos comunes del cansancio en la conducción?', 'multiple', 'texto', 1, '2025-06-11 11:08:51'),
(40, '¿Qué señales indican peligro?', 'multiple', 'texto', 1, '2025-06-11 11:10:02'),
(41, '¿Qué vehículos puede arrastrar legalmente un conductor con el carnet B+E?', 'multiple', 'texto', 1, '2025-06-11 11:11:13'),
(42, '¿Qué medidas se deben tomar en caso de incendio en el motor?', 'multiple', 'texto', 1, '2025-06-11 11:12:30'),
(43, 'El conductor con permiso B puede conducir una furgoneta de hasta 3.500 kg de MMA.', 'vf', 'texto', 1, '2025-06-11 11:14:12'),
(44, 'El casco es obligatorio solo fuera de zonas urbanas para motociclistas.', 'vf', 'texto', 1, '2025-06-11 11:14:36'),
(45, 'El permiso C permite conducir vehículos destinados al transporte de mercancías de más de 3.500 kg de MMA.', 'vf', 'texto', 1, '2025-06-11 11:15:09'),
(46, 'Un tractor agrícola no necesita seguro obligatorio para circular por vías públicas.', 'vf', 'texto', 1, '2025-06-11 11:15:33'),
(47, 'El permiso D autoriza a conducir autobuses con más de 9 plazas.', 'vf', 'texto', 1, '2025-06-11 11:16:07'),
(48, 'La carga mal distribuida puede afectar la estabilidad del camión.', 'vf', 'texto', 1, '2025-06-11 11:16:27'),
(49, 'El permiso AM permite conducir motocicletas de hasta 125 cc.', 'vf', 'texto', 1, '2025-06-11 11:16:49'),
(50, 'Una señal triangular con borde rojo indica peligro.', 'vf', 'texto', 1, '2025-06-11 11:17:15'),
(51, 'La fatiga mejora los reflejos del conductor.', 'vf', 'texto', 1, '2025-06-11 11:17:45'),
(52, 'Está permitido utilizar el teléfono móvil mientras se conduce si se usa con manos libres.', 'vf', 'texto', 1, '2025-06-11 11:18:52'),
(53, '¿Qué le indica a Ud. esta señal?', 'unica', 'ilustracion', 1, '2025-06-11 11:24:56'),
(54, 'El siguiente símbolo indica que se trata de un carril que debe ser liberado cuando se aproxima un vehículo en emergencia.', 'vf', 'ilustracion', 1, '2025-06-11 11:28:02'),
(55, 'La señal que se muestra es una señal preventiva, de máximo peligro, que anuncia la existencia de un tramo de vía con fuerte pendiente descendente.', 'vf', 'ilustracion', 1, '2025-06-11 11:29:18'),
(56, '¿Cuál de estas señales es Reglamentaria?', 'unica', 'ilustracion', 1, '2025-06-11 11:32:28'),
(57, 'Determine qué indica la señal que a continuación se presenta:', 'unica', 'ilustracion', 1, '2025-06-11 11:34:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pregunta_categoria`
--

CREATE TABLE `pregunta_categoria` (
  `id` int(11) NOT NULL,
  `pregunta_id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pregunta_categoria`
--

INSERT INTO `pregunta_categoria` (`id`, `pregunta_id`, `categoria_id`) VALUES
(44, 20, 1),
(45, 20, 2),
(46, 20, 3),
(47, 20, 4),
(48, 20, 6),
(49, 20, 7),
(50, 20, 9),
(51, 20, 11),
(52, 20, 13),
(53, 21, 1),
(54, 21, 2),
(55, 21, 3),
(56, 21, 12),
(57, 21, 4),
(58, 21, 5),
(59, 21, 6),
(60, 21, 8),
(61, 21, 7),
(62, 21, 9),
(63, 21, 11),
(64, 21, 10),
(65, 21, 13),
(66, 22, 1),
(67, 22, 2),
(68, 22, 3),
(69, 22, 12),
(70, 22, 4),
(71, 22, 5),
(72, 22, 6),
(73, 22, 8),
(74, 22, 7),
(75, 22, 9),
(76, 22, 11),
(77, 22, 10),
(78, 22, 13),
(79, 23, 1),
(80, 23, 2),
(81, 23, 3),
(82, 23, 12),
(83, 23, 4),
(84, 23, 5),
(85, 23, 6),
(86, 23, 8),
(87, 23, 7),
(88, 23, 9),
(89, 23, 11),
(90, 23, 10),
(91, 23, 13),
(92, 24, 1),
(93, 24, 2),
(94, 24, 3),
(95, 24, 12),
(96, 24, 4),
(97, 24, 5),
(98, 24, 6),
(99, 24, 8),
(100, 24, 7),
(101, 24, 9),
(102, 24, 11),
(103, 24, 10),
(104, 24, 13),
(105, 25, 1),
(106, 25, 2),
(107, 25, 3),
(108, 25, 12),
(109, 25, 4),
(110, 25, 5),
(111, 25, 6),
(112, 25, 8),
(113, 25, 7),
(114, 25, 9),
(115, 25, 11),
(116, 25, 10),
(117, 25, 13),
(118, 26, 1),
(119, 26, 2),
(120, 26, 3),
(121, 26, 12),
(122, 26, 4),
(123, 26, 5),
(124, 26, 6),
(125, 26, 8),
(126, 26, 7),
(127, 26, 9),
(128, 26, 11),
(129, 26, 10),
(130, 26, 13),
(131, 27, 1),
(132, 27, 2),
(133, 27, 3),
(134, 27, 12),
(135, 27, 4),
(136, 27, 5),
(137, 27, 6),
(138, 27, 8),
(139, 27, 7),
(140, 27, 9),
(141, 27, 11),
(142, 27, 10),
(143, 27, 13),
(144, 28, 4),
(145, 28, 6),
(146, 28, 8),
(147, 29, 9),
(148, 29, 11),
(149, 30, 9),
(150, 30, 11),
(151, 29, 10),
(152, 30, 10),
(153, 31, 3),
(154, 31, 12),
(155, 31, 4),
(156, 32, 1),
(157, 32, 2),
(158, 32, 3),
(159, 33, 4),
(160, 34, 1),
(161, 34, 2),
(162, 34, 3),
(163, 35, 6),
(164, 35, 8),
(165, 35, 7),
(166, 36, 9),
(167, 36, 11),
(168, 36, 10),
(169, 37, 3),
(170, 38, 13),
(171, 39, 1),
(172, 39, 2),
(173, 39, 3),
(174, 39, 12),
(175, 39, 4),
(176, 39, 5),
(177, 39, 6),
(178, 39, 8),
(179, 39, 7),
(180, 39, 9),
(181, 39, 11),
(182, 39, 10),
(183, 39, 13),
(184, 40, 1),
(185, 40, 4),
(186, 40, 6),
(187, 41, 5),
(188, 42, 1),
(189, 42, 2),
(190, 42, 3),
(191, 42, 12),
(192, 42, 4),
(193, 42, 5),
(194, 42, 6),
(195, 42, 8),
(196, 42, 7),
(197, 42, 9),
(198, 42, 11),
(199, 42, 10),
(200, 42, 13),
(201, 43, 4),
(202, 44, 1),
(203, 44, 2),
(204, 44, 3),
(205, 45, 6),
(206, 46, 13),
(207, 47, 9),
(208, 48, 6),
(209, 48, 8),
(210, 49, 12),
(211, 50, 1),
(212, 50, 2),
(213, 50, 3),
(214, 50, 12),
(215, 50, 4),
(216, 50, 5),
(217, 50, 6),
(218, 50, 8),
(219, 50, 7),
(220, 50, 9),
(221, 50, 11),
(222, 50, 10),
(223, 50, 13),
(224, 51, 1),
(225, 51, 2),
(226, 51, 3),
(227, 51, 12),
(228, 51, 4),
(229, 51, 5),
(230, 51, 6),
(231, 51, 8),
(232, 51, 7),
(233, 51, 9),
(234, 51, 11),
(235, 51, 10),
(236, 51, 13),
(237, 52, 1),
(238, 52, 2),
(239, 52, 3),
(240, 52, 12),
(241, 52, 4),
(242, 52, 5),
(243, 52, 6),
(244, 52, 8),
(245, 52, 7),
(246, 52, 9),
(247, 52, 11),
(248, 52, 10),
(249, 52, 13),
(250, 53, 1),
(251, 53, 2),
(252, 53, 3),
(253, 53, 12),
(254, 53, 4),
(255, 53, 5),
(256, 53, 6),
(257, 53, 8),
(258, 53, 7),
(259, 53, 9),
(260, 53, 11),
(261, 53, 10),
(262, 53, 13),
(263, 54, 1),
(264, 54, 2),
(265, 54, 3),
(266, 54, 12),
(267, 54, 4),
(268, 54, 5),
(269, 54, 6),
(270, 54, 8),
(271, 54, 7),
(272, 54, 9),
(273, 54, 11),
(274, 54, 10),
(275, 54, 13),
(276, 55, 1),
(277, 55, 2),
(278, 55, 3),
(279, 55, 12),
(280, 55, 4),
(281, 55, 5),
(282, 55, 6),
(283, 55, 8),
(284, 55, 7),
(285, 55, 9),
(286, 55, 11),
(287, 55, 10),
(288, 55, 13),
(289, 56, 1),
(290, 56, 2),
(291, 56, 3),
(292, 56, 12),
(293, 56, 4),
(294, 56, 5),
(295, 56, 6),
(296, 56, 8),
(297, 56, 7),
(298, 56, 9),
(299, 56, 11),
(300, 56, 10),
(301, 56, 13),
(302, 57, 1),
(303, 57, 2),
(304, 57, 3),
(305, 57, 12),
(306, 57, 4),
(307, 57, 5),
(308, 57, 6),
(309, 57, 8),
(310, 57, 7),
(311, 57, 9),
(312, 57, 11),
(313, 57, 10),
(314, 57, 13);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuestas_estudiante`
--

CREATE TABLE `respuestas_estudiante` (
  `id` int(11) NOT NULL,
  `examen_pregunta_id` int(11) NOT NULL,
  `opcion_id` int(11) DEFAULT NULL,
  `fecha_respuesta` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `respuestas_estudiante`
--

INSERT INTO `respuestas_estudiante` (`id`, `examen_pregunta_id`, `opcion_id`, `fecha_respuesta`) VALUES
(302, 89, 55, '2025-06-11 11:41:07'),
(303, 90, 72, '2025-06-11 11:41:26'),
(304, 90, 73, '2025-06-11 11:41:26'),
(305, 90, 75, '2025-06-11 11:41:26'),
(306, 94, 142, '2025-06-11 12:23:02'),
(307, 95, 45, '2025-06-11 12:23:17'),
(308, 96, 52, '2025-06-11 12:23:26'),
(309, 97, 152, '2025-06-11 12:23:54'),
(310, 98, 61, '2025-06-11 12:24:22'),
(311, 99, 142, '2025-06-14 10:05:16'),
(312, 100, 52, '2025-06-14 10:05:25'),
(313, 101, 54, '2025-06-14 10:05:32'),
(314, 102, 116, '2025-06-14 10:05:49'),
(315, 102, 117, '2025-06-14 10:05:49'),
(316, 102, 118, '2025-06-14 10:05:49'),
(317, 103, 72, '2025-06-14 10:06:02'),
(318, 103, 73, '2025-06-14 10:06:02'),
(319, 103, 75, '2025-06-14 10:06:02'),
(320, 104, 50, '2025-06-14 10:06:08'),
(321, 105, 57, '2025-06-14 10:06:20'),
(322, 106, 152, '2025-06-14 10:06:28'),
(323, 107, 66, '2025-06-14 10:06:48'),
(324, 108, 150, '2025-06-14 10:06:59');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `estudiante_categorias`
--
ALTER TABLE `estudiante_categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `examenes`
--
ALTER TABLE `examenes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `examen_preguntas`
--
ALTER TABLE `examen_preguntas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT de la tabla `imagenes_pregunta`
--
ALTER TABLE `imagenes_pregunta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `opciones_pregunta`
--
ALTER TABLE `opciones_pregunta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=154;

--
-- AUTO_INCREMENT de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT de la tabla `pregunta_categoria`
--
ALTER TABLE `pregunta_categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=315;

--
-- AUTO_INCREMENT de la tabla `respuestas_estudiante`
--
ALTER TABLE `respuestas_estudiante`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=325;

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
