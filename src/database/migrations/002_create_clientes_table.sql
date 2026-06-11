-- Migración 002: Crear tabla clientes
-- Descripción: Tabla de clientes con información de RUT, categoría y descuentos

CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rut VARCHAR(12) NOT NULL UNIQUE COMMENT 'RUT chileno (XX.XXX.XXX-X)',
    nombre_comercial VARCHAR(150) NOT NULL COMMENT 'Nombre comercial del cliente',
    direccion VARCHAR(255) COMMENT 'Dirección (ciudad y región)',
    categoria ENUM('Regular','Preferencial') NOT NULL DEFAULT 'Regular' COMMENT 'Categoría de cliente',
    contacto_nombre VARCHAR(150) COMMENT 'Nombre del encargado de compras',
    contacto_email VARCHAR(100) COMMENT 'Email del encargado de compras',
    descuento_porcentaje DECIMAL(5, 2) DEFAULT 0 COMMENT 'Porcentaje de descuento en todos productos',
    activo BOOLEAN DEFAULT 1 COMMENT 'Indica si el cliente está activo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_rut (rut),
    INDEX idx_categoria (categoria),
    INDEX idx_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Migración 002 completada
