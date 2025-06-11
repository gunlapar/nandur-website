<?php
// includes/koneksi.php
$host     = 'localhost';
$user     = 'kel3';
$pass     = '12345678';
$db       = 'login';             // atau nama database yang berisi tabel profil

// Buat koneksi MySQLi
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die('Koneksi gagal: ' . $conn->connect_error);
}
?>
