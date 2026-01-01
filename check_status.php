<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_unik = trim($_POST['tracking_code']);

    if (empty($kode_unik)) {
        echo json_encode(['success' => false, 'message' => 'Kode tracking tidak boleh kosong']);
        exit();
    }

    // Query untuk menggabungkan data dari kedua tabel
    $query = "
        SELECT 
            t.kode_unik, 
            t.status, 
            t.tanggal_update, 
            t.keterangan, 
            i.nama AS nama,
            'informasi' AS tipe
        FROM 
            tracking t
        JOIN 
            informasi_permohonan i ON t.id_permohonan_ref = i.id_permohonan
        WHERE 
            t.jenis_permohonan = 'informasi' AND t.kode_unik = ?
        
        UNION
        
        SELECT 
            t.kode_unik, 
            t.status, 
            t.tanggal_update, 
            t.keterangan, 
            COALESCE(k.nama_kuasa, k.identitas) AS nama,
            'keberatan' AS tipe
        FROM 
            tracking t
        JOIN 
            keberatan_permohonan k ON t.id_permohonan_ref = k.id_keberatan
        WHERE 
            t.jenis_permohonan = 'keberatan' AND t.kode_unik = ?
    ";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Gagal mempersiapkan statement']);
        exit();
    }

    $stmt->bind_param("ss", $kode_unik, $kode_unik);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        echo json_encode(['success' => true, 'data' => $data]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan untuk kode tracking tersebut']);
    }

    $stmt->close();
    $conn->close();
}
?>
