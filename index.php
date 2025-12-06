<?php
// Simple router for views
require __DIR__ . '/config/config.php';
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '\\');
$path = preg_replace('#^' . preg_quote($base, '#') . '#', '', $path);
$path = trim($path, '/');

if($path === '' || $path === 'index.php'){
    include __DIR__ . '/views/home.php';
    exit;
}

if($path === 'categories' || $path === 'categories.php'){
    include __DIR__ . '/views/category-view.php';
    exit;
}

if($path === 'items' || $path === 'items.php'){
    include __DIR__ . '/views/item-view.php';
    exit;
}

if($path === 'compare' || $path === 'compare.php'){
    include __DIR__ . '/views/compare-view.php';
    exit;
}

http_response_code(404);
echo 'Not Found';
