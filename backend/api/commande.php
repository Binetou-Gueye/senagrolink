<?php
header("Content-Type: application/json");
require_once __DIR__.'/../models/Commande.php';
require_once __DIR__.'/../models/DetailsCommande.php';
require_once __DIR__.'/../models/Utilisateur.php';

// Configuration CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

require_once __DIR__.'/../models/Commande.php';
require_once __DIR__.'/../models/DetailsCommande.php';
require_once __DIR__.'/../models/Produit.php';

// Initialisation PDO
$pdo = new PDO("mysql:host=localhost;dbname=senagrolink", "root", "");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    try {
        if (!$data['id_utilisateur']) {
            throw new Exception("L'Utilisateur n'est pas inscrit dans la plateforme", 404);
        }

        if (!$data['id_boutique']) {
            throw new Exception("Aucune boutique trouvée pour cet agriculteur", 404);
        }
        
        // Créer le commande
        
        $commande = new Commande($pdo);
        $idCommande = $commande->creerCommande(
            $data['id_utilisateur'],
            $data['statut'],
            $data['adresse_livraison'],
            $data['id_boutique'],
        );

        //Ajout des profuit
        $detailsCommande = new DetailsCommande($pdo);
        foreach ($data['produits'] as $produit) {
            $success = $detailsCommande->ajouterProduit(
                $idCommande,
                $produit['id_produit'],
                $produit['quantite'],
                $produit['prix_unitaire']
            );

        }
        
        echo json_encode(['success' => $success]);
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Vérifier si c'est un boutique ou pas
        
        $commande = new Commande($pdo);
        if (!empty($_GET['id_boutique'])) {
            $success = $commande->getCommandesParBoutique($_GET['id_boutique']);
            $commandesFormatees = formatCommandes($success);
            echo json_encode($commandesFormatees);
        }elseif (!empty($_GET['acheteur'])) {
            $success = $commande->getCommandesParAcheteur($_GET['acheteur']);
            $commandesFormatees = formatCommandes($success);
            echo json_encode($commandesFormatees);
        }elseif (!empty($_GET['status'])) {
            $status = $_GET['status'];
            $idCommande = $_GET['id_commande'];
            if ($status=='Validé') {
                $success = $commande->getCommandesForChangeStatus($idCommande);
                if ($status=='Validé') {
                $commande = new Commande($pdo);
                $produit = new Produit($pdo);

                $details = $commande->getCommandeAvecDetailsOnly($idCommande);
                
                foreach ($details as $detail) {
                    $idProduit = $detail['id_produit'];
                    $quantite = $detail['quantite'];
                    error_log("Avant");
                //     // Appel de la méthode qui décrémente le stock
                    $successProduit = $produit->updateQuantity($idProduit, $quantite);
                    error_log("ok");
                }
                    $success = $commande->changeStatus($status,$idCommande);
                }
            }

            $success = $commande->changeStatus($status,$idCommande);
            echo json_encode($success);
        }
        
    } catch (Exception $e) {
        http_response_code($e->getCode() ?: 500);
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit();
}

?>