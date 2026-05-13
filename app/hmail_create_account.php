<?php

require_once __DIR__ . "/../config/hmail_config.php";

function nettoyerPseudoPourMail($pseudo)
{
    $pseudo_mail = strtolower(trim($pseudo));
    $pseudo_mail = preg_replace('/[^a-z0-9]/', '', $pseudo_mail);

    return $pseudo_mail;
}

function genererMotDePasseMail($longueur = 12)
{
    $caracteres = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $mot_de_passe = "";

    for ($i = 0; $i < $longueur; $i++) {
        $mot_de_passe .= $caracteres[random_int(0, strlen($caracteres) - 1)];
    }

    return $mot_de_passe;
}

function creerCompteHMail($pseudo)
{
    $pseudo_mail = nettoyerPseudoPourMail($pseudo);

    if ($pseudo_mail == "") {
        return array(
            "success" => false,
            "message" => "Pseudo invalide pour créer l'adresse mail Open Arena."
        );
    }

    $adresse_mail = $pseudo_mail . "@" . HMAIL_DOMAIN;
    $mot_de_passe_mail = genererMotDePasseMail();

    try {
        $hmail = new COM("hMailServer.Application");
        $hmail->Authenticate("Administrator", HMAIL_ADMIN_PASSWORD);

        $domain = $hmail->Domains->ItemByName(HMAIL_DOMAIN);
        $accounts = $domain->Accounts;

        for ($i = 0; $i < $accounts->Count; $i++) {
            $account_existant = $accounts->Item($i);

            if (strtolower($account_existant->Address) == strtolower($adresse_mail)) {
                return array(
                    "success" => true,
                    "email" => $adresse_mail,
                    "password" => null,
                    "message" => "Le compte mail existe déjà."
                );
            }
        }

        $account = $accounts->Add();
        $account->Address = $adresse_mail;
        $account->Password = $mot_de_passe_mail;
        $account->Active = true;
        $account->MaxSize = 100;
        $account->Save();

        return array(
            "success" => true,
            "email" => $adresse_mail,
            "password" => $mot_de_passe_mail,
            "message" => "Compte mail créé avec succès."
        );

    } catch (Exception $e) {
        return array(
            "success" => false,
            "message" => "Erreur hMailServer : " . $e->getMessage()
        );
    }
}