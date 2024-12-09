<?php
// Inclure le fichier de configuration pour la connexion à la base de données
require_once '../config/db.php'; // Ici, le fichier db.php doit définir la variable $pdo pour la connexion PDO

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Récupérer les données POST et les filtrer
        $animal = htmlspecialchars($_POST['animal'], ENT_QUOTES, 'UTF-8');
        $date = $_POST['date'];
        $etat = htmlspecialchars($_POST['etat'], ENT_QUOTES, 'UTF-8');
        $nourriture = htmlspecialchars($_POST['nourriture'], ENT_QUOTES, 'UTF-8');
        $grammage = (int) $_POST['grammage'];
        $avis = htmlspecialchars($_POST['avis'], ENT_QUOTES, 'UTF-8');

        // Vérification des champs obligatoires
        if (empty($animal) || empty($date) || empty($etat) || empty($nourriture) || $grammage <= 0 || empty($avis)) {
            throw new Exception("Tous les champs sont obligatoires.");
        }

        // Requête SQL préparée
        $sql = "INSERT INTO veterinary_reports (animal, date, etat, nourriture, grammage, avis)
                VALUES (:animal, :date, :etat, :nourriture, :grammage, :avis)";
        
        // Utiliser la connexion PDO pour préparer la requête
        $stmt = $pdo->prepare($sql);

        // Exécuter la requête avec les valeurs de paramètres
        $stmt->execute([
            ':animal' => $animal,
            ':date' => $date,
            ':etat' => $etat,
            ':nourriture' => $nourriture,
            ':grammage' => $grammage,
            ':avis' => $avis
        ]);

        // Redirection ou message de succès
        header("Location: success_page.php");
        exit;
    } catch (Exception $e) {
        // Gestion des erreurs
        http_response_code(400);
        echo "Erreur : " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    }
} else {
    // Méthode non autorisée
    http_response_code(405);
    echo "Méthode non autorisée.";
}
<script>
        document.getElementById('veterinary_reports').addEventListener('submit', function (e) {
            let errors = [];
            const date = document.getElementById('date').value;
            const grammage = document.getElementById('grammage').value;
            const etat = document.getElementById('etat').value;
        
            if (!date) {
                errors.push("Veuillez sélectionner une date.");
            }
            if (grammage <= 0) {
                errors.push("Le grammage doit être supérieur à 0.");
            }
            if (etat.trim() === '') {
                errors.push("Veuillez décrire l'état de l'animal.");
            }
        
            if (errors.length > 0) {
                e.preventDefault();
                alert(errors.join('\n'));
            }
        });
        </script>