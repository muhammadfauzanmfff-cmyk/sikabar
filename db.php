<?php
// Koneksi ke database
$servername = "localhost"; // Ganti dengan server database Anda jika perlu
$username = "root";        // Ganti dengan username database Anda
$password = "";            // Ganti dengan password database Anda
$database = "sikabar"; // Nama database Anda

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $database);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>
