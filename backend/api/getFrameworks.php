<?php
header('Content-Type: application/json; charset=utf-8');
require __DIR__ . '/../db/connection.php';
$pdo = getPDO();
$stmt = $pdo->prepare("SELECT * FROM items WHERE type='framework' ORDER BY popularity_score DESC");
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode(['ok'=>true,'frameworks'=>$rows]);
