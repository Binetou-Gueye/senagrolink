<?php
require_once __DIR__.'/BaseController.php';
require_once __DIR__.'/../models/Administrateur.php';

class AdministrateurController extends BaseController {
    private $model;

    public function __construct() {
        parent::__construct();
        $this->model = new Administrateur($this->pdo);
    }

    public function createAdmin($userId, $niveauAcces = 1) {
        try {
            $success = $this->model->creerAdministrateur($userId, $niveauAcces);
            return $this->jsonResponse([
                'success' => $success,
                'adminId' => $success ? $userId : null
            ]);
        } catch (PDOException $e) {
            return $this->jsonResponse(
                ['error' => 'Erreur lors de la création: ' . $e->getMessage()],
                500
            );
        }
    }

    public function getAdmin($userId) {
        $admin = $this->model->getAdminByUserId($userId);
        return $this->jsonResponse(
            $admin ?: ['error' => 'Administrateur non trouvé'],
            $admin ? 200 : 404
        );
    }
}
?>