<?php
header('Content-Type: application/json; charset=utf-8');
require __DIR__ . '/../db/connection.php';
$pdo = getPDO();
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if(!$id){ echo json_encode(['ok'=>false,'message'=>'Missing id']); exit; }
$stmt = $pdo->prepare('SELECT * FROM items WHERE id = :id');
$stmt->execute([':id'=>$id]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$item) { echo json_encode(['ok'=>false,'message'=>'Not found']); exit; }
// fetch tags
$ts = $pdo->prepare('SELECT t.* FROM tags t JOIN item_tags it ON it.tag_id=t.id WHERE it.item_id=:id');
$ts->execute([':id'=>$id]);
$item['tags'] = $ts->fetchAll(PDO::FETCH_ASSOC);

// Add simple alternatives: other items with same type (limit 4)
$alt = $pdo->prepare('SELECT id,name,description,type FROM items WHERE type = :type AND id != :id LIMIT 4');
$alt->execute([':type'=>$item['type'], ':id'=>$id]);
$alts = $alt->fetchAll(PDO::FETCH_ASSOC);

// output combined
echo json_encode(['ok'=>true,'item'=>$item, 'alternatives'=>$alts]);
