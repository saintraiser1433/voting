-- Add is_finished to election_title (1 = closed, 0 = open). Run once in phpMyAdmin or: mysql -u root -p votes < add_is_finished_to_election_title.sql
-- If you get "Duplicate column", the column already exists; skip or comment out the ALTER.

ALTER TABLE `election_title`
  ADD COLUMN `is_finished` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '0=open, 1=closed';

UPDATE `election_title` SET `is_finished` = 0 WHERE `is_finished` IS NULL;
