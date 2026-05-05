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
    case 'creation_tournoi':
        require_once __DIR__ . '/../app/creation_tournoi.php';
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
    case 'traitement_connexion':
        require_once __DIR__ . '/../app/traitement_connexion.php';
        break;
    case 'tournois_en_cours':
    require_once __DIR__ . '/../app/tournois_en_cours.php';
    break;

    case 'gestion_tournoi':
        require_once __DIR__ . '/../app/gestion_tournoi.php';
        break;
    
    case 'creer_round_swiss':
        require_once __DIR__ . '/../app/creer_round_swiss.php';
        break;
    case 'matchs_tournoi':
        require_once __DIR__ . '/../app/matchs_tournoi.php';
        break;
    case 'traitement_scores_swiss':
        require_once __DIR__ . '/../app/traitement_scores_swiss.php';
        break;
    case 'creer_bracket_final':
        require_once __DIR__ . '/../app/creer_bracket_final.php';
        break;

    case 'bracket_final':
        require_once __DIR__ . '/../app/bracket_final.php';
        break;

    case 'traitement_scores_final':
        require_once __DIR__ . '/../app/traitement_scores_final.php';
        break;

    case 'home':
    default:
        require_once __DIR__ . '/../app/home.php';
        break;
}