<?php
session_start();
require '../Login_page/koneksi.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['cart_id']) || !isset($data['quantity'])) {
        throw new Exception('Invalid request data');
    }

    // Check stock availability
    $stmt = $pdo->prepare("
        SELECT c.*, p.stock 
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.id = ? AND c.user_id = ?
    ");
    $stmt->execute([$data['cart_id'], $_SESSION['user']['id']]);
    $cartItem = $stmt->fetch();

    if (!$cartItem) {
        throw new Exception('Cart item not found');
    }

    if ($data['quantity'] > $cartItem['stock']) {
        throw new Exception("Stok tidak mencukupi! Tersedia: {$cartItem['stock']}");
    }

    if ($data['quantity'] < 1) {
        throw new Exception('Quantity must be at least 1');
    }

    // Update quantity
    $stmt = $pdo->prepare("
        UPDATE cart 
        SET quantity = ?, 
            updated_at = CURRENT_TIMESTAMP
        WHERE id = ? AND user_id = ?
    ");
    
    if ($stmt->execute([$data['quantity'], $data['cart_id'], $_SESSION['user']['id']])) {
        echo json_encode(['success' => true]);
    } else {
        throw new Exception('Failed to update cart');
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}