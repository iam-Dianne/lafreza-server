<?php
session_start();

require_once '../shared/connection.php';
require_once '../shared/cors.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Decode the incoming JSON data
    $data = json_decode(file_get_contents('php://input'), true);

    $cartId = $data['cart_id'] ?? '';

    if (empty($cartId)) {
        echo json_encode(['success' => false, 'message' => 'Cart item ID is required.']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM cart WHERE cart_id = :cart_id");
        $stmt->bindParam(':cart_id', $cartId);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Item deleted from cart successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete item from cart.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
