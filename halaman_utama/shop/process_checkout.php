<?php
session_start();
require '../Login_page/koneksi.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user'])) {
    header('Location: ../Login_page/login_page.php');
    exit;
}

try {
    $pdo->beginTransaction();

    // Validate form data
    if (empty($_POST['shipping_address']) || empty($_POST['payment_method'])) {
        throw new Exception('Please fill all required fields');
    }

    // Get cart items
    $stmt = $pdo->prepare("
        SELECT c.*, p.price, p.stock, p.user_id as seller_id 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?
    ");
    $stmt->execute([$_SESSION['user']['id']]);
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($cartItems)) {
        throw new Exception('Cart is empty');
    }

    // Calculate total
    $total = 0;
    foreach ($cartItems as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    // Create order
    $stmt = $pdo->prepare("
        INSERT INTO orders (
            user_id, 
            total_amount, 
            shipping_address, 
            payment_method,
            status
        ) VALUES (?, ?, ?, ?, 'pending')
    ");
    $stmt->execute([
        $_SESSION['user']['id'],
        $total,
        $_POST['shipping_address'],
        $_POST['payment_method']
    ]);
    $orderId = $pdo->lastInsertId();

    // Add order items
    $stmt = $pdo->prepare("
        INSERT INTO order_items (
            order_id, 
            product_id, 
            quantity, 
            price
        ) VALUES (?, ?, ?, ?)
    ");

    foreach ($cartItems as $item) {
        $stmt->execute([
            $orderId,
            $item['product_id'],
            $item['quantity'],
            $item['price']
        ]);
    }

    // Clear cart
    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->execute([$_SESSION['user']['id']]);

    $pdo->commit();
    header('Location: order-detail.php?id=' . $orderId);
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    error_log("Checkout Error: " . $e->getMessage());
    header('Location: checkout.php?error=' . urlencode($e->getMessage()));
    exit;
}