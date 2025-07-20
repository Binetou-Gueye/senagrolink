<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require_once __DIR__.'/../models/Produit.php';
require_once __DIR__.'/../models/Boutique.php';

$pdo = new PDO("mysql:host=localhost;dbname=senagrolink", "root", "");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    try {
        // // Vérifier que l'utilisateur a une boutique
        // $boutiqueModel = new Boutique($pdo);
        // $boutique = $boutiqueModel->getBoutiqueByAgriculteur($data['id_agriculteur']);
        
        if (!$data['id_boutique']) {
            throw new Exception("Aucune boutique trouvée pour cet agriculteur", 404);
        }
        
        // Créer le produit
        $produit = new Produit($pdo);
        $success = $produit->creerProduit(
            $data['id_boutique'],
            $data['nom'],
            $data['description'],
            $data['prix_unitaire'],
            $data['quantite_stock'],
            $data["certification"],
            $data["categorie"],
            $data["unite_vente"],
        );      
        
        echo json_encode(['success' => $success]);
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Vérifier l'agriculteur
        if (empty($_GET['agriculteur'])) {
            throw new Exception("Paramètre agriculteur manquant", 400);
        }

        // Récupérer la boutique
        $boutiqueModel = new Boutique($pdo);
        $boutique = $boutiqueModel->getBoutiqueByAgriculteur($_GET['agriculteur']);
        
        if (!$boutique) {
            throw new Exception("Boutique non trouvée", 404);
        }

        // Récupérer les produits
        $produitModel = new Produit($pdo);
        $produits = $produitModel->getProduitsByBoutique($boutique['id_boutique']);
        
        echo json_encode($produits);
        
    } catch (Exception $e) {
        http_response_code($e->getCode() ?: 500);
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit();
}
?>