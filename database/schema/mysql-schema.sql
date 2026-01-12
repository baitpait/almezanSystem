/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `appointments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `branches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_general_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `price` decimal(10,2) DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `doctors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ectasia_risk_assessments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ectasia_risk_assessments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `operation_id` bigint unsigned NOT NULL,
  `pta_percentage_od` text COLLATE utf8mb4_general_ci,
  `pta_percentage_os` text COLLATE utf8mb4_general_ci,
  `rsb_od` text COLLATE utf8mb4_general_ci,
  `rsb_os` text COLLATE utf8mb4_general_ci,
  `tomography_normal_pattern` tinyint(1) NOT NULL DEFAULT '0',
  `tomography_status` enum('normal','suspicious','other','not_normal') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tomography_other` text COLLATE utf8mb4_general_ci,
  `pachymetry_thinnest_od` text COLLATE utf8mb4_general_ci,
  `pachymetry_thinnest_os` text COLLATE utf8mb4_general_ci,
  `notes` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ectasia_risk_assessments_operation_id_index` (`operation_id`),
  CONSTRAINT `ectasia_risk_assessments_operation_id_foreign` FOREIGN KEY (`operation_id`) REFERENCES `operations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `eye_examinations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `eye_examinations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `operation_id` bigint unsigned NOT NULL,
  `examination_type` enum('pre_op','post_op') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pre_op',
  `od_lids` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `od_iop` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Intraocular Pressure OD',
  `od_conjunctiva` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `od_cornea` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `od_tbut` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `od_schirmer` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `od_anterior_chamber` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `od_iris_pupil` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `od_lens` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `od_vitreous` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `od_optic_disc` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `od_retina` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `od_macula` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `od_vessels` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `od_fom` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `od_findings` text COLLATE utf8mb4_general_ci,
  `os_lids` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `os_iop` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Intraocular Pressure OS',
  `os_conjunctiva` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `os_cornea` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `os_tbut` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `os_schirmer` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `os_anterior_chamber` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `os_iris_pupil` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `os_lens` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `os_vitreous` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `os_optic_disc` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `os_retina` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `os_macula` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `os_vessels` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `os_fom` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `os_findings` text COLLATE utf8mb4_general_ci,
  `unaided_od` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `unaided_os` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `manifest_refraction_od` text COLLATE utf8mb4_general_ci,
  `manifest_refraction_os` text COLLATE utf8mb4_general_ci,
  `cyclo_refraction_od` text COLLATE utf8mb4_general_ci,
  `cyclo_refraction_os` text COLLATE utf8mb4_general_ci,
  `notes` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `eye_examinations_operation_id_index` (`operation_id`),
  KEY `eye_examinations_examination_type_index` (`examination_type`),
  CONSTRAINT `eye_examinations_operation_id_foreign` FOREIGN KEY (`operation_id`) REFERENCES `operations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `invoice_services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `medical_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `medical_histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `operation_id` bigint unsigned NOT NULL,
  `diabetes` tinyint(1) NOT NULL DEFAULT '0',
  `chronic_disease` tinyint(1) NOT NULL DEFAULT '0',
  `chronic_disease_details` text COLLATE utf8mb4_general_ci,
  `herpes_keratitis` tinyint(1) NOT NULL DEFAULT '0',
  `glaucoma` tinyint(1) NOT NULL DEFAULT '0',
  `family_history_keratoconus` tinyint(1) NOT NULL DEFAULT '0',
  `eye_rubber` tinyint(1) NOT NULL DEFAULT '0',
  `pregnancy` tinyint(1) NOT NULL DEFAULT '0',
  `ocular_surgery` tinyint(1) NOT NULL DEFAULT '0',
  `ocular_surgery_details` text COLLATE utf8mb4_general_ci,
  `family_history_ocular_disease` text COLLATE utf8mb4_general_ci,
  `family_history_ocular_disease_yes` tinyint(1) NOT NULL DEFAULT '0',
  `current_medications` text COLLATE utf8mb4_general_ci,
  `current_medications_yes` tinyint(1) NOT NULL DEFAULT '0',
  `allergies` text COLLATE utf8mb4_general_ci,
  `glare_halos_squint` tinyint(1) NOT NULL DEFAULT '0',
  `refraction_stable_1year` tinyint(1) NOT NULL DEFAULT '1',
  `contact_lens_use` tinyint(1) NOT NULL DEFAULT '0',
  `past_medical_history` text COLLATE utf8mb4_general_ci,
  `past_ophthalmic_history` text COLLATE utf8mb4_general_ci,
  `chief_complaint` text COLLATE utf8mb4_general_ci,
  `notes` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `medical_histories_operation_id_index` (`operation_id`),
  CONSTRAINT `medical_histories_operation_id_foreign` FOREIGN KEY (`operation_id`) REFERENCES `operations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `operation_approvals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `operation_approvals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `operation_id` bigint unsigned NOT NULL,
  `approved_by` bigint unsigned NOT NULL,
  `approval_type` enum('medical_clearance','surgical_approval','anesthesia_approval','final_approval') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'medical_clearance',
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `comments` text COLLATE utf8mb4_general_ci,
  `approval_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `operation_approvals_operation_id_index` (`operation_id`),
  KEY `operation_approvals_approved_by_index` (`approved_by`),
  KEY `operation_approvals_status_index` (`status`),
  CONSTRAINT `operation_approvals_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `operation_approvals_operation_id_foreign` FOREIGN KEY (`operation_id`) REFERENCES `operations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `operation_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `operation_details` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `operation_id` bigint unsigned NOT NULL,
  `femto_lasik` tinyint(1) NOT NULL DEFAULT '0',
  `prk_mmc` tinyint(1) NOT NULL DEFAULT '0',
  `prk_type` enum('Alcohol 20%','Trans-PRK','Brush') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `trans_prk` tinyint(1) NOT NULL DEFAULT '0',
  `ptk` tinyint(1) NOT NULL DEFAULT '0',
  `topography_guided` tinyint(1) NOT NULL DEFAULT '0',
  `excimer_profile` enum('WFO','Topography Guided','Custom','Other') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mmc_concentration` decimal(5,3) DEFAULT NULL COMMENT 'e.g., 0.02 for 0.02%',
  `mmc_duration_seconds` int DEFAULT NULL,
  `bll` tinyint(1) NOT NULL DEFAULT '0',
  `drops_used` text COLLATE utf8mb4_general_ci,
  `target_refraction_od` text COLLATE utf8mb4_general_ci,
  `target_refraction_os` text COLLATE utf8mb4_general_ci,
  `mv_eye` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Monovision Eye',
  `has_complications` tinyint(1) NOT NULL DEFAULT '0',
  `complications_details` text COLLATE utf8mb4_general_ci,
  `notes` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `operation_details_operation_id_index` (`operation_id`),
  CONSTRAINT `operation_details_operation_id_foreign` FOREIGN KEY (`operation_id`) REFERENCES `operations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `operation_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `operation_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `operations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
  `prk_epithelial_removal` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `prk_excimer_profile` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `prk_monovision_eye` enum('none','OD','OS') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `prk_target` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `prk_epithelial_removal_od` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `prk_epithelial_removal_os` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `prk_excimer_profile_od` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `prk_excimer_profile_os` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `prk_monovision_eye_od` enum('none','OD','OS') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `prk_monovision_eye_os` enum('none','OD','OS') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `prk_target_od` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `prk_target_os` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `femto_excimer_profile` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `femto_monovision_eye` enum('none','OD','OS') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `femto_target` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `femto_excimer_profile_od` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `femto_excimer_profile_os` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `femto_monovision_eye_od` enum('none','OD','OS') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `femto_monovision_eye_os` enum('none','OD','OS') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `femto_target_od` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `femto_target_os` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `smile_monovision_eye` enum('none','OD','OS') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `smile_target` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `smile_monovision_eye_od` enum('none','OD','OS') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `smile_monovision_eye_os` enum('none','OD','OS') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `smile_target_od` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `smile_target_os` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ptk_epithelial_removal` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ptk_excimer_profile` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ptk_monovision_eye` enum('none','OD','OS') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ptk_target` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ptk_epithelial_removal_od` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ptk_epithelial_removal_os` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ptk_excimer_profile_od` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ptk_excimer_profile_os` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ptk_monovision_eye_od` enum('none','OD','OS') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ptk_monovision_eye_os` enum('none','OD','OS') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ptk_target_od` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ptk_target_os` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `incompatible_notes` text COLLATE utf8mb4_general_ci,
  `incompatible_notes_od` text COLLATE utf8mb4_general_ci,
  `incompatible_notes_os` text COLLATE utf8mb4_general_ci,
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
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `patients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `procedures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procedures` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `default_duration` int NOT NULL DEFAULT '30' COMMENT 'Default duration in minutes',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `refractive_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
  `manifest_refraction_od_rg` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'R/G test result',
  `manifest_refraction_os_udva` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `manifest_refraction_os_sphere` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `manifest_refraction_os_cylinder` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `manifest_refraction_os_axis` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `manifest_refraction_os_bscva` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `manifest_refraction_os_dcnva_40cm` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `manifest_refraction_os_add_j1` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `manifest_refraction_os_rg` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'R/G test result',
  `refraction_after_dilation_od_sphere` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `refraction_after_dilation_od_cylinder` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `refraction_after_dilation_od_axis` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `refraction_after_dilation_od_vision` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `refraction_after_dilation_os_sphere` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `refraction_after_dilation_os_cylinder` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `refraction_after_dilation_os_axis` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `refraction_after_dilation_os_vision` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `refraction_after_dilation_type` enum('Mydramide','CYCLO') COLLATE utf8mb4_general_ci DEFAULT NULL,
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
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
  KEY `users_branch_id_foreign` (`branch_id`),
  CONSTRAINT `users_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'0001_01_01_000000_create_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4,'2025_12_08_085741_create_patients_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5,'2025_12_08_104139_create_categories_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6,'2025_12_08_104145_create_doctors_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7,'2025_12_08_104149_create_procedures_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8,'2025_12_08_104150_create_appointments_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9,'2025_12_08_105608_add_id_number_to_patients_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10,'2025_12_08_111647_add_role_and_branch_to_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (11,'2025_12_08_111650_create_branches_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12,'2025_12_08_111654_add_user_id_and_branch_id_to_doctors_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13,'2025_12_08_111656_add_created_by_and_branch_id_to_appointments_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (14,'2025_12_08_132848_add_photo_to_doctors_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (15,'2025_12_08_132850_create_invoices_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (16,'2025_12_08_135318_add_visit_stage_to_appointments_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (17,'2025_12_08_155010_create_operations_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (18,'2025_12_08_155813_create_refractive_profiles_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (19,'2025_12_08_155814_create_medical_histories_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (20,'2025_12_08_155815_create_eye_examinations_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (21,'2025_12_08_155816_create_ectasia_risk_assessments_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (22,'2025_12_08_155817_create_operation_details_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (23,'2025_12_08_155818_create_operation_approvals_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (24,'2025_12_08_155820_add_operation_id_to_appointments_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (25,'2025_12_08_155821_add_operation_id_to_invoices_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (26,'2025_12_10_175913_drop_operation_files_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (27,'2025_12_10_180434_add_missing_fields_to_refractive_profiles_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (28,'2025_12_10_180436_add_missing_fields_to_eye_examinations_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (29,'2025_12_10_180438_add_missing_fields_to_ectasia_risk_assessments_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (30,'2025_12_10_180440_add_missing_fields_to_operations_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (31,'2025_12_10_182629_add_pre_op_target_fields_to_operation_details_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (32,'2025_12_10_182631_add_surgeon_correction_fields_to_refractive_profiles_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (33,'2025_12_14_100918_add_occupation_to_patients_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (34,'2025_12_14_101306_remove_country_from_patients_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (35,'2025_12_14_102207_remove_target_parameters_from_operation_details_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (36,'2025_12_14_115132_add_visit_type_to_appointments_table',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (37,'2025_12_14_120219_add_photo_to_patients_table',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (38,'2025_12_14_120823_remove_photo_from_patients_table',7);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (39,'2025_12_14_121250_add_price_to_appointments_table',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (40,'2025_12_14_121250_add_price_to_categories_table',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (41,'2025_12_14_132504_remove_cycloplegic_refraction_from_refractive_profiles_table',9);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (42,'2025_12_14_133125_add_refraction_after_dilation_type_to_refractive_profiles_table',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (43,'2025_12_14_134157_remove_surgeon_correction_from_refractive_profiles_table',11);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (44,'2025_12_14_134604_add_pupil_diameter_to_refractive_profiles_table',12);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (45,'2025_12_14_171809_change_refractive_profiles_fields_to_string',13);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (46,'2025_12_14_172144_change_refraction_stable_1year_default_to_true',14);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (47,'2025_12_14_172951_add_yes_fields_to_medical_histories_table',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (48,'2025_12_14_184545_update_tomography_status_enum_to_include_not_normal',16);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (49,'2025_12_14_190845_remove_treatment_options_from_operations_table',17);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (50,'2025_12_14_191902_remove_patient_teaching_and_comments_from_operations_table',18);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (52,'2025_12_14_192236_create_operation_files_table',19);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (53,'2025_12_15_000001_add_decision_fields_to_operations_table',20);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (54,'2025_12_15_082927_add_appointment_id_to_operations_table',21);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (55,'2025_12_15_084450_convert_decimal_fields_to_text_in_assessment_tables',22);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (56,'2025_12_15_084914_fix_operation_files_table_structure',23);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (57,'2025_12_15_092713_create_operation_notes_table',24);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (58,'2025_12_15_093358_add_prk_fields_to_ptk_in_operation_notes_table',25);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (59,'2025_12_15_095104_revert_ptk_excimer_profile_to_original_values',26);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (60,'2025_12_20_182309_remove_general_recommendation_fields_from_operations_table',27);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (61,'2025_12_20_182632_add_monovision_eye_and_target_to_femto_lasik_in_operations_table',27);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (62,'2025_12_20_182815_add_monovision_eye_and_target_to_smile_in_operations_table',27);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (63,'2025_12_20_183053_add_prk_fields_to_ptk_in_operations_table',27);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (64,'2025_12_20_183830_add_decision_od_and_decision_os_to_operations_table',27);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (65,'2025_12_20_185238_add_separate_eye_fields_to_operations_table',28);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (66,'2025_12_20_192510_create_permission_tables',29);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (67,'2025_12_20_193415_add_operation_type_od_and_os_to_operations_table',30);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (68,'2025_12_20_194320_add_none_option_to_monovision_eye_fields',31);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (69,'2025_12_20_194500_add_none_option_to_monovision_eye_fields',31);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (70,'2025_12_21_071458_add_operation_type_od_os_and_monovision_eye_to_operation_notes_table',32);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (71,'2025_12_21_072456_add_separate_eye_fields_to_operation_notes_table',33);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (72,'2025_12_21_081212_add_target_fields_to_operation_notes_table',34);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (73,'2025_12_21_085145_add_mmc_duration_to_operation_notes_table',35);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (74,'2025_12_24_085840_fix_ptk_epithelial_removal_enum_to_trans_ptk',36);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (75,'2025_12_24_091034_add_photo_to_users_table',37);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (76,'2025_12_24_092809_add_phone_notes_last_login_to_users_table',38);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (77,'2025_12_24_133829_remove_category_and_price_from_appointments_table',39);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (78,'2026_01_01_212502_add_previous_last_login_to_users_table',40);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (79,'2026_01_01_213509_create_services_table',41);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (80,'2026_01_01_213515_create_doctor_service_percentages_table',41);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (81,'2026_01_01_213521_create_invoice_services_table',41);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (82,'2026_01_01_214317_simplify_services_and_invoice_system',42);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (83,'2026_01_01_215950_add_service_id_to_invoices_table',43);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (84,'2026_01_01_220719_simplify_services_table',44);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (85,'2026_01_02_002900_remove_unused_fields_from_doctors_table',45);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (86,'2026_01_03_223944_clear_non_essential_data',46);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (87,'2026_01_10_072148_update_visit_stage_enum_add_scheduled_remove_cancelled',47);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (88,'2026_01_10_072330_add_cancelled_to_visit_stage_enum',48);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (89,'2026_01_10_074247_make_operation_type_and_eye_nullable_in_operations_table',49);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (90,'2026_01_10_083949_remove_unnecessary_columns_from_operations_table',50);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (91,'2026_01_11_094348_add_same_operation_type_both_eyes_to_operation_notes_table',51);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (92,'2026_01_07_191106_create_permission_system_tables',52);
