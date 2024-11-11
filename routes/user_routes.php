<?php
declare(strict_types=1);



require_once __DIR__ . '/../src/controllers/UserController.php';
use App\Controllers\UserController;

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];
$controller = new UserController();

// Route pour récupérer tous les utilisateurs (Admin uniquement)
if ($requestUri === '/users' && $requestMethod === 'GET') {
    $controller->getAllUsers();
}
// Route pour récupérer un utilisateur par ID
                        //'#^/users/(\d+)$#'
if (preg_match('#^/users/(\d+)$#', $requestUri, $matches) && $requestMethod === 'GET') {
    $userId = (int)$matches[1];
    $controller->getUserById($userId);
}
// Route pour créer un nouvel utilisateur (Admin uniquement)
elseif ($requestUri === '/user/create' && $requestMethod === 'POST') {
    $controller->createUser();
}
// Route pour mettre à jour un utilisateur par ID
elseif (preg_match('#^/user/(\d+)/update$#', $requestUri, $matches) && $requestMethod === 'PUT') {
    $userId = (int)$matches[1];
    $controller->updateUser($userId);
}
// Route pour supprimer un utilisateur (Admin uniquement)
elseif (preg_match('#^/user/(\d+)/delete$#', $requestUri, $matches) && $requestMethod === 'DELETE') {
    $userId = (int)$matches[1];
    $controller->deleteUser($userId);
}
// Route non trouvée
else {
    echo json_encode(["status" => "error", "message" => "Route not found"]);
    http_response_code(404);
}
?>
