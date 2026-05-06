<?php

require_once __DIR__ . "/../config/database.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}



/*
    Récupérer tous les tournois terminés
*/
$sql = "
    SELECT 
        t.id_tournoi,
        t.nom_tournoi,
        t.phase,
        t.date_creation,
        COUNT(p.id_participation) AS nombre_participants
    FROM tournois t
    LEFT JOIN participations p
        ON t.id_tournoi = p.id_tournoi
    WHERE t.phase = 'termine'
    GROUP BY 
        t.id_tournoi,
        t.nom_tournoi,
        t.phase,
        t.date_creation
    ORDER BY t.date_creation DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$tournois_archives = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tournois archivés - Open Arena</title>
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
        <a href="<?= BASE_URL ?>/?page=creation_tournoi">Créer tournoi</a>
        <a href="<?= BASE_URL ?>/?page=accueil">Accueil</a>
    </nav>
</header>

<section class="section tournoi-section">

    <h2>Tournois archivés</h2>

    <div class="tournoi-container">

        <?php if (empty($tournois_archives)): ?>

            <p class="empty-message">
                Aucun tournoi terminé pour le moment.
            </p>

        <?php else: ?>

            <table class="tournoi-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom du tournoi</th>
                        <th>Phase</th>
                        <th>Participants</th>
                        <th>Date de création</th>
                        <th>Classement</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($tournois_archives as $tournoi): ?>
                        <tr>
                            <td>
                                <?= intval($tournoi["id_tournoi"]) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($tournoi["nom_tournoi"]) ?>
                            </td>

                            <td>
                                Terminé
                            </td>

                            <td>
                                <?= intval($tournoi["nombre_participants"]) ?>
                            </td>

                            <td>
                                <?php if (!empty($tournoi["date_creation"])): ?>
                                    <?= htmlspecialchars($tournoi["date_creation"]) ?>
                                <?php else: ?>
                                    Non renseignée
                                <?php endif; ?>
                            </td>

                            <td>
                                <a 
                                    class="btn-gerer" 
                                    href="<?= BASE_URL ?>/?page=classement_tournoi&id_tournoi=<?= intval($tournoi["id_tournoi"]) ?>"
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