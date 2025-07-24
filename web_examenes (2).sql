-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 24-07-2025 a las 14:54:17
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
  `telefono` varchar(25) NOT NULL,
  `director` varchar(100) NOT NULL,
  `nif` varchar(100) NOT NULL,
  `ciudad` varchar(100) NOT NULL,
  `correo` varchar(25) DEFAULT NULL,
  `pais` varchar(100) DEFAULT 'Guinea Ecuatorial',
  `ubicacion` varchar(50) NOT NULL,
  `numero_registro` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `escuelas_conduccion`
--

INSERT INTO `escuelas_conduccion` (`id`, `nombre`, `telefono`, `director`, `nif`, `ciudad`, `correo`, `pais`, `ubicacion`, `numero_registro`) VALUES
(1, 'Guinea Circula', '222478702', 'Serafin Riberi Belope', '00987654', 'Malabo', 'salvadormete2@gmail.com', 'Guinea Ecuatorial', 'Buena Esperanza I', '98302');

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



CREATE TABLE `estudiante_categorias` (
  `id` int(11) NOT NULL,
  `estudiante_id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `estado` enum('pendiente','aprobado','rechazado','en_proceso') DEFAULT 'pendiente',
  `fecha_asignacion` datetime DEFAULT current_timestamp(),
  `fecha_aprobacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `examen_preguntas` (
  `id` int(11) NOT NULL,
  `examen_id` int(11) NOT NULL,
  `pregunta_id` int(11) NOT NULL,
  `respondida` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
(153, 57, 'Estrechamiento (en una sola mano).', 0),
(154, 58, '100 km/h', 0),
(155, 58, '90 km/h', 1),
(156, 58, '80 km/h', 0),
(157, 59, 'Que se puede adelantar si no viene nadie', 0),
(158, 59, 'Que está prohibido adelantar', 1),
(159, 59, 'Que es una vía de doble sentido', 0),
(160, 60, 'Tocar el claxon para avisarle', 0),
(161, 60, 'Reducir la velocidad', 0),
(162, 60, 'Detenerse y ceder el paso', 1),
(163, 61, '0,25 mg/l', 1),
(164, 61, '0,5 mg/l', 0),
(165, 61, '0,0 mg/l', 0),
(166, 62, 'No, solo para el conductor', 0),
(167, 62, 'Sí, siempre', 1),
(168, 62, 'Solo si circula por carretera', 0),
(169, 63, 'No', 0),
(170, 63, 'Sí, si se hace a velocidad moderada y con precaución', 1),
(171, 63, 'Sí, a cualquier velocidad', 0),
(172, 64, '100 km/h', 0),
(173, 64, '90 km/h', 0),
(174, 64, '80 km/h', 1),
(175, 65, 'Solo freno de servicio', 0),
(176, 65, 'Freno de estacionamiento y freno de emergencia', 1),
(177, 65, 'Freno de mano únicamente', 0),
(178, 66, 'Permiso de circulación', 0),
(179, 66, 'Ficha técnica del vehículo', 0),
(180, 66, 'Carta de porte y autorización ADR', 1),
(181, 67, 'Acelerar con suavidad', 0),
(182, 67, 'Frenar bruscamente', 0),
(183, 67, 'Conducir con especial precaución', 1),
(184, 68, '10 horas', 0),
(185, 68, '9 horas', 1),
(186, 68, '8 horas', 0),
(187, 69, 'No', 0),
(188, 69, 'Sí', 1),
(189, 69, 'Solo en trayectos superiores a 50 km', 0),
(190, 70, 'Cuando hay un peatón cruzando en un paso de cebra.', 1),
(191, 70, 'Cuando un semáforo está en rojo.', 1),
(192, 70, 'Cuando circula un ciclista por el carril derecho.', 0),
(193, 70, 'Cuando hay una señal de ceda el paso.', 0),
(194, 71, 'Permiso de conducción correspondiente', 1),
(195, 71, 'Seguro obligatorio del vehículo', 1),
(196, 71, 'Pasaporte del conductor', 0),
(197, 71, 'Permiso de circulación del vehículo', 1),
(198, 72, 'Chaleco reflectante', 1),
(199, 72, 'Dos triángulos de preseñalización', 1),
(200, 72, 'Extintor', 0),
(201, 72, 'Una rueda de repuesto o kit reparapinchazos', 1),
(202, 73, 'Dos espejos retrovisores', 1),
(203, 73, 'Luz de posición trasera', 1),
(204, 73, 'Botiquín de primeros auxilios', 0),
(205, 73, 'Silenciador de escape en buen estado', 1),
(206, 74, 'Reducir la velocidad', 1),
(207, 74, 'Usar ropa reflectante', 1),
(208, 74, 'Frenar bruscamente al inicio de la curva', 0),
(209, 74, 'Evitar circular sobre marcas viales pintadas', 1),
(210, 75, 'Estado de los frenos', 1),
(211, 75, 'Nivel de aceite del motor', 1),
(212, 75, 'Color de la matrícula', 0),
(213, 75, 'Presión de los neumáticos', 1),
(214, 76, 'Avería del sistema de frenos', 1),
(215, 76, 'Pérdida de carga en circulación', 1),
(216, 76, 'Falta de aire acondicionado', 0),
(217, 76, 'Accidente o emergencia en la vía', 1),
(218, 77, 'Garantizar la seguridad durante el trayecto', 1),
(219, 77, 'Informar sobre las paradas', 1),
(220, 77, 'Permitir fumar dentro del autobús', 0),
(221, 77, 'Asegurarse de que todos están sentados antes de arrancar', 1),
(222, 78, 'Señal V-10 (transporte escolar)', 1),
(223, 78, 'Extintor', 1),
(224, 78, 'Neumáticos de invierno', 0),
(225, 78, 'Botiquín de primeros auxilios', 1),
(226, 79, 'Conducir con neumáticos desgastados', 1),
(227, 79, 'Circular con lluvia o hielo', 1),
(228, 79, 'Frenar en una recta seca', 0),
(229, 79, 'Llevar el coche sobrecargado', 1),
(230, 80, '5 metros', 0),
(231, 80, '30 metros', 1),
(232, 80, '60 metros', 0),
(233, 81, 'Los vehículos', 1),
(234, 81, 'Los peatones', 0),
(235, 81, 'Es indistinto', 0),
(236, 82, 'El factor humano', 0),
(237, 82, 'El factor vehicular', 0),
(238, 82, 'El factor humano, ambiental y vehicular', 1),
(239, 83, 'Por la derecha', 0),
(240, 83, 'Por cualquier lado', 0),
(241, 83, 'Siempre por la izquierda', 1),
(242, 84, 'a 10 Km/h', 0),
(243, 84, 'a 40 Km/h', 0),
(244, 84, 'a 20 Km/h', 1),
(245, 85, 'Es inmovilizarlo reglamentariamente por un tiempo no mayor que el necesario   para el ascenso y descenso de pasajeros ó carga y descarga de cosas.', 1),
(246, 85, 'Es inmovilizarlo reglamentariamente por un tiempo mayor que el necesario, para  el ascenso y descenso de pasajeros ó carga y descarga de cosas', 0),
(247, 86, '15 Km/h', 0),
(248, 86, '60 Km/h', 0),
(249, 86, '40 Km/h', 1),
(250, 87, 'Espera a que el semáforo indique luz verde y entonces continúa', 0),
(251, 87, 'Obedece la seña del Policía', 1),
(252, 87, 'Le Indica al Policía que la luz está roja', 0),
(253, 88, 'El estado del tránsito y las condiciones de la vía', 1),
(254, 88, 'El límite de velocidad máximo permitido', 0),
(255, 88, 'El estado de los vehículos', 0),
(256, 89, 'Sugerencias', 0),
(257, 89, 'Ordenes impuestas por Leyes y Ordenanzas', 1),
(258, 89, 'Información general', 0),
(259, 90, 'Advierten sobre un cambio de normalidad en la vía', 1),
(260, 90, 'Ordenan y exigen un determinado comportamiento del conductor', 0),
(261, 90, 'Informan sobre servicios disponibles en la vía', 0);

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
(57, 'Determine qué indica la señal que a continuación se presenta:', 'unica', 'ilustracion', 1, '2025-06-11 11:34:47'),
(58, '¿Cuál es la velocidad máxima permitida en una carretera convencional para un turismo sin remolque? opción unica', 'unica', 'texto', 1, '2025-07-24 11:05:33'),
(59, '¿Qué indica una línea continua en el centro de la calzada? opción única', 'unica', 'texto', 1, '2025-07-24 11:07:01'),
(60, '¿Qué debe hacer un conductor si un peatón ciego con bastón blanco desea cruzar por un paso de peatones? opción única ', 'unica', 'texto', 1, '2025-07-24 11:08:14'),
(61, '¿Cuál es la tasa máxima de alcohol permitida para un conductor de motocicleta en mg/l en aire espirado? opción única', 'unica', 'texto', 1, '2025-07-24 11:09:15'),
(62, 'En una moto, ¿es obligatorio el uso de casco para el pasajero? opción única', 'unica', 'texto', 1, '2025-07-24 11:10:16'),
(63, '¿Está permitido circular entre los coches en un atasco con una moto? opción única', 'unica', 'texto', 1, '2025-07-24 11:11:18'),
(64, '¿Cuál es la velocidad máxima permitida para un camión en autopista? opción única ', 'unica', 'texto', 1, '2025-07-24 11:13:42'),
(65, '¿Qué tipo de freno debe tener obligatoriamente un vehículo de transporte de mercancías pesadas? opción única ', 'unica', 'texto', 1, '2025-07-24 11:15:51'),
(66, '¿Qué documento específico debe llevar un camión que transporta mercancías peligrosas?', 'unica', 'texto', 1, '2025-07-24 11:16:59'),
(67, '¿Qué debe hacer un conductor de autobús si un pasajero se encuentra de pie mientras el vehículo está en marcha? opción única', 'unica', 'texto', 1, '2025-07-24 11:18:24'),
(68, '¿Cuál es el número máximo de horas diarias de conducción permitidas para un conductor profesional de autobús? opción única ', 'unica', 'texto', 1, '2025-07-24 11:19:57'),
(69, '¿Es obligatorio el tacógrafo en los autobuses de transporte público interurbano? opción única ', 'unica', 'texto', 1, '2025-07-24 11:22:30'),
(70, '¿Cuáles de estas situaciones obligan a detener el vehículo? opción múltiple ', 'multiple', 'texto', 1, '2025-07-24 11:27:22'),
(71, '¿Qué documentos son obligatorios para circular con un turismo? opción multiple', 'multiple', 'texto', 1, '2025-07-24 11:29:02'),
(72, '¿Qué elementos forman parte del equipo obligatorio de un coche? opción múltiple', 'multiple', 'texto', 1, '2025-07-24 11:40:37'),
(73, '¿Qué elementos son obligatorios en una motocicleta para la seguridad? opción múltiple ', 'multiple', 'texto', 1, '2025-07-24 11:42:58'),
(74, '¿Qué precauciones debe tomar un motorista en días de lluvia?', 'multiple', 'texto', 1, '2025-07-24 11:44:14'),
(75, '¿Qué elementos deben revisarse antes de iniciar un viaje con un camión? opción múltiple', 'unica', 'texto', 1, '2025-07-24 11:47:08'),
(76, '¿Qué circunstancias obligan a detener un camión en carretera? opción múltiple', 'multiple', 'texto', 1, '2025-07-24 11:54:49'),
(77, '¿Qué obligaciones tiene un conductor de autobús hacia sus pasajeros? opción múltiple', 'multiple', 'texto', 1, '2025-07-24 11:58:11'),
(78, '¿Qué elementos son obligatorios en un autobús de transporte escolar? opción múltiple', 'multiple', 'texto', 1, '2025-07-24 11:59:55'),
(79, '¿Qué situaciones pueden aumentar la distancia de frenado de un vehículo? opción múltiple', 'unica', 'texto', 1, '2025-07-24 12:02:15'),
(80, '¿Cuántos metros, antes de llegar a una intersección,  deberá anunciar la  \r\nmaniobra si se propone girar a la  izquierda ó a la derecha? opción única ', 'unica', 'texto', 1, '2025-07-24 12:21:59'),
(81, ' ¿Quién tiene prioridad de paso en las carreteras, fuera de las zonas urbanas? opción única ', 'unica', 'texto', 1, '2025-07-24 12:23:50'),
(82, '¿Cuáles son los factores que intervienen en el tránsito? opción única ', 'unica', 'texto', 1, '2025-07-24 12:25:39'),
(83, '¿Por qué lugar de la calzada se debe adelantar a otros  vehículos? opción unica', 'unica', 'texto', 1, '2025-07-24 12:27:24'),
(84, 'Al llegar a una encrucijada o cruce de calle, ¿A qué velocidad se debe  \r\ncircular?', 'unica', 'texto', 1, '2025-07-24 12:30:47'),
(85, '¿Qué se entiende por detener un vehículo? opción única', 'unica', 'texto', 1, '2025-07-24 12:32:16'),
(86, '¿Cuál es el límite máximo de velocidad en zona urbana? opción única ', 'unica', 'texto', 1, '2025-07-24 12:34:46'),
(87, ' ¿Qué debe hacer Usted si llegando a un cruce en el que el semáforo indica \r\nroja, un Policía le hace señas para que siga?', 'unica', 'texto', 1, '2025-07-24 12:36:57'),
(88, 'La distancia a la que Usted debe mantenerse detrás del vehículo que va  \r\ndelante suyo, depende de:', 'unica', 'texto', 1, '2025-07-24 12:39:09'),
(89, '¿Qué transmiten las señales reglamentarias? opción única ', 'unica', 'texto', 1, '2025-07-24 12:42:05'),
(90, '¿Qué transmiten las señales de prevención?', 'unica', 'texto', 1, '2025-07-24 12:44:03');

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
(314, 57, 13),
(315, 58, 4),
(316, 59, 4),
(317, 60, 4),
(318, 61, 1),
(319, 62, 1),
(320, 63, 1),
(321, 64, 6),
(322, 65, 6),
(323, 66, 6),
(324, 67, 9),
(325, 68, 9),
(326, 69, 9),
(327, 70, 4),
(328, 71, 4),
(329, 72, 4),
(330, 73, 1),
(332, 74, 1),
(333, 75, 6),
(334, 76, 6),
(335, 77, 9),
(336, 78, 9),
(337, 79, 1),
(338, 79, 2),
(339, 79, 3),
(340, 79, 12),
(341, 79, 4),
(342, 79, 5),
(343, 79, 6),
(344, 79, 8),
(345, 79, 7),
(346, 79, 9),
(347, 79, 11),
(348, 79, 10),
(349, 79, 13),
(350, 80, 1),
(351, 80, 2),
(352, 80, 3),
(353, 80, 12),
(354, 80, 4),
(355, 80, 5),
(356, 80, 6),
(357, 80, 8),
(358, 80, 7),
(359, 80, 9),
(360, 80, 11),
(361, 80, 10),
(362, 80, 13),
(363, 81, 1),
(364, 81, 2),
(365, 81, 3),
(366, 81, 12),
(367, 81, 4),
(368, 81, 5),
(369, 81, 6),
(370, 81, 8),
(371, 81, 7),
(372, 81, 9),
(373, 81, 11),
(374, 81, 10),
(375, 81, 13),
(376, 82, 1),
(377, 82, 2),
(378, 82, 3),
(379, 82, 12),
(380, 82, 4),
(381, 82, 5),
(382, 82, 6),
(383, 82, 8),
(384, 82, 7),
(385, 82, 9),
(386, 82, 11),
(387, 82, 10),
(388, 82, 13),
(389, 83, 1),
(390, 83, 2),
(391, 83, 3),
(392, 83, 12),
(393, 83, 4),
(394, 83, 5),
(395, 83, 6),
(396, 83, 8),
(397, 83, 7),
(398, 83, 9),
(399, 83, 11),
(400, 83, 10),
(401, 83, 13),
(402, 84, 1),
(403, 84, 2),
(404, 84, 3),
(405, 84, 12),
(406, 84, 4),
(407, 84, 5),
(408, 84, 6),
(409, 84, 8),
(410, 84, 7),
(411, 84, 9),
(412, 84, 11),
(413, 84, 10),
(414, 84, 13),
(415, 85, 4),
(416, 85, 5),
(417, 86, 1),
(418, 86, 2),
(419, 86, 3),
(420, 86, 12),
(421, 86, 4),
(422, 86, 5),
(423, 86, 6),
(424, 86, 8),
(425, 86, 7),
(426, 86, 9),
(427, 86, 11),
(428, 86, 10),
(429, 86, 13),
(430, 87, 1),
(431, 87, 2),
(432, 87, 3),
(433, 87, 12),
(434, 87, 4),
(435, 87, 5),
(436, 87, 6),
(437, 87, 8),
(438, 87, 7),
(439, 87, 9),
(440, 87, 11),
(441, 87, 10),
(442, 87, 13),
(443, 88, 1),
(444, 88, 2),
(445, 88, 3),
(446, 88, 12),
(447, 88, 4),
(448, 88, 5),
(449, 88, 6),
(450, 88, 8),
(451, 88, 7),
(452, 88, 9),
(453, 88, 11),
(454, 88, 10),
(455, 88, 13),
(456, 89, 1),
(457, 89, 2),
(458, 89, 3),
(459, 89, 12),
(460, 89, 4),
(461, 89, 5),
(462, 89, 6),
(463, 89, 8),
(464, 89, 7),
(465, 89, 9),
(466, 89, 11),
(467, 89, 10),
(468, 89, 13),
(469, 90, 1),
(470, 90, 2),
(471, 90, 3),
(472, 90, 12),
(473, 90, 4),
(474, 90, 5),
(475, 90, 6),
(476, 90, 8),
(477, 90, 7),
(478, 90, 9),
(479, 90, 11),
(480, 90, 10),
(481, 90, 13);

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
  ADD KEY `escuela_id` (`escuela_id`);
ALTER TABLE `estudiantes` ADD FULLTEXT KEY `email_3` (`email`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT de la tabla `estudiante_categorias`
--
ALTER TABLE `estudiante_categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT de la tabla `examenes`
--
ALTER TABLE `examenes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT de la tabla `examen_preguntas`
--
ALTER TABLE `examen_preguntas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT de la tabla `imagenes_pregunta`
--
ALTER TABLE `imagenes_pregunta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `opciones_pregunta`
--
ALTER TABLE `opciones_pregunta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=262;

--
-- AUTO_INCREMENT de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT de la tabla `pregunta_categoria`
--
ALTER TABLE `pregunta_categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=482;

--
-- AUTO_INCREMENT de la tabla `respuestas_estudiante`
--
ALTER TABLE `respuestas_estudiante`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=340;

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

