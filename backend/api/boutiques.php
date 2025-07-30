<?php
require_once __DIR__.'/../models/Boutique.php';
require_once __DIR__.'/../models/Produit.php';
header("Content-Type: application/json");

$pdo = new PDO("mysql:host=localhost;dbname=senagrolink", "root", "");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {

        // Récupérer le paramètre 'name' de l'URL
        $nomBoutique = isset($_GET['name']) ? urldecode($_GET['name']) : null;
        $boutiqueModel = new Boutique($pdo);
        $boutique = $boutiqueModel->getBoutiques();

        if ($nomBoutique) {
            // Récupérer produit par boutique
            $produitModel = new Produit($pdo);
            $produits = $produitModel->getProduitsByBoutique($_GET['name']);
            echo json_encode($produits);
        }
        
        
        
        echo json_encode($boutique);
        
    } catch (Exception $e) {
        http_response_code($e->getCode() ?: 500);
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit();
}
?>