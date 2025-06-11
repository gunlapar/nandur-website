<?php
session_start();
require 'koneksi.php';

$email = $_POST['email'];
$password = $_POST['password'];

// Cek data user di database
$stmt = $pdo->prepare("SELECT * FROM user WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user'] = $user;

    // Tentukan tujuan halaman berdasarkan role
    $redirectPage = '../Login_page/home_after_login.php'; // default fallback
    switch ($user['role']) {
        case 'petani':
            $redirectPage = '../petani_dashboard/home_after_login_petani.php';
            break;
        case 'pemilik_lahan':
            $redirectPage = '../pemilik_dashboard/home_after_login_pemilik.php';
            break;
    }

    echo "<script>
        alert('Login berhasil! Selamat datang, {$user['nama']}');
        window.location.href = '$redirectPage';
    </script>";
} else {
    echo "<script>
        alert('Login gagal! Email atau password salah.');
        window.location.href = 'login_page.php';
    </script>";
}
?>
