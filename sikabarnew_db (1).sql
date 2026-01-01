-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 22, 2024 at 09:39 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sikabarnew_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `password`, `created_at`) VALUES
(1, 'dinkominfo', 'dinkominfo123', '2024-08-21 07:03:18');

-- --------------------------------------------------------

--
-- Table structure for table `informasi_permohonan`
--

CREATE TABLE `informasi_permohonan` (
  `id_permohonan` int(11) NOT NULL,
  `kategori_permohonan` enum('perorangan','lembaga','mahasiswa') NOT NULL,
  `identitas` varchar(255) NOT NULL,
  `instansi` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `alamat` text NOT NULL,
  `pekerjaan` varchar(255) NOT NULL,
  `no_handphone` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `rincian` text NOT NULL,
  `tujuan` text NOT NULL,
  `cara_memperoleh` enum('melihat','salinan') NOT NULL,
  `cara_mendapatkan` enum('langsung','kurir','Pos','Fax') NOT NULL,
  `berkas` varchar(255) NOT NULL,
  `konfirmasi` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `informasi_permohonan`
--

INSERT INTO `informasi_permohonan` (`id_permohonan`, `kategori_permohonan`, `identitas`, `instansi`, `nama`, `alamat`, `pekerjaan`, `no_handphone`, `email`, `rincian`, `tujuan`, `cara_memperoleh`, `cara_mendapatkan`, `berkas`, `konfirmasi`, `created_at`) VALUES
(4, 'perorangan', 'ascasc', 'dinkominfo', 'Poyeng', 'Jl. Almunawwaroh No. 16 Rt. 01 Rw.02', 'Mahasiswa', '0895381373417', 'ozanpoyeng@gmail.com', '3rg3rg', '34g34g', 'salinan', 'langsung', 'logo veggiebox png.png', 1, '2024-08-22 01:38:29'),
(5, 'mahasiswa', '21102088', 'dinkominfo', 'Poyeng', 'Jl. Al munawaroh Rt 01 Re 02', 'wegwegwe', '0895381373417', 'tiarokta@gmail.com', 'advadvaadvdav', 'advadva', 'melihat', 'langsung', '3.jpg', 1, '2024-08-22 02:11:35'),
(6, 'perorangan', '21102088', 'dinkominfo', 'Muhammad Haykal', 'Jl. Al munawaroh Rt 01 Re 02', 'Mahasiswa', '0895381373417', 'zaenadienhaykal@gmail.com', 'jfjhv', 'nvcngdh', 'salinan', 'kurir', 'Screenshot (1364).png', 1, '2024-08-22 03:01:30');

-- --------------------------------------------------------

--
-- Table structure for table `keberatan_permohonan`
--

CREATE TABLE `keberatan_permohonan` (
  `id_keberatan` int(11) NOT NULL,
  `kode_permohonan` varchar(255) NOT NULL,
  `identitas` varchar(255) NOT NULL,
  `sertakan_kuasa` tinyint(1) NOT NULL,
  `nama_kuasa` varchar(255) DEFAULT NULL,
  `alamat_kuasa` varchar(255) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `alasan` enum('permohonan','info-berkala','tidak-ditanggapi','ditanggapi-tidak-sesuai','tidak-dipenuhi','biaya','melebihi-jangka-waktu') NOT NULL,
  `keterangan` text NOT NULL,
  `berkas` varchar(255) NOT NULL,
  `konfirmasi` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `statistik`
--

CREATE TABLE `statistik` (
  `ID_Statistik` int(11) NOT NULL,
  `Tipe_Permohonan` enum('Informasi','Difabel','Keberatan') DEFAULT NULL,
  `Jumlah_Permohonan` int(11) DEFAULT NULL,
  `Tanggal` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tracking`
--

CREATE TABLE `tracking` (
  `id_tracking` int(11) NOT NULL,
  `id_permohonan` int(11) DEFAULT NULL,
  `kode_unik` varchar(255) DEFAULT NULL,
  `status` enum('Diproses','Berhasil','Gagal') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `informasi_permohonan`
--
ALTER TABLE `informasi_permohonan`
  ADD PRIMARY KEY (`id_permohonan`);

--
-- Indexes for table `keberatan_permohonan`
--
ALTER TABLE `keberatan_permohonan`
  ADD PRIMARY KEY (`id_keberatan`);

--
-- Indexes for table `statistik`
--
ALTER TABLE `statistik`
  ADD PRIMARY KEY (`ID_Statistik`);

--
-- Indexes for table `tracking`
--
ALTER TABLE `tracking`
  ADD PRIMARY KEY (`id_tracking`),
  ADD KEY `id_permohonan` (`id_permohonan`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `informasi_permohonan`
--
ALTER TABLE `informasi_permohonan`
  MODIFY `id_permohonan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `keberatan_permohonan`
--
ALTER TABLE `keberatan_permohonan`
  MODIFY `id_keberatan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `statistik`
--
ALTER TABLE `statistik`
  MODIFY `ID_Statistik` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tracking`
--
ALTER TABLE `tracking`
  MODIFY `id_tracking` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tracking`
--
ALTER TABLE `tracking`
  ADD CONSTRAINT `tracking_ibfk_1` FOREIGN KEY (`id_permohonan`) REFERENCES `informasi_permohonan` (`id_permohonan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- Modify tracking table
ALTER TABLE tracking 
ADD COLUMN tanggal_update TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN keterangan TEXT,
MODIFY COLUMN status ENUM('Menunggu','Diproses','Diterima','Ditolak') DEFAULT 'Menunggu';

-- Add trigger for automatic tracking code generation
DELIMITER //
CREATE TRIGGER generate_tracking_code AFTER INSERT ON informasi_permohonan
FOR EACH ROW
BEGIN
    DECLARE tracking_code VARCHAR(10);
    SET tracking_code = CONCAT('INF', LPAD(NEW.id_permohonan, 7, '0'));
    
    INSERT INTO tracking (id_permohonan, kode_unik, status, tanggal_update)
    VALUES (NEW.id_permohonan, tracking_code, 'Menunggu', NOW());
END//
DELIMITER ;

-- Add indexes for better performance
ALTER TABLE tracking ADD INDEX idx_kode_unik (kode_unik);
ALTER TABLE informasi_permohonan ADD INDEX idx_created_at (created_at);

-- ALTER TABLE tracking 
-- ADD COLUMN tanggal_update TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;