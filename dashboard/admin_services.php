<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "zoo_arcadia";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission for add/update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type = $_POST['type'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $image = $_POST['image'];
    
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Update existing service
        $id = $_POST['id'];
        $sql = "UPDATE services SET type='$type', title='$title', description='$description', image='$image' WHERE id=$id";
    } else {
        // Add new service
        $sql = "INSERT INTO services (type, title, description, image) VALUES ('$type', '$title', '$description', '$image')";
    }

    if ($conn->query($sql) === TRUE) {
        $message = "Service saved successfully";
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Handle deletion
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM services WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        $message = "Service deleted successfully";
    } else {
        $message = "Error: " . $conn->error;
    }
}

// Fetch all services
$sql = "SELECT * FROM services";
$result = $conn->query($sql);
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue au Zoo Arcadia</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Bienvenue au Zoo Gourmand et Guide</h1>
    </header>
    <main>
        <section id="menus">
            <h2>Nos Menus</h2>
            <article>
                <h3>Menu 1: Délices de la Savane</h3>
                <p>Profitez d'un festin inspiré par les saveurs africaines. Ce menu inclut des brochettes de viande marinée, des légumes grillés et un dessert exotique à base de fruits tropicaux. Une expérience culinaire qui vous transporte directement au cœur de la savane.</p>
            </article>
            <article>
                <h3>Menu 2: Saveurs de la Jungle</h3>
                <p>Découvrez les goûts vibrants de la jungle amazonienne avec notre assortiment de plats à base de fruits de mer, de viandes exotiques et de salades fraîches. Terminez votre repas avec un dessert chocolaté riche et savoureux.</p>
            </article>
        </section>
        
        <section id="location">
            <h2>Localisation du Restaurant</h2>
            <p>Notre restaurant est situé en plein cœur du zoo, entouré des habitats de nos animaux exotiques. Profitez d'un repas en plein air tout en observant les animaux dans leur environnement naturel.</p>
        </section>
        
        <section id="tours">
            <h2>Visites Guidées</h2>
            <article>
                <h3>Nom de notre guide zoologique : Dr. Sophie Lambert</h3>
                <p>Dr. Sophie Lambert, une zoologiste passionnée avec plus de 20 ans d'expérience, vous guidera à travers une visite informative et captivante du zoo. Découvrez les comportements fascinants des animaux, apprenez-en davantage sur leurs habitats et participez à des sessions interactives d'alimentation.</p>
            </article>
            <article>
                <h3>Visite Affolante en Petit Train</h3>
                <p>Montez à bord de notre petit train pour une aventure palpitante à travers le zoo. Cette visite affolante est parfaite pour toute la famille, offrant des vues panoramiques des enclos des animaux et des arrêts spéciaux pour des séances photos inoubliables.</p>
            </article>
        </section>
        
        <section id="invitation">
            <p>Nous vous invitons à venir vivre une expérience unique où la gastronomie et la découverte de la faune se rejoignent pour créer des souvenirs inoubliables.</p>
        </section>
     

        <section id="manage-services">
            <h2>Gestion des Services</h2>

            <?php if (isset($message)) : ?>
                <p><?php echo $message; ?></p>
            <?php endif; ?>

            <form method="post" action="">
                <input type="hidden" name="id" id="service-id">
                <label for="type">Type:</label>
                <select name="type" id="type">
                    <option value="menu">Menu</option>
                    <option value="guide">Guide</option>
                    <option value="train">Train</option>
                </select>
                <br>
                <label for="title">Title:</label>
                <input type="text" name="title" id="title" required>
                <br>
                <label for="description">Description:</label>
                <textarea name="description" id="description" required></textarea>
                <br>
                <label for="image">Image URL:</label>
                <input type="text" name="image" id="image" required>
                <br>
                <input type="submit" value="Save">
            </form>

            <h2>Services Existant</h2>
            <table>
                <tr>
                    <th>Type</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['type']; ?></td>
                            <td><?php echo $row['title']; ?></td>
                            <td><?php echo $row['description']; ?></td>
                            <td><img src="<?php echo $row['image']; ?>" alt="<?php echo $row['title']; ?>" width="50"></td>
                            <td>
                                <a href="javascript:void(0)" onclick="editService(<?php echo $row['id']; ?>, '<?php echo $row['type']; ?>', '<?php echo $row['title']; ?>', '<?php echo addslashes($row['description']); ?>', '<?php echo $row['image']; ?>')">Modifier</a>
                                <a href="?delete=<?php echo $row['id']; ?>">Supprimer</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </table>

            <script>
                function editService(id, type, title, description, image) {
                    document.getElementById('service-id').value = id;
                    document.getElementById('type').value = type;
                    document.getElementById('title').value = title;
                    document.getElementById('description').value = description;
                    document.getElementById('image').value = image;
                }
            </script>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Zoo Arcadia. Tous droits réservés.</p>
    </footer>
</body>
</html>
