<?php
// Basic PDO connection using config
$cfg = require __DIR__ . '/../../config/config.php';
function getPDO(){
    global $cfg;
    static $pdo = null;
    if($pdo) return $pdo;
    $host = $cfg['db_host'] ?? '127.0.0.1';
    $port = $cfg['db_port'] ?? 3306;
    $db = $cfg['db_name'] ?? 'tech_navigator';
    $user = $cfg['db_user'] ?? 'root';
    $pass = $cfg['db_pass'] ?? '';
    $dsn = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";
    try{
        $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    }catch(Exception $e){
        http_response_code(500); echo json_encode(['ok'=>false,'error'=>'DB connection failed']); exit;
    }
    return $pdo;
}
