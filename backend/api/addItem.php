<?php
header('Content-Type: application/json; charset=utf-8');
require __DIR__ . '/../db/connection.php';
$pdo = getPDO();

// accept JSON body
$input = json_decode(file_get_contents('php://input'), true);
if(!$input) $input = $_POST;

$name = isset($input['name']) ? trim($input['name']) : '';
$type = isset($input['type']) ? trim($input['type']) : '';
$category_id = isset($input['category_id']) ? intval($input['category_id']) : 0;
$developer = isset($input['developer']) ? trim($input['developer']) : '';
$description = isset($input['description']) ? trim($input['description']) : '';
$performance_score = isset($input['performance_score']) ? intval($input['performance_score']) : 0;
$popularity_score = isset($input['popularity_score']) ? intval($input['popularity_score']) : 0;
$learning_curve = isset($input['learning_curve']) ? trim($input['learning_curve']) : '';
$difficulty_level = isset($input['difficulty_level']) ? intval($input['difficulty_level']) : 0;
$release_year = isset($input['release_year']) ? intval($input['release_year']) : 0;
$tags = isset($input['tags']) ? trim($input['tags']) : '';

// basic validation
if($name === '' || $type === '' || $category_id <= 0){
    echo json_encode(['ok'=>false,'error'=>'Missing required fields: name, type, category_id']);
    exit;
}

try{
    $pdo->beginTransaction();
    $stmt = $pdo->prepare('INSERT INTO items (name, type, category_id, developer, description, performance_score, popularity_score, learning_curve, difficulty_level, release_year) VALUES (:name, :type, :cid, :dev, :desc, :perf, :pop, :lc, :diff, :ry)');
    $stmt->execute([
        ':name' => $name,
        ':type' => $type,
        ':cid' => $category_id,
        ':dev' => $developer,
        ':desc' => $description,
        ':perf' => $performance_score,
        ':pop' => $popularity_score,
        ':lc' => $learning_curve,
        ':diff' => $difficulty_level,
        ':ry' => $release_year
    ]);
    $itemId = $pdo->lastInsertId();

    // handle tags (comma-separated)
    if($tags !== ''){
        $tagArr = array_filter(array_map('trim', explode(',', $tags)));
        foreach($tagArr as $t){
            if($t === '') continue;
            // check existing
            $s = $pdo->prepare('SELECT id FROM tags WHERE name = ? LIMIT 1');
            $s->execute([$t]);
            $row = $s->fetch(PDO::FETCH_ASSOC);
            if($row){
                $tagId = $row['id'];
            } else {
                $ins = $pdo->prepare('INSERT INTO tags (name) VALUES (?)');
                $ins->execute([$t]);
                $tagId = $pdo->lastInsertId();
            }
            // insert mapping
            $m = $pdo->prepare('INSERT INTO item_tags (item_id, tag_id) VALUES (?, ?)');
            $m->execute([$itemId, $tagId]);
        }
    }

    $pdo->commit();
    echo json_encode(['ok'=>true,'id'=>$itemId]);
}catch(Exception $e){
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['ok'=>false,'error'=>'Server error: ' . $e->getMessage()]);
}
