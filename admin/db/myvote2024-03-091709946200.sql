-- mysqldump-php https://github.com/ifsnop/mysqldump-php
--
-- Host: localhost	Database: myvote
-- ------------------------------------------------------
-- Server version 	8.2.0
-- Date: Sat, 09 Mar 2024 01:03:20 +0000

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40101 SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `acad_tbl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acad_tbl` (
  `acad_id` int NOT NULL AUTO_INCREMENT,
  `description` varchar(50) NOT NULL,
  `status` int NOT NULL,
  PRIMARY KEY (`acad_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acad_tbl`
--

LOCK TABLES `acad_tbl` WRITE;
/*!40000 ALTER TABLE `acad_tbl` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `acad_tbl` VALUES (1,'2020-2021',1),(3,'2021-2022',0),(5,'2024',0);
/*!40000 ALTER TABLE `acad_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `acad_tbl` with 3 row(s)
--

--
-- Table structure for table `admin`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `admin` VALUES (1,'admin','admins');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `admin` with 1 row(s)
--

--
-- Table structure for table `candidate`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `candidate` (
  `c_id` int NOT NULL AUTO_INCREMENT,
  `acad_id` int NOT NULL,
  `p_id` int NOT NULL,
  `stud_id` varchar(100) NOT NULL,
  `pos_id` int NOT NULL,
  `img` varchar(100) NOT NULL,
  `platform` text,
  PRIMARY KEY (`c_id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `candidate`
--

LOCK TABLES `candidate` WRITE;
/*!40000 ALTER TABLE `candidate` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `candidate` VALUES (2,1,13,'2018-2019',6,'candidatephoto/f3ccdd27d2000e3f9255a7e3e2c48800.jpg',NULL),(3,1,13,'2019-2323',7,'candidatephoto/156005c5baf40ff51a327f1c34f2975b.jpg',NULL),(4,1,13,'2019-2211',8,'candidatephoto/db3a17f7bcac837ecc1fe2bc630a5473.jpg',NULL),(5,1,14,'2019-23111',5,'candidatephoto/032b2cc936860b03048302d991c3498f.jpg',NULL),(6,1,14,'20123-123',6,'candidatephoto/d0096ec6c83575373e3a21d129ff8fef.jpg',NULL),(8,1,0,'12321-2321',5,'candidatephoto/8686a8a56e9f8f2239ff8085e49680db.jpg','<p>dfdsfsdgffdgdfgdf</p>\r\n'),(9,1,0,'2019-232312',7,'libraries/img/logo.png','<p>dasdasdasfd</p>\r\n'),(10,1,13,'2019-1231',11,'libraries/img/logo.png',NULL),(11,1,13,'2019-2211',5,'libraries/img/logo.png',NULL),(12,1,14,'2022-0601',8,'libraries/img/logo.png',NULL),(13,1,14,'2022-0602',11,'libraries/img/logo.png',NULL),(14,1,15,'2022-0603',5,'libraries/img/logo.png',NULL),(15,1,15,'2022-0604',6,'libraries/img/logo.png',NULL),(16,1,15,'2022-0605',7,'libraries/img/logo.png',NULL),(17,1,15,'2022-0606',8,'libraries/img/logo.png',NULL),(18,1,15,'2022-0607',11,'libraries/img/logo.png',NULL),(19,1,0,'2022-0608',8,'libraries/img/logo.png','<p>To provide gaming environment for all gamer student</p>\r\n'),(20,1,0,'2022-0609',11,'libraries/img/logo.png','<p>Please vote me</p>\r\n'),(21,1,0,'2022-0610',6,'libraries/img/logo.png','<p>Char char lang</p>\r\n');
/*!40000 ALTER TABLE `candidate` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `candidate` with 19 row(s)
--

--
-- Table structure for table `election_title`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `election_title` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `acad_id` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `election_title`
--

LOCK TABLES `election_title` WRITE;
/*!40000 ALTER TABLE `election_title` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `election_title` VALUES (1,'PPNHS SEGWAY 202',1),(3,'ppnhsss',3);
/*!40000 ALTER TABLE `election_title` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `election_title` with 2 row(s)
--

--
-- Table structure for table `partylist`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `partylist` (
  `p_id` int NOT NULL AUTO_INCREMENT,
  `acad_id` int NOT NULL,
  `party_name` varchar(100) NOT NULL,
  `platform` text NOT NULL,
  `img` varchar(100) NOT NULL,
  PRIMARY KEY (`p_id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `partylist`
--

LOCK TABLES `partylist` WRITE;
/*!40000 ALTER TABLE `partylist` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `partylist` VALUES (14,1,'uniteam','<p>gfdgfd</p>\r\n','partylistphoto/uniteam1654235489.png'),(15,1,'liberal','<p>We love you</p>\r\n','libraries/img/logo.png'),(13,1,'kakampinks','<p>we are the north</p>\r\n','partylistphoto/kakampinks1654235470.png');
/*!40000 ALTER TABLE `partylist` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `partylist` with 3 row(s)
--

--
-- Table structure for table `position`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `position` (
  `pos_id` int NOT NULL AUTO_INCREMENT,
  `description` varchar(100) NOT NULL,
  `max_vote` int NOT NULL,
  `acad_id` int NOT NULL,
  `priority` int NOT NULL,
  PRIMARY KEY (`pos_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `position`
--

LOCK TABLES `position` WRITE;
/*!40000 ALTER TABLE `position` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `position` VALUES (6,'vice president',1,1,2),(5,'President',1,1,1),(7,'secretary',2,1,3),(8,'PIO',2,1,4),(9,'president',1,3,5),(11,'Treasurer',2,1,6);
/*!40000 ALTER TABLE `position` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `position` with 6 row(s)
--

--
-- Table structure for table `vote`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vote` (
  `id` int NOT NULL AUTO_INCREMENT,
  `voter_id` int NOT NULL,
  `candidate_id` int NOT NULL,
  `acad_id` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vote`
--

LOCK TABLES `vote` WRITE;
/*!40000 ALTER TABLE `vote` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `vote` VALUES (1,19,6,1),(2,19,11,1),(3,19,3,1),(4,19,9,1),(5,19,4,1),(6,19,12,1),(7,19,10,1),(8,19,13,1),(9,49,6,1),(10,49,8,1),(11,49,3,1),(12,49,9,1),(13,49,4,1),(14,49,12,1),(15,49,10,1),(16,49,13,1),(17,20,6,1),(18,20,5,1),(19,20,3,1),(20,20,9,1),(21,20,4,1),(22,20,12,1),(23,20,13,1),(24,20,18,1);
/*!40000 ALTER TABLE `vote` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `vote` with 24 row(s)
--

--
-- Table structure for table `voters`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `voters` (
  `v_id` int NOT NULL AUTO_INCREMENT,
  `stud_id` varchar(100) NOT NULL,
  `acad_id` int NOT NULL,
  `fname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `mname` varchar(100) NOT NULL,
  `grade_level` int NOT NULL,
  `strand` varchar(100) NOT NULL,
  `section` varchar(100) NOT NULL,
  `date_issued` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`v_id`)
) ENGINE=MyISAM AUTO_INCREMENT=59 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `voters`
--

LOCK TABLES `voters` WRITE;
/*!40000 ALTER TABLE `voters` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `voters` VALUES (21,'2019-22',1,'bart','pacaldo','batiancila',7,'','M','2022-06-02 13:22:37'),(19,'2018-2019',1,'john rey','nalla','decosta',12,'STEM','O','2022-06-02 13:22:37'),(20,'2019-2323',1,'anjelly','espino','fusingan',7,'','L','2022-06-02 13:22:37'),(22,'2019-2211',1,'ile nathaniel','norcos','flores',7,'','R','2022-06-02 13:22:37'),(23,'2019-23111',1,'cyril','madrigal','tulod',8,'','O','2022-06-02 13:22:37'),(24,'20123-123',1,'eddie','alcazar','malboro',11,'HUMMS','G','2022-06-02 13:22:37'),(25,'2019-23231',1,'Peyton','rely','asf',7,'','O','2022-06-02 13:22:37'),(26,'2011-1212',1,'REYNALDO','SALOS','TAMAYO',12,'ABM','A','2022-06-02 13:22:37'),(27,'2019-1231',1,'JOBERT','QUISTO','DOMINGO',12,'ABM','B','2022-06-02 13:22:37'),(28,'2012-1232',1,'CRISTIAN','DQQ','ARANAS',8,'','C','2022-06-02 13:22:37'),(29,'20123-2123',1,'DARA','pacaldo','QUIS',9,'','D','2022-06-02 13:22:37'),(30,'20123-1231',1,'jayson','nalla','decosta',10,'','E','2022-06-02 13:22:37'),(31,'12321-2321',1,'rosita','nalla','decosta',8,'','F','2022-06-02 13:22:37'),(32,'2019-232322',1,'DARA','PP','DSAD',7,'','L','2022-06-02 13:22:37'),(33,'2018-2020',1,'ALLAHU','AKBAR','SALOS',8,'','O','2022-06-02 13:22:37'),(34,'2312-213',1,'MYRINE','DASD','RIZABA',10,'','A','2022-06-02 13:22:37'),(35,'2312-3212',1,'MIYA','ASHH','RABIYA',11,'HUMMS','A','2022-06-02 13:22:37'),(36,'2019-232312',1,'BABI','RESHA','MAYA',12,'HUMMS','A','2022-06-02 13:22:37'),(37,'201221-2312',1,'decosta','lopez','amy',9,'','O','2022-06-02 13:22:37'),(38,'2019-23231232',1,'job','yers','dom',12,'TVL-ICT','A','2022-06-02 13:22:37'),(39,'2012-1232',1,'ARIES','JOHN','LUMAYAG',8,'','B','2022-06-02 13:22:37'),(40,'2019-2323123',1,'ARIES','JOHN','Ga',7,'','M','2022-06-02 13:22:37'),(41,'2012-12221',1,'indep','dd','mee',7,'','L','2022-06-02 13:22:37'),(42,'2018-20191111',1,'charlie','puth','ind',11,'STEM','B','2022-06-02 13:22:37'),(43,'2019-321321',1,'anggely','puth','charlie',12,'HUMMS','L','2022-06-02 13:22:37'),(44,'31321-21321',1,'shawn','mendes','poo',11,'TVL-COOKERY','O','2022-06-02 13:22:37'),(45,'2031231-1231',1,'asens','paulino','sd',9,'','N','2022-06-02 13:22:37'),(46,'2321-321321',3,'decosta','jan','asd',8,'','B','2022-06-02 13:22:37'),(47,'2012-2312',1,'arson','california','dsada',8,'','A','2022-06-02 13:29:22'),(49,'2022-0601',1,'jovet','DOMINGO','QUISTO',7,'','A','2022-06-03 23:37:29'),(50,'2022-0602',1,'hannae czarielle','espaldon','dolorosa',10,'','A','2022-06-03 23:38:09'),(51,'2022-0603',1,'christian jade','espaldon','dolorosa',9,'','A','2022-06-03 23:38:52'),(52,'2022-0604',1,'kenth laurence','espaldon','dolorosa',7,'','A','2022-06-03 23:40:33'),(53,'2022-0605',1,'joshua','domingo','datu',8,'','F','2022-06-03 23:42:19'),(54,'2022-0606',1,'geo','DOMINGO','datu',7,'','A','2022-06-03 23:43:03'),(55,'2022-0607',1,'kevin','lanticse','bayot',9,'','A','2022-06-03 23:53:00'),(56,'2022-0608',1,'aldin','mamantal','maymay',9,'','L','2022-06-03 23:55:38'),(57,'2022-0609',1,'raymarch','ruiz','tata',8,'','D','2022-06-03 23:58:03'),(58,'2022-0610',1,'jay','jihoz','lloren',11,'GAS','B','2022-06-03 23:59:56');
/*!40000 ALTER TABLE `voters` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `voters` with 39 row(s)
--

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;
/*!40101 SET AUTOCOMMIT=@OLD_AUTOCOMMIT */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on: Sat, 09 Mar 2024 01:03:20 +0000
