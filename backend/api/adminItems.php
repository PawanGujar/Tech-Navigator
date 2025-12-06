<?php
header('Content-Type: application/json; charset=utf-8');
require __DIR__ . '/../db/connection.php';
$pdo = getPDO();

// GET => list items (brief)
if($_SERVER['REQUEST_METHOD'] === 'GET'){
    $stmt = $pdo->query('SELECT i.id,i.name,i.type,i.category_id,i.developer,i.performance_score,i.popularity_score,i.learning_curve,i.release_year FROM items i ORDER BY i.id DESC');
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['ok'=>true,'items'=>$items]);
    exit;
}

// For POST actions expect JSON body
$input = json_decode(file_get_contents('php://input'), true);
if(!$input) $input = $_POST;
$action = isset($input['action']) ? $input['action'] : '';

try{
    if($action === 'delete'){
        $id = intval($input['id']);
        if(!$id) throw new Exception('Invalid id');
        $pdo->beginTransaction();
        $pdo->prepare('DELETE FROM item_tags WHERE item_id = ?')->execute([$id]);
        $pdo->prepare('DELETE FROM items WHERE id = ?')->execute([$id]);
        $pdo->commit();
        echo json_encode(['ok'=>true]);
        exit;
    }
    if($action === 'update'){
        $id = intval($input['id']);
        $fields = isset($input['fields']) ? $input['fields'] : [];
        if(!$id || empty($fields)) throw new Exception('Invalid update payload');
        $allowed = ['name','type','category_id','developer','description','performance_score','popularity_score','learning_curve','difficulty_level','release_year'];
        $sets = [];$params = [];
        foreach($fields as $k=>$v){ if(in_array($k,$allowed)){ $sets[] = "$k = :$k"; $params[":$k"] = $v; } }
        if(count($sets)>0){
            $sql = 'UPDATE items SET ' . implode(',', $sets) . ' WHERE id = :id';
            $params[':id'] = $id;
            $stmt = $pdo->prepare($sql); $stmt->execute($params);
        }
        // tags handling: if tags provided, replace mappings
        if(isset($input['tags'])){
            $pdo->beginTransaction();
            $pdo->prepare('DELETE FROM item_tags WHERE item_id = ?')->execute([$id]);
            $tagArr = array_filter(array_map('trim', explode(',', $input['tags'])));
            foreach($tagArr as $t){ if($t==='') continue; $s = $pdo->prepare('SELECT id FROM tags WHERE name = ? LIMIT 1'); $s->execute([$t]); $r=$s->fetch(PDO::FETCH_ASSOC); if($r) $tagId=$r['id']; else { $ins=$pdo->prepare('INSERT INTO tags (name) VALUES (?)'); $ins->execute([$t]); $tagId=$pdo->lastInsertId(); } $m=$pdo->prepare('INSERT INTO item_tags (item_id, tag_id) VALUES (?,?)'); $m->execute([$id,$tagId]); }
            $pdo->commit();
        }
        echo json_encode(['ok'=>true]); exit;
    }
    echo json_encode(['ok'=>false,'error'=>'Unknown action']);
}catch(Exception $e){ if($pdo->inTransaction()) $pdo->rollBack(); http_response_code(500); echo json_encode(['ok'=>false,'error'=>$e->getMessage()]); }
