

-- Verificar si la base de datos "examenes_online" existe
DROP DATABASE IF EXISTS examenes_online;
CREATE DATABASE IF NOT EXISTS examenes_online;

-- Usar la base de datos "examenes_online"
USE examenes_online;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

 

--Tablas Principales:
/*
1. users: Almacena la información de los usuarios que interactúan con la plataforma (principalmente administradores).

***/
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin') NOT NULL DEFAULT 'admin', -- Limitado a administradores
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

/*
2. subjects: Almacena las áreas temáticas de los exámenes (ej: Normativa, Señales, Mecánica).
***/
CREATE TABLE tematica_examenes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

/****
3. exams: Almacena la información de los exámenes.

**/
CREATE TABLE examenes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tematica_examenes_id INT NOT NULL REFERENCES tematica_examenes(id) ON DELETE CASCADE,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    duration_minutes INT,
    start_time DATETIME,
    end_time DATETIME,
    created_by INT NOT NULL REFERENCES usuarios(id) ON DELETE RESTRICT, -- Quién creó el examen
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

/****
4. questions: Almacena la información de las preguntas.

***/

CREATE TABLE preguntas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    exam_id INT NOT NULL REFERENCES exams(id) ON DELETE CASCADE,
    question_text TEXT NOT NULL,
    question_type ENUM('multiple_choice', 'true_false', 'short_answer') NOT NULL,
    created_by INT NOT NULL REFERENCES users(id) ON DELETE RESTRICT, -- Quién creó la pregunta
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

/**
5. multiple_choice_options: Almacena las opciones para las preguntas de opción múltiple.
*/

CREATE TABLE multiple_choice_options (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_id INT NOT NULL REFERENCES questions(id) ON DELETE CASCADE,
    option_text VARCHAR(255) NOT NULL,
    is_correct BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

/**
6. true_false_options: Almacena la respuesta correcta para las preguntas de verdadero/falso.
**/

CREATE TABLE falso_verdad_options (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_id INT NOT NULL REFERENCES questions(id) ON DELETE CASCADE,
    is_correct BOOLEAN NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

/*
7. short_answer_answers: Almacena las respuestas correctas esperadas para las preguntas de respuesta corta (puede haber varias).
*/

CREATE TABLE short_answer_answers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_id INT NOT NULL REFERENCES questions(id) ON DELETE CASCADE,
    answer_text TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

/*
8. exam_submissions: Almacena la información de los intentos de examen realizados por los estudiantes.
*/

CREATE TABLE exam_submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    exam_id INT NOT NULL REFERENCES exams(id) ON DELETE CASCADE,
    student_identifier VARCHAR(255) NOT NULL, -- Identificador único del estudiante (puede ser un ID externo, matrícula, etc.)
    submission_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_graded BOOLEAN NOT NULL DEFAULT FALSE,
    score DECIMAL(5, 2), -- Puntuación obtenida en el examen
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `unique_submission` (`exam_id`, `student_identifier`) -- Evitar múltiples envíos del mismo estudiante para el mismo examen
);


/*
9. submission_answers: Almacena las respuestas dadas por el estudiante a cada pregunta en un intento de examen.
*/

CREATE TABLE submission_answers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    exam_submission_id INT NOT NULL REFERENCES exam_submissions(id) ON DELETE CASCADE,
    question_id INT NOT NULL REFERENCES questions(id) ON DELETE CASCADE,
    answer_text TEXT, -- Respuesta del estudiante (para todos los tipos de pregunta)
    is_correct BOOLEAN, -- Si la respuesta fue correcta (para calificación automática)
    score_awarded DECIMAL(3, 2), -- Puntuación asignada a esta respuesta (para calificación manual)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


/*
Tablas de Relación (si es necesario):

exam_questions: Si quieres un orden específico de preguntas por examen o información adicional sobre la pregunta en el contexto del examen.
*/

CREATE TABLE exam_questions (
    exam_id INT NOT NULL REFERENCES exams(id) ON DELETE CASCADE,
    question_id INT NOT NULL REFERENCES questions(id) ON DELETE CASCADE,
    question_order INT,
    PRIMARY KEY (exam_id, question_id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

/*
10. driving_license_categories: Almacena las diferentes categorías de carné de conducir (A, B, C, D, etc.).
*/

CREATE TABLE driving_license_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE, -- Ej: A, B, C1, D
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

/*
Modificaciones en las Tablas Existentes:

exams: Relacionar los exámenes con las categorías de carné.
*/

ALTER TABLE examenes
ADD COLUMN driving_license_category_id INT REFERENCES driving_license_categories(id) ON DELETE RESTRICT;

/*
questions: Relacionar las preguntas con las categorías de carné. Esto permite que una pregunta sea específica para una o varias categorías.
Opción 1: Una pregunta pertenece a una única categoría principal.
*/
ALTER TABLE preguntas
ADD COLUMN driving_license_category_id INT REFERENCES driving_license_categories(id) ON DELETE RESTRICT;

/*
Opción 2: Una pregunta puede pertenecer a múltiples categorías (tabla de relación).
*/
CREATE TABLE question_driving_license_categories (
    question_id INT NOT NULL REFERENCES questions(id) ON DELETE CASCADE,
    driving_license_category_id INT NOT NULL REFERENCES driving_license_categories(id) ON DELETE CASCADE,
    PRIMARY KEY (question_id, driving_license_category_id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

/*
2. users: Podría almacenar información adicional relacionada con los administradores que gestionan el sistema.
*/

ALTER TABLE usuarios
ADD COLUMN is_active BOOLEAN NOT NULL DEFAULT TRUE,
ADD COLUMN last_login TIMESTAMP NULL;

/*
12. access_codes: Almacena los códigos que los estudiantes usarán para acceder a los exámenes.
*/
CREATE TABLE access_codes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(255) NOT NULL UNIQUE,
    exam_id INT NOT NULL REFERENCES exams(id) ON DELETE CASCADE,
    student_identifier VARCHAR(255) NULL, -- Opcional: para vincular el código a un estudiante específico
    is_used BOOLEAN NOT NULL DEFAULT FALSE,
    used_at TIMESTAMP NULL,
    expires_at DATETIME NULL, -- Opcional: fecha de expiración del código
    created_by INT NOT NULL REFERENCES users(id) ON DELETE RESTRICT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

/*
13. email_queue: Almacena los correos electrónicos que deben ser enviados por el sistema.
*/
CREATE TABLE email_queue (
    id INT AUTO_INCREMENT PRIMARY KEY,
    recipient_email VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    body TEXT NOT NULL,
    status ENUM('pending', 'sending', 'sent', 'failed') NOT NULL DEFAULT 'pending',
    attempts INT NOT NULL DEFAULT 0,
    last_attempted_at TIMESTAMP NULL,
    sent_at TIMESTAMP NULL,
    error_message TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
