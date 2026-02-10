DROP DATABASE IF EXISTS web_examenes;
CREATE DATABASE web_examenes;
USE web_examenes;



-- CATEGORIAS
CREATE TABLE categorias (
  id int(11) NOT NULL AUTO_INCREMENT,
  nombre varchar(50) NOT NULL,
  descripcion text DEFAULT NULL,
  edad_minima tinyint(3) UNSIGNED NOT NULL DEFAULT 15,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ESCUELAS_CONDUCCION
CREATE TABLE escuelas_conduccion (
  id int(11) NOT NULL AUTO_INCREMENT,
  nombre varchar(100) NOT NULL,
  telefono varchar(25) NOT NULL,
  director varchar(100) NOT NULL,
  nif varchar(100) NOT NULL,
  ciudad varchar(100) NOT NULL,
  correo varchar(25) DEFAULT NULL,
  pais varchar(100) DEFAULT 'Guinea Ecuatorial',
  ubicacion varchar(50) NOT NULL,
  numero_registro varchar(100) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- USUARIOS
CREATE TABLE usuarios (
  id int(11) NOT NULL AUTO_INCREMENT,
  nombre varchar(100) NOT NULL,
  email varchar(100) NOT NULL UNIQUE,
  contrasena_hash varchar(255) NOT NULL,
  rol enum('admin','examinador','secretaria') NOT NULL,
  creado_en datetime DEFAULT current_timestamp(),
  activo tinyint(1) DEFAULT 1,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- PREGUNTAS
CREATE TABLE preguntas (
  id int(11) NOT NULL AUTO_INCREMENT,
  texto text NOT NULL,
  tipo enum('unica','multiple','vf') NOT NULL,
  tipo_contenido enum('texto','ilustracion') NOT NULL,
  activa tinyint(1) DEFAULT 1,
  creado_en datetime DEFAULT current_timestamp(),
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ESTUDIANTES
CREATE TABLE estudiantes (
  id int(11) NOT NULL AUTO_INCREMENT,
  dni varchar(20) NOT NULL UNIQUE,
  nombre varchar(100) NOT NULL,
  apellidos varchar(250) DEFAULT NULL,
  email varchar(100) DEFAULT NULL,
  telefono varchar(20) DEFAULT NULL,
  fecha_nacimiento date DEFAULT NULL,
  direccion varchar(250) DEFAULT NULL,
  usuario varchar(100) NOT NULL,
  Doc varchar(255) NOT NULL,
  escuela_id int(11) DEFAULT NULL,
  estado enum('activo','inactivo') DEFAULT 'activo',
  creado_en datetime DEFAULT current_timestamp(),
  PRIMARY KEY (id),
  CONSTRAINT fk_estudiante_escuela FOREIGN KEY (escuela_id) REFERENCES escuelas_conduccion (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- OPCIONES_PREGUNTA
CREATE TABLE opciones_pregunta (
  id int(11) NOT NULL AUTO_INCREMENT,
  pregunta_id int(11) NOT NULL,
  texto varchar(255) NOT NULL,
  es_correcta tinyint(1) DEFAULT 0,
  PRIMARY KEY (id),
  CONSTRAINT fk_opciones_pregunta FOREIGN KEY (pregunta_id) REFERENCES preguntas (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- IMAGENES_PREGUNTA
CREATE TABLE imagenes_pregunta (
  id int(11) NOT NULL AUTO_INCREMENT,
  pregunta_id int(11) NOT NULL,
  ruta_imagen varchar(255) NOT NULL,
  descripcion text DEFAULT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_imagenes_pregunta FOREIGN KEY (pregunta_id) REFERENCES preguntas (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- PREGUNTA_CATEGORIA
CREATE TABLE pregunta_categoria (
  id int(11) NOT NULL AUTO_INCREMENT,
  pregunta_id int(11) NOT NULL,
  categoria_id int(11) NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_pc_pregunta FOREIGN KEY (pregunta_id) REFERENCES preguntas (id) ON DELETE CASCADE,
  CONSTRAINT fk_pc_categoria FOREIGN KEY (categoria_id) REFERENCES categorias (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ESTUDIANTE_CATEGORIAS
CREATE TABLE estudiante_categorias (
  id int(11) NOT NULL AUTO_INCREMENT,
  estudiante_id int(11) NOT NULL,
  categoria_id int(11) NOT NULL,
  estado enum('pendiente','aprobado','rechazado','en_proceso') DEFAULT 'pendiente',
  fecha_asignacion datetime DEFAULT current_timestamp(),
  fecha_aprobacion datetime DEFAULT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_ec_estudiante FOREIGN KEY (estudiante_id) REFERENCES estudiantes (id) ON DELETE CASCADE,
  CONSTRAINT fk_ec_categoria FOREIGN KEY (categoria_id) REFERENCES categorias (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- EXAMENES
CREATE TABLE examenes (
  id int(11) NOT NULL AUTO_INCREMENT,
  estudiante_id int(11) NOT NULL,
  categoria_id int(11) NOT NULL,
  asignado_por int(11) DEFAULT NULL,
  fecha_asignacion datetime DEFAULT current_timestamp(),
  duracion tinyint(1) DEFAULT 0,
  total_preguntas int(11) NOT NULL,
  estado enum('pendiente','en_progreso','finalizado','INICIO') DEFAULT 'pendiente',
  calificacion decimal(5,2) DEFAULT NULL,
  codigo_acceso varchar(20) NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_exam_estudiante FOREIGN KEY (estudiante_id) REFERENCES estudiantes (id) ON DELETE CASCADE,
  CONSTRAINT fk_exam_categoria FOREIGN KEY (categoria_id) REFERENCES categorias (id),
  CONSTRAINT fk_exam_usuario FOREIGN KEY (asignado_por) REFERENCES usuarios (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- EXAMEN_PREGUNTAS
CREATE TABLE examen_preguntas (
  id int(11) NOT NULL AUTO_INCREMENT,
  examen_id int(11) NOT NULL,
  pregunta_id int(11) NOT NULL,
  respondida tinyint(1) DEFAULT 0,
  PRIMARY KEY (id),
  CONSTRAINT fk_ep_examen FOREIGN KEY (examen_id) REFERENCES examenes (id) ON DELETE CASCADE,
  CONSTRAINT fk_ep_pregunta FOREIGN KEY (pregunta_id) REFERENCES preguntas (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- RESPUESTAS_ESTUDIANTE
CREATE TABLE respuestas_estudiante (
  id int(11) NOT NULL AUTO_INCREMENT,
  examen_pregunta_id int(11) NOT NULL,
  opcion_id int(11) DEFAULT NULL,
  fecha_respuesta datetime DEFAULT current_timestamp(),
  PRIMARY KEY (id),
  CONSTRAINT fk_re_examen_pregunta FOREIGN KEY (examen_pregunta_id) REFERENCES examen_preguntas (id) ON DELETE CASCADE,
  CONSTRAINT fk_re_opcion FOREIGN KEY (opcion_id) REFERENCES opciones_pregunta (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- CORREOS_ENVIADOS
CREATE TABLE correos_enviados (
  id int(11) NOT NULL AUTO_INCREMENT,
  estudiante_id int(11) DEFAULT NULL,
  tipo_correo enum('registro','invitacion_examen','resultado','recordatorio') DEFAULT NULL,
  asunto varchar(255) DEFAULT NULL,
  cuerpo text DEFAULT NULL,
  enviado_por int(11) DEFAULT NULL,
  enviado_en datetime DEFAULT current_timestamp(),
  PRIMARY KEY (id),
  CONSTRAINT fk_correo_estudiante FOREIGN KEY (estudiante_id) REFERENCES estudiantes (id) ON DELETE SET NULL,
  CONSTRAINT fk_correo_usuario FOREIGN KEY (enviado_por) REFERENCES usuarios (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
