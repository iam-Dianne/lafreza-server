<?php
require_once '../shared/auth.php';
require_once '../shared/connection.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);

$data = json_decode(file_get_contents("php://input"), true);

$reservation_id = $data['reservation_id'] ?? null;
$new_status = $data['status'] ?? null;

if (!$reservation_id || !$new_status) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE reservations SET status = :status, updated_at = NOW() WHERE reservation_id = :id");
    $stmt->bindParam(':status', $new_status);
    $stmt->bindParam(':id', $reservation_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Status updated successfully.']);
    } else {
        $errorInfo = $stmt->errorInfo();
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $errorInfo[2]]);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
