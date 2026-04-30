<?php

require_once __DIR__ . "/../config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: " . BASE_URL . "/?page=admin_tournoi");
    exit();
}

$nom_tournoi = isset($_POST["nom_tournoi"]) ? trim($_POST["nom_tournoi"]) : "";
$nombre_joueurs = isset($_POST["nombre_joueurs"]) ? intval($_POST["nombre_joueurs"]) : 0;
$joueurs = isset($_POST["joueurs"]) ? $_POST["joueurs"] : array();

if ($nom_tournoi == "") {
    die("Erreur : le nom du tournoi est obligatoire.");
}

if ($nombre_joueurs < 2) {
    die("Erreur : le tournoi doit contenir au moins 2 joueurs.");
}

if (empty($joueurs)) {
    die("Erreur : vous devez sélectionner des joueurs.");
}

if (count($joueurs) != $nombre_joueurs) {
    die("Erreur : le nombre de joueurs sélectionnés doit correspondre au nombre indiqué.");
}

try {
    $pdo->beginTransaction();

    $sql = "
        INSERT INTO tournois 
        (nom_tournoi, nombre_joueurs, round_actuel, phase)
        VALUES (?, ?, 0, 'swiss')
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($nom_tournoi, $nombre_joueurs));

    $id_tournoi = $pdo->lastInsertId();

    foreach ($joueurs as $id_utilisateur) {
        $id_utilisateur = intval($id_utilisateur);

        if ($id_utilisateur > 0) {
            $sql = "
                INSERT INTO participations
                (id_tournoi, id_utilisateur, victoires, defaites, qualifie, elimine)
                VALUES (?, ?, 0, 0, 0, 0)
            ";

            $stmt = $pdo->prepare($sql);
            $stmt->execute(array($id_tournoi, $id_utilisateur));
        }
    }

    $pdo->commit();

    header("Location: " . BASE_URL . "/?page=admin");
    exit();

} catch (Exception $e) {
    $pdo->rollBack();
    die("Erreur lors de la création du tournoi : " . $e->getMessage());
}

?>