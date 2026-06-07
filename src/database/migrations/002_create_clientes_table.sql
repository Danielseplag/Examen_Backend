-- Migración 002: Crear tabla clientes
-- Descripción: Tabla de clientes con información de RUT, categoría y descuentos

CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rut VARCHAR(12) NOT NULL UNIQUE COMMENT 'RUT chileno del cliente (XX.XXX.XXX-X)',
    nombre VARCHAR(150) NOT NULL COMMENT 'Nombre completo',
    email VARCHAR(100) NULL COMMENT 'Email del cliente',
    telefono VARCHAR(15) NULL COMMENT 'Número de teléfono',
    categoria ENUM('mayorista', 'minorista', 'vip') DEFAULT 'minorista' COMMENT 'Categoría de cliente',
    descuento_porcentaje DECIMAL(5, 2) DEFAULT 0 COMMENT 'Descuento aplicable al cliente (%)',
    credito_disponible DECIMAL(12, 2) DEFAULT 0 COMMENT 'Crédito disponible en CLP',
    activo BOOLEAN DEFAULT 1 COMMENT 'Indica si el cliente está activo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_rut (rut),
    INDEX idx_categoria (categoria),
    INDEX idx_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Migración 002 completada
