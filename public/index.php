<?php
// Démarrer la session au début du fichier
session_start();

// Inclusion d'une bibliothèque pour la gestion des requêtes sécurisées (si nécessaire)
require_once('/config/db.php'); // Assurez-vous d'avoir un fichier de connexion à la base de données.

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
<style>
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700&display=swap');

:root {
  --main: #006666;
  --bg: #ffffff;
  --black: #141414;
  --white: #ffffff;
  --box-shadow: 0 .5rem 1rem rgba(0, 0, 0, 0.1);
}

* {
  font-family: 'Roboto', sans-serif;
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  outline: none;
  border: none;
  text-decoration: none;
  text-transform: capitalize;
  transition: .2s linear;
}

html {
  font-size: 62.5%;
  overflow-x: hidden;
  scroll-behavior: smooth;
  scroll-padding-top: 4rem;
}

html::-webkit-scrollbar {
  width: 1rem;
}

html::-webkit-scrollbar-track {
  background: transparent;
}

html::-webkit-scrollbar-thumb {
  background: var(--main);
  border-radius: 5rem;
}

body {
  background: var(--bg);
  color: var(--black);
}

section {
  margin-bottom: 0;
  background-color: #D9D9D9;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 7%;
  background: transparent;
  position: fixed;
  width: 100%;
  top: 0;
  left: 0;
  z-index: 1000;
  box-shadow: var(--box-shadow);
}

.header .logo img {
  max-height: 50px;
  max-width: 100%; 
}


.navbar {
  display: none;
  
}
.navbar.active {
  display: block;
}
.navbar ul {
 display: flex;
  list-style: none;
  list-style-type: none;
  margin: 0;
  padding: 0;
}

.navbar ul li {
  margin: 0 1rem;
}

.navbar ul li a {
  color: var(--white);
  font-size: 1.8rem;
}
.navbar ul li a:hover {
  color:var(--main)
}
.icons {
  display: flex;
  align-items: center;
  justify-content: flex-end;
  padding: 1rem;

}
.icons div {
  margin-left: 1rem;
  font-size: 2rem;
  cursor: pointer;
  color: var(--white);
 
}

.icons div:hover {
  color:var(--main)
}
.login-form {
  display: none ;
  flex-direction: column;
  background: var(--bg);
  padding: 2rem;
  box-shadow: var(--box-shadow);
  border-radius: 0.5rem;
  position:absolute;
  top: 10rem;
  right: 2rem;
  z-index: 1001;
}

.login-form.hidden {
  display: none;
}
.login-form.active {
  display: flex;
  right: 2rem;
  transition: .4s linear;
}

.login-form h3 {
  color: var(--main);
  font-size: 2.5rem;
  margin-bottom: 1rem;
}

.login-form .box {
  width: 100%;
  padding: 1rem;
  margin: 1rem 0;
  font-size: 1.6rem;
  border-radius: 0.5rem;
  background: var(--white);
  color: var(--black);
}

.login-form .remember {
  display: flex;
  align-items: center;
  color: var(--black);
}

.login-form .remember input {
  margin-right: 0.5rem;
}

.login-form .btn {
  font-size: 1.7rem;
  display: inline-block;
 background: var(--main);
  color: var(--white);
  box-shadow: var(--box-shadow);
  border-radius: 5rem;
  padding: 0.9rem 3rem;
  margin-top: 1rem;
  position: relative;
  overflow: hidden;
}

.login-form .btn::before {
  content: '';
  position: absolute;
  top: 0;
  height: 100%;
  width: 0%;
  background: #004d4d;
  z-index: -1;
  transition: .3s linear;
  left: 0;
}

.login-form .btn:hover::before {
  width: 100%;
}



