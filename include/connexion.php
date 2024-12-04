<?php

function connexion() {
    try {
        // Créer une instance de PDO pour se connecter à la base de données
        $pdo = new PDO('mysql:host=localhost;dbname=cms;charset=utf8mb4', 'root', '');
        
        // Configurer l'attribut pour afficher les erreurs
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Retourner l'objet PDO pour l'utiliser dans d'autres parties du code
        return $pdo;
    } catch (PDOException $e) {
        // En cas d'erreur, arrêter l'exécution du script et afficher l'erreur
        die("Erreur de connexion : " . $e->getMessage());
    }
}


