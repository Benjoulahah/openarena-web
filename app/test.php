<?php

try {
    $hmail = new COM("hMailServer.Application");

    // Mot de passe utilisé pour ouvrir hMailServer Administrator
    $hmail->Authenticate("admin", "admin");

    echo "Connexion à hMailServer réussie.";

} catch (Exception $e) {
    echo "Erreur hMailServer : " . $e->getMessage();
}