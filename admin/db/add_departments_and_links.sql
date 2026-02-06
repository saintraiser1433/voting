-- Departments and department-level voting support

-- 1) Departments table
CREATE TABLE IF NOT EXISTS `departments` (
  `department_id` INT NOT NULL AUTO_INCREMENT,
  `department_code` VARCHAR(20) NOT NULL,
  `department_name` VARCHAR(100) NOT NULL,
  `acad_id` INT NOT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1-active, 0-inactive',
  `date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`department_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 2) Link courses to departments
-- NOTE: MySQL 5.7 does not support IF NOT EXISTS for ADD COLUMN,
-- so this statement should be run only once or adjusted manually if needed.
ALTER TABLE `courses`
  ADD COLUMN `department_id` INT NULL AFTER `acad_id`;

-- 3) Department-level votes table
CREATE TABLE IF NOT EXISTS `department_vote` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `voter_id` INT NOT NULL,
  `candidate_id` INT NOT NULL,
  `acad_id` INT NOT NULL,
  `department_id` INT NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 4) Extend candidate table for multi-purpose elections
--    election_type: 'general' (SSC) or 'department'
ALTER TABLE `candidate`
  ADD COLUMN `election_type` VARCHAR(20) NOT NULL DEFAULT 'general' AFTER `platform`,
  ADD COLUMN `department_id` INT NULL AFTER `election_type`;

-- 5) Extend position table for multi-purpose elections
--    election_type: 'general' (SSC) or 'department'
ALTER TABLE `position`
  ADD COLUMN `election_type` VARCHAR(20) NOT NULL DEFAULT 'general' AFTER `priority`;

