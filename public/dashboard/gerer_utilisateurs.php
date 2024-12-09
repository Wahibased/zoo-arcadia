<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../config/db.php';
require __DIR__ . '/../vendor/autoload.php';

// Connexion à la base de données avec PDO
$hostname = 'localhost';
$username = 'root';
$password = '';
$dbname = 'zoo_arcadia';

try {
    $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Fonction pour générer un mot de passe aléatoire
function generatePassword($length = 12) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomPassword = '';
    for ($i = 0; $i < $length; $i++) {
        $randomPassword .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomPassword;
}

// Fonction pour hacher le mot de passe
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

// Traitement du formulaire d'ajout d'utilisateur
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'ajouter') {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $role = $_POST['role'];
  
    // Générer un nom d'utilisateur unique
    $username = strtolower(explode(' ', $nom)[0]) . rand(1000, 9999);

    // Générer et hacher un mot de passe aléatoire
    $password = generatePassword();
    $hashedPassword = hashPassword($password);

    // Insérer l'utilisateur dans la base de données
    $sql = "INSERT INTO utilisateurs (nom, email, password, role) 
            VALUES (:nom, :email, :password, :role)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'nom' => $nom,
        'email' => $email,
        'password' => $hashedPassword,
        'role' => $role,
        
    ]);

    // Envoyer un e-mail si ajout réussi
    if ($stmt->rowCount() > 0) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'wahi8436@gmail.com'; // Email SMTP
            $mail->Password = 'gyyi sxzb qkur phwk'; // Mot de passe spécifique de l'application
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('noreply@zooarcadia.com', 'Zoo Arcadia');
            $mail->addAddress($email, $nom);

            $mail->isHTML(true);
            $mail->Subject = "Bienvenue à Zoo Arcadia - Accès à votre compte";
            $mail->Body = "
                <p>Bonjour $nom,</p>
                <p>Votre compte a été créé avec succès. Voici vos identifiants :</p>
                <p><strong>Nom d'utilisateur :</strong> $username</p>
                <p><strong>Mot de passe temporaire :</strong> $password</p>
                <p>Cordialement,<br>L'équipe de Zoo Arcadia</p>";
            $mail->send();
            echo "Utilisateur ajouté avec succès. Email envoyé à $email.";
        } catch (Exception $e) {
            echo "Erreur d'envoi de l'e-mail : {$mail->ErrorInfo}";
        }
    } else {
        echo "Erreur lors de l'ajout de l'utilisateur.";
    }
}
// Code de gestion des ajouts et de la connexion PDO (tel que dans le précédent exemple)

// Suppression de l'utilisateur
if (isset($_GET['action']) && $_GET['action'] === 'supprimer' && isset($_GET['id'])) {
    $userId = $_GET['id'];
    $deleteStmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id = :id");
    $deleteStmt->execute(['id' => $userId]);
    header("Location: gerer_utilisateurs.php");
    exit;
}

// Édition de l'utilisateur
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'editer') {
    $userId = $_POST['id'];
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $editStmt = $pdo->prepare("UPDATE utilisateurs SET nom = :nom, email = :email, role = :role WHERE id = :id");
    $editStmt->execute([
        'nom' => $nom,
        'email' => $email,
        'role' => $role,
        'id' => $userId,
    ]);

    header("Location: gerer_utilisateurs.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Gérer Utilisateurs - Zoo Arcadia</title>
</head>
<body>
    <header>
   
             
    </header>
    <main>
        <section id="gerer_utilisateurs">
            <h2>Gérer Utilisateurs</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $pdo->query("SELECT * FROM utilisateurs");
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['nom']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['role']; ?></td>
                        
                        <td>
                            <!-- Actions (supprimer ou éditer) -->
                            <a href="gerer_utilisateurs.php?action=editer&id=<?php echo $row['id']; ?>">Éditer</a> |
                            <a href="gerer_utilisateurs.php?action=supprimer&id=<?php echo $row['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">Supprimer</a>
                        
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            
<!--Formulaire d'ajout d'un nouvel utilisateur, toujours visible -->
           <div class="new-user-form">
               <h3>Ajouter un Nouvel Utilisateur</h3>
               <form action="gerer_utilisateurs.php" method="post">
                   <input type="hidden" name="action" value="ajouter">
                   
                   <label for="nom">Nom:</label>
                   <input type="text" id="nom" name="nom" required>

                   <label for="email">Email:</label>
                   <input type="email" id="email" name="email" required>

                   <label for="role">Rôle:</label>
                   <select id="role" name="role" required>
                       <option value="employe">Employé</option>
                       <option value="veterinaire">Vétérinaire</option>
                   </select>

                   <button type="submit" class="btn">Ajouter</button>
               </form>
           </div>

           <!-- Formulaire d'édition de l'utilisateur, visible seulement si 'action=editer' et 'id' sont définis -->
           <?php if (isset($_GET['action']) && $_GET['action'] === 'editer' && isset($_GET['id'])):
               $userId = $_GET['id'];
               $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = :id");
               $stmt->execute(['id' => $userId]);
               $user = $stmt->fetch(PDO::FETCH_ASSOC);
               if ($user):
           ?>
           <div class="edit-user-form">
               <h3>Modifier l'Utilisateur</h3>
               <form action="gerer_utilisateurs.php" method="post">
                   <input type="hidden" name="action" value="editer">
                   <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                   
                   <label for="nom">Nom:</label>
                   <input type="text" id="nom" name="nom" value="<?php echo $user['nom']; ?>" required>

                   <label for="email">Email:</label>
                   <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>

                   <label for="role">Rôle:</label>
                   <select id="role" name="role" required>
                       <option value="employe" <?php echo $user['role'] == 'employe' ? 'selected' : ''; ?>>Employé</option>
                       <option value="veterinaire" <?php echo $user['role'] == 'veterinaire' ? 'selected' : ''; ?>>Vétérinaire</option>
                   </select>

                   <button type="submit" class="btn">Mettre à jour</button>
               </form>
           </div>
           <?php endif; endif; ?>
        </section>
    </main>
    <footer>
        <!-- Code du footer -->
  </footer>
</body>
</html>
