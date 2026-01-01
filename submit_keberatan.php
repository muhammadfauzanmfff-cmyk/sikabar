<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_permohonan = trim($_POST['kode-permohonan']);
    if (empty($kode_permohonan)) {
        header("Location: form-keberatan.php?error=" . urlencode("Kode permohonan tidak boleh kosong"));
        exit();
    }

    // Cek apakah kode permohonan valid di database
    $check_permohonan = $conn->prepare("SELECT id_permohonan FROM informasi_permohonan WHERE CONCAT('INF', LPAD(id_permohonan, 7, '0')) = ?");
    $check_permohonan->bind_param("s", $kode_permohonan);
    $check_permohonan->execute();
    $result = $check_permohonan->get_result();

    if ($result->num_rows === 0) {
        header("Location: form-keberatan.php?error=" . urlencode("Kode permohonan tidak valid"));
        exit();
    }

    $id_permohonan_ref = $result->fetch_assoc()['id_permohonan'];

    // Upload berkas
    $upload_dir = 'uploads/keberatan/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $berkas = '';
    if (isset($_FILES['berkas']) && $_FILES['berkas']['error'] === UPLOAD_ERR_OK) {
        $filename = basename($_FILES['berkas']['name']);
        $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'pdf'];

        if (!in_array($file_ext, $allowed)) {
            header("Location: form-keberatan.php?error=" . urlencode("Format file tidak diizinkan"));
            exit();
        }

        if ($_FILES['berkas']['size'] > 5 * 1024 * 1024) {
            header("Location: form-keberatan.php?error=" . urlencode("Ukuran file terlalu besar. Maksimal 5MB"));
            exit();
        }

        $berkas = time() . '_' . $filename;
        move_uploaded_file($_FILES['berkas']['tmp_name'], $upload_dir . $berkas);
    } else {
        header("Location: form-keberatan.php?error=" . urlencode("File tidak ditemukan"));
        exit();
    }

    // Ambil data lainnya
    $identitas = htmlspecialchars(trim($_POST['identitas']));
    $sertakan_kuasa = isset($_POST['sertakan-kuasa']) ? 1 : 0;
    $nama_kuasa = $sertakan_kuasa ? htmlspecialchars(trim($_POST['nama-kuasa'])) : null;
    $alamat_kuasa = $sertakan_kuasa ? htmlspecialchars(trim($_POST['alamat-kuasa'])) : null;
    $no_hp = $sertakan_kuasa ? htmlspecialchars(trim($_POST['no-hp'])) : null;
    $alasan = htmlspecialchars(trim($_POST['alasan']));
    $keterangan = htmlspecialchars(trim($_POST['keterangan']));
    $konfirmasi = isset($_POST['konfirmasi']) ? 1 : 0;

    // Insert ke tabel keberatan_permohonan
    $conn->begin_transaction();
    try {
        $query = "INSERT INTO keberatan_permohonan (kode_permohonan, identitas, sertakan_kuasa, nama_kuasa, alamat_kuasa, no_hp, alasan, keterangan, berkas, konfirmasi)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssissssssi", $kode_permohonan, $identitas, $sertakan_kuasa, $nama_kuasa, $alamat_kuasa, $no_hp, $alasan, $keterangan, $berkas, $konfirmasi);

        if (!$stmt->execute()) {
            throw new Exception("Gagal menyimpan data keberatan: " . $stmt->error);
        }

        $id_keberatan = $stmt->insert_id;

        // Pastikan kode unik sebelum insert ke tracking
        $kode_tracking = sprintf("KEB%07d", $id_keberatan);
        $check_tracking = $conn->prepare("SELECT kode_unik FROM tracking WHERE kode_unik = ?");
        $check_tracking->bind_param("s", $kode_tracking);
        $check_tracking->execute();
        $tracking_result = $check_tracking->get_result();

        if ($tracking_result->num_rows > 0) {
            // Gunakan metode unik lain jika duplikat ditemukan
            $kode_tracking = sprintf("KEB%07d_%d", $id_keberatan, time());
        }

        // Insert ke tabel tracking
        $tracking_query = "INSERT INTO tracking (kode_unik, jenis_permohonan, id_permohonan_ref, status, tanggal_update)
                           VALUES (?, 'keberatan', ?, 'Menunggu', NOW())";
        $tracking_stmt = $conn->prepare($tracking_query);
        $tracking_stmt->bind_param("si", $kode_tracking, $id_keberatan);

        if (!$tracking_stmt->execute()) {
            throw new Exception("Gagal menyimpan data tracking: " . $tracking_stmt->error);
        }

        $conn->commit();

        header("Location: success_keberatan.php?kode=" . urlencode($kode_tracking));
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        header("Location: form-keberatan.php?error=" . urlencode($e->getMessage()));
        exit();
    }
}
?>
