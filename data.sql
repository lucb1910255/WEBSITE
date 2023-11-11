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

DROP TABLE IF EXISTS `product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int NOT NULL,
  `category_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `productcategory` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;



LOCK TABLES `product` WRITE;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` VALUES 
	(1,'Iphone 11 promax','Iphone 99%',10000000,2,1),
    (2,'Iphone 11 promax','Iphone 99%',11000000,2,1),
    (3,'Iphone 11 promax','Iphone 99%',12000000,2,1),
    (4,'Iphone 12 promax','Iphone 99%',20000000,2,1),
    (5,'Iphone 12 promax','Iphone 99%',30000000,2,1),
    (6,'Iphone 12 promax','Iphone 99%',10000000,2,1),
    (7,'Iphone 13 promax','Iphone 99%',11000000,2,1),
    (8,'Iphone 13 promax','Iphone 99%',12000000,2,1),
    (9,'Iphone 13 promax','Iphone 99%',20000000,2,1),
    (10,'Iphone 14 promax','Iphone 99%',30000000,2,1),
    (11,'Iphone 14 promax','Iphone 99%',10000000,2,1),
    (12,'Iphone 14 promax','Iphone 99%',11000000,2,1),
    (13,'Iphone 15 promax','Iphone 99%',12000000,2,1),
    (14,'Iphone 15 promax','Iphone 99%',20000000,2,1),
    (15,'Samsung note7','New 100%',30000000,2,2),
    (16,'Samsung note8','New 100%',10000000,2,2),
    (17,'Samsung note9','New 100%',11000000,2,2),
    (18,'Samsung note10','New 100%',12000000,2,2),
    (19,'Samsung s20 plus','New 100%',20000000,2,2),
    (20,'Samsung s23 plus','New 100%',30000000,2,2),
    (21,'Samsung ultra s19plus','New 100%',10000000,2,2),
    (22,'Samsung s22','New 100%',11000000,2,2),
    (23,'Samsung galaxy s20','New 100%',12000000,2,2),
    (24,'Samsung s22','New 100%',20000000,2,2),
    (25,'Samsung s20 pro','New 100%',30000000,2,2),
    (26,'Xiaomi 13 black','New 100%',10000000,2,3),
    (27,'Xiaomi 13 green','New 100%',11000000,2,3),
    (28,'Xiaomi 13 blue','New 100%',12000000,2,3),
    (29,'Xiaomi 13 red','New 100%',20000000,2,3),
    (30,'Xiaomi 13 white','New 100%',30000000,2,3);    
/*!40000 ALTER TABLE `product` ENABLE KEYS */;
UNLOCK TABLES;



DROP TABLE IF EXISTS `productcategory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `productcategory` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;



LOCK TABLES `productcategory` WRITE;
/*!40000 ALTER TABLE `productcategory` DISABLE KEYS */;
INSERT INTO `productcategory` 
VALUES (1,'Iphone','Moi nhat'),
		(2,'Samsung','Moi nhat'),
        (3,'Xiaomi','Moi nhat');
/*!40000 ALTER TABLE `productcategory` ENABLE KEYS */;
UNLOCK TABLES;



DROP TABLE IF EXISTS `productimage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `productimage` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `image_url` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `productimage_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=139 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;


LOCK TABLES `productimage` WRITE;
/*!40000 ALTER TABLE `productimage` DISABLE KEYS */;
INSERT INTO `productimage`
VALUES (1,1,'Images/ip1.webp'),
	(2,2,'Images/ip2.webp'),
    (3,3,'Images/ip3.jpg'),
    (4,4,'Images/ip4.webp'),
    (5,5,'Images/ip11.jpeg'),
    (6,6,'Images/ip12.jpeg'),
	(7,7,'Images/ip13.webp'),
    (8,8,'Images/ip14 (2).webp'),
    (9,9,'Images/ip14 (3).webp'),
    (10,10,'Images/ip14 den.webp'),
    (11,11,'Images/ip14 silver.webp'),
	(12,12,'Images/ip14.webp'),
    (13,13,'Images/ip15.webp'),
    (14,14,'Images/ip15.webp'),
    (15,15,'Images/samsung1.jpeg'),
	(16,16,'Images/samsung2.jpeg'),
    (17,17,'Images/samsung3.jpeg'),
    (18,18,'Images/samsung3.jpeg'),
    (19,19,'Images/samsung2.jpeg'),
    (20,20,'Images/samsung1.jpeg'),
	(21,21,'Images/sss23ultragreen.webp'),
    (22,22,'Images/sss23.webp'),
    (23,23,'Images/sss23ultra.webp'),
    (24,24,'Images/sss23ultragreen.webp'),
    (25,25,'Images/sss23ultralavender.webp'),
	(26,26,'Images/xiaomi_13_black.webp'),
    (27,27,'Images/xiaomi_13_blue.webp'),
    (28,28,'Images/xiaomi_13_green.webp'),
    (29,29,'Images/xiaomi_13_grey.webp'),
    (30,30,'Images/xiaomi_13_white.webp');
/*!40000 ALTER TABLE `productimage` ENABLE KEYS */;
UNLOCK TABLES;



DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(80) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `invoice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoice` (
  `id` int NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(50) NOT NULL,
  `order_date` date NOT NULL,
  `delivery_date` date DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` varchar(50) DEFAULT NULL,
  `user_id` int NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `transaction` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `invoice_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=131 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;



LOCK TABLES `invoice` WRITE;
/*!40000 ALTER TABLE `invoice` DISABLE KEYS */;
/*!40000 ALTER TABLE `invoice` ENABLE KEYS */;
UNLOCK TABLES;



DROP TABLE IF EXISTS `invoicedetail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoicedetail` (
  `id` int NOT NULL AUTO_INCREMENT,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `product_id` int NOT NULL,
  `invoice_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_id` (`invoice_id`),
  KEY `fk_product_id` (`product_id`),
  CONSTRAINT `invoicedetail_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  CONSTRAINT `invoicedetail_ibfk_2` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoicedetail`
--

LOCK TABLES `invoicedetail` WRITE;
/*!40000 ALTER TABLE `invoicedetail` DISABLE KEYS */;
/*!40000 ALTER TABLE `invoicedetail` ENABLE KEYS */;
UNLOCK TABLES;

