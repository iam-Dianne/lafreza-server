<?php
session_start();
require_once '../shared/auth.php';
require_once '../shared/connection.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $stmt = $pdo->prepare("SELECT * FROM accomodations");

    if ($stmt->execute()) {
        $accommodations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'data' => $accommodations]);
    } else {
        $errorInfo = $stmt->errorInfo();
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $errorInfo[2]]);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
