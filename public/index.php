<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($requestUri) {
    case '/recit':
        require_once __DIR__ . '/../routes/recit_routes.php';
        break;
    case '/login':
    case '/logout':
        require_once __DIR__ . '/../routes/auth_routes.php';
        break;
    default:
        echo json_encode(['message' => 'Route not found']);
        http_response_code(404);
        break;
}
?>
