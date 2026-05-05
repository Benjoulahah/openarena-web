<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Open Arena</title>
    <link rel="stylesheet" href="/Projet S8/assets/styless.css">
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
            <a href="<?= BASE_URL ?>/?page=accueil">Accueil</a>
            <a href="<?= BASE_URL ?>/?page=connexion">Connexion</a>
        </nav>
    </header>

    
    <div class="form-container">
        <h2>Créer un compte</h2>

        <form action="<?= BASE_URL ?>/?page=traitement_inscription" method="POST">
            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" required>
            </div>

            <div class="form-group">
                <label for="prenom">Prénom</label>
                <input type="text" id="prenom" name="prenom" required>
            </div>

            <div class="form-group">
                <label for="pseudo">Pseudo</label>
                <input type="text" id="pseudo" name="pseudo" required>
            </div>

            <div class="form-group">
                <label for="email">Adresse mail</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="mdp">Mot de passe</label>
                <input type="password" id="mdp" name="mdp" required>
            </div>

            <div class="form-group">
                <label for="confirm_mdp">Confirmer le mot de passe</label>
                <input type="password" id="confirm_mdp" name="confirm_mdp" required>
            </div>

            <button type="submit" class="btn-submit">S'inscrire</button>
        </form>

        <div class="login-link">
            <p>Déjà un compte ? <a href="<?= BASE_URL ?>/?page=connexion">Se connecter</a></p>
        </div>
    </div>

</body>
</html>