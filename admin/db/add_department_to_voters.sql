-- Add department support to shared voters table
-- Run once in phpMyAdmin (database: votes) or via mysql CLI:
--   mysql -u root -p votes < add_department_to_voters.sql

ALTER TABLE `voters`
  ADD COLUMN `department_id` INT NULL AFTER `section`;

