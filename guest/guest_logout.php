<?php
session_start();
require_once '../shared/connection.php';
require_once '../shared/cors.php';

$_SESSION = [];
session_destroy();

header('Content-Type: application/json');
echo json_encode(['success' => true, 'message' => 'Logged out successfully.']);
