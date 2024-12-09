<?php
// Démarrer la session au début du fichier
session_start();

// Inclusion d'une bibliothèque pour la gestion des requêtes sécurisées (si nécessaire)
require_once('db_connection.php'); // Assurez-vous d'avoir un fichier de connexion à la base de données.

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assainir les entrées
    $username = htmlspecialchars(trim($_POST['username']));
    $password = $_POST['password'];
    
    // Validation des champs
    if (empty($username) || empty($password)) {
        $_SESSION['error'] = 'Tous les champs doivent être remplis.';
    } else {
        // Vérification des informations d'identification dans la base de données
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username OR email = :username LIMIT 1");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        
        // Vérification du mot de passe
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            // Connexion réussie
            $_SESSION['user'] = $user;
            $_SESSION['success'] = 'Connexion réussie.';
            header('Location: dashboard.php'); // Rediriger vers une page protégée
            exit;
        } else {
            // Informations d'identification incorrectes
            $_SESSION['error'] = 'Nom d\'utilisateur ou mot de passe incorrect.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Feuilles de style -->
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="assets/style.css">
    
    <title>Zoo Arcadia</title>

</head>
  <body>
    <!-- Header -->
    <header class="header">
        <a href="#" class="logo"><img src="assets/image/logo.jpg" alt="Logo"></a>
        <nav class="navbar">
            <ul>
                <li><a href="template/services.php">Services</a></li>
                <li><a href="template/habitat.php">Habitats</a></li>
                <li><a href="avis.php">Avis</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="login-btn" class="fas fa-user"></div>
        </div>
        <form action="" method="post" class="login-form">
            <h3>Login Admin</h3>
            <input type="text" placeholder="Nom d'utilisateur / Email" name="username" class="box" required>
            <input type="password" placeholder="Mot de passe" name="password" class="box" required>
            <div class="remember">
                <input type="checkbox" name="remember" id="remember-me">
                <label for="remember-me">Se souvenir de moi</label>
            </div>
            <button type="submit" class="btn">Connexion</button>
            
            <?php if (isset($_SESSION['error'])): ?>
                <p style="color: red;"><?php echo htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8'); ?></p>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            <?php if (isset($_SESSION['success'])): ?>
                <p style="color: green;"><?php echo htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8'); ?></p>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
        </form>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Bienvenue à Zoo Arcadia</h1>
            <p>Découvrez notre merveilleux zoo et explorez la diversité de la vie animale.</p>
            <a href="template/presentation.html" class="btn">Explorer</a>
        </div>
    </section>

    <!-- Footer -->
    <section class="footer">
        <div class="box-container">
            <div class="box">
                <h3><i class="fas fa-paw"></i> Horaires de Zoo Arcadia</h3>
                <p class="links"><i class="fas fa-clock"></i> Lundi-Dimanche</p>
                <p class="days">8:00AM - 21:00PM</p>
            </div>
            <div class="box">
                <h3>Nos contacts</h3>
                <a href="#" class="links"><i class="fas fa-phone"></i> 01.22.44.55.20</a>
                <a href="#" class="links"><i class="fas fa-envelope"></i> info@zooArcadia.com</a>
                <a href="#" class="links"><i class="fas fa-map-marker-alt"></i> Brocéliande, Bretagne</a>
            </div>
            <div class="box">
                <h3>Arcadia Carte</h3>
                <iframe src="https://www.google.com/maps/d/embed?mid=1RMFibnDTskUEdjpGRdaZxDwle3AvHWo&ehbc=2E312F" width="400" height="280"></iframe>
            </div>
        </div>
        <div class="credit">
            &copy; 2024 Zoo Arcadia.
        </div>
    </section>

    <!-- Script -->
    <script src="assets/script.js" defer></script>
</body>
</html>
