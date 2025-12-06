<?php
header('Content-Type: application/json; charset=utf-8');
require __DIR__ . '/../db/connection.php';
$pdo = getPDO();
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'popularity';
// extra filters
$min_performance = isset($_GET['min_performance']) ? intval($_GET['min_performance']) : 0;
$learning_curve = isset($_GET['learning_curve']) ? $_GET['learning_curve'] : '';
$tags = isset($_GET['tags']) ? $_GET['tags'] : ''; // comma-separated tag names
$params = [];
$sql = 'SELECT DISTINCT i.* FROM items i WHERE 1=1';
if($q !== ''){ $sql .= ' AND (i.name LIKE :q OR i.description LIKE :q)'; $params[':q'] = "%$q%"; }
if($category_id){ $sql .= ' AND i.category_id = :cid'; $params[':cid']=$category_id; }

// apply extra filters (build WHERE clause first, ORDER BY will be appended later)
if($min_performance){
	$sql .= ' AND i.performance_score >= ' . intval($min_performance);
}
if($learning_curve){
	$sql .= ' AND i.learning_curve = ' . $pdo->quote($learning_curve);
}

// tag filtering
// build ORDER BY clause after filters
$orderSql = '';
if($sort === 'performance'){
    $orderSql = ' ORDER BY i.performance_score DESC, i.popularity_score DESC';
} elseif ($sort === 'ease'){
    $orderSql = ' ORDER BY i.difficulty_level ASC, i.popularity_score DESC';
} elseif ($sort === 'newest'){
    $orderSql = ' ORDER BY i.release_year DESC';
} else {
    $orderSql = ' ORDER BY i.popularity_score DESC, i.performance_score DESC';
}

if($tags !== ''){
	$tagArr = array_filter(array_map('trim', explode(',', $tags)));
	if(count($tagArr) > 0){
		// join with item_tags and tags tables
		$placeholders = implode(',', array_fill(0, count($tagArr), '?'));
		$sql = str_replace('SELECT DISTINCT i.* FROM items i WHERE 1=1', 
			"SELECT DISTINCT i.* FROM items i INNER JOIN item_tags it ON i.id = it.item_id INNER JOIN tags t ON it.tag_id = t.id WHERE 1=1 AND t.name IN ($placeholders)", $sql);
		$sql .= $orderSql;
		$stmt = $pdo->prepare($sql);
		$idx = 1;
		foreach($tagArr as $t) { $stmt->bindValue($idx++, $t); }
		foreach($params as $k => $v) { $stmt->bindValue($k, $v); }
		$stmt->execute();
		$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
	} else {
		$sql .= $orderSql;
		$stmt = $pdo->prepare($sql);
		$stmt->execute($params);
		$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
} else {
	$sql .= $orderSql;
	$stmt = $pdo->prepare($sql);
	$stmt->execute($params);
	$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
echo json_encode(['ok'=>true,'items'=>$items]);
