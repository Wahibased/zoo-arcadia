<?php
require_once '../config/db.php'; // Connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données soumises par le formulaire
    $animal = trim($_POST['animal']);
    $nom = trim($_POST['nom']);
    $race = trim($_POST['race']);
    $habitat_id = intval($_POST['habitat_id']);
    $consultation_count = intval($_POST['consultation_count']);
    $imagePath = trim($_POST['image_path']); // Chemin ou URL de l'image

    try {
        // Insérer les données dans la base
        $query = "INSERT INTO animaux (animal, nom, race, habitat_id, consultation_count, image_path) 
                  VALUES (:animal, :nom, :race, :habitat_id, :consultation_count, :image_path)";
        $stmt = $pdo->prepare($query);

        // Liaison des paramètres
        $stmt->bindParam(':animal', $animal, PDO::PARAM_STR);
        $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
        $stmt->bindParam(':race', $race, PDO::PARAM_STR);
        $stmt->bindParam(':habitat_id', $habitat_id, PDO::PARAM_INT);
        $stmt->bindParam(':consultation_count', $consultation_count, PDO::PARAM_INT);
        $stmt->bindParam(':image_path', $imagePath, PDO::PARAM_STR);

        // Exécuter la requête
        $stmt->execute();
        echo "Animal ajouté avec succès !";

    } catch (Exception $e) {
        die("Erreur lors de l'insertion : " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/dashboard/style.css">
    <title>Ajouter un Animal</title>
</head>
<body>
    <h1>Ajouter un Nouvel Animal</h1>
    <form method="POST" action="ajouter_animal.php">
        <label for="animal">Nom de l'Animal :</label>
        <input type="text" id="animal" name="animal" required><br>

        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" required><br>

        <label for="race">Race :</label>
        <input type="text" id="race" name="race" required><br>

        <label for="habitat_id">ID de l'Habitat :</label>
        <input type="number" id="habitat_id" name="habitat_id" required><br>

        <label for="consultation_count">Nombre de Consultations :</label>
        <input type="number" id="consultation_count" name="consultation_count"><br>

        <label for="image_path">URL de l'Image :</label>
        <input type="url" id="image_path" name="image_path" placeholder="https://exemple.com/mon-image.jpg"><br>

        <button type="submit">Ajouter</button>
    </form>
</body>
</html>

