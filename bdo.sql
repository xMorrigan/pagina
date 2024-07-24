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
SELECT * FROM personas
CREATE TABLE IF NOT EXISTS productos(
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    descripcion VARCHAR(1024) NOT NULL,
    categoria VARCHAR(1024) NOT NULL,
    img VARCHAR(1024) NOT NULL,
    precio DECIMAL(9,2)
);
CREATE TABLE IF NOT EXISTS carrito_usuarios(
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_sesion VARCHAR(255) NOT NULL,
    id_producto BIGINT UNSIGNED NOT NULL,
    talla INT NULL,
    FOREIGN KEY (id_producto) REFERENCES productos(id)
    ON UPDATE CASCADE ON DELETE CASCADE
);

-- consulta para insertar productos
INSERT INTO `productos`(`nombre`, `descripcion`, `precio`, `categoria`, `img`) VALUES 
('Botin 501','Productos 100% Mexicanos','500','botines', 'img/Botin 501.jpg'),
('Botin 545','Productos 100% Mexicanos','500','botines', 'img/Botin_545.jpg'),
('Botin 640','Productos 100% Mexicanos','500','botines', 'img/Botin_640.jpg'),
('Botin 982','Productos 100% Mexicanos','500','botines', 'img/Botin 982.jpg'),
('Sombrero Paja','Productos 100% Mexicanos','500','sombreros', 'img/stetson paja.jpg'),
('Sombrero Fieltro','Productos 100% Mexicanos','500','sombreros', 'img/stetson fieltro.png'),
('Traje Charro','Productos 100% Mexicanos','500','trajes', 'img/charro.png'),
('Traje Adelita','Productos 100% Mexicanos','500','trajes', 'img/Ade_Rojo.jfif'),
('Hannover Bordada Blanca','Productos 100% Mexicanos','950','camisas', 'img/camisa1.png'),
('Hannover Bordada Beige','Productos 100% Mexicanos','950','camisas', 'img/Camisa2.jpg');