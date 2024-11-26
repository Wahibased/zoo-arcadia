<?php
// Connexion à la base de données
$host = 'localhost'; 
$dbname = 'zoo_arcadia'; 
$username = 'root'; 
$password = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer les filtres depuis la requête GET
    $animal_filter = isset($_GET['animal']) ? $_GET['animal'] : '';
    $date_filter = isset($_GET['date']) ? $_GET['date'] : '';

    // Construire la requête SQL
    $query = "SELECT * FROM veterinary_reports WHERE 1=1";
    if ($animal_filter) {
        $query .= " AND animal = :animal";
    }
    if ($date_filter) {
        $query .= " AND date = :date";
    }

    // Préparer et exécuter la requête SQL
    $stmt = $pdo->prepare($query);

    if ($animal_filter) {
        $stmt->bindParam(':animal', $animal_filter);
    }
    if ($date_filter) {
        $stmt->bindParam(':date', $date_filter);
    }

    $stmt->execute();
    $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retourner les rapports sous forme de JSON
    echo json_encode($reports);
} catch (PDOException $e) {
    echo json_encode(['message' => 'Erreur de connexion à la base de données: ' . $e->getMessage()]);
}

