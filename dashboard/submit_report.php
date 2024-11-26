<?php
// Paramètres de connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "zoo_arcadia";

// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Vérifier si les données du formulaire sont envoyées
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $animal = $conn->real_escape_string($_POST['animal']);
    $date = $conn->real_escape_string($_POST['date']);
    $etat = $conn->real_escape_string($_POST['etat']);
    $nourriture = $conn->real_escape_string($_POST['nourriture']);
    $grammage = $conn->real_escape_string($_POST['grammage']);
    $avis = $conn->real_escape_string($_POST['avis']);

    // Requête SQL pour insérer les données dans la table
    $sql = "INSERT INTO veterinary_reports (animal, date, etat, nourriture, grammage, avis)
            VALUES ('$animal', '$date', '$etat', '$nourriture', $grammage, '$avis')";

    if ($conn->query($sql) === TRUE) {
        echo "Compte rendu enregistré avec succès.";
    } else {
        echo "Erreur : " . $sql . "<br>" . $conn->error;
    }
}

// Fermer la connexion
$conn->close();

