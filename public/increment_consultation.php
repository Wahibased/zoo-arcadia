<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$pdo = new PDO("mysql:host=localhost;dbname=zoo_arcadia", "root", "");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['animalId'])) {
    $animalId = (int) $_POST['animalId'];
    var_dump($animalId); // Debugging: Vérifiez si l'ID de l'animal est correct

    $stmt = $pdo->prepare("UPDATE animaux SET consultation_count = consultation_count + 1 WHERE id = ?");
    if ($stmt->execute([$animalId])) {
        $stmt = $pdo->prepare("SELECT consultation_count FROM animaux WHERE id = ?");
        $stmt->execute([$animalId]);
        $newCount = $stmt->fetchColumn();

        var_dump($newCount); // Debugging: Vérifiez la nouvelle valeur du compteur

        echo json_encode(['success' => true, 'newCount' => $newCount]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to update consultation count']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}


