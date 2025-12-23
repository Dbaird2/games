<?php
include_once 'config.php';
header('Content-Type: application/json');
$input = file_get_contents('php://input');
$input = json_decode($input, true);

$select = 'SELECT * FROM game';
$stmt = $dbh->query($select);
try {
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['status' => 'Ok', 'data' => $rows, 'type' => 'All']);
    exit;
} catch (PDOException $e) {
    echo json_encode(['status' => 'Failed']);
    exit;
}
