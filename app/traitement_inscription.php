<?php

require_once __DIR__ . "/../config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: " . BASE_URL . "/?page=inscription");
    exit();
}

$nom = isset($_POST["nom"]) ? trim($_POST["nom"]) : "";
$prenom = isset($_POST["prenom"]) ? trim($_POST["prenom"]) : "";
$pseudo = isset($_POST["pseudo"]) ? trim($_POST["pseudo"]) : "";
$email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
$mdp = isset($_POST["mdp"]) ? $_POST["mdp"] : "";
$confirm_mdp = isset($_POST["confirm_mdp"]) ? $_POST["confirm_mdp"] : "";

if ($nom == "" || $prenom == "" || $pseudo == "" || $email == "" || $mdp == "" || $confirm_mdp == "") {
    die("Erreur : tous les champs sont obligatoires.");
}

if ($mdp !== $confirm_mdp) {
    die("Erreur : les mots de passe ne correspondent pas.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Erreur : adresse email invalide.");
}

// Vérifier si le pseudo ou l'email existe déjà
$sql = "SELECT id_utilisateur FROM utilisateurs WHERE pseudo = ? OR email = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute(array($pseudo, $email));

$utilisateur_existant = $stmt->fetch(PDO::FETCH_ASSOC);

if ($utilisateur_existant) {
    die("Erreur : ce pseudo ou cette adresse email existe déjà.");
}

// Sécurisation du mot de passe
$mot_de_passe_hash = password_hash($mdp, PASSWORD_DEFAULT);

// Création de l'utilisateur
$sql = "
    INSERT INTO utilisateurs 
    (nom, prenom, pseudo, email, mot_de_passe, role, total_kill, total_death, total_victoires, total_defaites)
    VALUES (?, ?, ?, ?, ?, 'joueur', 0, 0, 0, 0)
";

$stmt = $pdo->prepare($sql);
$stmt->execute(array($nom, $prenom, $pseudo, $email, $mot_de_passe_hash));

header("Location: " . BASE_URL . "/?page=connexion");
exit();

?>