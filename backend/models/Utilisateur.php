<?php
class Utilisateur {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function creerUtilisateur($nom, $email, $mot_de_passe, $localisation, $telephone) {
        $stmt = $this->pdo->prepare("
            INSERT INTO utilisateur 
            (nom, email, mot_de_passe, localisation, telephone) 
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $nom, 
            $email,
            password_hash($mot_de_passe, PASSWORD_DEFAULT),
            $localisation,
            $telephone
        ]);
    }

    public function verifierUtilisateur($email, $mot_de_passe) {
    $email = trim($email);
    $mot_de_passe = trim($mot_de_passe); // Nettoyage important
    
    try {
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateur WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
            unset($user['mot_de_passe']); // Retire le mot de passe avant de retourner
            return $user;
        }
        error_log(password_verify($mot_de_passe, $user['mot_de_passe']));
        return false;

    } catch (PDOException $e) {
        error_log("Erreur PDO: ".$e->getMessage());
        return false;
    }

    public function lastUtilisateur($id) {
        $stmt = $this->pdo->prepare("
            SELECT * 
            FROM utilisateur 
            WHERE id_utilisateur = ?
        ");
        return $stmt->execute([$id]);
    }

}
}
?>