<?php

require_once __DIR__ . "/../config/database.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["id_utilisateur"])) {
    header("Location: " . BASE_URL . "/?page=connexion");
    exit();
}

$id_utilisateur = intval($_SESSION["id_utilisateur"]);
$message = "";
$erreur = "";

/*
    Récupération de l'utilisateur
*/
$sql = "
    SELECT *
    FROM utilisateurs
    WHERE id_utilisateur = ?
";
$stmt = $pdo->prepare($sql);
$stmt->execute(array($id_utilisateur));
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$utilisateur) {
    die("Utilisateur introuvable.");
}

/*
    Modification des informations du compte
*/
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["modifier_infos"])) {

    $nouveau_pseudo = isset($_POST["pseudo"]) ? trim($_POST["pseudo"]) : "";
    $nouveau_mdp = isset($_POST["nouveau_mdp"]) ? trim($_POST["nouveau_mdp"]) : "";
    $confirmation_mdp = isset($_POST["confirmation_mdp"]) ? trim($_POST["confirmation_mdp"]) : "";

    if ($nouveau_pseudo === "") {
        $erreur = "Le pseudo ne peut pas être vide.";
    } else {

        /*
            Vérifier si le pseudo est déjà utilisé par un autre utilisateur
        */
        $sql = "
            SELECT COUNT(*)
            FROM utilisateurs
            WHERE pseudo = ?
            AND id_utilisateur != ?
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($nouveau_pseudo, $id_utilisateur));
        $pseudo_existe = $stmt->fetchColumn();

        if ($pseudo_existe > 0) {
            $erreur = "Ce pseudo est déjà utilisé.";
        } else {

            /*
                Si le mot de passe est vide, on modifie seulement le pseudo.
                Sinon, on modifie le pseudo et le mot de passe.
            */
            if ($nouveau_mdp !== "") {

                if ($nouveau_mdp !== $confirmation_mdp) {
                    $erreur = "Les deux mots de passe ne correspondent pas.";
                } else {
                    $hash = password_hash($nouveau_mdp, PASSWORD_DEFAULT);

                    $sql = "
                        UPDATE utilisateurs
                        SET pseudo = ?, mot_de_passe = ?
                        WHERE id_utilisateur = ?
                    ";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(array($nouveau_pseudo, $hash, $id_utilisateur));

                    $_SESSION["pseudo"] = $nouveau_pseudo;
                    $message = "Vos informations ont bien été modifiées.";
                }

            } else {

                $sql = "
                    UPDATE utilisateurs
                    SET pseudo = ?
                    WHERE id_utilisateur = ?
                ";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array($nouveau_pseudo, $id_utilisateur));

                $_SESSION["pseudo"] = $nouveau_pseudo;
                $message = "Votre pseudo a bien été modifié.";
            }
        }
    }

    /*
        On recharge les informations utilisateur après modification.
    */
    $sql = "
        SELECT *
        FROM utilisateurs
        WHERE id_utilisateur = ?
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($id_utilisateur));
    $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);
}

/*
    Modification des touches
*/
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["modifier_touches"])) {

    $touche_avancer = isset($_POST["touche_avancer"]) ? trim($_POST["touche_avancer"]) : "Z";
    $touche_reculer = isset($_POST["touche_reculer"]) ? trim($_POST["touche_reculer"]) : "S";
    $touche_gauche = isset($_POST["touche_gauche"]) ? trim($_POST["touche_gauche"]) : "Q";
    $touche_droite = isset($_POST["touche_droite"]) ? trim($_POST["touche_droite"]) : "D";
    $touche_sauter = isset($_POST["touche_sauter"]) ? trim($_POST["touche_sauter"]) : "ESPACE";
    $touche_tirer = isset($_POST["touche_tirer"]) ? trim($_POST["touche_tirer"]) : "CLIC GAUCHE";
    $touche_recharger = isset($_POST["touche_recharger"]) ? trim($_POST["touche_recharger"]) : "R";
    $touche_sprint = isset($_POST["touche_sprint"]) ? trim($_POST["touche_sprint"]) : "SHIFT";

    /*
        Si un champ est vide, on remet une valeur par défaut
    */
    if ($touche_avancer === "") {
        $touche_avancer = "Z";
    }

    if ($touche_reculer === "") {
        $touche_reculer = "S";
    }

    if ($touche_gauche === "") {
        $touche_gauche = "Q";
    }

    if ($touche_droite === "") {
        $touche_droite = "D";
    }

    if ($touche_sauter === "") {
        $touche_sauter = "ESPACE";
    }

    if ($touche_tirer === "") {
        $touche_tirer = "CLIC GAUCHE";
    }

    if ($touche_recharger === "") {
        $touche_recharger = "R";
    }

    if ($touche_sprint === "") {
        $touche_sprint = "SHIFT";
    }

    /*
        Vérifier si l'utilisateur a déjà une configuration de touches
    */
    $sql = "
        SELECT COUNT(*)
        FROM touches_utilisateur
        WHERE id_utilisateur = ?
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($id_utilisateur));
    $touches_existent = $stmt->fetchColumn();

    if ($touches_existent > 0) {

        $sql = "
            UPDATE touches_utilisateur
            SET
                touche_avancer = ?,
                touche_reculer = ?,
                touche_gauche = ?,
                touche_droite = ?,
                touche_sauter = ?,
                touche_tirer = ?,
                touche_recharger = ?,
                touche_sprint = ?
            WHERE id_utilisateur = ?
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            $touche_avancer,
            $touche_reculer,
            $touche_gauche,
            $touche_droite,
            $touche_sauter,
            $touche_tirer,
            $touche_recharger,
            $touche_sprint,
            $id_utilisateur
        ));

    } else {

        $sql = "
            INSERT INTO touches_utilisateur
            (
                id_utilisateur,
                touche_avancer,
                touche_reculer,
                touche_gauche,
                touche_droite,
                touche_sauter,
                touche_tirer,
                touche_recharger,
                touche_sprint
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            $id_utilisateur,
            $touche_avancer,
            $touche_reculer,
            $touche_gauche,
            $touche_droite,
            $touche_sauter,
            $touche_tirer,
            $touche_recharger,
            $touche_sprint
        ));
    }

    $message = "Vos touches ont bien été enregistrées.";
}

