<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avis des visiteurs | Zoo Arcadia</title>
    <link rel="stylesheet" href="">
</head>
<style>
        /* Global Styles */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
    color: #333;
}

/* Header Styles */
.header {
    background-color: #2c3e50;
    padding: 20px;
    text-align: center;
}

.header .logo img {
    width: 150px;
}

.navbar {
    margin-top: 20px;
}

.navbar ul {
    list-style: none;
    padding: 0;
}

.navbar ul li {
    display: inline;
    margin: 0 15px;
}

.navbar ul li a {
    color: white;
    text-decoration: none;
    font-size: 18px;
    text-transform: uppercase;
    font-weight: bold;
}

.navbar ul li a:hover {
    color: #e74c3c;
}
    /* Avis Form */
.avis-form {
    background-color: white;
    max-width: 600px;
    margin: 50px auto;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.avis-form h1 {
    text-align: center;
    color: #2c3e50;
}

.avis-form .form-group {
    margin-bottom: 20px;
}

.avis-form label {
    font-weight: bold;
    display: block;
    margin-bottom: 5px;
}

.avis-form input,
.avis-form textarea {
    width: 100%;
    padding: 10px;
    margin: 5px 0 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
}

.avis-form button {
    background-color: #e74c3c;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
}

.avis-form button:hover {
    background-color: #c0392b;
}

/* Comment Display */
.comments-container {
    background-color: white;
    max-width: 600px;
    margin: 50px auto;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.comment {
    border-bottom: 1px solid #ccc;
    padding: 10px 0;
    margin-bottom: 10px;
}

.comment strong {
    color: #e74c3c;
}

.comment p {
    font-size: 14px;
    color: #555;
}

.footer {
    background-color: #2c6e50;
    color: white;
    padding: 30px 0;
    margin-top: 50px;
}

.footer .box-container {
    display: flex;
    justify-content: space-between;
    padding: 0 50px;
}

.footer .box {
    width: 30%;
}

.footer .box h3 {
    font-size: 18px;
    margin-bottom: 10px;
    text-align:center;
}

.footer .links {
    color: white;
    text-decoration: none;
    display: block;
    margin: 5px 0;
}

.footer .links:hover {
    color: #e74c3c;
}

.footer .box iframe {
    width: 100%;
    height: 200px;
    border: 0;
}

.footer .credit {
    text-align: center;
    margin-top: 20px;
    font-size: 14px;
}

/* Mobile Styles */
@media (max-width: 768px) {
    .navbar ul li {
        display: block;
        margin: 10px 0;
        text-align: center;
    }

    .footer .box-container {
        flex-direction: column;
        align-items: center;
    }

    .footer .box {
        width: 80%;
        margin-bottom: 30px;
    }
}

</style>
<body>

    <!-- Header -->
    <header class="header">
        <a href="index.php" class="logo"><img src="assets/image/logo.jpg" alt="Logo Zoo Arcadia"></a>
        <nav class="navbar">
            <ul>
                <li><a href="index.php#Services">Services</a></li>
                <li><a href="index.php#habitats">Habitats</a></li>
                <li><a href="avis.php">Avis</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
    </header>

    <!-- Formulaire pour Soumettre un Avis -->
    <section class="avis-form">
        <h1>Laissez votre Avis</h1>
        <form action="/dashboard/submit_avis.php" method="POST">
            <label for="pseudo">Pseudo:</label>
            <input type="text" id="pseudo" name="pseudo" required>

            <label for="avis">Votre Avis:</label>
            <textarea id="avis" name="avis" rows="5" required></textarea>

            <button type="submit" class="btn">Soumettre</button>
        </form>
    </section>

    <!-- Section pour Afficher les Avis -->
    <section class="approved-comments">
        <h2>Avis des Visiteurs</h2>
        <div class="comments-container">
            <?php
            require_once 'config/db.php'; // Connexion à la base de données
            $sql = "SELECT pseudo, avis FROM avis_visiteurs WHERE statut = 'approuve' ORDER BY id DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $comments = $stmt->fetchAll();

            if (!empty($comments)) {
                foreach ($comments as $comment) {
                    echo "<div class='comment'>";
                    echo "<strong>" . htmlspecialchars($comment['pseudo']) . "</strong>: ";
                    echo htmlspecialchars($comment['avis']);
                    echo "</div>";
                }
            } else {
                echo "<p>Aucun avis n'est disponible pour le moment.</p>";
            }
            ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
    <footer>
    <p>&copy; 2024 Zoo Arcadia. Tous droits réservés.</p>
</footer>

    <script src="assets/script.js" defer></script>
</body>
</html>
