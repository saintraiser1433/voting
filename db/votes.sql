-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 08, 2024 at 11:58 AM
-- Server version: 8.2.0
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
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `acad_tbl`
--

INSERT INTO `acad_tbl` (`acad_id`, `description`, `status`) VALUES
(1, '2022', 0),
(8, '2024', 1);

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(2, 'admin', '21232f297a57a5a743894a0e4a801fc3');

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
  `fname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `mname` varchar(100) NOT NULL,
  `grade_level` int NOT NULL,
  `strand` varchar(100) NOT NULL,
  `section` varchar(100) NOT NULL,
  `auth_code` text NOT NULL,
  `date_issued` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `archives`
--

INSERT INTO `archives` (`id`, `v_id`, `stud_id`, `acad_id`, `fname`, `lname`, `mname`, `grade_level`, `strand`, `section`, `auth_code`, `date_issued`, `password`) VALUES
(17, 46, '20121-2323', 1, 'johanie', 'valeros', 'mine', 7, '', 'H', '66488ee6886ab7696ca4445d590b8956', '2022-06-28 20:37:35', ''),
(18, 69, '2024-0007', 8, 'jj', 'olatundi', 'ksi', 7, 'undefined', 'B', '732d273e5c8622c330991a0c393f80c2', '2024-12-07 03:23:47', '');

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
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `archivesvote`
--

INSERT INTO `archivesvote` (`id`, `voter_id`, `candidate_id`, `acad_id`) VALUES
(8, 46, 19, 1),
(9, 46, 21, 1),
(10, 46, 14, 1),
(11, 46, 25, 1),
(12, 46, 15, 1),
(13, 46, 22, 1);

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
) ENGINE=MyISAM AUTO_INCREMENT=51 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `candidate`
--

INSERT INTO `candidate` (`c_id`, `acad_id`, `p_id`, `stud_id`, `pos_id`, `img`, `platform`) VALUES
(26, 1, 16, '2019-232', 12, 'candidatephoto/f3ccdd27d2000e3f9255a7e3e2c48800.jpg', NULL),
(12, 1, 17, '2019-2331', 12, 'libraries/img/logo.png', NULL),
(13, 1, 17, '2019-2333', 17, 'libraries/img/logo.png', NULL),
(14, 1, 17, '2019-2324', 16, 'libraries/img/logo.png', NULL),
(15, 1, 16, '2019-2325', 13, 'libraries/img/logo.png', NULL),
(16, 1, 16, '2019-2326', 14, 'libraries/img/logo.png', NULL),
(17, 1, 16, '2019-2327', 16, 'libraries/img/logo.png', NULL),
(18, 1, 17, '2019-2323', 14, 'libraries/img/logo.png', NULL),
(19, 1, 17, '2019-2328', 17, 'libraries/img/logo.png', NULL),
(20, 1, 16, '2019-2330', 17, 'libraries/img/logo.png', NULL),
(21, 1, 16, '2018-01282', 17, 'libraries/img/logo.png', NULL),
(50, 8, 0, '2024-0017', 22, 'candidatephoto/64b8299d1597b8a5c7b9cb9c88642f6c.jpg', '<p>To ensure that all the budgets within the SSG elections are all secured within me.</p>\r\n'),
(27, 1, 16, '2019-2337', 15, 'libraries/img/logo.png', NULL),
(28, 1, 17, '2019-2338', 15, 'libraries/img/logo.png', NULL),
(49, 8, 0, '2024-0016', 19, 'candidatephoto/85b6f89b41cae26786ac72365fff771b.jpg', '<ul>\r\n	<li>To ensure that all garbages will be thrown away on their respective places.</li>\r\n	<li>Add more courts like volleyball court, basketball court, takraw, etc.</li>\r\n</ul>\r\n'),
(31, 1, 16, '2018-2022', 17, 'libraries/img/logo.png', NULL),
(33, 8, 19, '2024-0001', 19, 'candidatephoto/f3ccdd27d2000e3f9255a7e3e2c48800.jpg', NULL),
(34, 8, 19, '2024-0002', 20, 'candidatephoto/156005c5baf40ff51a327f1c34f2975b.jpg', NULL),
(35, 8, 19, '2024-0003', 21, 'candidatephoto/799bad5a3b514f096e69bbc4a7896cd9.jpg', NULL),
(36, 8, 19, '2024-0004', 22, 'candidatephoto/d0096ec6c83575373e3a21d129ff8fef.jpg', NULL),
(37, 8, 19, '2024-0005', 23, 'candidatephoto/032b2cc936860b03048302d991c3498f.jpg', NULL),
(38, 8, 19, '2024-0006', 24, 'candidatephoto/18e2999891374a475d0687ca9f989d83.jpg', NULL),
(40, 8, 19, '2024-0007', 25, 'candidatephoto/fe5df232cafa4c4e0f1a0294418e5660.jpg', NULL),
(41, 8, 20, '2024-0008', 19, 'candidatephoto/8cda81fc7ad906927144235dda5fdf15.jpg', NULL),
(42, 8, 20, '2024-0009', 20, 'candidatephoto/30e62fddc14c05988b44e7c02788e187.jpg', NULL),
(43, 8, 20, '2024-0010', 21, 'candidatephoto/ae566253288191ce5d879e51dae1d8c3.jpg', NULL),
(44, 8, 20, '2024-0011', 22, 'candidatephoto/62bf1edb36141f114521ec4bb4175579.jpg', NULL),
(45, 8, 20, '2024-0012', 23, 'candidatephoto/8df7b73a7820f4aef47864f2a6c5fccf.jpg', NULL),
(46, 8, 20, '2024-0013', 24, 'candidatephoto/9414a8f5b810972c3c9a0e2860c07532.jpg', NULL),
(47, 8, 20, '2024-0014', 25, 'candidatephoto/edab7ba7e203cd7576d1200465194ea8.jpg', NULL),
(48, 8, 20, '2024-0015', 25, 'candidatephoto/db3a17f7bcac837ecc1fe2bc630a5473.jpg', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `election_title`
--

DROP TABLE IF EXISTS `election_title`;
CREATE TABLE IF NOT EXISTS `election_title` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `acad_id` int NOT NULL,
  `date_start` date NOT NULL,
  `date_end` date NOT NULL,
  `is_finished` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `election_title`
--

INSERT INTO `election_title` (`id`, `title`, `acad_id`, `date_start`, `date_end`, `is_finished`) VALUES
(5, 'GIT SEGWAYs', 1, '2023-01-01', '2023-01-30', 1),
(6, 'SELECTING THE RIGHTEOUS CANDIDATES FOR THE BEST ENVIRONMENT', 8, '2024-12-06', '2024-12-30', 0);

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
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `partylist`
--

INSERT INTO `partylist` (`p_id`, `acad_id`, `party_name`, `platform`, `img`) VALUES
(16, 1, 'uniteam', '<p>dasdasds</p>\r\n', 'libraries/img/logo.png'),
(17, 1, 'KAKAMPINK', '<p>MY SAMPLE PLATFORMs</p>\r\n', 'libraries/img/logo.png'),
(19, 8, 'kabataan partylist', '<p>The main goal of Kabataan Partylist is to establish a bully-free and clean environment for the students to experience during the current school year.<br />\r\n<br />\r\nThese are the following things that the partylist wants to do:</p>\r\n\r\n<ul>\r\n	<li>Ensure a clean and green environment.</li>\r\n	<li>Acknowledge the clubs for the schools.</li>\r\n	<li>Create an E-sports club for the gamers within the school</li>\r\n</ul>\r\n', 'partylistphoto/kabataan partylist1733543333.png'),
(20, 8, 'Party partylist', '<p>The main goal of PARTY partylist is to:</p>\r\n\r\n<ul>\r\n	<li>Establish LGBTQ+ friendly environment.</li>\r\n	<li>Ensure that the foods on the canteen are not processed, but all home made.</li>\r\n	<li>Ensure that drinking fountains can be found per building.</li>\r\n</ul>\r\n', 'partylistphoto/Party partylist1733543429.png');

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
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `position`
--

INSERT INTO `position` (`pos_id`, `description`, `max_vote`, `acad_id`, `priority`) VALUES
(12, 'president', 1, 1, 1),
(13, 'VICE PRESIDENT', 1, 1, 2),
(14, 'SECRETARY', 1, 1, 3),
(15, 'Treasurer', 1, 1, 4),
(16, 'AUDITOR', 1, 1, 5),
(17, 'P.I.O', 2, 1, 7),
(19, 'President', 1, 8, 6),
(20, 'vice president', 1, 8, 8),
(21, 'secretary', 1, 8, 9),
(22, 'treasurer', 1, 8, 10),
(23, 'auditor', 1, 8, 11),
(24, 'pio', 1, 8, 12),
(25, 'business managers', 2, 8, 13);

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
) ENGINE=MyISAM AUTO_INCREMENT=277 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vote`
--

