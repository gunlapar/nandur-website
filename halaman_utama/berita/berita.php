<?php
require '../jobs/koneksi.php';

$stmt = $pdo->query("SELECT * FROM card ORDER BY id DESC");
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nandur - Dukung Petani Lokal</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../jobs/jobs-1.css">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="icon" type="image/x-icon" href="/gambar/nandur_logo.png">
    <link rel="stylesheet" href="berita_pertanian.css">
</head>

<body id="home" class="min-h-screen bg-gray-100">

    <header id="home">
       <header id="home">
        <div class="nav-container">
            <nav>
                <div class="logo">nandur</div>
            </nav>
        
        <div class="menu-items">
            <a href="../home.html">Beranda</a>
            <a href="../Login_page/login_page.php">Kerja</a>
            <a href="#">Berita</a>
            <a href="../tentang/tentang.php">Tentang</a>
            <a href="../Login_page/login_page.php">Belanja</a>
        </div>
  <a href="../Login_page/login_page.php" class="btn sign-in">Login</a>

        </div>
        </div>
        <!-- MOBILE NAV MENU -->
    <div class="mobile-navmenu">
        <div class="logo">nandur</div>
        <div class="menu-icons">
            <img class="menu-icon" src="/gambar/menu-icon.png" alt="">
            <img class="close-icon" src="/gambar/close-iconn.png" alt="">
        </div>
    </div>

    <div class="mobile-menu-items">
        <div class="menu-items">
            <a href="../home.html">Beranda</a>
            <a href="../Login_page/login_page.php">Kerja</a>
            <a href="#">Berita</a>
            <a href="../tentang/tentang.php">Tentang</a>
            <a href="../Login_page/login_page.php">Belanja</a>
        </div>
            <a href="../Login_page/login_page.php" class="btn sign-in">Login</a>
        </div>
    </div>
    </header>
    <section>
    <?php include 'tampilkan_berita_pertanian.php'; ?>




      
    
    <footer class="bg-green-800 text-white py-6 mt-16">
  <div class="container mx-auto text-center">
    &copy; 2025 Nandur. Semua Hak Cipta Dilindungi.
  </div>
</footer>


    <script>
                const menuIcon = document.querySelector('.menu-icon');
        const closeIcon = document.querySelector('.close-icon');
        const mobileNavmenu = document.querySelector('.mobile-navmenu');
        const mobileMenuItems = document.querySelector('.mobile-menu-items');

        menuIcon.addEventListener('click', () => {
            mobileNavmenu.classList.add('active');
            mobileMenuItems.classList.add('active'); // ini penting!
        });

        closeIcon.addEventListener('click', () => {
            mobileNavmenu.classList.remove('active');
            mobileMenuItems.classList.remove('active'); // ini juga penting!
        });
</script>
</body>
</html>
