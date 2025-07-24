<?php
class Acheteur {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function creerAcheteur($userId, $typeAcheteur) {
        $stmt = $this->pdo->prepare("
            INSERT INTO acheteur (id_utilisateur, type_acheteur)
            VALUES (?, ?)
        ");
        return $stmt->execute([$userId, $typeAcheteur]);
    }

    public function getAcheteurByUserId($userId) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM acheteur 
            WHERE id_utilisateur = ?
            LIMIT 1
        ");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getTypeAcheteur($userId) {
        $stmt = $this->pdo->prepare("
            SELECT type_acheteur FROM acheteur
            WHERE id_utilisateur = ?
            LIMIT 1
        ");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>