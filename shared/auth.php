<?php
session_start();

require_once '../shared/connection.php';
require_once '../shared/cors.php';



header('Content-Type: application/json');

if (isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] == true) {
    
    echo json_encode(['success' => true, 'message' => 'Authorized access.']);
    
    
} else {
    http_response_code(401); // unauthorized
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
    
}