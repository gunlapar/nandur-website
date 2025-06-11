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

// Ambil data dari form khusus petani
$nama = $_POST['nama'];
$deskripsi = $_POST['deskripsi'];
$pengalaman = $_POST['pengalaman'] ?? null;
$tanaman_dikuasai = $_POST['tanaman_dikuasai'] ?? null;
$kemampuan_khusus = $_POST['kemampuan_khusus'] ?? null;
$wilayah_digarap = $_POST['wilayah_digarap'] ?? null;
$tim_kerja = $_POST['tim_kerja'] ?? null;
$whatsapp = $_POST['whatsapp'] ?? null;

// Update ke database
$stmt = $pdo->prepare("UPDATE user SET 
    nama = ?, 
    deskripsi = ?, 
    pengalaman = ?, 
    tanaman_dikuasai = ?, 
    kemampuan_khusus = ?, 
    wilayah_digarap = ?, 
    tim_kerja = ?, 
    whatsapp = ?
    WHERE id = ?");

$success = $stmt->execute([
    $nama,
    $deskripsi,
    $pengalaman,
    $tanaman_dikuasai,
    $kemampuan_khusus,
    $wilayah_digarap,
    $tim_kerja,
    $whatsapp,
    $userId
]);

if ($success) {
    // Refresh session data dan redirect
    $stmt = $pdo->prepare("SELECT * FROM user WHERE id = ?");
    $stmt->execute([$userId]);
    $_SESSION['user'] = $stmt->fetch(PDO::FETCH_ASSOC);

    header("Location: profile2.php");
    exit;
} else {
    $errorInfo = $stmt->errorInfo();
    echo "Gagal update: " . $errorInfo[2];
    exit;
}
?>
