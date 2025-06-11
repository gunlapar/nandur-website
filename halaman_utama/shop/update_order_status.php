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
    
    if (!isset($data['order_id']) || !isset($data['status'])) {
        throw new Exception('Missing required data');
    }

    // Verify seller owns the products in this order
    $stmt = $pdo->prepare("
        SELECT DISTINCT o.id 
        FROM orders o
        JOIN order_items oi ON o.id = oi.order_id
        JOIN products p ON oi.product_id = p.id
        WHERE o.id = ? AND p.user_id = ?
    ");
    $stmt->execute([$data['order_id'], $_SESSION['user']['id']]);
    
    if (!$stmt->fetch()) {
        throw new Exception('Order not found or unauthorized');
    }

    // Update order status
    $stmt = $pdo->prepare("
        UPDATE orders 
        SET status = ?, 
            updated_at = CURRENT_TIMESTAMP 
        WHERE id = ?
    ");
    
    if ($stmt->execute([$data['status'], $data['order_id']])) {
        echo json_encode(['success' => true]);
    } else {
        throw new Exception('Failed to update order');
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}