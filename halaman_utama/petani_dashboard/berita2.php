<?php
require '../Login_page/koneksi.php';
require '../jobs/koneksi.php';


$stmt = $pdo->query("SELECT * FROM card ORDER BY id DESC");
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
session_start();
$isLoggedIn = isset($_SESSION['user']);
$userName = $isLoggedIn ? $_SESSION['user']['nama'] : '';
$userRole = $isLoggedIn ? $_SESSION['user']['role'] : '';
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
            <a href="home_after_login_petani.php">Beranda</a>
            <a href="jobs-login_petani.php">Kerja</a>
            <a href="#">Berita</a>
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
            <a href="home_after_login_petani.php">Beranda</a>
            <a href="jobs-login_petani.php">kerja</a>
            <a href="#">Berita</a>
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


        const logout = document.getElementById('logout');

        logout.addEventListener('click', function (e) {
            const confirmLogout = confirm("Apakah Anda yakin ingin logout?");
            if (!confirmLogout) {
            e.preventDefault();
            }
        
        });
</script>
</body>
</html>
