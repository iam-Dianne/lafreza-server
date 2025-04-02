<?php

require_once '../shared/connection.php';
require_once '../shared/cors.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $email = $data['email'] ?? '';
    $password = $data['password'] ?? '';

    if (empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => "All fields are required"]);
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM guests WHERE guest_email = :email");

        $stmt->bindParam(":email", $email);

        if ($stmt->execute()) {
            $guest = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($guest && password_verify($password, $guest['guest_password'])) {
                session_start();
                $_SESSION['guest_logged_in'] = true;
                $_SESSION['guest_email'] = $guest['guest_email'];
                $_SESSION['guest_id'] = $guest['id'];

                echo json_encode(['success' => true, 'message' => 'Successfully logged in.', 'data' => ['guest_id' => $guest['id']]]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
            }
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}
