<?php
class Commande {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function creerCommande($idUtilisateur, $statut, $adresseLivraison, $id_boutique) {
        $stmt = $this->pdo->prepare("
            INSERT INTO commande 
            (id_utilisateur, statut, adresse_livraison, id_boutique) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$idUtilisateur, $statut, $adresseLivraison,$id_boutique]);
        return $this->pdo->lastInsertId();
    }

    public function getCommandeAvecDetails($idCommande) {
        // Récupère la commande de base
        $stmt = $this->pdo->prepare("
            SELECT * FROM commande 
            WHERE id_commande = ?
            LIMIT 1
        ");
        $stmt->execute([$idCommande]);
        $commande = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$commande) {
            return null;
        }
        
        // Récupère les détails avec calcul du montant
        $stmtDetails = $this->pdo->prepare("
            SELECT d.*, p.nom as produit_nom
            FROM details_commande d
            JOIN produit p ON d.id_produit = p.id_produit
            WHERE d.id_commande = ?
        ");
        $stmtDetails->execute([$idCommande]);
        $details = $stmtDetails->fetchAll(PDO::FETCH_ASSOC);
        
        // Calcule le montant total
        $montantTotal = 0;
        foreach ($details as $detail) {
            $montantTotal += $detail['quantite'] * $detail['prix_unitaire'];
        }
        
        return [
            'commande' => $commande,
            'details' => $details,
            'montant_total' => $montantTotal
        ];
    }

    public function getCommandesUtilisateur($idUtilisateur) {
        $stmt = $this->pdo->prepare("
            SELECT c.* FROM commande c
            WHERE c.id_utilisateur = ?
            ORDER BY c.date_commande DESC
        ");
        $stmt->execute([$idUtilisateur]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCommandesByBoutique($idBoutique) {
        global $pdo;
        
        $stmt = $pdo->prepare("
            SELECT 
                c.id_commande,
                c.date_commande,
                c.statut,
                u.nom AS nom_utilisateur,
                b.nom AS nom_boutique,
                d.id_produit,
                p.nom AS nom_produit,
                d.quantite,
                d.prix_unitaire
            FROM 
                commande c
            JOIN 
                utilisateur u ON c.id_utilisateur = u.id_utilisateur
            JOIN 
                details_commande d ON c.id_commande = d.id_commande
            JOIN 
                produit p ON d.id_produit = p.id_produit
            JOIN 
                boutique b ON b.id_boutique = c.id_boutique
            WHERE 
                c.id_boutique = ?
            ORDER BY 
                c.date_commande DESC
        ");
        
        $stmt->execute([$idBoutique]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getCommandesParAcheteur($id_acheteur) {
        global $pdo;
        
        $stmt = $pdo->prepare("
            SELECT 
                c.id_commande,
                c.date_commande,
                c.statut,
                u.nom AS nom_utilisateur,
                b.nom AS nom_boutique,
                d.id_produit,
                p.nom AS nom_produit,
                d.quantite,
                d.prix_unitaire
            FROM 
                commande c
            JOIN 
                utilisateur u ON c.id_utilisateur = u.id_utilisateur
            JOIN 
                details_commande d ON c.id_commande = d.id_commande
            JOIN 
                produit p ON d.id_produit = p.id_produit
            JOIN 
                boutique b ON b.id_boutique = c.id_boutique
            WHERE 
                c.id_utilisateur = ?
            ORDER BY 
                c.date_commande DESC
        ");
        
        $stmt->execute([$id_acheteur]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCommandesForChangeStatus($id_commande) {
        global $pdo;
        
        $stmt = $pdo->prepare("
            SELECT 
                c.id_commande,
                d.id_produit,
                d.quantite
            FROM 
                commande c
            JOIN 
                details_commande d ON c.id_commande = d.id_commande
            JOIN 
                produit p ON d.id_produit = p.id_produit
            WHERE 
                c.id_commande = ?
            ORDER BY 
                c.date_commande DESC
        ");
        
        $stmt->execute([$id_commande]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function changeStatus($statut, $id_commande) {
        $stmt = $this->pdo->prepare("
             UPDATE commande SET statut = ? WHERE id_commande = ?;
        ");
        $stmt->execute([$statut, $id_commande]);
        return $this->pdo->lastInsertId();
    }
}

function formatCommandes($commandesBrutes) {
    $commandesFormatees = [];
    
    foreach ($commandesBrutes as $ligne) {
        $idCommande = $ligne['id_commande'];
        
        // Si la commande n'existe pas encore dans le tableau final
        if (!isset($commandesFormatees[$idCommande])) {
            $commandesFormatees[$idCommande] = [
                'id_commande' => $idCommande,
                'date_commande' => $ligne['date_commande'],
                'statut' => $ligne['statut'],
                'nom_utilisateur' => $ligne['nom_utilisateur'],
                'nom_boutique' => $ligne['nom_boutique'],
                'produits' => []
            ];
        }
        
        // Ajouter le produit à la commande
        $commandesFormatees[$idCommande]['produits'][] = [
            'id_produit' => $ligne['id_produit'],
            'nom_produit' => $ligne['nom_produit'],
            'quantite' => $ligne['quantite'],
            'prix_unitaire' => $ligne['prix_unitaire']
        ];
    }
    
    // Réindexer le tableau et calculer le montant total pour chaque commande
    $resultat = [];
    foreach ($commandesFormatees as $commande) {
        $montantTotal = 0;
        foreach ($commande['produits'] as $produit) {
            $montantTotal += $produit['quantite'] * $produit['prix_unitaire'];
        }
        
        $commande['montant_total'] = $montantTotal;
        $resultat[] = $commande;
    }
    
    return array_values($resultat);
}

?>