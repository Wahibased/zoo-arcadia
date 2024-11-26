

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $utilisateur_id = $_POST['utilisateur'];
    // Vous pouvez récupérer les autres champs de la même manière
    
    // Connexion à la base de données
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "db_zoo";
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Vérification de la connexion
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Préparation de la requête SQL pour insérer les données dans la table compte_rendu_veterinaire
    $sql = "INSERT INTO compte_rendu_veterinaire (utilisateur_id, ...) VALUES ('$utilisateur_id', ...)";
    // Remplacez les points de suspension (...) par les autres champs à insérer
    
    // Exécution de la requête
    if ($conn->query($sql) === TRUE) {
        echo "Le compte rendu vétérinaire a été soumis avec succès.";
    } else {
        echo "Erreur lors de la soumission du compte rendu vétérinaire: " . $conn->error;
    }
    
    // Fermeture de la connexion à la base de données
    $conn->close();
}
?>