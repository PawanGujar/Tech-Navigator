<?php
// Basic config - adjust to your environment or use environment variables
$cfg = [
    'db_host' => '127.0.0.1',
    'db_port' => 3306,
    'db_name' => 'tech_navigator',
    'db_user' => 'root',
    'db_pass' => '',
    // Admin password for local admin UI (change in production)
    'admin_password' => 'admin123'
];

// Derive base path for assets and API when the project is not at the webroot
// Example: if project is served at http://localhost/Tech-Navigator, base_path -> /Tech-Navigator
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$basePath = rtrim(dirname($scriptName), '\\/');
if ($basePath === '.' || $basePath === '/' || $basePath === '') {
    $basePath = '';
}
$cfg['base_path'] = $basePath;

// build absolute base URL (http://host[:port]/basePath)
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$cfg['base_url'] = $scheme . '://' . $host . ($basePath ? $basePath : '');

return $cfg;
