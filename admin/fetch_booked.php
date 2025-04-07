<?php
session_start();
require_once '../shared/connection.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);

try {
    $today = date('Y-m-d');

    $stmt = $pdo->prepare("
        SELECT COUNT(*) as total_bookings
        FROM reservations r
        WHERE r.status = 'approved' 
        AND r.date_from <= :today 
        AND r.date_to >= :today
    ");

    $stmt->bindParam(':today', $today);

    // Execute the query
    if ($stmt->execute()) {
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'total_bookings' => $result['total_bookings']]);
    } else {
        $errorInfo = $stmt->errorInfo();
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $errorInfo[2]]);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
