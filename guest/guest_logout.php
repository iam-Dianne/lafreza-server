<?php
session_start();
require_once '../shared/connection.php';
require_once '../shared/cors.php';

$_SESSION = [];
unset($_SESSION['guest_id']);
unset($_SESSION['guest_username']);
// session_destroy();

header('Content-Type: application/json');
echo json_encode(['success' => true, 'message' => 'Logged out successfully.']);
