<?php
require_once __DIR__ . "/../config/database.php";

$sql = "
    SELECT 
    id_utilisateur, 
    pseudo, 
    nom, 
    prenom 
FROM utilisateurs 
ORDER BY pseudo ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un tournoi - Open Arena</title>
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
        <a href="<?= BASE_URL ?>/index.php">Accueil</a>
        <a href="<?= BASE_URL ?>/?page=admin">Admin</a>
        <a href="<?= BASE_URL ?>/?page=connexion">Deconnexion</a>
    </nav>
</header>

<main class="login-page">
    <div class="login-box">
        <h1>Créer un tournoi</h1>

        <form action="<?= BASE_URL ?>/?page=traitement_creation_tournoi" method="post" id="form-tournoi">

            <div class="form-group">
                <label for="nom_tournoi">Nom du tournoi</label>
                <input type="text" id="nom_tournoi" name="nom_tournoi" required>
            </div>

            <div class="form-group">
                <label for="nombre_joueurs">Nombre de joueurs</label>
                <input type="number" id="nombre_joueurs" name="nombre_joueurs" min="2" max="32" required>
            </div>

            <h3>Ajouter des joueurs</h3>

            <div class="form-group">
                <label for="select_joueur">Choisir un joueur</label>

                <div class="player-select-row">
                    <select id="select_joueur">
                        <option value="">-- Sélectionner un joueur --</option>

                        <?php foreach ($utilisateurs as $utilisateur): ?>
                            <option 
                                value="<?= $utilisateur["id_utilisateur"] ?>"
                                data-label="<?= htmlspecialchars($utilisateur["pseudo"] . " - " . $utilisateur["prenom"] . " " . $utilisateur["nom"]) ?>"
                            >
                                <?= htmlspecialchars($utilisateur["pseudo"]) ?>
                                -
                                <?= htmlspecialchars($utilisateur["prenom"]) ?>
                                <?= htmlspecialchars($utilisateur["nom"]) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <button type="button" class="login-btn small-btn" onclick="ajouterJoueur()">
                        Ajouter
                    </button>
                </div>
            </div>

            <h3>Joueurs sélectionnés</h3>

            <div id="liste-joueurs-selectionnes" class="selected-players">
                <p class="empty-message">Aucun joueur sélectionné.</p>
            </div>

            <button type="submit" class="login-btn">
                Créer le tournoi
            </button>

        </form>
    </div>
</main>

<script>
var joueursSelectionnes = [];

function ajouterJoueur() {
    var select = document.getElementById("select_joueur");
    var idJoueur = select.value;

    if (idJoueur === "") {
        alert("Veuillez choisir un joueur.");
        return;
    }

    if (joueursSelectionnes.indexOf(idJoueur) !== -1) {
        alert("Ce joueur est déjà sélectionné.");
        return;
    }

    var nombreJoueurs = document.getElementById("nombre_joueurs").value;

    if (nombreJoueurs !== "" && joueursSelectionnes.length >= parseInt(nombreJoueurs)) {
        alert("Vous avez déjà sélectionné le nombre maximum de joueurs.");
        return;
    }

    joueursSelectionnes.push(idJoueur);
    afficherJoueurs();
}

function retirerJoueur(idJoueur) {
    var nouvelleListe = [];

    for (var i = 0; i < joueursSelectionnes.length; i++) {
        if (joueursSelectionnes[i] !== idJoueur) {
            nouvelleListe.push(joueursSelectionnes[i]);
        }
    }

    joueursSelectionnes = nouvelleListe;
    afficherJoueurs();
}

function afficherJoueurs() {
    var conteneur = document.getElementById("liste-joueurs-selectionnes");
    conteneur.innerHTML = "";

    if (joueursSelectionnes.length === 0) {
        conteneur.innerHTML = '<p class="empty-message">Aucun joueur sélectionné.</p>';
        return;
    }

    for (var i = 0; i < joueursSelectionnes.length; i++) {
        var idJoueur = joueursSelectionnes[i];
        var option = document.querySelector('#select_joueur option[value="' + idJoueur + '"]');
        var label = option.getAttribute("data-label");

        var ligne = document.createElement("div");
        ligne.className = "selected-player-line";

        ligne.innerHTML =
            '<span>' + label + '</span>' +
            '<button type="button" onclick="retirerJoueur(\'' + idJoueur + '\')">Retirer</button>' +
            '<input type="hidden" name="joueurs[]" value="' + idJoueur + '">';

        conteneur.appendChild(ligne);
    }
}

document.getElementById("form-tournoi").addEventListener("submit", function(event) {
    var nombreJoueurs = parseInt(document.getElementById("nombre_joueurs").value);

    if (joueursSelectionnes.length !== nombreJoueurs) {
        alert("Vous devez sélectionner exactement " + nombreJoueurs + " joueurs.");
        event.preventDefault();
    }
});
</script>

</body>
</html>