<?php
include_once '../config.php';
header('Content-Type: application/json');
$input = file_get_contents('php://input');
$input = json_decode($input, true);

$id = $input['id'];
$player = $input['player'];


$select = 'SELECT * FROM game WHERE id = ?';
$stmt = $dbh->prepare($select);
$stmt->execute([$id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$update = 'UPDATE game SET player_turn = ? WHERE id = ?';

if ($row['player1'] === $player && $row['player_turn'] === 1) {
    $stmt = $dbh->prepare($update);
    $stmt->execute([2, $id]);
    echo json_encode(['status' => 'Ok', 'data' => $row, 'placement' => $input['placement'], 'turn' => 2]);
    exit;
} else if ($row['player2'] === $player && $row['player_turn'] === 2) {
    $stmt = $dbh->prepare($update);
    $stmt->execute([1, $id]);
    echo json_encode(['status' => 'Ok', 'data' => $row, 'placement' => $input['placement'], 'turn' => 1]);
    exit;
}
echo json_encode(['status' => 'Failed']);
exit;
