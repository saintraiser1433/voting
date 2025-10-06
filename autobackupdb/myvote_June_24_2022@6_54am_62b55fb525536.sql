-- MySQL dump 10.13  Distrib 5.7.36, for Win64 (x86_64)
--
-- Host: localhost    Database: myvote
-- ------------------------------------------------------
-- Server version	5.7.36

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `acad_tbl`
--

DROP TABLE IF EXISTS `acad_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acad_tbl` (
  `acad_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(50) NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`acad_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acad_tbl`
--

LOCK TABLES `acad_tbl` WRITE;
/*!40000 ALTER TABLE `acad_tbl` DISABLE KEYS */;
INSERT INTO `acad_tbl` VALUES (1,'2022',1),(7,'2023',0);
/*!40000 ALTER TABLE `acad_tbl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (2,'admin','827ccb0eea8a706c4c34a16891f84e7b');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `archives`
--

DROP TABLE IF EXISTS `archives`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `archives` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `v_id` int(11) NOT NULL,
  `stud_id` varchar(100) NOT NULL,
  `acad_id` int(11) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `mname` varchar(100) NOT NULL,
  `grade_level` int(5) NOT NULL,
  `strand` varchar(100) NOT NULL,
  `section` varchar(100) NOT NULL,
  `auth_code` text NOT NULL,
  `date_issued` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `archives`
--

LOCK TABLES `archives` WRITE;
/*!40000 ALTER TABLE `archives` DISABLE KEYS */;
/*!40000 ALTER TABLE `archives` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `archivesvote`
--

DROP TABLE IF EXISTS `archivesvote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `archivesvote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `voter_id` int(11) NOT NULL,
  `candidate_id` int(11) NOT NULL,
  `acad_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `archivesvote`
--

LOCK TABLES `archivesvote` WRITE;
/*!40000 ALTER TABLE `archivesvote` DISABLE KEYS */;
/*!40000 ALTER TABLE `archivesvote` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `candidate`
--

DROP TABLE IF EXISTS `candidate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `candidate` (
  `c_id` int(11) NOT NULL AUTO_INCREMENT,
  `acad_id` int(11) NOT NULL,
  `p_id` int(11) NOT NULL,
  `stud_id` varchar(100) NOT NULL,
  `pos_id` int(11) NOT NULL,
  `img` varchar(100) NOT NULL,
  `platform` text,
  PRIMARY KEY (`c_id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `candidate`
--

LOCK TABLES `candidate` WRITE;
/*!40000 ALTER TABLE `candidate` DISABLE KEYS */;
INSERT INTO `candidate` VALUES (26,1,16,'2019-232',12,'candidatephoto/f3ccdd27d2000e3f9255a7e3e2c48800.jpg',NULL),(25,1,0,'2019-2336',15,'libraries/img/logo.png',''),(12,1,17,'2019-2331',12,'libraries/img/logo.png',NULL),(13,1,17,'2019-2333',17,'libraries/img/logo.png',NULL),(14,1,17,'2019-2324',16,'libraries/img/logo.png',NULL),(15,1,16,'2019-2325',13,'libraries/img/logo.png',NULL),(16,1,16,'2019-2326',14,'libraries/img/logo.png',NULL),(17,1,16,'2019-2327',16,'libraries/img/logo.png',NULL),(18,1,17,'2019-2323',14,'libraries/img/logo.png',NULL),(19,1,17,'2019-2328',17,'libraries/img/logo.png',NULL),(20,1,16,'2019-2330',17,'libraries/img/logo.png',NULL),(21,1,16,'2018-01282',17,'libraries/img/logo.png',NULL),(22,1,0,'2019-2329',12,'candidatephoto/799bad5a3b514f096e69bbc4a7896cd9.jpg',''),(23,1,0,'2019-2332',13,'libraries/img/logo.png',''),(24,1,0,'2019-2335',14,'libraries/img/logo.png',''),(27,1,16,'2019-2337',15,'libraries/img/logo.png',NULL),(28,1,17,'2019-2338',15,'libraries/img/logo.png',NULL),(29,1,0,'2019-2339',17,'libraries/img/logo.png',''),(30,1,0,'2019-2340',17,'libraries/img/logo.png','');
/*!40000 ALTER TABLE `candidate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `election_title`
--

DROP TABLE IF EXISTS `election_title`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `election_title` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `acad_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `election_title`
--

LOCK TABLES `election_title` WRITE;
/*!40000 ALTER TABLE `election_title` DISABLE KEYS */;
INSERT INTO `election_title` VALUES (5,'PPNHS SEGWAY',1);
/*!40000 ALTER TABLE `election_title` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `partylist`
--

DROP TABLE IF EXISTS `partylist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `partylist` (
  `p_id` int(11) NOT NULL AUTO_INCREMENT,
  `acad_id` int(11) NOT NULL,
  `party_name` varchar(100) NOT NULL,
  `platform` text NOT NULL,
  `img` varchar(100) NOT NULL,
  PRIMARY KEY (`p_id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `partylist`
--

LOCK TABLES `partylist` WRITE;
/*!40000 ALTER TABLE `partylist` DISABLE KEYS */;
INSERT INTO `partylist` VALUES (16,1,'uniteam','<p>dasdasds</p>\r\n','partylistphoto/1260555720.png'),(17,1,'KAKAMPINK','<p>MY SAMPLE PLATFORM</p>\r\n','partylistphoto/KAKAMPINK1655993836.png');
/*!40000 ALTER TABLE `partylist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `position`
--

DROP TABLE IF EXISTS `position`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `position` (
  `pos_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(100) NOT NULL,
  `max_vote` int(11) NOT NULL,
  `acad_id` int(11) NOT NULL,
  `priority` int(5) NOT NULL,
  PRIMARY KEY (`pos_id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `position`
--

LOCK TABLES `position` WRITE;
/*!40000 ALTER TABLE `position` DISABLE KEYS */;
INSERT INTO `position` VALUES (12,'president',1,1,1),(13,'VICE PRESIDENT',1,1,2),(14,'SECRETARY',1,1,3),(15,'Treasurer',1,1,4),(16,'AUDITOR',1,1,5),(17,'P.I.O',2,1,6);
/*!40000 ALTER TABLE `position` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vote`
--

DROP TABLE IF EXISTS `vote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `voter_id` int(11) NOT NULL,
  `candidate_id` int(11) NOT NULL,
  `acad_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=67 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vote`
--

LOCK TABLES `vote` WRITE;
/*!40000 ALTER TABLE `vote` DISABLE KEYS */;
INSERT INTO `vote` VALUES (4,12,10,1),(5,26,12,1),(6,26,15,1),(7,26,18,1),(8,26,27,1),(9,26,17,1),(10,26,21,1),(11,26,19,1),(12,37,26,1),(13,37,23,1),(14,37,18,1),(15,37,25,1),(16,37,14,1),(17,37,21,1),(18,37,13,1),(19,42,22,1),(20,42,16,1),(21,42,28,1),(22,42,17,1),(23,42,21,1),(24,42,29,1),(25,41,26,1),(26,41,23,1),(27,41,18,1),(28,41,25,1),(29,41,17,1),(30,41,21,1),(31,41,19,1),(32,40,26,1),(33,40,23,1),(34,40,18,1),(35,40,27,1),(36,40,17,1),(37,40,21,1),(38,40,13,1),(39,39,12,1),(40,39,23,1),(41,39,18,1),(42,39,27,1),(43,39,17,1),(44,39,21,1),(45,39,13,1),(46,38,26,1),(47,38,15,1),(48,38,16,1),(49,38,28,1),(50,38,14,1),(51,38,20,1),(52,38,30,1),(53,36,26,1),(54,36,23,1),(55,36,18,1),(56,36,25,1),(57,36,17,1),(58,36,21,1),(59,36,13,1),(60,35,26,1),(61,35,15,1),(62,35,18,1),(63,35,25,1),(64,35,17,1),(65,35,21,1),(66,35,19,1);
/*!40000 ALTER TABLE `vote` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `voters`
--

DROP TABLE IF EXISTS `voters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `voters` (
  `v_id` int(11) NOT NULL AUTO_INCREMENT,
  `stud_id` varchar(100) NOT NULL,
  `acad_id` int(11) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `mname` varchar(100) NOT NULL,
  `grade_level` int(5) NOT NULL,
  `strand` varchar(100) NOT NULL,
  `section` varchar(100) NOT NULL,
  `auth_code` text NOT NULL,
  `date_issued` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`v_id`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `voters`
--

LOCK TABLES `voters` WRITE;
/*!40000 ALTER TABLE `voters` DISABLE KEYS */;
INSERT INTO `voters` VALUES (12,'2019-2322',1,'john rey','decosta','nalla',7,'','I','123c1103f638c5d13e6c53425b8b086a','2022-06-24 12:46:39'),(13,'2018-01282',1,'Mechelle','lallab','pax',11,'TVL-ICT','A','662808e52ef7d7f236a23c5f9414c540','2022-06-24 13:04:33'),(14,'2019-2323',1,'jobert','domingo','quisto',12,'STEM','A','eda18848f81890ea7b49436e2b8b1788','2022-06-24 13:04:58'),(15,'2019-2324',1,'jaazer','LAPLANA','bataluna',8,'','B','77870ec9a82200c8cb5c89bec4baaeb1','2022-06-24 13:05:38'),(16,'2019-2325',1,'jarid jay','espanola','fernandez',10,'','Z','cff74adfaff46f848cdf719f14da49e1','2022-06-24 13:06:07'),(17,'2019-2326',1,'annie','gonia','laboa',7,'','A','a90d1bfd5a2e482e9344baf5c128cc44','2022-06-24 13:06:42'),(18,'2019-2327',1,'anjelly','fusingan','e',11,'ABM','A','f448e0b49483cf6a3936a3434512465c','2022-06-24 13:07:15'),(19,'2019-2328',1,'nicky','bataluna','x',8,'','A','d0ed0c6655dedbce6cb875c484518a7c','2022-06-24 13:07:41'),(20,'2019-2329',1,'bart','batiancila','f',9,'','A','ec629ae356006909d75c6d54d45073fb','2022-06-24 13:08:05'),(21,'2019-2330',1,'carmel','preciados','t',7,'','A','026a04022d6daaae809a1caefbb66e4b','2022-06-24 13:08:38'),(22,'2019-2322',1,'jinky','flores','f',11,'GAS','A','9e701d6cc0bad656b1adc16a48102a2f','2022-06-24 13:09:04'),(23,'2019-2331',1,'ile nathaniel','flores','d',12,'STEM','A','9a85d2f00723060b93ae7fb085a177ae','2022-06-24 13:09:32'),(24,'2019-2332',1,'john franklim','lanoy','g',7,'','A','f3135e99737586ac03a8e4aec158fc9c','2022-06-24 13:10:03'),(25,'2019-2333',1,'jaime','diamante jr','r',11,'TVL-COOKERY','A','4f91d25dfecc65beead11a8e19f3ea0f','2022-06-24 13:10:28'),(26,'2019-2335',1,'cyril ','tulod','d',11,'GAS','A','a14f78d33c531f845787df4a76418c4b','2022-06-24 13:19:14'),(27,'2019-2336',1,'MYRINE','RIZABA','D',7,'','N','f16ac01650564cd2bc13718ae5210d52','2022-06-24 13:43:10'),(28,'2019-2337',1,'eddie','alcazar jr','k',10,'','K','bed7eeb414f9a52116f9cef41ed1bce4','2022-06-24 14:22:02'),(29,'2019-232',1,'JOHN REY','decosta','nalla',7,'','A','cf4b4252cfe450caf9624a6ee0948489','2022-06-24 14:23:20'),(30,'2019-2338',1,'kyna marie','cuna','a',7,'','B','5619861735b12ef6709f5a88ff2850ba','2022-06-24 14:24:55'),(31,'2019-2339',1,'josh renzo','esmail','d',11,'TVL-ICT','A','47253dc958a09c2c485714b2b248ee35','2022-06-24 14:26:14'),(32,'2019-2340',1,'christian','ARANAS','s',12,'GAS','A','d0744163dbef762029b7417e1552305a','2022-06-24 14:27:13'),(33,'2019-2341',1,'judy ann ','palopalo','f',7,'','Q','09ecac0c70716d873a3523439317e568','2022-06-24 14:29:48'),(34,'2019-2342',1,'joey','penAFORIDA','H',11,'TVL-COOKERY','A','aa72089239e73fa8701deddfa803613c','2022-06-24 14:30:28'),(35,'2019-2343',1,'ANNA ROSE','BARNESO','N',11,'TVL-ICT','A','ba666b1e7f92ac31cb67ec8beadee3ad','2022-06-24 14:30:55'),(36,'2019-2344',1,'DAN DAVE','PENAFIEL','G',12,'STEM','A','9c627797deb49ff15e2c642a61db5890','2022-06-24 14:31:22'),(37,'2019-2345',1,'RUSTY','ehimplar','S',9,'','S','ffed2b886118d25e650db842c6897a98','2022-06-24 14:31:50'),(38,'2019-2346',1,'JONAFE','DAGOHOY','H',10,'','A','328e073da08ee2f33e3da02b34baa198','2022-06-24 14:32:37'),(39,'2019-2347',1,'JEAN','FEO','O',9,'','A','527f0c726588661da4c23f0a4144515b','2022-06-24 14:33:06'),(40,'2019-2348',1,'JUNREY','DEPAUR','P',11,'ABM','A','60bd7aeaaf6435be0dad3a8b82902fe1','2022-06-24 14:33:35'),(41,'2019-2349',1,'SHEILA MAE','NAMION','P',9,'','P','f853a214b62302ff162c0914ac1d3b94','2022-06-24 14:34:14'),(42,'2019-2350',1,'RHYME ROSE','PANES','L',12,'GAS','A','9aeb96c952c2eb9c7cc014a4c311e815','2022-06-24 14:34:51');
/*!40000 ALTER TABLE `voters` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-06-24 14:54:45
