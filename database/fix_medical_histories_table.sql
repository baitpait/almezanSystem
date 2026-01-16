-- Fix medical_histories table - Add missing columns
-- إصلاح جدول medical_histories - إضافة الأعمدة الناقصة

ALTER TABLE `medical_histories`
  ADD COLUMN `diabetes` tinyint(1) NOT NULL DEFAULT '0' AFTER `operation_id`,
  ADD COLUMN `chronic_disease` tinyint(1) NOT NULL DEFAULT '0' AFTER `diabetes`,
  ADD COLUMN `chronic_disease_details` text COLLATE utf8mb4_general_ci AFTER `chronic_disease`,
  ADD COLUMN `herpes_keratitis` tinyint(1) NOT NULL DEFAULT '0' AFTER `chronic_disease_details`,
  ADD COLUMN `glaucoma` tinyint(1) NOT NULL DEFAULT '0' AFTER `herpes_keratitis`,
  ADD COLUMN `family_history_keratoconus` tinyint(1) NOT NULL DEFAULT '0' AFTER `glaucoma`,
  ADD COLUMN `eye_rubber` tinyint(1) NOT NULL DEFAULT '0' AFTER `family_history_keratoconus`,
  ADD COLUMN `pregnancy` tinyint(1) NOT NULL DEFAULT '0' AFTER `eye_rubber`,
  ADD COLUMN `ocular_surgery` tinyint(1) NOT NULL DEFAULT '0' AFTER `pregnancy`,
  ADD COLUMN `ocular_surgery_details` text COLLATE utf8mb4_general_ci AFTER `ocular_surgery`,
  ADD COLUMN `family_history_ocular_disease` text COLLATE utf8mb4_general_ci AFTER `ocular_surgery_details`,
  ADD COLUMN `family_history_ocular_disease_yes` tinyint(1) NOT NULL DEFAULT '0' AFTER `family_history_ocular_disease`,
  ADD COLUMN `current_medications` text COLLATE utf8mb4_general_ci AFTER `family_history_ocular_disease_yes`,
  ADD COLUMN `current_medications_yes` tinyint(1) NOT NULL DEFAULT '0' AFTER `current_medications`,
  ADD COLUMN `allergies` text COLLATE utf8mb4_general_ci AFTER `current_medications_yes`,
  ADD COLUMN `glare_halos_squint` tinyint(1) NOT NULL DEFAULT '0' AFTER `allergies`,
  ADD COLUMN `refraction_stable_1year` tinyint(1) NOT NULL DEFAULT '1' AFTER `glare_halos_squint`,
  ADD COLUMN `contact_lens_use` tinyint(1) NOT NULL DEFAULT '0' AFTER `refraction_stable_1year`,
  ADD COLUMN `past_medical_history` text COLLATE utf8mb4_general_ci AFTER `contact_lens_use`,
  ADD COLUMN `past_ophthalmic_history` text COLLATE utf8mb4_general_ci AFTER `past_medical_history`,
  ADD COLUMN `chief_complaint` text COLLATE utf8mb4_general_ci AFTER `past_ophthalmic_history`,
  ADD COLUMN `notes` text COLLATE utf8mb4_general_ci AFTER `chief_complaint`;
