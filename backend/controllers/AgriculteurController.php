<?php
require_once __DIR__.'/BaseController.php';
require_once __DIR__.'/../models/Agriculteur.php';

class AgriculteurController extends BaseController {
    private $model;

    public function __construct() {
        parent::__construct();
        $this->model = new Agriculteur($this->pdo);
    }

    public function createAgriculteur($userId, $typeProduction) {
        try {
            $success = $this->model->creerAgriculteur($userId, $typeProduction);
            return $this->jsonResponse([
                'success' => $success,
                'agriculteurId' => $success ? $userId : null
            ]);
        } catch (PDOException $e) {
            return $this->jsonResponse(
                ['error' => 'Erreur lors de la création: ' . $e->getMessage()],
                500
            );
        }
    }

    public function getAgriculteur($userId) {
        $agriculteur = $this->model->getAgriculteurByUserId($userId);
        return $this->jsonResponse(
            $agriculteur ?: ['error' => 'Agriculteur non trouvé'],
            $agriculteur ? 200 : 404
        );
    }
}
?>