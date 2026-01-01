<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['username'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_keberatan']) && isset($_POST['status'])) {
    try {
        $id_keberatan = filter_var($_POST['id_keberatan'], FILTER_SANITIZE_NUMBER_INT);
        $status = htmlspecialchars(trim($_POST['status']), ENT_QUOTES, 'UTF-8');
        
        // Validasi status
        $valid_statuses = ['Menunggu', 'Diproses', 'Diterima', 'Ditolak'];
        if (!in_array($status, $valid_statuses)) {
            throw new Exception('Status tidak valid');
        }

        // Update status in tracking_keberatan table
        $query = "UPDATE tracking_keberatan 
                 SET status = ?, tanggal_update = NOW() 
                 WHERE id_keberatan = ?";
                 
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("si", $status, $id_keberatan);
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                throw new Exception($stmt->error);
            }
            $stmt->close();
        } else {
            throw new Exception($conn->error);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>