-- PHPDraft Migration Script
-- Only use if you have a pre-existing PHPDraft database (from version before version 1.2.0)
-- *Schema change for draft table
-- *Value updates associated with new team abbreviations
--
-- No deprecated columns result from this migration.

USE phpdraft;

--
-- Add the new column, but for now allow NULL values:
--
ALTER TABLE `draft`
ADD COLUMN `draft_create_time` datetime NULL COMMENT 'The datetime the draft was created, can be used for sorting purposes.';

-- 
-- Then go through and make NOW default value for all existing drafts
--

UPDATE `draft`
SET `draft_create_time` = NOW()
WHERE `draft_create_time` IS NULL;

--
-- And finally make the column not nullable again
--
ALTER TABLE `draft`
MODIFY `draft_create_time` datetime NOT NULL COMMENT 'The datetime the draft was created, can be used for sorting purposes.';

--
-- Switch all NBA picks from New Jersey Nets to Brooklyn Nets
--
UPDATE `players`
SET `team` = 'BKN'
WHERE `team` = 'NJN';

--
-- Add new column to draft table for updated draft board
--
ALTER TABLE `draft`
ADD COLUMN `draft_counter` int(11) NOT NULL default '0' COMMENT 'The counter tracking the sequence of events to help keep the draft board fresh';

--
-- Add new column to player table for updated draft board
--
ALTER TABLE `players`
ADD COLUMN `player_counter` int(11) default NULL COMMENT 'The draft counter value in which this pick was edited at';