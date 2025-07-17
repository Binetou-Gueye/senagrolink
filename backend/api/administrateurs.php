<?php
require_once __DIR__.'/../controllers/AdministrateurController.php';

header("Content-Type: application/json");

$controller = new AdministrateurController();
$method = $_SERVER['REQUEST_METHOD'];

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    switch ($method) {
        case 'POST':
            if (isset($data['user_id'])) {
                $niveau = $data['niveau_acces'] ?? 1;
                echo $controller->createAdmin($data['user_id'], $niveau);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'user_id requis']);
            }
            break;
            
        case 'GET':
            if (isset($_GET['user_id'])) {
                echo $controller->getAdmin($_GET['user_id']);
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