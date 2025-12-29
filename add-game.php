<?php
include_once 'config.php';
header('Content-Type: application/json');
$input = file_get_contents('php://input');
$input = json_decode($input, true);

$insert = 'INSERT INTO game (type, player1) VALUES (?, ?)';
$error = 0;
try {
    $stmt = $dbh->prepare($insert);
    $stmt->execute([trim($input['game']), trim($input['player'])]);
} catch (PDOException $e) {
    error_log($e->getMessage());
    echo json_encode(['status' => 'Failed', 'reason' => 'DB insert Error']);
    exit;
}
if ($error === 0) {
    $select = 'SELECT id, type, player1 FROM game WHERE type = ? AND player1 = ?';
    $stmt = $dbh->prepare($select);
    try {
        $stmt->execute([trim($input['game']), trim($input['player'])]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        $data = json_encode($data);
        echo json_encode(['status' => 'Ok', 'data' => $data, 'ip' => trim($input['ip'])]);
        exit;
    } catch (PDOException $e) {
        echo json_encode(['status' => 'Failed', 'reason' => 'DB select Error']);
        exit;
    }
}
