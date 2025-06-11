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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Nandur - Dukung Petani Lokal</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Oxygen:wght@700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../jobs/jobs-1.css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="icon" type="image/x-icon" href="../gambar/nandur_logo.png" />
</head>
<body id="home" class="min-h-screen bg-gray-100">

<header id="home">
    <div class="nav-container">
        <nav>
            <div class="logo">nandur</div>
        </nav>
        <div class="menu-items">
            <a href="home_after_login_pemilik.php">Beranda</a>
            <a href="#">Kerja</a>
            <a href="berita1.php">Berita</a>
            <a href="tentang_pemilik.php">Tentang</a>
            <a href="../shop/shop.php">Belanja</a>
        </div>
        <!-- Profile / Login -->
        <?php if ($isLoggedIn): ?>
            <div class="profile-dropdown">
                <?php
                $avatarSrc = '../gambar/default_avatar.jpg';
                if ($userRole === 'petani') {
                    $avatarSrc = '../gambar/farmer.png';
                } elseif ($userRole === 'pemilik_lahan') {
                    $avatarSrc = '../gambar/juragan.png';
                }
                ?>
                <img src="<?= $avatarSrc ?>" alt="Avatar" class="avatar-icon" />
                <div class="dropdown-content">
                    <span style="padding: 0 12px; display: block; font-weight: 1000;"><?= htmlspecialchars($userName) ?></span>
                    <a href="profile1.php">Profil</a>
                    <a href="../home.html" id="logout">Logout</a>
                </div>
            </div>
        <?php else: ?>
            <a href="../Login_page/login_page.php" class="btn sign-in">Login</a>
        <?php endif; ?>
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
            <a href="#">Kerja</a>
            <a href="berita1.php">Berita</a>
            <a href="tentang_pemilik.php">Tentang</a>
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
            <a href="../Login_page/login_page.php" class="btn sign-in">Login</a>
            <?php endif; ?>
        </div>
    </div>
    
</header>

<section class="md:h-full flex items-center text-gray-600">
    <div class="container px-5 py-24 mx-auto">
        <div class="text-center mb-12">
            <h5 class="text-base md:text-lg text-indigo-700 mb-1">Lihat Peluang Terbaru</h5>
            <h1 class="text-4xl md:text-6xl text-gray-700 font-semibold">Lahan & Kesempatan Kerja Petani</h1>
        </div>
        <div class="flex justify-end mb-6">
            <a href="../jobs/form_lahan.php" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg shadow">+ Tambah Lahan</a>
        </div>

        <!-- Cards -->
        <div class="flex flex-wrap -m-4">
            <?php foreach ($data as $row): ?>
                <div class="p-4 sm:w-1/2 lg:w-1/3">
                    <div class="h-full border-2 border-gray-200 border-opacity-60 rounded-lg overflow-hidden">
                        <?php
                            $imgPath = !empty($row['foto']) ? '/gambar_lahan/' . htmlspecialchars($row['foto']) : '/gambar/lahan-kosong.jpg';
                        ?>
                        <img class="lg:h-72 md:h-48 w-full object-cover object-center" src="<?= $imgPath ?>" alt="lahan" />
                        <div class="p-6 hover:bg-green-700 hover:text-white transition duration-300 ease-in">
                            <h2 class="text-base font-medium text-indigo-300 mb-1">Upload <?= htmlspecialchars($row['tanggal']) ?></h2>
                            <h1 class="text-2xl font-semibold mb-3">Lahan Pak <?= htmlspecialchars($row['pemilik']) ?></h1>
                            <p class="leading-relaxed mb-3"><?= htmlspecialchars($row['deskripsi']) ?></p>
                            <p><strong>Lokasi:</strong> <?= htmlspecialchars($row['lokasi']) ?></p>
                            <p><strong>Jenis Lahan:</strong> <?= htmlspecialchars($row['jenis_lahan']) ?></p>
                            <p><strong>Jenis Tanaman:</strong> <?= htmlspecialchars($row['jenis_tanaman']) ?></p>
                            <p><strong>Luas Lahan:</strong> <?= htmlspecialchars($row['luas_lahan']) ?></p>
                            <p><strong>Ketentuan Bertani:</strong> <?= nl2br(htmlspecialchars($row['ketentuan_bertani'])) ?></p>
                            <div class="flex items-center flex-wrap mt-2">
                                <button
                                    class="text-indigo-300 inline-flex items-center md:mb-2 lg:mb-0 open-modal"
                                    data-pemilik="<?= htmlspecialchars($row['pemilik']) ?>"
                                    data-tanggal="<?= htmlspecialchars($row['tanggal']) ?>"
                                    data-deskripsi="<?= htmlspecialchars($row['deskripsi_rinci']) ?>"
                                    data-gaji="<?= number_format($row['harga'], 0, ',', '.') ?>"
                                    data-lokasi="<?= htmlspecialchars($row['lokasi']) ?>"
                                    data-jenis-lahan="<?= htmlspecialchars($row['jenis_lahan']) ?>"
                                    data-jenis-tanaman="<?= htmlspecialchars($row['jenis_tanaman']) ?>"
                                    data-luas="<?= htmlspecialchars($row['luas_lahan']) ?>"
                                    data-ketentuan="<?= nl2br(htmlspecialchars($row['ketentuan_bertani'])) ?>"
                                    data-foto="<?= $imgPath ?>"
                                >
                                    Read More
                                    <svg class="w-4 h-4 ml-2" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12h14"></path>
                                        <path d="M12 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                                <span class="text-gray-400 mr-3 inline-flex items-center lg:ml-auto md:ml-0 ml-auto leading-none text-sm pr-3 py-1 border-r-2 border-gray-200">
                                    <svg class="w-4 h-4 mr-1" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    Rp <?= number_format($row['harga'], 0, ',', '.') ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Modal Popup -->
