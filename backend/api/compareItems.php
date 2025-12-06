<?php
header('Content-Type: application/json; charset=utf-8');
require __DIR__ . '/../db/connection.php';
$pdo = getPDO();
$ids = isset($_GET['ids']) ? $_GET['ids'] : '';
if(!$ids){ echo json_encode(['ok'=>false,'message'=>'Missing ids']); exit; }
$idsArr = array_filter(array_map('intval', explode(',', $ids)));
if(!$idsArr){ echo json_encode(['ok'=>false,'message'=>'Invalid ids']); exit; }
$in = implode(',', array_fill(0,count($idsArr),'?'));
$stmt = $pdo->prepare("SELECT * FROM items WHERE id IN ($in)");
$stmt->execute($idsArr);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode(['ok'=>true,'items'=>$rows]);
