-- ============================================================
-- قاعدة بيانات كاملة - Fresh Database Setup
-- Full Database Schema + Data (DROP + CREATE + INSERT)
-- ============================================================
-- 
-- الاستخدام: استورد هذا الملف في قاعدة البيانات على السيرفر
-- Usage: Import this file into the database on the server
-- 
-- mysql -u [username] -p [database_name] < full_database_fresh.sql
-- 
-- ⚠️ تحذير: هذا الملف سيمسح جميع البيانات القديمة!
-- ⚠️ Warning: This file will DELETE all existing data!
-- 
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;
SET UNIQUE_CHECKS = 0;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
SET TIME_ZONE = '+00:00';

-- ============================================================
-- 1. مسح جميع الجداول القديمة
-- ============================================================

DROP TABLE IF EXISTS `appointments`;
DROP TABLE IF EXISTS `branches`;
DROP TABLE IF EXISTS `cache`;
DROP TABLE IF EXISTS `cache_locks`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `doctors`;
DROP TABLE IF EXISTS `ectasia_risk_assessments`;
DROP TABLE IF EXISTS `eye_examinations`;
DROP TABLE IF EXISTS `failed_jobs`;
DROP TABLE IF EXISTS `invoice_services`;
DROP TABLE IF EXISTS `invoices`;
DROP TABLE IF EXISTS `job_batches`;
DROP TABLE IF EXISTS `jobs`;
DROP TABLE IF EXISTS `medical_histories`;
DROP TABLE IF EXISTS `migrations`;
DROP TABLE IF EXISTS `model_has_permissions`;
DROP TABLE IF EXISTS `model_has_roles`;
DROP TABLE IF EXISTS `operation_approvals`;
DROP TABLE IF EXISTS `operation_details`;
DROP TABLE IF EXISTS `operation_files`;
DROP TABLE IF EXISTS `operation_notes`;
DROP TABLE IF EXISTS `operations`;
DROP TABLE IF EXISTS `password_reset_tokens`;
DROP TABLE IF EXISTS `patients`;
DROP TABLE IF EXISTS `permissions`;
DROP TABLE IF EXISTS `procedures`;
DROP TABLE IF EXISTS `refractive_profiles`;
DROP TABLE IF EXISTS `role_has_permissions`;
DROP TABLE IF EXISTS `roles`;
DROP TABLE IF EXISTS `services`;
DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `users`;

-- ============================================================
-- 2. إنشاء جميع الجداول (Schema)
-- ============================================================

