<?php
// Définir les informations de connexion MySQL
$host = "alv4v3hlsipxnujn.cbetxkdyhwsb.us-east-1.rds.amazonaws.com";
$username = "p7jr3mcbsyvbcllf";
$password = "ycrgx4akgsl5u0z6";
$dbname = "jch6tgw1mw5gns41";
$port = 3306;

// Connexion à MySQL avec PDO
try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connexion réussie à la base MySQL JawsDB!";
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
