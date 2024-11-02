<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/Controllers/AuthController.php';

$authController = new AuthController();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] === '/login') {
    $data = json_decode(file_get_contents('php://input'), true);
    $response = $authController->login($data['username'], $data['password'], $data['rememberMe']);
    echo json_encode($response);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] === '/logout') {
    $authController->logout();
}
?>
