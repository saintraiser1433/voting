-- Fully separate department voting tables (dual-mode architecture).
-- Requires: departments table, acad_tbl. Creates department_vote if not present.

-- 0) Department votes (if not already created by add_departments_and_links.sql)
CREATE TABLE IF NOT EXISTS `department_vote` (
  `id`           INT NOT NULL AUTO_INCREMENT,
  `voter_id`     INT NOT NULL,
  `candidate_id` INT NOT NULL,
  `acad_id`      INT NOT NULL,
  `department_id` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 1) Department voters (separate from general voters)
CREATE TABLE IF NOT EXISTS `dept_voters` (
  `dv_id`         INT NOT NULL AUTO_INCREMENT,
  `stud_id`       VARCHAR(100) NOT NULL,
  `acad_id`       INT NOT NULL,
  `fname`         VARCHAR(100) NOT NULL,
  `lname`         VARCHAR(100) NOT NULL,
  `mname`         VARCHAR(100) NOT NULL DEFAULT '',
  `year_level`    INT NOT NULL DEFAULT 1,
  `strand`        VARCHAR(100) NOT NULL DEFAULT '',
  `section`       VARCHAR(100) NOT NULL DEFAULT '',
  `department_id` INT NOT NULL,
  `password`      VARCHAR(255) NULL,
  `is_verified`   INT NOT NULL DEFAULT 0,
  `date_issued`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`dv_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 2) Department positions (separate from general position)
CREATE TABLE IF NOT EXISTS `dept_position` (
  `dp_id`       INT NOT NULL AUTO_INCREMENT,
  `description` VARCHAR(100) NOT NULL,
  `max_vote`    INT NOT NULL DEFAULT 1,
  `acad_id`     INT NOT NULL,
  `priority`    INT NOT NULL DEFAULT 1,
  PRIMARY KEY (`dp_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 3) Department candidates (separate from general candidate; no partylist)
-- pos_id stores dp_id from dept_position
CREATE TABLE IF NOT EXISTS `dept_candidate` (
  `dc_id`         INT NOT NULL AUTO_INCREMENT,
  `acad_id`       INT NOT NULL,
  `stud_id`       VARCHAR(100) NOT NULL,
  `pos_id`        INT NOT NULL,
  `department_id` INT NOT NULL,
  `img`           VARCHAR(100) NOT NULL DEFAULT 'libraries/img/logo.png',
  `platform`      TEXT NULL,
  PRIMARY KEY (`dc_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
