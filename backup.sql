-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: radar
-- ------------------------------------------------------
-- Server version	8.0.30

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

--
-- Table structure for table `assessment_report`
--

DROP TABLE IF EXISTS `assessment_report`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `assessment_report` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_karyawan` bigint unsigned NOT NULL,
  `tahun` year DEFAULT NULL,
  `periode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kb_kecerdasan_umum` double DEFAULT NULL,
  `kb_fleksibilitas_berfikir` double DEFAULT NULL,
  `kb_kemampuan_konseptual` double DEFAULT NULL,
  `kb_analisa_sintesa` double DEFAULT NULL,
  `sk_sistematika_kerja` double DEFAULT NULL,
  `sk_daya_tahan` double DEFAULT NULL,
  `sk_fleksibilitas` double DEFAULT NULL,
  `sk_perhatian_detail` double DEFAULT NULL,
  `kp_stabilitas_emosi` double DEFAULT NULL,
  `kp_penyesuaian_diri` double DEFAULT NULL,
  `kp_kepercayaan_diri` double DEFAULT NULL,
  `kp_kepemimpinan` double DEFAULT NULL,
  `kp_integritas` double DEFAULT NULL,
  `kp_motivasi_kerja` double DEFAULT NULL,
  `ms_planning_controlling` double DEFAULT NULL,
  `ms_managing_changes` double DEFAULT NULL,
  `ms_empowerment` double DEFAULT NULL,
  `ms_fostering_team_work` double DEFAULT NULL,
  `ms_analysis_decision` double DEFAULT NULL,
  `ms_humility` double DEFAULT NULL,
  `ms_compassion` double DEFAULT NULL,
  `ms_excellence` double DEFAULT NULL,
  `ms_integrity_ethics` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `assessment_report_id_karyawan_foreign` (`id_karyawan`),
  CONSTRAINT `assessment_report_id_karyawan_foreign` FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `assessment_report`
--

LOCK TABLES `assessment_report` WRITE;
/*!40000 ALTER TABLE `assessment_report` DISABLE KEYS */;
INSERT INTO `assessment_report` VALUES (1,203,NULL,NULL,NULL,2,2,4,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-10-08 18:00:13','2026-10-08 18:13:06'),(2,2,NULL,NULL,NULL,3,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-10-08 19:35:55','2026-10-08 19:35:55');
/*!40000 ALTER TABLE `assessment_report` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('laravel-cache-spatie.permission.cache','a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:44:{i:0;a:3:{s:1:\"a\";i:10;s:1:\"b\";s:14:\"lihat_karyawan\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:11;s:1:\"b\";s:15:\"tambah_karyawan\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";i:12;s:1:\"b\";s:13:\"edit_karyawan\";s:1:\"c\";s:3:\"web\";}i:3;a:3:{s:1:\"a\";i:13;s:1:\"b\";s:14:\"hapus_karyawan\";s:1:\"c\";s:3:\"web\";}i:4;a:3:{s:1:\"a\";i:14;s:1:\"b\";s:16:\"lihat_departemen\";s:1:\"c\";s:3:\"web\";}i:5;a:3:{s:1:\"a\";i:15;s:1:\"b\";s:17:\"tambah_departemen\";s:1:\"c\";s:3:\"web\";}i:6;a:3:{s:1:\"a\";i:16;s:1:\"b\";s:15:\"edit_departemen\";s:1:\"c\";s:3:\"web\";}i:7;a:3:{s:1:\"a\";i:17;s:1:\"b\";s:16:\"hapus_departemen\";s:1:\"c\";s:3:\"web\";}i:8;a:3:{s:1:\"a\";i:18;s:1:\"b\";s:10:\"lihat_role\";s:1:\"c\";s:3:\"web\";}i:9;a:3:{s:1:\"a\";i:19;s:1:\"b\";s:11:\"tambah_role\";s:1:\"c\";s:3:\"web\";}i:10;a:3:{s:1:\"a\";i:20;s:1:\"b\";s:9:\"edit_role\";s:1:\"c\";s:3:\"web\";}i:11;a:3:{s:1:\"a\";i:21;s:1:\"b\";s:10:\"hapus_role\";s:1:\"c\";s:3:\"web\";}i:12;a:3:{s:1:\"a\";i:22;s:1:\"b\";s:10:\"lihat_user\";s:1:\"c\";s:3:\"web\";}i:13;a:3:{s:1:\"a\";i:23;s:1:\"b\";s:11:\"tambah_user\";s:1:\"c\";s:3:\"web\";}i:14;a:3:{s:1:\"a\";i:24;s:1:\"b\";s:9:\"edit_user\";s:1:\"c\";s:3:\"web\";}i:15;a:3:{s:1:\"a\";i:25;s:1:\"b\";s:10:\"hapus_user\";s:1:\"c\";s:3:\"web\";}i:16;a:3:{s:1:\"a\";i:30;s:1:\"b\";s:14:\"lihat_training\";s:1:\"c\";s:3:\"web\";}i:17;a:3:{s:1:\"a\";i:31;s:1:\"b\";s:15:\"tambah_training\";s:1:\"c\";s:3:\"web\";}i:18;a:3:{s:1:\"a\";i:32;s:1:\"b\";s:13:\"edit_training\";s:1:\"c\";s:3:\"web\";}i:19;a:3:{s:1:\"a\";i:33;s:1:\"b\";s:14:\"hapus_training\";s:1:\"c\";s:3:\"web\";}i:20;a:3:{s:1:\"a\";i:34;s:1:\"b\";s:15:\"lihat_mentoring\";s:1:\"c\";s:3:\"web\";}i:21;a:3:{s:1:\"a\";i:35;s:1:\"b\";s:16:\"tambah_mentoring\";s:1:\"c\";s:3:\"web\";}i:22;a:3:{s:1:\"a\";i:36;s:1:\"b\";s:14:\"edit_mentoring\";s:1:\"c\";s:3:\"web\";}i:23;a:3:{s:1:\"a\";i:37;s:1:\"b\";s:15:\"hapus_mentoring\";s:1:\"c\";s:3:\"web\";}i:24;a:3:{s:1:\"a\";i:38;s:1:\"b\";s:19:\"lihat_certification\";s:1:\"c\";s:3:\"web\";}i:25;a:3:{s:1:\"a\";i:39;s:1:\"b\";s:20:\"tambah_certification\";s:1:\"c\";s:3:\"web\";}i:26;a:3:{s:1:\"a\";i:40;s:1:\"b\";s:18:\"edit_certification\";s:1:\"c\";s:3:\"web\";}i:27;a:3:{s:1:\"a\";i:41;s:1:\"b\";s:19:\"hapus_certification\";s:1:\"c\";s:3:\"web\";}i:28;a:3:{s:1:\"a\";i:42;s:1:\"b\";s:16:\"lihat_assessment\";s:1:\"c\";s:3:\"web\";}i:29;a:3:{s:1:\"a\";i:43;s:1:\"b\";s:17:\"tambah_assessment\";s:1:\"c\";s:3:\"web\";}i:30;a:3:{s:1:\"a\";i:44;s:1:\"b\";s:15:\"edit_assessment\";s:1:\"c\";s:3:\"web\";}i:31;a:3:{s:1:\"a\";i:45;s:1:\"b\";s:16:\"hapus_assessment\";s:1:\"c\";s:3:\"web\";}i:32;a:4:{s:1:\"a\";i:53;s:1:\"b\";s:13:\"lihat_journey\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:33;a:4:{s:1:\"a\";i:54;s:1:\"b\";s:14:\"tambah_journey\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:34;a:4:{s:1:\"a\";i:55;s:1:\"b\";s:12:\"edit_journey\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:35;a:4:{s:1:\"a\";i:56;s:1:\"b\";s:13:\"hapus_journey\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:36;a:3:{s:1:\"a\";i:57;s:1:\"b\";s:13:\"lihat_jabatan\";s:1:\"c\";s:3:\"web\";}i:37;a:3:{s:1:\"a\";i:58;s:1:\"b\";s:14:\"tambah_jabatan\";s:1:\"c\";s:3:\"web\";}i:38;a:3:{s:1:\"a\";i:59;s:1:\"b\";s:12:\"edit_jabatan\";s:1:\"c\";s:3:\"web\";}i:39;a:3:{s:1:\"a\";i:60;s:1:\"b\";s:13:\"hapus_jabatan\";s:1:\"c\";s:3:\"web\";}i:40;a:4:{s:1:\"a\";i:61;s:1:\"b\";s:9:\"lihat_idp\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:41;a:4:{s:1:\"a\";i:62;s:1:\"b\";s:10:\"tambah_idp\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:42;a:4:{s:1:\"a\";i:63;s:1:\"b\";s:8:\"edit_idp\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:43;a:4:{s:1:\"a\";i:64;s:1:\"b\";s:9:\"hapus_idp\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}}s:5:\"roles\";a:1:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:5:\"Admin\";s:1:\"c\";s:3:\"web\";}}}',1791595035);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `departments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_departemen` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` VALUES (1,'Production & PPIC','2026-10-06 00:40:57','2026-10-06 00:40:57'),(2,'Maintenance','2026-10-06 00:41:07','2026-10-06 00:41:07'),(3,'Engineering','2026-10-06 00:41:16','2026-10-06 00:41:16'),(4,'IT','2026-10-06 00:41:24','2026-10-06 00:41:24'),(5,'Quality Control','2026-10-06 00:41:34','2026-10-06 00:41:48'),(6,'Quality Assurance','2026-10-06 00:41:59','2026-10-06 00:41:59'),(7,'Customer Relation','2026-10-06 00:42:08','2026-10-06 00:42:08'),(8,'Admin','2026-10-06 00:42:18','2026-10-06 00:42:18'),(9,'Purchasing','2026-10-06 00:42:26','2026-10-06 00:42:26');
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `idp`
--

