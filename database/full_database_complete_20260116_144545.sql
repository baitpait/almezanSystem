-- MySQL Database Export
-- Database: dralmyzin
-- Host: 127.0.0.1
-- Port: 3306
-- Export Date: Fri Jan 16 14:45:45 EET 2026
--
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;


-- MySQL dump 10.13  Distrib 9.5.0, for macos26.1 (arm64)
--
-- Host: 127.0.0.1    Database: dralmyzin
-- ------------------------------------------------------
-- Server version	9.5.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
SET @MYSQLDUMP_TEMP_LOG_BIN = @@SESSION.SQL_LOG_BIN;
SET @@SESSION.SQL_LOG_BIN= 0;

--
-- GTID state at the beginning of the backup 
--

SET @@GLOBAL.GTID_PURGED=/*!80000 '+'*/ 'c53e961a-d6dc-11f0-8b78-b73e0f94b6d4:1-8938';

--
-- Table structure for table `appointments`
--

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
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `appointments`
--

LOCK TABLES `appointments` WRITE;
/*!40000 ALTER TABLE `appointments` DISABLE KEYS */;
INSERT INTO `appointments` (`id`, `patient_id`, `doctor_id`, `procedure_id`, `operation_id`, `appointment_date`, `appointment_time`, `duration`, `notes`, `notify_patient_sms`, `notify_doctor_sms`, `notify_doctor_email`, `follow_up`, `status`, `visit_stage`, `visit_type`, `created_at`, `updated_at`, `created_by`, `branch_id`) VALUES (17,22,7,NULL,NULL,'2025-12-31','09:00:00',30,'تقييم أولي للمريض - فحص شامل للعين',0,0,0,0,'completed','completed','Assessment','2026-01-12 10:14:46','2026-01-12 10:14:46',5,1),(18,23,6,NULL,13,'2026-02-10','09:30:00',30,'تقييم أولي للمريض - فحص شامل للعين',0,0,0,0,'scheduled','completed','Assessment','2026-01-12 10:14:46','2026-01-12 11:21:37',5,1),(19,24,7,NULL,16,'2026-01-25','10:00:00',30,'تقييم أولي للمريض - فحص شامل للعين',0,0,0,0,'scheduled','in_consultation','Assessment','2026-01-12 10:14:46','2026-01-12 12:18:14',5,1),(20,25,6,NULL,NULL,'2025-12-23','10:30:00',30,'تقييم أولي للمريض - فحص شامل للعين',0,0,0,0,'completed','completed','Assessment','2026-01-12 10:14:46','2026-01-12 10:14:46',5,1),(21,26,6,NULL,17,'2026-02-06','11:00:00',30,'تقييم أولي للمريض - فحص شامل للعين',0,0,0,0,'scheduled','in_consultation','Assessment','2026-01-12 10:14:46','2026-01-12 12:21:57',5,1),(22,27,7,NULL,12,'2026-01-31','11:30:00',60,'عملية جراحية - LASIK / Femto-LASIK',0,0,0,0,'scheduled','waiting','Operation','2026-01-12 10:14:46','2026-01-12 10:22:31',5,1),(23,28,7,NULL,20,'2026-01-26','12:00:00',60,'عملية جراحية - LASIK / Femto-LASIK',0,0,0,0,'scheduled','in_consultation','Operation','2026-01-12 10:14:46','2026-01-12 12:40:22',5,1),(24,29,7,NULL,14,'2026-01-16','12:30:00',60,'عملية جراحية - LASIK / Femto-LASIK',0,0,0,0,'scheduled','in_consultation','Operation','2026-01-12 10:14:46','2026-01-12 11:24:18',5,1),(25,30,6,NULL,19,'2026-01-23','13:00:00',60,'عملية جراحية - LASIK / Femto-LASIK',0,0,0,0,'scheduled','in_consultation','Operation','2026-01-12 10:14:46','2026-01-12 12:40:11',5,1),(26,31,7,NULL,NULL,'2026-02-03','13:30:00',60,'عملية جراحية - LASIK / Femto-LASIK',0,0,0,0,'scheduled','waiting','Operation','2026-01-12 10:14:46','2026-01-12 10:14:46',5,1),(28,1,6,NULL,18,'2026-01-12','14:14:00',30,'',0,0,0,0,'scheduled','waiting','Operation','2026-01-12 12:14:23','2026-01-12 12:40:53',5,NULL),(29,4,6,NULL,NULL,'2026-01-12','14:41:00',30,'',0,0,0,0,'scheduled','waiting','Operation','2026-01-12 12:41:50','2026-01-12 12:41:50',5,NULL);
/*!40000 ALTER TABLE `appointments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `branches`
--

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `branches`
--

LOCK TABLES `branches` WRITE;
/*!40000 ALTER TABLE `branches` DISABLE KEYS */;
INSERT INTO `branches` (`id`, `name`, `address`, `phone`, `email`, `notes`, `is_active`, `created_at`, `updated_at`) VALUES (1,'Main Branch','Main Clinic Address','0599999999','main@clinic.com',NULL,1,'2025-12-11 20:01:29','2025-12-11 20:01:29');
/*!40000 ALTER TABLE `branches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

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

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('spatie.permission.cache','a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:61:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:13:\"view-patients\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:15:\"create-patients\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";i:3;s:1:\"b\";s:13:\"edit-patients\";s:1:\"c\";s:3:\"web\";}i:3;a:3:{s:1:\"a\";i:4;s:1:\"b\";s:15:\"delete-patients\";s:1:\"c\";s:3:\"web\";}i:4;a:3:{s:1:\"a\";i:5;s:1:\"b\";s:17:\"view-appointments\";s:1:\"c\";s:3:\"web\";}i:5;a:3:{s:1:\"a\";i:6;s:1:\"b\";s:19:\"create-appointments\";s:1:\"c\";s:3:\"web\";}i:6;a:3:{s:1:\"a\";i:7;s:1:\"b\";s:17:\"edit-appointments\";s:1:\"c\";s:3:\"web\";}i:7;a:3:{s:1:\"a\";i:8;s:1:\"b\";s:19:\"delete-appointments\";s:1:\"c\";s:3:\"web\";}i:8;a:3:{s:1:\"a\";i:9;s:1:\"b\";s:15:\"view-operations\";s:1:\"c\";s:3:\"web\";}i:9;a:3:{s:1:\"a\";i:10;s:1:\"b\";s:17:\"create-operations\";s:1:\"c\";s:3:\"web\";}i:10;a:3:{s:1:\"a\";i:11;s:1:\"b\";s:15:\"edit-operations\";s:1:\"c\";s:3:\"web\";}i:11;a:3:{s:1:\"a\";i:12;s:1:\"b\";s:17:\"delete-operations\";s:1:\"c\";s:3:\"web\";}i:12;a:3:{s:1:\"a\";i:13;s:1:\"b\";s:13:\"view-invoices\";s:1:\"c\";s:3:\"web\";}i:13;a:3:{s:1:\"a\";i:14;s:1:\"b\";s:15:\"create-invoices\";s:1:\"c\";s:3:\"web\";}i:14;a:3:{s:1:\"a\";i:15;s:1:\"b\";s:13:\"edit-invoices\";s:1:\"c\";s:3:\"web\";}i:15;a:3:{s:1:\"a\";i:16;s:1:\"b\";s:15:\"delete-invoices\";s:1:\"c\";s:3:\"web\";}i:16;a:3:{s:1:\"a\";i:17;s:1:\"b\";s:20:\"view-operation-notes\";s:1:\"c\";s:3:\"web\";}i:17;a:3:{s:1:\"a\";i:18;s:1:\"b\";s:22:\"create-operation-notes\";s:1:\"c\";s:3:\"web\";}i:18;a:3:{s:1:\"a\";i:19;s:1:\"b\";s:20:\"edit-operation-notes\";s:1:\"c\";s:3:\"web\";}i:19;a:3:{s:1:\"a\";i:20;s:1:\"b\";s:22:\"delete-operation-notes\";s:1:\"c\";s:3:\"web\";}i:20;a:3:{s:1:\"a\";i:21;s:1:\"b\";s:12:\"manage-users\";s:1:\"c\";s:3:\"web\";}i:21;a:3:{s:1:\"a\";i:22;s:1:\"b\";s:15:\"manage-branches\";s:1:\"c\";s:3:\"web\";}i:22;a:3:{s:1:\"a\";i:23;s:1:\"b\";s:15:\"manage-settings\";s:1:\"c\";s:3:\"web\";}i:23;a:3:{s:1:\"a\";i:24;s:1:\"b\";s:12:\"view-reports\";s:1:\"c\";s:3:\"web\";}i:24;a:4:{s:1:\"a\";i:25;s:1:\"b\";s:10:\"view.users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:25;a:4:{s:1:\"a\";i:26;s:1:\"b\";s:12:\"create.users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:26;a:4:{s:1:\"a\";i:27;s:1:\"b\";s:12:\"update.users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:27;a:4:{s:1:\"a\";i:28;s:1:\"b\";s:12:\"delete.users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:28;a:4:{s:1:\"a\";i:29;s:1:\"b\";s:13:\"view.branches\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:29;a:4:{s:1:\"a\";i:30;s:1:\"b\";s:15:\"create.branches\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:30;a:4:{s:1:\"a\";i:31;s:1:\"b\";s:15:\"update.branches\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:31;a:4:{s:1:\"a\";i:32;s:1:\"b\";s:15:\"delete.branches\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:32;a:4:{s:1:\"a\";i:33;s:1:\"b\";s:13:\"view.patients\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:33;a:4:{s:1:\"a\";i:34;s:1:\"b\";s:15:\"create.patients\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:34;a:4:{s:1:\"a\";i:35;s:1:\"b\";s:15:\"update.patients\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:35;a:4:{s:1:\"a\";i:36;s:1:\"b\";s:15:\"delete.patients\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:36;a:4:{s:1:\"a\";i:37;s:1:\"b\";s:17:\"view.appointments\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:37;a:4:{s:1:\"a\";i:38;s:1:\"b\";s:19:\"create.appointments\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:38;a:4:{s:1:\"a\";i:39;s:1:\"b\";s:19:\"update.appointments\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:39;a:4:{s:1:\"a\";i:40;s:1:\"b\";s:19:\"delete.appointments\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:40;a:4:{s:1:\"a\";i:41;s:1:\"b\";s:13:\"view.services\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:41;a:4:{s:1:\"a\";i:42;s:1:\"b\";s:15:\"create.services\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:42;a:4:{s:1:\"a\";i:43;s:1:\"b\";s:15:\"update.services\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:43;a:4:{s:1:\"a\";i:44;s:1:\"b\";s:15:\"delete.services\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:44;a:4:{s:1:\"a\";i:45;s:1:\"b\";s:13:\"view.invoices\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:45;a:4:{s:1:\"a\";i:46;s:1:\"b\";s:15:\"create.invoices\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:46;a:4:{s:1:\"a\";i:47;s:1:\"b\";s:15:\"update.invoices\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:47;a:4:{s:1:\"a\";i:48;s:1:\"b\";s:15:\"delete.invoices\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:48;a:4:{s:1:\"a\";i:49;s:1:\"b\";s:14:\"print.invoices\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:49;a:4:{s:1:\"a\";i:50;s:1:\"b\";s:15:\"view.assessment\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:50;a:4:{s:1:\"a\";i:51;s:1:\"b\";s:17:\"create.assessment\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:51;a:4:{s:1:\"a\";i:52;s:1:\"b\";s:17:\"update.assessment\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:52;a:4:{s:1:\"a\";i:53;s:1:\"b\";s:17:\"delete.assessment\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:53;a:4:{s:1:\"a\";i:54;s:1:\"b\";s:15:\"view.operations\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:54;a:4:{s:1:\"a\";i:55;s:1:\"b\";s:17:\"create.operations\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:55;a:4:{s:1:\"a\";i:56;s:1:\"b\";s:17:\"update.operations\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:56;a:4:{s:1:\"a\";i:57;s:1:\"b\";s:17:\"delete.operations\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:57;a:4:{s:1:\"a\";i:58;s:1:\"b\";s:12:\"view.doctors\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:58;a:4:{s:1:\"a\";i:59;s:1:\"b\";s:14:\"create.doctors\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:59;a:4:{s:1:\"a\";i:60;s:1:\"b\";s:14:\"update.doctors\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:60;a:4:{s:1:\"a\";i:61;s:1:\"b\";s:14:\"delete.doctors\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}}s:5:\"roles\";a:3:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:5:\"admin\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:6:\"doctor\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";i:3;s:1:\"b\";s:9:\"secretary\";s:1:\"c\";s:3:\"web\";}}}',1768650936);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

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

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

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

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctors`
--

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctors`
--

LOCK TABLES `doctors` WRITE;
/*!40000 ALTER TABLE `doctors` DISABLE KEYS */;
INSERT INTO `doctors` (`id`, `name`, `phone`, `created_at`, `updated_at`, `user_id`, `branch_id`) VALUES (6,'Dr. Alaa Al-Talbishi','0591234567','2026-01-12 10:14:00','2026-01-12 10:14:00',7,1),(7,'Dr. Tariq ','0597654321','2026-01-12 10:14:00','2026-01-16 10:44:51',8,1);
/*!40000 ALTER TABLE `doctors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ectasia_risk_assessments`
--

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ectasia_risk_assessments`
--

LOCK TABLES `ectasia_risk_assessments` WRITE;
/*!40000 ALTER TABLE `ectasia_risk_assessments` DISABLE KEYS */;
INSERT INTO `ectasia_risk_assessments` (`id`, `operation_id`, `pta_percentage_od`, `pta_percentage_os`, `rsb_od`, `rsb_os`, `tomography_normal_pattern`, `tomography_status`, `tomography_other`, `pachymetry_thinnest_od`, `pachymetry_thinnest_os`, `notes`, `created_at`, `updated_at`) VALUES (2,13,NULL,NULL,NULL,NULL,1,'normal',NULL,'550','550',NULL,'2026-01-12 11:21:37','2026-01-12 11:21:37'),(3,18,NULL,NULL,NULL,NULL,1,'normal',NULL,'550','550',NULL,'2026-01-12 12:39:58','2026-01-12 12:39:58');
/*!40000 ALTER TABLE `ectasia_risk_assessments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `eye_examinations`
--

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `eye_examinations`
--

LOCK TABLES `eye_examinations` WRITE;
/*!40000 ALTER TABLE `eye_examinations` DISABLE KEYS */;
INSERT INTO `eye_examinations` (`id`, `operation_id`, `examination_type`, `od_lids`, `od_iop`, `od_conjunctiva`, `od_cornea`, `od_tbut`, `od_schirmer`, `od_anterior_chamber`, `od_iris_pupil`, `od_lens`, `od_vitreous`, `od_optic_disc`, `od_retina`, `od_macula`, `od_vessels`, `od_fom`, `od_findings`, `os_lids`, `os_iop`, `os_conjunctiva`, `os_cornea`, `os_tbut`, `os_schirmer`, `os_anterior_chamber`, `os_iris_pupil`, `os_lens`, `os_vitreous`, `os_optic_disc`, `os_retina`, `os_macula`, `os_vessels`, `os_fom`, `os_findings`, `unaided_od`, `unaided_os`, `manifest_refraction_od`, `manifest_refraction_os`, `cyclo_refraction_od`, `cyclo_refraction_os`, `notes`, `created_at`, `updated_at`) VALUES (2,13,'pre_op','Normal','','Normal','Clear','','','Deep and quiet','Normal','Clear','Clear','Normal','Normal','Normal',NULL,NULL,NULL,'Normal','','Normal','Clear','','','Deep and quiet','Normal','Clear','Clear','Normal','Normal','Normal',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-01-12 11:21:37','2026-01-12 11:21:37'),(3,18,'pre_op','Normal','','Normal','Clear','','','Deep and quiet','Normal','Clear','Clear','Normal','Normal','Normal',NULL,NULL,NULL,'Normal','','Normal','Clear','','','Deep and quiet','Normal','Clear','Clear','Normal','Normal','Normal',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-01-12 12:39:58','2026-01-12 12:39:58');
/*!40000 ALTER TABLE `eye_examinations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

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

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoice_services`
--

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

--
-- Dumping data for table `invoice_services`
--

LOCK TABLES `invoice_services` WRITE;
/*!40000 ALTER TABLE `invoice_services` DISABLE KEYS */;
/*!40000 ALTER TABLE `invoice_services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoices`
--

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
INSERT INTO `invoices` (`id`, `invoice_number`, `patient_id`, `service_id`, `appointment_id`, `operation_id`, `doctor_id`, `branch_id`, `invoice_date`, `due_date`, `subtotal`, `discount`, `tax`, `total_amount`, `paid_amount`, `remaining_amount`, `status`, `payment_method`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES (1,'INV-2026010001',1,1,NULL,NULL,NULL,1,'2026-01-11',NULL,5000.00,0.00,0.00,5000.00,5000.00,0.00,'paid','cash','',NULL,'2026-01-11 08:12:43','2026-01-11 08:12:43');
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

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

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

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

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `medical_histories`
--

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `medical_histories`
--

LOCK TABLES `medical_histories` WRITE;
/*!40000 ALTER TABLE `medical_histories` DISABLE KEYS */;
INSERT INTO `medical_histories` (`id`, `operation_id`, `diabetes`, `chronic_disease`, `chronic_disease_details`, `herpes_keratitis`, `glaucoma`, `family_history_keratoconus`, `eye_rubber`, `pregnancy`, `ocular_surgery`, `ocular_surgery_details`, `family_history_ocular_disease`, `family_history_ocular_disease_yes`, `current_medications`, `current_medications_yes`, `allergies`, `glare_halos_squint`, `refraction_stable_1year`, `contact_lens_use`, `past_medical_history`, `past_ophthalmic_history`, `chief_complaint`, `notes`, `created_at`, `updated_at`) VALUES (2,13,0,0,NULL,0,0,0,0,0,0,'','',0,'',0,NULL,0,1,0,NULL,NULL,NULL,NULL,'2026-01-12 11:21:37','2026-01-12 11:21:37'),(3,18,0,0,NULL,0,0,0,0,0,0,'','',0,'',0,NULL,0,1,0,NULL,NULL,NULL,NULL,'2026-01-12 12:39:58','2026-01-12 12:39:58');
/*!40000 ALTER TABLE `medical_histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_12_08_085741_create_patients_table',1),(5,'2025_12_08_104139_create_categories_table',1),(6,'2025_12_08_104145_create_doctors_table',1),(7,'2025_12_08_104149_create_procedures_table',1),(8,'2025_12_08_104150_create_appointments_table',1),(9,'2025_12_08_105608_add_id_number_to_patients_table',1),(10,'2025_12_08_111647_add_role_and_branch_to_users_table',1),(11,'2025_12_08_111650_create_branches_table',1),(12,'2025_12_08_111654_add_user_id_and_branch_id_to_doctors_table',1),(13,'2025_12_08_111656_add_created_by_and_branch_id_to_appointments_table',1),(14,'2025_12_08_132848_add_photo_to_doctors_table',1),(15,'2025_12_08_132850_create_invoices_table',1),(16,'2025_12_08_135318_add_visit_stage_to_appointments_table',1),(17,'2025_12_08_155010_create_operations_table',1),(18,'2025_12_08_155813_create_refractive_profiles_table',1),(19,'2025_12_08_155814_create_medical_histories_table',1),(20,'2025_12_08_155815_create_eye_examinations_table',1),(21,'2025_12_08_155816_create_ectasia_risk_assessments_table',1),(22,'2025_12_08_155817_create_operation_details_table',1),(23,'2025_12_08_155818_create_operation_approvals_table',1),(24,'2025_12_08_155820_add_operation_id_to_appointments_table',1),(25,'2025_12_08_155821_add_operation_id_to_invoices_table',1),(26,'2025_12_10_175913_drop_operation_files_table',1),(27,'2025_12_10_180434_add_missing_fields_to_refractive_profiles_table',1),(28,'2025_12_10_180436_add_missing_fields_to_eye_examinations_table',1),(29,'2025_12_10_180438_add_missing_fields_to_ectasia_risk_assessments_table',1),(30,'2025_12_10_180440_add_missing_fields_to_operations_table',1),(31,'2025_12_10_182629_add_pre_op_target_fields_to_operation_details_table',1),(32,'2025_12_10_182631_add_surgeon_correction_fields_to_refractive_profiles_table',1),(33,'2025_12_14_100918_add_occupation_to_patients_table',2),(34,'2025_12_14_101306_remove_country_from_patients_table',3),(35,'2025_12_14_102207_remove_target_parameters_from_operation_details_table',4),(36,'2025_12_14_115132_add_visit_type_to_appointments_table',5),(37,'2025_12_14_120219_add_photo_to_patients_table',6),(38,'2025_12_14_120823_remove_photo_from_patients_table',7),(39,'2025_12_14_121250_add_price_to_appointments_table',8),(40,'2025_12_14_121250_add_price_to_categories_table',8),(41,'2025_12_14_132504_remove_cycloplegic_refraction_from_refractive_profiles_table',9),(42,'2025_12_14_133125_add_refraction_after_dilation_type_to_refractive_profiles_table',10),(43,'2025_12_14_134157_remove_surgeon_correction_from_refractive_profiles_table',11),(44,'2025_12_14_134604_add_pupil_diameter_to_refractive_profiles_table',12),(45,'2025_12_14_171809_change_refractive_profiles_fields_to_string',13),(46,'2025_12_14_172144_change_refraction_stable_1year_default_to_true',14),(47,'2025_12_14_172951_add_yes_fields_to_medical_histories_table',15),(48,'2025_12_14_184545_update_tomography_status_enum_to_include_not_normal',16),(49,'2025_12_14_190845_remove_treatment_options_from_operations_table',17),(50,'2025_12_14_191902_remove_patient_teaching_and_comments_from_operations_table',18),(52,'2025_12_14_192236_create_operation_files_table',19),(53,'2025_12_15_000001_add_decision_fields_to_operations_table',20),(54,'2025_12_15_082927_add_appointment_id_to_operations_table',21),(55,'2025_12_15_084450_convert_decimal_fields_to_text_in_assessment_tables',22),(56,'2025_12_15_084914_fix_operation_files_table_structure',23),(57,'2025_12_15_092713_create_operation_notes_table',24),(58,'2025_12_15_093358_add_prk_fields_to_ptk_in_operation_notes_table',25),(59,'2025_12_15_095104_revert_ptk_excimer_profile_to_original_values',26),(60,'2025_12_20_182309_remove_general_recommendation_fields_from_operations_table',27),(61,'2025_12_20_182632_add_monovision_eye_and_target_to_femto_lasik_in_operations_table',27),(62,'2025_12_20_182815_add_monovision_eye_and_target_to_smile_in_operations_table',27),(63,'2025_12_20_183053_add_prk_fields_to_ptk_in_operations_table',27),(64,'2025_12_20_183830_add_decision_od_and_decision_os_to_operations_table',27),(65,'2025_12_20_185238_add_separate_eye_fields_to_operations_table',28),(66,'2025_12_20_192510_create_permission_tables',29),(67,'2025_12_20_193415_add_operation_type_od_and_os_to_operations_table',30),(68,'2025_12_20_194320_add_none_option_to_monovision_eye_fields',31),(69,'2025_12_20_194500_add_none_option_to_monovision_eye_fields',31),(70,'2025_12_21_071458_add_operation_type_od_os_and_monovision_eye_to_operation_notes_table',32),(71,'2025_12_21_072456_add_separate_eye_fields_to_operation_notes_table',33),(72,'2025_12_21_081212_add_target_fields_to_operation_notes_table',34),(73,'2025_12_21_085145_add_mmc_duration_to_operation_notes_table',35),(74,'2025_12_24_085840_fix_ptk_epithelial_removal_enum_to_trans_ptk',36),(75,'2025_12_24_091034_add_photo_to_users_table',37),(76,'2025_12_24_092809_add_phone_notes_last_login_to_users_table',38),(77,'2025_12_24_133829_remove_category_and_price_from_appointments_table',39),(78,'2026_01_01_212502_add_previous_last_login_to_users_table',40),(79,'2026_01_01_213509_create_services_table',41),(80,'2026_01_01_213515_create_doctor_service_percentages_table',41),(81,'2026_01_01_213521_create_invoice_services_table',41),(82,'2026_01_01_214317_simplify_services_and_invoice_system',42),(83,'2026_01_01_215950_add_service_id_to_invoices_table',43),(84,'2026_01_01_220719_simplify_services_table',44),(85,'2026_01_02_002900_remove_unused_fields_from_doctors_table',45),(86,'2026_01_03_223944_clear_non_essential_data',46),(87,'2026_01_10_072148_update_visit_stage_enum_add_scheduled_remove_cancelled',47),(88,'2026_01_10_072330_add_cancelled_to_visit_stage_enum',48),(89,'2026_01_10_074247_make_operation_type_and_eye_nullable_in_operations_table',49),(90,'2026_01_10_083949_remove_unnecessary_columns_from_operations_table',50),(91,'2026_01_11_094348_add_same_operation_type_both_eyes_to_operation_notes_table',51),(92,'2026_01_07_191106_create_permission_system_tables',52);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

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

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

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

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES (1,'App\\Models\\User',5),(2,'App\\Models\\User',7),(2,'App\\Models\\User',8);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `operation_approvals`
--

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

--
-- Dumping data for table `operation_approvals`
--

LOCK TABLES `operation_approvals` WRITE;
/*!40000 ALTER TABLE `operation_approvals` DISABLE KEYS */;
/*!40000 ALTER TABLE `operation_approvals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `operation_details`
--

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

--
-- Dumping data for table `operation_details`
--

LOCK TABLES `operation_details` WRITE;
/*!40000 ALTER TABLE `operation_details` DISABLE KEYS */;
/*!40000 ALTER TABLE `operation_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `operation_files`
--

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `operation_files`
--

LOCK TABLES `operation_files` WRITE;
/*!40000 ALTER TABLE `operation_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `operation_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `operation_notes`
--

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `operation_notes`
--

LOCK TABLES `operation_notes` WRITE;
/*!40000 ALTER TABLE `operation_notes` DISABLE KEYS */;
INSERT INTO `operation_notes` (`id`, `appointment_id`, `patient_id`, `doctor_id`, `operation_type`, `operation_type_od`, `operation_type_os`, `same_operation_type_both_eyes`, `operation_eye`, `monovision_eye`, `eye_drops_vigamox`, `eye_drops_pred_forte`, `eye_drops_other`, `eye_drops_other_details`, `prk_epithelial_removal`, `prk_excimer_profile`, `prk_mmc_0_02_percent`, `prk_bandage_contact_lens`, `prk_epithelial_removal_od`, `prk_epithelial_removal_os`, `prk_excimer_profile_od`, `prk_excimer_profile_os`, `prk_mmc_0_02_percent_od`, `prk_mmc_0_02_percent_os`, `prk_bandage_contact_lens_od`, `prk_bandage_contact_lens_os`, `prk_target_od`, `prk_target_os`, `femto_flap_done`, `femto_excimer_profile`, `femto_bandage_contact_lens`, `femto_flap_done_od`, `femto_flap_done_os`, `femto_excimer_profile_od`, `femto_excimer_profile_os`, `femto_bandage_contact_lens_od`, `femto_bandage_contact_lens_os`, `femto_target_od`, `femto_target_os`, `smile_complete_lenticule_separation`, `smile_complete_lenticule_extraction`, `smile_complete_lenticule_separation_od`, `smile_complete_lenticule_separation_os`, `smile_complete_lenticule_extraction_od`, `smile_complete_lenticule_extraction_os`, `smile_target_od`, `smile_target_os`, `ptk_excimer_profile`, `ptk_epithelial_removal`, `ptk_mmc_0_02_percent`, `ptk_bandage_contact_lens`, `ptk_epithelial_removal_od`, `ptk_epithelial_removal_os`, `ptk_excimer_profile_od`, `ptk_excimer_profile_os`, `ptk_mmc_0_02_percent_od`, `ptk_mmc_0_02_percent_os`, `ptk_bandage_contact_lens_od`, `ptk_bandage_contact_lens_os`, `ptk_target_od`, `ptk_target_os`, `mmc_0_02_percent_od`, `mmc_duration_sec_od`, `mmc_0_02_percent_os`, `mmc_duration_sec_os`, `notes`, `created_at`, `updated_at`) VALUES (3,24,29,7,'PRK','PRK','PRK',1,'OU',NULL,0,0,0,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,'2026-01-12 11:24:26','2026-01-12 11:24:26');
/*!40000 ALTER TABLE `operation_notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `operations`
--

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
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `operations`
--

LOCK TABLES `operations` WRITE;
/*!40000 ALTER TABLE `operations` DISABLE KEYS */;
INSERT INTO `operations` (`id`, `patient_id`, `doctor_id`, `branch_id`, `appointment_id`, `created_by`, `start_date`, `end_date`, `status`, `pre_op_assessment_date`, `post_op_notes`, `decision`, `decision_od`, `decision_os`, `prk_epithelial_removal`, `prk_excimer_profile`, `prk_monovision_eye`, `prk_target`, `prk_epithelial_removal_od`, `prk_epithelial_removal_os`, `prk_excimer_profile_od`, `prk_excimer_profile_os`, `prk_monovision_eye_od`, `prk_monovision_eye_os`, `prk_target_od`, `prk_target_os`, `femto_excimer_profile`, `femto_monovision_eye`, `femto_target`, `femto_excimer_profile_od`, `femto_excimer_profile_os`, `femto_monovision_eye_od`, `femto_monovision_eye_os`, `femto_target_od`, `femto_target_os`, `smile_monovision_eye`, `smile_target`, `smile_monovision_eye_od`, `smile_monovision_eye_os`, `smile_target_od`, `smile_target_os`, `ptk_epithelial_removal`, `ptk_excimer_profile`, `ptk_monovision_eye`, `ptk_target`, `ptk_epithelial_removal_od`, `ptk_epithelial_removal_os`, `ptk_excimer_profile_od`, `ptk_excimer_profile_os`, `ptk_monovision_eye_od`, `ptk_monovision_eye_os`, `ptk_target_od`, `ptk_target_os`, `incompatible_notes`, `incompatible_notes_od`, `incompatible_notes_os`, `recommendation_notes`, `diagnosis`, `plan`, `notes`, `created_at`, `updated_at`, `deleted_at`) VALUES (12,27,7,1,22,5,'2026-01-31',NULL,'scheduled',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-01-12 10:22:31','2026-01-12 10:22:31',NULL),(13,23,6,1,18,5,'2026-02-10',NULL,'scheduled',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-01-12 11:21:22','2026-01-12 11:21:22',NULL),(14,29,7,1,24,5,'2026-01-16',NULL,'scheduled',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-01-12 11:24:18','2026-01-12 11:24:18',NULL),(16,24,7,1,19,5,'2026-01-25',NULL,'scheduled',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-01-12 12:18:14','2026-01-12 12:18:14',NULL),(17,26,6,1,21,5,'2026-02-06',NULL,'scheduled',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-01-12 12:21:57','2026-01-12 12:21:57',NULL),(18,1,6,1,28,5,'2026-01-12',NULL,'scheduled',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-01-12 12:39:50','2026-01-12 12:39:50',NULL),(19,30,6,1,25,5,'2026-01-23',NULL,'scheduled',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-01-12 12:40:11','2026-01-12 12:40:11',NULL),(20,28,7,1,23,5,'2026-01-26',NULL,'scheduled',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-01-12 12:40:22','2026-01-12 12:40:22',NULL);
/*!40000 ALTER TABLE `operations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

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

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patients`
--

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
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patients`
--

LOCK TABLES `patients` WRITE;
/*!40000 ALTER TABLE `patients` DISABLE KEYS */;
INSERT INTO `patients` (`id`, `full_name`, `id_number`, `date_of_birth`, `gender`, `phone`, `phone_secondary`, `city`, `occupation`, `notes`, `created_at`, `updated_at`) VALUES (1,'مصطفى','1234','2001-02-21','male','0599814758','','11','111','','2026-01-10 05:20:18','2026-01-10 05:20:18'),(2,'محمد أحمد النابلسي',NULL,'1963-10-15','male','0591000000','0592000000','رام الله',NULL,'مريض جديد - يحتاج تقييم شامل','2026-01-12 10:14:12','2026-01-12 10:14:12'),(3,'فاطمة خليل القدسي',NULL,'1964-02-16','female','0591000100',NULL,'نابلس',NULL,NULL,'2026-01-12 10:14:12','2026-01-12 10:14:12'),(4,'علي محمود الخليل',NULL,'1961-06-10','male','0591000200',NULL,'القدس',NULL,NULL,'2026-01-12 10:14:12','2026-01-12 10:14:12'),(5,'سارة يوسف رام الله',NULL,'1982-11-08','female','0591000300',NULL,'الخليل',NULL,'مريض جديد - يحتاج تقييم شامل','2026-01-12 10:14:12','2026-01-12 10:14:12'),(6,'خالد إبراهيم نابلس',NULL,'1991-04-14','male','0591000400',NULL,'بيت لحم',NULL,NULL,'2026-01-12 10:14:12','2026-01-12 10:14:12'),(7,'مريم حسن بيت لحم',NULL,'1995-04-20','female','0591000500',NULL,'جنين',NULL,NULL,'2026-01-12 10:14:12','2026-01-12 10:14:12'),(8,'أحمد سعد غزة',NULL,'1990-12-12','male','0591000600','0592000600','طولكرم',NULL,'مريض جديد - يحتاج تقييم شامل','2026-01-12 10:14:12','2026-01-12 10:14:12'),(9,'ليلى عمر يافا',NULL,'1981-01-20','female','0591000700',NULL,'قلقيلية',NULL,NULL,'2026-01-12 10:14:12','2026-01-12 10:14:12'),(10,'يوسف فهد حيفا',NULL,'1976-01-30','male','0591000800','0592000800','سلفيت',NULL,NULL,'2026-01-12 10:14:12','2026-01-12 10:14:12'),(11,'نورا عبدالرحمن عكا',NULL,'1989-03-09','female','0591000900',NULL,'طوباس',NULL,'مريض جديد - يحتاج تقييم شامل','2026-01-12 10:14:12','2026-01-12 10:14:12'),(12,'محمد أحمد النابلسي',NULL,'1962-01-01','male','0591000000',NULL,'رام الله',NULL,'مريض جديد - يحتاج تقييم شامل','2026-01-12 10:14:31','2026-01-12 10:14:31'),(13,'فاطمة خليل القدسي',NULL,'1980-11-27','female','0591000100','0592000100','نابلس',NULL,NULL,'2026-01-12 10:14:31','2026-01-12 10:14:31'),(14,'علي محمود الخليل',NULL,'1993-03-13','male','0591000200',NULL,'القدس',NULL,NULL,'2026-01-12 10:14:31','2026-01-12 10:14:31'),(15,'سارة يوسف رام الله',NULL,'1965-05-25','female','0591000300',NULL,'الخليل',NULL,'مريض جديد - يحتاج تقييم شامل','2026-01-12 10:14:31','2026-01-12 10:14:31'),(16,'خالد إبراهيم نابلس',NULL,'1973-02-19','male','0591000400',NULL,'بيت لحم',NULL,NULL,'2026-01-12 10:14:31','2026-01-12 10:14:31'),(17,'مريم حسن بيت لحم',NULL,'1979-05-24','female','0591000500','0592000500','جنين',NULL,NULL,'2026-01-12 10:14:31','2026-01-12 10:14:31'),(18,'أحمد سعد غزة',NULL,'1968-12-10','male','0591000600','0592000600','طولكرم',NULL,'مريض جديد - يحتاج تقييم شامل','2026-01-12 10:14:31','2026-01-12 10:14:31'),(19,'ليلى عمر يافا',NULL,'1968-11-20','female','0591000700',NULL,'قلقيلية',NULL,NULL,'2026-01-12 10:14:31','2026-01-12 10:14:31'),(20,'يوسف فهد حيفا',NULL,'1974-10-27','male','0591000800',NULL,'سلفيت',NULL,NULL,'2026-01-12 10:14:31','2026-01-12 10:14:31'),(21,'نورا عبدالرحمن عكا',NULL,'1987-01-03','female','0591000900',NULL,'طوباس',NULL,'مريض جديد - يحتاج تقييم شامل','2026-01-12 10:14:31','2026-01-12 10:14:31'),(22,'محمد أحمد النابلسي',NULL,'1971-10-28','male','0591000000',NULL,'رام الله',NULL,'مريض جديد - يحتاج تقييم شامل','2026-01-12 10:14:46','2026-01-12 10:14:46'),(23,'فاطمة خليل القدسي',NULL,'1999-12-26','female','0591000100',NULL,'نابلس',NULL,NULL,'2026-01-12 10:14:46','2026-01-12 10:14:46'),(24,'علي محمود الخليل',NULL,'1996-09-25','male','0591000200','0592000200','القدس',NULL,NULL,'2026-01-12 10:14:46','2026-01-12 10:14:46'),(25,'سارة يوسف رام الله',NULL,'1985-06-29','female','0591000300',NULL,'الخليل',NULL,'مريض جديد - يحتاج تقييم شامل','2026-01-12 10:14:46','2026-01-12 10:14:46'),(26,'خالد إبراهيم نابلس',NULL,'1972-09-04','male','0591000400',NULL,'بيت لحم',NULL,NULL,'2026-01-12 10:14:46','2026-01-12 10:14:46'),(27,'مريم حسن بيت لحم',NULL,'1961-07-27','female','0591000500',NULL,'جنين',NULL,NULL,'2026-01-12 10:14:46','2026-01-12 10:14:46'),(28,'أحمد سعد غزة',NULL,'1960-07-04','male','0591000600',NULL,'طولكرم',NULL,'مريض جديد - يحتاج تقييم شامل','2026-01-12 10:14:46','2026-01-12 10:14:46'),(29,'ليلى عمر يافا',NULL,'2000-03-12','female','0591000700','0592000700','قلقيلية',NULL,NULL,'2026-01-12 10:14:46','2026-01-12 10:14:46'),(30,'يوسف فهد حيفا',NULL,'1962-08-05','male','0591000800',NULL,'سلفيت',NULL,NULL,'2026-01-12 10:14:46','2026-01-12 10:14:46'),(31,'نورا عبدالرحمن عكا',NULL,'1966-08-11','female','0591000900',NULL,'طوباس',NULL,'مريض جديد - يحتاج تقييم شامل','2026-01-12 10:14:46','2026-01-12 10:14:46');
/*!40000 ALTER TABLE `patients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

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
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES (1,'view-patients','web','2025-12-20 17:25:36','2025-12-20 17:25:36'),(2,'create-patients','web','2025-12-20 17:25:36','2025-12-20 17:25:36'),(3,'edit-patients','web','2025-12-20 17:25:36','2025-12-20 17:25:36'),(4,'delete-patients','web','2025-12-20 17:25:36','2025-12-20 17:25:36'),(5,'view-appointments','web','2025-12-20 17:25:36','2025-12-20 17:25:36'),(6,'create-appointments','web','2025-12-20 17:25:36','2025-12-20 17:25:36'),(7,'edit-appointments','web','2025-12-20 17:25:36','2025-12-20 17:25:36'),(8,'delete-appointments','web','2025-12-20 17:25:36','2025-12-20 17:25:36'),(9,'view-operations','web','2025-12-20 17:25:36','2025-12-20 17:25:36'),(10,'create-operations','web','2025-12-20 17:25:36','2025-12-20 17:25:36'),(11,'edit-operations','web','2025-12-20 17:25:36','2025-12-20 17:25:36'),(12,'delete-operations','web','2025-12-20 17:25:36','2025-12-20 17:25:36'),(13,'view-invoices','web','2025-12-20 17:25:36','2025-12-20 17:25:36'),(14,'create-invoices','web','2025-12-20 17:25:36','2025-12-20 17:25:36'),(15,'edit-invoices','web','2025-12-20 17:25:36','2025-12-20 17:25:36'),(16,'delete-invoices','web','2025-12-20 17:25:36','2025-12-20 17:25:36'),(17,'view-operation-notes','web','2025-12-20 17:25:36','2025-12-20 17:25:36'),(18,'create-operation-notes','web','2025-12-20 17:25:36','2025-12-20 17:25:36'),(19,'edit-operation-notes','web','2025-12-20 17:25:36','2025-12-20 17:25:36'),(20,'delete-operation-notes','web','2025-12-20 17:25:36','2025-12-20 17:25:36'),(21,'manage-users','web','2025-12-20 17:25:36','2025-12-20 17:25:36'),(22,'manage-branches','web','2025-12-20 17:25:36','2025-12-20 17:25:36'),(23,'manage-settings','web','2025-12-20 17:25:36','2025-12-20 17:25:36'),(24,'view-reports','web','2025-12-20 17:25:36','2025-12-20 17:25:36'),(25,'view.users','web','2026-01-01 22:35:29','2026-01-01 22:35:29'),(26,'create.users','web','2026-01-01 22:35:29','2026-01-01 22:35:29'),(27,'update.users','web','2026-01-01 22:35:29','2026-01-01 22:35:29'),(28,'delete.users','web','2026-01-01 22:35:29','2026-01-01 22:35:29'),(29,'view.branches','web','2026-01-01 22:35:29','2026-01-01 22:35:29'),(30,'create.branches','web','2026-01-01 22:35:29','2026-01-01 22:35:29'),(31,'update.branches','web','2026-01-01 22:35:29','2026-01-01 22:35:29'),(32,'delete.branches','web','2026-01-01 22:35:29','2026-01-01 22:35:29'),(33,'view.patients','web','2026-01-01 22:35:29','2026-01-01 22:35:29'),(34,'create.patients','web','2026-01-01 22:35:29','2026-01-01 22:35:29'),(35,'update.patients','web','2026-01-01 22:35:29','2026-01-01 22:35:29'),(36,'delete.patients','web','2026-01-01 22:35:29','2026-01-01 22:35:29'),(37,'view.appointments','web','2026-01-01 22:35:29','2026-01-01 22:35:29'),(38,'create.appointments','web','2026-01-01 22:35:29','2026-01-01 22:35:29'),(39,'update.appointments','web','2026-01-01 22:35:29','2026-01-01 22:35:29'),(40,'delete.appointments','web','2026-01-01 22:35:29','2026-01-01 22:35:29'),(41,'view.services','web','2026-01-01 22:35:29','2026-01-01 22:35:29'),(42,'create.services','web','2026-01-01 22:35:29','2026-01-01 22:35:29'),(43,'update.services','web','2026-01-01 22:35:29','2026-01-01 22:35:29'),(44,'delete.services','web','2026-01-01 22:35:29','2026-01-01 22:35:29'),(45,'view.invoices','web','2026-01-01 22:35:29','2026-01-01 22:35:29'),(46,'create.invoices','web','2026-01-01 22:35:29','2026-01-01 22:35:29'),(47,'update.invoices','web','2026-01-01 22:35:29','2026-01-01 22:35:29'),(48,'delete.invoices','web','2026-01-01 22:35:29','2026-01-01 22:35:29'),(49,'print.invoices','web','2026-01-01 22:35:29','2026-01-01 22:35:29'),(50,'view.assessment','web','2026-01-01 22:35:29','2026-01-01 22:35:29'),(51,'create.assessment','web','2026-01-01 22:35:29','2026-01-01 22:35:29'),(52,'update.assessment','web','2026-01-01 22:35:29','2026-01-01 22:35:29'),(53,'delete.assessment','web','2026-01-01 22:35:29','2026-01-01 22:35:29'),(54,'view.operations','web','2026-01-01 22:35:29','2026-01-01 22:35:29'),(55,'create.operations','web','2026-01-01 22:35:29','2026-01-01 22:35:29'),(56,'update.operations','web','2026-01-01 22:35:29','2026-01-01 22:35:29'),(57,'delete.operations','web','2026-01-01 22:35:29','2026-01-01 22:35:29'),(58,'view.doctors','web','2026-01-01 22:35:29','2026-01-01 22:35:29'),(59,'create.doctors','web','2026-01-01 22:35:29','2026-01-01 22:35:29'),(60,'update.doctors','web','2026-01-01 22:35:29','2026-01-01 22:35:29'),(61,'delete.doctors','web','2026-01-01 22:35:29','2026-01-01 22:35:29');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `procedures`
--

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

--
-- Dumping data for table `procedures`
--

LOCK TABLES `procedures` WRITE;
/*!40000 ALTER TABLE `procedures` DISABLE KEYS */;
/*!40000 ALTER TABLE `procedures` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `refractive_profiles`
--

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `refractive_profiles`
--

LOCK TABLES `refractive_profiles` WRITE;
/*!40000 ALTER TABLE `refractive_profiles` DISABLE KEYS */;
INSERT INTO `refractive_profiles` (`id`, `operation_id`, `patient_name`, `patient_age`, `optometrist`, `eyeglasses_age`, `time_with_current_rx`, `current_eyeglasses_od_sphere`, `current_eyeglasses_od_cylinder`, `current_eyeglasses_od_axis`, `current_eyeglasses_od_vision`, `current_eyeglasses_os_sphere`, `current_eyeglasses_os_cylinder`, `current_eyeglasses_os_axis`, `current_eyeglasses_os_vision`, `contact_lenses`, `time_without_lenses`, `manifest_refraction_od_udva`, `manifest_refraction_od_sphere`, `manifest_refraction_od_cylinder`, `manifest_refraction_od_axis`, `manifest_refraction_od_bscva`, `manifest_refraction_od_dcnva_40cm`, `manifest_refraction_od_add_j1`, `manifest_refraction_od_rg`, `manifest_refraction_os_udva`, `manifest_refraction_os_sphere`, `manifest_refraction_os_cylinder`, `manifest_refraction_os_axis`, `manifest_refraction_os_bscva`, `manifest_refraction_os_dcnva_40cm`, `manifest_refraction_os_add_j1`, `manifest_refraction_os_rg`, `refraction_after_dilation_od_sphere`, `refraction_after_dilation_od_cylinder`, `refraction_after_dilation_od_axis`, `refraction_after_dilation_od_vision`, `refraction_after_dilation_os_sphere`, `refraction_after_dilation_os_cylinder`, `refraction_after_dilation_os_axis`, `refraction_after_dilation_os_vision`, `refraction_after_dilation_type`, `pupil_diameter_od_mesopic`, `pupil_diameter_od_scotopic`, `pupil_diameter_os_mesopic`, `pupil_diameter_os_scotopic`, `dominant_eye`, `simulation_for_monovision`, `notes`, `created_at`, `updated_at`) VALUES (2,13,'فاطمة خليل القدسي',-26,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'No',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-01-12 11:21:37','2026-01-12 11:21:37'),(3,18,'مصطفى',-25,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'No',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-01-12 12:39:58','2026-01-12 12:39:58');
/*!40000 ALTER TABLE `refractive_profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

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

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES (25,1),(26,1),(27,1),(28,1),(29,1),(30,1),(31,1),(32,1),(33,1),(34,1),(35,1),(36,1),(37,1),(38,1),(39,1),(40,1),(41,1),(42,1),(43,1),(44,1),(45,1),(46,1),(47,1),(48,1),(49,1),(50,1),(51,1),(52,1),(53,1),(54,1),(55,1),(56,1),(57,1),(58,1),(59,1),(60,1),(61,1),(25,2),(26,2),(27,2),(28,2),(29,2),(30,2),(31,2),(32,2),(33,2),(34,2),(35,2),(36,2),(37,2),(38,2),(39,2),(40,2),(41,2),(42,2),(43,2),(44,2),(45,2),(46,2),(47,2),(48,2),(49,2),(50,2),(51,2),(52,2),(53,2),(54,2),(55,2),(56,2),(57,2),(58,2),(59,2),(60,2),(61,2),(25,3),(26,3),(27,3),(28,3),(29,3),(30,3),(31,3),(32,3),(33,3),(34,3),(35,3),(36,3),(37,3),(38,3),(39,3),(40,3),(41,3),(42,3),(43,3),(44,3),(45,3),(46,3),(47,3),(48,3),(49,3),(50,3),(51,3),(52,3),(53,3),(54,3),(55,3),(56,3),(57,3),(58,3),(59,3),(60,3),(61,3);
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES (1,'admin','web','2025-12-20 17:25:36','2025-12-20 17:25:36'),(2,'doctor','web','2025-12-20 17:25:36','2025-12-20 17:25:36'),(3,'secretary','web','2025-12-20 17:25:36','2025-12-20 17:25:36');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` (`id`, `name`, `base_price`, `is_active`, `created_at`, `updated_at`) VALUES (1,'Cataract Surgery',5000.00,1,'2026-01-01 19:36:07','2026-01-01 19:36:07'),(2,'Lasik Surgery',8000.00,1,'2026-01-01 19:36:07','2026-01-01 19:36:07'),(3,'Consultation',200.00,1,'2026-01-01 19:36:07','2026-01-01 19:36:07'),(4,'X-Ray',150.00,1,'2026-01-01 19:36:07','2026-01-01 19:36:07'),(6,'Eye Exam',50.00,1,'2026-01-01 19:36:07','2026-01-01 19:36:07'),(7,'Follow-up Visit',150.00,1,'2026-01-01 19:36:07','2026-01-01 19:36:07');
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

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

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('FW2x8wEw4DqIqLRK8glosaLVZcZqI5ZUFS0AWhs6',5,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Cursor/2.3.35 Chrome/138.0.7204.251 Electron/37.7.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiRmRpZkwxNWVFb1ZZTVB4OFFTYzNUWGVvdzBtTGhvMHhwTmJ3b1pSNyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3BhdGllbnRzIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9kb2N0b3JzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MTp7aTowO3M6NzoibWVzc2FnZSI7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NTt9',1768567491),('H4xOaT68PdrEMJjMDo380vew7gqs0G2Jmdwr2y0H',5,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Cursor/2.3.34 Chrome/138.0.7204.251 Electron/37.7.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiZHh0d3hTWFJtQ3RoUlBBbVNidWlhdHZOQ2NMNlpCc2ptSENlVTBaVSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zY2hlZHVsZWQtb3BlcmF0aW9ucyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjU7fQ==',1768228942),('VMYth1GkfManTIIuTROvoOSExlmGVcbiZLwds5Ng',NULL,'127.0.0.1','curl/8.7.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQkdmOWtSTXE5bVZZWXhTbGllRlVhZVlDNjlvZUNwMXZjYVZwTjQ1UiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1768564452);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `name`, `email`, `phone`, `photo`, `notes`, `last_login_at`, `previous_last_login_at`, `role`, `is_active`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `branch_id`) VALUES (5,'Admin User','admin@gmail.com',NULL,NULL,NULL,'2026-01-16 09:55:36','2026-01-12 10:12:49','admin',1,NULL,'$2y$12$QQYEeCoHryGH6U/sAeCFu.w5xpZ9b48OIfJ1MBE0ixC8OY8brvkyG',NULL,'2026-01-11 11:50:33','2026-01-16 09:55:36',NULL),(7,'Dr. Alaa Al-Talbishi','alaa@almyzan.ps','0591234567',NULL,NULL,NULL,NULL,'doctor',1,NULL,'$2y$12$DidOd8bR0cLK44qYOyYELeEnnxdNakwXAlRNhx.WhZbNV/c/GBpHK',NULL,'2026-01-12 10:13:45','2026-01-12 10:13:45',1),(8,'Dr. Tariq ','tariq@almyzan.ps','0597654321',NULL,'',NULL,NULL,'doctor',1,NULL,'$2y$12$dzwy6nR8iN0cS93qYwW4v.imnWfGMGyhopRWuqE7tMY34Rl0QoA6a',NULL,'2026-01-12 10:14:00','2026-01-16 10:44:29',1);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'dralmyzin'
--

--
-- Dumping routines for database 'dralmyzin'
--
SET @@SESSION.SQL_LOG_BIN = @MYSQLDUMP_TEMP_LOG_BIN;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-01-16 14:45:45

SET FOREIGN_KEY_CHECKS = 1;
