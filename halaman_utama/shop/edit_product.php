<?php
session_start();
require '../Login_page/koneksi.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user'])) {
    header('Location: ../Login_page/login_page.php');
    exit;
}

$error = '';
$product_id = $_GET['id'] ?? 0;

// Fetch existing product data
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND user_id = ?");
$stmt->execute([$product_id, $_SESSION['user']['id']]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header('Location: my-products.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate inputs
        if (empty($_POST['name']) || empty($_POST['price']) || empty($_POST['stock'])) {
            throw new Exception('Semua field harus diisi');
        }

        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
        $price = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT);
        $stock = filter_var($_POST['stock'], FILTER_VALIDATE_INT);
        $category = $_POST['category'];

        // Handle image upload if provided
        $image_url = $product['image_url'];
        if (!empty($_FILES['image']['tmp_name'])) {
            $uploadDir = '../uploads/products/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileName = uniqid() . '-' . $_FILES['image']['name'];
            $filePath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {
                $image_url = '/uploads/products/' . $fileName;
            } else {
                throw new Exception('Gagal mengupload gambar');
            }
        }

        // Update product
        $stmt = $pdo->prepare("
            UPDATE products 
            SET name = ?, description = ?, price = ?, 
                stock = ?, image_url = ?, category = ?
            WHERE id = ? AND user_id = ?
        ");

        if ($stmt->execute([
            $name, $description, $price, $stock,
            $image_url, $category, $product_id, $_SESSION['user']['id']
        ])) {
            header('Location: my-products.php');
            exit;
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - Nandur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100 font-[Poppins]">
    <div class="container mx-auto px-4 py-8">
        <form action="edit_product.php?id=<?= $product_id ?>" method="POST" enctype="multipart/form-data" 
              class="max-w-2xl bg-white p-6 rounded-lg shadow mx-auto">
            
            <h1 class="text-3xl font-bold text-green-800 mb-8">Edit Produk</h1>
            
            <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Nama Produk</label>
                <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required
                       class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-green-500">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Deskripsi</label>
                <textarea name="description" rows="4" 
                          class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-green-500"
                ><?= htmlspecialchars($product['description']) ?></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Harga (Rp)</label>
                    <input type="number" name="price" value="<?= $product['price'] ?>" required min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-green-500">
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Stok</label>
                    <input type="number" name="stock" value="<?= $product['stock'] ?>" required min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-green-500">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Kategori</label>
                <select name="category" required class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-green-500">
                    <option value="bibit" <?= $product['category'] === 'bibit' ? 'selected' : '' ?>>Bibit</option>
                    <option value="pupuk" <?= $product['category'] === 'pupuk' ? 'selected' : '' ?>>Pupuk</option>
                    <option value="peralatan" <?= $product['category'] === 'peralatan' ? 'selected' : '' ?>>Peralatan</option>
                    <option value="hasil_tani" <?= $product['category'] === 'hasil_tani' ? 'selected' : '' ?>>Hasil Tani</option>
                    <option value="lainnya" <?= $product['category'] === 'lainnya' ? 'selected' : '' ?>>Lainnya</option>
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Foto Produk Baru (Opsional)</label>
                <input type="file" name="image" accept="image/*"
                       class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-green-500">
            </div>

            <div class="flex justify-end gap-4">
                <a href="my-products.php" class="px-4 py-2 text-gray-600 hover:text-gray-800">Batal</a>
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</body>
</html>