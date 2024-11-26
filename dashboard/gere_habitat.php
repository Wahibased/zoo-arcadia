<?php
// Paramètres de connexion
$host = 'localhost';
$dbname = 'zoo_arcadia';
$user = 'root';
$password = '';

try {
    // Connexion à la base de données avec PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Exemple de récupération des habitats et des animaux
    $stmt = $pdo->query('SELECT * FROM habitats');
    $habitats = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($habitats as &$habitat) {
        $stmtAnimals = $pdo->prepare('SELECT * FROM animals WHERE habitat_id = ?');
        $stmtAnimals->execute([$habitat['id']]);
        $habitat['animals'] = $stmtAnimals->fetchAll(PDO::FETCH_ASSOC);
    }

    // Maintenant, $habitats contient les données pour la page.
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Habitats</title>
</head>
<body>
<header> 
    <h1>Exploration nos Habitats</h1>
</header>

<!-- Bouton pour ajouter un nouvel habitat -->
<button onclick="showAddHabitatForm()">Ajouter un nouvel habitat</button>

<!-- Formulaire pour ajouter un nouvel habitat (initialement caché) -->
<div id="addHabitatForm" style="display: none;">
    <h3>Ajouter un nouvel habitat</h3>
    <form onsubmit="addHabitat(event)">
        <input type="text" name="habitatName" placeholder="Nom de l'habitat" required>
        <button type="submit">Ajouter</button>
    </form>
</div>

<?php
// Simulation de données pour les habitats et animaux (remplacez cela par vos données réelles provenant d'une base de données)
$habitats = [
    [
        'id' => 1,
        'name' => 'marai',
        'animals' => [
            ['id' => 1, 'name' => 'Lion', 'race' => 'Panthère', 'state' => 'En bonne santé', 'image' => 'lion.jpg'],
            ['id' => 2, 'name' => 'Éléphant', 'race' => 'Loxodonta', 'state' => 'En bonne santé', 'image' => 'elephant.jpg'],
        ]
    ],
    [
        'id' => 2,
        'name' => 'jungle ',
        'animals' => [
            ['id' => 1, 'name' => 'Toucan', 'race' => 'Ramphastidae', 'state' => 'En bonne santé', 'image' => 'toucan.jpg'],
            ['id' => 2, 'name' => 'Jaguar', 'race' => 'Panthera', 'state' => 'En bonne santé', 'image' => 'jaguar.jpg'],
        ]
        ],
    [
        'id' => 3,
        'name' => 'savane',
        'animals' => [
            ['id' => 1, 'name' => 'Toucan', 'race' => 'Ramphastidae', 'state' => 'En bonne santé', 'image' => 'toucan.jpg'],
            ['id' => 2, 'name' => 'Jaguar', 'race' => 'Panthera', 'state' => 'En bonne santé', 'image' => 'jaguar.jpg'],
        ]
    ]
];
?>

<!-- Boucle pour chaque habitat -->
<?php foreach ($habitats as $habitat): ?>
<div class="habitat">
    <h2><?php echo htmlspecialchars($habitat['name']); ?></h2>
    <button onclick="editHabitat('<?php echo $habitat['id']; ?>')">Modifier</button>
    <button onclick="deleteHabitat('<?php echo $habitat['id']; ?>')">Supprimer</button>

    <!-- Bouton pour ajouter un nouvel animal -->
    <button onclick="showAddAnimalForm('<?php echo $habitat['id']; ?>')">Ajouter un animal</button>

    <!-- Formulaire pour ajouter un nouvel animal (initialement caché) -->
    <div id="addAnimalForm-<?php echo $habitat['id']; ?>" style="display: none;">
        <h3>Ajouter un nouvel animal</h3>
        <form onsubmit="addAnimal(event, '<?php echo $habitat['id']; ?>')">
            <input type="text" name="animalName" placeholder="Nom de l'animal" required>
            <input type="text" name="animalRace" placeholder="Race de l'animal" required>
            <input type="text" name="animalState" placeholder="État de l'animal" required>
            <input type="file" name="animalImage" accept="image/*" required>
            <button type="submit">Ajouter</button>
        </form>
    </div>

    <!-- Boucle pour chaque animal dans l'habitat -->
    <?php foreach ($habitat['animals'] as $animal): ?>
    <div class="animal-card" id="animal-<?php echo $habitat['id']; ?>-<?php echo $animal['id']; ?>" onclick="incrementCounter('<?php echo $habitat['id']; ?>', '<?php echo $animal['id']; ?>')">
        <img src="<?php echo htmlspecialchars($animal['image']); ?>" alt="<?php echo htmlspecialchars($animal['name']); ?>">
        <p>Nom: <?php echo htmlspecialchars($animal['name']); ?></p>
        <p>Race: <?php echo htmlspecialchars($animal['race']); ?></p>
        <p>Habitat: <?php echo htmlspecialchars($habitat['name']); ?></p>
        <p>État: <span id="state-<?php echo $habitat['id']; ?>-<?php echo $animal['id']; ?>"><?php echo htmlspecialchars($animal['state']); ?></span></p>
        <p>Consultations: <span id="counter-<?php echo $habitat['id']; ?>-<?php echo $animal['id']; ?>">0</span></p>
        <p><strong>Avis du Vétérinaire :</strong> <p><strong>Avis du Vétérinaire :</strong><?php echo !empty($animal['vet_advice'])  ? htmlspecialchars($animal['vet_advice']) 
            : 'Aucun avis disponible';   ?>
        <button onclick="editAnimal('<?php echo $habitat['id']; ?>', '<?php echo $animal['id']; ?>')">Modifier</button>
        <button onclick="deleteAnimal('<?php echo $habitat['id']; ?>', '<?php echo $animal['id']; ?>')">Supprimer</button>
    </div>
    <?php endforeach; ?>
</div>
<?php endforeach; ?>

<footer>
    <p>&copy; 2024 Zoo Arcadia. Tous droits réservés.</p>
</footer>

<script>
    function showAddHabitatForm() {
        document.getElementById('addHabitatForm').style.display = 'block';
    }

    function addHabitat(event) {
        event.preventDefault();
        console.log('Ajouter habitat');
    }

    function editHabitat(habitatId) {
        console.log('Modifier habitat', habitatId);
    }

    function deleteHabitat(habitatId) {
        console.log('Supprimer habitat', habitatId);
    }

    function showAddAnimalForm(habitatId) {
        document.getElementById(`addAnimalForm-${habitatId}`).style.display = 'block';
    }

    function addAnimal(event, habitatId) {
        event.preventDefault();
        console.log('Ajouter animal à l\'habitat', habitatId);
    }

    function editAnimal(habitatId, animalId) {
        console.log('Modifier animal', habitatId, animalId);
    }

    function deleteAnimal(habitatId, animalId) {
        console.log('Supprimer animal', habitatId, animalId);
    }
</script>
</body>
</html>
