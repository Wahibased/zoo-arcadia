<?php
// Affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connexion à la base de données
try {
    $pdo = new PDO('mysql:host=localhost;dbname=zoo_arcadia', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erreur de connexion à la base de données : ' . $e->getMessage());
}

// Action à exécuter
$action = $_GET['action'] ?? $_POST['action'] ?? '';

if ($action === 'save') {
    try {
        $id = $_POST['id'] ?? null;
        $nom = $_POST['nom'] ?? '';
        $race = $_POST['race'] ?? '';
        $habitat_id = $_POST['habitat'] ?? '';
        $imagePath = null;

        // Gestion du téléchargement de l'image
        if (isset($_FILES['animalImage']) && $_FILES['animalImage']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = "dashboard/uploads/"; // Chemin du dossier d'upload
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true); // Crée le dossier s'il n'existe pas
            }

            // Générez un nom unique pour l'image
            $fileExtension = pathinfo($_FILES['animalImage']['name'], PATHINFO_EXTENSION);
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array(strtolower($fileExtension), $allowedExtensions)) {
                $imageName = uniqid('animal_', true) . '.' . $fileExtension;
                $targetPath = $uploadDir . $imageName; // Chemin complet du fichier

                if (move_uploaded_file($_FILES['animalImage']['tmp_name'], $targetPath)) {
                    $imagePath = $targetPath; // Enregistrer le chemin pour la base de données
                } else {
                    throw new Exception("Erreur lors du déplacement de l'image vers le dossier.");
                }
            } else {
                throw new Exception("Extension de fichier non autorisée.");
            }
        }

        if ($id) {
            // Mise à jour
            $stmt = $pdo->prepare('UPDATE animaux SET nom = ?, race = ?, habitat_id = ?, image_path = ? WHERE id = ?');
            $stmt->execute([$nom, $race, $habitat_id, $imagePath, $id]);
        } else {
            // Insertion
            $stmt = $pdo->prepare('INSERT INTO animaux (nom, race, habitat_id, image_path) VALUES (?, ?, ?, ?)');
            $stmt->execute([$nom, $race, $habitat_id, $imagePath]);
        }

        echo json_encode(['status' => 'success', 'message' => 'Animal enregistré avec succès']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Erreur : ' . $e->getMessage()]);
    }
    exit;
}

if ($action === 'load') {
    $stmt = $pdo->query('
        SELECT a.id, a.nom, a.race, h.nom AS habitat, a.image_path, a.consultation_count
        FROM animaux a
        JOIN habitats h ON a.habitat_id = h.id
    ');
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

if ($action === 'edit') {
    $id = $_GET['id'] ?? null;
    if ($id) {
        $stmt = $pdo->prepare('SELECT * FROM animaux WHERE id = ?');
        $stmt->execute([$id]);
        echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
    }
    exit;
}

if ($action === 'delete') {
    $id = $_GET['id'] ?? null;
    if ($id) {
        $stmt = $pdo->prepare('DELETE FROM animaux WHERE id = ?');
        $stmt->execute([$id]);
    }
    exit;
}

if ($action === 'consult') {
    $id = $_GET['id'] ?? null;
    if ($id) {
        $stmt = $pdo->prepare('UPDATE animaux SET consultation_count = consultation_count + 1 WHERE id = ?');
        $stmt->execute([$id]);
    }
    exit;
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Animaux</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Gestion des Animaux</h1>
        <form action="" id="animal-form" enctype="multipart/form-data">
            <input type="hidden" id="animal-id" name="id">
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" required>

            <label for="race">Race :</label>
            <input type="text" id="race" name="race" required>

            <label for="habitat">Habitat :</label>
            <select id="habitat" name="habitat" required>
                <option value="1">Marais</option>
                <option value="2">Savane</option>
                <option value="3">Jungle</option>
            </select>

            <label for="animalImage">Image :</label>
            <input type="file" id="animalImage" name="animalImage" accept="image/*">
            <button type="submit">Enregistrer</button>
        </form>

        <h2>Liste des Animaux</h2>
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Race</th>
                    <th>Habitat</th>
                    <th>Image</th>
                    <th>Consultations</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="animals-table-body"></tbody>
        </table>
    </div>

    <script>
        const form = document.getElementById('animal-form');
        const animalsTableBody = document.getElementById('animals-table-body');

        const loadAnimals = () => {
            fetch('?action=load')
                .then(res => res.json())
                .then(data => {
                    animalsTableBody.innerHTML = '';
                    data.forEach(animal => {
                        animalsTableBody.innerHTML += `
                            <tr>
                                <td>${animal.nom}</td>
                                <td>${animal.race}</td>
                                <td>${animal.habitat}</td>
                                <td><img src="${animal.image_path}" alt="Image" width="50"></td>
                                <td>${animal.consultation_count}</td>
                                <td>
                                    <button onclick="editAnimal(${animal.id})">Modifier</button>
                                    <button onclick="deleteAnimal(${animal.id})">Supprimer</button>
                                </td>
                            </tr>
                        `;
                    });
                });
        };

        form.addEventListener('submit', e => {
            e.preventDefault();
            const formData = new FormData(form);
            fetch('?action=save', {
                method: 'POST',
                body: formData
            }).then(res => res.json())
              .then(data => {
                  alert(data.message);
                  loadAnimals();
                  form.reset();
              });
        });

        const editAnimal = id => {
            fetch(`?action=edit&id=${id}`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('animal-id').value = data.id;
                    document.getElementById('nom').value = data.nom;
                    document.getElementById('race').value = data.race;
                    document.getElementById('habitat').value = data.habitat_id;
                });
        };

        const deleteAnimal = id => {
            if (confirm('Confirmer la suppression ?')) {
                fetch(`?action=delete&id=${id}`).then(() => loadAnimals());
            }
        };

        document.addEventListener('DOMContentLoaded', loadAnimals);
    </script>
</body>
</html>
