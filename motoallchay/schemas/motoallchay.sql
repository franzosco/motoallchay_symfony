DROP DATABASE IF EXISTS `motoallchaydb`;

CREATE DATABASE `motoallchaydb`;

USE `motoallchaydb`;

DROP TABLE IF EXISTS `usuario`;

CREATE TABLE `usuario` (
  `id` INT(10) unsigned AUTO_INCREMENT NOT NULL,
  `username` varchar(32) NOT NULL UNIQUE,
  `password` varchar(40) NOT NULL,
  `name` varchar(20) NOT NULL,
  `last_name` varchar(40) NOT NULL,
  `email` VARCHAR(60) NOT NULL UNIQUE,
  `tipo` varchar(15) NOT NULL,
  `active` varchar(1) NOT NULL,
  `created_at` DATETIME NOT NULL,

  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `moto`;

CREATE TABLE `moto` (
  `id` INT(10) unsigned NOT NULL AUTO_INCREMENT,
  `placa` varchar(7) NOT NULL UNIQUE,
  `cliente_dni` char(8) NOT NULL,
  `color` char(25) NOT NULL,
  `marca` varchar(35) NOT NULL,
  `descripcion` text NOT NULL,

  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `reparacion`;

CREATE TABLE `reparacion` (
  `id` INT(10) unsigned NOT NULL AUTO_INCREMENT,
  `fecha_ingreso` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `fecha_entrega` TIMESTAMP NULL,
  `moto_id` INT(10) unsigned NOT NULL,
  `usuario_id` INT(10) unsigned NOT NULL,
  `servicios` text NOT NULL,
  `descripcion` text NULL,
  `estado` varchar(10) DEFAULT 'pendiente',
  `precio` Decimal(16,2) NOT NULL,

  PRIMARY KEY (`id`),
  FOREIGN KEY (`moto_id`) REFERENCES `moto`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`usuario_id`) REFERENCES `usuario`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `usuario` WRITE;

INSERT INTO `usuario` VALUES 
(1,'admin', '0140e9ae5182d2c4ab347984ea417cb8ba273904', 'Mariluz', 'Llicahua Huanco','mariluz@gmail.com', 'administrador', 'Y', '2012-04-10 20:53:03'),
(2,'elsa', '0140e9ae5182d2c4ab347984ea417cb8ba273904', 'Elza Elizabet', 'Aedo Morales','elsa1@gmail.com', 'administrador', 'Y', '2016-04-10 20:53:03'),
(3,'nelida', '0140e9ae5182d2c4ab347984ea417cb8ba273904', 'Nelida Alicia', 'Alvares Vargas','nelida@gmail.com', 'administrador', 'Y', '2016-04-10 20:53:03'),
(4,'maria', '0140e9ae5182d2c4ab347984ea417cb8ba273904', 'Maria Flor', 'Cahuana Quispitupa','maria@gmail.com', 'cajero', 'N', '2016-04-10 20:53:03'),
(5,'evelyn', '0140e9ae5182d2c4ab347984ea417cb8ba273904', 'Evelyn', 'Ccahuana Herrera','evelyn@gmail.com', 'cajero', 'Y', '2015-04-10 20:53:03'),
(6,'leonil', '0140e9ae5182d2c4ab347984ea417cb8ba273904', 'Leonil', 'Chipana Huaman','leonil@gmail.com', 'cajero', 'Y', '2016-04-10 20:53:03'),
(7,'dean', '0140e9ae5182d2c4ab347984ea417cb8ba273904', 'Dean Dario', 'Cruz Rios','dean@gmail.com', 'cajero', 'N', '2014-04-10 20:53:03'),
(8,'esther', '0140e9ae5182d2c4ab347984ea417cb8ba273904', 'Shamili Esther', 'Damian Enrique','esther@gmail.com', 'cajero', 'Y', '2015-04-10 20:53:03'),
(9,'karina', '0140e9ae5182d2c4ab347984ea417cb8ba273904', 'Karina', 'Garrafa Huamani','karina@gmail.com', 'administrador', 'N', '2013-04-10 20:53:03'),
(10,'jhon', '0140e9ae5182d2c4ab347984ea417cb8ba273904', 'Jhon Christopher', 'Huaman Arroyo','jhon@gmail.com', 'cajero', 'Y', '2015-04-10 20:53:03'),
(11,'raddy', '0140e9ae5182d2c4ab347984ea417cb8ba273904', 'Jimmy raddy', 'Huaman Ccayhuari','raddy@gmail.com', 'cajero', 'Y', '2013-04-10 20:53:03'),
(12,'sara', '0140e9ae5182d2c4ab347984ea417cb8ba273904', 'Sara Esther', 'Mendoza Choque','sara@gmail.com', 'cajero', 'Y', '2014-04-10 20:53:03'),
(13,'jean', '0140e9ae5182d2c4ab347984ea417cb8ba273904', 'Jean Carlos', 'Peña Soto','juan@gmail.com', 'cajero', 'Y', '2016-04-10 20:53:03'),
(14,'jhanet', '0140e9ae5182d2c4ab347984ea417cb8ba273904', 'Ely Jhanet', 'Pumapillo Cruz','jhanet@gmail.com', 'cajero', 'Y', '2015-04-10 20:53:03'),
(15,'mijael', '0140e9ae5182d2c4ab347984ea417cb8ba273904', 'Yulmer Mijael', 'Rojas Marcavillaca','mijael@gmail.com', 'cajero', 'N', '2014-04-10 20:53:03'),
(16,'brayan', '0140e9ae5182d2c4ab347984ea417cb8ba273904', 'Jojahan Brayan', 'Serrano Palma','brayan@gmail.com', 'cajero', 'Y', '2016-04-10 20:53:03'),
(17,'mateo', '0140e9ae5182d2c4ab347984ea417cb8ba273904', 'Jhonatan Mateo', 'Soria Panuera','mateo@gmail.com', 'cajero', 'Y', '2013-04-10 20:53:03'),
(18,'diomides', '0140e9ae5182d2c4ab347984ea417cb8ba273904', 'Diomides', 'Tantalla Cruz','diomides@gmail.com', 'gerente', 'Y', '2016-04-10 20:53:03'),
(19,'edelmira', '0140e9ae5182d2c4ab347984ea417cb8ba273904', 'Luz Edelmira', 'Terzi Tejada','edelmira@gmail.com', 'gerente', 'Y', '2016-04-10 20:53:03');

UNLOCK TABLES;

LOCK TABLES `moto` WRITE;

INSERT INTO `moto` VALUES
(1,'azc-123','32456781','rojo','Yamaha','Una moto un poco oxidada'),
(2,'azc-135','32456781','verde','Yamaha','Una con el haciento un poco bajo'),
(3,'zc-123','32456781','amarillo','Yamaha','Una con el haciento un poco bajo'),
(4,'ac-123','32456781','negro','Yamaha','Una con el haciento un poco bajo'),
(5,'az-123','32456781','negro','Yamaha','Una moto un poco oxidada'),
(6,'az-34','32456781','verde','Yamaha','Una moto un poco oxidada'),
(7,'az-55','32456781','rojo','Yamaha','Una moto un poco oxidada'),
(8,'az-348','32456781','rojo','Yamaha','Una moto un poco oxidada'),
(9,'azc-12','32456781','rojo','Yamaha','Una moto un poco oxidada'),
(10,'azc-43','32456781','rojo','Yamaha','Una con el haciento un poco bajo'),
(11,'azc-53','32456781','verde','Yamaha','Una moto un poco oxidada'),
(12,'azc-87','32456781','rojo','Yamaha','Una con el haciento un poco bajo'),
(13,'azc-567','32456781','rojo','Yamaha','Una moto un poco oxidada'),
(14,'azc-345','32456781','negro','Yamaha','Una moto con el timón arqueado'),
(15,'azc-222','32456781','rojo','Yamaha','La moto del año'),
(16,'azc-11','32456781','rojo','Yamaha','Una moto un poco oxidada'),
(17,'azc-765','32456781','verde','Yamaha','Una con el haciento un poco bajo'),
(18,'azc-543','32456781','rojo','Yamaha','Una moto un poco oxidada'),
(19,'azc-987','32456781','rojo','Yamaha','Una con el haciento un poco bajo'),
(20,'azc-780','32456781','verde','Yamaha','Una moto un poco oxidada'),
(21,'azc-435','32456781','rojo','Yamaha','Una con el haciento un poco bajo'),
(23,'azc-879','32456781','azul','Yamaha','UUna con el haciento un poco bajo'),
(24,'azc-432','32456781','rojo','Yamaha','Una moto un poco oxidada'),
(25,'azc-766','32456781','verde','Yamaha','Una con el haciento un poco bajo'),
(26,'azc-507','32456781','rojo','Yamaha','Una moto un poco oxidada'),
(27,'azc-897','32456781','verde','Yamaha','Una moto un poco oxidada');

UNLOCK TABLES;

LOCK TABLES `reparacion` WRITE;

INSERT INTO `reparacion` VALUES
(1,NULL,NULL,1,5,'Cambio de llanta','Tenia la llanta baja y el aro chueco','pendiente',49.90),
(2,NULL,NOW() + INTERVAL 1 DAY,2,5,'Cambio de llanta','Tenia la llanta baja y el aro chueco','entregado',59.90),
(3,NULL,NOW() + INTERVAL 1 DAY,3,5,'Cambio de llanta','Tenia la llanta baja y el aro chueco','entregado',69.90),
(4,NULL,NOW() + INTERVAL 1 DAY,4,5,'Cambio de llanta','Tenia la llanta baja y el aro chueco','entregado',79.90),
(5,NULL,NULL,5,5,'Cambio de llanta','Tenia la llanta baja y el aro chueco','pendiente',89.90),
(6,NULL,NULL,4,5,'Cambio de llanta','Tenia la llanta baja y el aro chueco','pendiente',99.90),
(7,NULL,NULL,2,5,'Cambio de llanta','Tenia la llanta baja y el aro chueco','pendiente',109.90),
(8,NULL,NULL,10,16,'Cambio de llanta','Tenia la llanta baja y el aro chueco','pendiente',39.90),
(9,NULL,NULL,11,16,'Cambio de llanta','Tenia la llanta baja y el aro chueco','pendiente',29.90),
(10,NULL,NOW() + INTERVAL 2 DAY,2,16,'Cambio de llanta','Tenia la llanta baja y el aro chueco','entregado',59.90),
(11,NULL,NOW() + INTERVAL 2 DAY,3,16,'Cambio de llanta','Tenia la llanta baja y el aro chueco','entregado',479.90),
(12,NULL,NOW() + INTERVAL 2 DAY,4,16,'Cambio de llanta','Tenia la llanta baja y el aro chueco','entregado',19.90),
(13,NULL,NOW() + INTERVAL 3 DAY,5,16,'Cambio de llanta','Tenia la llanta baja y el aro chueco','entregado',39.90),
(14,NULL,NOW() + INTERVAL 5 HOUR,6,16,'Cambio de llanta','Tenia la llanta baja y el aro chueco','entregado',89.90),
(15,NULL,NOW() + INTERVAL 3 HOUR,7,16,'Cambio de llanta','Tenia la llanta baja y el aro chueco','entregado',499.90),
(16,NULL,NOW() + INTERVAL 4 HOUR,8,16,'Cambio de llanta','Tenia la llanta baja y el aro chueco','entregado',349.90),
(17,NULL,NOW() + INTERVAL 2 HOUR,9,16,'Cambio de llanta','Tenia la llanta baja y el aro chueco','entregado',149.90),
(18,NULL,NULL,10,14,'Cambio de llanta','Tenia la llanta baja y el aro chueco','pendiente',249.90),
(19,NULL,NULL,12,14,'Cambio de llanta','Tenia la llanta baja y el aro chueco','pendiente',249.90),
(20,NULL,NOW() + INTERVAL 1 DAY,13,14,'Cambio de llanta','Tenia la llanta baja y el aro chueco','entregado',249.90),
(21,NULL,NOW() + INTERVAL 3 DAY,14,14,'Cambio de llanta','Tenia la llanta baja y el aro chueco','entregado',249.90),
(22,NULL,NOW() + INTERVAL 4 DAY,15,14,'Cambio de llanta','Tenia la llanta baja y el aro chueco','entregado',249.90),
(23,NULL,NOW() + INTERVAL 2 DAY,16,14,'Cambio de llanta','Tenia la llanta baja y el aro chueco','entregado',249.90),
(24,NULL,NULL,17,14,'Cambio de llanta','Tenia la llanta baja y el aro chueco','pendiente',249.90),
(25,NULL,NULL,18,14,'Cambio de llanta','Tenia la llanta baja y el aro chueco','pendiente',249.90),
(26,NULL,NULL,19,14,'Cambio de llanta','Tenia la llanta baja y el aro chueco','pendiente',249.90),
(27,NULL,NULL,20,14,'Cambio de llanta','Tenia la llanta baja y el aro chueco','pendiente',249.90),
(28,NULL,NULL,21,14,'Cambio de llanta','Tenia la llanta baja y el aro chueco','pendiente',249.90),
(29,NULL,NULL,21,14,'Cambio de llanta','Tenia la llanta baja y el aro chueco','pendiente',249.90);

UNLOCK TABLES;
