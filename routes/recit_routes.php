<?php

use RecitController\RecitController;

$controller = new RecitController();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_GET['id'])) {
            $controller->getRecit($_GET['id']);
        } else {
            $controller->getAllRecits();
        }
        break;
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $controller->createRecit($data);
        break;
    case 'PUT':
        parse_str(file_get_contents("php://input"), $data);
        $controller->updateRecit($_GET['id'], $data);
        break;
    case 'DELETE':
        $controller->deleteRecit($_GET['id']);
        break;
}
