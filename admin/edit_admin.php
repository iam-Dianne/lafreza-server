<?php
require_once '../shared/connection.php';
require_once '../shared/cors.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['admin_id'])) {
        $adminId = $_GET['admin_id'];

        try {
            $stmt = $pdo->prepare("SELECT * FROM admin_accounts WHERE admin_id = :adminId");
            $stmt->bindParam(':adminId', $adminId);
            $stmt->execute();

            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($admin) {
                // Use $admin not $row
                $fullName = trim($admin['admin_name']);

                // Split name into first and last name
                $nameParts = explode(" ", $fullName, 2); // Split into 2 parts only
                $firstName = $nameParts[0];
                $lastName = isset($nameParts[1]) ? $nameParts[1] : '';

                // Send the response
                echo json_encode([
                    'success' => true,
                    'firstName' => $firstName,
                    'lastName' => $lastName,
                    'username' => $admin['admin_username'],
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Admin not found']);
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Admin ID is required']);
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['admin_id'], $data['firstName'], $data['lastName'], $data['username'])) {
        $adminId = $data['admin_id'];
        $firstName = trim($data['firstName']);
        $lastName = trim($data['lastName']);
        $fullName = $firstName . " " . $lastName; // Combine to save as full name
        $username = trim($data['username']);

        try {
            $stmt = $pdo->prepare("UPDATE admin_accounts SET admin_name = :fullName, admin_username = :username WHERE admin_id = :adminId");
            $stmt->bindParam(':fullName', $fullName);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':adminId', $adminId);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Admin details updated successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update admin details']);
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
