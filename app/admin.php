<?php
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['start_server'])) {
        $message = "Le serveur a été lancé.";
    }

    if (isset($_POST['stop_server'])) {
        $message = "Le serveur a été arrêté.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Open Arena</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/styless.css">
</head>
<body>

<header>
    <div class="left">
        <div class="logo">
            <img src="<?= BASE_URL ?>/assets/images/logo.png" alt="Open Arena Logo">
        </div>
        <div class="title">
            Open Arena
        </div>
    </div>

    <nav>
        <a href="<?= BASE_URL ?>/?page=home">Accueil</a>
        <a href="#">Connexion</a>
    </nav>
</header>

<section class="section admin-section">
    <h2>Panneau d'administration</h2>
    <p>Gérez ici l’état du serveur de jeu.</p>

    <?php if (!empty($message)): ?>
        <div class="admin-message">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <div class="admin-actions">
        <form method="POST">
            <button type="submit" name="start_server" class="admin-btn start-btn">
                Lancer le serveur
            </button>
        </form>

        <form method="POST">
            <button type="submit" name="stop_server" class="admin-btn stop-btn">
                Fermer le serveur
            </button>
        </form>
    </div>
</section>

<footer>
    <p>© 2026 Open Arena - Projet S8</p>
</footer>

</body>
</html>