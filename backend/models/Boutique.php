<?php
class Boutique {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function creerBoutique($idAgriculteur, $avatar, $emplacement) {
        $stmt = $this->pdo->prepare("
            INSERT INTO boutique (id_agriculteur, avatar, emplacement)
            VALUES (?, ?, ?)
        ");
        return $stmt->execute([$idAgriculteur, $avatar, $emplacement]);
    }

    public function getBoutiqueByAgriculteur($idAgriculteur) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM boutique 
            WHERE id_agriculteur = ?
        ");
        $stmt->execute([$idAgriculteur]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>