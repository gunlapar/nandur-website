<?php
session_start();
require '../Login_page/koneksi.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'Login required']);
    exit;
}

// Get JSON data from request
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['product_id']) || !isset($data['quantity'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request data']);
    exit;
}

try {
    // Check if product exists and is active
    $stmt = $pdo->prepare("SELECT id, stock FROM products WHERE id = ? AND status = 'active'");
    $stmt->execute([$data['product_id']]);
    $product = $stmt->fetch();

    if (!$product) {
        throw new Exception('Product not found or inactive');
    }

    // Check if product is already in cart
    $stmt = $pdo->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$_SESSION['user']['id'], $data['product_id']]);
    $cartItem = $stmt->fetch();

    if ($cartItem) {
        // Update existing cart item
        $newQuantity = $cartItem['quantity'] + $data['quantity'];
        if ($newQuantity > $product['stock']) {
            throw new Exception('Not enough stock available');
        }

        $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $stmt->execute([$newQuantity, $cartItem['id']]);
    } else {
        // Add new cart item
        if ($data['quantity'] > $product['stock']) {
            throw new Exception('Not enough stock available');
        }

        $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->execute([$_SESSION['user']['id'], $data['product_id'], $data['quantity']]);
    }

    echo json_encode(['success' => true, 'message' => 'Product added to cart']);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}