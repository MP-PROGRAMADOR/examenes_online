-- Eliminar y crear la base de datos
DROP DATABASE IF EXISTS examenes_online;
CREATE DATABASE examenes_online CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE examenes_online;

-- Tabla: usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(50) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'docente') NOT NULL DEFAULT 'docente',
    activo BOOLEAN DEFAULT TRUE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla: escuelas_conduccion
CREATE TABLE escuelas_conduccion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    direccion VARCHAR(255),
    telefono VARCHAR(20),
    email VARCHAR(255)
);

-- Tabla: categorias_carne
CREATE TABLE categorias_carne (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion TEXT
);

-- Tabla: estudiantes
CREATE TABLE estudiantes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    escuela_id INT NOT NULL,
    numero_identificacion VARCHAR(50) NOT NULL UNIQUE,
    nombre VARCHAR(255) NOT NULL,
    apellido VARCHAR(255) NOT NULL,
    fecha_nacimiento DATE,
    telefono VARCHAR(20),
    direccion VARCHAR(255),
    categoria_carne_id INT NOT NULL,
    codigo_registro_examen VARCHAR(100) NOT NULL UNIQUE,
    examen_realizado BOOLEAN DEFAULT FALSE,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (escuela_id) REFERENCES escuelas_conduccion(id) ON UPDATE CASCADE ON DELETE RESTRICT,
    FOREIGN KEY (categoria_carne_id) REFERENCES categorias_carne(id) ON UPDATE CASCADE ON DELETE RESTRICT
);

-- Tabla: examenes
CREATE TABLE examenes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categoria_carne_id INT NOT NULL,
    titulo VARCHAR(255) NOT NULL,
    descripcion TEXT,
    duracion_minutos INT,
    total_preguntas INT,
    preguntas_aleatorias BOOLEAN DEFAULT TRUE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_carne_id) REFERENCES categorias_carne(id) ON UPDATE CASCADE ON DELETE RESTRICT
);

-- Tabla: preguntas
CREATE TABLE preguntas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    examen_id INT NOT NULL,
    texto_pregunta TEXT NOT NULL,
    tipo_pregunta ENUM('multiple_choice', 'respuesta_unica', 'verdadero_falso', 'ilustrada') NOT NULL DEFAULT 'multiple_choice',
    imagen VARCHAR(255), -- imagen principal
    activo BOOLEAN DEFAULT TRUE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (examen_id) REFERENCES examenes(id) ON DELETE CASCADE
);

-- Tabla: imagenes_pregunta
CREATE TABLE imagenes_pregunta (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pregunta_id INT NOT NULL,
    ruta_imagen VARCHAR(255) NOT NULL,
    fecha_subida TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pregunta_id) REFERENCES preguntas(id) ON DELETE CASCADE
);

-- Tabla: opciones_pregunta
CREATE TABLE opciones_pregunta (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pregunta_id INT NOT NULL,
    texto_opcion TEXT NOT NULL,
    es_correcta BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (pregunta_id) REFERENCES preguntas(id) ON DELETE CASCADE
);

-- Tabla: intentos_examen
CREATE TABLE intentos_examen (
    id INT AUTO_INCREMENT PRIMARY KEY,
    estudiante_id INT NOT NULL,
    examen_id INT NOT NULL,
    fecha_inicio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_fin TIMESTAMP NULL,
    completado BOOLEAN DEFAULT FALSE,
    codigo_acceso_utilizado VARCHAR(100) NOT NULL,
    FOREIGN KEY (estudiante_id) REFERENCES estudiantes(id) ON DELETE CASCADE,
    FOREIGN KEY (examen_id) REFERENCES examenes(id) ON DELETE RESTRICT
);

-- Tabla: respuestas_estudiante
CREATE TABLE respuestas_estudiante (
    id INT AUTO_INCREMENT PRIMARY KEY,
    intento_examen_id INT NOT NULL,
    pregunta_id INT NOT NULL,
    opcion_seleccionada_id INT NULL,
    respuesta_texto TEXT NULL,
    es_correcta BOOLEAN,
    FOREIGN KEY (intento_examen_id) REFERENCES intentos_examen(id) ON DELETE CASCADE,
    FOREIGN KEY (pregunta_id) REFERENCES preguntas(id) ON DELETE RESTRICT,
    FOREIGN KEY (opcion_seleccionada_id) REFERENCES opciones_pregunta(id) ON DELETE SET NULL
);

-- Tabla: logs_sistema (auditoría)
CREATE TABLE logs_sistema (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    accion VARCHAR(100) NOT NULL,
    descripcion TEXT,
    ip_origen VARCHAR(45),
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabla: configuraciones_sistema
CREATE TABLE configuraciones_sistema (
    id INT AUTO_INCREMENT PRIMARY KEY,
    clave VARCHAR(100) NOT NULL UNIQUE,
    valor TEXT NOT NULL,
    descripcion TEXT
);
