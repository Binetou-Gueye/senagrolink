<?php
require_once __DIR__ . '/../controllers/TypeProductionController.php';

$controller = new TypeProductionController();
$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        if (isset($_GET['user_id'])) {
            $controller->getUserTypes($_GET['user_id']);
        } else {
            $controller->getAllTypes();
        }
        break;
    case 'POST':
        if (isset($_GET['action'])) {
            if ($_GET['action'] === 'create') {
                $controller->createType($data);
            } elseif ($_GET['action'] === 'add_to_user') {
                $controller->addUserType($data);
            }
        }
        break;
    case 'DELETE':
        $controller->removeUserType($data);
        break;
    default:
        header("HTTP/1.1 405 Method Not Allowed");
        break;
}
?>