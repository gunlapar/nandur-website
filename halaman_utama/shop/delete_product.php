<?php
session_start();
require '../Login_page/koneksi.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['product_id'])) {
        throw new Exception('Product ID is required');
    }

    // Verify product ownership
    $stmt = $pdo->prepare("
        SELECT id, image_url 
        FROM products 
        WHERE id = ? AND user_id = ?
    ");
    $stmt->execute([$data['product_id'], $_SESSION['user']['id']]);
    $product = $stmt->fetch();

    if (!$product) {
        throw new Exception('Product not found or unauthorized');
    }

    // Begin transaction
    $pdo->beginTransaction();

    // Delete from cart first (if product is in any cart)
    $stmt = $pdo->prepare("DELETE FROM cart WHERE product_id = ?");
    $stmt->execute([$data['product_id']]);

    // Delete the product
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ? AND user_id = ?");
    $stmt->execute([$data['product_id'], $_SESSION['user']['id']]);

    // If deletion successful, delete the image file if it exists
    if ($product['image_url'] && strpos($product['image_url'], '/uploads/products/') === 0) {
        $imagePath = __DIR__ . '/..' . $product['image_url'];
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    $pdo->commit();
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log("Delete Product Error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Gagal menghapus produk: ' . $e->getMessage()
    ]);
}