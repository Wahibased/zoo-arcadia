<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contactez-nous | Zoo Arcadia</title>
    <link rel="stylesheet" href="assets/styles.css">
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

/* Contact Form Styles */
.contact-form {
    background-color: white;
    max-width: 600px;
    margin: 50px auto;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.contact-form h1 {
    text-align: center;
    color: #2c3e50;
}

.contact-form .form-group {
    margin-bottom: 20px;
}

.contact-form label {
    font-weight: bold;
    display: block;
    margin-bottom: 5px;
}

.contact-form input,
.contact-form textarea {
    width: 100%;
    padding: 10px;
    margin: 5px 0 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
}

.contact-form textarea {
    resize: vertical;
}

.contact-form button {
    background-color: #e74c3c;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
}

.contact-form button:hover {
    background-color: #c0392b;
}

/* Footer Styles */
.footer {
    background-color: #2c3e50;
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
                <li><a href="index.php#avis">Avis</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
    </header>

    <!-- Formulaire de Contact -->
    <section class="contact-form">
        <h1>Contactez-nous</h1>
        <form action="send_contact.php" method="POST">
            <div class="form-group">
                <label for="title">Titre :</label>
                <input type="text" id="title" name="title" required>
            </div>

            <div class="form-group">
                <label for="description">Description :</label>
                <textarea id="description" name="description" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label for="email">Votre Email :</label>
                <input type="email" id="email" name="email" required>
            </div>

            <button type="submit" class="btn">Envoyer</button>
        </form>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="box-container">
            <div class="box">
                <h3><i class="fas fa-paw"></i> Zoo Arcadia</h3>
                <p>Nos horaires</p>
                <p>8:00AM - 21:00PM</p>
            </div>
            <div class="box">
                <h3>Nos contacts</h3>
                <a href="tel:0122445520" class="links"><i class="fas fa-phone"></i> 01.22.44.55.20</a>
                <a href="mailto:info@zooArcadia.com" class="links"><i class="fas fa-envelope"></i> info@zooArcadia.com</a>
            </div>
        </div>
    </footer>

    <script src="assets/script.js" defer></script>
</body>
</html>
