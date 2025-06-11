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
    
    if (!isset($data['order_id'])) {
        throw new Exception('Order ID is required');
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

    $pdo->beginTransaction();

    // Delete order items first
    $stmt = $pdo->prepare("DELETE FROM order_items WHERE order_id = ?");
    $stmt->execute([$data['order_id']]);

    // Then delete the order
    $stmt = $pdo->prepare("DELETE FROM orders WHERE id = ?");
    $stmt->execute([$data['order_id']]);

    $pdo->commit();
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}