DROP TABLE IF EXISTS `idp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `idp` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_karyawan` bigint unsigned NOT NULL,
  `tahun` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `periode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_idp` enum('pending','completed','cancelled') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mentor` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `development` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target` date DEFAULT NULL,
  `monitoring` date DEFAULT NULL,
  `final` date DEFAULT NULL,
  `comp_planning` double DEFAULT NULL,
  `comp_adaptability` double DEFAULT NULL,
  `comp_leadership` double DEFAULT NULL,
  `comp_communication` double DEFAULT NULL,
  `comp_problem_solving` double DEFAULT NULL,
  `comp_technical_skill` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idp_id_karyawan_foreign` (`id_karyawan`),
  CONSTRAINT `idp_id_karyawan_foreign` FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `idp`
--

LOCK TABLES `idp` WRITE;
/*!40000 ALTER TABLE `idp` DISABLE KEYS */;
INSERT INTO `idp` VALUES (1,203,NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-10-08 21:45:12','2026-10-08 21:45:12'),(2,203,NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-10-08 21:46:05','2026-10-08 21:46:05'),(3,203,NULL,NULL,NULL,NULL,NULL,'2022-02-12',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-10-08 21:46:28','2026-10-08 21:46:28'),(4,203,NULL,'1',NULL,NULL,NULL,'2026-12-12',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-10-08 21:48:53','2026-10-08 23:34:10');
/*!40000 ALTER TABLE `idp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `idp_penilain`
--

