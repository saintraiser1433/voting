-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 02, 2026 at 11:30 AM
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
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `acad_tbl`
--

INSERT INTO `acad_tbl` (`acad_id`, `description`, `status`) VALUES
(1, '2022', 1);

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `election_title`
--

INSERT INTO `election_title` (`id`, `title`, `acad_id`, `election_type`) VALUES
(5, 'SELECTING THE RIGHTEOUS CANDIDATES FOR THE BEST ENVIRONMENT', 1, 'general');

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
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

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
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
  `auth_code` text NOT NULL,
  `date_issued` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`v_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `voters`
--

INSERT INTO `voters` (`v_id`, `stud_id`, `acad_id`, `fname`, `lname`, `mname`, `grade_level`, `strand`, `section`, `auth_code`, `date_issued`) VALUES
(3, '2019-2323', 1, 'JOHN REY', 'decosta', 'nalla', 7, '', 'H', 'e4116e3f4060cb8b5ee7a1745ecd5d8c', '2022-06-20 00:02:03');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
