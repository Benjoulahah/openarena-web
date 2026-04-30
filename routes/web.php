<?php

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

switch ($page) {
    case 'admin':
        require_once __DIR__ . '/../app/admin.php';
        break;

    case 'inscription':
        require_once __DIR__ . '/../app/inscription.php';
        break;
    case 'connexion':
        require_once __DIR__ . '/../app/connexion.php';
        break;
    case 'tournoi':
        require_once __DIR__ . '/../app/tournoi.php';
        break;
    case 'admin_tournoi':
        require_once __DIR__ . '/../app/admin_tournoi.php';
        break;
    case 'traitement_inscription':
        require_once __DIR__ . '/../app/traitement_inscription.php';
        break;
    case 'ajouter_joueurs_tournoi':
    require_once __DIR__ . '/../app/ajouter_joueurs_tournoi.php';
    break;

    case 'traitement_creation_tournoi':
        require_once __DIR__ . '/../app/traitement_creation_tournoi.php';
        break;

    case 'home':
    default:
        require_once __DIR__ . '/../app/home.php';
        break;
}