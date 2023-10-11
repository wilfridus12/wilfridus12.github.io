<?php
$servername = "localhost"; // Nama server database
$username = "u326558280_midnight"; // Nama pengguna database
$password = "Midnightmaximum6"; // Kata sandi database
$database = "u326558280_midnight"; // Nama database

// Membuat koneksi ke database
$conn = new mysqli($servername, $username, $password, $database);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}


// Sekarang Anda dapat menjalankan query atau operasi database lainnya di sini

// Tutup koneksi ketika selesai
$conn->close();
?>