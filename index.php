<?php
// CORS-Header setzen
$allowedOrigins = explode(',', getenv('ALLOWED_ORIGINS') ?: 'https://webshop-cbw.vercel.app');
$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';

if (in_array($origin, $allowedOrigins)) {
    header("Access-Control-Allow-Origin: $origin");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-Control-Allow-Credentials: true");
}

// OPTIONS-Requests für CORS-Preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Fehlerbehandlung
error_reporting(E_ALL);
ini_set('display_errors', getenv('APP_DEBUG') ? '1' : '0');
ini_set('log_errors', '1');
ini_set('error_log', 'php://stderr');

// Routing
$requestUri = $_SERVER['REQUEST_URI'];
$basePath = '/';

// Entferne Query-String und trailing slash
$path = parse_url($requestUri, PHP_URL_PATH);
$path = rtrim($path, '/');

// Einfaches Routing
switch ($path) {
    case $basePath:
    case $basePath . 'index.php':
        require __DIR__ . '/php/index.php';
        break;
    case $basePath . 'login':
        require __DIR__ . '/php/login.php';
        break;
    case $basePath . 'register':
        require __DIR__ . '/php/register.php';
        break;
    case $basePath . 'logout':
        require __DIR__ . '/php/logout.php';
        break;
    case $basePath . 'impressum':
        require __DIR__ . '/php/impressum.php';
        break;
    case $basePath . 'datenschutz':
        require __DIR__ . '/php/datenschutz.php';
        break;
    default:
        header("HTTP/1.0 404 Not Found");
        echo "404 - Seite nicht gefunden";
        break;
}
?> 