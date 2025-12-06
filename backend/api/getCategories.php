<?php
header('Content-Type: application/json; charset=utf-8');
require __DIR__ . '/../db/connection.php';
$pdo = getPDO();
$stmt = $pdo->query('SELECT id,name,description,icon FROM categories ORDER BY id');
$cats = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode(['ok'=>true,'categories'=>$cats]);