-- جدول المستخدمين
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'User phone number',
  `photo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'User profile photo',
  `notes` text COLLATE utf8mb4_general_ci COMMENT 'General notes about the user',
  `last_login_at` timestamp NULL DEFAULT NULL COMMENT 'Last login timestamp',
  `previous_last_login_at` timestamp NULL DEFAULT NULL COMMENT 'Previous last login timestamp (before current login)',
  `role` enum('admin','doctor','secretary') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'secretary',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_branch_id_foreign` (`branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- جدول الفروع
CREATE TABLE `branches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_general_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `branches_is_active_index` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- إضافة Foreign Key للمستخدمين
ALTER TABLE `users` ADD CONSTRAINT `users_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL;

-- جدول الأدوار (Spatie Permission)
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- جدول الصلاحيات
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- جدول ربط الأدوار بالصلاحيات
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- جدول ربط المستخدمين بالأدوار
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- جدول ربط المستخدمين بالصلاحيات
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- جدول الأطباء
CREATE TABLE `doctors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `branch_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `doctors_user_id_unique` (`user_id`),
  KEY `doctors_branch_id_foreign` (`branch_id`),
  CONSTRAINT `doctors_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `doctors_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- جدول المرضى
CREATE TABLE `patients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `id_number` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone_secondary` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `occupation` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `patients_id_number_unique` (`id_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- جدول الفئات
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `price` decimal(10,2) DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- جدول الخدمات
CREATE TABLE `services` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `base_price` decimal(10,2) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `services_name_unique` (`name`),
  KEY `services_service_type_is_active_index` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- جدول الإجراءات
CREATE TABLE `procedures` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `default_duration` int NOT NULL DEFAULT '30' COMMENT 'Default duration in minutes',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- جدول المواعيد
CREATE TABLE `appointments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint unsigned NOT NULL,
  `doctor_id` bigint unsigned NOT NULL,
  `procedure_id` bigint unsigned DEFAULT NULL,
  `operation_id` bigint unsigned DEFAULT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `duration` int NOT NULL DEFAULT '30' COMMENT 'Duration in minutes',
  `notes` text COLLATE utf8mb4_general_ci,
  `notify_patient_sms` tinyint(1) NOT NULL DEFAULT '0',
  `notify_doctor_sms` tinyint(1) NOT NULL DEFAULT '0',
  `notify_doctor_email` tinyint(1) NOT NULL DEFAULT '0',
  `follow_up` tinyint(1) NOT NULL DEFAULT '0',
  `status` enum('scheduled','completed','cancelled','rescheduled','no_show') COLLATE utf8mb4_general_ci DEFAULT 'scheduled',
  `visit_stage` enum('scheduled','waiting','in_consultation','completed','cancelled') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `visit_type` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Assessment, Operation, Follow up, New visit',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `branch_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `appointments_procedure_id_foreign` (`procedure_id`),
  KEY `appointments_appointment_date_appointment_time_index` (`appointment_date`,`appointment_time`),
  KEY `appointments_patient_id_index` (`patient_id`),
  KEY `appointments_doctor_id_index` (`doctor_id`),
  KEY `appointments_created_by_index` (`created_by`),
  KEY `appointments_branch_id_index` (`branch_id`),
  KEY `appointments_operation_id_index` (`operation_id`),
  CONSTRAINT `appointments_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `appointments_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `appointments_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  CONSTRAINT `appointments_operation_id_foreign` FOREIGN KEY (`operation_id`) REFERENCES `operations` (`id`) ON DELETE SET NULL,
  CONSTRAINT `appointments_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `appointments_procedure_id_foreign` FOREIGN KEY (`procedure_id`) REFERENCES `procedures` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- جدول العمليات
CREATE TABLE `operations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint unsigned NOT NULL,
  `doctor_id` bigint unsigned NOT NULL,
  `branch_id` bigint unsigned NOT NULL,
  `appointment_id` bigint unsigned DEFAULT NULL,
  `created_by` bigint unsigned NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('scheduled','in_progress','completed','cancelled','postponed') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'scheduled',
  `pre_op_assessment_date` date DEFAULT NULL,
  `post_op_notes` text COLLATE utf8mb4_general_ci,
  `decision` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `decision_od` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Decision for Right Eye (OD)',
  `decision_os` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Decision for Left Eye (OS)',
  `recommendation_notes` text COLLATE utf8mb4_general_ci,
  `diagnosis` text COLLATE utf8mb4_general_ci,
  `plan` text COLLATE utf8mb4_general_ci,
  `notes` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `operations_created_by_foreign` (`created_by`),
  KEY `operations_patient_id_index` (`patient_id`),
  KEY `operations_doctor_id_index` (`doctor_id`),
  KEY `operations_branch_id_index` (`branch_id`),
  KEY `operations_status_index` (`status`),
  KEY `operations_start_date_index` (`start_date`),
  KEY `operations_appointment_id_foreign` (`appointment_id`),
  CONSTRAINT `operations_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `operations_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `operations_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `operations_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  CONSTRAINT `operations_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- جدول الفواتير
CREATE TABLE `invoices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `patient_id` bigint unsigned NOT NULL,
  `service_id` bigint unsigned DEFAULT NULL,
  `appointment_id` bigint unsigned DEFAULT NULL,
  `operation_id` bigint unsigned DEFAULT NULL,
  `doctor_id` bigint unsigned DEFAULT NULL,
  `branch_id` bigint unsigned DEFAULT NULL,
  `invoice_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tax` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `paid_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `remaining_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` enum('draft','pending','partial','paid','cancelled') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `payment_method` enum('cash','card','bank_transfer','cheque','other') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_general_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`),
  KEY `invoices_appointment_id_foreign` (`appointment_id`),
  KEY `invoices_doctor_id_foreign` (`doctor_id`),
  KEY `invoices_branch_id_foreign` (`branch_id`),
  KEY `invoices_created_by_foreign` (`created_by`),
  KEY `invoices_invoice_number_index` (`invoice_number`),
  KEY `invoices_patient_id_index` (`patient_id`),
  KEY `invoices_invoice_date_index` (`invoice_date`),
  KEY `invoices_status_index` (`status`),
  KEY `invoices_operation_id_index` (`operation_id`),
  KEY `invoices_service_id_index` (`service_id`),
  CONSTRAINT `invoices_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `invoices_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `invoices_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `invoices_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE SET NULL,
  CONSTRAINT `invoices_operation_id_foreign` FOREIGN KEY (`operation_id`) REFERENCES `operations` (`id`) ON DELETE SET NULL,
  CONSTRAINT `invoices_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoices_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- جدول تفاصيل الفواتير
CREATE TABLE `invoice_services` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint unsigned NOT NULL,
  `service_id` bigint unsigned NOT NULL,
  `doctor_id` bigint unsigned DEFAULT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `notes` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_services_service_id_foreign` (`service_id`),
  KEY `invoice_services_invoice_id_service_id_index` (`invoice_id`,`service_id`),
  KEY `invoice_services_doctor_id_index` (`doctor_id`),
  CONSTRAINT `invoice_services_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE SET NULL,
  CONSTRAINT `invoice_services_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoice_services_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- جداول إضافية للعمليات
CREATE TABLE `refractive_profiles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `operation_id` bigint unsigned NOT NULL,
  `patient_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `patient_age` int DEFAULT NULL,
  `optometrist` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `eyeglasses_age` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `time_with_current_rx` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `current_eyeglasses_od_sphere` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `current_eyeglasses_od_cylinder` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `current_eyeglasses_od_axis` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `current_eyeglasses_od_vision` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `current_eyeglasses_os_sphere` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `current_eyeglasses_os_cylinder` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `current_eyeglasses_os_axis` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `current_eyeglasses_os_vision` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `contact_lenses` enum('No','Soft','Hard') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `time_without_lenses` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `manifest_refraction_od_udva` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `manifest_refraction_od_sphere` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `manifest_refraction_od_cylinder` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `manifest_refraction_od_axis` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `manifest_refraction_od_bscva` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `manifest_refraction_od_dcnva_40cm` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `manifest_refraction_od_add_j1` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `manifest_refraction_od_rg` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `manifest_refraction_os_udva` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `manifest_refraction_os_sphere` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `manifest_refraction_os_cylinder` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `manifest_refraction_os_axis` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `manifest_refraction_os_bscva` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `manifest_refraction_os_dcnva_40cm` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `manifest_refraction_os_add_j1` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `manifest_refraction_os_rg` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `refraction_after_dilation_od_sphere` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `refraction_after_dilation_od_cylinder` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `refraction_after_dilation_od_axis` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `refraction_after_dilation_od_vision` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `refraction_after_dilation_os_sphere` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `refraction_after_dilation_os_cylinder` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `refraction_after_dilation_os_axis` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `refraction_after_dilation_os_vision` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `refraction_after_dilation_type` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pupil_diameter_od_mesopic` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pupil_diameter_od_scotopic` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pupil_diameter_os_mesopic` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pupil_diameter_os_scotopic` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `dominant_eye` enum('OD','OS') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `simulation_for_monovision` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `refractive_profiles_operation_id_index` (`operation_id`),
  CONSTRAINT `refractive_profiles_operation_id_foreign` FOREIGN KEY (`operation_id`) REFERENCES `operations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `medical_histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `operation_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `medical_histories_operation_id_index` (`operation_id`),
  CONSTRAINT `medical_histories_operation_id_foreign` FOREIGN KEY (`operation_id`) REFERENCES `operations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `eye_examinations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `operation_id` bigint unsigned NOT NULL,
  `examination_type` enum('pre_op','post_op') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pre_op',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `eye_examinations_operation_id_index` (`operation_id`),
  KEY `eye_examinations_examination_type_index` (`examination_type`),
  CONSTRAINT `eye_examinations_operation_id_foreign` FOREIGN KEY (`operation_id`) REFERENCES `operations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `ectasia_risk_assessments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `operation_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ectasia_risk_assessments_operation_id_index` (`operation_id`),
  CONSTRAINT `ectasia_risk_assessments_operation_id_foreign` FOREIGN KEY (`operation_id`) REFERENCES `operations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `operation_details` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `operation_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `operation_details_operation_id_index` (`operation_id`),
  CONSTRAINT `operation_details_operation_id_foreign` FOREIGN KEY (`operation_id`) REFERENCES `operations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `operation_notes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `appointment_id` bigint unsigned NOT NULL,
  `patient_id` bigint unsigned NOT NULL,
  `doctor_id` bigint unsigned NOT NULL,
  `operation_type` enum('PRK','Femto-LASIK','SMILE','PTK') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `operation_type_od` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `operation_type_os` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `same_operation_type_both_eyes` tinyint(1) NOT NULL DEFAULT '0',
  `operation_eye` enum('OD','OS','OU') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'OU',
  `monovision_eye` enum('none','OD','OS') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `eye_drops_vigamox` tinyint(1) NOT NULL DEFAULT '0',
  `eye_drops_pred_forte` tinyint(1) NOT NULL DEFAULT '0',
  `eye_drops_other` tinyint(1) NOT NULL DEFAULT '0',
  `eye_drops_other_details` text COLLATE utf8mb4_general_ci,
  `prk_epithelial_removal` enum('Alcohol','Mechanical','Trans-PRK') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `prk_excimer_profile` enum('Aspheric Front','Topography-guided') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `prk_mmc_0_02_percent` tinyint(1) NOT NULL DEFAULT '0',
  `prk_bandage_contact_lens` tinyint(1) NOT NULL DEFAULT '0',
  `prk_epithelial_removal_od` enum('Alcohol','Mechanical','Trans-PRK') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `prk_epithelial_removal_os` enum('Alcohol','Mechanical','Trans-PRK') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `prk_excimer_profile_od` enum('Aspheric Front','Topography-guided') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `prk_excimer_profile_os` enum('Aspheric Front','Topography-guided') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `prk_mmc_0_02_percent_od` tinyint(1) NOT NULL DEFAULT '0',
  `prk_mmc_0_02_percent_os` tinyint(1) NOT NULL DEFAULT '0',
  `prk_bandage_contact_lens_od` tinyint(1) NOT NULL DEFAULT '0',
  `prk_bandage_contact_lens_os` tinyint(1) NOT NULL DEFAULT '0',
  `prk_target_od` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `prk_target_os` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `femto_flap_done` tinyint(1) DEFAULT NULL,
  `femto_excimer_profile` enum('Aspheric Front','Topography-guided') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `femto_bandage_contact_lens` tinyint(1) NOT NULL DEFAULT '0',
  `femto_flap_done_od` tinyint(1) DEFAULT NULL,
  `femto_flap_done_os` tinyint(1) DEFAULT NULL,
  `femto_excimer_profile_od` enum('Aspheric Front','Topography-guided') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `femto_excimer_profile_os` enum('Aspheric Front','Topography-guided') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `femto_bandage_contact_lens_od` tinyint(1) NOT NULL DEFAULT '0',
  `femto_bandage_contact_lens_os` tinyint(1) NOT NULL DEFAULT '0',
  `femto_target_od` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `femto_target_os` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `smile_complete_lenticule_separation` tinyint(1) DEFAULT NULL,
  `smile_complete_lenticule_extraction` tinyint(1) DEFAULT NULL,
  `smile_complete_lenticule_separation_od` tinyint(1) DEFAULT NULL,
  `smile_complete_lenticule_separation_os` tinyint(1) DEFAULT NULL,
  `smile_complete_lenticule_extraction_od` tinyint(1) DEFAULT NULL,
  `smile_complete_lenticule_extraction_os` tinyint(1) DEFAULT NULL,
  `smile_target_od` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `smile_target_os` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ptk_excimer_profile` enum('Aspheric Front','Topography-guided') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ptk_epithelial_removal` enum('Alcohol','Mechanical','Trans-PTK') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ptk_mmc_0_02_percent` tinyint(1) NOT NULL DEFAULT '0',
  `ptk_bandage_contact_lens` tinyint(1) NOT NULL DEFAULT '0',
  `ptk_epithelial_removal_od` enum('Alcohol','Mechanical','Trans-PTK') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ptk_epithelial_removal_os` enum('Alcohol','Mechanical','Trans-PTK') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ptk_excimer_profile_od` enum('Aspheric Front','Topography-guided') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ptk_excimer_profile_os` enum('Aspheric Front','Topography-guided') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ptk_mmc_0_02_percent_od` tinyint(1) NOT NULL DEFAULT '0',
  `ptk_mmc_0_02_percent_os` tinyint(1) NOT NULL DEFAULT '0',
  `ptk_bandage_contact_lens_od` tinyint(1) NOT NULL DEFAULT '0',
  `ptk_bandage_contact_lens_os` tinyint(1) NOT NULL DEFAULT '0',
  `ptk_target_od` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ptk_target_os` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mmc_0_02_percent_od` tinyint(1) NOT NULL DEFAULT '0',
  `mmc_duration_sec_od` int DEFAULT NULL,
  `mmc_0_02_percent_os` tinyint(1) NOT NULL DEFAULT '0',
  `mmc_duration_sec_os` int DEFAULT NULL,
  `notes` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `operation_notes_appointment_id_index` (`appointment_id`),
  KEY `operation_notes_patient_id_index` (`patient_id`),
  KEY `operation_notes_doctor_id_index` (`doctor_id`),
  KEY `operation_notes_operation_type_index` (`operation_type`),
  CONSTRAINT `operation_notes_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `operation_notes_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  CONSTRAINT `operation_notes_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `operation_files` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `operation_id` bigint unsigned NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `file_type` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mime_type` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `file_size` bigint DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `eye` enum('OD','OS','OU') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'OU',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `operation_files_operation_id_index` (`operation_id`),
  CONSTRAINT `operation_files_operation_id_foreign` FOREIGN KEY (`operation_id`) REFERENCES `operations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- جداول Laravel الأساسية
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_general_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_general_ci,
  `payload` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_general_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `connection` text COLLATE utf8mb4_general_ci NOT NULL,
  `queue` text COLLATE utf8mb4_general_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- 3. إدراج البيانات الأساسية
-- ============================================================

-- إنشاء الفرع الرئيسي
INSERT INTO `branches` (`id`, `name`, `address`, `phone`, `email`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Main Branch', 'رام الله - فلسطين', '0599999999', 'main@almyzan.ps', 1, NOW(), NOW());

-- إنشاء مستخدم Admin
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `branch_id`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'System Administrator', 'admin@gmail.com', '$2y$12$CEeeBPFzGSRHFQrEF6ewhO4NBI6Qf.CrRK0j2zZKKnBD0AmOLvna6', 'admin', 1, 1, NOW(), NOW());
-- كلمة المرور: 100200300

-- إنشاء الأدوار
INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'web', NOW(), NOW()),
(2, 'doctor', 'web', NOW(), NOW()),
(3, 'secretary', 'web', NOW(), NOW());

