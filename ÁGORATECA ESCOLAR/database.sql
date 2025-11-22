-- Crear base de datos
CREATE DATABASE IF NOT EXISTS u339208770_Agorateca CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Usar la base de datos
USE u339208770_Agorateca;

-- Eliminar tabla si existe (para recrearla con los campos nuevos)
DROP TABLE IF EXISTS users;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    is_verified TINYINT(1) NOT NULL DEFAULT 1,
    verification_token VARCHAR(255) NULL,
    remember_token VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_username (username),
    INDEX idx_remember_token (remember_token) -- Índice para búsquedas rápidas
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Nueva tabla para quejas y sugerencias
DROP TABLE IF EXISTS suggestions;

CREATE TABLE IF NOT EXISTS suggestions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL, -- Relación al ID del usuario
    title VARCHAR(255) NOT NULL, -- Título de la queja/sugerencia
    description TEXT NOT NULL, -- Descripción detallada
    type ENUM('Queja', 'Sugerencia') NOT NULL, -- Tipo: Queja o Sugerencia
    building ENUM(
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'P', 'L', 'M', 'N', 'Administrativo'
    ) NOT NULL, -- Edificios especificados
    category ENUM(
        'Servicios Escolares', 'Instalaciones', 'Profesores', 'Administrativo', 'Otro'
    ) NOT NULL, -- Áreas o Categorías
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Fecha y hora de creación
    FOREIGN KEY (user_id) REFERENCES users(id) -- Relación con la tabla de usuarios
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;