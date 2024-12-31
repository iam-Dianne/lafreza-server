<?php 

require_once '../shared/connection.php';
require_once '../shared/cors.php';

header('Content-Type: application/json');
error_reporting(E_ALL); 
ini_set('display_errors', 0);

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    // form data is sent as a json; json decode parses it into an associative arraaayyyy
    $data = json_decode(file_get_contents('php://input'), true);

    // extracting data
    $firstName = $data['firstName'] ?? '';
    $lastName = $data['lastName'] ?? '';
    $username = $data['username'] ?? '';
    $email = $data['email'] ?? '';
    $password = $data['password'] ?? '';

    if (empty($firstName) || empty($lastName) || empty($username) || empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $fullName = $firstName . " " . $lastName;

    try {
        $stmt = $pdo->prepare("INSERT INTO admin_accounts (admin_name, admin_username, admin_email, admin_password)
                                VALUES (:fullName, :username, :email, :password)");
        
        $stmt->bindParam(':fullName', $fullName);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Admin account created successfully.']);
        } else {
            $errorInfo = $stmt->errorInfo();
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $errorInfo[2]]);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }

    
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

?>