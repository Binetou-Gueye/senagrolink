<?php
require_once __DIR__.'/BaseController.php';
require_once __DIR__.'/../models/UserFactory.php';

class UserFactoryController extends BaseController {
    private $factory;

    public function __construct() {
        parent::__construct();
        $this->factory = new UserFactory($this->pdo);
    }

    public function createUser($type, $requestData) {
        try {
            $userId = $this->factory->createUser($type, $requestData);
            return $this->jsonResponse([
                'success' => true,
                'userId' => $userId,
                'type' => $type
            ], 201);
        } catch (Exception $e) {
            return $this->jsonResponse(
                ['error' => $e->getMessage()],
                400
            );
        }
    }
}
?>