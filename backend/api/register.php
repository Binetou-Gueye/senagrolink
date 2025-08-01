<?php
header("Access-Control-Allow-Origin: *"); // Spécifiez l'origine exacte
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Access-Control-Allow-Credentials: true');
header("Content-Type: application/json");

require_once __DIR__.'/../models/Utilisateur.php';
require_once __DIR__.'/../models/Agriculteur.php';
require_once __DIR__.'/../models/Acheteur.php';
require_once __DIR__.'/../models/Panier.php';
require_once __DIR__.'/../models/Boutique.php';

// Connexion DB (à mettre dans un fichier séparé si nécessaire)
$pdo = new PDO("mysql:host=localhost;dbname=senagrolink", "root", "");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    try {
        // 1. Créer l'utilisateur de base
        $utilisateur = new Utilisateur($pdo);
        $success = $utilisateur->creerUtilisateur(
            $data['nom'],
            $data['email'],
            $data['mot_de_passe'],
            $data['localisation'],
            $data['telephone'] ?? null
        );
        
        if (!$success) {
            throw new Exception("Échec création utilisateur");
        }
        
        $userId = $pdo->lastInsertId();
        error_log($userId);
        
        // 2. Créer le profil agriculteur
        if ($data['type'] === 'agriculteur') {
            $agriculteur = new Agriculteur($pdo);
            $agriculteur->creerAgriculteur(
                $userId,
                $data['type_production']
            );
            // Création automatique de la boutique
            $boutique = new Boutique($pdo);
            $boutique->creerBoutique(
                $userId,
                'Boutique de '.$data['nom'], // id_agriculteur
                'user.png', // Avatar par défaut
                $data['localisation'] // Utilise la localisation comme emplacement initial
            );
        }
        if ($data['type'] === 'acheteur') {
            $acheteur = new Acheteur($pdo);
            $panier = new Panier($pdo);
            $id_panier = $panier->creerPanier();
            $acheteur->creerAcheteur(
                $userId,
                $data['type_acheteur'],
                $id_panier
            );
        }
        
        echo json_encode(   [
            'success' => true,
            'userId' => $userId
        ]);
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Méthode non autorisée']);
}
?>