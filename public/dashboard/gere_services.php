<?php

session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Vérifier si le fichier db.php existe
if (!file_exists(__DIR__ . '/../config/db.php')) {
    die('Fichier db.php introuvable');
}
require __DIR__ . '/../config/db.php';

try {
    $pdo = new PDO('mysql:host=localhost;dbname=zoo_arcadia', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erreur de connexion à la base de données : ' . $e->getMessage());
}

// Vérification du rôle utilisateur
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'employe')) {
    header('Location: ../login.php');
    exit;
}

$message = '';

// Gestion de l'ajout, modification, et suppression des services
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'add' || $action === 'edit') {
        $type = $_POST['type'];
        $titre = $_POST['title'];
        $description = $_POST['description'];
        
        // Gestion du téléchargement de l'image
        $image = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
            $filename = $_FILES["image"]["name"];
            $filetype = $_FILES["image"]["type"];
            $filesize = $_FILES["image"]["size"];

            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if (!array_key_exists($ext, $allowed)) die("Erreur : Veuillez sélectionner un format de fichier valide.");

            $maxsize = 5 * 1024 * 1024;
            if ($filesize > $maxsize) die("Erreur: La taille du fichier est supérieure à la limite autorisée.");

            if (in_array($filetype, $allowed)) {
                $image = "uploads/" . $filename;
                move_uploaded_file($_FILES["image"]["tmp_name"], $image);
            } else {
                $message = "Erreur: Il y a eu un problème de téléchargement de votre fichier. Veuillez réessayer."; 
            }
        }

        if ($action === 'add') {
            try {
                $sql = "INSERT INTO services (type, titre, description, image, cree_le, mis_a_jour_le) 
                        VALUES (?, ?, ?, ?, NOW(), NOW())";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$type, $titre, $description, $image]);
                $message = "Nouveau service ajouté avec succès";
            } catch (PDOException $e) {
                $message = "Erreur lors de l'ajout du service : " . $e->getMessage();
            }
        } elseif ($action === 'edit' && isset($_POST['id'])) {
            $id = $_POST['id'];
            try {
                // Si une nouvelle image est téléchargée, on la remplace, sinon on garde l'ancienne image
                $sql = "UPDATE services SET type = ?, titre = ?, description = ?, mis_a_jour_le = NOW(), image = ? WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$type, $titre, $description, $image ? $image : $_POST['current_image'], $id]);
                $message = "Service mis à jour avec succès";
            } catch (PDOException $e) {
                $message = "Erreur lors de la mise à jour du service : " . $e->getMessage();
            }
        }
    } elseif ($action === 'delete' && isset($_POST['id'])) {
        $id = $_POST['id'];
        try {
            $sql = "DELETE FROM services WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            $message = "Service supprimé avec succès";
        } catch (PDOException $e) {
            $message = "Erreur lors de la suppression du service : " . $e->getMessage();
        }
    }
}

// Récupération de tous les services
$sql = "SELECT * FROM services";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Services</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Gestion des Services</h1>
    </header>

    <main>
        <section id="form">
            <h2>Ajouter ou Modifier un Service</h2>
            <?php if (!empty($message)): ?>
                <p><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>

            <form method="post" action="" enctype="multipart/form-data">
                <input type="hidden" name="action" value="add">
                <label for="type">Type:</label>
                <select name="type" id="type" required>
                    <option value="menu">Menu</option>
                    <option value="guide">Guide</option>
                    <option value="train">Train</option>
                </select>
                <br>
                <label for="title">Titre:</label>
                <input type="text" name="title" id="title" required>
                <br>
                <label for="description">Description:</label>
                <textarea name="description" id="description" required></textarea>
                <br>
                <label for="image">Image:</label>
                <input type="file" name="image" id="image" accept="image/*">
                <br>
                <button type="submit">Ajouter le Service</button>
            </form>
        </section>

        <section id="services-list">
            <h2>Liste des Services</h2>
            <table>
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Titre</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($services as $service): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($service['type']); ?></td>
                            <td><?php echo htmlspecialchars($service['titre']); ?></td>
                            <td><?php echo htmlspecialchars($service['description']); ?></td>
                            <td><img src="<?php echo htmlspecialchars($service['image']); ?>" alt="<?php echo htmlspecialchars($service['titre']); ?>" width="50"></td>
                            <td>
                                <!-- Formulaire de modification -->
                                <form method="post" action="" enctype="multipart/form-data">
                                    <input type="hidden" name="action" value="edit">
                                    <input type="hidden" name="id" value="<?php echo $service['id']; ?>">
                                    <input type="hidden" name="current_image" value="<?php echo $service['image']; ?>"> <!-- Image actuelle -->
                                    <input type="text" name="type" value="<?php echo $service['type']; ?>">
                                    <input type="text" name="title" value="<?php echo $service['titre']; ?>">
                                    <textarea name="description"><?php echo $service['description']; ?></textarea>
                                    <input type="file" name="image" accept="image/*">
                                    <button type="submit">Modifier</button>
                                </form>

                                <!-- Formulaire de suppression -->
                                <form method="post" action="">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $service['id']; ?>">
                                    <button type="submit">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Zoo Arcadia. Tous droits réservés.</p>
    </footer>
</body>
</html>
