<?php
require 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pemilik = $_POST['pemilik'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal = $_POST['tanggal'];
    $harga = $_POST['harga'];
    $status = $_POST['status'];
    $lokasi = $_POST['lokasi'];
    $jenis_lahan = $_POST['jenis_lahan'];
    $jenis_tanaman = $_POST['jenis_tanaman'];

    try {
        $sql = "INSERT INTO card (pemilik, deskripsi, tanggal, harga, status, lokasi, jenis_lahan, jenis_tanaman) 
                VALUES (:pemilik, :deskripsi, :tanggal, :harga, :status, :lokasi, :jenis_lahan, :jenis_tanaman)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':pemilik' => $pemilik,
            ':deskripsi' => $deskripsi,
            ':tanggal' => $tanggal,
            ':harga' => $harga,
            ':status' => $status,
            ':lokasi' => $lokasi,
            ':jenis_lahan' => $jenis_lahan,
            ':jenis_tanaman' => $jenis_tanaman
        ]);
        echo "<script>alert('✅ Login berhasil!'); window.location='../jobs/dashboard.php';</script>";
    } catch (PDOException $e) {
        echo "Gagal menyimpan data: " . $e->getMessage();
    }
} else {
    echo "Akses tidak valid.";
}
?>
