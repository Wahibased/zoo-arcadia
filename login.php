<?php
session_start();

// Vérification des identifiants statiques pour l'exemple
// Dans un projet réel, vous devez utiliser une base de données pour vérifier les informations.
$valid_username = "wahi";
$valid_password = "Admin123";

// Récupérer les données du formulaire
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Vérifier si les identifiants sont corrects
if ($username === $valid_username && $password === $valid_password) {
    // Connexion réussie
    $_SESSION['logged_in'] = true;
    $_SESSION['username'] = $username; // Pour afficher le nom de l'utilisateur
    header("Location: dashboard/index.html");
    exit;
} else {
    // Connexion échouée
    $_SESSION['error'] = "Nom d'utilisateur ou mot de passe incorrect.";
    header("Location: index.html"); // Redirection vers la page de connexion
    exit;
}
?>
