<?php
// Connexion à la base de données
try {
    $pdo = new PDO("mysql:host=localhost;dbname=zoo_arcadia", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (Exception $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Fonction pour récupérer les animaux d'un habitat
function get_animaux_by_habitat($habitatId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM animaux WHERE habitat_id = :habitatId");
    $stmt->execute(['habitatId' => $habitatId]);
    return $stmt->fetchAll();
}

// Fonction pour récupérer les avis des vétérinaires
function get_veterinary_reports() {
    global $pdo;
    $stmt = $pdo->query("SELECT animal, avis FROM veterinary_reports");
    return $stmt->fetchAll();
}

// Récupérer les animaux par habitat
$marais_animaux = get_animaux_by_habitat(1); // Marais
$savane_animaux = get_animaux_by_habitat(2); // Savane
$jungle_animaux = get_animaux_by_habitat(3); // Jungle
// Debugging : afficher les animaux récupérés
/*echo "<pre>";
var_dump($marais_animaux);
echo "</pre>";
echo "<pre>";
var_dump($savane_animaux);
echo "</pre>";
echo "<pre>";
var_dump($jungle_animaux);
echo "</pre>";
// Récupérer les avis vétérinaires
$veterinary_reports = get_veterinary_reports();
// Debugging : afficher les rapports vétérinaires
echo "<pre>";
var_dump($veterinary_reports);
echo "</pre>";
$avis_par_animal = [];
foreach ($veterinary_reports as $report) {
    $avis_par_animal[$report['animal']] = $report['avis'];
}*/

// Ajouter les avis vétérinaires aux animaux
foreach ([$marais_animaux, $savane_animaux, $jungle_animaux] as &$animaux) {
    foreach ($animaux as &$animal) {
        $animal['avis_veterinaire'] = $avis_par_animal[$animal['id']] ?? null; // Avis ou null si pas d'avis
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/style.css">
    <title>Zoo Arcadia - Habitats</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        header {
            background-color: bisque;
            padding: 10px;
            text-align: center;
        }
        .habitat-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        .animal-card {
            margin: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 250px;
            text-align: center;
        }
        .animal-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 10px 10px 0 0;
        }
        footer {
            background-color: bisque;
            padding: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
<header>
    <h1>Exploration de nos Habitats</h1>
</header>

<?php
// Boucle principale pour afficher les animaux par habitat
foreach (['marais' => $marais_animaux, 'savane' => $savane_animaux, 'jungle' => $jungle_animaux] as $habitat => $animaux): ?>
    <h2>Habitat: <?= ucfirst($habitat) ?></h2>
    <div class="habitat-container">
        <?php foreach ($animaux as $animal): ?>
            <div class="animal-card">
                <?php
                $imagePath = !empty($animal['image_path']) 
                    ? (strpos($animal['image_path'], 'uploads/') === 0 
                        ? "/zoo-arcadia/" . htmlspecialchars($animal['image_path']) 
                        : "/zoo-arcadia/dashboard/uploads/" . htmlspecialchars($animal['image_path']))
                    : "/assets/image/default.jpg"; 
                ?>
                <img src="<?= $imagePath ?>" alt="<?= htmlspecialchars($animal['nom']) ?>">
                <p>Nom: <?= htmlspecialchars($animal['nom']) ?></p>
                <p>Race: <?= htmlspecialchars($animal['race']) ?></p>
                <p>Consultations: <span id="counter-<?= $animal['id'] ?>"><?= htmlspecialchars($animal['consultation_count']) ?></span></p>
                <p><strong>Avis du Vétérinaire :</strong> <?= !empty($animal['avis_veterinaire']) ? htmlspecialchars($animal['avis_veterinaire']) : "Aucun avis disponible" ?></p>
                <button onclick="incrementCounter(<?= $animal['id'] ?>)">Incrémenter Consultation</button>
            </div>
        <?php endforeach; ?>
    </div>
<?php endforeach; ?>
<footer>
    <p>&copy; 2024 Zoo Arcadia. Tous droits réservés.</p>
</footer>

<script>
// Fonction pour incrémenter le compteur de consultations
function incrementCounter(animalId) {
    fetch("increment_consultation.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "animalId=" + animalId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const counterSpan = document.getElementById("counter-" + animalId);
            counterSpan.textContent = data.newCount;
        } else {
            alert("Erreur : " + data.error);
        }
    })
    .catch(error => console.error("Erreur lors de l'incrémentation :", error));
}
</script>
</body>
</html>


