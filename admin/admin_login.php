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
        $stmt = $pdo->prepare("SELECT * FROM admin_accounts WHERE admin_username = :username");
        $stmt->bindParam(':username', $username);

        if ($stmt->execute()) {
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($admin && password_verify($password, $admin['admin_password'])) {
                session_start();
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_username'] = $admin['admin_username'];
                
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