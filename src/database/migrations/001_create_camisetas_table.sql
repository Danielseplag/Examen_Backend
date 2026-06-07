-- Migración 001: Crear tabla camisetas
-- Descripción: Tabla principal de camisetas con información de club, precio y ofertas

CREATE TABLE IF NOT EXISTS camisetas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sku VARCHAR(50) NOT NULL UNIQUE COMMENT 'SKU único de la camiseta',
    club VARCHAR(100) NOT NULL COMMENT 'Nombre del club',
    precio DECIMAL(10, 2) NOT NULL COMMENT 'Precio base en CLP',
    precio_oferta DECIMAL(10, 2) NULL COMMENT 'Precio con oferta',
    descripcion TEXT NULL COMMENT 'Descripción de la camiseta',
    activa BOOLEAN DEFAULT 1 COMMENT 'Indica si la camiseta está activa',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_club (club),
    INDEX idx_activa (activa)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Migración 001 completada
