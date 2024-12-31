<?php 

require_once '../shared/auth.php';
require_once '../shared/connection.php';

// data to fetch sample
$data = [
    'user_count' => 20,
    'bookings_count' => 30,
];

echo json_encode(['success' => true, 'data' => $data]);
