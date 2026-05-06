<?php

require_once __DIR__ . "/../config/database.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["id_utilisateur"])) {
    header("Location: " . BASE_URL . "/?page=connexion");
    exit();
}

$id_utilisateur = intval($_SESSION["id_utilisateur"]);

/*
    Récupération des informations de l'utilisateur connecté
*/
$sql = "
    SELECT *
    FROM utilisateurs
    WHERE id_utilisateur = ?
";
$stmt = $pdo->prepare($sql);
$stmt->execute(array($id_utilisateur));
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$utilisateur) {
    die("Utilisateur introuvable.");
}

/*
    Statistiques liées aux participations
*/
$sql = "
    SELECT 
        COUNT(*) AS nombre_tournois,
        COALESCE(SUM(victoires), 0) AS total_victoires_tournois
    FROM participations
    WHERE id_utilisateur = ?
";
$stmt = $pdo->prepare($sql);
$stmt->execute(array($id_utilisateur));
$stats = $stmt->fetch(PDO::FETCH_ASSOC);

$nombre_tournois = intval($stats["nombre_tournois"]);
$total_victoires_tournois = intval($stats["total_victoires_tournois"]);

/*
    Tournois en cours
*/
$sql = "
    SELECT 
        t.id_tournoi,
        t.nom_tournoi,
        t.phase,
        p.id_participation,
        p.victoires
    FROM participations p
    INNER JOIN tournois t
        ON p.id_tournoi = t.id_tournoi
    WHERE p.id_utilisateur = ?
    AND t.phase != 'termine'
    ORDER BY t.id_tournoi DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute(array($id_utilisateur));
$tournois_en_cours = $stmt->fetchAll(PDO::FETCH_ASSOC);

/*
    Tournois terminés
*/
$sql = "
    SELECT 
        t.id_tournoi,
        t.nom_tournoi,
        t.phase,
        p.id_participation,
        p.victoires
    FROM participations p
    INNER JOIN tournois t
        ON p.id_tournoi = t.id_tournoi
    WHERE p.id_utilisateur = ?
    AND t.phase = 'termine'
    ORDER BY t.id_tournoi DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute(array($id_utilisateur));
$tournois_termines = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon compte - Open Arena</title>
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
        <a href="<?= BASE_URL ?>/?page=parametres_compte">Paramètres</a>
        <a href="<?= BASE_URL ?>/?page=deconnexion">Déconnexion</a>
    </nav>
</header>

<section class="section compte-section">

    <h2>Mon compte</h2>

    <div class="compte-grid">

        <div class="compte-card">
            <h3>Profil joueur</h3>

            <p>
                <strong>Pseudo :</strong>
                <?= htmlspecialchars($utilisateur["pseudo"]) ?>
            </p>

            <p>
                <strong>ID utilisateur :</strong>
                <?= intval($utilisateur["id_utilisateur"]) ?>
            </p>

            <div style="text-align:center; margin-top:25px;">
                <a class="admin-btn start-btn" href="<?= BASE_URL ?>/?page=parametres_compte">
                    Modifier mes paramètres
                </a>
            </div>
        </div>

        <div class="compte-card">
            <h3>Statistiques générales</h3>

            <?php if (isset($utilisateur["total_victoires"])): ?>
                <p>
                    <strong>Victoires globales :</strong>
                    <?= intval($utilisateur["total_victoires"]) ?>
                </p>
            <?php endif; ?>

            <?php if (isset($utilisateur["total_defaites"])): ?>
                <p>
                    <strong>Défaites globales :</strong>
                    <?= intval($utilisateur["total_defaites"]) ?>
                </p>
            <?php endif; ?>

            <p>
                <strong>Tournois joués :</strong>
                <?= $nombre_tournois ?>
            </p>
        </div>

    </div>

    <div class="compte-card full-card">
        <h3>Tournois en cours</h3>

        <?php if (empty($tournois_en_cours)): ?>

            <p class="empty-message">
                Vous ne participez actuellement à aucun tournoi en cours.
            </p>

        <?php else: ?>

            <table class="compte-table">
                <thead>
                    <tr>
                        <th>Tournoi</th>
                        <th>Phase</th>
                        <th>Victoires</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($tournois_en_cours as $tournoi): ?>
                        <tr>
                            <td><?= htmlspecialchars($tournoi["nom_tournoi"]) ?></td>
                            <td><?= htmlspecialchars($tournoi["phase"]) ?></td>
                            <td><?= intval($tournoi["victoires"]) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <?php endif; ?>
    </div>

    <div class="compte-card full-card">
        <h3>Tournois terminés</h3>

        <?php if (empty($tournois_termines)): ?>

            <p class="empty-message">
                Vous n'avez encore terminé aucun tournoi.
            </p>

        <?php else: ?>

            <table class="compte-table">
                <thead>
                    <tr>
                        <th>Tournoi</th>
                        <th>Phase</th>
                        <th>Victoires</th>
                        <th>Classement</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($tournois_termines as $tournoi): ?>
                        <tr>
                            <td><?= htmlspecialchars($tournoi["nom_tournoi"]) ?></td>
                            <td><?= htmlspecialchars($tournoi["phase"]) ?></td>
                            <td><?= intval($tournoi["victoires"]) ?></td>
                            <td>
                                <a class="small-btn" href="<?= BASE_URL ?>/?page=classement_tournoi&id_tournoi=<?= intval($tournoi["id_tournoi"]) ?>">
                                    Classement
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