<?php
require __DIR__ . './config/db.php'; // Assurez-vous que ce chemin correspond à la localisation de db.php

// Les données des services à insérer
$services = [
    [
        'type' => 'menu',
        'titre' => 'Restauration à la cafétéria',
        'description' => 'Une variété de plats pour tous les goûts. Venez savourer notre menu végétarien ou nos spécialités locales.',
        'image' => 'assets/image/restaurant.jpg',
    ],
    [
        'type' => 'menu',
        'titre' => 'Barbecue au zoo',
        'description' => 'Profitez de nos grillades au zoo, un repas en plein air avec une vue incroyable.',
        'image' => '',
    ],
    [
        'type' => 'guide',
        'titre' => 'Visite guidée des lions',
        'description' => 'Un guide expert vous emmène à la rencontre des lions dans leur enclos naturel.',
        'image' => 'assets/image/train.jpg',
    ],
  
    [
        'type' => 'train',
        'titre' => 'Tour en train autour du zoo',
        'description' => 'Faites une visite guidée en train autour du zoo, avec une vue imprenable sur les différents enclos.',
        'image' => 'assets/image/train.jpg',
    ],
  
];

try {
    // Préparer la requête d'insertion
    $sql = "INSERT INTO services (type, titre, description, image, cree_le, mis_a_jour_le) VALUES (?, ?, ?, ?, NOW(), NOW())";
    $stmt = $pdo->prepare($sql);

    // Insérer chaque service
    foreach ($services as $service) {
        $stmt->execute([$service['type'], $service['titre'], $service['description'], $service['image']]);
    }

    echo "Les services ont été ajoutés avec succès dans la base de données.";

} catch (PDOException $e) {
    echo "Erreur lors de l'ajout des services : " . $e->getMessage();
}

