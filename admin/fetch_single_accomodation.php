<?php
require_once '../shared/auth.php';
require_once '../shared/connection.php';

header('Content-Type: application/json');
error_reporting(E_ALL); 
ini_set('display_errors', 0);

try {
    if(isset($_GET['id'])) {
        $id = $_GET['id'];

        $stmt = $pdo->prepare("SELECT a.*, ai.image_path FROM accomodations a
                               LEFT JOIN accommodations_images ai ON a.id = ai.accomodation_id
                               WHERE a.id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if($stmt->execute()) {
            $accommodation = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // if ($accommodation) {
            //     echo json_encode(['success' => true, 'data' => $accommodation]);
            // } else {
            //     echo json_encode(['success' => false, 'message' => 'Accommodation not found']);
            // }

            $accommodationDetails = $accommodation[0]; // First row contains the main accommodation data
            $images = array_map(function($item) {
                return $item['image_path']; // Extract image paths
            }, $accommodation); // All rows will contain the image paths

            echo json_encode([
                'success' => true,
                'data' => [
                    'accommodation' => $accommodationDetails,
                    'images' => $images
                ]
            ]);

        } else {
            $errorInfo = $stmt->errorInfo();
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $errorInfo[2]]);
        }
    }

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}