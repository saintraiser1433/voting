-- Create departments table (required for department voting).
-- Run in phpMyAdmin or: mysql -u root -p votes < create_departments_table.sql

CREATE TABLE IF NOT EXISTS `departments` (
  `department_id` int NOT NULL AUTO_INCREMENT,
  `department_name` varchar(100) NOT NULL,
  `status` int NOT NULL DEFAULT 1 COMMENT '1=active, 0=archived',
  PRIMARY KEY (`department_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
