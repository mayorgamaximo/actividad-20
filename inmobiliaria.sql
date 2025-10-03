-- Crear base de datos
CREATE DATABASE IF NOT EXISTS inmobiliaria;
USE inmobiliaria;

-- Crear tabla viviendas
CREATE TABLE viviendas (
    id INT(3) AUTO_INCREMENT PRIMARY KEY,
    tipo VARCHAR(15),
    zona VARCHAR(15),
    direccion VARCHAR(30),
    dormitorios INT(1),
    precio FLOAT(10,2),
    tamano INT(5),
    extras SET('Piscina','Jardín','Garage')
);

-- Insertar datos de ejemplo
INSERT INTO viviendas (tipo, zona, direccion, dormitorios, precio, tamano, extras) VALUES
('Casa', 'Norte', 'Av. Libertador 1234', 3, 250000.00, 120, 'Piscina,Jardín'),
('Departamento', 'Centro', 'Calle Principal 456', 2, 150000.00, 75, 'Garage'),
('Casa', 'Sur', 'Calle Flores 789', 4, 320000.00, 180, 'Piscina,Jardín,Garage'),
('Departamento', 'Norte', 'Av. Belgrano 321', 1, 95000.00, 45, ''),
('Casa', 'Oeste', 'Calle Sol 567', 3, 280000.00, 150, 'Jardín,Garage'),
('Departamento', 'Centro', 'Av. Corrientes 890', 2, 175000.00, 80, 'Garage'),
('Casa', 'Norte', 'Calle Luna 234', 5, 450000.00, 220, 'Piscina,Jardín,Garage'),
('Departamento', 'Sur', 'Av. Rivadavia 678', 2, 140000.00, 70, ''),
('Casa', 'Este', 'Calle Estrella 901', 4, 380000.00, 200, 'Piscina,Garage'),
('Departamento', 'Centro', 'Calle Central 345', 3, 210000.00, 95, 'Garage'),
('Casa', 'Norte', 'Av. Maipú 456', 3, 290000.00, 140, 'Jardín'),
('Departamento', 'Oeste', 'Calle Oeste 789', 1, 88000.00, 42, ''),
('Casa', 'Sur', 'Av. San Martín 123', 4, 340000.00, 190, 'Piscina,Jardín'),
('Departamento', 'Norte', 'Calle Norte 567', 2, 165000.00, 78, 'Garage'),
('Casa', 'Centro', 'Av. Italia 890', 5, 520000.00, 250, 'Piscina,Jardín,Garage');