DROP TABLE IF EXISTS `idp_penilain`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `idp_penilain` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_idp` bigint unsigned NOT NULL,
  `id_karyawan` bigint unsigned NOT NULL,
  `id_penilain` bigint unsigned NOT NULL,
  `head_managing_change` double DEFAULT NULL,
  `head_empowerment` double DEFAULT NULL,
  `head_fostering_teamwork` double DEFAULT NULL,
  `head_analysis_decision` double DEFAULT NULL,
  `head_planning_controlling` double DEFAULT NULL,
  `head_integrity_ethics` double DEFAULT NULL,
  `head_excellence` double DEFAULT NULL,
  `head_compassion` double DEFAULT NULL,
  `head_humility` double DEFAULT NULL,
  `staff_leads_courageously` double DEFAULT NULL,
  `staff_coach_develop` double DEFAULT NULL,
  `staff_teamwork` double DEFAULT NULL,
  `staff_trouble_shooting` double DEFAULT NULL,
  `staff_customer_satisfaction` double DEFAULT NULL,
  `staff_cross_functional` double DEFAULT NULL,
  `staff_quality_focus` double DEFAULT NULL,
  `staff_continuous_improvement` double DEFAULT NULL,
  `staff_planning_monitoring` double DEFAULT NULL,
  `staff_personal_integrity` double DEFAULT NULL,
  `staff_drive_for_result` double DEFAULT NULL,
  `staff_learning` double DEFAULT NULL,
  `unit_directiveness` double DEFAULT NULL,
  `unit_monitoring` double DEFAULT NULL,
  `unit_teamwork` double DEFAULT NULL,
  `unit_quality_customer` double DEFAULT NULL,
  `unit_continuous_improvement` double DEFAULT NULL,
  `unit_personal_integrity` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idp_penilain_id_idp_foreign` (`id_idp`),
  KEY `idp_penilain_id_karyawan_foreign` (`id_karyawan`),
  CONSTRAINT `idp_penilain_id_idp_foreign` FOREIGN KEY (`id_idp`) REFERENCES `idp` (`id`) ON DELETE CASCADE,
  CONSTRAINT `idp_penilain_id_karyawan_foreign` FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `idp_penilain`
