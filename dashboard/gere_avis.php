<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root"; 
$password = "";     
$dbname = "zoo_arcadia"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

// Approuver un avis
if (isset($_GET['approve'])) {
    $id = intval($_GET['approve']); // Toujours valider les données utilisateur
    $conn->query("UPDATE avis_visiteurs SET statut = 'approuve' WHERE id = $id");
}

// Mettre un avis en attente
if (isset($_GET['pending'])) {
    $id = intval($_GET['pending']); // Toujours valider les données utilisateur
    $conn->query("UPDATE avis_visiteurs SET statut = 'en attente' WHERE id = $id");
}

// Supprimer un avis
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']); // Toujours valider les données utilisateur
    $conn->query("DELETE FROM avis_visiteurs WHERE id = $id");
}

// Récupérer tous les avis
$avis = $conn->query("SELECT * FROM avis_visiteurs ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des avis - Admin</title>
    <link rel="stylesheet" href="style.css"> <!-- Lien vers votre fichier CSS -->
</head>
<body>
    <h1>Gestion des Avis</h1>
    
    <table border="1">
        <tr>
            <th>Pseudo</th>
            <th>Avis</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $avis->fetch_assoc()) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row['pseudo']); ?></td>
            <td><?php echo htmlspecialchars($row['avis']); ?></td>
            <td><?php echo htmlspecialchars($row['statut']); ?></td>
            <td>
                <?php if ($row['statut'] == 'en attente') { ?>
                    <a href="?approve=<?php echo $row['id']; ?>">Approuver</a>
                <?php } else { ?>
                    <a href="?pending=<?php echo $row['id']; ?>">Mettre en attente</a>
                <?php } ?>
                <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet avis ?');">Supprimer</a>
            </td>
        </tr>
        <?php } ?>
    </table>

</body>
</html>

<?php
$conn->close();
?>

