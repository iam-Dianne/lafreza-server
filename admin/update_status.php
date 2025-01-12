<?php 

require_once '../shared/connection.php';
require_once '../shared/cors.php';

header('Content-Type: application/json');
error_reporting(E_ALL); 
ini_set('display_errors', 0);

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'] ?? null;
    $status = $data['status'] ?? null;

    try {
        $stmt = $pdo->prepare("UPDATE accomodations SET availability = :status WHERE id = :id");

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':status', $status);

        if($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update status']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Datbase error: ' . $e->getMessage()]);
    }
}