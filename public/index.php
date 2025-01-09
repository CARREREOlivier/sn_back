<?php
declare(strict_types=1);

header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
//DEBUG
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$requestUri = $_SERVER['REQUEST_URI'];
// Gère les requêtes OPTIONS (pre-flight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
// Redirige vers user_routes.php pour les chemins /users
if (preg_match('#^/users(/.*)?$#', $requestUri)) {
    require_once __DIR__ . '/../routes/user_routes.php';
}
// Redirige vers article_routes.php pour les chemins /recits
elseif (preg_match('#^/recits/([a-zA-Z0-9_-]+)/(new-article|([a-zA-Z0-9_-]+(/(update-article|delete-article)?)?))$#', $requestUri)) {
    require_once __DIR__ . '/../routes/article_routes.php';
}
// Redirige vers news_routes.php pour les chemins /news
elseif (preg_match('#^/news(/.*)?$#', $requestUri)) {
    require_once __DIR__ . '/../routes/news_routes.php';
}
// Par défaut, redirige vers recit_routes.php
else {
    require_once __DIR__ . '/../routes/recit_routes.php';
}
