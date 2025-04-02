<?php
session_start();
require_once '../shared/connection.php';
require_once '../shared/cors.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);

// if (!isset($_SESSION['guest_logged_in']) || $_SESSION['guest_logged_in'] !== true) {
//     echo json_encode(['success' => false, 'message' => 'Please log in to view your cart']);
//     exit;
// }

try {
    $guest_email = $_SESSION['guest_email'];

    $stmt = $pdo->prepare("SELECT id FROM guests WHERE guest_email = :guest_email");
    $stmt->bindParam(':guest_email', $guest_email);
    $stmt->execute();
    $guest = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($guest) {
        $guest_id = $guest['id'];

        // Debugging session email
        // echo json_encode(['guest_id' => $guest_id]);
        // exit;

        $stmt = $pdo->prepare("SELECT * FROM cart WHERE guest_id = :guest_id");
        $stmt->bindParam(':guest_id', $guest_id);
        $stmt->execute();

        $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($cart_items) {
            echo json_encode(['success' => true, 'cart_items' => $cart_items]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No items in your cart']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Guest not found']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
