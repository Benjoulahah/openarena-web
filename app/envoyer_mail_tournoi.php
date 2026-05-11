<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . "/../lib/PHPMailer/src/Exception.php";
require_once __DIR__ . "/../lib/PHPMailer/src/PHPMailer.php";
require_once __DIR__ . "/../lib/PHPMailer/src/SMTP.php";

function envoyerMailTournoi($destinataire, $pseudo, $nom_tournoi)
{
    $mail = new PHPMailer(true);

    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = "localhost";
        $mail->SMTPAuth = true;

        // Email address used to send notifications
        $mail->Username = "admin@open-aren-rouen.test";

        // Gmail application password
        $mail->Password = "admin";

        $mail->SMTPSecure = false;
        $mail->Port = 25;

        // Sender
        $mail->setFrom("admin@open-aren-rouen.test", "Open Arena");

        // Receiver
        $mail->addAddress($destinataire, $pseudo);

        // Email content
        $mail->isHTML(true);
        $mail->CharSet = "UTF-8";
        $mail->Subject = "Nouveau tournoi Open Arena";

        $mail->Body = "
            <h2>Nouveau tournoi Open Arena</h2>
            <p>Bonjour <strong>" . htmlspecialchars($pseudo) . "</strong>,</p>
            <p>Un nouveau tournoi vient d'être créé sur le serveur Open Arena.</p>
            <p><strong>Nom du tournoi :</strong> " . htmlspecialchars($nom_tournoi) . "</p>
            <p>Connecte-toi au serveur web pour consulter les informations du tournoi.</p>
            <br>
            <p>L'équipe Open Arena</p>
        ";

        $mail->AltBody = "Bonjour " . $pseudo . ", un nouveau tournoi vient d'être créé sur Open Arena : " . $nom_tournoi;

        $mail->send();

        return true;

    } catch (Exception $e) {
        return false;
    }
}