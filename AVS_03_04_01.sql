-- Script de base de datos: AVS_03_04_01
-- Importar desde phpMyAdmin (pestaña Importar) o ejecutar en la pestaña SQL.

CREATE DATABASE IF NOT EXISTS AVS_03_04_01
  CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE AVS_03_04_01;

-- Tabla de categorías (tabla padre)
CREATE TABLE IF NOT EXISTS categorias (
  id_categoria INT(8)      NOT NULL AUTO_INCREMENT,
  descripcion  VARCHAR(30) NOT NULL,
  PRIMARY KEY (id_categoria)
) ENGINE=InnoDB;

-- Tabla de productos (tabla hija, con integridad referencial)
CREATE TABLE IF NOT EXISTS productos (
  id_producto  INT(11)      NOT NULL AUTO_INCREMENT,
  nombre       VARCHAR(30)  NOT NULL,
  precio       DOUBLE       NOT NULL,
  stock        INT(100)     NOT NULL,
  foto_p       VARCHAR(100) NOT NULL,
  categoria_id INT(8)       NOT NULL,
  PRIMARY KEY (id_producto),
  KEY idx_categoria (categoria_id),
  CONSTRAINT fk_productos_categoria FOREIGN KEY (categoria_id)
    REFERENCES categorias (id_categoria)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Categorías de ejemplo
INSERT INTO categorias (descripcion) VALUES
  ('Electrónica'), ('Papelería'), ('Hogar'), ('Ropa'), ('Alimentos');
