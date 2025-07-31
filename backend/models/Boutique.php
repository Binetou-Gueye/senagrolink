<?php
class Boutique {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function creerBoutique($idAgriculteur, $nom, $avatar, $emplacement) {
        $stmt = $this->pdo->prepare("
            INSERT INTO boutique (id_agriculteur, nom, avatar, emplacement)
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([$idAgriculteur, $nom, $avatar, $emplacement]);
    }

    public function getBoutiqueByAgriculteur($idAgriculteur) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM boutique 
            WHERE id_agriculteur = ?
        ");
        $stmt->execute([$idAgriculteur]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getBoutiqueProduits() {
        $stmt = $this->pdo->prepare("
            SELECT 
                b.id_boutique,
                b.nom,
                b.date_creation,
                b.emplacement,
                u.nom,
                u.nom AS nom_utilisateur,
                p.id_produit,
                p.nom,
                p.description,
                p.prix_unitaire
            FROM 
                boutique b, utilisateur u, produit p
            WHERE 
                b.id_agriculteur = u.id_utilisateur
            AND 
                b.id_boutique = p.id_boutique
            ORDER BY 
                p.date_ajout DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBoutiques(){
        $stmt = $this->pdo->prepare("
            SELECT
            b.id_boutique,
            b.nom AS nom_boutique,
            b.emplacement,
            u.nom,
            b.date_creation
            FROM boutique b, utilisateur u
            WHERE u.id_utilisateur = b.id_agriculteur
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>