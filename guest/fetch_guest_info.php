<?php
session_start();
require_once '../shared/connection.php';
require_once '../shared/cors.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $guest_id = $_GET['id'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM guests WHERE id = ?");
        $stmt->execute([$guest_id]);
        $guest = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($guest) {
            echo json_encode([
                'success' => true,
                'data' => $guest
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Guest not found.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
