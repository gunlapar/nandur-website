<?php
session_start();
session_unset();         // Hapus semua variabel dalam session
session_destroy();       // Hancurkan session
if (!isset($_SESSION['user'])) {
    // User belum login, redirect ke halaman login
    header('Location: /Login_page/login_page.php');
    exit;
}

require '../Login_page/koneksi.php';

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
    <link href="https://fonts.googleapis.com/css2?family=Oxygen:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="jobs-1.css">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="icon" type="image/x-icon" href="/gambar/nandur_logo.png">
</head>
<body id="home" class="min-h-screen bg-gray-100">

<header id="home">
    <div class="nav-container">
        <nav>
            <div class="logo">nandur</div>
        </nav>
        <div class="menu-items">
            <a href="/home.html">Home</a>
            <a href="#">Jobs</a>
            <a href="#">News</a>
            <a href="#">About Us</a>
        </div>
        <div class="auth-buttons">
            <a href="/Login_page/login_page.php" class="btn sign-in">Login</a>
        </div>
    </div>

    <div class="mobile-navmenu">
        <div class="logo">nandur</div>
        <div class="menu-icons">
            <img class="menu-icon" src="/gambar/menu-icon.png" alt="">
            <img class="close-icon" src="/gambar/close-iconn.png" alt="">
        </div>
    </div>

    <div class="mobile-menu-items">
        <div class="menu-items">
            <a href="/home.html">Home</a>
            <a href="#">Jobs</a>
            <a href="#">News</a>
            <a href="#">About Us</a>
        </div>
        <div class="auth-buttons">
            <a href="/Login_page/login_page.php" class="btn sign-in">Login</a>
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
    <a href="../jobs/form_lahan.php" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg shadow">
        + Tambah Lahan
    </a>
</div>

        <div class="flex flex-col p-2 py-6">
            <!-- Search Bar -->
            <div class="bg-white items-center justify-between w-full flex rounded-full shadow-lg p-2 mb-5">
                <input class="font-bold uppercase rounded-full w-full py-4 pl-4 text-gray-700 bg-gray-100 leading-tight focus:outline-none focus:shadow-outline lg:text-sm text-xs" type="text" placeholder="Search">
                <div class="bg-gray-600 p-2 hover:bg-blue-400 cursor-pointer mx-2 rounded-full">
                    <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div id="filterToggle" class="bg-gray-600 p-2 hover:bg-blue-400 cursor-pointer mx-2 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill="white" d="M3 17v2h6v-2H3zM3 5v2h10V5H3zm10 16v-2h8v-2h-8v-2h-2v6h2zM7 9v2H3v2h4v2h2V9H7zm14 4v-2H11v2h10zm-6-4h2V7h4V5h-4V3h-2v6z"/>
                    </svg>
                </div>
            </div>

            <!-- Filter Dropdowns -->
            <div id="filterPanel" class="flex flex-col md:flex-row gap-4 w-full hidden">
                <?php
                $filters = [
                    'Lokasi' => ['Sleman', 'Jogja', 'Bantul'],
                    'Jenis Lahan' => ['Sawah', 'Kebun', 'Kosong'],
                    'Jenis Tanaman' => ['Kosong', 'Jeruk', 'Padi'],
                    'Luas Lahan' => ['10M × 10M', '20M × 20M', '25M × 25M']
                ];
                foreach ($filters as $title => $options): ?>
                    <div class="dropdown relative border-2 border-dashed border-purple-400 p-2 rounded flex-1">
                        <button class="bg-white px-4 py-2 border w-full flex justify-between items-center">
                            <?= $title ?> <span>▼</span>
                        </button>
                        <div class="dropdown-menu absolute mt-1 bg-gray-100 shadow w-full z-10 hidden">
                            <div class="p-2 space-y-1 accent-green-500">
                                <?php foreach ($options as $option): ?>
                                    <label><input type="checkbox"> <?= $option ?></label><br>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Cards -->
        <div class="flex flex-wrap -m-4">
            <?php foreach ($data as $row): ?>
                <div class="p-4 sm:w-1/2 lg:w-1/3">
                    <div class="h-full border-2 border-gray-200 border-opacity-60 rounded-lg overflow-hidden">
                        <img class="lg:h-72 md:h-48 w-full object-cover object-center" src="/gambar/lahan-kosong.jpg" alt="lahan">
                        <div class="p-6 hover:bg-green-700 hover:text-white transition duration-300 ease-in">
                            <h2 class="text-base font-medium text-indigo-300 mb-1">Upload <?= htmlspecialchars($row['tanggal']) ?></h2>
                            <h1 class="text-2xl font-semibold mb-3">Lahan Pak <?= htmlspecialchars($row['pemilik']) ?></h1>
                            <p class="leading-relaxed mb-3"><?= htmlspecialchars($row['deskripsi']) ?></p>
                            <div class="flex items-center flex-wrap">
                                <a href="#" class="text-indigo-300 inline-flex items-center md:mb-2 lg:mb-0">Read More
                                    <svg class="w-4 h-4 ml-2" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12h14"></path>
                                        <path d="M12 5l7 7-7 7"></path>
                                    </svg>
                                </a>
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

<script>
    const moreTextEls = document.querySelectorAll('.more-text');
    const toggleBtnEl = document.getElementById('toggle-btn');
    const hideBtnEl = document.getElementById('hide-btn');

    toggleBtnEl?.addEventListener('click', () => {
        moreTextEls.forEach(el => el.classList.remove('hidden'));
        toggleBtnEl.classList.add('hidden');
        hideBtnEl.classList.remove('hidden');
    });

    hideBtnEl?.addEventListener('click', () => {
        moreTextEls.forEach(el => el.classList.add('hidden'));
        toggleBtnEl.classList.remove('hidden');
        hideBtnEl.classList.add('hidden');
    });
</script>

<footer class="bg-green-800 text-white py-6 mt-16">
    <div class="container mx-auto text-center">
        &copy; 2025 Nandur. Semua Hak Cipta Dilindungi.
    </div>
</footer>

<script src="/jobs/jobs-dwn.js"></script>
<script src="/jobs/jobs-header.js"></script>
</body>
</html>
