<?php
include_once 'config.php';
header('Content-Type: application/json');
$input = file_get_contents('php://input');
$input = json_decode($input, true);

$insert = 'UPDATE game SET player2 = ? WHERE id = ?';
try {
    $stmt = $dbh->prepare($insert);
    $stmt->execute([$input['player2'], $input['id']]);
    $select = 'SELECT * FROM game WHERE id = ?';
    $stmt = $dbh->prepare($select);
    $stmt->execute([$input['id']]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode(['status' => 'Ok', 'type' => 'join', 'data' => $data, 'player' => $data['player2'], 'id' => $data['id']]);
    exit;
} catch (PDOException $e) {
    error_log($e->getMessage());
    echo json_encode(['status' => 'Failed', 'reason' => 'DB Insert Error']);
    exit;
}
