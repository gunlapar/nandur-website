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
    $backLink = '../pemilik_dashboard/home_after_login_pemilik.php';}

$avatarSrc = '../gambar/default_avatar.jpg'; // default

if ($userRole === 'petani') {
    $avatarSrc = '../gambar/farmer.png';
} elseif ($userRole === 'pemilik_lahan') {
    $avatarSrc = '../gambar/juragan.png';
} 

// Ambil item keranjang dari database
$stmt = $pdo->prepare("SELECT c.*, p.name, p.price, 
                        CASE 
                            WHEN p.image_url LIKE '/uploads/products/%'
                            THEN CONCAT('..', p.image_url)
                            ELSE p.image_url
                        END as full_image_path,
                        u.nama as seller_name
                        FROM cart c
                        JOIN products p ON c.product_id = p.id
                        JOIN user u ON p.user_id = u.id
                        WHERE c.user_id = ?");
$stmt->execute([$_SESSION['user']['id']]);
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Hitung total
$total = 0;
foreach ($cartItems as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang - Nandur</title>
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

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-green-800 mb-8">Keranjang Belanja</h1>
        
        <?php if (empty($cartItems)): ?>
            <p class="text-gray-600">Keranjang belanja kosong</p>
            <a href="shop.php" class="inline-block mt-4 px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                Mulai Belanja
            </a>
        <?php else: ?>
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6">
                    <?php foreach ($cartItems as $item): ?>
                    <div class="flex items-center gap-4 py-4 border-b">
                        <img src="<?= htmlspecialchars($item['full_image_path']) ?>" 
                            alt="<?= htmlspecialchars($item['name']) ?>"
                            class="w-24 h-24 object-cover rounded"
                            onerror="this.src='../assets/images/default-product.jpg'">
    
                        
                        <div class="flex-1">
                            <h3 class="font-semibold"><?= htmlspecialchars($item['name']) ?></h3>
                            <p class="text-gray-600">Penjual: <?= htmlspecialchars($item['seller_name']) ?></p>
                            <p class="text-green-600">Rp <?= number_format($item['price'], 0, ',', '.') ?></p>
                            
                            <div class="mt-2 flex items-center gap-2">
                                <button onclick="updateQuantity(<?= $item['id'] ?>, <?= $item['quantity'] - 1 ?>)"
                                        class="px-2 py-1 bg-gray-200 rounded">-</button>
                                <span class="px-4"><?= $item['quantity'] ?></span>
                                <button onclick="updateQuantity(<?= $item['id'] ?>, <?= $item['quantity'] + 1 ?>)"
                                        class="px-2 py-1 bg-gray-200 rounded">+</button>
                            </div>
                        </div>
                        
                        <button onclick="removeItem(<?= $item['id'] ?>)"
                                class="text-red-600 hover:text-red-800">Hapus</button>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="bg-gray-50 p-6">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-lg font-semibold">Total:</span>
                        <span class="text-2xl font-bold text-green-600">
                            Rp <?= number_format($total, 0, ',', '.') ?>
                        </span>
                    </div>
                    
                    <a href="checkout.php" 
                       class="block w-full py-3 text-center bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Lanjut ke Pembayaran
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script>
function updateQuantity(cartId, newQuantity) {
    if (newQuantity < 1) return;
    
    fetch('update_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            cart_id: cartId,
            quantity: newQuantity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Gagal mengupdate keranjang');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan');
    });
}

function removeItem(cartId) {
    if (!confirm('Hapus item ini dari keranjang?')) return;
    
    fetch('remove_from_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            cart_id: cartId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Gagal menghapus item');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan');
    });
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