INSERT INTO `vote` (`id`, `voter_id`, `candidate_id`, `acad_id`) VALUES
(7, 26, 18, 1),
(8, 26, 27, 1),
(9, 26, 17, 1),
(10, 26, 21, 1),
(11, 26, 19, 1),
(12, 37, 26, 1),
(13, 37, 23, 1),
(14, 37, 18, 1),
(16, 37, 14, 1),
(17, 37, 21, 1),
(19, 42, 22, 1),
(20, 42, 16, 1),
(21, 42, 28, 1),
(22, 42, 17, 1),
(23, 42, 21, 1),
(24, 42, 29, 1),
(25, 41, 26, 1),
(26, 41, 23, 1),
(27, 41, 18, 1),
(29, 41, 17, 1),
(30, 41, 21, 1),
(31, 41, 19, 1),
(32, 40, 26, 1),
(33, 40, 23, 1),
(34, 40, 18, 1),
(35, 40, 27, 1),
(36, 40, 17, 1),
(37, 40, 21, 1),
(38, 40, 13, 1),
(39, 39, 12, 1),
(40, 39, 23, 1),
(41, 39, 18, 1),
(42, 39, 27, 1),
(43, 39, 17, 1),
(44, 39, 21, 1),
(45, 39, 13, 1),
(46, 38, 26, 1),
(47, 38, 15, 1),
(48, 38, 16, 1),
(49, 38, 28, 1),
(50, 38, 14, 1),
(51, 38, 20, 1),
(52, 38, 30, 1),
(53, 36, 26, 1),
(54, 36, 23, 1),
(55, 36, 18, 1),
(56, 36, 25, 1),
(57, 36, 17, 1),
(58, 36, 21, 1),
(59, 36, 13, 1),
(60, 35, 26, 1),
(61, 35, 15, 1),
(62, 35, 18, 1),
(63, 35, 25, 1),
(64, 35, 17, 1),
(65, 35, 21, 1),
(66, 35, 19, 1),
(67, 34, 12, 1),
(68, 34, 23, 1),
(69, 34, 18, 1),
(70, 34, 27, 1),
(71, 34, 17, 1),
(72, 34, 21, 1),
(73, 34, 13, 1),
(74, 33, 26, 1),
(75, 33, 23, 1),
(76, 33, 18, 1),
(77, 33, 27, 1),
(78, 33, 17, 1),
(79, 33, 21, 1),
(80, 33, 13, 1),
(81, 45, 22, 1),
(82, 45, 15, 1),
(83, 45, 18, 1),
(85, 45, 14, 1),
(86, 45, 21, 1),
(87, 45, 19, 1),
(112, 57, 21, 1),
(111, 57, 14, 1),
(110, 57, 27, 1),
(109, 57, 18, 1),
(108, 57, 23, 1),
(107, 57, 22, 1),
(100, 54, 12, 1),
(101, 54, 23, 1),
(102, 54, 18, 1),
(103, 54, 25, 1),
(104, 54, 14, 1),
(105, 54, 21, 1),
(106, 54, 20, 1),
(113, 58, 12, 1),
(114, 58, 15, 1),
(115, 58, 16, 1),
(116, 58, 17, 1),
(117, 58, 19, 1),
(118, 59, 12, 1),
(119, 59, 15, 1),
(120, 59, 18, 1),
(121, 59, 25, 1),
(122, 59, 14, 1),
(123, 59, 21, 1),
(124, 62, 33, 8),
(125, 62, 34, 8),
(126, 62, 35, 8),
(127, 62, 36, 8),
(128, 62, 37, 8),
(129, 62, 38, 8),
(130, 62, 40, 8),
(131, 62, 48, 8),
(132, 64, 41, 8),
(133, 64, 34, 8),
(134, 64, 43, 8),
(135, 64, 44, 8),
(136, 64, 45, 8),
(137, 64, 38, 8),
(138, 64, 47, 8),
(139, 65, 49, 8),
(140, 65, 34, 8),
(141, 65, 35, 8),
(142, 65, 44, 8),
(143, 65, 45, 8),
(144, 65, 38, 8),
(145, 65, 40, 8),
(146, 65, 47, 8),
(147, 66, 33, 8),
(148, 66, 34, 8),
(149, 66, 35, 8),
(150, 66, 50, 8),
(151, 66, 37, 8),
(152, 66, 46, 8),
(153, 66, 40, 8),
(154, 66, 47, 8),
(155, 67, 49, 8),
(156, 67, 42, 8),
(157, 67, 35, 8),
(158, 67, 50, 8),
(159, 67, 37, 8),
(160, 67, 46, 8),
(161, 67, 40, 8),
(162, 67, 48, 8),
(163, 68, 49, 8),
(164, 68, 34, 8),
(165, 68, 35, 8),
(166, 68, 44, 8),
(167, 68, 37, 8),
(168, 68, 38, 8),
(169, 68, 40, 8),
(170, 68, 48, 8),
(171, 70, 41, 8),
(172, 70, 42, 8),
(173, 70, 43, 8),
(174, 70, 36, 8),
(175, 70, 37, 8),
(176, 70, 46, 8),
(177, 70, 40, 8),
(178, 70, 47, 8),
(179, 71, 41, 8),
(180, 71, 34, 8),
(181, 71, 43, 8),
(182, 71, 44, 8),
(183, 71, 45, 8),
(184, 71, 38, 8),
(185, 71, 47, 8),
(186, 71, 48, 8),
(187, 73, 33, 8),
(188, 73, 34, 8),
(189, 73, 35, 8),
(190, 73, 36, 8),
(191, 73, 37, 8),
(192, 73, 38, 8),
(193, 73, 40, 8),
(194, 73, 48, 8),
(195, 75, 49, 8),
(196, 75, 42, 8),
(197, 75, 43, 8),
(198, 75, 44, 8),
(199, 75, 45, 8),
(200, 75, 46, 8),
(201, 75, 40, 8),
(202, 75, 48, 8),
(203, 81, 49, 8),
(204, 81, 34, 8),
(205, 81, 43, 8),
(206, 81, 50, 8),
(207, 81, 37, 8),
(208, 81, 46, 8),
(209, 81, 40, 8),
(210, 81, 48, 8),
(211, 83, 33, 8),
(212, 83, 34, 8),
(213, 83, 43, 8),
(214, 83, 44, 8),
(215, 83, 37, 8),
(216, 83, 46, 8),
(217, 83, 40, 8),
(218, 83, 48, 8),
(219, 82, 33, 8),
(220, 82, 42, 8),
(221, 82, 43, 8),
(222, 82, 36, 8),
(223, 82, 37, 8),
(224, 82, 46, 8),
(225, 82, 47, 8),
(226, 82, 48, 8),
(227, 85, 41, 8),
(228, 85, 36, 8),
(229, 85, 38, 8),
(230, 85, 47, 8),
(231, 53, 12, 1),
(232, 53, 15, 1),
(233, 53, 18, 1),
(234, 53, 27, 1),
(235, 53, 14, 1),
(236, 53, 21, 1),
(237, 53, 20, 1),
(238, 86, 33, 8),
(239, 86, 34, 8),
(240, 86, 43, 8),
(241, 86, 36, 8),
(242, 86, 37, 8),
(243, 86, 38, 8),
(244, 86, 40, 8),
(245, 86, 47, 8),
(246, 87, 33, 8),
(247, 87, 42, 8),
(248, 87, 43, 8),
(249, 87, 44, 8),
(250, 87, 45, 8),
(251, 87, 38, 8),
(252, 87, 40, 8),
(253, 87, 47, 8),
(254, 88, 33, 8),
(255, 88, 34, 8),
(256, 88, 43, 8),
(257, 88, 36, 8),
(258, 88, 46, 8),
(259, 88, 40, 8),
(260, 88, 47, 8),
(261, 89, 33, 8),
(262, 89, 34, 8),
(263, 89, 35, 8),
(264, 89, 50, 8),
(265, 89, 45, 8),
(266, 89, 38, 8),
(267, 89, 40, 8),
(268, 89, 47, 8),
(269, 90, 33, 8),
(270, 90, 34, 8),
(271, 90, 43, 8),
(272, 90, 44, 8),
(273, 90, 37, 8),
(274, 90, 38, 8),
(275, 90, 40, 8),
(276, 90, 47, 8);

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
  `password` varchar(100) NOT NULL,
  `is_verified` int NOT NULL DEFAULT '0' COMMENT '0-not verified, 1-verified',
  PRIMARY KEY (`v_id`)
) ENGINE=MyISAM AUTO_INCREMENT=91 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `voters`
--

