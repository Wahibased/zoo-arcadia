<?php
// Définir les en-têtes CORS pour permettre les requêtes cross-origin
header("Access-Control-Allow-Origin: *"); // Permet à toutes les origines d'accéder à l'API
header("Access-Control-Allow-Headers: Content-Type"); // Permet l'en-tête Content-Type
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE"); // Ajoute GET, PUT, DELETE

// Connexion à la base de données

$host = 'localhost'; 
$dbname = 'zoo_arcadia'; 
$username = 'root'; 
$password = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer les données JSON envoyées
    $data = json_decode(file_get_contents("php://input"), true);

    // Vérifier si tous les champs sont présents
    if (isset($data['utilisateur_nom'], $data['animal'], $data['etat'], $data['nourriture'], $data['grammage'], $data['avis'], $data['date'])) {

        // Préparer la requête SQL pour insérer les données dans la table
        $stmt = $pdo->prepare("INSERT INTO veterinary_reports (utilisateur_nom, animal, etat, nourriture, grammage, avis, date) 
                               VALUES (:utilisateur_nom, :animal, :etat, :nourriture, :grammage, :avis, :date)");

       
        $stmt->bindParam(':animal', $data['animal']);
        $stmt->bindParam(':etat', $data['etat']);
        $stmt->bindParam(':nourriture', $data['nourriture']);
        $stmt->bindParam(':grammage', $data['grammage']);
        $stmt->bindParam(':avis', $data['avis']);
        $stmt->bindParam(':date', $data['date']);

        // Exécuter la requête
        if ($stmt->execute()) {
            echo json_encode(['message' => 'Compte rendu enregistré avec succès!']);
        } else {
            echo json_encode(['message' => 'Erreur lors de l\'enregistrement du compte rendu.']);
        }
    } else {
        echo json_encode(['message' => 'Données manquantes.']);
    }

} catch (PDOException $e) {
    echo json_encode(['message' => 'Erreur de connexion à la base de données: ' . $e->getMessage()]);
}
?>



