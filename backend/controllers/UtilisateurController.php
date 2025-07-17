<?php
require_once __DIR__.'/BaseController.php';
require_once __DIR__.'/../models/Utilisateur.php';

class UtilisateurController extends BaseController {
    private $model;

    public function __construct() {
        parent::__construct();
        $this->model = new Utilisateur($this->pdo);
    }

    public function register($requestData) {
        // Validation
        $required = ['nom', 'email', 'mot_de_passe', 'localisation'];
        foreach ($required as $field) {
            if (empty($requestData[$field])) {
                return $this->jsonResponse(
                    ['error' => "Le champ $field est requis"], 
                    400
                );
            }
        }

        // Création
        try {
            $result = $this->model->creerUtilisateur(
                $requestData['nom'],
                $requestData['email'],
                $requestData['mot_de_passe'],
                $requestData['localisation'],
                $requestData['telephone'] ?? null
            );
            
            return $this->jsonResponse([
                'success' => $result,
                'userId' => $result ? $this->pdo->lastInsertId() : null
            ]);
            
        } catch (PDOException $e) {
            return $this->jsonResponse(
                ['error' => 'Erreur de base de données: ' . $e->getMessage()], 
                500
            );
        }
    }

    public function get($id) {
        $user = $this->model->getUtilisateur($id);
        return $this->jsonResponse(
            $user ?: ['error' => 'Utilisateur non trouvé'],
            $user ? 200 : 404
        );
    }
}
?>