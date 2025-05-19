CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    contrasena_hash VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'examinador', 'operador') DEFAULT 'operador',
    activo BOOLEAN DEFAULT 1,
    creado_en DATETIME DEFAULT CURRENT_TIMESTAMP
);


-- Tabla de categorías de carnet
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    descripcion TEXT
);

-- Tabla de escuelas de conducción
CREATE TABLE escuelas_conduccion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    ciudad VARCHAR(100) NOT NULL,
    pais VARCHAR(100) DEFAULT 'Guinea Ecuatorial'
);

-- Tabla de estudiantes
CREATE TABLE estudiantes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dni VARCHAR(20) UNIQUE NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(250) NOT NULL,
    direccion VARCHAR(250) NOT NULL,
    email VARCHAR(100) UNIQUE DEFAULT NULL,
    telefono VARCHAR(20),
    fecha_nacimiento DATE,
    escuela_id INT, 
    estado ENUM('activo', 'inactivo') DEFAULT 'activo',
    creado_en DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (escuela_id) REFERENCES escuelas_conduccion(id)
        ON DELETE SET NULL ON UPDATE CASCADE 
);
CREATE TABLE estudiante_categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    estudiante_id INT NOT NULL,
    categoria_id INT NOT NULL,
    estado ENUM('pendiente', 'aprobado', 'rechazado', 'en_proceso') DEFAULT 'pendiente',
    fecha_asignacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_aprobacion DATETIME,
    FOREIGN KEY (estudiante_id) REFERENCES estudiantes(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

-- Tabla de preguntas
CREATE TABLE preguntas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    texto TEXT NOT NULL,
    tipo ENUM('unica', 'multiple', 'vf') NOT NULL,
    tipo_contenido ENUM('texto', 'ilustracion') NOT NULL,
    activa BOOLEAN DEFAULT TRUE,
    creado_en DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de imágenes por pregunta
CREATE TABLE imagenes_pregunta (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pregunta_id INT NOT NULL,
    ruta_imagen VARCHAR(255) NOT NULL,
    descripcion TEXT,
    FOREIGN KEY (pregunta_id) REFERENCES preguntas(id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

-- Relación muchas-a-muchas entre preguntas y categorías
CREATE TABLE pregunta_categoria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pregunta_id INT NOT NULL,
    categoria_id INT NOT NULL,
    FOREIGN KEY (pregunta_id) REFERENCES preguntas(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

-- Tabla de opciones para cada pregunta
CREATE TABLE opciones_pregunta (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pregunta_id INT NOT NULL,
    texto VARCHAR(255) NOT NULL,
    es_correcta BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (pregunta_id) REFERENCES preguntas(id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

-- Tabla de exámenes por estudiante
CREATE TABLE examenes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    estudiante_id INT NOT NULL,
    categoria_id INT NOT NULL,
    asignado_por INT,
    fecha_asignacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    total_preguntas INT NOT NULL,
    estado ENUM('pendiente', 'en_progreso', 'finalizado') DEFAULT 'pendiente',
    calificacion DECIMAL(5,2),
    codigo_acceso VARCHAR(20) UNIQUE NOT NULL, -- ← nuevo campo para acceso por token 
FOREIGN KEY (asignado_por) REFERENCES usuarios(id)
    ON DELETE SET NULL ON UPDATE CASCADE,
     FOREIGN KEY (categoria_id) REFERENCES categorias(id)
    ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (estudiante_id) REFERENCES estudiantes(id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

-- Tabla de preguntas asignadas a un examen
CREATE TABLE examen_preguntas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    examen_id INT NOT NULL,
    pregunta_id INT NOT NULL,
    respondida BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (examen_id) REFERENCES examenes(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (pregunta_id) REFERENCES preguntas(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Respuestas del estudiante a las preguntas asignadas
CREATE TABLE respuestas_estudiante (
    id INT AUTO_INCREMENT PRIMARY KEY,
    examen_pregunta_id INT NOT NULL,
    opcion_id INT,
    fecha_respuesta DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (examen_pregunta_id) REFERENCES examen_preguntas(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (opcion_id) REFERENCES opciones_pregunta(id)
        ON DELETE SET NULL ON UPDATE CASCADE
);

-- Correos enviados automáticamente
CREATE TABLE correos_enviados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    estudiante_id INT,
    tipo_correo ENUM('registro', 'invitacion_examen', 'resultado', 'recordatorio'),
    asunto VARCHAR(255),
    cuerpo TEXT,
    enviado_por INT,
    enviado_en DATETIME DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY (enviado_por) REFERENCES usuarios(id)
    ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (estudiante_id) REFERENCES estudiantes(id)
        ON DELETE SET NULL ON UPDATE CASCADE
);
