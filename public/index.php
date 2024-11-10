<?php
declare(strict_types=1);

$requestUri = $_SERVER['REQUEST_URI'];

// Route vers article_routes.php uniquement si l'URL contient un chemin lié aux articles
if (preg_match('#^/recits/([a-zA-Z0-9_-]+)/(new-article|([a-zA-Z0-9_-]+(/(update-article|delete-article)?)?))$#', $requestUri)) {
    require_once __DIR__ . '/../routes/article_routes.php';
} else {
    require_once __DIR__ . '/../routes/recit_routes.php';
}
