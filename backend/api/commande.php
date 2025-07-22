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
        if ($_GET['id_boutique']) {
            $commande = new Commande($pdo);
            $success = $commande->getCommandesParBoutique($_GET['id_boutique']);
            $commandesFormatees = formatCommandes($success);
        }

        echo json_encode($commandesFormatees);
        
    } catch (Exception $e) {
        http_response_code($e->getCode() ?: 500);
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit();
}

?>