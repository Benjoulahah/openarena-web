<?php
// connexion.php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Open Arena</title>
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
            <a href="index.php">Accueil</a>
            <a href="<?= BASE_URL ?>/?page=admin">Admin</a>
            <a href="<?= BASE_URL ?>/?page=inscription">Inscription</a>
            <a href="<?= BASE_URL ?>/?page=connexion">Connexion</a>
        </nav>
    </header>

    <!-- PAGE CONNEXION -->
    <main class="login-page">

        <div class="login-box">
            <h1>Connexion</h1>

            <form action="#" method="post">

                <div class="form-group">
                    <label for="email">Adresse email</label>
                    <input type="email" id="email" name="email" placeholder="Entrez votre email" required>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" placeholder="Entrez votre mot de passe" required>
                </div>

                <button type="submit" class="login-btn">Se connecter</button>

                <p class="register-link">
                    Pas encore de compte ?
                    <a href="<?= BASE_URL ?>/?page=inscription">Créer un compte</a>
                </p>

            </form>
        </div>

    </main>

</body>
</html>