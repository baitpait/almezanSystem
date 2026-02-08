-- Add Dry Auto-Ref columns to refractive_profiles (Sphere, Cylinder, Axis per eye - no Vision)
-- Run once. Safe to re-run only if columns do not exist (will error if duplicate).

ALTER TABLE `refractive_profiles`
  ADD COLUMN `dry_auto_ref_od_sphere`   VARCHAR(255) NULL DEFAULT NULL AFTER `current_eyeglasses_os_vision`,
  ADD COLUMN `dry_auto_ref_od_cylinder` VARCHAR(255) NULL DEFAULT NULL AFTER `dry_auto_ref_od_sphere`,
  ADD COLUMN `dry_auto_ref_od_axis`    VARCHAR(255) NULL DEFAULT NULL AFTER `dry_auto_ref_od_cylinder`,
  ADD COLUMN `dry_auto_ref_os_sphere`  VARCHAR(255) NULL DEFAULT NULL AFTER `dry_auto_ref_od_axis`,
  ADD COLUMN `dry_auto_ref_os_cylinder` VARCHAR(255) NULL DEFAULT NULL AFTER `dry_auto_ref_os_sphere`,
  ADD COLUMN `dry_auto_ref_os_axis`    VARCHAR(255) NULL DEFAULT NULL AFTER `dry_auto_ref_os_cylinder`;
