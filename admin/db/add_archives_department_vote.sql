CREATE TABLE IF NOT EXISTS `archives_department_vote` (
  `id`           INT NOT NULL AUTO_INCREMENT,
  `voter_id`     INT NOT NULL,
  `candidate_id` INT NOT NULL,
  `acad_id`      INT NOT NULL,
  `department_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_voter_id` (`voter_id`),
  KEY `idx_acad_id` (`acad_id`),
  KEY `idx_department_id` (`department_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

