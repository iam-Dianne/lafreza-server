<?php 

$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'lafrezagold_db';

// data source name
$dsn = 'mysql:host=' . $dbHost . ';dbname=' . $dbName;

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die('Conection failed: ' . $e->getMessage());
}