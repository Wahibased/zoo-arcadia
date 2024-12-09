<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Zoo_arcadia";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Définir le charset pour gérer les caractères spéciaux
$conn->set_charset("utf8");

// Récupérer les paramètres GET
$animal_filter = isset($_GET['animal']) ? $conn->real_escape_string($_GET['animal']) : '';
$date_filter = isset($_GET['date']) ? $conn->real_escape_string($_GET['date']) : '';

// Construire la requête SQL
$query = "SELECT * FROM veterinary_reports WHERE 1=1"; // Requête de base
if (!empty($animal_filter)) {
    $query .= " AND animal = '$animal_filter'";
}
if (!empty($date_filter)) {
    $query .= " AND date = '$date_filter'";
}

// Exécuter la requête
$result = $conn->query($query);

// Générer les lignes HTML pour le tableau
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['utilisateur_nom']) . "</td>";
        echo "<td>" . htmlspecialchars($row['animal']) . "</td>";
        echo "<td>" . htmlspecialchars($row['etat']) . "</td>";
        echo "<td>" . htmlspecialchars($row['nourriture']) . "</td>";
        echo "<td>" . htmlspecialchars($row['grammage']) . "</td>";
        echo "<td>" . htmlspecialchars($row['avis']) . "</td>";
        echo "<td>" . htmlspecialchars($row['date']) . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='8'>Aucun compte rendu trouvé pour ces critères.</td></tr>";
}

$conn->close();