@media (max-width: 768px) {
    .navbar ul {
        display: none;
        flex-direction: column;
        background: var(--bg);
        position: fixed;
        top: 4rem;
        right: 2rem;
        width: 200px;
        border-radius: 0.5rem;
        box-shadow: var(--box-shadow);
    }
   /* .navbar {
      display: block; 
  }*/
  .navbar ul {
      display: flex;
  }

    .navbar ul.active {
        display: flex;
    }
 .navbar ul li {
        margin: 1rem 0;
        text-align: right;
    }
 .header {
        justify-content: space-between;
    }
}
/*hero*/
.hero {
  background-image: url('/zoo-arcadia/assets/image/hero.jpg');
  background-size: cover;
  height: 85vh;
  display: flex;
  justify-content: center;
  align-items: center;
  text-align: center;
}

.hero-content h1 {
  color: white;
  font-size: 2.4rem;
}

.hero-content p {
  color: white;
  font-size: 1.4rem;
}

.btn {
  font-size: 1.7rem;
  display: inline-block;
  background: var(--main);
  color: var(--white);
  box-shadow: var(--box-shadow);
  border-radius: 5rem;
  padding: 0.9rem 3rem;
  margin-top: 1rem;
  position: relative;
  color: #ffffff;
}

.btn:hover {
  background-color: #004040;
}

@media (max-width: 768px) {
  .navbar ul {
    display: none;
    flex-direction: column;
    background: var(--bg);
    position: fixed;
    top: 8rem;
    right: 2rem;
    width: 200px;
    border-radius: 0.5rem;
    box-shadow: var(--box-shadow);
  }

  .navbar ul.active {
    display: flex;
  }

  .navbar ul li {
    margin: 1rem 0;
    text-align: right;
  }

  .header {
    justify-content: space-between;
  }

  .hero-content h1 {
    font-size: 2rem;
  }

  .hero-content p {
    font-size: 1.2rem;
  }
}

@media (max-width: 480px) {
  .hero-content h1 {
    font-size: 1.8rem;
  }

  .hero-content p {
    font-size: 1rem;
  }
}
/* presentation html*/
.container {
  max-width: 1200px;
  margin: auto;
  padding: 0 20px;
}

header {
  background: #006666;
  color: #fff;
  padding: 20px;
  text-align: center;
}

header h1 {
  font-size: 2.8rem;
  color: var(--white);
}

header p {
  font-size: 1.6rem;
}

main {
  padding: 20px 0;
}

#presentation {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin: 0;
}

.presentation-content {
  max-width: 800px;
  text-align: center;
  margin-bottom: 40px;
}

.presentation-content h2 {
  font-size: 2rem;
  margin-bottom: 20px;
  color: #141414;
}

.presentation-content p {
  font-size: 1.20rem;
  margin-bottom: 20px;
}

.presentation-album {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 40px;
}

.presentation-album figure {
  margin: 0;
}

.presentation-album img {
  width: 100%;
  display: block;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.presentation-album figcaption {
  margin-top: 10px;
  font-size: 0.9rem;
  text-align: center;
}

.card-container {
  display: flex;
  overflow-x: auto;
  margin-bottom: 10px;
  justify-content: center;
}
/* Section Footer */
.footer {
  background-color: #333;
    color: #fff;
    padding: 10px 0;
    text-align: center
}

.footer .box-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    /*gap: 0px;*/
}

.footer .box {
    flex: 1 1 80px;
    margin: 5px;
}

.footer .box h3 {
    font-size: 1.70em;
    margin-bottom: 10px;
    color: #f9c846;
}

.footer .box p,
.footer .box a {
    font-size: 1.20em;
    color: #ddd;
    text-decoration: none;
}

.footer .box a:hover {
    color: #f9c846;
}

/* Style spécifique pour la carte */
.footer .box iframe {
    width: 100%; /* Rend la carte responsive */
    max-width: 200px; /* Limite la taille maximale */
    height: 100px; /* Réduit la hauteur */
    border: none;
    margin: auto;
    display: block;
}

/* Section des crédits */
.footer .credit {
    margin-top: 10px;
    font-size: 0.8em;
    color: #ccc;
}
</style>
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
