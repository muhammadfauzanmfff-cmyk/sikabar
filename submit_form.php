<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validasi file upload
        $upload_dir = 'uploads/informasi/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Handle file upload
        $berkas = '';
        if (isset($_FILES['berkas']) && $_FILES['berkas']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['berkas'];
            $file_size = $file['size'];
            $file_tmp = $file['tmp_name'];
            $filename = basename($file['name']);
            $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            // Validasi ekstensi file
            $allowed = ['jpg', 'jpeg', 'png', 'pdf'];
            if (!in_array($file_ext, $allowed)) {
                throw new Exception('Format file tidak diizinkan. Format yang diperbolehkan: ' . implode(', ', $allowed));
            }
            
            // Validasi ukuran file (5MB)
            if ($file_size > 5 * 1024 * 1024) {
                throw new Exception('Ukuran file terlalu besar. Maksimal 5MB');
            }
            
            $berkas = time() . '_' . $filename;
            $upload_path = $upload_dir . $berkas;
            
            if (!move_uploaded_file($file_tmp, $upload_path)) {
                throw new Exception('Gagal mengupload file');
            }
        } else {
            throw new Exception('File tidak ditemukan');
        }

        // Sanitasi input
        function sanitize_input($data) {
            return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
        }

        $kategori = sanitize_input($_POST['kategori_permohonan']);
        $identitas = sanitize_input($_POST['identitas']);
        $instansi = sanitize_input($_POST['instansi']);
        $nama = sanitize_input($_POST['nama']);
        $alamat = sanitize_input($_POST['alamat']);
        $pekerjaan = sanitize_input($_POST['pekerjaan']);
        $no_hp = sanitize_input($_POST['no_handphone']);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $rincian = sanitize_input($_POST['rincian']);
        $tujuan = sanitize_input($_POST['tujuan']);
        $cara_memperoleh = sanitize_input($_POST['cara_memperoleh']);
        $cara_mendapatkan = sanitize_input($_POST['cara_mendapatkan']);
        $konfirmasi = isset($_POST['konfirmasi']) ? 1 : 0;

        // Start transaction
        $conn->begin_transaction();

        try {
            // Insert permohonan
            $query = "INSERT INTO informasi_permohonan (
                kategori_permohonan, identitas, instansi, nama, alamat, 
                pekerjaan, no_handphone, email, rincian, tujuan, 
                cara_memperoleh, cara_mendapatkan, berkas, konfirmasi
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($query);
            if (!$stmt) {
                throw new Exception("Error preparing statement: " . $conn->error);
            }

            $stmt->bind_param("sssssssssssssi",
                $kategori, $identitas, $instansi, $nama, $alamat,
                $pekerjaan, $no_hp, $email, $rincian, $tujuan,
                $cara_memperoleh, $cara_mendapatkan, $berkas, $konfirmasi
            );

            if (!$stmt->execute()) {
                throw new Exception("Error executing statement: " . $stmt->error);
            }

            $id_permohonan = $stmt->insert_id;

            // Ambil kode_unik dari tabel tracking
            $result = $conn->query("SELECT kode_unik FROM tracking WHERE id_permohonan_ref = $id_permohonan AND jenis_permohonan = 'informasi'");
            if ($result && $row = $result->fetch_assoc()) {
                $kode_tracking = $row['kode_unik'];
            } else {
                throw new Exception("Kode tracking tidak ditemukan.");
            }

            // Commit transaction
            $conn->commit();

            // Redirect ke halaman sukses
            header("Location: success.php?kode=" . urlencode($kode_tracking));
            exit();

        } catch (Exception $e) {
            // Rollback transaction
            $conn->rollback();
            throw $e;
        }

    } catch (Exception $e) {
        // Hapus file jika ada error
        if (!empty($berkas) && file_exists($upload_dir . $berkas)) {
            unlink($upload_dir . $berkas);
        }

        header("Location: form.php?error=" . urlencode($e->getMessage()));
        exit();
    }
}
?>
