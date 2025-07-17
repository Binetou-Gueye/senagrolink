<?php
require_once __DIR__.'/BaseController.php';
require_once __DIR__.'/../models/UserTypeChecker.php';

class UserTypeController extends BaseController {
    private $checker;

    public function __construct() {
        parent::__construct();
        $this->checker = new UserTypeChecker($this->pdo);
    }

    public function getUserType($userId) {
        $type = $this->checker->getUserType($userId);
        return $this->jsonResponse([
            'type' => $type,
            'userId' => $userId
        ]);
    }
}
?>