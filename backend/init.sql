CREATE TABLE productos
(
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(255)   NOT NULL,
    descripcion TEXT NULL,
    precio      DECIMAL(10, 2) NOT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at  TIMESTAMP NULL DEFAULT NULL
);

CREATE INDEX idx_productos_nombre_precio ON productos (nombre, precio);
