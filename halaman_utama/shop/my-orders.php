<?php
session_start();
require '../Login_page/koneksi.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../Login_page/login_page.php');
    exit;
}

$isLoggedIn = isset($_SESSION['user']);
$userName = $isLoggedIn ? $_SESSION['user']['nama'] : '';
$userRole = $isLoggedIn ? $_SESSION['user']['role'] : '';

$backLink = '#'; // Default value
if ($userRole === 'petani') {
    $backLink = '../petani_dashboard/home_after_login_petani.php';
} elseif ($userRole === 'pemilik_lahan') {
    $backLink = '../pemilik_dashboard/home_after_login_pemilik.php';
}

$avatarSrc = '../gambar/default_avatar.jpg'; // default

if ($userRole === 'petani') {
    $avatarSrc = '../gambar/farmer.png';
} elseif ($userRole === 'pemilik_lahan') {
    $avatarSrc = '../gambar/juragan.png';
} 


// Fetch user's orders
$stmt = $pdo->prepare("
    SELECT 
        o.*,
        GROUP_CONCAT(
            DISTINCT CONCAT(
                p.name, '|',
                p.category, '|',
                oi.quantity, '|',
                p.stock
            )
            SEPARATOR ';;'
        ) as products_info
    FROM orders o
    LEFT JOIN order_items oi ON o.id = oi.order_id
    LEFT JOIN products p ON oi.product_id = p.id
    WHERE o.user_id = ?
    GROUP BY o.id
    ORDER BY o.created_at DESC
");
$stmt->execute([$_SESSION['user']['id']]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya - Nandur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="navbar.css">
</head>
<body class="bg-gray-100 font-[Poppins]">
<!-- Navbar -->
<header class="text-white">
    <!-- Desktop Navigation -->
    <div class="nav-container">
        <div class="flex items-center gap-4">
            <div class="text-2xl font-bold">Nandur</div>
        </div>
        <div class="menu-items">
            <a href="<?= htmlspecialchars($backLink) ?>" class="hover:text-green-200">Beranda</a>
            <a href="shop.php" class="hover:text-green-200">Toko</a>
            <a href="my-products.php" class="hover:text-green-200">Produk Saya</a>
            <a href="cart.php" class="hover:text-green-200">Keranjang</a>
            <a href="my-orders.php" class="hover:text-green-200">Pesanan Saya</a>
            <a href="seller-orders.php" class="hover:text-green-200">Penjualan Saya</a>
            
            <?php if ($isLoggedIn): ?>
                <div class="profile-dropdown">
                    <img src="<?= htmlspecialchars($avatarSrc) ?>" alt="Avatar" class="avatar-icon">
                    <div class="dropdown-content">
                        <span class="text-green-800 px-4 py-3 block font-semibold border-b border-gray-200">
                            <?= htmlspecialchars($userName) ?>
                        </span>
                        <?php if ($userRole === 'petani'): ?>
                            <a href="../petani_dashboard/profile2.php">Profil</a>
                        <?php elseif ($userRole === 'pemilik_lahan'): ?>
                            <a href="../pemilik_dashboard/profile1.php">Profil</a>
                        <?php endif; ?>
                        <a href="../home.html" class="text-red-600 hover:bg-red-50">Logout</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</header>

<!-- Mobile Navigation -->
<div class="mobile-navmenu container mx-auto px-4">
    <div class="logo">Nandur</div>
    <div class="menu-icons">
        <img class="menu-icon" src="../gambar/menu-icon.png" alt="menu">
        <img class="close-icon" src="../gambar/close-iconn.png" alt="close">
    </div>
</div>

<div class="mobile-menu-items">
    <div class="menu-items">
        <a href="<?= htmlspecialchars($backLink) ?>">Beranda</a>
        <a href="shop.php">Toko</a>
        <a href="my-products.php">Produk Saya</a>
        <a href="cart.php">Keranjang</a>
        <a href="my-orders.php">Pesanan Saya</a>
        <a href="seller-orders.php">Penjualan Saya</a>
        <?php if ($isLoggedIn): ?>
            <div class="profile-section mt-4 border-t border-gray-600 pt-4">
                <span class="text-white block mb-2"><?= htmlspecialchars($userName) ?></span>
                <?php if ($userRole === 'petani'): ?>
                    <a href="../petani_dashboard/profile2.php">Profil</a>
                <?php elseif ($userRole === 'pemilik_lahan'): ?>
                    <a href="../pemilik_dashboard/profile1.php">Profil</a>
                <?php endif; ?>
                <a href="../home.html" class="text-red-400">Logout</a>
            </div>
        <?php endif; ?>
    </div>
</div>


    <main class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-green-800 mb-8">Riwayat Pesanan Saya</h1>

        <?php if (empty($orders)): ?>
            <div class="bg-white rounded-lg shadow-md p-6">
                <p class="text-gray-600">Anda belum memiliki pesanan.</p>
                <a href="shop.php" class="inline-block mt-4 px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    Mulai Belanja
                </a>
            </div>
        <?php else: ?>
            <div class="grid gap-6">
                <?php foreach ($orders as $order): ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="font-semibold text-lg">Pesanan #<?= $order['id'] ?></h3>
                                    <p class="text-gray-600">
                                        <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?>
                                    </p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-block px-3 py-1 rounded-full text-sm
                                        <?php
                                        switch($order['status']) {
                                            case 'pending':
                                                echo 'bg-yellow-100 text-yellow-800';
                                                break;
                                            case 'processing':
                                                echo 'bg-blue-100 text-blue-800';
                                                break;
                                            case 'completed':
                                                echo 'bg-green-100 text-green-800';
                                                break;
                                            case 'cancelled':
                                                echo 'bg-red-100 text-red-800';
                                                break;
                                        }
                                        ?>">
                                        <?= ucfirst($order['status']) ?>
                                    </span>
                                </div>
                            </div>

                            <div class="border-t pt-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="mt-4 border-t pt-4">
                                        <p class="text-gray-600 mb-2">Produk:</p>
                                        <?php 
                                        $productsInfo = explode(';;', $order['products_info'] ?? '');
                                        foreach ($productsInfo as $productInfo): 
                                            list($name, $category, $quantity, $stock) = explode('|', $productInfo);
                                        ?>
                                            <div class="mb-4">
                                                <p class="text-sm font-semibold"><?= htmlspecialchars($name) ?></p>
                                                <p class="text-sm text-gray-600">Kategori: <?= htmlspecialchars($category) ?></p>
                                                <p class="text-sm text-gray-600">
                                                    Jumlah: <?= $quantity ?> 
                                                    (Stok tersedia: <?= $stock ?>)
                                                </p>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>

                                    <div>
                                        <p class="text-gray-600">Total Pembayaran</p>
                                        <p class="font-semibold">
                                            Rp <?= number_format($order['total_amount'], 0, ',', '.') ?>
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <a href="order-detail.php?id=<?= $order['id'] ?>" 
                                       class="text-green-600 hover:text-green-700 font-semibold">
                                        Lihat Detail →
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
    <script>
                // Mobile menu functionality
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
    </script>
</body>
</html>