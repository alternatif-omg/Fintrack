-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.5.0.6677
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table fintrack_app.cicilan
CREATE TABLE IF NOT EXISTS `cicilan` (
  `id_cicilan` int NOT NULL AUTO_INCREMENT,
  `id_pengguna` int DEFAULT NULL,
  `nominal` int DEFAULT NULL,
  `tenggat` date DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci,
  `status` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id_cicilan`),
  KEY `FK_cicilan_register` (`id_pengguna`),
  CONSTRAINT `FK_cicilan_register` FOREIGN KEY (`id_pengguna`) REFERENCES `register` (`id_pengguna`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table fintrack_app.pemasukan
CREATE TABLE IF NOT EXISTS `pemasukan` (
  `id_pemasukan` int NOT NULL AUTO_INCREMENT,
  `id_pengguna` int NOT NULL,
  `judul` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nominal` int DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id_pemasukan`),
  KEY `FK_pemasukan_register` (`id_pengguna`),
  CONSTRAINT `FK_pemasukan_register` FOREIGN KEY (`id_pengguna`) REFERENCES `register` (`id_pengguna`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table fintrack_app.pembayaran
CREATE TABLE IF NOT EXISTS `pembayaran` (
  `id_pembayaran` int NOT NULL AUTO_INCREMENT,
  `id_pengguna` int DEFAULT NULL,
  `id_cicilan` int DEFAULT NULL,
  `nominal` int DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `keterangan` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id_pembayaran`),
  KEY `FK_pembayaran_register` (`id_pengguna`),
  KEY `FK_pembayaran_cicilan` (`id_cicilan`),
  CONSTRAINT `FK_pembayaran_cicilan` FOREIGN KEY (`id_cicilan`) REFERENCES `cicilan` (`id_cicilan`),
  CONSTRAINT `FK_pembayaran_register` FOREIGN KEY (`id_pengguna`) REFERENCES `register` (`id_pengguna`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table fintrack_app.pengeluaran
CREATE TABLE IF NOT EXISTS `pengeluaran` (
  `id_pengeluaran` int NOT NULL AUTO_INCREMENT,
  `id_pengguna` int DEFAULT NULL,
  `judul` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nominal` int DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id_pengeluaran`),
  KEY `FK_pengeluaran_register` (`id_pengguna`),
  CONSTRAINT `FK_pengeluaran_register` FOREIGN KEY (`id_pengguna`) REFERENCES `register` (`id_pengguna`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table fintrack_app.register
CREATE TABLE IF NOT EXISTS `register` (
  `id_pengguna` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id_pengguna`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table fintrack_app.rekapan
CREATE TABLE IF NOT EXISTS `rekapan` (
  `id_rekapan` int NOT NULL AUTO_INCREMENT,
  `id_pemasukan` int DEFAULT NULL,
  `id_pengguna` int DEFAULT NULL,
  `id_pengeluaran` int DEFAULT NULL,
  `id_cicilan` int DEFAULT NULL,
  `id_pembayaran` int DEFAULT NULL,
  `bulan` int DEFAULT NULL,
  `tahun` int DEFAULT NULL,
  `tanggal_awal` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal_akhir` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tot_pemasukan` int DEFAULT NULL,
  `tot_pengeluaran` int DEFAULT NULL,
  `tot_cicilan` int DEFAULT NULL,
  `tot_pembayaran` int DEFAULT NULL,
  PRIMARY KEY (`id_rekapan`),
  KEY `FK_rekapan_register` (`id_pengguna`),
  KEY `FK_rekapan_pemasukan` (`id_pemasukan`),
  KEY `FK_rekapan_pengeluaran` (`id_pengeluaran`),
  KEY `FK_rekapan_cicilan` (`id_cicilan`),
  KEY `FK_rekapan_pembayaran` (`id_pembayaran`),
  CONSTRAINT `FK_rekapan_cicilan` FOREIGN KEY (`id_cicilan`) REFERENCES `cicilan` (`id_cicilan`),
  CONSTRAINT `FK_rekapan_pemasukan` FOREIGN KEY (`id_pemasukan`) REFERENCES `pemasukan` (`id_pemasukan`),
  CONSTRAINT `FK_rekapan_pembayaran` FOREIGN KEY (`id_pembayaran`) REFERENCES `pembayaran` (`id_pembayaran`),
  CONSTRAINT `FK_rekapan_pengeluaran` FOREIGN KEY (`id_pengeluaran`) REFERENCES `pengeluaran` (`id_pengeluaran`),
  CONSTRAINT `FK_rekapan_register` FOREIGN KEY (`id_pengguna`) REFERENCES `register` (`id_pengguna`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
