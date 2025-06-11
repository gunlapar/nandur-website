<!DOCTYPE html>
<?php
session_start();
require '../Login_page/koneksi.php';

$isLoggedIn = isset($_SESSION['user']);
$userName = $isLoggedIn ? $_SESSION['user']['nama'] : '';
$userRole = $isLoggedIn ? $_SESSION['user']['role'] : '';
?>
<html lang="id">
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nandur - Tentang Kami</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../home-1.css">
    <link rel="icon" type="image/x-icon" href="../gambar/nandur_logo.png">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'nandur': '#293241',
                    },
                    fontFamily: {
                        'archivo': ['"Archivo Black"', 'sans-serif'],
                        'poppins': ['"Poppins"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body id="home">

    <header id="home">
        <div class="nav-container">
            <nav>
                <div class="logo">nandur</div>
            </nav>
        
        <div class="menu-items">
            <a href="home_after_login_pemilik.php">Beranda</a>
            <a href="jobs-login_pemilik.php">Kerja</a>
            <a href="berita1.php">Berita</a>
            <a href="#">Tentang</a>
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
?>s
<img src="<?= $avatarSrc ?>" alt="Avatar" class="avatar-icon">
    <div class="dropdown-content">
      <span style="padding: 0 12px; display: block; font-weight: 1000;"><?= htmlspecialchars($userName) ?></span>
      <a href="profile1.php">Profil</a>
      <a href="../home.html" id="logout">Logout</a>
    </div>
  </div>
<?php else: ?>
  <a href="Login_page/login_page.php" class="btn sign-in">Login</a>
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
            <a href="home_after_login_pemilik.php">Beranda</a>
            <a href="jobs-login_pemilik.php">Kerja</a>
            <a href="berita1">Berita</a>
            <a href="#">Tentang</a>
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
                    <a href="profile1.php">Profil</a>
                    <a href="../home.html" id="logout-mobile">Logout</a>
                </div>
            </div>
            <?php else: ?>
            <a href="Login_page/login_page.php" class="btn sign-in">Login</a>
            <?php endif; ?>
        </div>
    </div>
    </header>
    

<!-- About Section -->
    <div class="py-20 px-4 max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row items-start gap-12 p-8">
            <img src="../gambar/tentang2.jpg" alt="Petani di sawah" 
                class="w-full md:w-1/2 rounded-lg shadow-lg object-cover object-[100%_60%] h-[400px]">
            <div class="md:w-1/2">
                <h2 class="text-5xl md:text-6xl font-archivo text-green-800 mb-8">
                    Tentang Nandur
                </h2>
                <p class="text-xl text-gray-700 leading-relaxed">
                    Nandur adalah platform pertanian inovatif yang menghubungkan pemilik lahan dengan petani atau penyewa secara mudah dan modern. Kami memfasilitasi pencarian lahan sesuai lokasi, luas, dan komoditas. Dengan fokus pada praktik berkelanjutan dan teknologi tepat guna, Nandur mendukung produktivitas pertanian serta pemberdayaan komunitas petani modern.
                </p>
            </div>
        </div>
    </div>

    <!-- Purpose Section -->
    <div class="py-20 px-4 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row-reverse items-start gap-12 p-8">
                <img src="../gambar/petani_sawah.jpg" alt="Petani di sawah" 
                     class="w-full md:w-1/2 rounded-lg shadow-lg object-cover h-[400px]">
                <div class="md:w-1/2">
                    <h2 class="text-5xl md:text-6xl font-archivo text-green-800 mb-8">
                        Tujuan Nandur
                    </h2>
                    <p class="text-xl text-gray-700 leading-relaxed">
                        Nandur memfasilitasi koneksi digital antara pemilik lahan dan petani: pemilik lahan dapat menawar lahan secara optimal, sedangkan petani mendapat akses ke lahan produktif dan dukungan teknologi modern serta praktik berkelanjutan. Kami berkomitmen menciptakan ekosistem pertanian yang cerdas, inklusif, dan adaptif terhadap perkembangan zaman.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Section -->
    <div class="py-20 px-4 bg-gray-50">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-5xl md:text-6xl font-archivo text-green-800 mb-6">
                    Kontak Nandur
                </h2>
                <p class="text-xl text-gray-700">
                    Untuk informasi, Kemitraan, atau dukungan teknis, Anda bisa<br>menghubungi tim kami melalui:
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <!-- WhatsApp Card -->
                <div class="bg-white rounded-xl shadow-md p-8 transition duration-300 hover:shadow-xl">
                    <div class="flex justify-center mb-6">
                        <!-- WhatsApp SVG remains the same -->
                    </div>
                    <h3 class="text-3xl font-semibold text-green-800 mb-4">
                        WhatsApp Kami
                    </h3>
                    <p class="text-lg text-gray-700">
                        Klik untuk hubungi via WhatsApp:<br>
                        <a href="https://wa.me/6281234567890" 
                           class="text-green-600 font-semibold hover:text-green-700 underline transition duration-300"
                           target="_blank">
                            +62 812-3456-7890
                        </a>
                    </p>
                </div>

                <!-- Email Card -->
                <div class="bg-white rounded-xl shadow-md p-8 transition duration-300 hover:shadow-xl">
                    <div class="flex justify-center mb-6">
                        <!-- Email SVG remains the same -->
                    </div>
                    <h3 class="text-3xl font-semibold text-green-800 mb-4">
                        Email Kami
                    </h3>
                    <p class="text-lg text-gray-700">
                        Klik untuk hubungi via Email:<br>
                        <a href="mailto:nostagis8@gmail.com" 
                           class="text-green-600 font-semibold hover:text-green-700 underline transition duration-300"
                           target="_blank">
                            nostagis8@gmail.com
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    
    <footer class="footer">
        <div class="footer-container">
            <p>&copy; 2025 Nandur. Semua Hak Cipta Dilindungi.</p>
        </div>
    </footer>

 
      
    <script src="header-1.js" ></script>
    <script>
const menuIcon = document.querySelector('.menu-icon');
  const closeIcon = document.querySelector('.close-icon');
  const mobileNavmenu = document.querySelector('.mobile-navmenu');
  const mobileMenuItems = document.querySelector('.mobile-menu-items');

  menuIcon.addEventListener('click', () => {
    mobileNavmenu.classList.add('active');
    mobileMenuItems.classList.add('active'); 
  });

  closeIcon.addEventListener('click', () => {
    mobileNavmenu.classList.remove('active');
    mobileMenuItems.classList.remove('active'); 
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
