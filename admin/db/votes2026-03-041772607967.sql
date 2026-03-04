-- mysqldump-php https://github.com/ifsnop/mysqldump-php
--
-- Host: localhost	Database: votes
-- ------------------------------------------------------
-- Server version 	9.1.0
-- Date: Wed, 04 Mar 2026 07:06:07 +0000

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
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acad_tbl`
--

LOCK TABLES `acad_tbl` WRITE;
/*!40000 ALTER TABLE `acad_tbl` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `acad_tbl` VALUES (1,'2022',1),(7,'2023',0);
/*!40000 ALTER TABLE `acad_tbl` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `acad_tbl` with 2 row(s)
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
INSERT INTO `admin` VALUES (1,'admin','21232f297a57a5a743894a0e4a801fc3');
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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `candidate`
--

LOCK TABLES `candidate` WRITE;
/*!40000 ALTER TABLE `candidate` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `candidate` VALUES (1,1,16,'123',12,'libraries/img/glanlogo.png',NULL);
/*!40000 ALTER TABLE `candidate` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `candidate` with 1 row(s)
--

--
-- Table structure for table `courses`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `courses` (
  `course_id` int NOT NULL AUTO_INCREMENT,
  `course_code` varchar(20) NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `acad_id` int NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`course_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `courses`
--

