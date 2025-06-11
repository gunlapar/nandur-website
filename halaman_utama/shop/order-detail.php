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

if (!isset($_GET['id'])) {
    header('Location: my-orders.php');
    exit;
}

$orderId = $_GET['id'];

try {
    // First, fetch the main order details
    $stmt = $pdo->prepare("
        SELECT o.*, u.nama as customer_name
        FROM orders o
        JOIN user u ON o.user_id = u.id
        WHERE o.id = ? AND o.user_id = ?
    ");
    $stmt->execute([$orderId, $_SESSION['user']['id']]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        throw new Exception('Order not found');
    }

    // Then fetch order items with product details
    $stmt = $pdo->prepare("
        SELECT 
            oi.*,
            p.name,
            CASE 
                WHEN p.image_url LIKE '/uploads/products/%' 
                THEN CONCAT('..', p.image_url)
                ELSE p.image_url 
            END as full_image_path,
            u.nama as seller_name,
            u.whatsapp as seller_whatsapp
        FROM order_items oi
        JOIN products p ON oi.product_id = p.id
        JOIN user u ON p.user_id = u.id
        WHERE oi.order_id = ?
    ");
    $stmt->execute([$orderId]);
    $orderItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    error_log("Order Detail Error: " . $e->getMessage());
    header('Location: my-orders.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan #<?= htmlspecialchars($orderId) ?> - Nandur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100 font-[Poppins]">
    <main class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center gap-4 mb-8">
                <a href="my-orders.php" class="text-green-600 hover:text-green-700">← Kembali</a>
                <h1 class="text-3xl font-bold text-green-800">Detail Pesanan #<?= htmlspecialchars($orderId) ?></h1>
            </div>

            <!-- Order Status -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <p class="text-gray-600">Status</p>
                        <p class="font-semibold"><?= ucfirst($order['status'] ?? 'pending') ?></p>
                    </div>
                    <div>
                        <p class="text-gray-600">Tanggal Pesanan</p>
                        <p class="font-semibold"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
                    </div>
                    <div>
                        <p class="text-gray-600">Total</p>
                        <p class="font-semibold">Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></p>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Item Pesanan</h2>
                <?php foreach ($orderItems as $item): ?>
                <div class="flex gap-4 py-4 border-b last:border-0">
                    <img src="<?= htmlspecialchars($item['full_image_path']) ?>" 
                         alt="<?= htmlspecialchars($item['name']) ?>"
                         class="w-24 h-24 object-cover rounded"
                         onerror="this.src='../assets/images/default-product.jpg'">
                    <div class="flex-1">
                        <h3 class="font-semibold"><?= htmlspecialchars($item['name']) ?></h3>
                        <p class="text-gray-600">Penjual: <?= htmlspecialchars($item['seller_name']) ?></p>
                        <p class="text-gray-600">Jumlah: <?= $item['quantity'] ?></p>
                        <p class="text-green-600">
                            Rp <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?>
                        </p>
                        <?php if (!empty($item['seller_whatsapp'])): ?>
                        <a href="https://wa.me/<?= htmlspecialchars($item['seller_whatsapp']) ?>" 
                           target="_blank"
                           class="inline-flex items-center gap-2 mt-2 text-green-600 hover:text-green-700">
                            Hubungi Penjual
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
</body>
</html>