<?php

require_once '../shared/connection.php';
require_once '../shared/cors.php';

// session_start();
// session_unset();
// session_destroy();

unset($_SESSION['admin_logged_in']);
unset($_SESSION['admin_username']);

header('Content-type: application/json');
echo json_encode(['success' => true, 'message' => 'Successfully logged out.']);
exit;
