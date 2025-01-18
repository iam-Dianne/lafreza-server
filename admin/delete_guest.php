<?php
require_once '../shared/auth.php';
require_once '../shared/connection.php';

header('Content-Type: application/json');
error_reporting(E_ALL); 
ini_set('display_errors', 0);

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'] ?? null;
    error_log(json_encode($data));

    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'Invalid guest id.']);
        exit;
    }

    if ($id) {
        try {
            $stmt = $pdo->prepare("DELETE FROM guests WHERE id = :id");
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Guest account deleted successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to delete guest account.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
        
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid guest id.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}