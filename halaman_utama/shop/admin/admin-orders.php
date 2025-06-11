<?php

session_start();
require '../../Login_page/koneksi.php';

// Check if user is admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../shop.php');
    exit;
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$_POST['status'], $_POST['order_id']]);
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Get all orders with customer and product details
$orders = $pdo->query("
    SELECT 
        o.*,
        u.nama as customer_name,
        u.whatsapp as customer_whatsapp,
        COUNT(oi.id) as total_items,
        GROUP_CONCAT(p.name SEPARATOR ', ') as product_names
    FROM orders o
    JOIN user u ON o.user_id = u.id
    LEFT JOIN order_items oi ON o.id = oi.order_id
    LEFT JOIN products p ON oi.product_id = p.id
    GROUP BY o.id
    ORDER BY o.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pesanan - Admin Nandur</title>
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
                <a href="admin_orders.php" class="block py-2 px-4 bg-green-700 rounded">Pesanan</a>
                <a href="admin_products.php" class="block py-2 px-4 hover:bg-green-700 rounded">Produk</a>
                <a href="admin_users.php" class="block py-2 px-4 hover:bg-green-700 rounded">Pengguna</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <h1 class="text-3xl font-bold text-green-800 mb-8">Kelola Pesanan</h1>

            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 text-left">
                                <th class="px-6 py-3">ID</th>
                                <th class="px-6 py-3">Pembeli</th>
                                <th class="px-6 py-3">Produk</th>
                                <th class="px-6 py-3">Total</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3">Tanggal</th>
                                <th class="px-6 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                            <tr class="border-t">
                                <td class="px-6 py-4">#<?= $order['id'] ?></td>
                                <td class="px-6 py-4">
                                    <?= htmlspecialchars($order['customer_name']) ?>
                                    <a href="https://wa.me/<?= $order['customer_whatsapp'] ?>" 
                                       target="_blank"
                                       class="text-sm text-green-600 hover:text-green-700 block">
                                        WhatsApp
                                    </a>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="max-w-xs truncate" title="<?= htmlspecialchars($order['product_names']) ?>">
                                        <?= htmlspecialchars($order['product_names']) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    Rp <?= number_format($order['total_amount'], 0, ',', '.') ?>
                                </td>
                                <td class="px-6 py-4">
                                    <form method="POST" class="flex items-center gap-2">
                                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                        <select name="status" 
                                                onchange="this.form.submit()"
                                                class="px-2 py-1 border rounded text-sm">
                                            <?php
                                            $statuses = ['pending', 'processing', 'completed', 'cancelled'];
                                            foreach ($statuses as $status) {
                                                $selected = $status === $order['status'] ? 'selected' : '';
                                                echo "<option value=\"$status\" $selected>" . 
                                                     ucfirst($status) . 
                                                     "</option>";
                                            }
                                            ?>
                                        </select>
                                    </form>
                                </td>
                                <td class="px-6 py-4">
                                    <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?>
                                </td>
                                <td class="px-6 py-4">
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
            </div>
        </main>
    </div>
</body>
</html>