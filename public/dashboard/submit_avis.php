
<?php
// Configuration de la base de données
$servername = "localhost";
$username = "root"; // Remplacez par votre nom d'utilisateur
$password = "";     // Remplacez par votre mot de passe
$dbname = "zoo_arcadia"; // Nom de votre base de données

// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Erreur de connexion à la base de données : " . $conn->connect_error);
}

// Vérifier si les données ont été soumises
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Récupérer et valider les entrées
    $pseudo = trim($_POST["pseudo"]);
    $avis = trim($_POST["avis"]);

    // Vérifier que les champs ne sont pas vides
    if (!empty($pseudo) && !empty($avis)) {
        // Échapper les caractères spéciaux pour éviter les injections SQL
        $pseudo = $conn->real_escape_string($pseudo);
        $avis = $conn->real_escape_string($avis);

        // Insérer les données dans la base avec le statut "en attente"
        $sql = "INSERT INTO avis_visiteurs (pseudo, avis, statut) VALUES ('$pseudo', '$avis', 'en attente')";

        if ($conn->query($sql) === TRUE) {
            // Rediriger l'utilisateur avec un message de succès
            header("Location: ../avis_success.php?status=success");
            exit();
        } else {
            // Message d'erreur en cas d'échec
            echo "Erreur lors de l'enregistrement de l'avis : " . $conn->error;
        }
    } else {
        echo "Veuillez remplir tous les champs.";
    }
}

// Fermer la connexion
$conn->close();

