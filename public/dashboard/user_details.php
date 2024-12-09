<?php
require '/config/db.php';

// Assurez-vous que vous récupérez l'utilisateur par son ID ou une autre méthode
$userId = $_GET['id'];

$sql = 'SELECT * FROM users WHERE id = :id';
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $userId]);
$user = $stmt->fetch();

if ($user) {
    echo "Nom d'utilisateur: " . htmlspecialchars($user['username']) . "<br>";
    echo "Email: " . htmlspecialchars($user['email']) . "<br>";
    echo "Type: " . htmlspecialchars($user['type']) . "<br>";
    echo "Mot de passe temporaire: " . htmlspecialchars($user['plain_password']) . "<br>";
} else {
    echo "Utilisateur non trouvé.";
}

// Connexion à la base de données (à adapter selon votre configuration)
$pdo = new PDO('mysql:host=localhost;dbname=db_zoo', 'root', '');

// Vérification de la connexion
if ($pdo === false) {
    die("Erreur de connexion à la base de données");
}

// Exemple d'insertion en base de données (utilisez votre propre connexion et requête adaptée)
$sql = "INSERT INTO users (email, password) VALUES (:email, :password)";
$stmt = $pdo->prepare($sql);

// Génération d'un mot de passe aléatoire
$motDePasseTemporaire = bin2hex(random_bytes(8)); // Génère un mot de passe de 8 caractères en hexadécimal
$motDePasseHache = password_hash($motDePasseTemporaire, PASSWORD_DEFAULT); // Hachage du mot de passe temporaire

// Exécution de la requête préparée
$stmt->execute([
    'email' => 'wahi8436@gmail.com',
    'password' => $motDePasseHache
]);

echo "Utilisateur ajouté avec succès. Mot de passe temporaire : " . $motDePasseTemporaire;


