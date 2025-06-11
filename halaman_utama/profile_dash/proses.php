<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require '../Login_page/koneksi.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../Login_page/login.php");
    exit;
}

$userId = $_SESSION['user']['id'];

// Ambil data dari form
$nama = $_POST['nama'];
$deskripsi = $_POST['deskripsi'];
$lahan_dimiliki = $_POST['lahan_dimiliki'];
$total_lahan = $_POST['total_lahan'];
$tipe_lahan = $_POST['tipe_lahan'];
$durasi_kontrak = $_POST['durasi_kontrak'];
$persebaran = $_POST['persebaran'];
$whatsapp = $_POST['whatsapp'];

// Update ke database
$stmt = $pdo->prepare("UPDATE user SET 
    nama = ?, 
    deskripsi = ?, 
    lahan_dimiliki = ?, 
    total_lahan = ?, 
    tipe_lahan = ?, 
    durasi_kontrak = ?, 
    persebaran = ?, 
    whatsapp = ?
    WHERE id = ?");

$success = $stmt->execute([
    $nama,
    $deskripsi,
    $lahan_dimiliki,
    $total_lahan,
    $tipe_lahan,
    $durasi_kontrak,
    $persebaran,
    $whatsapp,
    $userId
]);

if ($success) {
    // Update session dan redirect
    $stmt = $pdo->prepare("SELECT * FROM user WHERE id = ?");
    $stmt->execute([$userId]);
    $_SESSION['user'] = $stmt->fetch(PDO::FETCH_ASSOC);

    header("Location: profile.php"); // Sesuaikan dengan letak file profile.php
    exit;
} else {
    $errorInfo = $stmt->errorInfo();
    echo "Gagal update: " . $errorInfo[2];
    exit;
}
?>
