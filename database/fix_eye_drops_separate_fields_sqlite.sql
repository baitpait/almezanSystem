-- Add separate Eye Drops fields for OD and OS (SQLite)
-- This script adds eye_drops fields separated by eye (OD & OS) for SQLite database

ALTER TABLE `operation_notes`
  ADD COLUMN `eye_drops_vigamox_od` INTEGER NOT NULL DEFAULT 0;

ALTER TABLE `operation_notes`
  ADD COLUMN `eye_drops_vigamox_os` INTEGER NOT NULL DEFAULT 0;

ALTER TABLE `operation_notes`
  ADD COLUMN `eye_drops_pred_forte_od` INTEGER NOT NULL DEFAULT 0;

ALTER TABLE `operation_notes`
  ADD COLUMN `eye_drops_pred_forte_os` INTEGER NOT NULL DEFAULT 0;

ALTER TABLE `operation_notes`
  ADD COLUMN `eye_drops_other_od` INTEGER NOT NULL DEFAULT 0;

ALTER TABLE `operation_notes`
  ADD COLUMN `eye_drops_other_os` INTEGER NOT NULL DEFAULT 0;

ALTER TABLE `operation_notes`
  ADD COLUMN `eye_drops_other_details_od` TEXT;

ALTER TABLE `operation_notes`
  ADD COLUMN `eye_drops_other_details_os` TEXT;