--

LOCK TABLES `idp_penilain` WRITE;
/*!40000 ALTER TABLE `idp_penilain` DISABLE KEYS */;
INSERT INTO `idp_penilain` VALUES (1,4,203,4,2,3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-10-08 21:48:53','2026-10-08 23:14:46');
/*!40000 ALTER TABLE `idp_penilain` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `karyawan`
--

DROP TABLE IF EXISTS `karyawan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `karyawan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `department_id` bigint unsigned DEFAULT NULL,
  `npk` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `pendidikan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kontak` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position_id` bigint unsigned DEFAULT NULL,
  `grade` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `masuk_kerja` date DEFAULT NULL,
  `iq` int DEFAULT NULL,
  `readiness` enum('R1 ready','R2 ready','R3 ready','R4 ready','R5 ready','not_ready') COLLATE utf8mb4_unicode_ci NOT NULL,
  `disc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hav` enum('star','high potential','potential','excellent career person','career person','problem employee','deadwood') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `karyawan_email_unique` (`email`),
  KEY `karyawan_department_id_foreign` (`department_id`),
  KEY `karyawan_position_id_foreign` (`position_id`),
  CONSTRAINT `karyawan_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `karyawan_position_id_foreign` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=204 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `karyawan`
--

LOCK TABLES `karyawan` WRITE;
/*!40000 ALTER TABLE `karyawan` DISABLE KEYS */;
INSERT INTO `karyawan` VALUES (1,4,'1231232','widia',NULL,'2000-02-12','S1 Ilmu Komputer','08987654321','dimasjulianto987@gmail.com',2,'A1','2025-02-12',1,'R4 ready','D','potential','2026-10-06 01:02:57','2026-10-08 20:44:54'),(2,9,'12346','Siti Aminah',NULL,'1985-12-15','S2 Manajemen','08987654321','siti.aminah@example.com',1,'5B','2015-08-20',115,'not_ready','I','potential','2026-10-06 17:02:27','2026-10-08 01:46:42'),(203,4,'12345','Budi Santoso',NULL,'1990-01-01','S1 Teknik Informatika','08123456789','budi.santoso@example.com',1,'4A','2020-05-10',120,'R3 ready','D','star','2026-10-08 01:46:41','2026-10-08 20:44:33');
/*!40000 ALTER TABLE `karyawan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mentoring`
--

DROP TABLE IF EXISTS `mentoring`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mentoring` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_karyawan` bigint unsigned NOT NULL,
  `tahun` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `periode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `project` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_mentoring` enum('pending','completed','cancelled') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mentor` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `development` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target` date DEFAULT NULL,
  `monitoring` date DEFAULT NULL,
  `final` date DEFAULT NULL,
  `grow_goals` text COLLATE utf8mb4_unicode_ci,
  `grow_realitas` text COLLATE utf8mb4_unicode_ci,
  `grow_options` text COLLATE utf8mb4_unicode_ci,
  `grow_wrap_up` text COLLATE utf8mb4_unicode_ci,
  `grow_insight` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `competency` enum('Rendah','Tinggi') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `commitment` enum('Rendah','Tinggi') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mentoring_id_karyawan_foreign` (`id_karyawan`),
  CONSTRAINT `mentoring_id_karyawan_foreign` FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mentoring`
--

LOCK TABLES `mentoring` WRITE;
/*!40000 ALTER TABLE `mentoring` DISABLE KEYS */;
INSERT INTO `mentoring` VALUES (1,203,NULL,NULL,NULL,NULL,NULL,NULL,'2025-02-12',NULL,NULL,'s',NULL,NULL,NULL,NULL,'2026-10-08 19:11:36','2026-10-08 19:31:14',NULL,NULL),(2,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'sfs',NULL,NULL,NULL,NULL,'2026-10-08 19:38:03','2026-10-08 19:38:03',NULL,NULL);
/*!40000 ALTER TABLE `mentoring` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_09_07_012218_create_positions_table',1),(5,'2026_09_07_012219_create_departments_table',1),(6,'2026_09_07_012220_create_karyawan',1),(7,'2026_09_07_031950_add_role_to_users_table',1),(8,'2026_09_07_063749_create_strength_weakness',1),(9,'2026_09_07_064044_create_via_character',1),(10,'2026_09_07_065027_create_scarf_profile',1),(11,'2026_09_07_070230_create_assessment_report_table',1),(12,'2026_09_07_070906_create_mentoring',1),(13,'2026_09_07_074327_create_idp_table',1),(14,'2026_09_07_074644_create_idp_penilain_table',1),(15,'2026_09_07_075456_create_training',1),(16,'2026_09_07_075934_create_riwayat_training',1),(17,'2026_09_07_080943_create_sertifikasi',1),(18,'2026_09_07_082202_create_perjalanan_karir',1),(19,'2026_09_16_020944_add_competency_and_commitment_to_mentoring_table',1),(20,'2026_09_17_015001_add_status_and_last_login_to_users_table',1),(21,'2026_09_17_082644_add_eva_columns_to_riwayat_training_table',1),(22,'2026_09_21_031725_remove_evaluasi_from_training_table',1),(24,'2026_10_02_084856_add_npk_to_users_table',1),(25,'2026_10_06_020118_change_scarf_columns_to_decimal',1),(26,'2026_10_07_011057_create_permission_tables',2),(27,'2026_10_09_041318_add_project_to_mentorings_table',3);
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
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
INSERT INTO `model_has_permissions` VALUES (10,'App\\Models\\User',1),(11,'App\\Models\\User',1),(12,'App\\Models\\User',1),(13,'App\\Models\\User',1),(14,'App\\Models\\User',1),(15,'App\\Models\\User',1),(16,'App\\Models\\User',1),(17,'App\\Models\\User',1),(18,'App\\Models\\User',1),(19,'App\\Models\\User',1),(20,'App\\Models\\User',1),(21,'App\\Models\\User',1),(22,'App\\Models\\User',1),(23,'App\\Models\\User',1),(24,'App\\Models\\User',1),(25,'App\\Models\\User',1),(30,'App\\Models\\User',1),(31,'App\\Models\\User',1),(32,'App\\Models\\User',1),(33,'App\\Models\\User',1),(34,'App\\Models\\User',1),(35,'App\\Models\\User',1),(36,'App\\Models\\User',1),(37,'App\\Models\\User',1),(38,'App\\Models\\User',1),(39,'App\\Models\\User',1),(40,'App\\Models\\User',1),(41,'App\\Models\\User',1),(42,'App\\Models\\User',1),(43,'App\\Models\\User',1),(44,'App\\Models\\User',1),(45,'App\\Models\\User',1),(53,'App\\Models\\User',1),(54,'App\\Models\\User',1),(55,'App\\Models\\User',1),(56,'App\\Models\\User',1),(57,'App\\Models\\User',1),(58,'App\\Models\\User',1),(59,'App\\Models\\User',1),(60,'App\\Models\\User',1),(61,'App\\Models\\User',1),(62,'App\\Models\\User',1),(63,'App\\Models\\User',1),(64,'App\\Models\\User',1);
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
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `perjalanan_karir`
--

DROP TABLE IF EXISTS `perjalanan_karir`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `perjalanan_karir` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_karyawan` bigint unsigned NOT NULL,
  `department_id` bigint unsigned DEFAULT NULL,
  `tahun_karir` int DEFAULT NULL,
  `nama_posisi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipe_milestone` enum('aktif','tetap','promosi','rotasi','demosi','nonaktif') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_pencapaian` enum('riwayat/selesai','aktif/tercapai','proyeksi/target') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan_detail` text COLLATE utf8mb4_unicode_ci,
  `operational_award` text COLLATE utf8mb4_unicode_ci,
  `catatan_rekomendasi_atasan` text COLLATE utf8mb4_unicode_ci,
  `keputusan` enum('Promote to Next Grade','Ready for Promotion','Fit for Current Role','Extend Contract','Needs Further Development','Recommend for Rotation','Performance Improvement Plan','Terminate') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `perjalanan_karir_id_karyawan_foreign` (`id_karyawan`),
  KEY `perjalanan_karir_department_id_foreign` (`department_id`),
  CONSTRAINT `perjalanan_karir_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `perjalanan_karir_id_karyawan_foreign` FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perjalanan_karir`
--

LOCK TABLES `perjalanan_karir` WRITE;
/*!40000 ALTER TABLE `perjalanan_karir` DISABLE KEYS */;
/*!40000 ALTER TABLE `perjalanan_karir` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (10,'lihat_karyawan','web','2026-10-06 19:09:21','2026-10-06 19:09:21'),(11,'tambah_karyawan','web','2026-10-06 19:09:21','2026-10-06 19:09:21'),(12,'edit_karyawan','web','2026-10-06 19:09:21','2026-10-06 19:09:21'),(13,'hapus_karyawan','web','2026-10-06 19:09:21','2026-10-06 19:09:21'),(14,'lihat_departemen','web','2026-10-06 19:09:21','2026-10-06 19:09:21'),(15,'tambah_departemen','web','2026-10-06 19:09:21','2026-10-06 19:09:21'),(16,'edit_departemen','web','2026-10-06 19:09:21','2026-10-06 19:09:21'),(17,'hapus_departemen','web','2026-10-06 19:09:21','2026-10-06 19:09:21'),(18,'lihat_role','web','2026-10-06 19:09:21','2026-10-06 19:09:21'),(19,'tambah_role','web','2026-10-06 19:09:21','2026-10-06 19:09:21'),(20,'edit_role','web','2026-10-06 19:09:21','2026-10-06 19:09:21'),(21,'hapus_role','web','2026-10-06 19:09:21','2026-10-06 19:09:21'),(22,'lihat_user','web','2026-10-06 19:09:21','2026-10-06 19:09:21'),(23,'tambah_user','web','2026-10-06 19:09:21','2026-10-06 19:09:21'),(24,'edit_user','web','2026-10-06 19:09:21','2026-10-06 19:09:21'),(25,'hapus_user','web','2026-10-06 19:09:21','2026-10-06 19:09:21'),(30,'lihat_training','web','2026-10-06 19:17:42','2026-10-06 19:17:42'),(31,'tambah_training','web','2026-10-06 19:17:42','2026-10-06 19:17:42'),(32,'edit_training','web','2026-10-06 19:17:42','2026-10-06 19:17:42'),(33,'hapus_training','web','2026-10-06 19:17:42','2026-10-06 19:17:42'),(34,'lihat_mentoring','web','2026-10-06 19:17:42','2026-10-06 19:17:42'),(35,'tambah_mentoring','web','2026-10-06 19:17:42','2026-10-06 19:17:42'),(36,'edit_mentoring','web','2026-10-06 19:17:42','2026-10-06 19:17:42'),(37,'hapus_mentoring','web','2026-10-06 19:17:42','2026-10-06 19:17:42'),(38,'lihat_certification','web','2026-10-06 19:17:42','2026-10-06 19:17:42'),(39,'tambah_certification','web','2026-10-06 19:17:42','2026-10-06 19:17:42'),(40,'edit_certification','web','2026-10-06 19:17:42','2026-10-06 19:17:42'),(41,'hapus_certification','web','2026-10-06 19:17:42','2026-10-06 19:17:42'),(42,'lihat_assessment','web','2026-10-06 19:17:42','2026-10-06 19:17:42'),(43,'tambah_assessment','web','2026-10-06 19:17:42','2026-10-06 19:17:42'),(44,'edit_assessment','web','2026-10-06 19:17:42','2026-10-06 19:17:42'),(45,'hapus_assessment','web','2026-10-06 19:17:42','2026-10-06 19:17:42'),(53,'lihat_journey','web','2026-10-07 20:18:12','2026-10-07 20:18:12'),(54,'tambah_journey','web','2026-10-07 20:18:12','2026-10-07 20:18:12'),(55,'edit_journey','web','2026-10-07 20:18:12','2026-10-07 20:18:12'),(56,'hapus_journey','web','2026-10-07 20:18:12','2026-10-07 20:18:12'),(57,'lihat_jabatan','web','2026-10-08 17:43:02','2026-10-08 17:43:02'),(58,'tambah_jabatan','web','2026-10-08 17:43:02','2026-10-08 17:43:02'),(59,'edit_jabatan','web','2026-10-08 17:43:02','2026-10-08 17:43:02'),(60,'hapus_jabatan','web','2026-10-08 17:43:02','2026-10-08 17:43:02'),(61,'lihat_idp','web','2026-10-08 18:14:59','2026-10-08 18:14:59'),(62,'tambah_idp','web','2026-10-08 18:14:59','2026-10-08 18:14:59'),(63,'edit_idp','web','2026-10-08 18:14:59','2026-10-08 18:14:59'),(64,'hapus_idp','web','2026-10-08 18:14:59','2026-10-08 18:14:59');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `positions`
--

DROP TABLE IF EXISTS `positions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `positions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_jabatan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `positions`
--

LOCK TABLES `positions` WRITE;
/*!40000 ALTER TABLE `positions` DISABLE KEYS */;
INSERT INTO `positions` VALUES (1,'Department Head','2026-10-06 00:42:37','2026-10-06 00:42:37'),(2,'Staff','2026-10-06 00:42:42','2026-10-06 00:42:42'),(3,'Unit Head','2026-10-06 00:42:47','2026-10-06 00:42:47'),(4,'Group Head','2026-10-06 00:42:55','2026-10-06 00:42:55'),(5,'Section Head','2026-10-06 00:43:00','2026-10-06 00:43:00');
/*!40000 ALTER TABLE `positions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `riwayat_training`
--

DROP TABLE IF EXISTS `riwayat_training`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `riwayat_training` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_training` bigint unsigned NOT NULL,
  `id_karyawan` bigint unsigned NOT NULL,
  `kategori` enum('mandatory','skill','managerial') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_training` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_training` enum('completed','in_progress','remedial','not_started') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_training` date DEFAULT NULL,
  `tingkat_evaluasi` enum('eva1','eva2','eva3') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lampiran_training` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `eva1_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `eva1_lampiran` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `eva2_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `eva2_lampiran` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `eva3_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `eva3_lampiran` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `riwayat_training_id_training_foreign` (`id_training`),
  KEY `riwayat_training_id_karyawan_foreign` (`id_karyawan`),
  CONSTRAINT `riwayat_training_id_karyawan_foreign` FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `riwayat_training_id_training_foreign` FOREIGN KEY (`id_training`) REFERENCES `training` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `riwayat_training`
--

LOCK TABLES `riwayat_training` WRITE;
/*!40000 ALTER TABLE `riwayat_training` DISABLE KEYS */;
/*!40000 ALTER TABLE `riwayat_training` ENABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
INSERT INTO `role_has_permissions` VALUES (53,1),(54,1),(55,1),(56,1),(61,1),(62,1),(63,1),(64,1);
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
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Admin','web','2026-10-06 18:11:36','2026-10-06 18:11:36'),(2,'Head','web','2026-10-06 18:11:36','2026-10-06 18:11:36');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `scarf_profile`
--

DROP TABLE IF EXISTS `scarf_profile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `scarf_profile` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_karyawan` bigint unsigned NOT NULL,
  `status` decimal(5,2) DEFAULT NULL,
  `certainty` decimal(5,2) DEFAULT NULL,
  `autonomy` decimal(5,2) DEFAULT NULL,
  `relatedness` decimal(5,2) DEFAULT NULL,
  `fairness` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `scarf_profile_id_karyawan_foreign` (`id_karyawan`),
  CONSTRAINT `scarf_profile_id_karyawan_foreign` FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scarf_profile`
--

LOCK TABLES `scarf_profile` WRITE;
/*!40000 ALTER TABLE `scarf_profile` DISABLE KEYS */;
/*!40000 ALTER TABLE `scarf_profile` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sertifikasi`
--

DROP TABLE IF EXISTS `sertifikasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sertifikasi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_karyawan` bigint unsigned NOT NULL,
  `nama_sertifikasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `penerbit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `terbit` date DEFAULT NULL,
  `kadaluarsa` date DEFAULT NULL,
  `status_sertifikat` enum('aktif','nonaktif') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lampiran_sertifikasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sertifikasi_id_karyawan_foreign` (`id_karyawan`),
  CONSTRAINT `sertifikasi_id_karyawan_foreign` FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sertifikasi`
--

LOCK TABLES `sertifikasi` WRITE;
/*!40000 ALTER TABLE `sertifikasi` DISABLE KEYS */;
INSERT INTO `sertifikasi` VALUES (1,1,'Komunikasi','Jawa','2026-05-12','2028-05-12','aktif','/HRG/Sertifikat/widia/QuJJPn1UtU8yh0arnl4xPo1JIzter0nAkyfjXDUV.pdf','2026-10-06 16:46:20','2026-10-06 16:46:20');
/*!40000 ALTER TABLE `sertifikasi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('cMYAJhCZAum4YFk4u5wG8UVCkyzkfxkeDbVJoQAx',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJEeWhKcW1ZMk9mT1BTWE5aZ1JvcmlIdnp1Q1JPT2psQTViM0ltcFVGIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2Fzc2Vzc21lbnQtcmVwb3J0In0sIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvaWRwXC80Iiwicm91dGUiOiJpZHAuc2hvdyJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxfQ==',1791529329),('pSs0j8VXgt4hrpYej6XlDX3Ho2rRkraWnKpVNqqr',4,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJmSzhTTE5iVDdNc1pLamxlUlBWYXNSREIyT1BxQlJQQXhMbGdQOXZBIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL3VzZXIifSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC91c2VyLXRyYWluaW5nXC8yMDMiLCJyb3V0ZSI6InVzZXItdHJhaW5pbmcuc2hvdyJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjo0fQ==',1791530437);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `strength_weakness`
--

DROP TABLE IF EXISTS `strength_weakness`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `strength_weakness` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_karyawan` bigint unsigned NOT NULL,
  `strength` text COLLATE utf8mb4_unicode_ci,
  `weakness` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `strength_weakness_id_karyawan_foreign` (`id_karyawan`),
  CONSTRAINT `strength_weakness_id_karyawan_foreign` FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `strength_weakness`
--

LOCK TABLES `strength_weakness` WRITE;
/*!40000 ALTER TABLE `strength_weakness` DISABLE KEYS */;
/*!40000 ALTER TABLE `strength_weakness` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `training`
--

DROP TABLE IF EXISTS `training`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `training` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_karyawan` bigint unsigned NOT NULL,
  `target_mandatory` int DEFAULT NULL,
  `target_skill` int DEFAULT NULL,
  `target_managerial` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `training_id_karyawan_foreign` (`id_karyawan`),
  CONSTRAINT `training_id_karyawan_foreign` FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `training`
--

LOCK TABLES `training` WRITE;
/*!40000 ALTER TABLE `training` DISABLE KEYS */;
INSERT INTO `training` VALUES (1,2,0,0,0,'2026-10-06 19:53:23','2026-10-06 19:53:23'),(2,203,1,0,0,'2026-10-08 23:26:47','2026-10-08 23:26:47');
/*!40000 ALTER TABLE `training` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `npk` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `status` enum('aktif','nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `last_login_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_npk_unique` (`npk`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'ADMIN001','Admin HR','admin@admin.com',NULL,'$2y$12$aXLU9s6XAchCgFP1psXz3eMjHpHi7rjevSs2Fs9lhLHbeNIzlY8y.','Admin','aktif','2026-10-08 17:12:26',NULL,'2026-10-06 00:31:18','2026-10-08 17:12:26'),(3,'12346','Siti Aminah','siti.aminah@example.com',NULL,'$2y$12$vP9Kd0f2s2kSANewPMlMbevaku49qWyc4AGDbB5PhbxPZdsq99LCK','Head','aktif','2026-10-08 18:33:57',NULL,'2026-10-06 17:09:52','2026-10-08 18:33:57'),(4,'12345','Budi Santoso','budi.santoso@example.com',NULL,'$2y$12$9Ew73.Vi5WyktqVXhATI5.nzn9PH0.ZvexZNjfM1cK9.eOnzFxPnO','Head','aktif','2026-10-08 23:16:47',NULL,'2026-10-08 20:39:55','2026-10-08 23:16:47');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `via_character`
--

DROP TABLE IF EXISTS `via_character`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `via_character` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_karyawan` bigint unsigned NOT NULL,
  `overuse_strength` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `underuse_strength` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `via_character_id_karyawan_foreign` (`id_karyawan`),
  CONSTRAINT `via_character_id_karyawan_foreign` FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `via_character`
--

LOCK TABLES `via_character` WRITE;
/*!40000 ALTER TABLE `via_character` DISABLE KEYS */;
INSERT INTO `via_character` VALUES (1,2,'sda','sca','2026-10-07 01:42:57','2026-10-07 01:42:57');
/*!40000 ALTER TABLE `via_character` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-13  8:09:22
