-- Verificar si la base de datos "examenes_online" existe
DROP DATABASE IF EXISTS examenes_online;
CREATE DATABASE IF NOT EXISTS examenes_online;

-- Usar la base de datos "examenes_online"
USE examenes_online;
--
-- Tabla: entidades_trafico
CREATE TABLE IF NOT EXISTS entidades_trafico (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL UNIQUE,
    codigo_entidad VARCHAR(20) UNIQUE,
    direccion VARCHAR(255),
    telefono VARCHAR(20),
    email VARCHAR(255) UNIQUE,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla: escuelas_conduccion
CREATE TABLE IF NOT EXISTS escuelas_conduccion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    entidad_trafico_id INT NOT NULL,
    nombre VARCHAR(255) NOT NULL UNIQUE, 
    direccion VARCHAR(255),
    telefono VARCHAR(20),
    email VARCHAR(255) UNIQUE,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (entidad_trafico_id) REFERENCES entidades_trafico(id) ON UPDATE CASCADE ON DELETE NO ACTION
);

-- Tabla: estudiantes
CREATE TABLE IF NOT EXISTS estudiantes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    escuela_id INT NOT NULL,
    numero_identificacion VARCHAR(50) UNIQUE NOT NULL,
    nombre VARCHAR(255) NOT NULL,
    apellido VARCHAR(255) NOT NULL,
    fecha_nacimiento DATE,
    email VARCHAR(255) NOT NULL UNIQUE,
    telefono VARCHAR(20),
    direccion VARCHAR(255),
    categoria_carne VARCHAR(10) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    codigo_registro_examen VARCHAR(100) UNIQUE NOT NULL, -- Código único para acceder al examen
    examen_realizado BOOLEAN DEFAULT FALSE, -- Indica si el estudiante ya realizó el examen
    FOREIGN KEY (escuela_id) REFERENCES escuelas_conduccion(id) ON UPDATE CASCADE ON DELETE NO ACTION,
    INDEX (numero_identificacion),
    INDEX (email),
    INDEX (codigo_registro_examen)
);

-- Tabla: examenes
CREATE TABLE IF NOT EXISTS examenes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    entidad_trafico_id INT NOT NULL,
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT,
    fecha_inicio DATETIME NOT NULL,
    fecha_fin DATETIME NOT NULL,
    duracion_minutos INT,
    total_preguntas INT NOT NULL,
    preguntas_aleatorias BOOLEAN DEFAULT TRUE,
    orden_preguntas TEXT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (entidad_trafico_id) REFERENCES entidades_trafico(id) ON UPDATE CASCADE ON DELETE NO ACTION
);

-- Tabla: categorias_examen
CREATE TABLE IF NOT EXISTS categorias_examen (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL UNIQUE,
    descripcion TEXT
);

-- Tabla de relación: examenes_categorias
CREATE TABLE IF NOT EXISTS examenes_categorias (
    examen_id INT NOT NULL,
    categoria_id INT NOT NULL,
    PRIMARY KEY (examen_id, categoria_id),
    FOREIGN KEY (examen_id) REFERENCES examenes(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (categoria_id) REFERENCES categorias_examen(id) ON UPDATE CASCADE ON DELETE CASCADE
);

-- Tabla: preguntas
CREATE TABLE IF NOT EXISTS preguntas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categoria_id INT NOT NULL,
    texto_pregunta TEXT NOT NULL,
    imagen_url VARCHAR(255) NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias_examen(id) ON UPDATE CASCADE ON DELETE NO ACTION
);

-- Tabla: opciones_pregunta
CREATE TABLE IF NOT EXISTS opciones_pregunta (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pregunta_id INT NOT NULL,
    texto_opcion VARCHAR(255) NOT NULL,
    es_correcta BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (pregunta_id) REFERENCES preguntas(id) ON UPDATE CASCADE ON DELETE CASCADE
);

-- Tabla: intentos_examen
CREATE TABLE IF NOT EXISTS intentos_examen (
    id INT AUTO_INCREMENT PRIMARY KEY,
    estudiante_id INT NOT NULL,
    examen_id INT NOT NULL,
    fecha_inicio DATETIME NOT NULL,
    fecha_fin DATETIME NULL,
    respuestas_estudiante TEXT NULL,
    calificacion DECIMAL(5, 2) NULL,
    aprobado BOOLEAN NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (estudiante_id) REFERENCES estudiantes(id) ON UPDATE CASCADE ON DELETE NO ACTION,
    FOREIGN KEY (examen_id) REFERENCES examenes(id) ON UPDATE CASCADE ON DELETE NO ACTION,
    UNIQUE KEY estudiante_examen_unico (estudiante_id, examen_id) -- Asegura que un estudiante no intente el mismo examen varias veces (si se permite un solo intento)
);

-- Tabla: resultados_detallados
CREATE TABLE IF NOT EXISTS resultados_detallados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    intento_id INT NOT NULL,
    pregunta_id INT NOT NULL,
    opcion_seleccionada_id INT NULL,
    es_correcta BOOLEAN NULL,
    FOREIGN KEY (intento_id) REFERENCES intentos_examen(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (pregunta_id) REFERENCES preguntas(id) ON UPDATE CASCADE ON DELETE NO ACTION,
    FOREIGN KEY (opcion_seleccionada_id) REFERENCES opciones_pregunta(id) ON UPDATE CASCADE ON DELETE SET NULL -- Si la opción se elimina, el registro del resultado se mantiene
);

-- Tabla: usuarios (para administradores del sistema y personal de la entidad de tráfico)
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    entidad_trafico_id INT NULL, -- FK: Entidad a la que pertenece el usuario (si aplica)
    nombre_usuario VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE,
    rol ENUM('admin', 'docente') NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultimo_login TIMESTAMP NULL,
    activo BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (entidad_trafico_id) REFERENCES entidades_trafico(id) ON UPDATE CASCADE ON DELETE SET NULL
);