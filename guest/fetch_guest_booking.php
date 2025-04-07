<?php
session_start();
require_once '../shared/connection.php';
require_once '../shared/cors.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $guest_id = $_GET['guest_id'] ?? null;

    if ($guest_id) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM reservations WHERE guest_id = :guest_id");
            $stmt->bindParam(':guest_id', $guest_id, PDO::PARAM_INT);
            $stmt->execute();

            $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($bookings) {
                echo json_encode(['success' => true, 'bookings' => $bookings]);
            } else {
                echo json_encode(['success' => false, 'message' => 'No bookings found']);
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Guest ID is required']);
    }
}
