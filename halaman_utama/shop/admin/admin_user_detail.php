<?php
session_start();
require '../../Login_page/koneksi.php';

// Check if user is admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../shop.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: admin_users.php');
    exit;
}

$userId = $_GET['id'];

// Fetch user details
$stmt = $pdo->prepare("SELECT * FROM user WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: admin_users.php');
    exit;
}

// Fetch user's products
$stmt = $pdo->prepare("
    SELECT * FROM products 
    WHERE user_id = ? 
    ORDER BY created_at DESC
");
$stmt->execute([$userId]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch user's orders
$stmt = $pdo->prepare("
    SELECT o.*, 
           COUNT(oi.id) as total_items,
           GROUP_CONCAT(p.name SEPARATOR ', ') as product_names
    FROM orders o
    LEFT JOIN order_items oi ON o.id = oi.order_id
    LEFT JOIN products p ON oi.product_id = p.id
    WHERE o.user_id = ?
    GROUP BY o.id
    ORDER BY o.created_at DESC
");
$stmt->execute([$userId]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get user statistics
$stats = [
    'total_products' => count($products),
    'active_products' => array_reduce($products, function($carry, $item) {
        return $carry + ($item['status'] === 'active' ? 1 : 0);
    }, 0),
    'total_orders' => count($orders),
    'total_spent' => array_reduce($orders, function($carry, $item) {
        return $carry + $item['total_amount'];
    }, 0)
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengguna - Admin Nandur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100 font-[Poppins]">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-green-800 text-white p-6">
            <div class="text-2xl font-bold mb-8">Nandur Admin</div>
            <nav class="space-y-2">
                <a href="admin_dashboard.php" class="block py-2 px-4 hover:bg-green-700 rounded">Dashboard</a>
                <a href="admin_orders.php" class="block py-2 px-4 hover:bg-green-700 rounded">Pesanan</a>
                <a href="admin_products.php" class="block py-2 px-4 hover:bg-green-700 rounded">Produk</a>
                <a href="admin_users.php" class="block py-2 px-4 bg-green-700 rounded">Pengguna</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <div class="max-w-5xl mx-auto">
                <div class="flex items-center justify-between mb-8">
                    <h1 class="text-3xl font-bold text-green-800">Detail Pengguna</h1>
                    <a href="admin_users.php" class="text-green-600 hover:text-green-700">← Kembali</a>
                </div>

                <!-- User Info -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <h2 class="text-xl font-semibold mb-4">Informasi Pribadi</h2>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-gray-600">Nama</p>
                                    <p class="font-semibold"><?= htmlspecialchars($user['nama']) ?></p>
                                </div>
                                <div>
                                    <p class="text-gray-600">Email</p>
                                    <p class="font-semibold"><?= htmlspecialchars($user['email']) ?></p>
                                </div>
                                <div>
                                    <p class="text-gray-600">WhatsApp</p>
                                    <a href="https://wa.me/<?= $user['whatsapp'] ?>" 
                                       target="_blank"
                                       class="text-green-600 hover:text-green-700">
                                        <?= $user['whatsapp'] ?>
                                    </a>
                                </div>
                                <div>
                                    <p class="text-gray-600">Role</p>
                                    <span class="px-2 py-1 rounded-full text-sm
                                        <?= $user['role'] === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' ?>">
                                        <?= ucfirst($user['role']) ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold mb-4">Statistik</h2>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-gray-50 p-4 rounded">
                                    <p class="text-gray-600">Total Produk</p>
                                    <p class="text-2xl font-bold"><?= $stats['total_products'] ?></p>
                                    <p class="text-sm text-gray-500">
                                        <?= $stats['active_products'] ?> aktif
                                    </p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded">
                                    <p class="text-gray-600">Total Pesanan</p>
                                    <p class="text-2xl font-bold"><?= $stats['total_orders'] ?></p>
                                    <p class="text-sm text-gray-500">
                                        Rp <?= number_format($stats['total_spent'], 0, ',', '.') ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User's Products -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-semibold mb-4">Produk</h2>
                    <?php if (empty($products)): ?>
                        <p class="text-gray-600">Belum ada produk</p>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="text-left border-b">
                                        <th class="pb-3">Nama</th>
                                        <th class="pb-3">Harga</th>
                                        <th class="pb-3">Stok</th>
                                        <th class="pb-3">Status</th>
                                        <th class="pb-3">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($products as $product): ?>
                                    <tr class="border-b">
                                        <td class="py-3"><?= htmlspecialchars($product['name']) ?></td>
                                        <td>Rp <?= number_format($product['price'], 0, ',', '.') ?></td>
                                        <td><?= $product['stock'] ?></td>
                                        <td><?= ucfirst($product['status']) ?></td>
                                        <td>
                                            <a href="admin_product_edit.php?id=<?= $product['id'] ?>" 
                                               class="text-green-600 hover:text-green-700">
                                                Edit
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- User's Orders -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4">Riwayat Pesanan</h2>
                    <?php if (empty($orders)): ?>
                        <p class="text-gray-600">Belum ada pesanan</p>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="text-left border-b">
                                        <th class="pb-3">ID</th>
                                        <th class="pb-3">Tanggal</th>
                                        <th class="pb-3">Total</th>
                                        <th class="pb-3">Status</th>
                                        <th class="pb-3">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $order): ?>
                                    <tr class="border-b">
                                        <td class="py-3">#<?= $order['id'] ?></td>
                                        <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                                        <td>Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></td>
                                        <td><?= ucfirst($order['status']) ?></td>
                                        <td>
                                            <a href="admin_order_detail.php?id=<?= $order['id'] ?>" 
                                               class="text-green-600 hover:text-green-700">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</body>
</html>