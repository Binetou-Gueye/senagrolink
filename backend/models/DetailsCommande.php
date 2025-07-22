<?php
class DetailsCommande {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function ajouterProduit($idCommande, $idProduit, $quantite, $prixUnitaire) {
        $stmt = $this->pdo->prepare("
            INSERT INTO details_commande 
            (id_commande, id_produit, quantite, prix_unitaire) 
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([
            $idCommande,
            $idProduit,
            $quantite,
            $prixUnitaire
        ]);
    }

    public function getDetailsCommande($idCommande) {
        $stmt = $this->pdo->prepare("
            SELECT d.*, p.nom as produit_nom, p.description as produit_description
            FROM details_commande d
            JOIN produit p ON d.id_produit = p.id_produit
            WHERE d.id_commande = ?
        ");
        $stmt->execute([$idCommande]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function supprimerProduit($idCommande, $idProduit) {
        $stmt = $this->pdo->prepare("
            DELETE FROM details_commande 
            WHERE id_commande = ? AND id_produit = ?
        ");
        return $stmt->execute([$idCommande, $idProduit]);
    }

    public function updateQuantite($idCommande, $idProduit, $nouvelleQuantite) {
        $stmt = $this->pdo->prepare("
            UPDATE details_commande 
            SET quantite = ? 
            WHERE id_commande = ? AND id_produit = ?
        ");
        return $stmt->execute([$nouvelleQuantite, $idCommande, $idProduit]);
    }
}
?>