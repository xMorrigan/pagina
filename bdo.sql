CREATE DATABASE login;

CREATE TABLE Personas (
    id INT PRIMARY KEY IDENTITY,
    nombre VARCHAR(50) NOT NULL,
    apellidos VARCHAR(50) NOT NULL,
    aumero VARCHAR(15) NOT NULL,
    direccion VARCHAR(100) NOT NULL,
    codigo_postal VARCHAR(10) NOT NULL,
    area VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
	password VARCHAR(10) NOT NULL,
    estado_region VARCHAR(50) NOT NULL
)
-- agregar campo para el rol
ALTER TABLE Personas
ADD rol VARCHAR(30) NOT NULL;

--Consulta para insertar usuarios
INSERT INTO `personas`(`nombre`, `apellidos`, `aumero`, `direccion`, `codigo_postal`, `area`, `email`, `password`, `estado_region`) VALUES 
('User','user','5','S/D', 'S/D', 'S/D', 'USER@GMAIL.COM', '1234', 'S/D'),
('admin','admin','999999999','S/D', 'S/D', 'S/D', 'admin@gmail.com', '1234', 'S/D');

SELECT * FROM personas
CREATE TABLE IF NOT EXISTS productos(
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    descripcion VARCHAR(1024) NOT NULL,
    categoria VARCHAR(1024) NOT NULL,
    img VARCHAR(1024) NOT NULL,
    precio DECIMAL(9,2),
    stock_minimo INT NULL,
    stock_maximo INT NULL,
    existencia INT NULL
);
CREATE TABLE IF NOT EXISTS carrito_usuarios(
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_sesion VARCHAR(255) NOT NULL,
    id_producto BIGINT UNSIGNED NOT NULL,
    talla INT NULL,
    cantidad INT DEFAULT 0,
    FOREIGN KEY (id_producto) REFERENCES productos(id)
    ON UPDATE CASCADE ON DELETE CASCADE
);

-- consulta para insertar productos
INSERT INTO `productos`(`nombre`, `descripcion`, `precio`, `categoria`, `img`, `stock_minimo`, `stock_maximo`, `existencia`) VALUES 
('Botin 501','Productos 100% Mexicanos','500','botines', 'img/Botin 501.jpg', '20', '500', '500'),
('Botin 545','Productos 100% Mexicanos','500','botines', 'img/Botin_545.jpg', '20', '500', '500'),
('Botin 640','Productos 100% Mexicanos','500','botines', 'img/Botin_640.jpg', '20', '500', '500'),
('Botin 982','Productos 100% Mexicanos','500','botines', 'img/Botin 982.jpg', '20', '500', '500'),
('Sombrero Paja','Productos 100% Mexicanos','500','sombreros', 'img/stetson paja.jpg', '20', '500', '500'),
('Sombrero Fieltro','Productos 100% Mexicanos','500','sombreros', 'img/stetson fieltro.png', '20', '500', '500'),
('Traje Charro','Productos 100% Mexicanos','500','trajes', 'img/charro.png', '20', '500', '500'),
('Traje Adelita','Productos 100% Mexicanos','500','trajes', 'img/Ade_Rojo.jfif', '20', '500', '500'),
('Hannover Bordada Blanca','Productos 100% Mexicanos','950','camisas', 'img/camisa1.png', '20', '500', '500'),
('Hannover Bordada Beige','Productos 100% Mexicanos','950','camisas', 'img/Camisa2.jpg', '20', '500', '500');

CREATE TABLE IF NOT EXISTS ventas(
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_persona INT NOT NULL,
    fecha_venta DATETIME NOT NULL,
    devuelta BOOLEAN DEFAULT FALSE,
    credito BOOLEAN DEFAULT FALSE,
    fecha_pago DATETIME NULL,
    fecha_pagada DATETIME NULL,
    FOREIGN KEY (id_persona) REFERENCES Personas(id)
    ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS detalles_venta(
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_venta BIGINT UNSIGNED NOT NULL,
    id_producto BIGINT UNSIGNED NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(9,2) NOT NULL,
    FOREIGN KEY (id_venta) REFERENCES ventas(id)
    ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES productos(id)
    ON UPDATE CASCADE ON DELETE CASCADE
);
