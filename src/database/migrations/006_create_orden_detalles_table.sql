-- Migración 006: Crear tabla orden_detalles
-- Descripción: Detalle de los productos incluidos en cada orden

CREATE TABLE IF NOT EXISTS orden_detalles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    orden_id INT NOT NULL COMMENT 'Referencia a la orden',
    camiseta_id INT NOT NULL COMMENT 'Referencia a la camiseta',
    talla_id INT NOT NULL COMMENT 'Referencia a la talla específica',
    cantidad INT NOT NULL DEFAULT 1 COMMENT 'Cantidad comprada',
    precio_unitario DECIMAL(10, 2) NOT NULL COMMENT 'Precio unitario al momento de la compra',
    subtotal DECIMAL(10, 2) NOT NULL COMMENT 'cantidad * precio_unitario',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (orden_id) REFERENCES ordenes(id) ON DELETE CASCADE,
    FOREIGN KEY (camiseta_id) REFERENCES camisetas(id),
    FOREIGN KEY (talla_id) REFERENCES tallas(id),

    INDEX idx_orden (orden_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Migración 006 completada