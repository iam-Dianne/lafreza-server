<?php

require_once '../shared/connection.php';
require_once '../shared/cors.php';

unset($_SESSION['staff_logged_in']);
unset($_SESSION['staff_username']);

header('Content-type: application/json');
echo json_encode(['success' => true, 'message' => 'Successfully logged out.']);
exit;
