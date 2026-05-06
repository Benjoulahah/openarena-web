<?php

require_once __DIR__ . "/../config/database.php";

$id_tournoi = isset($_GET["id_tournoi"]) ? intval($_GET["id_tournoi"]) : 0;

if ($id_tournoi <= 0) {
    die("Tournoi invalide.");
}

/* Récupérer le tournoi */
$sql = "SELECT * FROM tournois WHERE id_tournoi = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute(array($id_tournoi));
$tournoi = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$tournoi) {
    die("Tournoi introuvable.");
}

/* Récupérer les matchs de phase finale */
$sql = "
    SELECT 
        matchs.id_match,
        matchs.round_num,
        matchs.termine,
        matchs.gagnant_participation_id,
        matchs.id_participation_1,
        matchs.id_participation_2,
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
    AND matchs.type_match = 'final'
    ORDER BY matchs.round_num ASC, matchs.id_match ASC
";
$stmt = $pdo->prepare($sql);
$stmt->execute(array($id_tournoi));
$matchs = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* Regrouper les matchs par round */
$rounds = array();

foreach ($matchs as $match) {
    $round_num = $match["round_num"];

    if (!isset($rounds[$round_num])) {
        $rounds[$round_num] = array();
    }

    $rounds[$round_num][] = $match;
}

function nomRoundFinal($nb_matchs) {
    if ($nb_matchs == 8) {
        return "8èmes de finale";
    } elseif ($nb_matchs == 4) {
        return "Quarts de finale";
    } elseif ($nb_matchs == 2) {
        return "Demi-finales";
    } elseif ($nb_matchs == 1) {
        return "Finale";
    }

    return "Phase finale";
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Phase finale - Open Arena</title>
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
        <a href="<?= BASE_URL ?>/?page=admin">Admin</a>
        <a href="<?= BASE_URL ?>/?page=gestion_tournoi&id_tournoi=<?= $id_tournoi ?>">Retour tournoi</a>
    </nav>
</header>

<section class="section">
    <h2>Phase finale : <?= htmlspecialchars($tournoi["nom_tournoi"]) ?></h2>

    <?php if (empty($matchs)): ?>

        <p style="text-align:center;">Aucun bracket final créé.</p>

    <?php else: ?>

        <form action="<?= BASE_URL ?>/?page=traitement_scores_final" method="post">
            <input type="hidden" name="id_tournoi" value="<?= $id_tournoi ?>">

            <div class="bracket">

                <?php foreach ($rounds as $round_num => $matchs_round): ?>
                    <div class="bracket-round">
                        <h3><?= nomRoundFinal(count($matchs_round)) ?></h3>

                        <?php foreach ($matchs_round as $match): ?>
                            <div class="card">
                                <h3>
                                    <?= htmlspecialchars($match["pseudo_1"]) ?>
                                    vs
                                    <?= htmlspecialchars($match["pseudo_2"]) ?>
                                </h3>

                                <input type="hidden" name="id_match[]" value="<?= $match["id_match"] ?>">

                                <div class="form-group">
                                    <label>Gagnant</label>

                                    <label>
                                        <input 
                                            type="radio"
                                            name="gagnant[<?= $match["id_match"] ?>]"
                                            value="1"
                                            <?php if ($match["gagnant_participation_id"] == $match["id_participation_1"]) echo "checked"; ?>
                                            <?php if ($match["termine"] == 1) echo "disabled"; ?>
                                        >
                                        <?= htmlspecialchars($match["pseudo_1"]) ?>
                                    </label>

                                    <label>
                                        <input 
                                            type="radio"
                                            name="gagnant[<?= $match["id_match"] ?>]"
                                            value="2"
                                            <?php if ($match["gagnant_participation_id"] == $match["id_participation_2"]) echo "checked"; ?>
                                            <?php if ($match["termine"] == 1) echo "disabled"; ?>
                                        >
                                        <?= htmlspecialchars($match["pseudo_2"]) ?>
                                    </label>
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
                <?php endforeach; ?>

            </div>

            <div style="text-align:center; margin-top:30px;">
                <button type="submit" class="admin-btn start-btn">
                    Enregistrer les gagnants
                </button>
            </div>
        </form>

    <?php endif; ?>

</section>

<footer>
    <p>© 2026 Open Arena - Projet S8</p>
</footer>

</body>
</html>