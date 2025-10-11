-- Add courses table for dynamic course management
CREATE TABLE IF NOT EXISTS `courses` (
  `course_id` int NOT NULL AUTO_INCREMENT,
  `course_code` varchar(20) NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `acad_id` int NOT NULL,
  `status` int NOT NULL DEFAULT '1' COMMENT '1-active, 0-inactive',
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`course_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Insert default courses for academic year 8 (2024)
INSERT INTO `courses` (`course_code`, `course_name`, `acad_id`, `status`) VALUES
('BSIT', 'Bachelor of Science in Information Technology', 8, 1),
('BSCS', 'Bachelor of Science in Computer Science', 8, 1),
('BSIS', 'Bachelor of Science in Information Systems', 8, 1),
('BSCE', 'Bachelor of Science in Computer Engineering', 8, 1),
('BSEE', 'Bachelor of Science in Electrical Engineering', 8, 1),
('BSME', 'Bachelor of Science in Mechanical Engineering', 8, 1),
('BSA', 'Bachelor of Science in Accountancy', 8, 1),
('BSBA', 'Bachelor of Science in Business Administration', 8, 1),
('BSE', 'Bachelor of Science in Education', 8, 1),
('BSED', 'Bachelor of Science in Elementary Education', 8, 1);


