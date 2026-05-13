<?php

session_start();

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../config/ldap_auth.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: " . BASE_URL . "/?page=connexion");
    exit();
}

$pseudo = isset($_POST["pseudo"]) ? trim($_POST["pseudo"]) : "";
$password = isset($_POST["password"]) ? $_POST["password"] : "";

if ($pseudo == "" || $password == "") {
    die("Erreur : pseudo ou mot de passe manquant.");
}

$sql = "SELECT * FROM utilisateurs WHERE pseudo = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute(array($pseudo));

$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$utilisateur) {
    die("Erreur : utilisateur introuvable.");
}

if (!password_verify($password, $utilisateur["mot_de_passe"])) {
    die("Erreur : mot de passe incorrect.");
}

/*$ad_link = connect_ad();
if (!$ad_link) {
    die("Impossible de contacter le serveur Active Directory.");
}
$user_principal = $pseudo . "@openarena.local";
$ad_bind = @ldap_bind($ad_link, $user_principal, $password);
if (!$ad_bind) {
    die("Mot de passe Active Directory incorrect ou compte désactivé.");
}

$base_dn = "DC=openarena,DC=local";
$filter = "(sAMAccountName=$pseudo)";
$search = ldap_search($ad_link, $base_dn, $filter);
$info = ldap_get_entries($ad_link, $search);

if ($info["count"] == 0) {
    die("Utilisateur introuvable dans l'annuaire.");
}
$userDN = $info[0]["dn"];

if (is_admin($ad_link, $userDN)) {
    $_SESSION["role"] = "admin";
} else {
    $_SESSION["role"] = "joueur";
}

ldap_close($ad_link);*/

$_SESSION["connecte"] = true;
$_SESSION["id_utilisateur"] = $utilisateur["id_utilisateur"];
$_SESSION["pseudo"] = $utilisateur["pseudo"];
if ($_SESSION["role"] == "admin") {
    header("Location: " . BASE_URL . "/?page=admin");
} else {
    header("Location: " . BASE_URL . "/?page=compte_utilisateur");
}
exit();

?>
