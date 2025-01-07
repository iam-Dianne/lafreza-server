<?php
require_once '../shared/auth.php';
require_once '../shared/connection.php';

header('Content-Type: application/json');
error_reporting(E_ALL); 
ini_set('display_errors', 0);

try {
    if(isset($_GET['id'])) {
        $id = $_GET['id'];

        $stmt = $pdo->prepare("SELECT * FROM accomodations WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if($stmt->execute()) {
            $accommodation = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($accommodation) {
                echo json_encode(['success' => true, 'data' => $accommodation]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Accommodation not found']);
            }

        } else {
            $errorInfo = $stmt->errorInfo();
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $errorInfo[2]]);
        }
    }

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}