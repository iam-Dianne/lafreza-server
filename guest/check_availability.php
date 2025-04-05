<?php
session_start();
require_once '../shared/connection.php';
require_once '../shared/cors.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$data = json_decode(file_get_contents("php://input"), true);

$accommodation_id = $data['accommodation_id'] ?? null;
$date_from = $data['date_from'] ?? null;
$date_to = $data['date_to'] ?? null;

if (!$accommodation_id || !$date_from || !$date_to) {
    echo json_encode([
        'success' => false,
        'message' => 'Missing one or more required fields.',
        'data' => compact('accommodation_id', 'date_from', 'date_to')
    ]);
    exit;
}


try {

    // echo json_encode([
    //     'accommodation_id' => $accommodation_id,
    //     'date_from' => $date_from,
    //     'date_to' => $date_to
    // ]);

    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM reservations 
        WHERE accommodation_id = :accommodation_id 
        AND status = 'approved' 
        AND (
            (date_from <= :date_to AND date_to >= :date_from)  -- Corrected condition
        )
    ");

    $stmt->bindParam(':accommodation_id', $accommodation_id);
    $stmt->bindParam(':date_from', $date_from);
    $stmt->bindParam(':date_to', $date_to);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result['count'] > 0) {
        echo json_encode(['success' => false, 'message' => 'Selected dates are not available.']);
    } else {
        echo json_encode(['success' => true, 'message' => 'Accommodation is available.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
