<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Access-Control-Allow-Credentials: true');
header("Content-Type: application/json");

require_once __DIR__.'/../models/Utilisateur.php';
require_once __DIR__.'/../models/Agriculteur.php';
require_once __DIR__.'/../models/Acheteur.php';
require_once __DIR__.'/../models/Administrateur.php';
require_once __DIR__.'/../models/Boutique.php';

// Connexion DB
$pdo = new PDO("mysql:host=localhost;dbname=senagrolink", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    try {
        // Validation
        if (empty($data['email']) || empty($data['mot_de_passe'])) {
            throw new Exception("Email et mot de passe requis", 400);
        }

        // 1. Vérification de l'utilisateur
        $utilisateur = new Utilisateur($pdo);
        $user = $utilisateur->verifierUtilisateur($data['email'], $data['mot_de_passe']);
        
        if (!$user) {
            throw new Exception("Email ou mot de passe incorrect", 401);
        }

        // 2. Récupération du type d'utilisateur
        $userType = getUserType($pdo, $user['id_utilisateur']);

        if ($userType == 'agriculteur') {
            // 2. Récupération de la boutique de l'agriculteur

            if ($userType && $userType == 'agriculteur') {
                $market = new Boutique($pdo);
                $bourique = $market->getBoutiqueByAgriculteur($user['id_utilisateur']);   
            }
            
            // 3. Génération du token (simplifié)
            $token = bin2hex(random_bytes(32));
            $expires = time() + 3600; // 1 heure
            
            // Réponse avec informations utilisateur
            echo json_encode([
                'success' => true,
                'token' => $token,
                'expires' => $expires,
                'user' => [
                    'id' => $user['id_utilisateur'],
                    'nom' => $user['nom'],
                    'email' => $user['email'],
                    'localisation' => $user['localisation'],
                    'telephone' => $user['telephone'],
                    'boutique' => $bourique,
                    'type' => $userType
                ]
            ]);
        }elseif ($userType == 'acheteur') {
        // 2. Récupération le type d'acheteur
            $acheteur = new Acheteur($pdo);
            $type_acheteur = $acheteur->getTypeAcheteur($user['id_utilisateur']);

        // 3. Génération du token (simplifié)
            $token = bin2hex(random_bytes(32));
            $expires = time() + 3600; // 1 heure
            
        // Réponse avec informations utilisateur
            echo json_encode([
                'success' => true,
                'token' => $token,
                'expires' => $expires,
                'user' => [
                    'id' => $user['id_utilisateur'],
                    'nom' => $user['nom'],
                    'email' => $user['email'],
                    'localisation' => $user['localisation'],
                    'telephone' => $user['telephone'],
                    'type' => $userType,
                    'type_acheteur' => $type_acheteur['type_acheteur']
                ]
            ]);
        }

        
        
    } catch (Exception $e) {
        http_response_code($e->getCode() >= 400 ? $e->getCode() : 500);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Méthode non autorisée']);
}

// Fonction pour déterminer le type d'utilisateur
function getUserType($pdo, $userId) {
    // Vérifie dans l'ordre: admin > acheteur > agriculteur
    $admin = new Administrateur($pdo);
    if ($admin->getAdminByUserId($userId)) return 'administrateur';
    
    $acheteur = new Acheteur($pdo);
    if ($acheteur->getAcheteurByUserId($userId)) return 'acheteur';
    
    $agriculteur = new Agriculteur($pdo);
    if ($agriculteur->getAgriculteurByUserId($userId)) return 'agriculteur';
    
    return 'utilisateur';
}
?>