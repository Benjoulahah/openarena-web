<?php
session_start();

if (!isset($_SESSION["connecte"]) || $_SESSION["connecte"] !== true) {
    header("Location: " . BASE_URL . "/?page=connexion");
    exit();
}

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: " . BASE_URL . "/?page=home");
    exit();
}

require_once __DIR__ . "/../config/database.php";

$sql = "SELECT * FROM tournois ORDER BY id_tournoi DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$tournois = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tournois en cours - Open Arena</title>
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
        <a href="<?= BASE_URL ?>/?page=home">Accueil</a>
        <a href="<?= BASE_URL ?>/?page=admin">Admin</a>
        <a href="<?= BASE_URL ?>/?page=admin_tournoi">Créer tournoi</a>
    </nav>
</header>

<section class="section tournoi-section">
    <h2>Tournois en cours</h2>

    <div class="tournoi-container">

        <?php if (empty($tournois)): ?>
            <p class="empty-message">Aucun tournoi créé pour le moment.</p>
        <?php else: ?>

            <table class="tournoi-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom du tournoi</th>
                        <th>Nombre de joueurs</th>
                        <th>Round actuel</th>
                        <th>Phase</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tournois as $tournoi): ?>
                        <tr>
                            <td><?= htmlspecialchars($tournoi["id_tournoi"]) ?></td>
                            <td><?= htmlspecialchars($tournoi["nom_tournoi"]) ?></td>
                            <td><?= htmlspecialchars($tournoi["nombre_joueurs"]) ?></td>
                            <td><?= htmlspecialchars($tournoi["round_actuel"]) ?></td>
                            <td><?= htmlspecialchars($tournoi["phase"]) ?></td>
                            <td>
                                <a href="<?= BASE_URL ?>/?page=gestion_tournoi&id_tournoi=<?= $tournoi["id_tournoi"] ?>" class="btn-gerer">
                                    Gérer
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