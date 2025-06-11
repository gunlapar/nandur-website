<?php
session_start();
require '../../Login_page/koneksi.php';

// Check if user is admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../shop.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: admin_products.php');
    exit;
}

$productId = $_GET['id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $pdo->prepare("
            UPDATE products 
            SET name = ?, description = ?, price = ?, 
                stock = ?, status = ?
            WHERE id = ?
        ");
        
        $stmt->execute([
            $_POST['name'],
            $_POST['description'],
            $_POST['price'],
            $_POST['stock'],
            $_POST['status'],
            $productId
        ]);

        // Handle image upload if new image is provided
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../../../uploads/products/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileName = uniqid() . '-' . $_FILES['image']['name'];
            $filePath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {
                $stmt = $pdo->prepare("UPDATE products SET image_url = ? WHERE id = ?");
                $stmt->execute(['/uploads/products/' . $fileName, $productId]);
            }
        }

        header('Location: admin_products.php?success=1');
        exit;
    } catch (PDOException $e) {
        $error = $e->getMessage();
    }
}

// Fetch product details
$stmt = $pdo->prepare("
    SELECT p.*, u.nama as seller_name 
    FROM products p
    JOIN user u ON p.user_id = u.id
    WHERE p.id = ?
");
$stmt->execute([$productId]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header('Location: admin_products.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - Admin Nandur</title>
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
                <a href="admin_products.php" class="block py-2 px-4 bg-green-700 rounded">Produk</a>
                <a href="admin_users.php" class="block py-2 px-4 hover:bg-green-700 rounded">Pengguna</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <div class="max-w-4xl mx-auto">
                <div class="flex items-center justify-between mb-8">
                    <h1 class="text-3xl font-bold text-green-800">Edit Produk</h1>
                    <a href="admin_products.php" class="text-green-600 hover:text-green-700">← Kembali</a>
                </div>

                <?php if (isset($error)): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <form method="POST" enctype="multipart/form-data" class="space-y-6">
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-gray-700 mb-2" for="name">Nama Produk</label>
                                <input type="text" id="name" name="name" required
                                       value="<?= htmlspecialchars($product['name']) ?>"
                                       class="w-full px-3 py-2 border rounded focus:outline-none focus:border-green-500">
                            </div>
                            <div>
                                <label class="block text-gray-700 mb-2" for="price">Harga (Rp)</label>
                                <input type="number" id="price" name="price" required
                                       value="<?= $product['price'] ?>"
                                       class="w-full px-3 py-2 border rounded focus:outline-none focus:border-green-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-gray-700 mb-2" for="description">Deskripsi</label>
                            <textarea id="description" name="description" rows="4" required
                                      class="w-full px-3 py-2 border rounded focus:outline-none focus:border-green-500"
                            ><?= htmlspecialchars($product['description']) ?></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-gray-700 mb-2" for="stock">Stok</label>
                                <input type="number" id="stock" name="stock" required
                                       value="<?= $product['stock'] ?>"
                                       class="w-full px-3 py-2 border rounded focus:outline-none focus:border-green-500">
                            </div>
                            <div>
                                <label class="block text-gray-700 mb-2" for="status">Status</label>
                                <select id="status" name="status" required
                                        class="w-full px-3 py-2 border rounded focus:outline-none focus:border-green-500">
                                    <option value="active" <?= $product['status'] === 'active' ? 'selected' : '' ?>>
                                        Active
                                    </option>
                                    <option value="inactive" <?= $product['status'] === 'inactive' ? 'selected' : '' ?>>
                                        Inactive
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-gray-700 mb-2">Gambar Saat Ini</label>
                            <img src="<?= htmlspecialchars($product['image_url']) ?>" 
                                 alt="<?= htmlspecialchars($product['name']) ?>"
                                 class="w-32 h-32 object-cover rounded">
                        </div>

                        <div>
                            <label class="block text-gray-700 mb-2" for="image">Ganti Gambar (Opsional)</label>
                            <input type="file" id="image" name="image" accept="image/*"
                                   class="w-full px-3 py-2 border rounded focus:outline-none focus:border-green-500">
                        </div>

                        <div class="flex justify-end gap-4">
                            <a href="admin_products.php" 
                               class="px-6 py-2 text-gray-600 hover:text-gray-800">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>