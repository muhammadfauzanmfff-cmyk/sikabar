<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['username'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validasi input
        if (!isset($_POST['id_permohonan']) || !isset($_POST['status'])) {
            throw new Exception('Data tidak lengkap');
        }

        $id_permohonan = filter_var($_POST['id_permohonan'], FILTER_SANITIZE_NUMBER_INT);
        $status = htmlspecialchars(trim($_POST['status']), ENT_QUOTES, 'UTF-8');
        $tipe = isset($_POST['tipe']) ? htmlspecialchars(trim($_POST['tipe']), ENT_QUOTES, 'UTF-8') : 'informasi';

        // Validasi status
        $valid_statuses = ['Menunggu', 'Diproses', 'Diterima', 'Ditolak'];
        if (!in_array($status, $valid_statuses)) {
            throw new Exception('Status tidak valid');
        }

        // Update status di tabel tracking
        $query = "UPDATE tracking 
                  SET status = ?, 
                      tanggal_update = NOW() 
                  WHERE id_permohonan_ref = ? 
                  AND jenis_permohonan = ?";

        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("sis", $status, $id_permohonan, $tipe);
            
            if (!$stmt->execute()) {
                throw new Exception("Gagal mengupdate status: " . $stmt->error);
            }

            if ($stmt->affected_rows === 0) {
                throw new Exception("Data tracking tidak ditemukan");
            }

            echo json_encode(['success' => true]);
        } else {
            throw new Exception("Gagal mempersiapkan query: " . $conn->error);
        }

    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}
?>