-- إنشاء الصلاحيات
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'view-patients', 'web', NOW(), NOW()),
(2, 'create-patients', 'web', NOW(), NOW()),
(3, 'edit-patients', 'web', NOW(), NOW()),
(4, 'delete-patients', 'web', NOW(), NOW()),
(5, 'view-appointments', 'web', NOW(), NOW()),
(6, 'create-appointments', 'web', NOW(), NOW()),
(7, 'edit-appointments', 'web', NOW(), NOW()),
(8, 'delete-appointments', 'web', NOW(), NOW()),
(9, 'view-operations', 'web', NOW(), NOW()),
(10, 'create-operations', 'web', NOW(), NOW()),
(11, 'edit-operations', 'web', NOW(), NOW()),
(12, 'delete-operations', 'web', NOW(), NOW()),
(13, 'view-invoices', 'web', NOW(), NOW()),
(14, 'create-invoices', 'web', NOW(), NOW()),
(15, 'edit-invoices', 'web', NOW(), NOW()),
(16, 'delete-invoices', 'web', NOW(), NOW()),
(17, 'view-operation-notes', 'web', NOW(), NOW()),
(18, 'create-operation-notes', 'web', NOW(), NOW()),
(19, 'edit-operation-notes', 'web', NOW(), NOW()),
(20, 'delete-operation-notes', 'web', NOW(), NOW()),
(21, 'manage-users', 'web', NOW(), NOW()),
(22, 'manage-branches', 'web', NOW(), NOW()),
(23, 'manage-settings', 'web', NOW(), NOW()),
(24, 'view-reports', 'web', NOW(), NOW());

