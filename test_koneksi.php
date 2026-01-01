<?php
// Sertakan file koneksi database
include 'db.php';

// Uji koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
} else {
    echo "Koneksi berhasil!";
}

// Tutup koneksi
$conn->close();
?>
