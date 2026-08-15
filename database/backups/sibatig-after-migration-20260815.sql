-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: sibatig
-- ------------------------------------------------------
-- Server version	8.4.3

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
-- Table structure for table `documents`
--

DROP TABLE IF EXISTS `documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `year` smallint unsigned NOT NULL DEFAULT '2026',
  `category` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `source` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document_date` date DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size` bigint unsigned DEFAULT NULL,
  `spt_record_id` bigint unsigned DEFAULT NULL,
  `uploaded_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `documents_spt_source_unique` (`spt_record_id`,`source`),
  KEY `documents_uploaded_by_foreign` (`uploaded_by`),
  KEY `documents_year_category_index` (`year`,`category`),
  KEY `documents_category_document_date_index` (`category`,`document_date`),
  CONSTRAINT `documents_spt_record_id_foreign` FOREIGN KEY (`spt_record_id`) REFERENCES `spt_records` (`id`) ON DELETE SET NULL,
  CONSTRAINT `documents_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documents`
--

LOCK TABLES `documents` WRITE;
/*!40000 ALTER TABLE `documents` DISABLE KEYS */;
/*!40000 ALTER TABLE `documents` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_08_11_155630_add_sibatig_fields_to_users_table',1),(5,'2026_08_11_155639_create_team_members_table',1),(6,'2026_08_11_155646_create_pkpt_activities_table',1),(7,'2026_08_11_155648_create_pkpt_activity_team_member_table',1),(8,'2026_08_11_155650_create_monitoring_evaluations_table',1),(9,'2026_08_11_155715_create_website_settings_table',1),(10,'2026_08_12_000001_add_theme_fields_to_website_settings_table',1),(11,'2026_08_12_000002_create_spt_records_table',1),(12,'2026_08_12_000003_update_inspectorate_organization_name',1),(13,'2026_08_12_000004_add_performance_indexes',1),(14,'2026_08_14_000001_create_documents_table',1),(15,'2026_08_14_000002_add_source_to_documents_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `monitoring_evaluations`
--

DROP TABLE IF EXISTS `monitoring_evaluations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `monitoring_evaluations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pkpt_activity_id` bigint unsigned NOT NULL,
  `evaluation_date` date NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `progress` tinyint unsigned NOT NULL DEFAULT '0',
  `stage` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `actual_start` date DEFAULT NULL,
  `actual_end` date DEFAULT NULL,
  `achievement` text COLLATE utf8mb4_unicode_ci,
  `obstacles` text COLLATE utf8mb4_unicode_ci,
  `follow_up` text COLLATE utf8mb4_unicode_ci,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `monitoring_evaluations_updated_by_foreign` (`updated_by`),
  KEY `monitoring_evaluations_evaluation_date_index` (`evaluation_date`),
  KEY `monitoring_evaluations_status_index` (`status`),
  KEY `monitoring_activity_date_index` (`pkpt_activity_id`,`evaluation_date`),
  CONSTRAINT `monitoring_evaluations_pkpt_activity_id_foreign` FOREIGN KEY (`pkpt_activity_id`) REFERENCES `pkpt_activities` (`id`) ON DELETE CASCADE,
  CONSTRAINT `monitoring_evaluations_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `monitoring_evaluations`
--

LOCK TABLES `monitoring_evaluations` WRITE;
/*!40000 ALTER TABLE `monitoring_evaluations` DISABLE KEYS */;
INSERT INTO `monitoring_evaluations` VALUES (1,8,'2026-01-12','selesai',100,'Laporan selesai','2026-01-05','2026-01-09','Kegiatan dan pelaporan telah diselesaikan.',NULL,'Arsipkan dokumen hasil pengawasan.',NULL,'2026-08-14 09:58:05','2026-08-14 09:58:05',NULL),(2,3,'2026-02-09','selesai',100,'Laporan selesai','2026-01-12','2026-01-30','Kegiatan dan pelaporan telah diselesaikan.',NULL,'Arsipkan dokumen hasil pengawasan.',NULL,'2026-08-14 09:58:05','2026-08-14 09:58:05',NULL),(3,15,'2026-04-06','selesai',100,'Laporan selesai','2026-01-22','2026-04-02','Kegiatan dan pelaporan telah diselesaikan.',NULL,'Arsipkan dokumen hasil pengawasan.',NULL,'2026-08-14 09:58:05','2026-08-14 09:58:05',NULL),(4,23,'2026-02-23','selesai',100,'Laporan selesai','2026-02-09','2026-02-13','Kegiatan dan pelaporan telah diselesaikan.',NULL,'Arsipkan dokumen hasil pengawasan.',NULL,'2026-08-14 09:58:05','2026-08-14 09:58:05',NULL),(5,1,'2026-06-08','selesai',100,'Laporan selesai','2026-01-12','2026-06-05','Kegiatan dan pelaporan telah diselesaikan.',NULL,'Arsipkan dokumen hasil pengawasan.',NULL,'2026-08-14 09:58:05','2026-08-14 09:58:05',NULL),(6,16,'2026-07-03','selesai',100,'Laporan selesai','2026-02-18','2026-07-03','Kegiatan dan pelaporan telah diselesaikan.',NULL,'Arsipkan dokumen hasil pengawasan.',NULL,'2026-08-14 09:58:05','2026-08-14 09:58:05',NULL),(7,25,'2026-07-24','selesai',100,'Laporan selesai','2026-06-08','2026-07-24','Kegiatan dan pelaporan telah diselesaikan.',NULL,'Arsipkan dokumen hasil pengawasan.',NULL,'2026-08-14 09:58:05','2026-08-14 09:58:05',NULL),(8,11,'2026-06-30','selesai',100,'Laporan selesai','2026-06-22','2026-06-26','Kegiatan dan pelaporan telah diselesaikan.',NULL,'Arsipkan dokumen hasil pengawasan.',NULL,'2026-08-14 09:58:06','2026-08-14 09:58:06',NULL),(9,14,'2026-07-31','selesai',100,'Laporan selesai','2026-07-13','2026-07-29','Kegiatan dan pelaporan telah diselesaikan.',NULL,'Arsipkan dokumen hasil pengawasan.',NULL,'2026-08-14 09:58:06','2026-08-14 09:58:06',NULL),(10,12,'2026-07-20','selesai',100,'Laporan selesai','2026-07-13','2026-07-17','Kegiatan dan pelaporan telah diselesaikan.',NULL,'Arsipkan dokumen hasil pengawasan.',NULL,'2026-08-14 09:58:06','2026-08-14 09:58:06',NULL),(11,17,'2026-08-10','sedang_berjalan',75,'Pelaksanaan reviu','2026-04-13',NULL,'Pelaksanaan berjalan sesuai SPT terakhir.',NULL,'Pantau penyelesaian laporan dan tindak lanjut.',NULL,'2026-08-14 09:58:06','2026-08-14 09:58:06',NULL);
/*!40000 ALTER TABLE `monitoring_evaluations` ENABLE KEYS */;
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
-- Table structure for table `pkpt_activities`
--

