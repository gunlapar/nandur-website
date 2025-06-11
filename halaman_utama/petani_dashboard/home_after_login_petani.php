<!DOCTYPE html>
<?php
require '../Login_page/koneksi.php';
session_start();
$isLoggedIn = isset($_SESSION['user']);
$userName = $isLoggedIn ? $_SESSION['user']['nama'] : '';
$userRole = $isLoggedIn ? $_SESSION['user']['role'] : '';

?>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nandur - Dukung Petani Lokal</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../home-1.css">
    <link rel="icon" type="image/x-icon" href="../gambar/nandur_logo.png">
</head>
<body id="home">

    <header id="home">
        <div class="nav-container">
            <nav>
                <div class="logo">nandur</div>
            </nav>
        
        <div class="menu-items">
            <a href="#">Beranda</a>
            <a href="jobs-login_petani.php">Kerja</a>
            <a href="berita2.php">Berita</a>
            <a href="tentang_petani.php">Tentang</a>
            <a href="../shop/shop.php">Belanja</a>
        </div>
        
        <!-- Tombol Sign In -->
         <?php if ($isLoggedIn): ?>
  <div class="profile-dropdown">
    <?php
// Pilih avatar berdasarkan role
$avatarSrc = '../gambar/default_avatar.jpg'; // default

if ($userRole === 'petani') {
    $avatarSrc = '../gambar/farmer.png';
} elseif ($userRole === 'pemilik_lahan') {
    $avatarSrc = '../gambar/juragan.png';
} 
?>
<img src="<?= $avatarSrc ?>" alt="Avatar" class="avatar-icon">
    <div class="dropdown-content">
      <span style="padding: 0 12px; display: block; font-weight: 1000;"><?= htmlspecialchars($userName) ?></span>
      <a href="profile2.php">Profil</a>
      <a href="../home.html" id="logout">Logout</a>
    </div>
  </div>
<?php else: ?>
  <a href="../Login_page/login_page.php" class="btn sign-in">Login</a>
<?php endif; ?>

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
            <a href="#">Beranda</a>
            <a href="jobs-login_petani.php">Kerja</a>
            <a href="berita2.php">Berita</a>
            <a href="tentang_petani.php">Tentang</a>
            <a href="../shop/shop.php">Belanja</a>
        </div>
        <div class="auth-buttons">
            <?php if ($isLoggedIn): ?>
            <div class="profile-dropdown">
                <img src="<?= $avatarSrc ?>" alt="Avatar" class="avatar-icon">
                <div class="dropdown-content">
                    <span style="padding: 0 12px; display: block; font-weight: 1000;">
                        <?= htmlspecialchars($userName) ?>
                    </span>
                    <a href="profile2.php">Profil</a>
                    <a href="../home.html" id="logout-mobile">Logout</a>
                </div>
            </div>
            <?php else: ?>
            <a href="../Login_page/login_page.php" class="btn sign-in">Login</a>
            <?php endif; ?>
        </div>
    </div>
    </header>

    <section class="hero">
        <div class="hero-content">
            <h1>Tersedia Lahan Siap Dikelola <br> Untuk Petani Siap Berkarya!</h1>
            <p>Temukan lahan kosong dari pemilik yang membutuhkan keahlian Anda. Bertani tanpa harus punya lahan sendiri, kini bisa dilakukan di mana saja.</p>
            <a href="jobs-login_petani.php" class="btn-hero">Cari Sekarang</a>
        </div>
    </section>

    <section class="services">
        <h2>Jelajahi Peluang Bertani</h2>
        <p>Temukan berbagai lahan kosong yang siap digarap. Platform kami menghubungkan petani dengan pemilik lahan yang membutuhkan keahlian Anda.</p>
    
        <div class="service-cards">
            <div class="card">
                <img src="https://images.unsplash.com/photo-1501004318641-b39e6451bec6" alt="Katalog Produk">
                <h3>Lahan Tersedia</h3>
                <p>Jelajahi berbagai lahan kosong dari pemilik lahan yang sedang mencari petani untuk mengelolanya. Pilih lokasi dan jenis lahan sesuai keahlian Anda.</p>
            </div>
            <div class="card">
                <img src="https://images.unsplash.com/photo-1501004318641-b39e6451bec6" alt="Belanja Online">
                <h3>Hubungi Pemilik Lahan</h3>
                <p>Langsung terhubung dengan pemilik lahan lewat fitur kontak. Bicarakan rencana, sistem bagi hasil, atau bentuk kerja sama lainnya.</p>
            </div>
            <div class="card">
                <img src="https://images.unsplash.com/photo-1501004318641-b39e6451bec6" alt="Blog Edukasi">
                <h3>Profil Pemilik Lahan</h3>
                <p>Kenal lebih dekat dengan pemilik lahan. Pelajari kebutuhan mereka sebelum mengajukan kerja sama.

                </p>
            </div>
        </div>
    </section>
    
    <section class="local-gardening">
        <div class="container">
            <div class="image">
                <img src="../gambar/pusat.jpg" alt="Berkebun Lokal">
            </div>
            <div class="content">
                <h2>Pusat Kesempatan Bertani Lokal</h2>
                <p>Di <b>nandur</b> kami menjadi jembatan antara petani lokal yang ingin bertani dengan pemilik lahan kosong yang membuka kesempatan kerja sama. Platform ini dirancang agar petani dapat menemukan lahan yang sesuai dengan kebutuhan mereka—langsung dari pemiliknya, tanpa perantara.
                </p>
                <p>Kami percaya bahwa dengan memberdayakan petani dan memanfaatkan lahan tak terpakai, kita bisa mendorong pertanian yang berkelanjutan dan menghidupkan kembali semangat bercocok tanam di berbagai wilayah.</p>
                <ul>
                    <li>✅ Akses langsung ke berbagai lahan siap garap</li>
                    <li>✅ Mendukung petani lokal dalam pengelolaan lahan</li>
                    <li>✅ Terpercaya oleh ribuan pengguna di seluruh Indonesia</li>
                </ul>
            </div>
        </div>
    </section>
    
    <footer class="footer">
        <div class="footer-container">
            <p>&copy; 2025 Nandur. Semua Hak Cipta Dilindungi.</p>
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


  const logout = document.getElementById('logout');

  logout.addEventListener('click', function (e) {
    const confirmLogout = confirm("Apakah Anda yakin ingin logout?");
    if (!confirmLogout) {
      e.preventDefault(); // Batalkan navigasi
    }
    // Kalau OK, biarkan link berjalan
  });
</script>
</body>
</html>
