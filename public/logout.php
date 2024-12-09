<?php
session_start();
session_destroy(); // Supprimer toutes les sessions
header("Location: index.php"); // Rediriger vers la page de connexion
exit;

