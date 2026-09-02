/*M!999999\- enable the sandbox mode */
-- MariaDB dump 10.19-11.5.2-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: permendagri_tahun_2019_nomor_90_main
-- ------------------------------------------------------
-- Server version	11.5.2-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `fungsi`
--

DROP TABLE IF EXISTS `fungsi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fungsi` (
    `id` varchar(5) NOT NULL,
    `kode` varchar(5) DEFAULT NULL,
    `kode_fungsi` varchar(2) DEFAULT NULL,
    `kode_subfungsi` varchar(2) DEFAULT NULL,
    `nama` varchar(255) DEFAULT NULL,
    `keterangan` varchar(30) DEFAULT NULL,
    `created_at` date DEFAULT NULL,
    `created_by` varchar(255) DEFAULT NULL,
    `updated_at` date DEFAULT NULL,
    `updated_by` varchar(255) DEFAULT NULL,
    `is_deleted` bit(1) DEFAULT NULL,
    `deleted_at` date DEFAULT NULL,
    `deleted_by` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `ck_fungsi_01` CHECK (`id` = `kode`),
    CONSTRAINT `ck_fungsi_02` CHECK (`kode` = concat_ws('.',`kode_fungsi`,`kode_subfungsi`)),
    CONSTRAINT `ck_fungsi_03` CHECK (`kode_fungsi` regexp '^0[1-9]|[1-9][0-9]$'),
  CONSTRAINT `ck_fungsi_04` CHECK (`kode_subfungsi` regexp '^0[1-9]|[1-9][0-9]$')
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fungsi`
--

LOCK TABLES `fungsi` WRITE;
/*!40000 ALTER TABLE `fungsi` DISABLE KEYS */;
/*!40000 ALTER TABLE `fungsi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fungsi_log`
--

DROP TABLE IF EXISTS `fungsi_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fungsi_log` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `id_reference` varchar(5) DEFAULT NULL,
    `kode` varchar(5) DEFAULT NULL,
    `kode_fungsi` varchar(2) DEFAULT NULL,
    `kode_subfungsi` varchar(2) DEFAULT NULL,
    `nama` varchar(255) DEFAULT NULL,
    `keterangan` varchar(30) DEFAULT NULL,
    `created_at` date DEFAULT NULL,
    `created_by` varchar(255) DEFAULT NULL,
    `updated_at` date DEFAULT NULL,
    `updated_by` varchar(255) DEFAULT NULL,
    `is_deleted` bit(1) DEFAULT NULL,
    `deleted_at` date DEFAULT NULL,
    `deleted_by` varchar(255) DEFAULT NULL,
    `logged_at` date DEFAULT NULL,
    `logged_by` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `fk_fungsi_log_01` (`id_reference`),
    CONSTRAINT `fk_fungsi_log_01` FOREIGN KEY (`id_reference`) REFERENCES `fungsi` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fungsi_log`
--

LOCK TABLES `fungsi_log` WRITE;
/*!40000 ALTER TABLE `fungsi_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `fungsi_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lo`
--

DROP TABLE IF EXISTS `lo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lo` (
    `id` varchar(16) NOT NULL,
    `kode` varchar(16) DEFAULT NULL,
    `kode_rekening1` varchar(1) DEFAULT NULL,
    `kode_rekening2` varchar(1) DEFAULT NULL,
    `kode_rekening3` varchar(2) DEFAULT NULL,
    `kode_rekening4` varchar(2) DEFAULT NULL,
    `kode_rekening5` varchar(2) DEFAULT NULL,
    `kode_rekening6` varchar(3) DEFAULT NULL,
    `nama` varchar(255) DEFAULT NULL,
    `keterangan` varchar(1053) DEFAULT NULL,
    `created_at` date DEFAULT NULL,
    `created_by` varchar(255) DEFAULT NULL,
    `updated_at` date DEFAULT NULL,
    `updated_by` varchar(255) DEFAULT NULL,
    `is_deleted` bit(1) DEFAULT NULL,
    `deleted_at` date DEFAULT NULL,
    `deleted_by` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `ck_lo_01` CHECK (`id` = `kode`),
    CONSTRAINT `ck_lo_02` CHECK (`kode` = concat_ws('.',`kode_rekening1`,`kode_rekening2`,`kode_rekening3`,`kode_rekening4`,`kode_rekening5`,`kode_rekening6`)),
    CONSTRAINT `ck_lo_03` CHECK (`kode_rekening1` regexp '^[7-8]$'),
  CONSTRAINT `ck_lo_04` CHECK (`kode_rekening2` regexp '^[1-9]$'),
  CONSTRAINT `ck_lo_05` CHECK (`kode_rekening3` regexp '^0[1-9]|[1-9][0-9]$'),
  CONSTRAINT `ck_lo_06` CHECK (`kode_rekening4` regexp '^0[1-9]|[1-9][0-9]$'),
  CONSTRAINT `ck_lo_07` CHECK (`kode_rekening5` regexp '^0[1-9]|[1-9][0-9]$'),
  CONSTRAINT `ck_lo_08` CHECK (`kode_rekening6` regexp '^00[1-9]|0[1-9][0-9]|[1-9][0-9][0-9]$')
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lo`
--

LOCK TABLES `lo` WRITE;
/*!40000 ALTER TABLE `lo` DISABLE KEYS */;
/*!40000 ALTER TABLE `lo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lo_log`
--

DROP TABLE IF EXISTS `lo_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lo_log` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `id_reference` varchar(16) DEFAULT NULL,
    `kode` varchar(16) DEFAULT NULL,
    `kode_rekening1` varchar(1) DEFAULT NULL,
    `kode_rekening2` varchar(1) DEFAULT NULL,
    `kode_rekening3` varchar(2) DEFAULT NULL,
    `kode_rekening4` varchar(2) DEFAULT NULL,
    `kode_rekening5` varchar(2) DEFAULT NULL,
    `kode_rekening6` varchar(3) DEFAULT NULL,
    `nama` varchar(255) DEFAULT NULL,
    `keterangan` varchar(1053) DEFAULT NULL,
    `created_at` date DEFAULT NULL,
    `created_by` varchar(255) DEFAULT NULL,
    `updated_at` date DEFAULT NULL,
    `updated_by` varchar(255) DEFAULT NULL,
    `is_deleted` bit(1) DEFAULT NULL,
    `deleted_at` date DEFAULT NULL,
    `deleted_by` varchar(255) DEFAULT NULL,
    `logged_at` date DEFAULT NULL,
    `logged_by` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `fk_lo_log_01` (`id_reference`),
    CONSTRAINT `fk_lo_log_01` FOREIGN KEY (`id_reference`) REFERENCES `lo` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lo_log`
--

LOCK TABLES `lo_log` WRITE;
/*!40000 ALTER TABLE `lo_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `lo_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lra`
--

DROP TABLE IF EXISTS `lra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lra` (
    `id` varchar(16) NOT NULL,
    `kode` varchar(16) DEFAULT NULL,
    `kode_rekening1` varchar(1) DEFAULT NULL,
    `kode_rekening2` varchar(1) DEFAULT NULL,
    `kode_rekening3` varchar(2) DEFAULT NULL,
    `kode_rekening4` varchar(2) DEFAULT NULL,
    `kode_rekening5` varchar(2) DEFAULT NULL,
    `kode_rekening6` varchar(3) DEFAULT NULL,
    `nama` varchar(255) DEFAULT NULL,
    `keterangan` varchar(862) DEFAULT NULL,
    `created_at` date DEFAULT NULL,
    `created_by` varchar(255) DEFAULT NULL,
    `updated_at` date DEFAULT NULL,
    `updated_by` varchar(255) DEFAULT NULL,
    `is_deleted` bit(1) DEFAULT NULL,
    `deleted_at` date DEFAULT NULL,
    `deleted_by` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `ck_lra_01` CHECK (`id` = `kode`),
    CONSTRAINT `ck_lra_02` CHECK (`kode` = concat_ws('.',`kode_rekening1`,`kode_rekening2`,`kode_rekening3`,`kode_rekening4`,`kode_rekening5`,`kode_rekening6`)),
    CONSTRAINT `ck_lra_03` CHECK (`kode_rekening1` regexp '^[4-6]$'),
  CONSTRAINT `ck_lra_04` CHECK (`kode_rekening2` regexp '^[1-9]$'),
  CONSTRAINT `ck_lra_05` CHECK (`kode_rekening3` regexp '^0[1-9]|[1-9][0-9]$'),
  CONSTRAINT `ck_lra_06` CHECK (`kode_rekening4` regexp '^0[1-9]|[1-9][0-9]$'),
  CONSTRAINT `ck_lra_07` CHECK (`kode_rekening5` regexp '^0[1-9]|[1-9][0-9]$'),
  CONSTRAINT `ck_lra_08` CHECK (`kode_rekening6` regexp '^00[1-9]|0[1-9][0-9]|[1-9][0-9][0-9]$')
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lra`
--

LOCK TABLES `lra` WRITE;
/*!40000 ALTER TABLE `lra` DISABLE KEYS */;
/*!40000 ALTER TABLE `lra` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lra_log`
--

DROP TABLE IF EXISTS `lra_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lra_log` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `id_reference` varchar(16) DEFAULT NULL,
    `kode` varchar(16) DEFAULT NULL,
    `kode_rekening1` varchar(1) DEFAULT NULL,
    `kode_rekening2` varchar(1) DEFAULT NULL,
    `kode_rekening3` varchar(2) DEFAULT NULL,
    `kode_rekening4` varchar(2) DEFAULT NULL,
    `kode_rekening5` varchar(2) DEFAULT NULL,
    `kode_rekening6` varchar(3) DEFAULT NULL,
    `nama` varchar(255) DEFAULT NULL,
    `keterangan` varchar(862) DEFAULT NULL,
    `created_at` date DEFAULT NULL,
    `created_by` varchar(255) DEFAULT NULL,
    `updated_at` date DEFAULT NULL,
    `updated_by` varchar(255) DEFAULT NULL,
    `is_deleted` bit(1) DEFAULT NULL,
    `deleted_at` date DEFAULT NULL,
    `deleted_by` varchar(255) DEFAULT NULL,
    `logged_at` date DEFAULT NULL,
    `logged_by` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `fk_lra_log_01` (`id_reference`),
    CONSTRAINT `fk_lra_log_01` FOREIGN KEY (`id_reference`) REFERENCES `lra` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lra_log`
--

LOCK TABLES `lra_log` WRITE;
/*!40000 ALTER TABLE `lra_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `lra_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `neraca`
--

DROP TABLE IF EXISTS `neraca`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `neraca` (
    `id` varchar(16) NOT NULL,
    `kode` varchar(16) DEFAULT NULL,
    `kode_rekening1` varchar(1) DEFAULT NULL,
    `kode_rekening2` varchar(1) DEFAULT NULL,
    `kode_rekening3` varchar(2) DEFAULT NULL,
    `kode_rekening4` varchar(2) DEFAULT NULL,
    `kode_rekening5` varchar(2) DEFAULT NULL,
    `kode_rekening6` varchar(3) DEFAULT NULL,
    `nama` varchar(255) DEFAULT NULL,
    `keterangan` varchar(961) DEFAULT NULL,
    `created_at` date DEFAULT NULL,
    `created_by` varchar(255) DEFAULT NULL,
    `updated_at` date DEFAULT NULL,
    `updated_by` varchar(255) DEFAULT NULL,
    `is_deleted` bit(1) DEFAULT NULL,
    `deleted_at` date DEFAULT NULL,
    `deleted_by` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `ck_neraca_01` CHECK (`id` = `kode`),
    CONSTRAINT `ck_neraca_02` CHECK (`kode` = concat_ws('.',`kode_rekening1`,`kode_rekening2`,`kode_rekening3`,`kode_rekening4`,`kode_rekening5`,`kode_rekening6`)),
    CONSTRAINT `ck_neraca_03` CHECK (`kode_rekening1` regexp '^[1-3]$'),
  CONSTRAINT `ck_neraca_04` CHECK (`kode_rekening2` regexp '^[1-9]$'),
  CONSTRAINT `ck_neraca_05` CHECK (`kode_rekening3` regexp '^0[1-9]|[1-9][0-9]$'),
  CONSTRAINT `ck_neraca_06` CHECK (`kode_rekening4` regexp '^0[1-9]|[1-9][0-9]$'),
  CONSTRAINT `ck_neraca_07` CHECK (`kode_rekening5` regexp '^0[1-9]|[1-9][0-9]$'),
  CONSTRAINT `ck_neraca_08` CHECK (`kode_rekening6` regexp '^00[1-9]|0[1-9][0-9]|[1-9][0-9][0-9]$')
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `neraca`
--

LOCK TABLES `neraca` WRITE;
/*!40000 ALTER TABLE `neraca` DISABLE KEYS */;
/*!40000 ALTER TABLE `neraca` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `neraca_log`
--

DROP TABLE IF EXISTS `neraca_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `neraca_log` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `id_reference` varchar(16) DEFAULT NULL,
    `kode` varchar(16) DEFAULT NULL,
    `kode_rekening1` varchar(1) DEFAULT NULL,
    `kode_rekening2` varchar(1) DEFAULT NULL,
    `kode_rekening3` varchar(2) DEFAULT NULL,
    `kode_rekening4` varchar(2) DEFAULT NULL,
    `kode_rekening5` varchar(2) DEFAULT NULL,
    `kode_rekening6` varchar(3) DEFAULT NULL,
    `nama` varchar(255) DEFAULT NULL,
    `keterangan` varchar(961) DEFAULT NULL,
    `created_at` date DEFAULT NULL,
    `created_by` varchar(255) DEFAULT NULL,
    `updated_at` date DEFAULT NULL,
    `updated_by` varchar(255) DEFAULT NULL,
    `is_deleted` bit(1) DEFAULT NULL,
    `deleted_at` date DEFAULT NULL,
    `deleted_by` varchar(255) DEFAULT NULL,
    `logged_at` date DEFAULT NULL,
    `logged_by` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `fk_neraca_log_01` (`id_reference`),
    CONSTRAINT `fk_neraca_log_01` FOREIGN KEY (`id_reference`) REFERENCES `neraca` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `neraca_log`
--

LOCK TABLES `neraca_log` WRITE;
/*!40000 ALTER TABLE `neraca_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `neraca_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `perencanaan_kabupaten`
--

DROP TABLE IF EXISTS `perencanaan_kabupaten`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perencanaan_kabupaten` (
    `id` varchar(15) NOT NULL,
    `kode` varchar(15) DEFAULT NULL,
    `kode_urusan` varchar(1) DEFAULT NULL,
    `kode_bidang` varchar(2) DEFAULT NULL,
    `kode_program` varchar(2) DEFAULT NULL,
    `kode_kegiatan` varchar(4) DEFAULT NULL,
    `kode_subkegiatan` varchar(2) DEFAULT NULL,
    `nama` varchar(345) DEFAULT NULL,
    `keterangan` varchar(36) DEFAULT NULL,
    `created_at` date DEFAULT NULL,
    `created_by` varchar(255) DEFAULT NULL,
    `updated_at` date DEFAULT NULL,
    `updated_by` varchar(255) DEFAULT NULL,
    `is_deleted` bit(1) DEFAULT NULL,
    `deleted_at` date DEFAULT NULL,
    `deleted_by` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `ck_perencanaan_kabupaten_01` CHECK (`id` = replace(`kode`,'X','0')),
    CONSTRAINT `ck_perencanaan_kabupaten_02` CHECK (`kode` = concat_ws('.',`kode_urusan`,`kode_bidang`,`kode_program`,`kode_kegiatan`,`kode_subkegiatan`)),
    CONSTRAINT `ck_perencanaan_kabupaten_03` CHECK (`kode_urusan` regexp '^[X1-9]$'),
  CONSTRAINT `ck_perencanaan_kabupaten_04` CHECK (`kode_bidang` regexp '^XX|0[1-9]|[1-9][0-9]$'),
  CONSTRAINT `ck_perencanaan_kabupaten_05` CHECK (`kode_program` regexp '^0[1-9]|[1-9][0-9]$'),
  CONSTRAINT `ck_perencanaan_kabupaten_06` CHECK (`kode_kegiatan` regexp '^[1-9].(0[1-9]|[1-9][0-9])$'),
  CONSTRAINT `ck_perencanaan_kabupaten_07` CHECK (`kode_subkegiatan` regexp '^0[1-9]|[1-9][0-9]$')
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perencanaan_kabupaten`
--

LOCK TABLES `perencanaan_kabupaten` WRITE;
/*!40000 ALTER TABLE `perencanaan_kabupaten` DISABLE KEYS */;
/*!40000 ALTER TABLE `perencanaan_kabupaten` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `perencanaan_kabupaten_log`
--

DROP TABLE IF EXISTS `perencanaan_kabupaten_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perencanaan_kabupaten_log` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `id_reference` varchar(15) DEFAULT NULL,
    `kode` varchar(15) DEFAULT NULL,
    `kode_urusan` varchar(1) DEFAULT NULL,
    `kode_bidang` varchar(2) DEFAULT NULL,
    `kode_program` varchar(2) DEFAULT NULL,
    `kode_kegiatan` varchar(4) DEFAULT NULL,
    `kode_subkegiatan` varchar(2) DEFAULT NULL,
    `nama` varchar(323) DEFAULT NULL,
    `keterangan` varchar(36) DEFAULT NULL,
    `created_at` date DEFAULT NULL,
    `created_by` varchar(255) DEFAULT NULL,
    `updated_at` date DEFAULT NULL,
    `updated_by` varchar(255) DEFAULT NULL,
    `is_deleted` bit(1) DEFAULT NULL,
    `deleted_at` date DEFAULT NULL,
    `deleted_by` varchar(255) DEFAULT NULL,
    `logged_at` date DEFAULT NULL,
    `logged_by` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `fk_perencanaan_kabupaten_log_01` (`id_reference`),
    CONSTRAINT `fk_perencanaan_kabupaten_log_01` FOREIGN KEY (`id_reference`) REFERENCES `perencanaan_kabupaten` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perencanaan_kabupaten_log`
--

LOCK TABLES `perencanaan_kabupaten_log` WRITE;
/*!40000 ALTER TABLE `perencanaan_kabupaten_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `perencanaan_kabupaten_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `perencanaan_provinsi`
--

DROP TABLE IF EXISTS `perencanaan_provinsi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perencanaan_provinsi` (
    `id` varchar(15) NOT NULL,
    `kode` varchar(15) DEFAULT NULL,
    `kode_urusan` varchar(1) DEFAULT NULL,
    `kode_bidang` varchar(2) DEFAULT NULL,
    `kode_program` varchar(2) DEFAULT NULL,
    `kode_kegiatan` varchar(4) DEFAULT NULL,
    `kode_subkegiatan` varchar(2) DEFAULT NULL,
    `nama` varchar(345) DEFAULT NULL,
    `keterangan` varchar(30) DEFAULT NULL,
    `created_at` date DEFAULT NULL,
    `created_by` varchar(255) DEFAULT NULL,
    `updated_at` date DEFAULT NULL,
    `updated_by` varchar(255) DEFAULT NULL,
    `is_deleted` bit(1) DEFAULT NULL,
    `deleted_at` date DEFAULT NULL,
    `deleted_by` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `ck_perencanaan_provinsi_01` CHECK (`id` = replace(`kode`,'X','0')),
    CONSTRAINT `ck_perencanaan_provinsi_02` CHECK (`kode` = concat_ws('.',`kode_urusan`,`kode_bidang`,`kode_program`,`kode_kegiatan`,`kode_subkegiatan`)),
    CONSTRAINT `ck_perencanaan_provinsi_03` CHECK (`kode_urusan` regexp '^[X1-9]$'),
  CONSTRAINT `ck_perencanaan_provinsi_04` CHECK (`kode_bidang` regexp '^XX|0[1-9]|[1-9][0-9]$'),
  CONSTRAINT `ck_perencanaan_provinsi_05` CHECK (`kode_program` regexp '^0[1-9]|[1-9][0-9]$'),
  CONSTRAINT `ck_perencanaan_provinsi_06` CHECK (`kode_kegiatan` regexp '^[1-9].(0[1-9]|[1-9][0-9])$'),
  CONSTRAINT `ck_perencanaan_provinsi_07` CHECK (`kode_subkegiatan` regexp '^0[1-9]|[1-9][0-9]$')
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perencanaan_provinsi`
--

LOCK TABLES `perencanaan_provinsi` WRITE;
/*!40000 ALTER TABLE `perencanaan_provinsi` DISABLE KEYS */;
/*!40000 ALTER TABLE `perencanaan_provinsi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `perencanaan_provinsi_log`
--

DROP TABLE IF EXISTS `perencanaan_provinsi_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perencanaan_provinsi_log` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `id_reference` varchar(15) DEFAULT NULL,
    `kode` varchar(15) DEFAULT NULL,
    `kode_urusan` varchar(1) DEFAULT NULL,
    `kode_bidang` varchar(2) DEFAULT NULL,
    `kode_program` varchar(2) DEFAULT NULL,
    `kode_kegiatan` varchar(4) DEFAULT NULL,
    `kode_subkegiatan` varchar(2) DEFAULT NULL,
    `nama` varchar(345) DEFAULT NULL,
    `keterangan` varchar(30) DEFAULT NULL,
    `created_at` date DEFAULT NULL,
    `created_by` varchar(255) DEFAULT NULL,
    `updated_at` date DEFAULT NULL,
    `updated_by` varchar(255) DEFAULT NULL,
    `is_deleted` bit(1) DEFAULT NULL,
    `deleted_at` date DEFAULT NULL,
    `deleted_by` varchar(255) DEFAULT NULL,
    `logged_at` date DEFAULT NULL,
    `logged_by` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `fk_perencanaan_provinsi_log_01` (`id_reference`),
    CONSTRAINT `fk_perencanaan_provinsi_log_01` FOREIGN KEY (`id_reference`) REFERENCES `perencanaan_provinsi` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perencanaan_provinsi_log`
--

LOCK TABLES `perencanaan_provinsi_log` WRITE;
/*!40000 ALTER TABLE `perencanaan_provinsi_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `perencanaan_provinsi_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sumber`
--

DROP TABLE IF EXISTS `sumber`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sumber` (
    `id` varchar(14) NOT NULL,
    `kode` varchar(14) DEFAULT NULL,
    `kode_rekening1` varchar(1) DEFAULT NULL,
    `kode_rekening2` varchar(1) DEFAULT NULL,
    `kode_rekening3` varchar(1) DEFAULT NULL,
    `kode_rekening4` varchar(2) DEFAULT NULL,
    `kode_rekening5` varchar(2) DEFAULT NULL,
    `kode_rekening6` varchar(2) DEFAULT NULL,
    `nama` varchar(255) DEFAULT NULL,
    `keterangan` varchar(2105) DEFAULT NULL,
    `created_at` date DEFAULT NULL,
    `created_by` varchar(255) DEFAULT NULL,
    `updated_at` date DEFAULT NULL,
    `updated_by` varchar(255) DEFAULT NULL,
    `is_deleted` bit(1) DEFAULT NULL,
    `deleted_at` date DEFAULT NULL,
    `deleted_by` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `ck_sumber_01` CHECK (`id` = `kode`),
    CONSTRAINT `ck_sumber_02` CHECK (`kode` = concat_ws('.',`kode_rekening1`,`kode_rekening2`,`kode_rekening3`,`kode_rekening4`,`kode_rekening5`,`kode_rekening6`)),
    CONSTRAINT `ck_sumber_03` CHECK (`kode_rekening1` regexp '^[1-2]$'),
  CONSTRAINT `ck_sumber_04` CHECK (`kode_rekening2` regexp '^[1-9]$'),
  CONSTRAINT `ck_sumber_05` CHECK (`kode_rekening3` regexp '^[1-9]$'),
  CONSTRAINT `ck_sumber_06` CHECK (`kode_rekening4` regexp '^0[1-9]|[1-9][0-9]$'),
  CONSTRAINT `ck_sumber_07` CHECK (`kode_rekening5` regexp '^0[1-9]|[1-9][0-9]$'),
  CONSTRAINT `ck_sumber_08` CHECK (`kode_rekening6` regexp '^0[1-9]|[1-9][0-9]$')
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sumber`
--

LOCK TABLES `sumber` WRITE;
/*!40000 ALTER TABLE `sumber` DISABLE KEYS */;
/*!40000 ALTER TABLE `sumber` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sumber_log`
--

DROP TABLE IF EXISTS `sumber_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sumber_log` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `id_reference` varchar(14) DEFAULT NULL,
    `kode` varchar(14) DEFAULT NULL,
    `kode_rekening1` varchar(1) DEFAULT NULL,
    `kode_rekening2` varchar(1) DEFAULT NULL,
    `kode_rekening3` varchar(1) DEFAULT NULL,
    `kode_rekening4` varchar(2) DEFAULT NULL,
    `kode_rekening5` varchar(2) DEFAULT NULL,
    `kode_rekening6` varchar(2) DEFAULT NULL,
    `nama` varchar(255) DEFAULT NULL,
    `keterangan` varchar(2105) DEFAULT NULL,
    `created_at` date DEFAULT NULL,
    `created_by` varchar(255) DEFAULT NULL,
    `updated_at` date DEFAULT NULL,
    `updated_by` varchar(255) DEFAULT NULL,
    `is_deleted` bit(1) DEFAULT NULL,
    `deleted_at` date DEFAULT NULL,
    `deleted_by` varchar(255) DEFAULT NULL,
    `logged_at` date DEFAULT NULL,
    `logged_by` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `fk_sumber_log_01` (`id_reference`),
    CONSTRAINT `fk_sumber_log_01` FOREIGN KEY (`id_reference`) REFERENCES `sumber` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sumber_log`
--

LOCK TABLES `sumber_log` WRITE;
/*!40000 ALTER TABLE `sumber_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `sumber_log` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-09-02 13:44:16
