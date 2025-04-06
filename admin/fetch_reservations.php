<?php
require_once '../shared/auth.php';
require_once '../shared/connection.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);

try {
    $stmt = $pdo->prepare("
        SELECT 
            r.reservation_id,
            r.date_from,
            r.date_to,
            r.total_price,
            r.updated_at,
            r.status,
            g.guest_name,
            a.accomodation_name
        FROM reservations r
        JOIN guests g ON r.guest_id = g.id
        JOIN accomodations a ON r.accommodation_id = a.id
    ");

    if ($stmt->execute()) {
        $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'data' => $reservations]);
    } else {
        $errorInfo = $stmt->errorInfo();
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $errorInfo[2]]);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