DROP TABLE IF EXISTS `pkpt_activities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pkpt_activities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `year` smallint unsigned NOT NULL DEFAULT '2026',
  `source_number` smallint unsigned NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `assignment_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `assignment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `audit_object` text COLLATE utf8mb4_unicode_ci,
  `executor` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'IRBAN III',
  `apip_count` smallint unsigned NOT NULL DEFAULT '0',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum_dilaksanakan',
  `progress` tinyint unsigned NOT NULL DEFAULT '0',
  `planned_start` date DEFAULT NULL,
  `planned_end` date DEFAULT NULL,
  `actual_start` date DEFAULT NULL,
  `actual_end` date DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pkpt_activities_year_source_number_unique` (`year`,`source_number`),
  KEY `pkpt_activities_created_by_foreign` (`created_by`),
  KEY `pkpt_activities_updated_by_foreign` (`updated_by`),
  KEY `pkpt_activities_year_index` (`year`),
  KEY `pkpt_activities_category_index` (`category`),
  KEY `pkpt_activities_status_index` (`status`),
  KEY `pkpt_year_status_index` (`year`,`status`),
  KEY `pkpt_year_progress_index` (`year`,`progress`),
  KEY `pkpt_year_category_index` (`year`,`category`),
  CONSTRAINT `pkpt_activities_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pkpt_activities_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pkpt_activities`
--

