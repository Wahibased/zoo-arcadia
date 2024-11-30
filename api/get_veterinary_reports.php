<?php
header("Access-Control-Allow-Origin: *");

// Connexion à la base de données
$host = "localhost";
$dbname = "zoo_arcadia";
$username = "root";
$password = "";

try {
    // Vous n'avez pas besoin de redéfinir la connexion deux fois
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupérer les noms des animaux et leurs avis
$query = "SELECT animal, avis FROM veterinary_reports";
$stmt = $pdo->prepare($query);
$stmt->execute();
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Retourner les données en JSON
header('Content-Type: application/json');
echo json_encode($reports);


