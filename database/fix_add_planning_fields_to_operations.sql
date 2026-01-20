-- Add Planning fields to operations table
-- These fields store the planning refraction values (Sphere, Cylinder, Axis) for each eye
-- They are separate from manifest_refraction values and are used for operation planning

ALTER TABLE `operations`
  ADD COLUMN `planning_sphere_od` VARCHAR(50) NULL DEFAULT NULL AFTER `incompatible_notes_os`,
  ADD COLUMN `planning_cylinder_od` VARCHAR(50) NULL DEFAULT NULL AFTER `planning_sphere_od`,
  ADD COLUMN `planning_axis_od` VARCHAR(50) NULL DEFAULT NULL AFTER `planning_cylinder_od`,
  ADD COLUMN `planning_sphere_os` VARCHAR(50) NULL DEFAULT NULL AFTER `planning_axis_od`,
  ADD COLUMN `planning_cylinder_os` VARCHAR(50) NULL DEFAULT NULL AFTER `planning_sphere_os`,
  ADD COLUMN `planning_axis_os` VARCHAR(50) NULL DEFAULT NULL AFTER `planning_cylinder_os`;