-- ربط جميع الصلاحيات بدور Admin
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1), (2, 1), (3, 1), (4, 1), (5, 1), (6, 1), (7, 1), (8, 1),
(9, 1), (10, 1), (11, 1), (12, 1), (13, 1), (14, 1), (15, 1), (16, 1),
(17, 1), (18, 1), (19, 1), (20, 1), (21, 1), (22, 1), (23, 1), (24, 1);

-- ربط الصلاحيات بدور Doctor
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 2), (2, 2), (3, 2), (5, 2), (6, 2), (7, 2), (9, 2), (10, 2), (11, 2), (12, 2),
(13, 2), (17, 2), (18, 2), (19, 2), (20, 2);

-- ربط الصلاحيات بدور Secretary
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 3), (2, 3), (3, 3), (4, 3), (5, 3), (6, 3), (7, 3), (8, 3), (13, 3), (14, 3), (15, 3), (16, 3);

-- تعيين دور Admin للمستخدم
INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1);

-- إنشاء الفئات
INSERT INTO `categories` (`id`, `name`, `price`, `created_at`, `updated_at`) VALUES
(1, 'Doctors', 0.00, NOW(), NOW()),
(2, 'Consultation', 0.00, NOW(), NOW()),
(3, 'Follow-up', 0.00, NOW(), NOW()),
(4, 'Surgery', 0.00, NOW(), NOW()),
(5, 'Emergency', 0.00, NOW(), NOW());

