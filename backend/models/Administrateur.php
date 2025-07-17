<?php
class Administrateur {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function creerAdministrateur($userId, $niveauAcces = 1) {
        $stmt = $this->pdo->prepare("
            INSERT INTO administrateur (id_utilisateur, niveau_acces)
            VALUES (?, ?)
        ");
        return $stmt->execute([$userId, $niveauAcces]);
    }

    public function getAdminByUserId($userId) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM administrateur 
            WHERE id_utilisateur = ?
            LIMIT 1
        ");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>