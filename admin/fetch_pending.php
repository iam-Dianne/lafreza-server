<?php

require_once '../shared/connection.php';
require_once '../shared/cors.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {

        $query = "SELECT COUNT(*) as pending_reservations
                  FROM reservations
                  WHERE status = 'pending'";

        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'pending_reservations' => $result['pending_reservations']]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}
