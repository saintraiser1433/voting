-- Add start and end date/time for voting window. Run once.
-- If a column already exists, that ALTER will error; run the other(s) as needed.

ALTER TABLE `election_title` ADD COLUMN `date_start` DATETIME NULL DEFAULT NULL COMMENT 'When voting opens';
ALTER TABLE `election_title` ADD COLUMN `date_end` DATETIME NULL DEFAULT NULL COMMENT 'When voting closes';
