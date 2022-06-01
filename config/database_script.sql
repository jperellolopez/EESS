-- CREACION DE LA BBDD

CREATE DATABASE IF NOT EXISTS eess;
USE eess;

-- CREACIÓN TABLAS

 CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `firstname` varchar(32) NOT NULL,
  `lastname` varchar(64) NOT NULL,
  `email` varchar(64) NOT NULL,
  `contact_number` varchar(32) NOT NULL,
  `address` text NOT NULL,
  `password` varchar(512) NOT NULL,
  `postal_code` varchar(5) NOT NULL,
  `access_level` varchar(16) NOT NULL,
  `access_code` text NOT NULL,
  `status` int NOT NULL COMMENT '0=pending,1=confirmed',
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8

CREATE TABLE `regions` (
`region_id` int NOT NULL,
`region` varchar(50),
PRIMARY KEY (`region_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8

CREATE TABLE `gas_stations` (
  `gas_station_id` int NOT NULL,
  `address` varchar(250) NOT NULL,
  `postal_code` varchar(5) NOT NULL,
  `latitude` varchar(12) NOT NULL,
  `longitude` varchar(12) NOT NULL,
  `brand` varchar(64) NOT NULL,
  `region_id` int NOT NULL,
  `province` varchar(30) NOT NULL,
  `municipality` varchar(60) NOT NULL,
  `opening_hours` varchar(50) DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`gas_station_id`),
  KEY `regionId_idx` (`region_id`),
  CONSTRAINT `regionId` FOREIGN KEY (`region_id`) REFERENCES `regions` (`region_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8

CREATE TABLE `invoices` (
  `invoice_id` int NOT NULL AUTO_INCREMENT,
  `gas_station_id` int NOT NULL,
  `user_id` int NOT NULL,
  `fuel_type` varchar(100) NOT NULL,
  `fuel_price` decimal(10,3) NOT NULL,
  `money_spent` decimal(10,2) NOT NULL,
  `refuel_date` date NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`invoice_id`),
  KEY `userId_idx` (`user_id`),
  KEY `gasStationId_idx` (`gas_station_id`),
  CONSTRAINT `gasStationId` FOREIGN KEY (`gas_station_id`) REFERENCES `gas_stations` (`gas_station_id`),
  CONSTRAINT `userId` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8

-- INSERTS

INSERT INTO `regions` (`region_id`, `region`) VALUES
(1, 'Andalucía'),
(2, 'Aragón'),
(3, 'Asturias'),
(4, 'Baleares'),
(5, 'Canarias'),
(6, 'Cantabria'),
(7, 'Castilla la Mancha'),
(8, 'Castilla y León'),
(9, 'Cataluña'),
(10, 'Comunidad Valenciana'),
(11, 'Extremadura'),
(12, 'Galicia'),
(13, 'Madrid'),
(14, 'Murcia'),
(15, 'Navarra'),
(16, 'País Vasco'),
(17, 'Rioja (La)'),
(18, 'Ceuta'),
(19, 'Melilla');

-- usuario de prueba
INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `contact_number`, `address`, `postal_code`, `password`, `access_level`, `access_code`, `status`, `created`, `modified`) VALUES (1, 'Nombre', 'Apellido', 'ejemplo@example.com', '9331868359', 'Calle de Ejemplo, 23', '07001', '$2y$10$tLq9lTKDUt7EyTFhxL0QHuen/BgO9YQzFYTUyH50kJXLJ.ISO3HAO', 'Usuario', 'ILXFBdMAbHVrJswNDnm231cziO8FZomn', 1, '2019-10-29 17:31:09', '2019-06-13 18:18:25');

-- credenciales
-- Username: ejemplo@example.com
-- Password: darwin12qw!@QW