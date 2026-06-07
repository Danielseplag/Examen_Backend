-- Migración 003: Crear tabla tallas
-- Descripción: Tabla de tallas de camiseta disponibles

CREATE TABLE IF NOT EXISTS tallas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(20) NOT NULL UNIQUE COMMENT 'Nombre de la talla (XS, S, M, L, XL, XXL)',
    descripcion VARCHAR(100) NULL COMMENT 'Descripción de la talla',
    activa BOOLEAN DEFAULT 1 COMMENT 'Indica si la talla está disponible',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_nombre (nombre),
    INDEX idx_activa (activa)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar tallas estándar
INSERT IGNORE INTO tallas (nombre, descripcion) VALUES
('XS', 'Extra Small'),
('S', 'Small'),
('M', 'Medium'),
('L', 'Large'),
('XL', 'Extra Large'),
('XXL', 'Double Extra Large');

-- Migración 003 completada
