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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'admin','admin');
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
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `candidate`
--

LOCK TABLES `candidate` WRITE;
/*!40000 ALTER TABLE `candidate` DISABLE KEYS */;
INSERT INTO `candidate` VALUES (11,1,16,'	2019-2322',17,'libraries/img/logo.png',NULL),(10,1,16,'2019-2322',12,'libraries/img/logo.png',NULL),(12,1,17,'	2019-2331',12,'libraries/img/logo.png',NULL),(13,1,17,'	2019-2333',17,'libraries/img/logo.png',NULL),(14,1,17,'	2019-2324',16,'libraries/img/logo.png',NULL),(15,1,16,'	2019-2325',13,'libraries/img/logo.png',NULL),(16,1,16,'	2019-2326',14,'libraries/img/logo.png',NULL),(17,1,16,'	2019-2327',16,'libraries/img/logo.png',NULL),(18,1,17,'	2019-2323',14,'libraries/img/logo.png',NULL),(19,1,17,'	2019-2328',17,'libraries/img/logo.png',NULL),(20,1,16,'	2019-2330',17,'libraries/img/logo.png',NULL),(21,1,16,'2018-01282',17,'libraries/img/logo.png',NULL),(22,1,0,'	2019-2329',12,'candidatephoto/799bad5a3b514f096e69bbc4a7896cd9.jpg',''),(23,1,0,'	2019-2332',13,'libraries/img/logo.png',''),(24,1,0,'	2019-2335',14,'libraries/img/logo.png','');
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
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vote`
--

LOCK TABLES `vote` WRITE;
/*!40000 ALTER TABLE `vote` DISABLE KEYS */;
INSERT INTO `vote` VALUES (4,12,10,1);
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
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `voters`
--

LOCK TABLES `voters` WRITE;
/*!40000 ALTER TABLE `voters` DISABLE KEYS */;
INSERT INTO `voters` VALUES (12,'2019-2322',1,'john rey','decosta','nalla',7,'','I','123c1103f638c5d13e6c53425b8b086a','2022-06-24 12:46:39'),(13,'2018-01282',1,'Mechelle','lallab','pax',11,'TVL-ICT','A','662808e52ef7d7f236a23c5f9414c540','2022-06-24 13:04:33'),(14,'2019-2323',1,'jobert','domingo','quisto',12,'STEM','A','eda18848f81890ea7b49436e2b8b1788','2022-06-24 13:04:58'),(15,'2019-2324',1,'jaazer','LAPLANA','bataluna',8,'','B','77870ec9a82200c8cb5c89bec4baaeb1','2022-06-24 13:05:38'),(16,'2019-2325',1,'jarid jay','espanola','fernandez',10,'','Z','cff74adfaff46f848cdf719f14da49e1','2022-06-24 13:06:07'),(17,'2019-2326',1,'annie','gonia','laboa',7,'','A','a90d1bfd5a2e482e9344baf5c128cc44','2022-06-24 13:06:42'),(18,'2019-2327',1,'anjelly','fusingan','e',11,'ABM','A','f448e0b49483cf6a3936a3434512465c','2022-06-24 13:07:15'),(19,'2019-2328',1,'nicky','bataluna','x',8,'','A','d0ed0c6655dedbce6cb875c484518a7c','2022-06-24 13:07:41'),(20,'2019-2329',1,'bart','batiancila','f',9,'','A','ec629ae356006909d75c6d54d45073fb','2022-06-24 13:08:05'),(21,'2019-2330',1,'carmel','preciados','t',7,'','A','026a04022d6daaae809a1caefbb66e4b','2022-06-24 13:08:38'),(22,'2019-2322',1,'jinky','flores','f',11,'GAS','A','9e701d6cc0bad656b1adc16a48102a2f','2022-06-24 13:09:04'),(23,'2019-2331',1,'ile nathaniel','flores','d',12,'STEM','A','9a85d2f00723060b93ae7fb085a177ae','2022-06-24 13:09:32'),(24,'2019-2332',1,'john franklim','lanoy','g',7,'','A','f3135e99737586ac03a8e4aec158fc9c','2022-06-24 13:10:03'),(25,'2019-2333',1,'jaime','diamante jr','r',11,'TVL-COOKERY','A','4f91d25dfecc65beead11a8e19f3ea0f','2022-06-24 13:10:28'),(26,'2019-2335',1,'cyril ','tulod','d',11,'GAS','A','a14f78d33c531f845787df4a76418c4b','2022-06-24 13:19:14');
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

-- Dump completed on 2022-06-24 13:37:35
