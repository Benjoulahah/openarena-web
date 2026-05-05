<?php

require_once __DIR__ . "/../config/database.php";

$id_tournoi = isset($_GET["id_tournoi"]) ? intval($_GET["id_tournoi"]) : 0;

if ($id_tournoi <= 0) {
    die("Tournoi invalide.");
}

$sql = "SELECT * FROM tournois WHERE id_tournoi = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute(array($id_tournoi));
$tournoi = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$tournoi) {
    die("Tournoi introuvable.");
}

$round_actuel = intval($tournoi["round_actuel"]);

$sql = "
    SELECT 
        matchs.id_match,
        matchs.score_1,
        matchs.score_2,
        matchs.termine,
        u1.pseudo AS pseudo_1,
        u2.pseudo AS pseudo_2
    FROM matchs
    INNER JOIN participations p1 
        ON matchs.id_participation_1 = p1.id_participation
    INNER JOIN participations p2 
        ON matchs.id_participation_2 = p2.id_participation
    INNER JOIN utilisateurs u1 
        ON p1.id_utilisateur = u1.id_utilisateur
    INNER JOIN utilisateurs u2 
        ON p2.id_utilisateur = u2.id_utilisateur
    WHERE matchs.id_tournoi = ?
    AND matchs.round_num = ?
    AND matchs.type_match = 'swiss'
    ORDER BY matchs.id_match ASC
";
$stmt = $pdo->prepare($sql);
$stmt->execute(array($id_tournoi, $round_actuel));
$matchs = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql = "
    SELECT COUNT(*)
    FROM matchs
    WHERE id_tournoi = ?
    AND round_num = ?
    AND type_match = 'swiss'
    AND termine = 0
";
$stmt = $pdo->prepare($sql);
$stmt->execute(array($id_tournoi, $round_actuel));
$matchs_non_termines = $stmt->fetchColumn();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Matchs du tournoi - Open Arena</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/styless.css">
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
        <a href="<?= BASE_URL ?>/?page=admin">Admin</a>
        <a href="<?= BASE_URL ?>/?page=gestion_tournoi&id_tournoi=<?= $id_tournoi ?>">Retour tournoi</a>
    </nav>
</header>

<section class="section">
    <h2><?= htmlspecialchars($tournoi["nom_tournoi"]) ?> - Round <?= $round_actuel ?></h2>

    <?php if (empty($matchs)): ?>
        <p style="text-align:center;">Aucun match pour ce round.</p>
    <?php else: ?>

        <form action="<?= BASE_URL ?>/?page=traitement_scores_swiss" method="post">
            <input type="hidden" name="id_tournoi" value="<?= $id_tournoi ?>">

            <div class="cards">
                <?php foreach ($matchs as $match): ?>
                    <div class="card">
                        <h3>
                            <?= htmlspecialchars($match["pseudo_1"]) ?>
                            vs
                            <?= htmlspecialchars($match["pseudo_2"]) ?>
                        </h3>

                        <input type="hidden" name="id_match[]" value="<?= $match["id_match"] ?>">

                        <div class="form-group">
                            <label><?= htmlspecialchars($match["pseudo_1"]) ?></label>
                            <input 
                                type="number" 
                                name="score_1[]" 
                                min="0"
                                value="<?= htmlspecialchars($match["score_1"]) ?>"
                                <?php if ($match["termine"] == 1) echo "readonly"; ?>
                            >
                        </div>

                        <div class="form-group">
                            <label><?= htmlspecialchars($match["pseudo_2"]) ?></label>
                            <input 
                                type="number" 
                                name="score_2[]" 
                                min="0"
                                value="<?= htmlspecialchars($match["score_2"]) ?>"
                                <?php if ($match["termine"] == 1) echo "readonly"; ?>
                            >
                        </div>

                        <p>
                            Statut :
                            <?php if ($match["termine"] == 1): ?>
                                Terminé
                            <?php else: ?>
                                En attente
                            <?php endif; ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>

            <div style="text-align:center; margin-top:30px;">
                <button type="submit" class="admin-btn start-btn">
                    Enregistrer les scores
                </button>
            </div>
        </form>

        <?php if ($matchs_non_termines == 0): ?>
            <div style="text-align:center; margin-top:25px;">
                <a href="<?= BASE_URL ?>/?page=creer_round_swiss&id_tournoi=<?= $id_tournoi ?>" class="admin-btn stop-btn">
                    Créer le round suivant
                </a>
            </div>
        <?php endif; ?>

    <?php endif; ?>

</section>

<footer>
    <p>© 2026 Open Arena - Projet S8</p>
</footer>

</body>
</html>