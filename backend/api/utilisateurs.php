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
}
?>