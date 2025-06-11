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
    
    if (!isset($data['cart_id'])) {
        throw new Exception('Invalid request data');
    }

    // Verify cart item belongs to user and delete it
    $stmt = $pdo->prepare("
        DELETE FROM cart 
        WHERE id = ? AND user_id = ?
    ");
    
    if ($stmt->execute([$data['cart_id'], $_SESSION['user']['id']])) {
        echo json_encode(['success' => true]);
    } else {
        throw new Exception('Failed to remove item');
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}