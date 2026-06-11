-- Migración 001: Crear tabla camisetas
-- Descripción: Tabla principal de camisetas con información de club, precio y ofertas

CREATE TABLE IF NOT EXISTS camisetas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sku VARCHAR(50) NOT NULL UNIQUE COMMENT 'SKU único de la camiseta',
    titulo VARCHAR(255) NOT NULL COMMENT 'Nombre descriptivo de la camiseta',
    club VARCHAR(100) NOT NULL COMMENT 'Nombre del club',
    pais VARCHAR(100) NOT NULL COMMENT 'País de procedencia (Chile, España)',
    tipo ENUM('Local','Visita','3era Camiseta','Femenino Local','Niño') NOT NULL COMMENT 'Tipo de camiseta',
    color VARCHAR(100) NOT NULL COMMENT 'Combinación principal de colores',
    precio DECIMAL(10, 2) NOT NULL COMMENT 'Precio base en CLP',
    precio_oferta DECIMAL(10, 2) NULL COMMENT 'Precio con oferta',
    detalles TEXT NULL COMMENT 'Detalles adicionales (Edición, etc)',
    activa BOOLEAN DEFAULT 1 COMMENT 'Indica si está activa',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_sku (sku),
    INDEX idx_club (club),
    INDEX idx_pais (pais),
    INDEX idx_tipo (tipo),
    INDEX idx_precio (precio),
    INDEX idx_activa (activa)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Migración 001 completada
