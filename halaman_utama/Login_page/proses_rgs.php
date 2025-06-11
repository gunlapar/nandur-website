<?php
// proses_rgs.php

require 'koneksi.php'; // Pastikan file koneksi.php berisi koneksi ke database menggunakan PDO

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Cek apakah email sudah terdaftar
        $stmt = $pdo->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            echo "<script>alert('Email sudah terdaftar. Silakan login.'); window.location.href = 'login_page.php';</script>";
            exit;
        }

        // Simpan data ke database
        $stmt = $pdo->prepare("INSERT INTO user (nama, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nama, $email, $hashed_password, $role]);

        echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location.href = 'login_page.php';</script>";
    } catch (PDOException $e) {
        echo "Terjadi kesalahan: " . $e->getMessage();
    }
} else {
    echo "Metode tidak diperbolehkan.";
}
