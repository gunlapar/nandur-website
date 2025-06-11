<?php
session_start();
require '../../Login_page/koneksi.php';

// Check if user is admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../shop.php');
    exit;
}

// Get statistics
$stats = [
    'total_orders' => $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn(),
    'pending_orders' => $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'pending'")->fetchColumn(),
    'total_products' => $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn(),
    'total_users' => $pdo->query("SELECT COUNT(*) FROM user")->fetchColumn()
];

// Get recent orders
$recentOrders = $pdo->query("
    SELECT o.*, u.nama as customer_name
    FROM orders o
    JOIN user u ON o.user_id = u.id
    ORDER BY o.created_at DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Nandur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100 font-[Poppins]">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-green-800 text-white p-6">
            <div class="text-2xl font-bold mb-8">Nandur Admin</div>
            <nav class="space-y-2">
                <a href="admin-dashboard.php" class="block py-2 px-4 bg-green-700 rounded">Dashboard</a>
                <a href="admin-orders.php" class="block py-2 px-4 hover:bg-green-700 rounded">Pesanan</a>
                <a href="admin-products.php" class="block py-2 px-4 hover:bg-green-700 rounded">Produk</a>
                <a href="admin-users.php" class="block py-2 px-4 hover:bg-green-700 rounded">Pengguna</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <!-- Rest of the dashboard code remains the same -->
            <!-- ... -->
        </main>
    </div>
</body>
</html>