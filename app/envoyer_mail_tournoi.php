<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . "/../lib/PHPMailer/src/Exception.php";
require_once __DIR__ . "/../lib/PHPMailer/src/PHPMailer.php";
require_once __DIR__ . "/../lib/PHPMailer/src/SMTP.php";

function creerContenuMailTournoi($pseudo, $nom_tournoi)
{
    $pseudo_propre = htmlspecialchars($pseudo, ENT_QUOTES, "UTF-8");
    $nom_tournoi_propre = htmlspecialchars($nom_tournoi, ENT_QUOTES, "UTF-8");

    return "
        <h2>Nouveau tournoi Open Arena Rouen</h2>
        <p>Bonjour <strong>$pseudo_propre</strong>,</p>
        <p>Un nouveau tournoi vient d'être créé sur le serveur Open Arena Rouen.</p>
        <p><strong>Nom du tournoi :</strong> $nom_tournoi_propre</p>
        <p>Connecte-toi au serveur web pour consulter les informations du tournoi.</p>
        <br>
        <p>L'équipe Open Arena Rouen</p>
    ";
}

function envoyerMailPersonnel($destinataire, $pseudo, $nom_tournoi)
{
    $mail = new PHPMailer(true);

    try {
        // Gmail SMTP configuration
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;

        $mail->Username = "palfraybenjamin43@gmail.com";
        $mail->Password = "nnql lidj odqi odpo";

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;
        $mail->Timeout = 10;

        // Sender
        $mail->setFrom("palfraybenjamin43@gmail.com", "Open Arena Rouen");

        // Receiver
        $mail->addAddress($destinataire, $pseudo);

        // Email content
        $mail->isHTML(true);
        $mail->CharSet = "UTF-8";
        $mail->Subject = "Nouveau tournoi Open Arena Rouen";
        $mail->Body = creerContenuMailTournoi($pseudo, $nom_tournoi);
        $mail->AltBody = "Bonjour $pseudo, un nouveau tournoi Open Arena Rouen vient d'être créé : $nom_tournoi.";

        $mail->send();

        return true;

    } catch (Exception $e) {
        return false;
    }
}

function envoyerMailOpenArena($destinataire, $pseudo, $nom_tournoi)
{
    $mail = new PHPMailer(true);

    try {
        // Local hMailServer SMTP configuration
        $mail->isSMTP();
        $mail->Host = "127.0.0.1";
        $mail->SMTPAuth = true;

        // Open Arena local admin mailbox
        $mail->Username = "admin@open-arena-rouen.test";
        $mail->Password = "admin";

        $mail->SMTPSecure = false;
        $mail->Port = 25;
        $mail->Timeout = 10;

        // Sender
        $mail->setFrom("admin@open-arena-rouen.test", "Open Arena Rouen");

        // Receiver
        $mail->addAddress($destinataire, $pseudo);

        // Email content
        $mail->isHTML(true);
        $mail->CharSet = "UTF-8";
        $mail->Subject = "Nouveau tournoi Open Arena Rouen";
        $mail->Body = creerContenuMailTournoi($pseudo, $nom_tournoi);
        $mail->AltBody = "Bonjour $pseudo, un nouveau tournoi Open Arena Rouen vient d'être créé : $nom_tournoi.";

        $mail->send();

        return true;

    } catch (Exception $e) {
        return false;
    }
}