<?php
require_once __DIR__.'/../controllers/AgriculteurController.php';

header("Content-Type: application/json");

$controller = new AgriculteurController();
$method = $_SERVER['REQUEST_METHOD'];

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    switch ($method) {
        case 'POST':
            if (isset($data['user_id'], $data['type_production'])) {
                echo $controller->createAgriculteur($data['user_id'], $data['type_production']);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'user_id et type_production requis']);
            }
            break;
            
        case 'GET':
            if (isset($_GET['user_id'])) {
                echo $controller->getAgriculteur($_GET['user_id']);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'user_id requis']);
            }
            break;
            
        default:
            http_response_code(405);
            echo json_encode(['error' => 'Méthode non autorisée']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>