-- إنشاء الخدمات
INSERT INTO `services` (`id`, `name`, `base_price`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'جراحة الساد', 5000.00, 1, NOW(), NOW()),
(2, 'جراحة الليزر', 8000.00, 1, NOW(), NOW()),
(3, 'استشارة طبية', 200.00, 1, NOW(), NOW()),
(4, 'أشعة سينية', 150.00, 1, NOW(), NOW()),
(5, 'تحاليل دم', 100.00, 1, NOW(), NOW()),
(6, 'فحص عيون أساسي', 50.00, 1, NOW(), NOW()),
(7, 'زيارة متابعة', 150.00, 1, NOW(), NOW());

-- ============================================================
-- 4. إدراج البيانات التجريبية
-- ============================================================

-- إنشاء حسابات User للأطباء
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `branch_id`, `phone`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 'Dr. Alaa Al-Talbishi', 'alaa@almyzan.ps', '$2y$12$4UEzGH6LHwW/tMxchR9J3et5u.fErmb0kWGLyh8ygLEzkgW/txxDS', 'doctor', 1, '0591234567', 1, NOW(), NOW()),
(3, 'Dr. Tariq Al-Husseini', 'tariq@almyzan.ps', '$2y$12$4UEzGH6LHwW/tMxchR9J3et5u.fErmb0kWGLyh8ygLEzkgW/txxDS', 'doctor', 1, '0597654321', 1, NOW(), NOW());
-- كلمة المرور: password123

