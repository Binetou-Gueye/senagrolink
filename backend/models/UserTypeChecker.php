<?php
class UserTypeChecker {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getUserType($userId) {
        $tables = [
            'administrateur' => 'Administrateur',
            'acheteur' => 'Acheteur',
            'agriculteur' => 'Agriculteur'
        ];

        foreach ($tables as $table => $type) {
            $stmt = $this->pdo->prepare("
                SELECT 1 FROM {$table} WHERE id_utilisateur = ? LIMIT 1
            ");
            $stmt->execute([$userId]);
            if ($stmt->fetch()) {
                return $type;
            }
        }

        return 'Utilisateur';
    }
}
?>