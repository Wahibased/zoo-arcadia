<?php
session_start();

// Rediriger l'utilisateur non connecté vers la page de connexion
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../index.html");
    exit;
}

