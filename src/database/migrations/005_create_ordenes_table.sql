-- Migración 005: Crear tabla de auditoría (opcional pero recomendada)
-- Descripción: Registra cambios en órdenes/transacciones

CREATE TABLE IF NOT EXISTS ordenes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL COMMENT 'Cliente que hace la orden',
    total DECIMAL(12, 2) NOT NULL COMMENT 'Total de la orden en CLP',
    descuento_aplicado DECIMAL(12, 2) DEFAULT 0 COMMENT 'Descuento total aplicado',
    estado ENUM('pendiente', 'confirmada', 'entregada', 'cancelada') DEFAULT 'pendiente' COMMENT 'Estado de la orden',
    observaciones TEXT NULL COMMENT 'Observaciones de la orden',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE,
    
    INDEX idx_cliente (cliente_id),
    INDEX idx_estado (estado),
    INDEX idx_fecha (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Migración 005 completada
