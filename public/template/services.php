<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "zoo_arcadia";
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupération des services
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
    <!-- Lien vers le fichier CSS -->
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <header>
        <style>
         /* Global Styles */
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
    color: #333;
    line-height: 1.6;
}

/* Header Section */
header {
    background-color: #2c3e50;
    color: white;
    text-align: center;
    padding: 30px 0;
}

header h1 {
    font-size: 36px;
    margin: 0;
}

/* Services Section */
.services {
    padding: 50px 20px;
    text-align: center;
    background-color: #fff;
}

.services h2 {
    font-size: 28px;
    color: #2c3e50;
    margin-bottom: 20px;
}

.card-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
}

/* Individual Card Styles */
.card {
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 10px;
    overflow: hidden;
    max-width: 300px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s, box-shadow 0.3s;
}

.card img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-bottom: 1px solid #ddd;
}

.card-content {
    padding: 15px;
    text-align: center;
}

.card-content h3 {
    font-size: 20px;
    color: #2c3e50;
    margin: 10px 0;
}

.card-content h3 a {
    text-decoration: none;
    color: #e74c3c;
}

.card-content h3 a:hover {
    color: #c0392b;
}

.card-content p {
    font-size: 14px;
    color: #555;
}

/* Hover Effect */
.card:hover {
    transform: translateY(-10px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

/* Service Sections (Dynamic Content) */
.service-cards {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
    margin-top: 20px;
}

.service-cards .card {
    max-width: 280px;
}

.service-cards .service-image {
    border-radius: 10px 10px 0 0;
}

/* Section Headers */
main h2 {
    font-size: 24px;
    color: #2c3e50;
    text-align: center;
    margin: 40px 0 20px;
}

/* Footer Styles */
footer {
    background-color: #2c3e50;
    color: white;
    text-align: center;
    padding: 20px 0;
    margin-top: 40px;
}

footer p {
    font-size: 14px;
    margin: 0;
}

/* Responsive Design */
@media (max-width: 768px) {
    .card-container, 
    .service-cards {
        flex-direction: column;
        align-items: center;
    }
    
    .card {
        max-width: 100%;
    }
}

      

        </style>
    </header>

    <h1>Nos Services au Zoo Arcadia</h1>
    <section class="services" id="Services">
        <h2>Nos Services</h2>
        <div class="card-container">
            <div class="card">
                <img src="../assets/image/visiteguide.jpg" alt="Visite guidée">
                <div class="card-content">
                    <h3><a href="#">Visite Guidée</a></h3>
                    <p>Découvrez la région avec nos visites guidées expertes.</p>
                   
                </div>
            </div>
            <div class="card">
                <img src="../assets/image/restaurant.jpg" alt="Restaurant">
                <div class="card-content">
                    <h3><a href="#">Restaurant</a></h3>
                    <p>Profitez de nos délicieux plats gastronomiques.</p>
                    
                </div>
            </div>
            <div class="card">
                <img src="../assets/image/train.jpg" alt="Train">
                <div class="card-content">
                    <h3><a href="#">Visite en train</a></h3>
                    <p>Voyagez confortablement avec notre service de transport en train.</p>
                    
                </div>
            </div>
        </div>
    </section>
    <footer>
        <p>&copy; 2024 Zoo Arcadia. Tous droits réservés.</p>
    </footer>
</body>
</html>


    <main>
        <section id="restauration">
            <h2>Restauration</h2>
            <div class="service-cards">
                <?php foreach ($services as $service): ?>
                    <?php if ($service['type'] == 'menu'): ?>
                        <div class="card">
                            <!-- Affichage de l'image -->
                            <img src="../assets/image/restaurant.jpg<?php echo $service['image']; ?>" alt="<?php echo $service['titre']; ?>" class="service-image">
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
                            <img src="../assets/image/visiteguide.jpg<?php echo $service['image']; ?>" alt="<?php echo $service['titre']; ?>" class="service-image">
                            <div class="card-content">
                                <h3><?php echo $service['titre']; ?></h3>
                                <p><?php echo $service['description']; ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </section>

        <section id="train">
            <h2>Visite en Train</h2>
            <div class="service-cards">
                <?php foreach ($services as $service): ?>
                    <?php if ($service['type'] == 'train'): ?>
                        <div class="card">
                            <img src="../assets/image/train.jpg<?php echo $service['image']; ?>" alt="<?php echo $service['titre']; ?>" class="service-image">
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

