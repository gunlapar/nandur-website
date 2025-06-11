<?php
session_start();
require '../Login_page/koneksi.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../Login_page/login.php");
    exit;
}

$userId = $_SESSION['user']['id'];
$userRole = $_SESSION['user']['role'];

if ($userRole === 'petani') {
    $avatarSrc = '../gambar/farmer.png';
} elseif ($userRole === 'pemilik_lahan') {
    $avatarSrc = '../gambar/juragan.png';
} else {
    $avatarSrc = '../gambar/default.png';
}
if ($userRole === 'petani') {
    $home = '../petani_dashboard/home_after_login_petani.php';
} elseif ($userRole === 'pemilik_lahan') {
    $home = '../pemilik_dashboard/home_after_login_pemilik.php';
} 

if ($userRole === 'petani') {
    $kerja = '../petani_dashboard/jobs-login_petani.php';
} elseif ($userRole === 'pemilik_lahan') {
    $kerja = '../pemilik_dashboard/jobs-login_pemilik.php';
}

if ($userRole === 'petani') {
    $berita = '../petani_dashboard/berita2.php';
} elseif ($userRole === 'pemilik_lahan') {
    $berita = '../pemilik_dashboard/berita1.php';
}

if ($userRole === 'petani') {
    $tentang = '../petani_dashboard/tentang_petani.php';
} elseif ($userRole === 'pemilik_lahan') {
    $tentang = '../pemilik_dashboard/tentang_pemilik.php';
}

$stmt = $pdo->prepare("SELECT * FROM user WHERE id = ?");
$stmt->execute([$userId]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    echo "User tidak ditemukan.";
    exit;
}

$updateSuccess = isset($_GET['update']) && $_GET['update'] === 'success';

function insert_space_every_n_chars($string, $n = 40) {
    return trim(chunk_split($string, $n, ' '));
}


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
    <link href="https://fonts.googleapis.com/css2?family=Oxygen:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../jobs/jobs-1.css">
    
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="icon" type="image/x-icon" href="../gambar/nandur_logo.png">
<body class="bg-green-900 min-h-screen flex justify-center items-center p-4 font-poppins">
  <header id="home">
    <div class="nav-container">
        <nav>
            <div class="logo">nandur</div>
        </nav>
        <div class="menu-items flex justify-center gap-8">
          <a href="<?= $home?>" class="hover:text-green-700">Beranda</a>
          <a href="<?= $kerja?>" class="hover:text-green-700">Kerja</a>
          <a href="<?= $berita?>" class="hover:text-green-700">Berita</a>
          <a href="<?= $tentang?>" class="hover:text-green-700">Tentang</a>
          <a href="../shop/shop.php">Belanja</a>
        </div>

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
            <a href="home_after_login_petani.php">Beranda</a>
            <a href="#">Kerja</a>
            <a href="berita2.php">Berita</a>
            <a href="tentang_petani.php">Tentang</a>
            <a href="../shop/shop.php">Belanja</a>
        </div>

    </header>
  
    <?php if ($updateSuccess): ?>
      <div class="bg-green-100 text-green-800 p-3 rounded max-w-6xl w-full mb-6 text-center font-semibold shadow">
        Profil berhasil diperbarui!
      </div>
    <?php endif; ?>

    <div class="flex flex-col md:flex-row gap-6 max-w-6xl w-full">
        <!-- Card Kiri -->
        <div class="card-kiri bg-white rounded-lg shadow-md p-6 flex flex-col items-center text-center w-full md:w-1/2">
            <img src="<?= htmlspecialchars($data['foto']) ?: $avatarSrc ?>" 
                 alt="Foto <?= htmlspecialchars($data['nama']) ?>" 
                 class="w-40 h-40 rounded-full object-cover mb-4" />
            <h2 class="text-xl font-bold text-green-900"><?= htmlspecialchars($data['nama']) ?></h2>
            <p class="text-green-700 font-semibold capitalize"><?= htmlspecialchars($data['role']) ?></p>
           <p class="deskripsi mt-2 text-sm text-gray-600 italic">
              "<?= htmlspecialchars(insert_space_every_n_chars($data['deskripsi'], 40)) ?>"
          </p>
        </div>

        <!-- Card Kanan -->
        <div class="bg-white rounded-lg shadow-md p-6 text-green-900 w-full md:w-1/2 space-y-4">
            <?php if ($data['role'] === 'pemilik_lahan') : ?>
                <div>
                    <p class="font-semibold">Lahan Yang Dimiliki</p>
                    <p><?= htmlspecialchars($data['lahan_dimiliki']) ?></p>
                </div>
                <div>
                    <p class="font-semibold">Jumlah Total Lahan</p>
                    <p><?= htmlspecialchars($data['total_lahan']) ?></p>
                </div>
                <div>
                    <p class="font-semibold">Tipe Lahan</p>
                    <p><?= htmlspecialchars($data['tipe_lahan']) ?></p>
                </div>
                <div>
                    <p class="font-semibold">Durasi Kontrak Standard</p>
                    <p><?= htmlspecialchars($data['durasi_kontrak']) ?></p>
                </div>
                <div>
                    <p class="font-semibold">Persebaran Lahan</p>
                    <p><?= htmlspecialchars($data['persebaran']) ?></p>
                </div>
                <div>
                    <p class="font-semibold">Kontak WhatsApp</p>
                    <p><?= htmlspecialchars($data['whatsapp']) ?></p>
                </div>
            <?php elseif ($data['role'] === 'petani') : ?>
                <div>
                    <p class="font-semibold">Lama Pengalaman Bertani</p>
                    <p><?= htmlspecialchars($data['pengalaman']) ?></p>
                </div>
                <div>
                    <p class="font-semibold">Jenis Tanaman Yang Dikuasai</p>
                    <p><?= htmlspecialchars($data['tanaman_dikuasai']) ?></p>
                </div>
                <div>
                    <p class="font-semibold">Kemampuan Khusus (Pilihan)</p>
                    <p><?= htmlspecialchars($data['kemampuan_khusus']) ?></p>
                </div>
                <div>
                    <p class="font-semibold">Wilayah Yang Bersedia Digarap</p>
                    <p><?= htmlspecialchars($data['wilayah_digarap']) ?></p>
                </div>
                <div>
                    <p class="font-semibold">Tim Kerja</p>
                    <p><?= htmlspecialchars($data['tim_kerja']) ?></p>
                </div>
                <div>
                    <p class="font-semibold">Kontak WhatsApp</p>
                    <p><?= htmlspecialchars($data['whatsapp']) ?></p>
                </div>
            <?php else: ?>
                <p>Detail profil belum tersedia untuk role ini.</p>
            <?php endif; ?>

            <a href="profile_edit.php" 
               class="inline-block mt-4 bg-green-700 hover:bg-green-800 text-white px-4 py-2 rounded shadow transition">
                Edit Profile
            </a>
        </div>
    </div>
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
