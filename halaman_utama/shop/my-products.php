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

// Fetch user's products
$stmt = $pdo->prepare("
    SELECT p.*, 
    CASE 
        WHEN p.image_url LIKE '/uploads/products/%' 
        THEN CONCAT('..', p.image_url)
        ELSE p.image_url 
    END as full_image_path 
    FROM products p
    WHERE user_id = ? 
    ORDER BY created_at DESC
");
$stmt->execute([$_SESSION['user']['id']]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk Saya - Nandur</title>
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
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-green-800">Produk Saya</h1>
            <a href="add_product.php" 
               class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                Tambah Produk
            </a>
        </div>

        <?php if (empty($products)): ?>
            <div class="bg-white rounded-lg shadow-md p-6">
                <p class="text-gray-600">Anda belum memiliki produk.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <?php foreach ($products as $product): ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <img src="<?= htmlspecialchars($product['full_image_path']) ?>" 
                            alt="<?= htmlspecialchars($product['name']) ?>"
                            class="w-full h-48 object-cover"
                            onerror="this.src='../assets/images/default-product.jpg'">
                        <div class="p-4">
                            <h3 class="font-semibold"><?= htmlspecialchars($product['name']) ?></h3>
                            <p class="text-green-600 font-bold mt-2">
                                Rp <?= number_format($product['price'], 0, ',', '.') ?>
                            </p>
                            <p class="text-gray-600 text-sm">Stok: <?= $product['stock'] ?></p>
                            <p class="text-gray-600 text-sm">Kategori: <?= $product['category'] ?></p>
                            <p class="text-gray-600 text-sm">
                                Status: <?= ucfirst($product['status']) ?>
                            </p>
                            <div class="mt-4 flex justify-end gap-2">

                                <button onclick="deleteProduct(<?= $product['id'] ?>)"
                                    class="text-red-600 hover:text-red-700">
                                    Hapus
                                </button>
                                <a href="edit_product.php?id=<?= $product['id'] ?>"
                                   class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                    Edit
                                </a>

                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
    
    <script>
function deleteProduct(productId) {
    if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
        fetch('delete_product.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                product_id: productId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Gagal menghapus produk');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        });
    }
}
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