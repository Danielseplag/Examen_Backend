-- Migración 004: Crear tabla relación camiseta_talla
-- Descripción: Tabla de relación N:M entre camisetas y tallas

CREATE TABLE IF NOT EXISTS camiseta_talla (
    id INT AUTO_INCREMENT PRIMARY KEY,
    camiseta_id INT NOT NULL COMMENT 'ID de la camiseta',
    talla_id INT NOT NULL COMMENT 'ID de la talla',
    stock INT DEFAULT 0 COMMENT 'Stock de esta combinación',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    UNIQUE KEY unique_camiseta_talla (camiseta_id, talla_id),
    FOREIGN KEY (camiseta_id) REFERENCES camisetas(id) ON DELETE CASCADE,
    FOREIGN KEY (talla_id) REFERENCES tallas(id) ON DELETE CASCADE,
    
    INDEX idx_camiseta (camiseta_id),
    INDEX idx_talla (talla_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Migración 004 completada
