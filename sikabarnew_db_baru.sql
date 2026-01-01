-- Hapus semua tabel yang ada terlebih dahulu
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS tracking;
DROP TABLE IF EXISTS keberatan_permohonan;
DROP TABLE IF EXISTS informasi_permohonan;
DROP TABLE IF EXISTS statistik;
DROP TABLE IF EXISTS admin;
SET FOREIGN_KEY_CHECKS = 1;

-- Buat tabel admin
CREATE TABLE admin (
  id_admin int(11) NOT NULL AUTO_INCREMENT,
  username varchar(255) NOT NULL,
  password varchar(255) NOT NULL,
  created_at timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (id_admin),
  UNIQUE KEY username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert data admin
INSERT INTO admin (username, password, created_at) 
VALUES ('dinkominfo', 'dinkominfo123', '2024-08-21 07:03:18');

-- Buat tabel informasi_permohonan
CREATE TABLE informasi_permohonan (
    id_permohonan INT PRIMARY KEY AUTO_INCREMENT,
    kategori_permohonan ENUM('perorangan','lembaga','mahasiswa') NOT NULL,
    identitas VARCHAR(255) NOT NULL,
    instansi VARCHAR(255) NOT NULL,
    nama VARCHAR(255) NOT NULL,
    alamat TEXT NOT NULL,
    pekerjaan VARCHAR(255) NOT NULL,
    no_handphone VARCHAR(20) NOT NULL,
    email VARCHAR(255) NOT NULL,
    rincian TEXT NOT NULL,
    tujuan TEXT NOT NULL,
    cara_memperoleh ENUM('melihat','salinan') NOT NULL,
    cara_mendapatkan ENUM('langsung','kurir','Pos','Fax') NOT NULL,
    berkas VARCHAR(255) NOT NULL,
    konfirmasi TINYINT(1) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Buat tabel keberatan_permohonan
CREATE TABLE keberatan_permohonan (
    id_keberatan INT PRIMARY KEY AUTO_INCREMENT,
    kode_permohonan VARCHAR(255) NOT NULL,
    identitas VARCHAR(255) NOT NULL,
    sertakan_kuasa TINYINT(1) NOT NULL,
    nama_kuasa VARCHAR(255) DEFAULT NULL,
    alamat_kuasa VARCHAR(255) DEFAULT NULL,
    no_hp VARCHAR(20) DEFAULT NULL,
    alasan ENUM('permohonan','info-berkala','tidak-ditanggapi','ditanggapi-tidak-sesuai','tidak-dipenuhi','biaya','melebihi-jangka-waktu') NOT NULL,
    keterangan TEXT NOT NULL,
    berkas VARCHAR(255) NOT NULL,
    konfirmasi TINYINT(1) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Buat tabel tracking
CREATE TABLE tracking (
    id_tracking INT PRIMARY KEY AUTO_INCREMENT,
    kode_unik VARCHAR(100) NOT NULL,
    jenis_permohonan ENUM('informasi','keberatan') NOT NULL,
    id_permohonan_ref INT NOT NULL,
    status ENUM('Menunggu','Diproses','Diterima','Ditolak') DEFAULT 'Menunggu',
    tanggal_update TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    keterangan TEXT DEFAULT NULL,
    UNIQUE KEY unique_kode (kode_unik)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Buat tabel statistik
CREATE TABLE statistik (
  ID_Statistik int(11) NOT NULL AUTO_INCREMENT,
  Tipe_Permohonan enum('Informasi','Difabel','Keberatan') DEFAULT NULL,
  Jumlah_Permohonan int(11) DEFAULT NULL,
  Tanggal date DEFAULT NULL,
  PRIMARY KEY (ID_Statistik)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tambah indexes
ALTER TABLE tracking ADD INDEX idx_permohonan_ref (id_permohonan_ref);
ALTER TABLE tracking ADD INDEX idx_jenis (jenis_permohonan);

-- Buat triggers
DELIMITER //

CREATE TRIGGER generate_info_tracking 
AFTER INSERT ON informasi_permohonan
FOR EACH ROW
BEGIN
    INSERT INTO tracking (
        kode_unik,
        jenis_permohonan,
        id_permohonan_ref,
        status
    ) VALUES (
        CONCAT('INF', LPAD(NEW.id_permohonan, 7, '0')),
        'informasi',
        NEW.id_permohonan,
        'Menunggu'
    );
END//

CREATE TRIGGER generate_keberatan_tracking 
AFTER INSERT ON keberatan_permohonan
FOR EACH ROW
BEGIN
    INSERT INTO tracking (
        kode_unik,
        jenis_permohonan,
        id_permohonan_ref,
        status
    ) VALUES (
        CONCAT('KEB', LPAD(NEW.id_keberatan, 7, '0')),
        'keberatan',
        NEW.id_keberatan,
        'Menunggu'
    );
END//

DELIMITER ;

-- Insert dummy data untuk informasi_permohonan
INSERT INTO `informasi_permohonan` (
    `kategori_permohonan`, `identitas`, `instansi`, `nama`, `alamat`, 
    `pekerjaan`, `no_handphone`, `email`, `rincian`, `tujuan`, 
    `cara_memperoleh`, `cara_mendapatkan`, `berkas`, `konfirmasi`
) VALUES
-- Permohonan 1
('perorangan', '3304122505990001', 'dinkominfo', 'Ahmad Sudrajat', 
'Jl. Veteran No. 45 RT 02/03 Banjarnegara', 'Wiraswasta', 
'085712345678', 'ahmad.s@gmail.com', 
'Data UMKM Banjarnegara tahun 2023', 
'Penelitian perkembangan UMKM di Banjarnegara', 
'salinan', 'langsung', 'dokumen_ahmad.pdf', 1),

-- Permohonan 2
('mahasiswa', '21102156', 'dinkominfo', 'Siti Nurhaliza', 
'Jl. Selomanik No. 12 RT 03/02 Banjarnegara', 'Mahasiswa', 
'087823456789', 'siti.nurhaliza@univ.ac.id', 
'Data pengembangan infrastruktur digital Banjarnegara', 
'Skripsi tentang perkembangan smart city', 
'melihat', 'Pos', 'surat_penelitian_siti.pdf', 1),

-- Permohonan 3
('lembaga', '123/LSM/2024', 'dinkominfo', 'LSM Peduli Digital', 
'Jl. Dipayuda No. 78 Banjarnegara', 'Lembaga Swadaya Masyarakat', 
'089567891234', 'lsmpeduli@gmail.com', 
'Data pengguna internet di Banjarnegara', 
'Analisis kesenjangan digital di Banjarnegara', 
'salinan', 'kurir', 'surat_permohonan_lsm.pdf', 1);

-- Insert dummy data untuk keberatan_permohonan
INSERT INTO `keberatan_permohonan` (
    `kode_permohonan`, `identitas`, `sertakan_kuasa`, 
    `nama_kuasa`, `alamat_kuasa`, `no_hp`, 
    `alasan`, `keterangan`, `berkas`, `konfirmasi`
) VALUES
-- Keberatan 1
('INF0000001', '3304122505990001', 0, 
NULL, NULL, NULL,
'tidak-ditanggapi', 
'Sudah 2 minggu belum ada tanggapan atas permohonan informasi', 
'keberatan_ahmad.pdf', 1),

-- Keberatan 2
('INF0000002', '21102156', 1, 
'Budi Santoso, S.H.', 'Jl. Letjen Suprapto No. 45 Banjarnegara', '081234567890',
'ditanggapi-tidak-sesuai', 
'Data yang diberikan tidak sesuai dengan yang diminta', 
'keberatan_siti.pdf', 1);

-- Insert dummy data (gunakan data yang sebelumnya sudah ada)