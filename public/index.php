<?php
declare(strict_types=1);

$requestUri = $_SERVER['REQUEST_URI'];

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
