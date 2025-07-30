<?php
class Panier {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function creerPanier(): int {
        $stmt = $this->pdo->prepare("INSERT INTO panier () VALUES ()");
        $stmt->execute();
        return $this->pdo->lastInsertId();
    }

    public function supprimerPanier(int $idPanier): bool {
        $this->pdo->beginTransaction();
        try {
            // Supprime d'abord les détails (grâce au ON DELETE CASCADE)
            $stmt = $this->pdo->prepare("DELETE FROM detail_commande WHERE id_panier = ?");
            $stmt->execute([$idPanier]);
            
            // Puis supprime le panier
            $stmt = $this->pdo->prepare("DELETE FROM panier WHERE id_panier = ?");
            $stmt->execute([$idPanier]);
            
            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function getContenuPanier(int $idPanier): array {
        $stmt = $this->pdo->prepare("
            SELECT dc.*, p.nom as produit_nom, p.image as produit_image
            FROM detail_commande dc
            JOIN produit p ON dc.produit_id = p.id
            WHERE dc.id_panier = ?
        ");
        $stmt->execute([$idPanier]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>