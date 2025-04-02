<?php
session_start();

require_once '../shared/connection.php';
require_once '../shared/cors.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $guestId = $data['guest_id'] ?? '';
    $accommodationId = $data['accommodation_id'] ?? '';
    $accommodationName = $data['accommodation_name'] ?? '';
    $dateFrom = $data['date_from'] ?? '';
    $dateTo = $data['date_to'] ?? '';
    $adults = $data['adults'] ?? '';
    $children = $data['children'] ?? '';
    $totalPrice = $data['total_price'] ?? '';

    if (empty($guestId) || empty($accommodationId) || empty($dateFrom) || empty($dateTo) || empty($totalPrice)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO cart (guest_id, accommodation_id, accommodation_name, date_from, date_to, adults, children, total_price) 
                                VALUES (:guest_id, :accommodation_id, :accommodation_name, :date_from, :date_to, :adults, :children, :total_price)");

        $stmt->bindParam(':guest_id', $guestId);
        $stmt->bindParam(':accommodation_id', $accommodationId);
        $stmt->bindParam(':accommodation_name', $accommodationName);
        $stmt->bindParam(':date_from', $dateFrom);
        $stmt->bindParam(':date_to', $dateTo);
        $stmt->bindParam(':adults', $adults);
        $stmt->bindParam(':children', $children);
        $stmt->bindParam(':total_price', $totalPrice);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Added to cart successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add to cart.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
