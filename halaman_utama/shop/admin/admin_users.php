<?php
session_start();
require '../../Login_page/koneksi.php';

// Check if user is admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../shop.php');
    exit;
}

// Handle user status toggle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['action'])) {
    if ($_POST['action'] === 'toggle_status') {
        $stmt = $pdo->prepare("UPDATE user SET status = ? WHERE id = ?");
        $stmt->execute([$_POST['status'], $_POST['user_id']]);
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Get all users with their stats
$users = $pdo->query("
    SELECT 
        u.*,
        COUNT(DISTINCT p.id) as total_products,
        COUNT(DISTINCT o.id) as total_orders,
        SUM(CASE WHEN p.status = 'active' THEN 1 ELSE 0 END) as active_products
    FROM user u
    LEFT JOIN products p ON u.id = p.user_id
    LEFT JOIN orders o ON u.id = o.user_id
    GROUP BY u.id
    ORDER BY u.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengguna - Admin Nandur</title>
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
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-green-800">Kelola Pengguna</h1>
            </div>

            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 text-left">
                                <th class="px-6 py-3">Nama</th>
                                <th class="px-6 py-3">Email</th>
                                <th class="px-6 py-3">Role</th>
                                <th class="px-6 py-3">WhatsApp</th>
                                <th class="px-6 py-3">Produk</th>
                                <th class="px-6 py-3">Pesanan</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                            <tr class="border-t">
                                <td class="px-6 py-4 font-semibold">
                                    <?= htmlspecialchars($user['nama']) ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?= htmlspecialchars($user['email']) ?>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-full text-sm
                                        <?= $user['role'] === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' ?>">
                                        <?= ucfirst($user['role']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="https://wa.me/<?= $user['whatsapp'] ?>" 
                                       target="_blank"
                                       class="text-green-600 hover:text-green-700">
                                        <?= $user['whatsapp'] ?>
                                    </a>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm">
                                        Total: <?= $user['total_products'] ?><br>
                                        Aktif: <?= $user['active_products'] ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <?= $user['total_orders'] ?>
                                </td>
                                <td class="px-6 py-4">
                                    <form method="POST">
                                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                        <input type="hidden" name="action" value="toggle_status">
                                        <input type="hidden" name="status" 
                                               value="<?= $user['status'] === 'active' ? 'inactive' : 'active' ?>">
                                        <button type="submit" 
                                                class="px-3 py-1 rounded text-sm <?= 
                                                $user['status'] === 'active' 
                                                    ? 'bg-green-100 text-green-800' 
                                                    : 'bg-red-100 text-red-800' 
                                                ?>">
                                            <?= ucfirst($user['status'] ?? 'active') ?>
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="admin_user_detail.php?id=<?= $user['id'] ?>" 
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