-- تعيين دور Doctor للأطباء
INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(2, 'App\\Models\\User', 2),
(2, 'App\\Models\\User', 3);

-- إنشاء الأطباء
INSERT INTO `doctors` (`id`, `user_id`, `branch_id`, `name`, `phone`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'Dr. Alaa Al-Talbishi', '0591234567', NOW(), NOW()),
(2, 3, 1, 'Dr. Tariq Al-Husseini', '0597654321', NOW(), NOW());

-- إنشاء 10 مرضى بأسماء فلسطينية
INSERT INTO `patients` (`id`, `full_name`, `id_number`, `date_of_birth`, `gender`, `phone`, `city`, `occupation`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'محمد أحمد النابلسي', '2100000001', '1985-03-15', 'male', '0591000001', 'رام الله', 'مهندس', 'مريض جديد - يحتاج تقييم شامل', NOW(), NOW()),
(2, 'فاطمة خليل القدسي', '2100000002', '1990-07-22', 'female', '0591000002', 'نابلس', 'معلمة', NULL, NOW(), NOW()),
(3, 'علي محمود الخليل', '2100000003', '1978-11-08', 'male', '0591000003', 'القدس', 'طبيب', 'مريض جديد - يحتاج تقييم شامل', NOW(), NOW()),
(4, 'سارة يوسف رام الله', '2100000004', '1992-05-30', 'female', '0591000004', 'الخليل', 'ممرضة', NULL, NOW(), NOW()),
(5, 'خالد إبراهيم نابلس', '2100000005', '1988-09-12', 'male', '0591000005', 'بيت لحم', 'محاسب', 'مريض جديد - يحتاج تقييم شامل', NOW(), NOW()),
(6, 'مريم حسن بيت لحم', '2100000006', '1995-01-25', 'female', '0591000006', 'جنين', 'طالبة', NULL, NOW(), NOW()),
(7, 'أحمد سعد غزة', '2100000007', '1982-12-18', 'male', '0591000007', 'طولكرم', 'تاجر', NULL, NOW(), NOW()),
(8, 'ليلى عمر يافا', '2100000008', '1987-06-05', 'female', '0591000008', 'قلقيلية', 'ربة منزل', NULL, NOW(), NOW()),
(9, 'يوسف فهد حيفا', '2100000009', '1993-08-20', 'male', '0591000009', 'سلفيت', 'موظف', NULL, NOW(), NOW()),
(10, 'نورا عبدالرحمن عكا', '2100000010', '1991-04-14', 'female', '0591000010', 'طوباس', 'محامية', NULL, NOW(), NOW());

