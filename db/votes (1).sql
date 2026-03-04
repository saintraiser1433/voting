-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 04, 2026 at 10:58 AM
-- Server version: 9.1.0
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `votes`
--

-- --------------------------------------------------------

--
-- Table structure for table `acad_tbl`
--

DROP TABLE IF EXISTS `acad_tbl`;
CREATE TABLE IF NOT EXISTS `acad_tbl` (
  `acad_id` int NOT NULL AUTO_INCREMENT,
  `description` varchar(50) NOT NULL,
  `status` int NOT NULL,
  PRIMARY KEY (`acad_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `acad_tbl`
--

INSERT INTO `acad_tbl` (`acad_id`, `description`, `status`) VALUES
(1, '2022', 1),
(7, '2023', 0);

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3');

-- --------------------------------------------------------

--
-- Table structure for table `archives`
--

DROP TABLE IF EXISTS `archives`;
CREATE TABLE IF NOT EXISTS `archives` (
  `id` int NOT NULL AUTO_INCREMENT,
  `v_id` int NOT NULL,
  `stud_id` varchar(100) NOT NULL,
  `acad_id` int NOT NULL,
  `department_id` int DEFAULT NULL,
  `fname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `mname` varchar(100) DEFAULT NULL,
  `grade_level` int NOT NULL,
  `strand` varchar(100) DEFAULT NULL,
  `section` varchar(100) DEFAULT NULL,
  `auth_code` varchar(255) DEFAULT NULL,
  `date_issued` datetime DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_v_id` (`v_id`),
  KEY `idx_acad_id` (`acad_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `archivesvote`
--

DROP TABLE IF EXISTS `archivesvote`;
CREATE TABLE IF NOT EXISTS `archivesvote` (
  `id` int NOT NULL AUTO_INCREMENT,
  `voter_id` int NOT NULL,
  `candidate_id` int NOT NULL,
  `acad_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_voter_id` (`voter_id`),
  KEY `idx_acad_id` (`acad_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `archives_department_vote`
--

DROP TABLE IF EXISTS `archives_department_vote`;
CREATE TABLE IF NOT EXISTS `archives_department_vote` (
  `id` int NOT NULL AUTO_INCREMENT,
  `voter_id` int NOT NULL,
  `candidate_id` int NOT NULL,
  `acad_id` int NOT NULL,
  `department_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_voter_id` (`voter_id`),
  KEY `idx_acad_id` (`acad_id`),
  KEY `idx_department_id` (`department_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `candidate`
--

DROP TABLE IF EXISTS `candidate`;
CREATE TABLE IF NOT EXISTS `candidate` (
  `c_id` int NOT NULL AUTO_INCREMENT,
  `acad_id` int NOT NULL,
  `p_id` int NOT NULL,
  `stud_id` varchar(100) NOT NULL,
  `pos_id` int NOT NULL,
  `img` varchar(100) NOT NULL,
  `platform` text,
  PRIMARY KEY (`c_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `candidate`
--

INSERT INTO `candidate` (`c_id`, `acad_id`, `p_id`, `stud_id`, `pos_id`, `img`, `platform`) VALUES
(1, 1, 16, '123', 12, 'libraries/img/glanlogo.png', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
CREATE TABLE IF NOT EXISTS `courses` (
  `course_id` int NOT NULL AUTO_INCREMENT,
  `course_code` varchar(20) NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `acad_id` int NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`course_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course_id`, `course_code`, `course_name`, `acad_id`, `status`, `date_created`) VALUES
(1, 'IT 123', 'COURSE 1', 1, 1, '2026-03-03 17:51:09');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
CREATE TABLE IF NOT EXISTS `departments` (
  `department_id` int NOT NULL AUTO_INCREMENT,
  `department_name` varchar(100) NOT NULL,
  `status` int NOT NULL DEFAULT '1' COMMENT '1=active, 0=archived',
  PRIMARY KEY (`department_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `department_name`, `status`) VALUES
(1, 'qwe', 0),
(2, 'fsdfds', 0),
(3, 'qwe', 1),
(4, 'xxx', 1);

-- --------------------------------------------------------

--
-- Table structure for table `department_vote`
--

DROP TABLE IF EXISTS `department_vote`;
CREATE TABLE IF NOT EXISTS `department_vote` (
  `id` int NOT NULL AUTO_INCREMENT,
  `voter_id` int NOT NULL,
  `candidate_id` int NOT NULL,
  `acad_id` int NOT NULL,
  `department_id` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `department_vote`
--

INSERT INTO `department_vote` (`id`, `voter_id`, `candidate_id`, `acad_id`, `department_id`) VALUES
(4, 11, 2, 1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `dept_candidate`
--

DROP TABLE IF EXISTS `dept_candidate`;
CREATE TABLE IF NOT EXISTS `dept_candidate` (
  `dc_id` int NOT NULL AUTO_INCREMENT,
  `acad_id` int NOT NULL,
  `stud_id` varchar(100) NOT NULL,
  `pos_id` int NOT NULL,
  `department_id` int NOT NULL,
  `img` varchar(100) NOT NULL DEFAULT 'libraries/img/logo.png',
  `platform` text,
  PRIMARY KEY (`dc_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `dept_candidate`
--

INSERT INTO `dept_candidate` (`dc_id`, `acad_id`, `stud_id`, `pos_id`, `department_id`, `img`, `platform`) VALUES
(1, 1, '1234', 1, 3, 'candidatephoto/ecb4cc1c535498077df715dc9085153f.jpg', 'dfgfdg'),
(2, 1, '155', 2, 4, 'libraries/img/logo.png', 'fsdfsd');

-- --------------------------------------------------------

--
-- Table structure for table `dept_position`
--

DROP TABLE IF EXISTS `dept_position`;
CREATE TABLE IF NOT EXISTS `dept_position` (
  `dp_id` int NOT NULL AUTO_INCREMENT,
  `description` varchar(100) NOT NULL,
  `max_vote` int NOT NULL DEFAULT '1',
  `acad_id` int NOT NULL,
  `priority` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`dp_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `dept_position`
--

INSERT INTO `dept_position` (`dp_id`, `description`, `max_vote`, `acad_id`, `priority`) VALUES
(1, 'muse', 1, 1, 0),
(2, 'eqwe', 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `dept_voters`
--

DROP TABLE IF EXISTS `dept_voters`;
CREATE TABLE IF NOT EXISTS `dept_voters` (
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

-- --------------------------------------------------------

--
-- Table structure for table `election_title`
--

DROP TABLE IF EXISTS `election_title`;
CREATE TABLE IF NOT EXISTS `election_title` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `acad_id` int NOT NULL,
  `election_type` varchar(20) NOT NULL DEFAULT 'general',
  `is_finished` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=open, 1=closed',
  `date_start` datetime DEFAULT NULL COMMENT 'When voting opens',
  `date_end` datetime DEFAULT NULL COMMENT 'When voting closes',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `election_title`
--

INSERT INTO `election_title` (`id`, `title`, `acad_id`, `election_type`, `is_finished`, `date_start`, `date_end`) VALUES
(5, 'SELECTING THE RIGHTEOUS CANDIDATES FOR THE BEST ENVIRONMENT', 1, 'general', 1, '2026-03-01 14:36:00', '2026-03-05 14:36:00'),
(6, 'qweeq', 1, 'department', 1, '2026-03-01 14:37:00', '2026-03-05 14:37:00'),
(7, 'qwewqe', 7, 'general', 0, '2026-03-01 14:30:00', '2026-03-31 14:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `partylist`
--

DROP TABLE IF EXISTS `partylist`;
CREATE TABLE IF NOT EXISTS `partylist` (
  `p_id` int NOT NULL AUTO_INCREMENT,
  `acad_id` int NOT NULL,
  `party_name` varchar(100) NOT NULL,
  `platform` text NOT NULL,
  `img` varchar(100) NOT NULL,
  PRIMARY KEY (`p_id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `partylist`
--

INSERT INTO `partylist` (`p_id`, `acad_id`, `party_name`, `platform`, `img`) VALUES
(16, 1, 'p1', '<p>fsdfsd</p>\r\n', 'partylistphoto/p11772533426.png');

-- --------------------------------------------------------

--
-- Table structure for table `position`
--

DROP TABLE IF EXISTS `position`;
CREATE TABLE IF NOT EXISTS `position` (
  `pos_id` int NOT NULL AUTO_INCREMENT,
  `description` varchar(100) NOT NULL,
  `max_vote` int NOT NULL,
  `acad_id` int NOT NULL,
  `priority` int NOT NULL,
  PRIMARY KEY (`pos_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `position`
--

INSERT INTO `position` (`pos_id`, `description`, `max_vote`, `acad_id`, `priority`) VALUES
(12, 'president', 1, 1, 1),
(13, 'IT Department Head', 1, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `vote`
--

DROP TABLE IF EXISTS `vote`;
CREATE TABLE IF NOT EXISTS `vote` (
  `id` int NOT NULL AUTO_INCREMENT,
  `voter_id` int NOT NULL,
  `candidate_id` int NOT NULL,
  `acad_id` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `voters`
--

DROP TABLE IF EXISTS `voters`;
CREATE TABLE IF NOT EXISTS `voters` (
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
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `voters`
--

INSERT INTO `voters` (`v_id`, `stud_id`, `acad_id`, `fname`, `lname`, `mname`, `grade_level`, `strand`, `section`, `department_id`, `auth_code`, `date_issued`, `is_verified_general`, `is_verified_dept`, `is_verified`, `password`) VALUES
(7, '123', 1, 'Ma.', 'Fusingan', 'Anjelly E.', 1, 'IT 123', 'A', 3, '', '2026-03-03 18:38:47', 0, 0, 1, ''),
(8, '1234', 1, 'john', 'decosta', 'nalla', 1, 'IT 123', 'A', 3, '', '2026-03-03 19:11:20', 0, 0, 1, ''),
(10, '155', 1, 'nelson', 'mandela', 'asds', 2, 'IT 123', 'B', 4, '', '2026-03-04 18:36:10', 0, 0, 1, ''),
(9, '122', 1, 'john', 'ray', 'dec', 1, 'IT 123', 'A', 3, '', '2026-03-04 10:09:27', 0, 0, 1, 'a0a080f42e6f13b3a2df133f073095dd'),
(11, '166', 1, 'andrei', 'decosta', 'asd', 2, 'IT 123', 'D', 4, '', '2026-03-04 18:36:50', 0, 0, 1, '7e7757b1e12abcb736ab9a754ffb617a');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
