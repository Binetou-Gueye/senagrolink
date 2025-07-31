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

    public function getProduitsByBoutique($idUser) {
        $stmt = $this->pdo->prepare("
            SELECT 
            p.id_produit ,
            p.nom ,
            p.description ,
            p.prix_unitaire ,
            p.quantite_stock ,
            p.unite_vente ,
            p.certification ,
            p.unite_vente ,
            p.categorie
            FROM boutique b, produit p
            WHERE b.id_agriculteur = ? AND p.id_boutique = b.id_boutique
        ");
        $stmt->execute([$idUser]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>