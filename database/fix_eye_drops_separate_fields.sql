-- Add separate Eye Drops fields for OD and OS
-- This script adds eye_drops fields separated by eye (OD & OS)
-- Note: MySQL doesn't support IF NOT EXISTS with ADD COLUMN, so run this only once
-- If you get "Duplicate column name" errors, the columns already exist and you can ignore them

ALTER TABLE `operation_notes`
  ADD COLUMN `eye_drops_vigamox_od` tinyint(1) NOT NULL DEFAULT '0' AFTER `eye_drops_other_details`,
  ADD COLUMN `eye_drops_vigamox_os` tinyint(1) NOT NULL DEFAULT '0' AFTER `eye_drops_vigamox_od`,
  ADD COLUMN `eye_drops_pred_forte_od` tinyint(1) NOT NULL DEFAULT '0' AFTER `eye_drops_vigamox_os`,
  ADD COLUMN `eye_drops_pred_forte_os` tinyint(1) NOT NULL DEFAULT '0' AFTER `eye_drops_pred_forte_od`,
  ADD COLUMN `eye_drops_other_od` tinyint(1) NOT NULL DEFAULT '0' AFTER `eye_drops_pred_forte_os`,
  ADD COLUMN `eye_drops_other_os` tinyint(1) NOT NULL DEFAULT '0' AFTER `eye_drops_other_od`,
  ADD COLUMN `eye_drops_other_details_od` text COLLATE utf8mb4_general_ci AFTER `eye_drops_other_os`,
  ADD COLUMN `eye_drops_other_details_os` text COLLATE utf8mb4_general_ci AFTER `eye_drops_other_details_od`;
