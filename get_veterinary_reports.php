<?php

// Connexion à la base de données
require_once '../config/db.php'; 

header('Content-Type: application/json');
// Vérifiez si la connexion est établie avant de procéder
if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Erreur de connexion à la base de données']);
    exit;
}

$sql = "SELECT animal, avis FROM veterinary_reports ORDER BY date DESC";
$result = $conn->query($sql);

$reports = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reports[] = $row;
    }
}

echo json_encode(['success' => true, 'data' => $reports]);
$conn->close();



