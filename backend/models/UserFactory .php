<?php
class UserFactory {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function createUser($type, $data) {
        // Démarrer une transaction
        $this->pdo->beginTransaction();

        try {
            // 1. Créer l'utilisateur de base
            $utilisateur = new Utilisateur($this->pdo);
            $success = $utilisateur->creerUtilisateur(
                $data['nom'],
                $data['email'],
                $data['mot_de_passe'],
                $data['localisation'],
                $data['telephone'] ?? null
            );

            if (!$success) {
                throw new Exception("Échec de la création de l'utilisateur");
            }

            $userId = $this->pdo->lastInsertId();

            // 2. Créer le type spécifique
            switch ($type) {
                case 'admin':
                    $admin = new Administrateur($this->pdo);
                    $admin->creerAdministrateur($userId, $data['niveau_acces'] ?? 1);
                    break;

                case 'acheteur':
                    $acheteur = new Acheteur($this->pdo);
                    $acheteur->creerAcheteur($userId, $data['type_acheteur']);
                    break;

                case 'agriculteur':
                    $agriculteur = new Agriculteur($this->pdo);
                    $agriculteur->creerAgriculteur($userId, $data['type_production']);
                    break;

                default:
                    throw new Exception("Type d'utilisateur invalide");
            }

            // Valider la transaction
            $this->pdo->commit();
            return $userId;

        } catch (Exception $e) {
            // Annuler en cas d'erreur
            $this->pdo->rollBack();
            throw $e;
        }
    }
}
?>