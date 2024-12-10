<?php

// Définir la connexion MySQL à partir de JAWSDB_URL
$url = parse_url("mysql://p7jr3mcbsyvbcllf:ycrgx4akgsl5u0z6@alv4v3hlsipxnujn.cbetxkdyhwsb.us-east-1.rds.amazonaws.com:3306/jch6tgw1mw5gns41");

$host = $url["host"];         
$username = $url["user"];    
$password = $url["pass"];     
$dbname = substr($url["path"], 1); // nom de la base de données
$port = $url["port"];       

// Connexion à MySQL avec PDO
try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connexion réussie à la base MySQL JawsDB!";
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}







