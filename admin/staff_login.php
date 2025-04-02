<?php

require_once '../shared/connection.php';
require_once '../shared/cors.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';

    if (empty($username) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM staff_accounts WHERE staff_username = :username");
        $stmt->bindParam(':username', $username);

        if ($stmt->execute()) {
            $staff = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($staff && password_verify($password, $staff['staff_password'])) {
                session_start();
                $_SESSION['staff_logged_in'] = true;
                $_SESSION['staff_username'] = $staff['staff_username'];
                
                echo json_encode(['success' => true, 'message' => 'Successfully logged in.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid username or password.']);
            }
        } else {
            $errorInfo = $stmt->errorInfo();
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $errorInfo[2]]);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}

?>