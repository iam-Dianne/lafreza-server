<?php
require_once '../shared/auth.php';
require_once '../shared/connection.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);

try {
    if (isset($_GET['guest_id'])) {
        $guest_id = $_GET['guest_id'];

        $stmt = $pdo->prepare("SELECT r.reservation_id, r.guest_id, r.date_from, r.date_to, 
                                      a.id, a.accomodation_name 
                               FROM reservations r
                               LEFT JOIN accomodations a ON r.accommodation_id = a.id
                               WHERE r.guest_id = :guest_id");
        $stmt->bindParam(':guest_id', $guest_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($reservations) > 0) {
                $reservationDetails = $reservations[0];

                echo json_encode([
                    'success' => true,
                    'data' => [
                        'reservation' => $reservationDetails
                    ]
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'No reservation found']);
            }
        } else {
            $errorInfo = $stmt->errorInfo();
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $errorInfo[2]]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'guest_id parameter is missing']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
