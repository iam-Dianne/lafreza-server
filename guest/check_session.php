<?php
session_start();
require_once '../shared/connection.php';
require_once '../shared/cors.php';
header('Content-Type: application/json');

if (isset($_SESSION['guest_logged_in']) && $_SESSION['guest_logged_in'] === true) {
    echo json_encode([
        'loggedIn' => true,
        'email' => $_SESSION['guest_email']
    ]);
} else {
    echo json_encode(['loggedIn' => false]);
}
