<?php
session_start();
require 'koneksi.php';
$userId = $_SESSION['user']['id'];
$userRole = $_SESSION['user']['role'];

if ($userRole === 'petani') {
    $kerja = '../petani_dashboard/jobs-login_petani.php';
} elseif ($userRole === 'pemilik_lahan') {
    $kerja = '../pemilik_dashboard/jobs-login_pemilik.php';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pemilik = $_POST['pemilik'];
    $tanggal = $_POST['tanggal'];
    $deskripsi = $_POST['deskripsi'];
    $deskripsi_rinci = $_POST['deskripsi_rinci'];
    $harga = $_POST['harga'];
    $status = $_POST['status'];
    $lokasi = $_POST['lokasi'];
    $jenis_lahan = $_POST['jenis_lahan'];
    $jenis_tanaman = $_POST['jenis_tanaman'];
    $luas_lahan = $_POST['luas_lahan'];
    $ketentuan_bertani = $_POST['ketentuan_bertani'];
    $kontak = $_POST['kontak'];


    try {
        $stmt = $pdo->prepare("INSERT INTO card (
            pemilik, tanggal, kontak, deskripsi, deskripsi_rinci, harga, status,
            lokasi, jenis_lahan, jenis_tanaman, luas_lahan, ketentuan_bertani
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

       $stmt->execute([
    $pemilik, $tanggal, $kontak, $deskripsi, $deskripsi_rinci, $harga, $status,
    $lokasi, $jenis_lahan, $jenis_tanaman, $luas_lahan, $ketentuan_bertani
]);


        header("Location: $kerja");
        exit;
    } catch (PDOException $e) {
        die("Gagal menyimpan data: " . $e->getMessage());
    }
} else {
    echo "Akses tidak sah.";
}
?>