INSERT INTO `voters` (`v_id`, `stud_id`, `acad_id`, `fname`, `lname`, `mname`, `grade_level`, `strand`, `section`, `auth_code`, `date_issued`, `password`, `is_verified`) VALUES
(12, '2019-2322', 1, 'john rey', 'decosta', 'nalla', 7, '', 'I', '123c1103f638c5d13e6c53425b8b086a', '2022-06-24 12:46:39', '827ccb0eea8a706c4c34a16891f84e7b', 0),
(13, '2018-01282', 1, 'Mechelle', 'lallab', 'pax', 11, 'TVL-ICT', 'A', '662808e52ef7d7f236a23c5f9414c540', '2022-06-24 13:04:33', '827ccb0eea8a706c4c34a16891f84e7b', 0),
(14, '2019-2323', 1, 'jobert', 'domingo', 'quisto', 12, 'STEM', 'A', 'eda18848f81890ea7b49436e2b8b1788', '2022-06-24 13:04:58', '827ccb0eea8a706c4c34a16891f84e7b', 0),
(15, '2019-2324', 1, 'jaazer', 'LAPLANA', 'bataluna', 8, '', 'B', '77870ec9a82200c8cb5c89bec4baaeb1', '2022-06-24 13:05:38', '33f5a7e8f4f310f9774894d807728e3c', 0),
(16, '2019-2325', 1, 'jarid jay', 'espanola', 'fernandez', 10, '', 'Z', 'cff74adfaff46f848cdf719f14da49e1', '2022-06-24 13:06:07', '', 0),
(17, '2019-2326', 1, 'annie', 'gonia', 'laboa', 7, '', 'A', 'a90d1bfd5a2e482e9344baf5c128cc44', '2022-06-24 13:06:42', '', 0),
(18, '2019-2327', 1, 'anjelly', 'fusingan', 'e', 11, 'ABM', 'A', 'f448e0b49483cf6a3936a3434512465c', '2022-06-24 13:07:15', '', 0),
(19, '2019-2328', 1, 'nicky', 'bataluna', 'x', 8, '', 'A', 'd0ed0c6655dedbce6cb875c484518a7c', '2024-12-08 16:31:48', '', 0),
(20, '2019-2329', 1, 'bart', 'batiancila', 'f', 9, '', 'A', 'ec629ae356006909d75c6d54d45073fb', '2022-06-24 13:08:05', '', 0),
(21, '2019-2330', 1, 'carmel', 'preciados', 't', 7, '', 'A', '026a04022d6daaae809a1caefbb66e4b', '2022-06-24 13:08:38', '', 0),
(22, '2019-2322', 1, 'jinky', 'flores', 'f', 11, 'GAS', 'A', '9e701d6cc0bad656b1adc16a48102a2f', '2022-06-24 13:09:04', '', 0),
(23, '2019-2331', 1, 'ile nathaniel', 'flores', 'd', 12, 'STEM', 'A', '9a85d2f00723060b93ae7fb085a177ae', '2022-06-24 13:09:32', '', 0),
(24, '2019-2332', 1, 'john franklim', 'lanoy', 'g', 7, '', 'A', 'f3135e99737586ac03a8e4aec158fc9c', '2022-06-24 13:10:03', '', 0),
(25, '2019-2333', 1, 'jaime', 'diamante jr', 'r', 11, 'TVL-COOKERY', 'A', '4f91d25dfecc65beead11a8e19f3ea0f', '2022-06-24 13:10:28', '', 0),
(26, '2019-2335', 1, 'cyril ', 'tulod', 'd', 11, 'GAS', 'A', 'a14f78d33c531f845787df4a76418c4b', '2022-06-24 13:19:14', '', 0),
(27, '2019-2336', 1, 'MYRINE', 'RIZABA', 'D', 7, '', 'N', 'f16ac01650564cd2bc13718ae5210d52', '2022-06-24 13:43:10', '', 0),
(28, '2019-2337', 1, 'eddie', 'alcazar jr', 'k', 10, '', 'K', 'bed7eeb414f9a52116f9cef41ed1bce4', '2022-06-24 14:22:02', '', 0),
(29, '2019-232', 1, 'JOHN REY', 'decosta', 'nalla', 7, '', 'A', 'cf4b4252cfe450caf9624a6ee0948489', '2022-06-24 14:23:20', '', 0),
(30, '2019-2338', 1, 'kyna marie', 'cuna', 'a', 7, '', 'B', '5619861735b12ef6709f5a88ff2850ba', '2022-06-24 14:24:55', '', 0),
(31, '2019-2339', 1, 'josh renzo', 'esmail', 'd', 11, 'TVL-ICT', 'A', '47253dc958a09c2c485714b2b248ee35', '2022-06-24 14:26:14', '', 0),
(32, '2019-2340', 1, 'christian', 'ARANAS', 's', 12, 'GAS', 'A', 'd0744163dbef762029b7417e1552305a', '2022-06-24 14:27:13', '', 0),
(33, '2019-2341', 1, 'judy ann ', 'palopalo', 'f', 7, '', 'Q', '09ecac0c70716d873a3523439317e568', '2022-06-24 14:29:48', '', 0),
(34, '2019-2342', 1, 'joey', 'penAFORIDA', 'H', 11, 'TVL-COOKERY', 'A', 'aa72089239e73fa8701deddfa803613c', '2022-06-24 14:30:28', '', 0),
(35, '2019-2343', 1, 'ANNA ROSE', 'BARNESO', 'N', 11, 'TVL-ICT', 'A', 'ba666b1e7f92ac31cb67ec8beadee3ad', '2022-06-24 14:30:55', '', 0),
(36, '2019-2344', 1, 'DAN DAVE', 'PENAFIEL', 'G', 12, 'STEM', 'A', '9c627797deb49ff15e2c642a61db5890', '2022-06-24 14:31:22', '', 0),
(37, '2019-2345', 1, 'RUSTY', 'ehimplar', 'S', 9, '', 'S', 'ffed2b886118d25e650db842c6897a98', '2022-06-24 14:31:50', '', 0),
(38, '2019-2346', 1, 'JONAFE', 'DAGOHOY', 'H', 10, '', 'A', '328e073da08ee2f33e3da02b34baa198', '2022-06-24 14:32:37', '', 0),
(39, '2019-2347', 1, 'JEAN', 'FEO', 'O', 9, '', 'A', '527f0c726588661da4c23f0a4144515b', '2022-06-24 14:33:06', '', 0),
(40, '2019-2348', 1, 'JUNREY', 'DEPAUR', 'P', 11, 'ABM', 'A', '60bd7aeaaf6435be0dad3a8b82902fe1', '2022-06-24 14:33:35', '', 0),
(41, '2019-2349', 1, 'SHEILA MAE', 'NAMION', 'P', 9, '', 'P', 'f853a214b62302ff162c0914ac1d3b94', '2022-06-24 14:34:14', '', 0),
(42, '2019-2350', 1, 'RHYME ROSE', 'PANES', 'L', 12, 'GAS', 'A', '9aeb96c952c2eb9c7cc014a4c311e815', '2022-06-24 14:34:51', '', 0),
(43, '2019-23123', 1, 'paul', 'sebando', 're', 10, '', 'P', 'c2e558bff75d2e0e72132c5c040961c2', '2022-06-24 17:34:53', '', 0),
(44, '2019-232322', 1, 'maria', 'labo', 'purr', 8, '', 'P', '091633e63d1025fa6e5d6dd56a192562', '2022-06-24 17:36:07', '', 3),
(45, '2018-2022', 1, 'rechelyns', 'dasan', 'umiyak', 11, 'HUMMS', 'A', '2a60322b71ee8fba411af6b503556416', '2022-06-28 20:11:18', '827ccb0eea8a706c4c34a16891f84e7b', 3),
(57, '2024-00001', 1, 'ile nathaniel', 'floresz', 'norcos', 7, '', '', '5846fda97f4fbbab71dd482eede3cd48', '2024-12-04 02:52:57', '827ccb0eea8a706c4c34a16891f84e7b', 1),
(53, '3123', 1, 'John Rey', 'Decosta', 'Nalla', 7, '', 'C', 'd3315cab47b369c41f6b53aa25ad5959', '2024-11-23 23:18:28', '7f11bec9c11bc13f38ec6a28f61e87c4', 1),
(54, '2024-12001', 1, 'ile natz', 'flores', 'norcos', 11, 'TVL-ICT', 'A', 'ec95ff2b2334270b8b35c3d85240b6b4', '2024-12-03 11:44:11', 'c260d659425ed05499f151521cc20d79', 1),
(55, '2020-03726', 1, 'Ile trixie ann', 'Flores', 'Norcos', 12, 'STEM', 'A', 'fa21644b1b13d02e88426737a10fa494', '2024-12-03 12:37:07', '', 3),
(56, '2020-03727', 1, 'Ile trixie ann', 'Flores', 'Norcos', 12, 'TVL-ICT', 'A', '218f337066df1628e5a574130f637e3e', '2024-12-03 12:38:51', '15a34bb0527613394d8846a38ae1e71c', 1),
(58, '2024-00002', 1, 'Natz', 'Norcos', ' ', 12, 'HUMMS', 'A', '230490711e7f7f234c0e3d02f5a9d9cd', '2024-12-04 03:12:12', '827ccb0eea8a706c4c34a16891f84e7b', 1),
(59, '2024-11001', 1, 'natz', 'nocos', 'natch', 12, 'STEM', 'R', '41f15a0c5658007b40a28b2a06725ebd', '2024-12-04 07:11:21', 'e10adc3949ba59abbe56e057f20f883e', 1),
(60, '1234', 1, 'Stephen', 'lumanta', 'choy', 9, '', 'F', '3230524d058fb36853affa1512b43973', '2024-12-04 09:05:10', '81dc9bdb52d04dc20036dbd8313ed055', 1),
(61, '2024-10001', 1, 'ile nathaniel', 'flores', 'norcos', 12, 'TVL-ICT', 'A', '15eca451db4d002def5777765aba931b', '2024-12-07 03:17:01', '', 0),
(62, '2024-0001', 8, 'ile nathaniel', 'flores', 'norcos', 7, '', 'A', '595db11b6bbee81c53680373d06ebef5', '2024-12-07 03:20:17', '827ccb0eea8a706c4c34a16891f84e7b', 1),
(63, '2024-0002', 8, 'Nathaniel', 'flores', 'ile', 7, '', 'A', '9d3229e9f2005b3828d7c85660e922f8', '2024-12-07 03:20:51', '827ccb0eea8a706c4c34a16891f84e7b', 1),
(64, '2024-0003', 8, 'john', 'doe', 'will', 7, '', 'A', '7436bd3784632ea2693bb86800a41e63', '2024-12-07 03:21:21', '827ccb0eea8a706c4c34a16891f84e7b', 1),
(65, '2024-0004', 8, 'jon', 'smith', 'bon', 7, '', 'A', 'ae82dee7a666cc34a7d689f1620d311f', '2024-12-07 03:21:47', '827ccb0eea8a706c4c34a16891f84e7b', 1),
(66, '2024-0005', 8, 'jan', 'bon', 'jovi', 7, '', 'A', 'f61f5571679a97da8b81310a714a95a1', '2024-12-07 03:22:13', '827ccb0eea8a706c4c34a16891f84e7b', 1),
(67, '2024-0006', 8, 'ayl natanyel', 'flores', ' ', 7, '', 'B', 'abe8516074d80b3388add767692d8d27', '2024-12-07 03:22:50', '827ccb0eea8a706c4c34a16891f84e7b', 1),
(68, '2024-0007', 8, 'adele', 'easy', 'breezy', 7, '', 'B', 'ceb1bc3b5d1f20a84f32c6fe3f3cdbda', '2024-12-07 03:23:22', '827ccb0eea8a706c4c34a16891f84e7b', 1),
(70, '2024-0008', 8, 'ksi', 'obladio', 'kanor', 7, '', 'B', '94e1c6427a0de9c150047bdf463cb409', '2024-12-07 03:24:25', '827ccb0eea8a706c4c34a16891f84e7b', 1),
(71, '2024-0009', 8, 'rexar', 'bastion', 'will', 7, '', 'B', '3f696a231d92f004f865772f8859f5ba', '2024-12-07 03:25:00', '827ccb0eea8a706c4c34a16891f84e7b', 1),
(72, '2024-0010', 8, 'rowen', 'rowell', ' ', 7, '', 'B', '2f89dcd6a4e981e39f8325559f6ea3fd', '2024-12-07 03:25:31', '', 1),
(73, '2024-0011', 8, 'bryan', 'carlos', 'noka', 7, '', 'C', 'a4d1e6ebb9cf3e550f570f69a0e747ba', '2024-12-07 03:26:09', '827ccb0eea8a706c4c34a16891f84e7b', 1),
(74, '2024-0012', 8, 'john carl', 'obladon', 'octavio', 7, '', 'C', 'fd4c09719bfa0961436dbbfeb4968913', '2024-12-07 03:26:46', '', 1),
(75, '2024-0013', 8, 'wena', 'welkinson', 'chai', 7, '', 'C', 'de7b5218fdc61ee2a066dc7731a8ae09', '2024-12-07 03:27:39', '827ccb0eea8a706c4c34a16891f84e7b', 1),
(76, '2024-0014', 8, 'cardel', 'shrapnel', 'carry', 7, '', 'C', 'b2bc8c4689551e97fb8609f9a2b5f441', '2024-12-07 03:28:10', '', 1),
(77, '2024-0015', 8, 'naga', 'siren', 'cardle', 7, '', 'C', 'a14916af0c2980f106e13c368218a518', '2024-12-07 03:28:43', '', 1),
(78, '2024-0016', 8, 'rodin', 'sunega', 'gahi', 7, '', 'D', '872286d577316a9328407db63c1cb070', '2024-12-07 03:29:12', '', 1),
(79, '2024-0017', 8, 'julz', 'saludo', ' ', 7, '', 'D', '55a503972bbfe321acdad034db6b5fd4', '2024-12-07 03:29:40', '', 1),
(84, '2024-0022', 8, 'Abdulsalam', 'Kusin', 'Omar', 12, 'TVL-ICT', 'A', '5707021e48a6cb73b4376d4ef94206ef', '2024-12-07 05:54:09', 'e10adc3949ba59abbe56e057f20f883e', 1),
(80, '2024-0018', 8, 'adexiar', 'klaus', ' ', 7, '', 'D', '88994c685a8eae56a5886c4db4033c83', '2024-12-07 03:39:32', '', 1),
(81, '2024-0019', 8, 'charlotte', 'sabrina', 'bear', 7, '', 'D', 'd0bdf06e40624015ed37f193016efdec', '2024-12-07 03:40:00', '827ccb0eea8a706c4c34a16891f84e7b', 1),
(82, '2024-0020', 8, 'ile nathaniel', 'gnor', 'choi', 7, '', 'D', '84add95d719c712f64a61e20c1b77ea5', '2024-12-07 03:41:11', '827ccb0eea8a706c4c34a16891f84e7b', 1),
(83, '2024-0021', 8, 'hernan Jr.', 'trillano', 'Emblar', 7, '', 'A', '7b04c50ce4c55c99b9ed90d8ed1da8f8', '2024-12-07 04:28:31', '202cb962ac59075b964b07152d234b70', 1),
(85, '2015380', 8, 'vince Marc', 'Sabado', 'Bermudez', 12, 'ABM', 'A', 'a7b65ce79d86bcd1326a1242eee350c2', '2024-12-07 08:34:03', 'c7d383d3644c5c0f1ab60399a00ef665', 1),
(86, '22', 8, 'koby', 'bryant', 'xx', 7, 'undefined', '', '8e10f218b822607e9e16cadd798c4795', '2024-12-08 14:35:59', 'b6d767d2f8ed5d21a44b0e5886680cb9', 1),
(87, '33', 8, 'asdas', 'dasdas', 'dasdas', 7, 'undefined', '', 'dd8cd65507b30294a38508d3dd29aca0', '2024-12-08 14:38:00', '182be0c5cdcd5072bb1864cdee4d3d6e', 1),
(88, '44', 8, 'dfdsf', 'reynold', 'sdsd', 7, 'undefined', '', '4c6fb35b2b08359ad4da886ebe808481', '2024-12-08 15:13:34', 'f7177163c833dff4b38fc8d2872f1ec6', 1),
(89, '66', 8, 'rex', 'rex', 'rex', 7, 'undefined', '', '9c9cb5ab258c8a2a2b723f8b07b7393f', '2024-12-08 15:16:13', '3295c76acbf4caaed33c36b1b5fc2cb1', 1),
(90, '77', 8, 'rexx', 'rexx', 'rexx', 7, 'undefined', '', 'b973983fa43e5d564f8c620a07825fa2', '2024-12-08 15:19:00', '28dd2c7955ce926456240b2ff0100bde', 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
