<?php
require_once '../shared/auth.php';
require_once '../shared/connection.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);

try {
    $stmtPending = $pdo->prepare("SELECT COUNT(*) AS pending_count FROM reservations WHERE status = 'pending'");
    $stmtPending->execute();
    $pending = $stmtPending->fetch(PDO::FETCH_ASSOC);
    $pendingCount = $pending['pending_count'];

    echo json_encode([
        'success' => true,
        'pending_count' => $pendingCount
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