<!-- Modal Popup -->
<div id="popupModal" class="fixed inset-0 bg-opacity-50 backdrop-blur-md flex items-center justify-center z-50 hidden">
  <div class="bg-white rounded-lg shadow-lg p-6 max-w-2xl w-full relative">
    <button id="closeModal" class="absolute top-2 right-2 text-gray-600 hover:text-red-600 text-3xl font-bold leading-none">&times;</button>
    
    <h2 class="text-2xl font-bold mb-2" id="modalPemilik"></h2>
    
    <!-- Gambar diletakkan di bawah judul -->
    <img id="modalFoto" src="" alt="Foto Lahan" class="w-full h-64 object-cover rounded mb-4" />
    
    <p class="text-sm text-gray-500 mb-2" id="modalTanggal"></p>
    <p class="mb-4 text-gray-800" id="modalDeskripsiRinci"></p>
    <ul class="text-gray-700 space-y-2 text-sm">
      <li><strong>Gaji:</strong> <span id="modalGaji"></span></li>
      <li><strong>Lokasi:</strong> <span id="modalLokasi"></span></li>
      <li><strong>Jenis Lahan:</strong> <span id="modalJenisLahan"></span></li>
      <li><strong>Jenis Tanaman:</strong> <span id="modalJenisTanaman"></span></li>
      <li><strong>Luas Lahan:</strong> <span id="modalLuas"></span></li>
      <li><strong>Ketentuan:</strong> <span id="modalKetentuan"></span></li>
    </ul>
  </div>
</div>

<script>
  // Buka modal dan isi data
  document.querySelectorAll('.open-modal').forEach(button => {
    button.addEventListener('click', () => {
      document.getElementById('modalPemilik').textContent = 'Lahan Pak ' + button.dataset.pemilik;
      document.getElementById('modalTanggal').textContent = 'Upload: ' + button.dataset.tanggal;
      document.getElementById('modalDeskripsiRinci').textContent = button.dataset.deskripsi;
      document.getElementById('modalGaji').textContent = 'Rp ' + button.dataset.gaji;
      document.getElementById('modalLokasi').textContent = button.dataset.lokasi;
      document.getElementById('modalJenisLahan').textContent = button.dataset.jenisLahan;
      document.getElementById('modalJenisTanaman').textContent = button.dataset.jenisTanaman;
      document.getElementById('modalLuas').textContent = button.dataset.luas;
      // Ketentuan sudah ada <br> karena nl2br, gunakan innerHTML
      document.getElementById('modalKetentuan').innerHTML = button.dataset.ketentuan;

      // Set src gambar modal
      document.getElementById('modalFoto').src = button.dataset.foto;

      document.getElementById('popupModal').classList.remove('hidden');
    });
  });

  // Tutup modal
  document.getElementById('closeModal').addEventListener('click', () => {
    document.getElementById('popupModal').classList.add('hidden');
  });

  // Tutup modal klik di luar konten modal
  document.getElementById('popupModal').addEventListener('click', (e) => {
    if (e.target === document.getElementById('popupModal')) {
      document.getElementById('popupModal').classList.add('hidden');
    }
  });


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

<footer class="bg-green-800 text-white py-6 mt-16">
    <div class="container mx-auto text-center">
        &copy; 2025 Nandur. Semua Hak Cipta Dilindungi.
    </div>
</footer>

</body>
</html>
