<?php
// includes/auth.php

/**
 * Auth generic untuk Farmer & Landowner
 *
 * Melindungi halaman agar hanya user yang sudah login
 * dan (opsional) sesuai role yang diizinkan.
 */
// Pastikan session dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek login
if (empty($_SESSION['user']['id'])) {
    header('Location: ../Login_page/login_page.php');
    exit;
}

// Ambil role dari session (harus set di proses login)
$role = $_SESSION['user']['role'] ?? '';

// Jika halaman menentukan $required_role, cek hak akses
// Contoh di file atas: $required_role = 'farmer';
if (isset($required_role)) {
    if ($role !== $required_role) {
        http_response_code(403);
        echo '<h2>403 Forbidden</h2><p>Anda tidak memiliki akses ke halaman ini.</p>';
        exit;
    }
}
