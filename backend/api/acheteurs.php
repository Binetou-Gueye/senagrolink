<?php
require_once __DIR__.'/../controllers/AcheteurController.php';

header("Content-Type: application/json");

$controller = new AcheteurController();
$method = $_SERVER['REQUEST_METHOD'];

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    switch ($method) {
        case 'POST':
            if (isset($data['user_id'], $data['type_acheteur'])) {
                echo $controller->createAcheteur($data['user_id'], $data['type_acheteur']);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'user_id et type_acheteur requis']);
            }
            break;
            
        case 'GET':
            if (isset($_GET['user_id'])) {
                echo $controller->getAcheteur($_GET['user_id']);
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