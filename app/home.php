<?php
$modes = require __DIR__ . '/modes.php';
$maps = require __DIR__ . '/maps.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open Arena</title>
    <link rel="stylesheet" href="/Projet S8/assets/styles.css">
</head>

<body>

    <!-- HEADER -->
    <header>
        <div class="left">
        <div class="logo">
            <img src="/Projet S8/assets/images/logo.png" alt="Open Arena Logo">
        </div>
        <div class="title">
            Open Arena
        </div>
    </div>
        <nav>
            <a href="<?= BASE_URL ?>/?page=admin">Admin</a>
            <a href="<?= BASE_URL ?>/?page=inscription">Inscription</a>
            <a href="<?= BASE_URL ?>/?page=connexion">Connexion</a>
        </nav>
    </header>

    <!-- HERO -->
    <section class="hero">
        <h1>Bienvenue sur Open Arena</h1>
        <p>Affrontez vos adversaires dans des combats rapides et stratégiques.</p>
        <button>Jouer maintenant</button>
    </section>

    <!-- CARTES -->
    <section class="section">
    <h2>Les cartes</h2>

    <div class="maps-slider">
        <?php foreach ($maps as $map): ?>
            <div class="map-card">
                <div class="map-image">
                    <img src="<?= BASE_URL ?>/assets/images/maps/<?= $map['image'] ?>" alt="<?= $map['name'] ?>">
                </div>
                <h3><?= $map['name'] ?></h3>
                <p><?= $map['description'] ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</section>

    <!-- MODES -->
    <section class="section">
    <h2>Modes de jeu</h2>

    <div class="cards">
        <?php foreach ($modes as $mode): ?>
            <div class="card">
                <div class="card-image">
                    <img src="<?= BASE_URL ?>/assets/images/modes/<?= $mode['image'] ?>" alt="<?= $mode['name'] ?>">
                </div>
                <h3><?= $mode['name'] ?></h3>
                <p><?= $mode['description'] ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</section>

    <!-- FOOTER -->
    <footer>
        <p>© 2026 Open Arena - Projet S8</p>
    </footer>

</body>
</html>