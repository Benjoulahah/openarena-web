<?php

require_once __DIR__ . "/../config/database.php";

date_default_timezone_set("Europe/Paris");
/*
    Récupérer les tournois en cours
*/
$sql = "
    SELECT *
    FROM tournois
    WHERE phase != 'termine'
    ORDER BY id_tournoi DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$tournois_en_cours = $stmt->fetchAll(PDO::FETCH_ASSOC);

/*
    Récupérer les tournois terminés
*/
$sql = "
    SELECT *
    FROM tournois
    WHERE phase = 'termine'
    ORDER BY id_tournoi DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$tournois_termines = $stmt->fetchAll(PDO::FETCH_ASSOC);

function afficherPhase($phase) {
    if ($phase == "swiss") {
        return "Phase Swiss";
    } elseif ($phase == "swiss_termine") {
        return "Swiss terminé";
    } elseif ($phase == "finale") {
        return "Phase finale";
    } elseif ($phase == "termine") {
        return "Terminé";
    }

    return $phase;
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tournois - Open Arena</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/styles.css">
</head>
<body>

<header>
    <div class="left">
        <div class="logo">
            <img src="<?= BASE_URL ?>/assets/images/logo.png" alt="Open Arena Logo">
        </div>
        <div class="title">Open Arena</div>
    </div>

    <nav>
        <a href="<?= BASE_URL ?>/?page=home">Accueil</a>
        <a href="<?= BASE_URL ?>/?page=connexion">Connexion</a>
        <a href="<?= BASE_URL ?>/?page=inscription">Inscription</a>
    </nav>
</header>

<section class="section tournoi-section">

    <h2>Tournois</h2>

    <div class="tournoi-container">

        <h3 class="tournoi-subtitle">Tournois en cours</h3>

        <?php if (empty($tournois_en_cours)): ?>

            <p class="empty-message">Aucun tournoi en cours pour le moment.</p>

        <?php else: ?>

            <table class="tournoi-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom du tournoi</th>
                        <th>Joueurs</th>
                        <th>Round actuel</th>
                        <th>Phase</th>
                        <th>Date de création</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($tournois_en_cours as $tournoi): ?>
                        <tr>
                            <td><?= htmlspecialchars($tournoi["id_tournoi"]) ?></td>

                            <td><?= htmlspecialchars($tournoi["nom_tournoi"]) ?></td>

                            <td>
                                <?php if (isset($tournoi["nombre_joueurs"])): ?>
                                    <?= htmlspecialchars($tournoi["nombre_joueurs"]) ?>
                                <?php else: ?>
                                    Non renseigné
                                <?php endif; ?>
                            </td>

                            <td>
                                <?php if (isset($tournoi["round_actuel"])): ?>
                                    <?= htmlspecialchars($tournoi["round_actuel"]) ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>

                            <td><?= htmlspecialchars(afficherPhase($tournoi["phase"])) ?></td>

                            <td>
                                <?php if (isset($tournoi["date_creation"]) && !empty($tournoi["date_creation"])): ?>
                                    <?= date("d/m/Y H:i", strtotime($tournoi["date_creation"])) ?>
                                <?php else: ?>
                                    Non renseignée
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <?php endif; ?>

    </div>

    <div class="tournoi-container">

        <h3 class="tournoi-subtitle">Tournois terminés</h3>

        <?php if (empty($tournois_termines)): ?>

            <p class="empty-message">Aucun tournoi terminé pour le moment.</p>

        <?php else: ?>

            <table class="tournoi-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom du tournoi</th>
                        <th>Joueurs</th>
                        <th>Phase</th>
                        <th>Date de création</th>
                        <th>Classement</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($tournois_termines as $tournoi): ?>
                        <tr>
                            <td><?= htmlspecialchars($tournoi["id_tournoi"]) ?></td>

                            <td><?= htmlspecialchars($tournoi["nom_tournoi"]) ?></td>

                            <td>
                                <?php if (isset($tournoi["nombre_joueurs"])): ?>
                                    <?= htmlspecialchars($tournoi["nombre_joueurs"]) ?>
                                <?php else: ?>
                                    Non renseigné
                                <?php endif; ?>
                            </td>

                            <td><?= htmlspecialchars(afficherPhase($tournoi["phase"])) ?></td>

                            <td>
                                <?php if (isset($tournoi["date_creation"]) && !empty($tournoi["date_creation"])): ?>
                                    <?= date("d/m/Y H:i", strtotime($tournoi["date_creation"])) ?>
                                <?php else: ?>
                                    Non renseignée
                                <?php endif; ?>
                            </td>

                            <td>
                                <a 
                                    href="<?= BASE_URL ?>/?page=classement_tournoi&id_tournoi=<?= intval($tournoi["id_tournoi"]) ?>" 
                                    class="btn-gerer"
                                >
                                    Voir
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <?php endif; ?>

    </div>

</section>

<footer>
    <p>© 2026 Open Arena - Projet S8</p>
</footer>

</body>
</html>