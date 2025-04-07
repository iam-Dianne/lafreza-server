<?php
require_once '../shared/connection.php';
require_once '../shared/auth.php';

header('Content-Type: application/json');
session_start();

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {

    if (isset($_SESSION['admin_name'])) {
        echo json_encode([
            'success' => true,
            'admin_name' => $_SESSION['admin_name'],
            'admin_username' => $_SESSION['admin_username']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Admin name not set in session']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
}
