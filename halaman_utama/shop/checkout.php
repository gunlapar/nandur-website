<?php
session_start();
require '../Login_page/koneksi.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../Login_page/login_page.php');
    exit;
}

// Fetch cart items with product details
$stmt = $pdo->prepare("
    SELECT c.*, p.name, p.price, p.stock,
    CASE 
        WHEN p.image_url LIKE '/uploads/products/%' 
        THEN CONCAT('..', p.image_url)
        ELSE p.image_url 
    END as full_image_path,
    u.nama as seller_name 
    FROM cart c
    JOIN products p ON c.product_id = p.id
    JOIN user u ON p.user_id = u.id
    WHERE c.user_id = ?
");
$stmt->execute([$_SESSION['user']['id']]);
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate total
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
    <title>Checkout - Nandur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100 font-[Poppins]">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-green-800 mb-8">Checkout</h1>

        <?php if (empty($cartItems)): ?>
            <p class="text-gray-600">Keranjang belanja kosong</p>
            <a href="shop.php" class="inline-block mt-4 px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                Kembali ke Toko
            </a>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Order Summary -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h2 class="text-xl font-semibold mb-4">Ringkasan Pesanan</h2>
                    <?php foreach ($cartItems as $item): ?>
                        <div class="flex gap-4 mb-4 pb-4 border-b">
                            <img src="<?= htmlspecialchars($item['full_image_path']) ?>"
                                alt="<?= htmlspecialchars($item['name']) ?>"
                                class="w-20 h-20 object-cover rounded"
                                onerror="this.src='../assets/images/default-product.jpg'">
                                
                            <div>
                                <h3 class="font-semibold"><?= htmlspecialchars($item['name']) ?></h3>
                                <p class="text-gray-600">Qty: <?= $item['quantity'] ?></p>
                                <p class="text-green-600">
                                    Rp <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?>
                                </p>
                                <?php if ($item['quantity'] > $item['stock']): ?>
                                    <p class="text-red-600 text-sm">Stok tidak mencukupi (Tersedia: <?= $item['stock'] ?>)</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="text-xl font-bold mt-4">
                        Total: Rp <?= number_format($total, 0, ',', '.') ?>
                    </div>
                </div>

                <!-- Checkout Form -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h2 class="text-xl font-semibold mb-4">Informasi Pengiriman</h2>
                    <form action="process_checkout.php" method="POST" class="space-y-4">
                        <div>
                            <label class="block text-gray-700 mb-2" for="shipping_address">Alamat Pengiriman</label>
                            <textarea id="shipping_address" name="shipping_address" rows="3" required
                                    class="w-full px-3 py-2 border rounded focus:outline-none focus:border-green-500"
                                    placeholder="Masukkan alamat lengkap pengiriman"></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 mb-2" for="notes">Catatan (opsional)</label>
                            <textarea id="notes" name="notes" rows="2"
                                    class="w-full px-3 py-2 border rounded focus:outline-none focus:border-green-500"
                                    placeholder="Tambahkan catatan untuk penjual"></textarea>
                        </div>

                        <div>
                            <label class="block text-gray-700 mb-2">Metode Pembayaran</label>
                            <select name="payment_method" required
                                    class="w-full px-3 py-2 border rounded focus:outline-none focus:border-green-500">
                                <option value="">Pilih metode pembayaran</option>
                                <option value="transfer">Transfer Bank</option>
                                <option value="cod">Bayar di Tempat (COD)</option>
                            </select>
                        </div>

                        <button type="submit" 
                                class="w-full py-3 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            Buat Pesanan
                        </button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>