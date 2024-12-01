<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

 <link rel="stylesheet"  href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />
      <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
        <link rel="stylesheet" href="/style.css" />
    <title>zoo arcadia</title>
   <!-- header -->
   <header class="header">
        <a href="#" class="logo"><img src="image/logo.jpg" alt="Logo" /></a>
        <nav class="navbar">
            <ul>
                <li><a href="#Services"> Services</a></li>
                <li><a href="#habitats">Habitats</a></li>
                <li><a href="#avis">Avis</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </nav>
        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="login-btn" class="fas fa-user"></div>
        </div>
        <form action="login.php" method="post" class="login-form">
            <h3>Login Admin</h3>
            <input type="text" placeholder="Nom d'utilisateur / Email" name="username" class="box" required />
            <input type="password" placeholder="Mot de passe" name="password" class="box" required />
            <div class="remember">
                <input type="checkbox" name="remember" id="remember-me" />
                <label for="remember-me">Se souvenir de moi</label>
            </div>
            <button type="submit" class="btn">Connexion</button>
            <?php
    session_start();
    if (isset($_SESSION['error'])): ?>
        <p style="color: red;"><?php echo htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8'); ?></p>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
        </form>
    </header> 
    <!--hero-->
    <section class="hero">
        <div class="hero-content">
            <h1>Bienvenue à Zoo Arcadia</h1>
            <p>Découvrez notre merveilleux zoo et explorez la diversité de la vie animale.</p>
            <a href="template/presentation.html" class="btn">Explorer</a>
        </div>
    </section>
    <body>
<!--services-->
<section class="services">
    <h2>Nos Services</h2>
    <div class="card-container">
        <div class="card">
            <img src="image/visiteguide.jpg" alt="Image 1">
            <div class="card-content">
                <h3><a href="">Visite Guidée</a></h3>
                <p>Découvrez la région avec nos visites guidées expertes.</p>
                <a href="template/services.php" class="card-link">Voir plus</a>
            </div>
        </div>
        <div class="card">
            <img src="image/restaurant.jpg" alt="Image 2">
            <div class="card-content">
                <h3><a href="">Restaurant</a></h3>
                <p>Profitez de nos délicieux plats gastronomiques .</p>
                <a href="services.php" class="card-link">Voir plus</a>
            </div>
        </div>
        <div class="card">
            <img src="image/train.jpg" alt="Image 3">
            <div class="card-content">
                <h3><a href="">visite en train</a></h3>
                <p>Voyagez confortablement avec notre service de transport en train.</p>
                <a href="template/services.php" class="card-link">Voir plus</a>
            </div>
        </div>
    </div>
    
   </section> 
<!-- Section Habitats -->
<section id="habitats" class="habitats">
    <div class="container">
        <h2>Nos Habitats</h2>
        <div class="habitat-cards">
        
            <a href="template/habitat.html" class="habitat-link">
                <div class="habitat-card" id="marais">
                    <h3>Marais</h3>
                </div>
            </a>
            <a href="template/habitat.html" class="habitat-link">
                <div class="habitat-card" id="savane">
                          <h3>Savane</h3>
                </div>
            </a>
            <a href="template/habitat.html" class="habitat-link">

                <div class="habitat-card" id="jungle">
                    <h3>Jungle</h3>
                </div>
            </a>
        </div>
    </div>
</section>
<!--avis-->
<section id="avis-section">
    <div class="container">
        <!-- Bloc pour laisser un avis -->
        <div class="form-block">
            <h1>Laissez un avis</h1>
            <form id="commentForm" action="dashboard/submit_avis.php" method="post">
                <label for="pseudo">Pseudo:</label>
                <input type="text" id="pseudo" name="pseudo" required>

                <label for="avis">Votre avis:</label>
                <textarea id="avis" name="avis" rows="5" required></textarea>

                <button type="submit" class="btn-medium">Soumettre</button>
            </form>
        </div>

       <!-- Bloc pour afficher les avis approuvés -->
<div class="comments-block">
    <h2>Avis des visiteurs</h2>
    <div id="approvedComments" class="carousel">
        <?php
       $host = xefi550t7t6tjn36.cbetxkdyhwsb.us-east-1.rds.amazonaws.com;
         $Port =3306;
         $user= smcjf3rv6c0r3x52;
         $password = g1pwk9sdxcz55ki2;
         $dbname =lk6admexwuwqufr8;
        /*
        // Connexion à la base de données
        $servername = "localhost";
        $username = "root"; // Ajustez selon votre configuration
        $password = "";     // Ajustez selon votre configuration
        $dbname = "zoo_arcadia"; // Nom de votre base de données
*/
        // Création de la connexion
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Vérifier la connexion
        if ($conn->connect_error) {
            die("La connexion a échoué : " . $conn->connect_error);
        }

        // Récupérer uniquement les avis approuvés
        $sql = "SELECT pseudo, avis FROM avis_visiteurs WHERE statut = 'approuve' ORDER BY id DESC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo '<div class="carousel-inner">';
            while ($comment = $result->fetch_assoc()) {
                echo '<div class="comment">';
                echo '<strong>' . htmlspecialchars($comment['pseudo']) . '</strong>: ' . htmlspecialchars($comment['avis']);
                echo '</div>';
            }
            echo '</div>';
        } else {
            echo "<p>Aucun avis approuvé pour le moment.</p>";
        }

        // Fermer la connexion
        $conn->close();
        ?>
    </div>
</div>

            </div>
        </div>
    </div>
</section>
<!--contact-->
<section id="contact">
    <h2>Contactez-nous</h2>
    <form action="send_contact.php" method="post">
        <label for="title">Titre:</label>
        <input type="text" id="title" name="title" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" rows="5" required></textarea>

        <label for="email">Votre Email:</label>
        <input type="email" id="email" name="email" required>

        <button type="submit" class="btn-medium" >Envoyer</button>
    </form>
</section>
 <!-- footer -->

 <section class="footer">
    <div class="box-container">
      <div class="box">
        <h3><i class="fas fa-paw"></i> zoo Arcadia</h3>
  <p>Nos Horaires.</p>
        <p class="links"><i class="fas fa-clock"></i>lundi- Dimanche</p>
        <p class="days">8:00AM - 21:00PM</p>
      </div>

      <div class="box">
        <h3>Nos contacts</h3>
        <a href="#" class="links"
          ><i class="fas fa-phone"></i> 01.22.44.55.20</a>
        <a href="#" class="links"
          ><i class="fas fa-envelope"></i> info@zooArcadia.com</a>
        <a href="#" class="links"
          ><i class="fas fa-map-marker-alt"></i>Brocélionde,Bretagne</a >
      </div>

      
      <div class="box">
        <h3>Arcadia carte</h3 >
   </script>
   <iframe src="https://www.google.com/maps/d/embed?mid=1RMFibnDTskUEdjpGRdaZxDwle3AvHWo&ehbc=2E312F"
    width="400" height="280"></iframe>
      </div>
    </div>

    <div class="credit">
      &copy; 2024 zoo Arcadia. 
     
    </div>
  </section>
   
   </script>
<script src="assets/script.js" defer></script>     
    </body>
</html>
