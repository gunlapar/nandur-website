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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate required fields
        $required_fields = ['name', 'description', 'price', 'stock', 'category'];
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                throw new Exception("Field $field is required");
            }
        }

        // Get form data
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
        $price = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT);
        $stock = filter_var($_POST['stock'], FILTER_VALIDATE_INT);
        $category = $_POST['category'];
        $user_id = $_SESSION['user']['id'];

        // Validate data
        if ($price === false || $price < 0) {
            throw new Exception('Invalid price value');
        }
        if ($stock === false || $stock < 0) {
            throw new Exception('Invalid stock value');
        }

               if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Please select an image file');
        }

        $uploadDir = '../uploads/products/';
        if (!is_dir($uploadDir)) {
            if (!@mkdir($uploadDir, 0755, true)) {
                error_log("Failed to create directory: $uploadDir");
                throw new Exception('Gagal membuat direktori upload');
            }
        }

        $fileName = uniqid() . '-' . $_FILES['image']['name'];
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {
            $image_url = '/uploads/products/' . $fileName;

            $sql = "INSERT INTO products (name, description, price, stock, image_url, user_id, category, status) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, 'active')";
            
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([$name, $description, $price, $stock, $image_url, $user_id, $category])) {
                header('Location: my-products.php');
                exit;
            } else {
                throw new Exception('Failed to save to database');
            }
        } else {
            throw new Exception('Failed to upload file');
        }
        
    } catch (Exception $e) {
        $error = $e->getMessage();
        error_log(sprintf(
            "Error in add_product.php:\nMessage: %s\nFile: %s\nLine: %d\nStack trace:\n%s",
            $error,
            $e->getFile(),
            $e->getLine(),
            $e->getTraceAsString()
        ));
    }
}

if (isset($_ENV['APP_DEBUG']) && $_ENV['APP_DEBUG'] === true) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - Nandur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-gray-100 font-[Poppins]">
    <div class="container mx-auto px-4 py-8">
        <form action="add_product.php" method="POST" enctype="multipart/form-data" class="max-w-2xl bg-white p-6 rounded-lg shadow">
            <h1 class="text-3xl font-bold text-green-800 mb-8">Tambah Produk Baru</h1>
            
            <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>
        
        <form action="add_product.php" method="POST" enctype="multipart/form-data" class="max-w-2xl bg-white p-6 rounded-lg shadow">
    <div class="mb-4">


    <div class="mb-4">
        <label for="category" class="block text-gray-700 font-semibold mb-2">Kategori</label>
        <select id="category" name="category" required class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-green-500">
            <option value="">Pilih Kategori</option>
            <option value="bibit">Bibit</option>
            <option value="pupuk">Pupuk</option>
            <option value="peralatan">Peralatan</option>
            <option value="hasil_tani">Hasil Tani</option>
            <option value="lainnya">Lainnya</option>
        </select>
    </div>
    
        <label for="name" class="block text-gray-700 font-semibold mb-2">Nama Produk</label>
        <input type="text" id="name" name="name" required
               class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-green-500">
    </div>

    <div class="mb-4">
        <label for="description" class="block text-gray-700 font-semibold mb-2">Deskripsi</label>
        <textarea id="description" name="description" required rows="4"
                  class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-green-500"></textarea>
    </div>

    <div class="grid grid-cols-2 gap-4 mb-4">
        <div>
            <label for="price" class="block text-gray-700 font-semibold mb-2">Harga (Rp)</label>
            <input type="number" id="price" name="price" required min="0"
                   class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-green-500">
        </div>
        <div>
            <label for="stock" class="block text-gray-700 font-semibold mb-2">Stok</label>
            <input type="number" id="stock" name="stock" required min="0"
                   class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-green-500">
        </div>
    </div>

    <div class="mb-6">
        <label for="image" class="block text-gray-700 font-semibold mb-2">Foto Produk</label>
        <input type="file" id="image" name="image" required accept="image/*"
               class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-green-500">
    </div>

            <div class="flex justify-end gap-4">
                <a href="shop.php" class="px-4 py-2 text-gray-600 hover:text-gray-800">Batal</a>
                <a href="my-products.php" class="px-4 py-2 text-gray-600 hover:text-gray-800">Balik</a>
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    Simpan Produk
                </button>
            </div>
        </form>
    </div>
</body>
</html>