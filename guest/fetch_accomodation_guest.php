<?php
require_once '../shared/connection.php';
require_once '../shared/cors.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);

try {
    $stmt = $pdo->prepare("SELECT a.*, ai.image_path
                            FROM accomodations a
                            LEFT JOIN (
                                SELECT accomodation_id, MIN(image_path) AS image_path
                                FROM accommodations_images
                                GROUP BY accomodation_id
                            ) ai ON a.id = ai.accomodation_id
                            ");

    if ($stmt->execute()) {
        $accommodations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $baseUrl = "http://localhost/lafreza-server/";

        foreach ($accommodations as &$accommodation) {
            // Modify the image path by replacing the relative part with the base URL
            $accommodation['image_path'] = str_replace("..", $baseUrl, $accommodation['image_path']);
        }


        echo json_encode(['success' => true, 'data' => $accommodations]);
    } else {
        $errorInfo = $stmt->errorInfo();
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $errorInfo[2]]);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
