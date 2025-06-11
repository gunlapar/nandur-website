<?php
session_start();
require '../Login_page/koneksi.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

try {
    // Modified query to correctly get seller's orders
$stmt = $pdo->prepare("
    SELECT DISTINCT
        o.id,
        o.created_at,
        o.status,
        o.shipping_address,
        o.payment_method,
        u.nama as customer_name,
        u.whatsapp as customer_whatsapp,
        GROUP_CONCAT(
            DISTINCT CONCAT(
                p.name, '|',
                p.category, '|',
                oi.quantity, '|',
                p.stock
            )
            SEPARATOR ';;'
        ) as products_info,
        COUNT(DISTINCT oi.id) as total_items,
        SUM(oi.quantity * oi.price) as seller_total
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    JOIN products p ON oi.product_id = p.id
    JOIN user u ON o.user_id = u.id
    WHERE p.user_id = ?
    GROUP BY o.id, o.created_at, o.status, o.shipping_address, o.payment_method, u.nama, u.whatsapp
    ORDER BY o.created_at DESC
");

    $stmt->execute([$_SESSION['user']['id']]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Add debugging
    error_log("Seller ID: " . $_SESSION['user']['id']);
    error_log("Found orders: " . count($orders));
    
    if (empty($orders)) {
        // Debug query
        $stmt = $pdo->prepare("
            SELECT COUNT(*) as total 
            FROM orders o 
            JOIN order_items oi ON o.id = oi.order_id 
            JOIN products p ON oi.product_id = p.id 
            WHERE p.user_id = ?
        ");
        $stmt->execute([$_SESSION['user']['id']]);
        $count = $stmt->fetch(PDO::FETCH_ASSOC);
        error_log("Total related orders: " . $count['total']);
    }

} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    $orders = [];
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Masuk - Nandur</title>
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
    <h1 class="text-3xl font-bold text-green-800 mb-8">Pesanan Masuk</h1>

    <?php if (empty($orders)): ?>
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-gray-600">Belum ada pesanan masuk.</p>
        </div>
    <?php else: ?>
        <div class="grid gap-6">
            <?php foreach ($orders as $order): ?>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="font-semibold text-lg">Pesanan #<?= htmlspecialchars($order['id']) ?></h3>
                            <p class="text-gray-600">
                                <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?>
                            </p>
                            
                            <!-- Status Management -->
                            <div class="mt-2 flex items-center gap-2">
                                <select onchange="updateOrderStatus(<?= $order['id'] ?>, this.value)" 
                                        class="text-sm border rounded px-2 py-1">
                                    <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="processing" <?= $order['status'] === 'processing' ? 'selected' : '' ?>>Diproses</option>
                                    <option value="shipped" <?= $order['status'] === 'shipped' ? 'selected' : '' ?>>Dikirim</option>
                                    <option value="completed" <?= $order['status'] === 'completed' ? 'selected' : '' ?>>Selesai</option>
                                    <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Dibatalkan</option>
                                </select>
                                <button onclick="deleteOrder(<?= $order['id'] ?>)"
                                        class="text-red-600 hover:text-red-700 text-sm">
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="text-gray-600">Pembeli:</p>
                            <p class="font-semibold"><?= htmlspecialchars($order['customer_name']) ?></p>
                            <?php if (!empty($order['customer_whatsapp'])): ?>
                                <a href="https://wa.me/<?= htmlspecialchars($order['customer_whatsapp']) ?>" 
                                   target="_blank"
                                   class="text-green-600 hover:text-green-700">
                                    WhatsApp
                                </a>
                            <?php endif; ?>
                        </div>
                        <div>
                            <p class="text-gray-600">Total Pembelian:</p>
                            <p class="font-semibold">
                                Rp <?= number_format($order['seller_total'] ?? 0, 0, ',', '.') ?>
                            </p>
                        </div>
                    </div>

                    <div class="border-t pt-4">
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
                                    <span class="<?= $stock < $quantity ? 'text-red-600' : 'text-green-600' ?>">
                                        (Stok tersedia: <?= $stock ?>)
                                    </span>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="mt-4 border-t pt-4">
                        <p class="text-gray-600">Alamat Pengiriman:</p>
                        <p class="whitespace-pre-line">
                            <?= nl2br(htmlspecialchars($order['shipping_address'])) ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

    <script>
async function updateOrderStatus(orderId, newStatus) {
    if (!confirm('Ubah status pesanan menjadi ' + newStatus + '?')) return;
    
    try {
        const response = await fetch('update_order_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                order_id: orderId,
                status: newStatus
            })
        });
        
        const data = await response.json();
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Gagal mengubah status');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan');
    }
}

async function deleteOrder(orderId) {
    if (!confirm('Apakah Anda yakin ingin menghapus pesanan ini?')) return;
    
    try {
        const response = await fetch('delete_order.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                order_id: orderId
            })
        });
        
        const data = await response.json();
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Gagal menghapus pesanan');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan');
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