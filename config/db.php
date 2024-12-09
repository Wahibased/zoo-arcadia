<?php
// Configuration de la base de données
$host = 'localhost';      
$dbname = 'zoo_arcadia'; 
$username = 'root';  
$password = ''; 

try {
    // Créer une instance PDO pour la connexion à la base de données
     // Créer une connexion PDO
     $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ];
    
    // Suppression du message de connexion réussie
} catch (PDOException $e) {
    // Log de l'erreur au lieu de l'afficher directement
    error_log("Erreur de connexion à la base de données : " . $e->getMessage());
    // Vous pouvez également définir une variable de session pour l'erreur si nécessaire
    // $_SESSION['db_error'] = "Impossible de se connecter à la base de données.";
    die("Une erreur est survenue lors de la connexion à la base de données.");
}






