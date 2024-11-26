<?php
header("Content-Type: application/json");

$mongo = new MongoDB\Client("mongodb://localhost:27017");
$db = $mongo->zoo_arcadia;

// Récupérer le nom de l'animal à incrémenter
$data = json_decode(file_get_contents('php://input'), true);
$animal = $data['animal'] ?? null;

if ($animal) {
    // Incrémenter le compteur
    $result = $db->habitat_consultations->updateOne(
        ['_id' => $animal],
        ['$inc' => ['totalConsultations' => 1]],
        ['upsert' => true]
    );
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Animal non spécifié']);
}
