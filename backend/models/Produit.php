<?php
class Produit {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function creerProduit($idBoutique, $nom, $description, $prixUnitaire, $quantite, $certification, $unite_vente, $categorie) {
        $stmt = $this->pdo->prepare("
            INSERT INTO produit 
            (id_boutique, nom, description, prix_unitaire, quantite_stock, certification, unite_vente, categorie)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$idBoutique, $nom, $description, $prixUnitaire, $quantite, $certification, $unite_vente, $categorie]);
    }

    public function getProduitsByBoutique($idBoutique) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM produit 
            WHERE id_boutique = ?
        ");
        $stmt->execute([$idBoutique]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>