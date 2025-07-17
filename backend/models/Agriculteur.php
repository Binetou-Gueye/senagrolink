<?php
class Agriculteur {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function creerAgriculteur($userId, $typeProduction) {
        $stmt = $this->pdo->prepare("
            INSERT INTO agriculteur 
            (id_utilisateur, type_production) 
            VALUES (?, ?)
        ");
        return $stmt->execute([$userId, $typeProduction]);
    }

    public function getAgriculteurByUserId($userId) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM agriculteur 
            WHERE id_utilisateur = ?
            LIMIT 1
        ");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>