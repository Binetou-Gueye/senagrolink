<?php
require_once __DIR__.'/BaseController.php';
require_once __DIR__.'/../models/Acheteur.php';

class AcheteurController extends BaseController {
    private $model;

    public function __construct() {
        parent::__construct();
        $this->model = new Acheteur($this->pdo);
    }

    public function createAcheteur($userId, $typeAcheteur) {
        try {
            $success = $this->model->creerAcheteur($userId, $typeAcheteur);
            return $this->jsonResponse([
                'success' => $success,
                'acheteurId' => $success ? $userId : null
            ]);
        } catch (PDOException $e) {
            return $this->jsonResponse(
                ['error' => 'Erreur lors de la création: ' . $e->getMessage()],
                500
            );
        }
    }

    public function getAcheteur($userId) {
        $acheteur = $this->model->getAcheteurByUserId($userId);
        return $this->jsonResponse(
            $acheteur ?: ['error' => 'Acheteur non trouvé'],
            $acheteur ? 200 : 404
        );
    }
}
?>