<?php

require_once '../shared/connection.php';
require_once '../shared/cors.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $today = date('Y-m-d');

        $query = "SELECT COUNT(*) as checked_in_rooms
                  FROM reservations
                  WHERE status = 'approved' AND DATE(date_from) = :today";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':today', $today, PDO::PARAM_STR);

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'checked_in_rooms' => $result['checked_in_rooms']]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}