-- إنشاء 10 زيارات (5 Assessment و 5 Operation)
INSERT INTO `appointments` (`id`, `patient_id`, `doctor_id`, `branch_id`, `created_by`, `appointment_date`, `appointment_time`, `duration`, `visit_type`, `visit_stage`, `status`, `notes`, `notify_patient_sms`, `notify_doctor_sms`, `notify_doctor_email`, `follow_up`, `created_at`, `updated_at`) VALUES
-- 5 زيارات Assessment
(1, 1, 1, 1, 1, DATE_SUB(NOW(), INTERVAL 10 DAY), '09:00:00', 30, 'Assessment', 'completed', 'completed', 'تقييم أولي للمريض - فحص شامل للعين', 0, 0, 0, 0, NOW(), NOW()),
(2, 2, 2, 1, 1, DATE_SUB(NOW(), INTERVAL 5 DAY), '09:30:00', 30, 'Assessment', 'completed', 'completed', 'تقييم أولي للمريض - فحص شامل للعين', 0, 0, 0, 0, NOW(), NOW()),
(3, 3, 1, 1, 1, DATE_ADD(NOW(), INTERVAL 3 DAY), '10:00:00', 30, 'Assessment', 'waiting', 'scheduled', 'تقييم أولي للمريض - فحص شامل للعين', 0, 0, 0, 0, NOW(), NOW()),
(4, 4, 2, 1, 1, DATE_ADD(NOW(), INTERVAL 7 DAY), '10:30:00', 30, 'Assessment', 'waiting', 'scheduled', 'تقييم أولي للمريض - فحص شامل للعين', 0, 0, 0, 0, NOW(), NOW()),
(5, 5, 1, 1, 1, DATE_ADD(NOW(), INTERVAL 15 DAY), '11:00:00', 30, 'Assessment', 'waiting', 'scheduled', 'تقييم أولي للمريض - فحص شامل للعين', 0, 0, 0, 0, NOW(), NOW()),
-- 5 زيارات Operation
(6, 6, 2, 1, 1, DATE_SUB(NOW(), INTERVAL 8 DAY), '11:30:00', 60, 'Operation', 'completed', 'completed', 'عملية جراحية - LASIK / Femto-LASIK', 0, 0, 0, 0, NOW(), NOW()),
(7, 7, 1, 1, 1, DATE_SUB(NOW(), INTERVAL 2 DAY), '12:00:00', 60, 'Operation', 'completed', 'completed', 'عملية جراحية - LASIK / Femto-LASIK', 0, 0, 0, 0, NOW(), NOW()),
(8, 8, 2, 1, 1, DATE_ADD(NOW(), INTERVAL 5 DAY), '12:30:00', 60, 'Operation', 'waiting', 'scheduled', 'عملية جراحية - LASIK / Femto-LASIK', 0, 0, 0, 0, NOW(), NOW()),
(9, 9, 1, 1, 1, DATE_ADD(NOW(), INTERVAL 12 DAY), '13:00:00', 60, 'Operation', 'waiting', 'scheduled', 'عملية جراحية - LASIK / Femto-LASIK', 0, 0, 0, 0, NOW(), NOW()),
(10, 10, 2, 1, 1, DATE_ADD(NOW(), INTERVAL 20 DAY), '13:30:00', 60, 'Operation', 'waiting', 'scheduled', 'عملية جراحية - LASIK / Femto-LASIK', 0, 0, 0, 0, NOW(), NOW());

-- ============================================================
-- 5. إعادة تفعيل Foreign Keys
-- ============================================================

SET FOREIGN_KEY_CHECKS = 1;
SET UNIQUE_CHECKS = 1;

-- ============================================================
-- ✅ اكتمل التجهيز!
-- ============================================================
-- 
-- البيانات المُنشأة:
-- - فرع واحد: Main Branch
-- - مستخدم Admin: admin@gmail.com / password123
-- - طبيبان: Dr. Alaa و Dr. Tariq (alaa@almyzan.ps / tariq@almyzan.ps / password123)
-- - 10 مرضى بأسماء فلسطينية
-- - 10 زيارات (5 Assessment و 5 Operation)
-- - 5 فئات
-- - 7 خدمات
-- - 3 أدوار (admin, doctor, secretary)
-- - 24 صلاحية
-- 
-- ============================================================
