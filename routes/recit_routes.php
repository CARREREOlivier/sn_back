<?php
declare(strict_types = 1);

require_once __DIR__ . '/../src/controllers/RecitController.php';

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];
$controller = new RecitController();

if ($requestUri === '/recits' && $requestMethod === 'GET') {
    $controller->displayAllRecits();
} elseif (preg_match('#^/recits/([a-zA-Z0-9_-]+)$#', $requestUri, $matches) && $requestMethod === 'GET') {
    $slug = $matches[1];
    $controller->displayRecitBySlug($slug);
} elseif ($requestUri === '/recits/create' && $requestMethod === 'POST') {
    $controller->createRecit();
} elseif (preg_match('#^/recits/([a-zA-Z0-9_-]+)$#', $requestUri, $matches) && $requestMethod === 'PUT') {
    $slug = $matches[1];
    $controller->updateRecit($slug);
} elseif (preg_match('#^/recits/delete/([a-zA-Z0-9_-]+)$#', $requestUri, $matches) && $requestMethod === 'DELETE') {
    $slug = $matches[1];
    $controller->deleteRecit($slug);
} else {
    echo json_encode(["status" => "error", "message" => "Route not found"]);
    http_response_code(404);
}
?>
