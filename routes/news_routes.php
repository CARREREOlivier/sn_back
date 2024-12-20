<?php
declare(strict_types=1);

use App\Controllers\NewsController;

require_once __DIR__ . '/../src/controllers/NewsController.php';

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];
$controller = new NewsController();

// Route pour récupérer toutes les nouvelles
if ($requestUri === '/news' && $requestMethod === 'GET') {
    $controller->getAllNews();
}
// Route pour récupérer une nouvelle par ID
elseif (preg_match('#^/news/(\d+)$#', $requestUri, $matches) && $requestMethod === 'GET') {
    $newsId = (int)$matches[1];
    $controller->getNewsById($newsId);
}
// Route pour créer une nouvelle
elseif ($requestUri === '/news/create' && $requestMethod === 'POST') {
    $controller->createNews();
}
// Route pour mettre à jour une nouvelle par ID
elseif (preg_match('#^/news/(\d+)/update$#', $requestUri, $matches) && $requestMethod === 'PUT') {
    $newsId = (int)$matches[1];
    $controller->updateNews($newsId);
}
// Route pour supprimer une nouvelle par ID
elseif (preg_match('#^/news/(\d+)/delete$#', $requestUri, $matches) && $requestMethod === 'DELETE') {
    $newsId = (int)$matches[1];
    $controller->deleteNews($newsId);
}
// Route non trouvée
else {
    echo json_encode(["status" => "error", "message" => "Route not found"]);
    http_response_code(404);
}
