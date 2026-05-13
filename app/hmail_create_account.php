<?php

require_once __DIR__ . "/../config/hmail_config.php";

function nettoyerPseudoPourMail($pseudo)
{
    $pseudo_mail = strtolower(trim($pseudo));

    // On garde uniquement les lettres et les chiffres
    $pseudo_mail = preg_replace('/[^a-z0-9]/', '', $pseudo_mail);

    return $pseudo_mail;
}

function creerCompteHMail($pseudo, $mot_de_passe_mail)
{
    $pseudo_mail = nettoyerPseudoPourMail($pseudo);

    if ($pseudo_mail == "") {
        return array(
            "success" => false,
            "message" => "Pseudo invalide pour créer l'adresse mail Open Arena."
        );
    }

    if ($mot_de_passe_mail == "") {
        return array(
            "success" => false,
            "message" => "Mot de passe mail invalide."
        );
    }

    // Adresse complète du compte Open Arena
    $adresse_mail = $pseudo_mail . "@" . HMAIL_DOMAIN;

    try {
        $hmail = new COM("hMailServer.Application");

        // Mot de passe administrateur de hMailServer Administrator
        $hmail->Authenticate("Administrator", HMAIL_ADMIN_PASSWORD);

        $domain = $hmail->Domains->ItemByName(HMAIL_DOMAIN);
        $accounts = $domain->Accounts;

        // Vérifier si le compte existe déjà
        for ($i = 0; $i < $accounts->Count; $i++) {
            $account_existant = $accounts->Item($i);

            if (strtolower($account_existant->Address) == strtolower($adresse_mail)) {

                // Si le compte existe déjà, on met à jour son mot de passe
                $account_existant->Password = $mot_de_passe_mail;
                $account_existant->Active = true;
                $account_existant->Save();

                return array(
                    "success" => true,
                    "email" => $adresse_mail,
                    "message" => "Le compte mail existait déjà, mot de passe mis à jour."
                );
            }
        }

        // Création du compte dans hMailServer
        $account = $accounts->Add();

        // hMailServer attend une adresse complète
        $account->Address = $adresse_mail;

        // Même mot de passe que celui saisi à l'inscription
        $account->Password = $mot_de_passe_mail;

        $account->Active = true;
        $account->MaxSize = 100;
        $account->Save();

        return array(
            "success" => true,
            "email" => $adresse_mail,
            "message" => "Compte mail créé avec succès."
        );

    } catch (Exception $e) {
        return array(
            "success" => false,
            "message" => "Erreur hMailServer : " . $e->getMessage()
        );
    }
}