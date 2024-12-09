<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Connexion à la base de données
include '../config/db.php'; 

// Récupérer les filtres depuis la requête GET
$animal = isset($_GET['animal']) ? $_GET['animal'] : '';
$date = isset($_GET['date']) ? $_GET['date'] : '';

// Préparer la requête SQL
$sql = "SELECT * FROM veterinary_reports WHERE 1=1";
$params = [];

if (!empty($animal)) {
    $sql .= " AND animal = :animal";
    $params[':animal'] = $animal;
}

if (!empty($date)) {
    $sql .= " AND date = :date";
    $params[':date'] = $date;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Retourner la réponse JSON
header('Content-Type: application/json');
echo json_encode($reports);
