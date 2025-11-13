<?php
require 'config/Database.php';
require 'config/Auth.php';
require 'models/CategoryModel.php';
require 'controllers/CategoryController.php';

// Simple router
$db = Database::getInstance()->getConnection();
$auth = new Auth($db);
// authenticate all requests
$auth->authenticate();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];
// remove leading/trailing slashes
$uri = trim($uri, '/');
$segments = explode('/', $uri);

// Expecting routes like: api/categories or api/categories/{id}
if (count($segments) >= 2 && $segments[0] === 'api' && $segments[1] === 'categories') {
    $model = new CategoryModel($db);
    $controller = new CategoryController($model);
    $id = isset($segments[2]) && is_numeric($segments[2]) ? (int)$segments[2] : null;
    // read input body as json
    $input = json_decode(file_get_contents('php://input'), true) ?: [];

    switch ($method) {
        case 'GET':
            if ($id) $controller->show($id);
            else $controller->index();
            break;
        case 'POST':
            $controller->store($input);
            break;
        case 'PUT':
        case 'PATCH':
            if (!$id) {
                http_response_code(400);
                echo json_encode(['error' => 'Bad Request', 'message' => 'ID required for update']);
                exit;
            }
            $controller->update($id, $input);
            break;
        case 'DELETE':
            if (!$id) {
                http_response_code(400);
                echo json_encode(['error' => 'Bad Request', 'message' => 'ID required for delete']);
                exit;
            }
            $controller->destroy($id);
            break;
        default:
            http_response_code(405);
            header('Allow: GET, POST, PUT, PATCH, DELETE');
            echo json_encode(['error' => 'Method Not Allowed']);
    }
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Not Found', 'message' => 'Endpoint not found']);
}
