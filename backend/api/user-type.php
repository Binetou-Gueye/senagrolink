<?php
require_once __DIR__.'/../controllers/UserTypeController.php';

header("Content-Type: application/json");

$controller = new UserTypeController();
$method = $_SERVER['REQUEST_METHOD'];

if ($method !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Seule la méthode GET est autorisée']);
    exit;
}

try {
    if (!isset($_GET['user_id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'user_id requis']);
        exit;
    }
    
    echo $controller->getUserType($_GET['user_id']);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>