LOCK TABLES `courses` WRITE;
/*!40000 ALTER TABLE `courses` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `courses` VALUES (1,'IT 123','COURSE 1',1,1,'2026-03-03 17:51:09');
/*!40000 ALTER TABLE `courses` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `courses` with 1 row(s)
--

--
-- Table structure for table `department_vote`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `department_vote` (
  `id` int NOT NULL AUTO_INCREMENT,
  `voter_id` int NOT NULL,
  `candidate_id` int NOT NULL,
  `acad_id` int NOT NULL,
  `department_id` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `department_vote`
--

LOCK TABLES `department_vote` WRITE;
/*!40000 ALTER TABLE `department_vote` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `department_vote` VALUES (1,9,1,1,3);
/*!40000 ALTER TABLE `department_vote` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `department_vote` with 1 row(s)
--

--
-- Table structure for table `departments`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `departments` (
  `department_id` int NOT NULL AUTO_INCREMENT,
  `department_name` varchar(100) NOT NULL,
  `status` int NOT NULL DEFAULT '1' COMMENT '1=active, 0=archived',
  PRIMARY KEY (`department_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `departments` VALUES (1,'qwe',0),(2,'fsdfds',0),(3,'qwe',1);
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `departments` with 3 row(s)
--

--
-- Table structure for table `dept_candidate`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dept_candidate` (
  `dc_id` int NOT NULL AUTO_INCREMENT,
  `acad_id` int NOT NULL,
  `stud_id` varchar(100) NOT NULL,
  `pos_id` int NOT NULL,
  `department_id` int NOT NULL,
  `img` varchar(100) NOT NULL DEFAULT 'libraries/img/logo.png',
  `platform` text,
  PRIMARY KEY (`dc_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dept_candidate`
--

LOCK TABLES `dept_candidate` WRITE;
/*!40000 ALTER TABLE `dept_candidate` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `dept_candidate` VALUES (1,1,'1234',1,3,'candidatephoto/ecb4cc1c535498077df715dc9085153f.jpg','dfgfdg');
/*!40000 ALTER TABLE `dept_candidate` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `dept_candidate` with 1 row(s)
--

--
-- Table structure for table `dept_position`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dept_position` (
  `dp_id` int NOT NULL AUTO_INCREMENT,
  `description` varchar(100) NOT NULL,
  `max_vote` int NOT NULL DEFAULT '1',
  `acad_id` int NOT NULL,
  `priority` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`dp_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dept_position`
--

LOCK TABLES `dept_position` WRITE;
/*!40000 ALTER TABLE `dept_position` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `dept_position` VALUES (1,'muse',1,1,0),(2,'eqwe',1,1,1);
/*!40000 ALTER TABLE `dept_position` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `dept_position` with 2 row(s)
--

--
-- Table structure for table `dept_voters`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dept_voters` (
  `dv_id` int NOT NULL AUTO_INCREMENT,
  `stud_id` varchar(100) NOT NULL,
  `acad_id` int NOT NULL,
  `fname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `mname` varchar(100) NOT NULL DEFAULT '',
  `year_level` int NOT NULL DEFAULT '1',
  `strand` varchar(100) NOT NULL DEFAULT '',
  `section` varchar(100) NOT NULL DEFAULT '',
  `department_id` int NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `is_verified` int NOT NULL DEFAULT '0',
  `date_issued` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`dv_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dept_voters`
--

LOCK TABLES `dept_voters` WRITE;
/*!40000 ALTER TABLE `dept_voters` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `dept_voters` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `dept_voters` with 0 row(s)
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
  `election_type` varchar(20) NOT NULL DEFAULT 'general',
  `is_finished` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=open, 1=closed',
  `date_start` datetime DEFAULT NULL COMMENT 'When voting opens',
  `date_end` datetime DEFAULT NULL COMMENT 'When voting closes',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `election_title`
--

LOCK TABLES `election_title` WRITE;
/*!40000 ALTER TABLE `election_title` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `election_title` VALUES (5,'SELECTING THE RIGHTEOUS CANDIDATES FOR THE BEST ENVIRONMENT',1,'general',0,'2026-03-01 14:36:00','2026-03-05 14:36:00'),(6,'qweeq',1,'department',0,'2026-03-01 14:37:00','2026-03-05 14:37:00'),(7,'qwewqe',7,'general',0,'2026-03-01 14:30:00','2026-03-31 14:30:00');
/*!40000 ALTER TABLE `election_title` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `election_title` with 3 row(s)
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
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `partylist`
--

LOCK TABLES `partylist` WRITE;
/*!40000 ALTER TABLE `partylist` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `partylist` VALUES (16,1,'p1','<p>fsdfsd</p>\r\n','partylistphoto/p11772533426.png');
/*!40000 ALTER TABLE `partylist` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `partylist` with 1 row(s)
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
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `position`
--

LOCK TABLES `position` WRITE;
/*!40000 ALTER TABLE `position` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `position` VALUES (12,'president',1,1,1),(13,'IT Department Head',1,1,2);
/*!40000 ALTER TABLE `position` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `position` with 2 row(s)
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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vote`
--

LOCK TABLES `vote` WRITE;
/*!40000 ALTER TABLE `vote` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `vote` VALUES (1,9,1,1);
/*!40000 ALTER TABLE `vote` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `vote` with 1 row(s)
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
  `department_id` int DEFAULT NULL,
  `auth_code` text NOT NULL,
  `date_issued` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_verified_general` tinyint(1) NOT NULL DEFAULT '0',
  `is_verified_dept` tinyint(1) NOT NULL DEFAULT '0',
  `is_verified` tinyint(1) NOT NULL DEFAULT '0',
  `password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`v_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `voters`
--

LOCK TABLES `voters` WRITE;
/*!40000 ALTER TABLE `voters` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `voters` VALUES (7,'123',1,'Ma.','Fusingan','Anjelly E.',1,'IT 123','A',3,'','2026-03-03 18:38:47',0,0,1,'202cb962ac59075b964b07152d234b70'),(8,'1234',1,'john','decosta','nalla',1,'IT 123','A',3,'','2026-03-03 19:11:20',0,0,1,''),(9,'122',1,'john','ray','dec',1,'IT 123','A',3,'','2026-03-04 10:09:27',0,0,1,'');
/*!40000 ALTER TABLE `voters` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `voters` with 3 row(s)
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

-- Dump completed on: Wed, 04 Mar 2026 07:06:07 +0000