LOCK TABLES `pkpt_activities` WRITE;
/*!40000 ALTER TABLE `pkpt_activities` DISABLE KEYS */;
INSERT INTO `pkpt_activities` VALUES (1,2026,1,'audit','Audit Kinerja','Audit Program pengelolaan dan pengembangan sistem drainase','DPUPR, Dinas Perkim','IRBAN III',7,'selesai',100,NULL,NULL,'2026-01-12','2026-06-05',NULL,NULL,NULL,'2026-08-14 09:58:05','2026-08-14 09:58:05',NULL),(2,2026,4,'audit','Audit Ketaatan','Audit Program perencanaan, pengendalian dan evaluasi pembangunan daerah','Badan Perencanaan Pembangunan Daerah','IRBAN III',20,'belum_dilaksanakan',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-14 09:58:05','2026-08-14 09:58:05',NULL),(3,2026,7,'audit','Audit Dengan Tujuan Tertentu','Audit Pekerjaan Fisik Proyek Strategis','DPUPR','IRBAN III',7,'selesai',100,NULL,NULL,'2026-01-12','2026-01-30',NULL,NULL,NULL,'2026-08-14 09:58:05','2026-08-14 09:58:05',NULL),(4,2026,8,'audit','Audit Dengan Tujuan Tertentu','Audit Program Pemberdayaan Masyarakat Desa dan Kelurahan','Kecamatan Kota','IRBAN III',7,'belum_dilaksanakan',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-14 09:58:05','2026-08-14 09:58:05',NULL),(5,2026,9,'audit','Probity Audit','Program/Kegiatan sebagaimana yang tercantum dalam SK Walikota tentang Proyek Strategis Pemerintah Kota Kediri Tahun 2026','OPD dalam SK Proyek Strategis Pemerintah Kota Kediri Tahun 2026','IRBAN I, II, III',21,'belum_dilaksanakan',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-14 09:58:05','2026-08-14 09:58:05',NULL),(6,2026,13,'reviu','Reviu','Reviu RKA Tahun 2027','Pemerintah Daerah Kota Kediri','IRBAN I, II, III',21,'belum_dilaksanakan',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-14 09:58:05','2026-08-14 09:58:05',NULL),(7,2026,14,'reviu','Reviu','Reviu RKA Perubahan Tahun 2026','Pemerintah Daerah Kota Kediri','IRBAN I, II, III',21,'belum_dilaksanakan',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-14 09:58:05','2026-08-14 09:58:05',NULL),(8,2026,21,'reviu','Reviu','Reviu Register Risiko Tahun 2026','Pemerintah Daerah Kota Kediri','IRBAN I, II, III',21,'selesai',100,NULL,NULL,'2026-01-05','2026-01-09',NULL,NULL,NULL,'2026-08-14 09:58:05','2026-08-14 09:58:05',NULL),(9,2026,22,'reviu','Reviu','Reviu PKPT Tahun 2026','Pemerintah Daerah Kota Kediri','IRBAN III',10,'belum_dilaksanakan',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-14 09:58:05','2026-08-14 09:58:05',NULL),(10,2026,27,'reviu','Reviu','Reviu Renja','Pemerintah Daerah Kota Kediri','IRBAN III',7,'belum_dilaksanakan',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-14 09:58:05','2026-08-14 09:58:05',NULL),(11,2026,28,'reviu','Reviu','Reviu RKPD','Pemerintah Daerah Kota Kediri','IRBAN III',7,'selesai',100,NULL,NULL,'2026-06-22','2026-06-26',NULL,NULL,NULL,'2026-08-14 09:58:05','2026-08-14 09:58:06',NULL),(12,2026,29,'reviu','Reviu','Reviu RKPD-P','Pemerintah Daerah Kota Kediri','IRBAN III',7,'selesai',100,NULL,NULL,'2026-07-13','2026-07-17',NULL,NULL,NULL,'2026-08-14 09:58:05','2026-08-14 09:58:06',NULL),(13,2026,30,'reviu','Reviu','Pemeriksaan Atas Honorarium Tahun Sebelumnya / Reviu Atas Honorarium Tahun Berjalan dengan Nilai Honorarium Tertinggi','Pemerintah Daerah Kota Kediri','IRBAN III',7,'belum_dilaksanakan',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-14 09:58:05','2026-08-14 09:58:05',NULL),(14,2026,31,'reviu','Reviu','Reviu atas SSH dan ASB','Pemerintah Daerah Kota Kediri','IRBAN III',7,'selesai',100,NULL,NULL,'2026-07-13','2026-07-29',NULL,NULL,NULL,'2026-08-14 09:58:05','2026-08-14 09:58:06',NULL),(15,2026,32,'reviu','Reviu','Reviu DAK','Pemerintah Daerah Kota Kediri','IRBAN III',7,'selesai',100,NULL,NULL,'2026-01-22','2026-04-02',NULL,NULL,NULL,'2026-08-14 09:58:05','2026-08-14 09:58:05',NULL),(16,2026,33,'reviu','Reviu','Reviu E-Audit PBJ','Pemerintah Daerah Kota Kediri','IRBAN III',7,'selesai',100,NULL,NULL,'2026-02-18','2026-07-03',NULL,NULL,NULL,'2026-08-14 09:58:05','2026-08-14 09:58:05',NULL),(17,2026,34,'reviu','Reviu','Reviu HPS','Pemerintah Daerah Kota Kediri','IRBAN III',7,'sedang_berjalan',75,NULL,NULL,'2026-04-13',NULL,NULL,NULL,NULL,'2026-08-14 09:58:05','2026-08-14 09:58:06',NULL),(18,2026,36,'monitoring','Monitoring','Pendampingan pemantauan Mewujudkan ketahanan pangan nasional','DKPP','IRBAN III',7,'belum_dilaksanakan',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-14 09:58:05','2026-08-14 09:58:05',NULL),(19,2026,37,'monitoring','Monitoring','Pendampingan pemantauan Optimalisasi Pelaksanaan Pengentasan Kemiskinan dan Penghapusan Kemiskinan Ekstrem','Pemerintah Kota Kediri','IRBAN I, II, III',21,'belum_dilaksanakan',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-14 09:58:05','2026-08-14 09:58:05',NULL),(20,2026,39,'monitoring','Monitoring','Pendampingan pemantauan Makan Bergizi Gratis','Pemerintah Kota Kediri','IRBAN I, II, III',21,'belum_dilaksanakan',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-14 09:58:05','2026-08-14 09:58:05',NULL),(21,2026,44,'evaluasi','Evaluasi','Evaluasi Register Risiko Tahun 2025','Pemerintah Daerah Kota Kediri','IRBAN I, II, III',21,'belum_dilaksanakan',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-14 09:58:05','2026-08-14 09:58:05',NULL),(22,2026,47,'evaluasi','Evaluasi','Evaluasi SAKIP Tahun 2026','Pemerintah Daerah Kota Kediri','IRBAN I, II, III',7,'belum_dilaksanakan',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-14 09:58:05','2026-08-14 09:58:05',NULL),(23,2026,48,'pendampingan','Pendampingan','Pendampingan Dana BOS Dinas Pendidikan Tahap II Tahun 2025','Dinas Pendidikan','IRBAN I, II, III',7,'selesai',100,NULL,NULL,'2026-02-09','2026-02-13',NULL,NULL,NULL,'2026-08-14 09:58:05','2026-08-14 09:58:05',NULL),(24,2026,49,'pendampingan','Pendampingan','Pendampingan Dana BOS Dinas Pendidikan Tahap I Tahun 2026','Dinas Pendidikan','IRBAN I, II, III',7,'belum_dilaksanakan',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-14 09:58:05','2026-08-14 09:58:05',NULL),(25,2026,50,'mandatory','Mandatory','Maturitas SPIP','Pemerintah Daerah Kota Kediri','IRBAN III',7,'selesai',100,NULL,NULL,'2026-06-08','2026-07-24',NULL,NULL,NULL,'2026-08-14 09:58:05','2026-08-14 09:58:05',NULL),(26,2026,51,'mandatory','Mandatory','Penilaian Mandiri Kapabilitas APIP','Pemerintah Kota Kediri','IRBAN III',7,'belum_dilaksanakan',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-14 09:58:05','2026-08-14 09:58:05',NULL),(27,2026,52,'mandatory','Mandatory','Fasilitasi Manajemen Risiko','Pemerintah Daerah Kota Kediri','IRBAN III',10,'belum_dilaksanakan',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-14 09:58:05','2026-08-14 09:58:05',NULL),(28,2026,53,'mandatory','Mandatory','PPBR Tahun 2027',NULL,'IRBAN III',12,'belum_dilaksanakan',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-14 09:58:05','2026-08-14 09:58:05',NULL),(29,2026,59,'mandatory','Mandatory','Penyusunan PKPT 2027',NULL,'IRBAN I, II, III',27,'belum_dilaksanakan',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-14 09:58:05','2026-08-14 09:58:05',NULL);
/*!40000 ALTER TABLE `pkpt_activities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pkpt_activity_team_member`
--

DROP TABLE IF EXISTS `pkpt_activity_team_member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pkpt_activity_team_member` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pkpt_activity_id` bigint unsigned NOT NULL,
  `team_member_id` bigint unsigned NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Anggota',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pkpt_activity_team_member_pkpt_activity_id_team_member_id_unique` (`pkpt_activity_id`,`team_member_id`),
  KEY `pkpt_activity_team_member_team_member_id_foreign` (`team_member_id`),
  CONSTRAINT `pkpt_activity_team_member_pkpt_activity_id_foreign` FOREIGN KEY (`pkpt_activity_id`) REFERENCES `pkpt_activities` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pkpt_activity_team_member_team_member_id_foreign` FOREIGN KEY (`team_member_id`) REFERENCES `team_members` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=204 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pkpt_activity_team_member`
--

LOCK TABLES `pkpt_activity_team_member` WRITE;
/*!40000 ALTER TABLE `pkpt_activity_team_member` DISABLE KEYS */;
INSERT INTO `pkpt_activity_team_member` VALUES (1,1,1,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(2,1,2,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(3,1,3,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(4,1,4,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(5,1,5,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(6,1,6,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(7,1,7,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(8,2,1,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(9,2,2,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(10,2,3,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(11,2,4,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(12,2,5,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(13,2,6,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(14,2,7,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(15,3,1,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(16,3,2,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(17,3,3,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(18,3,4,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(19,3,5,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(20,3,6,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(21,3,7,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(22,4,1,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(23,4,2,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(24,4,3,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(25,4,4,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(26,4,5,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(27,4,6,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(28,4,7,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(29,5,1,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(30,5,2,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(31,5,3,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(32,5,4,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(33,5,5,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(34,5,6,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(35,5,7,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(36,6,1,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(37,6,2,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(38,6,3,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(39,6,4,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(40,6,5,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(41,6,6,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(42,6,7,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(43,7,1,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(44,7,2,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(45,7,3,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(46,7,4,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(47,7,5,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(48,7,6,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(49,7,7,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(50,8,1,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(51,8,2,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(52,8,3,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(53,8,4,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(54,8,5,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(55,8,6,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(56,8,7,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(57,9,1,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(58,9,2,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(59,9,3,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(60,9,4,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(61,9,5,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(62,9,6,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(63,9,7,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(64,10,1,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(65,10,2,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(66,10,3,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(67,10,4,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(68,10,5,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(69,10,6,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(70,10,7,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(71,11,1,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(72,11,2,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(73,11,3,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(74,11,4,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(75,11,5,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(76,11,6,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(77,11,7,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(78,12,1,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(79,12,2,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(80,12,3,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(81,12,4,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(82,12,5,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(83,12,6,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(84,12,7,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(85,13,1,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(86,13,2,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(87,13,3,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(88,13,4,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(89,13,5,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(90,13,6,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(91,13,7,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(92,14,1,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(93,14,2,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(94,14,3,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(95,14,4,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(96,14,5,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(97,14,6,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(98,14,7,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(99,15,1,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(100,15,2,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(101,15,3,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(102,15,4,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(103,15,5,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(104,15,6,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(105,15,7,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(106,16,1,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(107,16,2,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(108,16,3,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(109,16,4,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(110,16,5,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(111,16,6,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(112,16,7,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(113,17,1,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(114,17,2,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(115,17,3,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(116,17,4,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(117,17,5,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(118,17,6,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(119,17,7,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(120,18,1,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(121,18,2,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(122,18,3,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(123,18,4,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(124,18,5,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(125,18,6,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(126,18,7,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(127,19,1,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(128,19,2,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(129,19,3,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(130,19,4,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(131,19,5,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(132,19,6,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(133,19,7,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(134,20,1,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(135,20,2,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(136,20,3,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(137,20,4,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(138,20,5,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(139,20,6,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(140,20,7,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(141,21,1,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(142,21,2,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(143,21,3,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(144,21,4,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(145,21,5,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(146,21,6,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(147,21,7,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(148,22,1,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(149,22,2,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(150,22,3,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(151,22,4,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(152,22,5,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(153,22,6,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(154,22,7,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(155,23,1,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(156,23,2,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(157,23,3,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(158,23,4,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(159,23,5,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(160,23,6,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(161,23,7,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(162,24,1,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(163,24,2,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(164,24,3,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(165,24,4,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(166,24,5,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(167,24,6,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(168,24,7,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(169,25,1,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(170,25,2,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(171,25,3,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(172,25,4,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(173,25,5,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(174,25,6,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(175,25,7,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(176,26,1,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(177,26,2,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(178,26,3,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(179,26,4,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(180,26,5,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(181,26,6,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(182,26,7,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(183,27,1,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(184,27,2,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(185,27,3,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(186,27,4,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(187,27,5,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(188,27,6,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(189,27,7,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(190,28,1,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(191,28,2,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(192,28,3,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(193,28,4,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(194,28,5,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(195,28,6,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(196,28,7,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(197,29,1,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(198,29,2,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(199,29,3,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(200,29,4,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(201,29,5,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(202,29,6,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05'),(203,29,7,'Anggota','2026-08-14 09:58:05','2026-08-14 09:58:05');
/*!40000 ALTER TABLE `pkpt_activity_team_member` ENABLE KEYS */;
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
INSERT INTO `sessions` VALUES ('Aeji0MuX0qKcyL19pHYtLrSuGxMx3ZrJm6bfkDRL',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; id-ID) WindowsPowerShell/5.1.26100.9168','eyJfdG9rZW4iOiJxNVpEblo4ejJxRU5CYjY4TEE2MmNPdmp5NFB0RkpaQ2pJZHpZaTg2IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pblwvbG9naW4iLCJyb3V0ZSI6ImZpbGFtZW50LmFkbWluLmF1dGgubG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1786701159),('neEy0klKcvTRVIiyVG9HKrp80LL8cqQDAPbOtYJn',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','eyJfdG9rZW4iOiI2cEVkRUhBZE1zMXJsS3VsN3llb0NQYllmUlVJN3RQUzN4Y1ZLT081IiwidXJsIjpbXSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pblwvcGtwdC1hY3Rpdml0aWVzXC8xXC9lZGl0Iiwicm91dGUiOiJmaWxhbWVudC5hZG1pbi5yZXNvdXJjZXMucGtwdC1hY3Rpdml0aWVzLmVkaXQifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwicGFzc3dvcmRfaGFzaF93ZWIiOiJjOWI2NTEzNzU5OGFjMTlhOWZlYjQ3N2ZhYjM1ODA5MDYwZWM2NTBhYTFhYWQwMjQxMzYwNGJhNDE3YmFhYTZhIiwidGFibGVzIjp7ImFkMGViMjA3ODIyM2Q2MTQwNDI4ZWQ3NmUyNjA2ZTMzX2NvbHVtbnMiOlt7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoicGtwdEFjdGl2aXR5LnNvdXJjZV9udW1iZXIiLCJsYWJlbCI6Ik5vLiBQS1BUIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6InBrcHRBY3Rpdml0eS5hc3NpZ25tZW50IiwibGFiZWwiOiJLZWdpYXRhbiIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJldmFsdWF0aW9uX2RhdGUiLCJsYWJlbCI6IlRhbmdnYWwgZXZhbHVhc2kiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoic3RhdHVzIiwibGFiZWwiOiJTdGF0dXMiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoicHJvZ3Jlc3MiLCJsYWJlbCI6IlByb2dyZXMiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoic3RhZ2UiLCJsYWJlbCI6IlRhaGFwYW4iLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjp0cnVlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOmZhbHNlfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoidXBkYXRlci5uYW1lIiwibGFiZWwiOiJEaXBlcmJhcnVpIG9sZWgiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6ZmFsc2UsImlzVG9nZ2xlYWJsZSI6dHJ1ZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0Ijp0cnVlfV0sIjczNzBhOWI4OWZjNDE5ZjFkOTZkODU4YTZjZjkyZWRmX2NvbHVtbnMiOlt7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoic291cmNlX251bWJlciIsImxhYmVsIjoiTm8uIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6ImNhdGVnb3J5IiwibGFiZWwiOiJLYXRlZ29yaSIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJhc3NpZ25tZW50IiwibGFiZWwiOiJQZW51Z2FzYW4iLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoiYXVkaXRfb2JqZWN0IiwibGFiZWwiOiJPYnJpayIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOnRydWUsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6ZmFsc2V9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJzdGF0dXMiLCJsYWJlbCI6IlN0YXR1cyIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJwcm9ncmVzcyIsImxhYmVsIjoiUHJvZ3JlcyIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJleGVjdXRvciIsImxhYmVsIjoiUGVsYWtzYW5hIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6dHJ1ZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpmYWxzZX0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6Im1vbml0b3JpbmdfZXZhbHVhdGlvbnNfY291bnQiLCJsYWJlbCI6Ik1vbmV2IiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6InllYXIiLCJsYWJlbCI6IlRhaHVuIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOmZhbHNlLCJpc1RvZ2dsZWFibGUiOnRydWUsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6dHJ1ZX1dLCI3MzcwYTliODlmYzQxOWYxZDk2ZDg1OGE2Y2Y5MmVkZl9wZXJfcGFnZSI6IjUwIiwiYmI0NGMwYThhODBlYjhjYzY3NTViMzFmNDNhMzZjOGVfY29sdW1ucyI6W3sidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJzb3VyY2VfbnVtYmVyIiwibGFiZWwiOiJOby4iLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoiZG9jdW1lbnRfbnVtYmVyIiwibGFiZWwiOiJOb21vciBTUFQiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoiZG9jdW1lbnRfZGF0ZSIsImxhYmVsIjoiVGFuZ2dhbCIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJzdWJqZWN0IiwibGFiZWwiOiJVcmFpYW4gcGVudWdhc2FuIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6ImFzc2lnbm1lbnRfdHlwZSIsImxhYmVsIjoiSmVuaXMiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoicGtwdEFjdGl2aXR5LnNvdXJjZV9udW1iZXIiLCJsYWJlbCI6IlBLUFQiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoic3RhdHVzIiwibGFiZWwiOiJTdGF0dXMiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoiZG9jdW1lbnRzX2NvdW50IiwibGFiZWwiOiJEb2t1bWVuIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6InN0YXJ0X2RhdGUiLCJsYWJlbCI6IlBlbGFrc2FuYWFuIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6dHJ1ZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpmYWxzZX0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6ImVuZF9kYXRlIiwibGFiZWwiOiJTZWxlc2FpIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6dHJ1ZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpmYWxzZX1dLCI4ZmIxYjExY2E4ZDg0NDk5NWZkYzAwOTcxNzc1ZDZhZl9jb2x1bW5zIjpbeyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6ImNhdGVnb3J5IiwibGFiZWwiOiJLYXRlZ29yaSIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJ0aXRsZSIsImxhYmVsIjoiSnVkdWwgZG9rdW1lbiIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJkb2N1bWVudF9udW1iZXIiLCJsYWJlbCI6Ik5vbW9yIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6dHJ1ZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpmYWxzZX0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6InNwdFJlY29yZC5kb2N1bWVudF9udW1iZXIiLCJsYWJlbCI6IlJla2FwIFNQVCIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOnRydWUsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6ZmFsc2V9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJkb2N1bWVudF9kYXRlIiwibGFiZWwiOiJUYW5nZ2FsIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6Im9yaWdpbmFsX25hbWUiLCJsYWJlbCI6IkZpbGUiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoiZmlsZV9zaXplIiwibGFiZWwiOiJVa3VyYW4iLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoidXBsb2FkZXIubmFtZSIsImxhYmVsIjoiUGVuZ3VuZ2dhaCIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjpmYWxzZSwiaXNUb2dnbGVhYmxlIjp0cnVlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOnRydWV9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJjcmVhdGVkX2F0IiwibGFiZWwiOiJEaXVuZ2dhaCIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOnRydWUsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6ZmFsc2V9XSwiYzM4NjgzZTA4NjI3OGVlYTAzOWMxY2M0OTYyN2RlYzhfY29sdW1ucyI6W3sidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJzb3J0X29yZGVyIiwibGFiZWwiOiJVcnV0IiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6ImZ1bGxfbmFtZSIsImxhYmVsIjoiTmFtYSIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJuaXAiLCJsYWJlbCI6Ik5JUCIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJyYW5rIiwibGFiZWwiOiJQYW5na2F0IiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6ImlzX2xlYWRlciIsImxhYmVsIjoiUGltcGluYW4iLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoiaXNfYWN0aXZlIiwibGFiZWwiOiJBa3RpZiIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJwa3B0X2FjdGl2aXRpZXNfY291bnQiLCJsYWJlbCI6IlBLUFQiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfV0sImE4YjMxMGEwMThhMjY0Zjc3OGQ3NzVhYTc3YjE1YmZiX2NvbHVtbnMiOlt7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoic2l0ZV9uYW1lIiwibGFiZWwiOiJOYW1hIGFwbGlrYXNpIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6Im9yZ2FuaXphdGlvbl9uYW1lIiwibGFiZWwiOiJPcmdhbmlzYXNpIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6ImFjdGl2ZV95ZWFyIiwibGFiZWwiOiJUYWh1biBha3RpZiIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJwcmltYXJ5X2NvbG9yIiwibGFiZWwiOiJXYXJuYSB1dGFtYSIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJtYWludGVuYW5jZV9tb2RlIiwibGFiZWwiOiJQZW1lbGloYXJhYW4iLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoidXBkYXRlZF9hdCIsImxhYmVsIjoiRGlwZXJiYXJ1aSIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9XSwiZTY0NDgzM2Y0ZTRlMDg3MTIzMTVkYTcxYjMzZmFjZDJfY29sdW1ucyI6W3sidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJuYW1lIiwibGFiZWwiOiJOYW1hIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6ImVtYWlsIiwibGFiZWwiOiJFbWFpbCIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJyb2xlIiwibGFiZWwiOiJQZXJhbiIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJpc19hY3RpdmUiLCJsYWJlbCI6IkFrdGlmIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6Imxhc3RfbG9naW5fYXQiLCJsYWJlbCI6IkxvZ2luIHRlcmFraGlyIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6ImNyZWF0ZWRfYXQiLCJsYWJlbCI6IkRpYnVhdCIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjpmYWxzZSwiaXNUb2dnbGVhYmxlIjp0cnVlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOnRydWV9XX19',1786702290),('o1UeItS6CRerSX3zoEpMs5D2KExB4d4O0TRFmmtk',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJDUnlrZE8xWlJETDBuZEphQ285Z1NSOFdMYW9mdm1WalRSdXE4anlvIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pblwvbG9naW4iLCJyb3V0ZSI6ImZpbGFtZW50LmFkbWluLmF1dGgubG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJ1cmwiOnsiaW50ZW5kZWQiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYWRtaW4ifX0=',1786770254);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `spt_records`
--

DROP TABLE IF EXISTS `spt_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `spt_records` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `year` smallint unsigned NOT NULL DEFAULT '2026',
  `source_number` smallint unsigned NOT NULL,
  `document_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_date` date NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `report_due_date` date DEFAULT NULL,
  `subject` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `audit_object` text COLLATE utf8mb4_unicode_ci,
  `report_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `report_date` date DEFAULT NULL,
  `assignment_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `relation_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NON PKPT',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'SELESAI',
  `pkpt_activity_id` bigint unsigned DEFAULT NULL,
  `match_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `spt_records_year_source_number_unique` (`year`,`source_number`),
  UNIQUE KEY `spt_records_document_number_unique` (`document_number`),
  KEY `spt_records_pkpt_activity_id_foreign` (`pkpt_activity_id`),
  KEY `spt_records_year_index` (`year`),
  KEY `spt_records_assignment_type_index` (`assignment_type`),
  KEY `spt_records_relation_type_index` (`relation_type`),
  KEY `spt_records_status_index` (`status`),
  KEY `spt_year_status_index` (`year`,`status`),
  KEY `spt_year_start_index` (`year`,`start_date`),
  KEY `spt_year_type_index` (`year`,`assignment_type`),
  CONSTRAINT `spt_records_pkpt_activity_id_foreign` FOREIGN KEY (`pkpt_activity_id`) REFERENCES `pkpt_activities` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `spt_records`
--

LOCK TABLES `spt_records` WRITE;
/*!40000 ALTER TABLE `spt_records` DISABLE KEYS */;
INSERT INTO `spt_records` VALUES (1,2026,1,'700.1.2/06/419.060/2026','2026-01-05','2026-01-05','2026-01-09','2026-01-12','Melaksanakan Reviu Dokumen Risk Register Tahun 2026 pada Perangkat Daerah Wilayah Irban 3 Inspektorat Kota Kediri','Perangkat Daerah Wilayah Irban III','700.1.2.8/035/419.060/2026','2026-01-12','REVIU','PKPT','SELESAI',8,'exact',NULL,'2026-08-14 09:58:06','2026-08-14 09:58:06',NULL),(2,2026,2,'700.1.2/37/419.060/2026','2026-01-12','2026-01-12','2026-01-15','2026-01-19','Audit Kinerja Berbasis Risiko atas Program Pengelolaan dan Pengembangan Sistem Drainase (Tahap Perencanaan) pada Dinas Pekerjaan Umum dan Penataan Ruang dan Dinas Perumahan dan Kawasan Permukiman Kota Kediri Tahun Anggaran 2025','Dinas Pekerjaan Umum dan Penataan Ruang dan Dinas Perumahan dan Kawasan Permukiman','700.1.2.8/066/419.60/2026','2026-01-12','AUDIT','PKPT','SELESAI',1,'exact',NULL,'2026-08-14 09:58:06','2026-08-14 09:58:06',NULL),(3,2026,3,'700.1.2/38/419.060/2026','2026-01-12','2026-01-12','2026-01-26','2026-01-27','Melaksanakan Audit Pekerjaan Fisik atas Rehabilitasi Saluran Drainase dan Trotoar Jalan Stasiun, Peningkatan Jalan Stasiun–Jalan PJKA, Pelebaran Jalan Betet-Kleco dan Pembangunan Jalan dan Landscape Menuju IPLT Kota Kediri','Dinas Pekerjaan Umum dan Penataan Ruang','700.1.2.1/176/419.060/2026','2026-02-09','AUDIT','PKPT','SELESAI',3,'exact',NULL,'2026-08-14 09:58:06','2026-08-14 09:58:06',NULL),(4,2026,4,'700.1.2/85/419.060/2026','2026-01-22','2026-01-22','2026-01-26','2026-01-27','Melaksanakan Reviu atas Realisasi Penyerapan Dana, Volume dan Capaian Output Kegiatan Dana Alokasi Khusus Tematik Pengentasan Permukiman Kumuh Terpadu (DAK TPPKT) Sub Bidang Perumahan dan Permukiman Tahap 3 Tahun Anggaran 2025','Dinas Perumahan dan Kawasan Permukiman',NULL,NULL,'REVIU','PKPT','SELESAI',15,'thematic',NULL,'2026-08-14 09:58:06','2026-08-14 09:58:06',NULL),(5,2026,5,'700.1.2/98/419.060/2026','2026-01-26','2026-01-26','2026-01-28','2026-01-29','Melaksanakan Reviu Laporan Kegiatan DAK Fisik Infrastruktur Bidang Sanitasi dan Bidang Air Minum TA 2025','Dinas Pekerjaan Umum dan Penataan Ruang','700/170/419.060/2026','2026-02-09','REVIU','PKPT','SELESAI',15,'thematic',NULL,'2026-08-14 09:58:06','2026-08-14 09:58:06',NULL),(6,2026,6,'700.1.2/63/419.060/2026','2026-01-19','2026-01-27','2026-01-30','2026-02-02','Melaksanakan Audit Pekerjaan Fisik atas Rehabilitasi Saluran Drainase dan Trotoar Jalan Stasiun, Peningkatan Jalan Stasiun–Jalan PJKA, Pelebaran Jalan Betet-Kleco dan Pembangunan Jalan dan Landscape Menuju IPLT Kota Kediri (Lanjutan)','Dinas Pekerjaan Umum dan Penataan Ruang','700.1.2.1/176/419.060/2026','2026-02-09','AUDIT','PKPT','SELESAI',3,'exact',NULL,'2026-08-14 09:58:06','2026-08-14 09:58:06',NULL),(7,2026,7,'700.1.2/138/419.060/2026','2026-02-02','2026-02-02','2026-02-06','2026-02-09','Melaksanakan Reviu DAK Fisik PAUD Tahun Anggaran 2025','Dinas Pendidikan','700/172/419.060/2026','2026-02-09','REVIU','PKPT','SELESAI',15,'thematic',NULL,'2026-08-14 09:58:06','2026-08-14 09:58:06',NULL),(8,2026,8,'700.1.2/139/419.060/2026','2026-02-03','2026-02-03','2026-02-11','2026-02-12','Melaksanakan Reviu Surat Edaran Sekretaris Daerah tentang Pokok Pikiran DPRD Tahun 2027','Badan Perencanaan Pembangunan Daerah','700.1.2/159/419.060/2026','2026-02-06','REVIU','NON PKPT','SELESAI',NULL,NULL,NULL,'2026-08-14 09:58:06','2026-08-14 09:58:06',NULL),(9,2026,9,'700.1.2/153/419.060/2026','2026-02-05','2026-02-09','2026-02-13','2026-02-23','Melaksanakan Pendampingan Pelaporan Dana BOSP Tahap II Tahun 2025 (periode Juli s.d. Desember 2025) pada jenjang PAUD, Pendidikan Dasar dan PKBM di Wilayah Kecamatan Kota','Dinas Pendidikan','700.1.2/234/419.060/2026','2026-02-23','PENDAMPINGAN','PKPT','SELESAI',23,'exact',NULL,'2026-08-14 09:58:06','2026-08-14 09:58:06',NULL),(10,2026,10,'700.1.2/198/419.060/2026','2026-02-18','2026-02-18','2026-02-24','2026-02-25','Melaksanakan Pengembangan Informasi Awal (PIA) Audit Pengadaan Barang dan Jasa Tahun Anggaran 2025','Bagian Pengadaan Barang dan Jasa',NULL,NULL,'AUDIT','PKPT','SELESAI',16,'contextual',NULL,'2026-08-14 09:58:06','2026-08-14 09:58:06',NULL),(11,2026,11,'700.1.2/224/419.060/2026','2026-02-25','2026-02-25','2026-03-10','2026-03-13','Melaksanakan Audit Pengadaan Barang dan Jasa Tahun Anggaran 2025','Bagian Pengadaan Barang dan Jasa','700.1.2/414/419.060/2026','2026-04-15','AUDIT','NON PKPT','SELESAI',NULL,NULL,NULL,'2026-08-14 09:58:06','2026-08-14 09:58:06',NULL),(12,2026,12,'700.1.2/258/419.060/2026','2026-03-03','2026-03-03','2026-03-06','2026-03-06','Melaksanakan Reviu Laporan Keterangan Pertanggungjawaban Wali Kota Kediri Tahun Anggaran 2025','Pemerintah Kota Kediri','700.1.2/279/419.060/2026','2026-03-06','REVIU','NON PKPT','SELESAI',NULL,NULL,NULL,'2026-08-14 09:58:06','2026-08-14 09:58:06',NULL),(13,2026,13,'700.1.2/302/419.060/2026','2026-03-11','2026-03-11','2026-03-17','2026-03-17','Melaksanakan Audit Pengadaan Barang dan Jasa Tahun Anggaran 2025 (Lanjutan)','Bagian Pengadaan Barang dan Jasa','700.1.2/414/419.060/2026','2026-04-15','AUDIT','NON PKPT','SELESAI',NULL,NULL,NULL,'2026-08-14 09:58:06','2026-08-14 09:58:06',NULL),(14,2026,14,'700.1.2/321/419.060/2026','2026-03-25','2026-03-25','2026-03-31','2026-03-31','Melaksanakan Pengembangan Informasi Awal (PIA) Audit Kinerja Berbasis Risiko atas Program Pengelolaan dan Pengembangan Sistem Drainase','Dinas Pekerjaan Umum dan Penataan Ruang, Dinas Perumahan dan Kawasan Permukiman','700.1.2.1/345/419.060/2026','2026-03-31','AUDIT','PKPT','SELESAI',1,'exact',NULL,'2026-08-14 09:58:06','2026-08-14 09:58:06',NULL),(15,2026,15,'700.1.2/353/419.060/2026','2026-03-31','2026-04-01','2026-04-02','2026-04-06','Melaksanakan Reviu Sisa DAK Tahun Anggaran 2025','Dinas Pendidikan','700/376/419.060/2026','2026-04-06','REVIU','PKPT','SELESAI',15,'thematic',NULL,'2026-08-14 09:58:06','2026-08-14 09:58:06',NULL),(16,2026,16,'700.1.2/361/419.060/2026','2026-04-01','2026-04-06','2026-05-08','2026-05-22','Melaksanakan Audit Kinerja Berbasis Risiko atas Program Pengelolaan dan Pengembangan Sistem Drainase','Dinas Pekerjaan Umum dan Penataan Ruang, Dinas Perumahan dan Kawasan Permukiman',NULL,NULL,'AUDIT','PKPT','SELESAI',1,'exact',NULL,'2026-08-14 09:58:06','2026-08-14 09:58:06',NULL),(17,2026,17,'700.1.2/409/419.060/2026','2026-04-14','2026-04-13','2026-04-17','2026-04-20','Melaksanakan Reviu Harga Perkiraan Sendiri (HPS) Pekerjaan Pembangunan TPA 4','Dinas Pekerjaan Umum dan Penataan Ruang','700.1.2/442/419.060/2026','2026-04-20','REVIU','PKPT','SELESAI',17,'exact',NULL,'2026-08-14 09:58:06','2026-08-14 09:58:06',NULL),(18,2026,18,'700.1.2/459/419.060/2026','2026-04-24','2026-04-27','2026-04-30','2026-05-04','Melaksanakan Reviu Data Skoring BPBD','BPBD','700.1.2.8/501/419.060/2026','2026-05-05','REVIU','NON PKPT','SELESAI',NULL,NULL,NULL,'2026-08-14 09:58:06','2026-08-14 09:58:06',NULL),(19,2026,19,'700.1.2/460/419.060/2026','2026-04-24','2026-04-27','2026-04-30','2026-05-04','Melaksanakan Reviu Telaahan Staf Bantuan Sosial untuk Rehabilitasi Rumah Tidak Layak Huni','Dinas Perumahan dan Kawasan Permukiman',NULL,NULL,'REVIU','NON PKPT','SELESAI',NULL,NULL,NULL,'2026-08-14 09:58:06','2026-08-14 09:58:06',NULL),(20,2026,20,'700.1.2/527/419.060/2026','2026-05-11','2026-05-11','2026-05-19','2026-05-19','Melaksanakan Reviu Hasil Verifikasi Calon Penerima Bansos Kompensasi Dampak TPA TA 2026','DLHKP',NULL,NULL,'REVIU','NON PKPT','SELESAI',NULL,NULL,NULL,'2026-08-14 09:58:06','2026-08-14 09:58:06',NULL),(21,2026,21,'700.1.2/552/419.060/2026','2026-05-20','2026-05-20','2026-06-05','2026-06-08','Melaksanakan Audit Kinerja Berbasis Risiko atas Program Pengelolaan dan Pengembangan Sistem Drainase (Lanjutan)','Dinas Pekerjaan Umum dan Penataan Ruang, Dinas Perumahan dan Kawasan Permukiman',NULL,NULL,'AUDIT','PKPT','SELESAI',1,'exact',NULL,'2026-08-14 09:58:06','2026-08-14 09:58:06',NULL),(22,2026,22,'700.1.2/657/419.060/2026','2026-06-11','2026-06-08','2026-06-22','2026-06-24','Pelaksanaan Penilaian Mandiri dan Penjaminan Kualitas Maturitas Sistem Pengendalian Intern Pemerintah (SPIP) Terintegrasi Pemerintah Kota Kediri Tahun 2026','Pemerintah Kota Kediri',NULL,NULL,'MANDATORY','PKPT','SELESAI',25,'exact',NULL,'2026-08-14 09:58:06','2026-08-14 09:58:06',NULL),(23,2026,23,'700.1.2/693/419.060/2026','2026-06-22','2026-06-22','2026-06-26','2026-06-30','Melaksanakan Reviu Rancangan Akhir Rencana Kerja Pemerintah Daerah (RKPD) Kota Kediri Tahun 2027','Badan Perencanaan Pembangunan Daerah',NULL,NULL,'REVIU','PKPT','SELESAI',11,'exact',NULL,'2026-08-14 09:58:06','2026-08-14 09:58:06',NULL),(24,2026,24,'700.1.2/694/419.060/2026','2026-06-22','2026-06-22','2026-06-26','2026-06-30','Melaksanakan Reviu Pengadaan Barang/Jasa Dinas Ketahanan Pangan dan Pertanian Tahun Anggaran 2025','Dinas Ketahanan Pangan dan Pertanian',NULL,NULL,'REVIU','PKPT','SELESAI',16,'contextual',NULL,'2026-08-14 09:58:06','2026-08-14 09:58:06',NULL),(25,2026,25,'700.1.2/752/419.060/2026','2026-07-01','2026-06-29','2026-07-03','2026-07-03','Melaksanakan Reviu Pengadaan Barang/Jasa Dinas Ketahanan Pangan dan Pertanian Tahun Anggaran 2025 (Lanjutan)','Dinas Ketahanan Pangan dan Pertanian',NULL,NULL,'REVIU','PKPT','SELESAI',16,'contextual',NULL,'2026-08-14 09:58:06','2026-08-14 09:58:06',NULL),(26,2026,26,'700.1.2/771/419.060/2026','2026-07-03','2026-07-06','2026-07-24','2026-07-24','Pelaksanaan Penjaminan Kualitas Maturitas Sistem Pengendalian Intern Pemerintah (SPIP) Terintegrasi Pemerintah Kota Kediri Tahun 2026','Pemerintah Kota Kediri',NULL,NULL,'MANDATORY','PKPT','SELESAI',25,'exact',NULL,'2026-08-14 09:58:06','2026-08-14 09:58:06',NULL),(27,2026,27,'700.1.2/807/419.060/2026','2026-07-10','2026-07-13','2026-07-17','2026-07-17','Melaksanakan Reviu Rancangan Peraturan Wali Kota Kediri tentang Standar Harga Satuan (SHS) Tahun Anggaran 2027','Bagian Administrasi Pembangunan',NULL,NULL,'REVIU','PKPT','SELESAI',14,'thematic',NULL,'2026-08-14 09:58:06','2026-08-14 09:58:06',NULL),(28,2026,28,'700.1.2/821/419.060/2026','2026-07-14','2026-07-13','2026-07-17','2026-07-20','Melaksanakan Reviu Rancangan Akhir Perubahan RKPD Tahun 2026','Badan Perencanaan Pembangunan Daerah',NULL,NULL,'REVIU','PKPT','SELESAI',12,'exact',NULL,'2026-08-14 09:58:06','2026-08-14 09:58:06',NULL),(29,2026,29,'700.1.2/864/419.060/2026','2026-07-22','2026-07-23','2026-07-29','2026-07-31','Melaksanakan Reviu Rancangan Peraturan Walikota tentang Perubahan Atas Peraturan Wali Kota Kediri Nomor 18 Tahun 2025 tentang Standar Harga Satuan Pemerintah Kota Kediri Tahun Anggaran 2026','Bagian Administrasi Pembangunan',NULL,NULL,'REVIU','PKPT','SELESAI',14,'thematic',NULL,'2026-08-14 09:58:06','2026-08-14 09:58:06',NULL),(30,2026,30,'700.1.2/906/419.060/2026','2026-07-30','2026-07-30','2026-08-07','2026-08-10','Melaksanakan Reviu Harga Perkiraan Sendiri (HPS) Paket Pekerjaan Pembangunan Kawasan Urban Farming Tahun Anggaran 2026','Dinas Ketahanan Pangan dan Pertanian',NULL,NULL,'REVIU','PKPT','ON PROGRES',17,'exact',NULL,'2026-08-14 09:58:06','2026-08-14 09:58:06',NULL);
/*!40000 ALTER TABLE `spt_records` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `team_members`
--

DROP TABLE IF EXISTS `team_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `team_members` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nip` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rank` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `grade` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_leader` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` smallint unsigned NOT NULL DEFAULT '0',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `team_members_nip_unique` (`nip`),
  KEY `team_members_user_id_foreign` (`user_id`),
  KEY `team_members_is_leader_index` (`is_leader`),
  KEY `team_members_is_active_index` (`is_active`),
  KEY `team_active_sort_index` (`is_active`,`sort_order`),
  CONSTRAINT `team_members_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `team_members`
--

LOCK TABLES `team_members` WRITE;
/*!40000 ALTER TABLE `team_members` DISABLE KEYS */;
INSERT INTO `team_members` VALUES (1,NULL,'Dedi Haryadi, S.H., M.M.','197312092001121004','Pembina Tk. I','IV/b','Inspektur Pembantu III',NULL,NULL,1,1,1,NULL,'2026-08-14 09:58:05','2026-08-14 09:58:05',NULL),(2,NULL,'Sri Mulyani, S.E., M.M.','197408041999012001','Pembina Tk. I','IV/b','PPUPD Ahli Madya',NULL,NULL,0,1,2,NULL,'2026-08-14 09:58:05','2026-08-14 09:58:05',NULL),(3,NULL,'Bram Brahmana, S.T.','198605172010011009','Penata Tk. I','III/d','Auditor Ahli Muda',NULL,NULL,0,1,3,NULL,'2026-08-14 09:58:05','2026-08-14 09:58:05',NULL),(4,NULL,'Dwi Yunianto, S.E., M.M.','197601242001121004','Pembina Utama Muda','IV/c','PPUPD Ahli Madya',NULL,NULL,0,1,4,NULL,'2026-08-14 09:58:05','2026-08-14 09:58:05',NULL),(5,NULL,'Wawan Wicaksono, S.T.','197911102001121002','Penata Tk. I','III/d','PPUPD Ahli Muda',NULL,NULL,0,1,5,NULL,'2026-08-14 09:58:05','2026-08-14 09:58:05',NULL),(6,NULL,'Anik Kusmianingsih, S.Pd. Kim.','197912102009012004','Penata','III/c','Auditor Ahli Muda',NULL,NULL,0,1,6,NULL,'2026-08-14 09:58:05','2026-08-14 09:58:05',NULL),(7,NULL,'Dani Angga Setyantono, S.T.','199706292025041001','Penata Muda','III/a','Auditor Ahli Pertama',NULL,NULL,0,1,7,NULL,'2026-08-14 09:58:05','2026-08-14 09:58:05',NULL);
/*!40000 ALTER TABLE `team_members` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'viewer',
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `last_login_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_index` (`role`),
  KEY `users_is_active_index` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrator SIBATIG','admin@sibatig.local','2026-08-14 09:57:25','$2y$12$U.eo2NQN7/VNoR/VJhsWkOJBJfm7aI6P7fwWVf3Obp6q7iNx0UJs6','super_admin',NULL,1,'2026-08-14 09:59:28',NULL,'2026-08-14 09:57:25','2026-08-14 09:59:28');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `website_settings`
--

DROP TABLE IF EXISTS `website_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `website_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `site_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'SIBATIG',
  `site_tagline` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organization_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `theme_preset` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ocean',
  `primary_color` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#0f766e',
  `accent_color` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#f59e0b',
  `sidebar_color` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#061b3b',
  `canvas_color` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#f4f7fb',
  `logo_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `timezone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Asia/Jakarta',
  `locale` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'id',
  `active_year` smallint unsigned NOT NULL DEFAULT '2026',
  `maintenance_mode` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `website_settings`
--

LOCK TABLES `website_settings` WRITE;
/*!40000 ALTER TABLE `website_settings` DISABLE KEYS */;
INSERT INTO `website_settings` VALUES (1,'SIBATIG','Sistem Informasi Irban Tiga','Inspektorat Kota Kediri','Platform pengelolaan PKPT, tim, serta monitoring dan evaluasi Irban Tiga.','ocean','#1769d2','#f3b73f','#061b3b','#f4f7fb',NULL,'inspektorat@kedirikota.go.id',NULL,NULL,'Asia/Jakarta','id',2026,0,'2026-08-14 09:57:25','2026-08-14 09:57:25');
/*!40000 ALTER TABLE `website_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'sibatig'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-08-15 12:19:16
