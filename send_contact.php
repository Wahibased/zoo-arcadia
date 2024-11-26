<?php
// Inclure l'autoloader de Composer pour PHPMailer
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Vérifier que le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $clientEmail = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

    // Vérifier que l'email du client est valide
    if (!$clientEmail) {
        die("Email invalide.");
    }

    // Configuration de l'email de l'administrateur et du sujet
    $adminEmail = 'wahi8436@gmail.com'; // Remplacez par l'email de l'administrateur
    $subject = "Nouveau message de contact : $title";

    // Créer une instance de PHPMailer
    $mail = new PHPMailer(true);

    try {
        // 1. Envoyer l'email de notification à l'administrateur
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'wahi8436@gmail.com'; // Votre email SMTP
        $mail->Password = 'gyyi sxzb qkur phwk'; // Mot de passe SMTP
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Informations pour l'email de l'administrateur
        $mail->setFrom($clientEmail); // Expéditeur (le client)
        $mail->addAddress($adminEmail); // Destinataire (l'admin)
        $mail->addReplyTo($clientEmail); // Réponse directe au client
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = "
            <h2>Nouveau message de contact reçu</h2>
            <p><strong>Titre:</strong> $title</p>
            <p><strong>Description:</strong><br>$description</p>
            <p><strong>Email du client:</strong> $clientEmail</p>
        ";
        $mail->send();

        // 2. Envoyer l'email de confirmation au client
        $mail->clearAddresses(); // Réinitialiser les adresses pour le deuxième email
        $mail->addAddress($clientEmail); // Destinataire (le client)
        $mail->setFrom($adminEmail, 'Zoo Arcadia'); // Expéditeur (l'admin)
        $mail->Subject = "Confirmation de réception de votre message - Zoo Arcadia";
        $mail->Body = "
            <h2>Merci de nous avoir contactés !</h2>
            <p>Bonjour,</p>
            <p>Nous avons bien reçu votre message et vous remercions de nous avoir contactés. Voici un récapitulatif de votre demande :</p>
            <p><strong>Titre :</strong> $title</p>
            <p><strong>Description :</strong><br>$description</p>
            <p>Un membre de notre équipe vous répondra dans les plus brefs délais.</p>
            <p>Si vous avez des questions supplémentaires, n'hésitez pas à nous contacter directement en répondant à cet e-mail.</p>
            <br>
            <p>Cordialement,</p>
            <p><strong>L'équipe de Zoo Arcadia</strong></p>
        ";
        $mail->send();

        echo "Votre message a été envoyé avec succès.";
    } catch (Exception $e) {
        echo "Une erreur est survenue lors de l'envoi du message. Erreur: {$mail->ErrorInfo}";
    }
}

