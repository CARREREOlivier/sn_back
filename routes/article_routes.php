<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/controllers/ArticleController.php';

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];
$controller = new ArticleController();

if (preg_match('#^/recits/([a-zA-Z0-9_-]+)/new-article$#', $requestUri, $matches) && $requestMethod === 'POST') {
    $slugRecit = $matches[1];
    $controller->createArticle($slugRecit);
} elseif (preg_match('#^/recits/([a-zA-Z0-9_-]+)/([a-zA-Z0-9_-]+)$#', $requestUri, $matches) && $requestMethod === 'GET') {
    $slugRecit = $matches[1];
    $slugArticle = $matches[2];
    $controller->displayArticleBySlug($slugRecit, $slugArticle);
} elseif (preg_match('#^/recits/([a-zA-Z0-9_-]+)/([a-zA-Z0-9_-]+)/update-article$#', $requestUri, $matches) && $requestMethod === 'PUT') {
    $slugRecit = $matches[1];
    $slugArticle = $matches[2];
    $controller->updateArticle($slugRecit, $slugArticle);
} elseif (preg_match('#^/recits/([a-zA-Z0-9_-]+)/([a-zA-Z0-9_-]+)/delete-article$#', $requestUri, $matches) && $requestMethod === 'DELETE') {
    $slugRecit = $matches[1];
    $slugArticle = $matches[2];
    $controller->deleteArticle($slugRecit, $slugArticle);
} else {
    echo json_encode(["status" => "error", "message" => "Route not found"]);
    http_response_code(404);
}
