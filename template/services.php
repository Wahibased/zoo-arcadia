<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "zoo_arcadia";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all services
$sql = "SELECT * FROM services";
$result = $conn->query($sql);

$services = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos Services - Zoo Arcadia</title>
    <link rel="stylesheet" href="/assets/style.css.css?v=1.0">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php"> Accueil</a></li>
         </nav>
       
    </header>
    <h1>Nos Services au Zoo Arcadia</h1>
    <main>
        <section id="restauration">
            <h2>Restauration</h2>
            <div class="service-cards">
                <?php foreach ($services as $service): ?>
                    <?php if ($service['type'] == 'menu'): ?>
                        <div class="card">
                            <img src="<?php echo $service['image']; ?>" alt="<?php echo $service['titre']; ?>">
                            <div class="card-content">
                                <h3><?php echo $service['titre']; ?></h3>
                                <p><?php echo $service['description']; ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </section>

        <section id="visites-guidees">
            <h2>Visites Guidées</h2>
            <div class="service-cards">
                <?php foreach ($services as $service): ?>
                    <?php if ($service['type'] == 'guide'): ?>
                        <div class="card">
                            <img src="<?php echo $service['image']; ?>" alt="<?php echo $service['titre']; ?>">
                            <div class="card-content">
                                <h3><?php echo $service['titre']; ?></h3>
                                <p><?php echo $service['description']; ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <h2>Visite en Train</h2>
            <div class="service-cards">
                <?php foreach ($services as $service): ?>
                    <?php if ($service['type'] == 'train'): ?>
                        <div class="card">
                            <img src="<?php echo $service['image']; ?>" alt="<?php echo $service['titre']; ?>">
                            <div class="card-content">
                                <h3><?php echo $service['titre']; ?></h3>
                                <p><?php echo $service['description']; ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Zoo Arcadia. Tous droits réservés.</p>
    </footer>
</body>
</html> 