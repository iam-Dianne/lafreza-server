<?php 

require_once '../shared/connection.php';
require_once '../shared/cors.php';

header('Content-Type: application/json');
error_reporting(E_ALL); 
ini_set('display_errors', 0);

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $name = $_POST['name'] ?? '';
    $type = $_POST['type'] ?? '';
    $features = $_POST['features'] ?? '';
    $capacity = $_POST['capacity'] ?? '';
    $price = $_POST['price'] ?? '';

    if (empty($id) || empty($name) || empty($type) || empty($capacity) || empty($price)) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
        exit;
    }


    $uploadDir = '../uploads/accomodations';
    $imagePaths = [];
    $uploadErrors = [];

    if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['name'] as $key => $imageName) {
            $imageTmpName = $_FILES['images']['tmp_name'][$key];
            $imageSize = $_FILES['images']['size'][$key];
            $imageExtension = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));

            // Allowed extensions
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

            if (!in_array($imageExtension, $allowedExtensions)) {
                $uploadErrors[] = "Invalid file type for $imageName.";
                continue;
            }

            if ($imageSize > 10 * 1024 * 1024) { // Limit to 10MB
                $uploadErrors[] = "$imageName exceeds the maximum allowed size (10MB).";
                continue;
            }

            $uniqueName = uniqid('accommodation_', true) . '.' . $imageExtension;
            $destination = $uploadDir . '/' . $uniqueName;

            if (move_uploaded_file($imageTmpName, $destination)) {
                $imagePaths[] = $destination; // Save file path for database
            } else {
                $uploadErrors[] = "Failed to upload $imageName.";
            }
        }
    }

    try {
        $stmt = $pdo->prepare("UPDATE accomodations SET accomodation_name = :name, accomodation_type = :type, features = :features, capacity = :capacity, price = :price WHERE id = :id");

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':features', $features);
        $stmt->bindParam(':capacity', $capacity);
        $stmt->bindParam(':price', $price);

        if($stmt->execute()) {
            if (!empty($imagePaths)) {
                $imageStmt = $pdo->prepare("INSERT INTO accommodations_images (accomodation_id, image_path) VALUES (:accomodation_id, :image_path)");

                foreach ($imagePaths as $path) {
                    $imageStmt->bindParam(':accomodation_id', $id);
                    $imageStmt->bindParam(':image_path', $path);
                    $imageStmt->execute();
                }
            }

            error_log("ID: $id, Name: $name, Type: $type, Features: $features, Capacity: $capacity, Price: $price");

            echo json_encode(['success' => true, 'message' => 'Accomodation updated.']);
        } else {
            $errorInfo = $stmt->errorInfo();
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $errorInfo[2]]);
        }

    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error ' . $e->getMessage()]);
    }
}