<?php
require '../Login_page/koneksi.php';
require '../jobs/koneksi.php';
session_start();

// Ambil data detail lahan berdasarkan ID (pastikan $id terdefinisi!)
$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM card WHERE id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

$card = isset($_SESSION['card']);
$a = $card ? $_SESSION['card']['pemilik'] : '';
$b = $card ? $_SESSION['card']['kontak'] : '';
$c = $card ? $_SESSION['card']['jenis_lahan'] : '';
$d = $card ? $_SESSION['card']['lokasi'] : '';
$e = $card ? $_SESSION['card']['harga'] : '';
$f = $card ? $_SESSION['card']['luas_lahan'] : '';
$g = $card ? $_SESSION['card']['ketentuan_bertani'] : '';
$h = $card ? $_SESSION['card']['deskripsi_rinci'] : '';

$isLoggedIn = isset($_SESSION['user']);
$userName = $isLoggedIn ? $_SESSION['user']['nama'] : '';
$userRole = $isLoggedIn ? $_SESSION['user']['role'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nandur - Dukung Petani Lokal</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../jobs/jobs-1.css">
  <link rel="icon" type="image/x-icon" href="../gambar/nandur_logo.png">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<!-- Navbar -->
<header id="home">
    <div class="nav-container">
        <nav>
            <div class="logo">nandur</div>
        </nav>
        <div class="menu-items">
            <a href="home_after_login_pemilik.php">Beranda</a>
            <a href="#">Kerja</a>
            <a href="berita_pemilik.php">Berita</a>
            <a href="tentang_pemilik.php">Tentang</a>
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
      <a href="../profile_dash/profile.php">Profil</a>
      <a href="../home.html" id="logout">Logout</a>
    </div>
  </div>
<?php else: ?>
  <a href="../Login_page/login_page.php" class="btn sign-in">Login</a>
<?php endif; ?>

        </div>
    
</div><!-- MOBILE NAV MENU -->
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
            <a href="#">Kerja</a>
            <a href="berita_pemilik.php">Berita</a>
            <a href="tentang_pemilik.php">Tentang</a>
        </div>
        <div class="auth-buttons">
            <?php if ($isLoggedIn): ?>
            <div class="profile-dropdown">
                <img src="<?= $avatarSrc ?>" alt="Avatar" class="avatar-icon">
                <div class="dropdown-content">
                    <span style="padding: 0 12px; display: block; font-weight: 1000;">
                        <?= htmlspecialchars($userName) ?>
                    </span>
                    <a href="/profile.php">Profil</a>
                    <a href="../home.html" id="logout-mobile">Logout</a>
                </div>
            </div>
            <?php else: ?>
            <a href="../Login_page/login_page.php" class="btn sign-in">Login</a>
            <?php endif; ?>
        </div>
    </div>
    </header>

<!-- Konten Detail -->
<div class="pt-32 max-w-4xl mx-auto bg-white rounded-2xl shadow-md p-8 mt-8">
  <h1 class="text-3xl font-bold text-green-700 mb-4"><?= $a ?></h1>
  <img src="../gambar/lahan-kosong.jpg" alt="Gambar Lahan" class="w-full h-64 object-cover rounded-xl mb-6 shadow-sm">

  <h2 class="text-xl font-semibold text-gray-800 mb-2"><?= htmlspecialchars($data['pemilik'] ?? 'Pemilik Tidak Diketahui') ?></h2>
  <p class="text-sm text-gray-500 mb-4">Kontak: <?= htmlspecialchars($data['kontak'] ?? 'N/A') ?></p>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700 mb-6">
    <p><strong>Jenis Lahan:</strong> <?= htmlspecialchars($data['jenis_lahan']) ?></p>
    <p><strong>Luas Lahan:</strong> <?= htmlspecialchars($data['luas_lahan']) ?></p>
    <p><strong>Lokasi:</strong> <?= htmlspecialchars($data['lokasi']) ?></p>
    <p><strong>Ketentuan:</strong> <?= htmlspecialchars($data['ketentuan']) ?></p>
    <p><strong>Gaji:</strong> Rp <?= number_format($data['gaji'], 0, ',', '.') ?> / bulan</p>
  </div>

  <h3 class="text-lg font-semibold text-gray-900 mb-2">Deskripsi Lahan</h3>
  <p class="text-gray-700 leading-relaxed"><?= nl2br(htmlspecialchars($data['deskripsi_rinci'])) ?></p>
</div>

</body>
</html>
