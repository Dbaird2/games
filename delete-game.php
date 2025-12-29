<?php
include_once 'config.php';
header('Content-Type: application/json');
$input = file_get_contents('php://input');
$input = json_decode($input, true);
$id = $input;
try {
    $delete = 'DELETE FROM game WHERE id = ?';
    $stmt = $dbh->prepare($delete);
    $stmt->execute([$id]);
    echo json_encode(['status' => 'Ok']);
    exit;
} catch (PDOException $e) {
    echo json_encode(['status' => 'Failed', 'reason' => 'DB error']);
    exit;
}
