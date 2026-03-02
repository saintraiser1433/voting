-- Add election_type to election_title so General and Department have separate settings per responsibility.
-- Run once in phpMyAdmin or: mysql -u root -p votes < add_election_type_to_election_title.sql
-- If you get "Duplicate column", the column already exists; skip or comment out the ALTER.

ALTER TABLE `election_title`
  ADD COLUMN `election_type` VARCHAR(20) NOT NULL DEFAULT 'general';

-- Backfill existing rows (in case default did not apply)
UPDATE `election_title` SET `election_type` = 'general' WHERE `election_type` = '' OR `election_type` IS NULL;