/*
    Récupération des touches de l'utilisateur
*/
$sql = "
    SELECT *
    FROM touches_utilisateur
    WHERE id_utilisateur = ?
";
$stmt = $pdo->prepare($sql);
$stmt->execute(array($id_utilisateur));
$touches = $stmt->fetch(PDO::FETCH_ASSOC);

/*
    Valeurs par défaut si l'utilisateur n'a encore rien enregistré
*/
if (!$touches) {
    $touches = array(
        "touche_avancer" => "Z",
        "touche_reculer" => "S",
        "touche_gauche" => "Q",
        "touche_droite" => "D",
        "touche_sauter" => "ESPACE",
        "touche_tirer" => "CLIC GAUCHE",
        "touche_recharger" => "R",
        "touche_sprint" => "SHIFT"
    );
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paramètres du compte - Open Arena</title>
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
        <a href="<?= BASE_URL ?>/?page=compte_utilisateur">Mon compte</a>
        <a href="<?= BASE_URL ?>/?page=accueil">Accueil</a>
        <a href="<?= BASE_URL ?>/?page=deconnexion">Déconnexion</a>
    </nav>
</header>

<section class="section compte-section">

    <h2>Paramètres du compte</h2>

    <?php if (!empty($message)): ?>
        <p class="success-message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <?php if (!empty($erreur)): ?>
        <p class="error-message"><?= htmlspecialchars($erreur) ?></p>
    <?php endif; ?>

    <div class="compte-card full-card">
        <h3>Informations utilisateur</h3>

        <form method="post" class="parametres-form">

            <div class="form-group">
                <label>Pseudo</label>
                <input 
                    type="text" 
                    name="pseudo" 
                    value="<?= htmlspecialchars($utilisateur["pseudo"]) ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label>Nouveau mot de passe</label>
                <input 
                    type="password" 
                    name="nouveau_mdp"
                    placeholder="Laisser vide pour ne pas modifier"
                >
            </div>

            <div class="form-group">
                <label>Confirmer le nouveau mot de passe</label>
                <input 
                    type="password" 
                    name="confirmation_mdp"
                    placeholder="Confirmer le nouveau mot de passe"
                >
            </div>

            <button type="submit" name="modifier_infos" class="admin-btn start-btn">
                Enregistrer mes informations
            </button>

        </form>
    </div>

    <div class="compte-card full-card">
        <h3>Touches du jeu</h3>

        <form method="post" class="touches-form">

            <div class="touches-grid">

                <div class="form-group">
                    <label>Avancer</label>
                    <input 
                        type="text" 
                        name="touche_avancer" 
                        value="<?= htmlspecialchars($touches["touche_avancer"]) ?>"
                    >
                </div>

                <div class="form-group">
                    <label>Reculer</label>
                    <input 
                        type="text" 
                        name="touche_reculer" 
                        value="<?= htmlspecialchars($touches["touche_reculer"]) ?>"
                    >
                </div>

                <div class="form-group">
                    <label>Aller à gauche</label>
                    <input 
                        type="text" 
                        name="touche_gauche" 
                        value="<?= htmlspecialchars($touches["touche_gauche"]) ?>"
                    >
                </div>

                <div class="form-group">
                    <label>Aller à droite</label>
                    <input 
                        type="text" 
                        name="touche_droite" 
                        value="<?= htmlspecialchars($touches["touche_droite"]) ?>"
                    >
                </div>

                <div class="form-group">
                    <label>Sauter</label>
                    <input 
                        type="text" 
                        name="touche_sauter" 
                        value="<?= htmlspecialchars($touches["touche_sauter"]) ?>"
                    >
                </div>

                <div class="form-group">
                    <label>Tirer</label>
                    <input 
                        type="text" 
                        name="touche_tirer" 
                        value="<?= htmlspecialchars($touches["touche_tirer"]) ?>"
                    >
                </div>

                <div class="form-group">
                    <label>Recharger</label>
                    <input 
                        type="text" 
                        name="touche_recharger" 
                        value="<?= htmlspecialchars($touches["touche_recharger"]) ?>"
                    >
                </div>

                <div class="form-group">
                    <label>Sprint</label>
                    <input 
                        type="text" 
                        name="touche_sprint" 
                        value="<?= htmlspecialchars($touches["touche_sprint"]) ?>"
                    >
                </div>

            </div>

            <button type="submit" name="modifier_touches" class="admin-btn start-btn">
                Enregistrer mes touches
            </button>

        </form>
    </div>

</section>

<footer>
    <p>© 2026 Open Arena - Projet S8</p>
</footer>

</body>
</html>