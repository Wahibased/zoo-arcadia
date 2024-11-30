<?php

// Informations de connexion JawsDB MySQL
$databaseUrl = "mysql://smcjf3rv6c0r3x52:g1pwk9sdxcz55ki2@xefi550t7t6tjn36.cbetxkdyhwsb.us-east-1.rds.amazonaws.com:3306/lk6admexwuwqufr8";

// Analyse de l'URL de la base de données
$parsedUrl = parse_url($databaseUrl);

// Récupération des informations de connexion
$host = $parsedUrl['host'];
$port = $parsedUrl['port'];
$user = $parsedUrl['user'];
$password = $parsedUrl['pass'];
$dbname = ltrim($parsedUrl['path'], '/');

// Création de la chaîne de connexion PDO
$dsn = "mysql:host=$host;port=$port;dbname=$dbname";
try {
    // Connexion à la base de données avec PDO
    $pdo = new PDO($dsn, $user, $password);
    // Définir le mode d'erreur PDO sur Exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connexion réussie à la base de données!";
} catch (PDOException $e) {
    // Si la connexion échoue, afficher l'erreur
    echo "Erreur de connexion : " . $e->getMessage();
}
?>

/*
// Configuration de la base de données
$host = 'localhost';      
$dbname = 'zoo_arcadia'; 
$username = 'root';  
$password = ''; 

try {
    // Connexion à la base de données en utilisant les variables
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
    // Suppression du message de connexion réussie
} catch (PDOException $e) {
    // Log de l'erreur au lieu de l'afficher directement
    error_log("Erreur de connexion à la base de données : " . $e->getMessage());
    // Vous pouvez également définir une variable de session pour l'erreur si nécessaire
    // $_SESSION['db_error'] = "Impossible de se connecter à la base de données.";
    die("Une erreur est survenue lors de la connexion à la base de données.");
}

*/




