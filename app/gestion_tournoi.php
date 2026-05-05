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

$sql = "
    SELECT 
        participations.id_participation,
        participations.victoires,
        participations.defaites,
        participations.qualifie,
        participations.elimine,
        utilisateurs.pseudo,
        utilisateurs.nom,
        utilisateurs.prenom
    FROM participations
    INNER JOIN utilisateurs 
        ON participations.id_utilisateur = utilisateurs.id_utilisateur
    WHERE participations.id_tournoi = ?
    ORDER BY utilisateurs.pseudo ASC
";

$stmt = $pdo->prepare($sql);
$stmt->execute(array($id_tournoi));
$participants = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql = "
    SELECT COUNT(*) 
    FROM matchs 
    WHERE id_tournoi = ?
";
$stmt = $pdo->prepare($sql);
$stmt->execute(array($id_tournoi));
$nombre_matchs = $stmt->fetchColumn();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion tournoi - Open Arena</title>
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
        <a href="<?= BASE_URL ?>/index.php">Accueil</a>
        <a href="<?= BASE_URL ?>/?page=admin">Admin</a>
        <a href="<?= BASE_URL ?>/?page=admin_tournoi">Créer tournoi</a>
    </nav>
</header>

<section class="section">
    <h2>Gestion du tournoi : <?= htmlspecialchars($tournoi["nom_tournoi"]) ?></h2>

    <p style="text-align:center;">
        Nombre de joueurs : <?= htmlspecialchars($tournoi["nombre_joueurs"]) ?> |
        Round actuel : <?= htmlspecialchars($tournoi["round_actuel"]) ?> |
        Phase : <?= htmlspecialchars($tournoi["phase"]) ?>
    </p>

    <h2>Participants</h2>

    <table class="classement-table">
        <tr>
            <th>Pseudo</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Victoires</th>
            <th>Défaites</th>
            <th>Statut</th>
        </tr>

        <?php foreach ($participants as $participant): ?>
            <tr>
                <td><?= htmlspecialchars($participant["pseudo"]) ?></td>
                <td><?= htmlspecialchars($participant["nom"]) ?></td>
                <td><?= htmlspecialchars($participant["prenom"]) ?></td>
                <td><?= htmlspecialchars($participant["victoires"]) ?></td>
                <td><?= htmlspecialchars($participant["defaites"]) ?></td>
                <td>
                    <?php if ($participant["qualifie"] == 1): ?>
                        Qualifié
                    <?php elseif ($participant["elimine"] == 1): ?>
                        Éliminé
                    <?php else: ?>
                        En cours
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <div style="text-align:center; margin-top:30px;">
        <?php if ($nombre_matchs == 0): ?>
            <a href="<?= BASE_URL ?>/?page=creer_round_swiss&id_tournoi=<?= $id_tournoi ?>" class="admin-btn start-btn">
                Créer le round 1
            </a>
        <?php else: ?>
            <a href="<?= BASE_URL ?>/?page=matchs_tournoi&id_tournoi=<?= $id_tournoi ?>" class="admin-btn start-btn">
                Voir les matchs
            </a>
        <?php endif; ?>
        <div style="text-align:center; margin-top:30px;">
            <a href="<?= BASE_URL ?>/?page=creer_bracket_final&id_tournoi=<?= $id_tournoi ?>" class="admin-btn start-btn">
                Créer la phase finale
            </a>
        </div>
    </div>

</section>

<footer>
    <p>© 2026 Open Arena - Projet S8</p>
</footer>

</body>
</html>