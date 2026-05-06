<?php

require_once __DIR__ . "/../config/database.php";

$id_tournoi = isset($_GET["id_tournoi"]) ? intval($_GET["id_tournoi"]) : 0;

if ($id_tournoi <= 0) {
    die("Tournoi invalide.");
}

/* Récupérer le tournoi */
$sql = "
    SELECT *
    FROM tournois
    WHERE id_tournoi = ?
";
$stmt = $pdo->prepare($sql);
$stmt->execute(array($id_tournoi));
$tournoi = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$tournoi) {
    die("Tournoi introuvable.");
}

/* Récupérer le classement depuis la table participations */
$sql = "
    SELECT 
        p.id_participation,
        p.id_tournoi,
        p.id_utilisateur,
        p.victoires,
        p.defaites,
        p.qualifie,
        p.elimine,
        u.pseudo
    FROM participations p
    INNER JOIN utilisateurs u
        ON p.id_utilisateur = u.id_utilisateur
    WHERE p.id_tournoi = ?
    ORDER BY 
        p.victoires DESC,
        p.defaites ASC,
        u.pseudo ASC
";

$stmt = $pdo->prepare($sql);
$stmt->execute(array($id_tournoi));
$classement = $stmt->fetchAll(PDO::FETCH_ASSOC);

$vainqueur = null;

if (!empty($classement)) {
    $vainqueur = $classement[0];
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Classement du tournoi - Open Arena</title>
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
        <a href="<?= BASE_URL ?>/?page=accueil">Accueil</a>
    </nav>
</header>

<section class="section">

    <h2>Classement du tournoi : <?= htmlspecialchars($tournoi["nom_tournoi"]) ?></h2>

    <?php if (empty($classement)): ?>

        <p class="empty-message">Aucun joueur trouvé pour ce tournoi.</p>

    <?php else: ?>

        <?php if ($vainqueur): ?>
            <div class="winner-box">
                <h3>Vainqueur actuel</h3>
                <p>
                    <?= htmlspecialchars($vainqueur["pseudo"]) ?>
                    avec <?= intval($vainqueur["victoires"]) ?> victoire(s)
                </p>
            </div>
        <?php endif; ?>

        <table class="classement-table">
            <thead>
                <tr>
                    <th>Position</th>
                    <th>Joueur</th>
                    <th>Victoires</th>
                    <th>Défaites</th>
                    <th>Statut</th>
                </tr>
            </thead>

            <tbody>
                <?php $position = 1; ?>

                <?php foreach ($classement as $joueur): ?>
                    <tr>
                        <td><?= $position ?></td>

                        <td>
                            <?= htmlspecialchars($joueur["pseudo"]) ?>
                        </td>

                        <td>
                            <?= intval($joueur["victoires"]) ?>
                        </td>

                        <td>
                            <?= intval($joueur["defaites"]) ?>
                        </td>

                        <td>
                            <?php if (intval($joueur["qualifie"]) === 1): ?>
                                Qualifié
                            <?php elseif (intval($joueur["elimine"]) === 1): ?>
                                Éliminé
                            <?php else: ?>
                                En cours
                            <?php endif; ?>
                        </td>
                    </tr>

                    <?php $position++; ?>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>

</section>

<footer>
    <p>© 2026 Open Arena - Projet S8</p>
</footer>

</body>
</html>