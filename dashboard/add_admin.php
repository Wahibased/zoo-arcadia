<?php
// Informations de l'administrateur
$email = 'wahi8436@gmail.com';
$password = password_hash('Admin123', PASSWORD_DEFAULT);
$role = 'administrateur';


try {
    // Vérifier si l'utilisateur existe déjà
    $stmt = $pdo->prepare('SELECT * FROM utilisateurs WHERE email = ?');
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        echo "L'utilisateur existe déjà.";
    } else {
        // Insérer l'administrateur
        $sql = 'INSERT INTO utilisateurs (email, password, role) VALUES (?, ?, ?)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email, $password, $role]);
        echo "Administrateur ajouté avec succès.";
    }
} catch (PDOException $e) {
    echo 'Erreur : ' . $e->getMessage();
}