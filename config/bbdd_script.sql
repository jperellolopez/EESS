CREATE DATABASE  IF NOT EXISTS `eess` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `eess`;
-- MySQL dump 10.13  Distrib 8.0.22, for Win64 (x86_64)
--
-- Host: localhost    Database: eess
-- ------------------------------------------------------
-- Server version	8.0.22

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `gas_stations`
--

DROP TABLE IF EXISTS `gas_stations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gas_stations`
--

LOCK TABLES `gas_stations` WRITE;
/*!40000 ALTER TABLE `gas_stations` DISABLE KEYS */;
/*!40000 ALTER TABLE `gas_stations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `regions`
--

DROP TABLE IF EXISTS `regions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `regions` (
  `region_id` int NOT NULL,
  `region` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`region_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `regions`
--

LOCK TABLES `regions` WRITE;
/*!40000 ALTER TABLE `regions` DISABLE KEYS */;
INSERT INTO `regions` VALUES (1,'Andalucía'),(2,'Aragón'),(3,'Asturias'),(4,'Baleares'),(5,'Canarias'),(6,'Cantabria'),(7,'Castilla la Mancha'),(8,'Castilla y León'),(9,'Cataluña'),(10,'Comunidad Valenciana'),(11,'Extremadura'),(12,'Galicia'),(13,'Madrid'),(14,'Murcia'),(15,'Navarra'),(16,'País Vasco'),(17,'Rioja (La)'),(18,'Ceuta'),(19,'Melilla');
/*!40000 ALTER TABLE `regions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `firstname` varchar(32) NOT NULL,
  `lastname` varchar(64) NOT NULL,
  `email` varchar(64) NOT NULL,
  `contact_number` varchar(32) NOT NULL,
  `address` text NOT NULL,
  `postal_code` varchar(5) NOT NULL,
  `password` varchar(512) NOT NULL,
  `access_level` varchar(16) NOT NULL,
  `access_code` text NOT NULL,
  `status` int NOT NULL COMMENT '0=pending,1=confirmed',
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'John','Doe','userinfogasolineras@gmail.com','933186835','Calle de Ejemplo, 23A','07001','$2y$10$6yjFUKRcXLnv6501aNldJO5hHk.sukbfDAzrYNyi0ygHcK6YZt1tO','Usuario','I7BtUvkUahxgX66TyWZL6WVPx4PcKIWV',1,'2019-10-29 17:31:09','2022-06-03 16:22:30');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-06-03 18:30:42
