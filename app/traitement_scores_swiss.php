<?php

require_once __DIR__ . "/../config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: " . BASE_URL . "/?page=admin");
    exit();
}

$id_tournoi = isset($_POST["id_tournoi"]) ? intval($_POST["id_tournoi"]) : 0;
$ids_matchs = isset($_POST["id_match"]) ? $_POST["id_match"] : array();
$scores_1 = isset($_POST["score_1"]) ? $_POST["score_1"] : array();
$scores_2 = isset($_POST["score_2"]) ? $_POST["score_2"] : array();

if ($id_tournoi <= 0) {
    die("Tournoi invalide.");
}

try {
    $pdo->beginTransaction();

    for ($i = 0; $i < count($ids_matchs); $i++) {

        $id_match = intval($ids_matchs[$i]);
        $score_1 = isset($scores_1[$i]) ? trim($scores_1[$i]) : "";
        $score_2 = isset($scores_2[$i]) ? trim($scores_2[$i]) : "";

        if ($score_1 === "" || $score_2 === "") {
            continue;
        }

        $score_1 = intval($score_1);
        $score_2 = intval($score_2);

        if ($score_1 == $score_2) {
            $pdo->rollBack();
            die("Erreur : les égalités ne sont pas autorisées dans ce système.");
        }

        /* Récupérer le match */
        $sql = "SELECT * FROM matchs WHERE id_match = ? AND id_tournoi = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($id_match, $id_tournoi));
        $match = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$match) {
            continue;
        }

        if ($match["termine"] == 1) {
            continue;
        }

        $id_gagnant = 0;
        $id_perdant = 0;

        if ($score_1 > $score_2) {
            $id_gagnant = $match["id_participation_1"];
            $id_perdant = $match["id_participation_2"];
        } else {
            $id_gagnant = $match["id_participation_2"];
            $id_perdant = $match["id_participation_1"];
        }

        /* Enregistrer le score du match */
        $sql = "
            UPDATE matchs
            SET score_1 = ?, score_2 = ?, termine = 1
            WHERE id_match = ?
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($score_1, $score_2, $id_match));

        /* Ajouter victoire au gagnant */
        $sql = "
            UPDATE participations
            SET victoires = victoires + 1
            WHERE id_participation = ?
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($id_gagnant));

        /* Ajouter défaite au perdant */
        $sql = "
            UPDATE participations
            SET defaites = defaites + 1
            WHERE id_participation = ?
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($id_perdant));

        /* Qualifier si 3 victoires */
        $sql = "
            UPDATE participations
            SET qualifie = 1
            WHERE id_participation = ?
            AND victoires >= 3
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($id_gagnant));

        /* Éliminer si 3 défaites */
        $sql = "
            UPDATE participations
            SET elimine = 1
            WHERE id_participation = ?
            AND defaites >= 3
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($id_perdant));
    }

    $pdo->commit();

    header("Location: " . BASE_URL . "/?page=matchs_tournoi&id_tournoi=" . $id_tournoi);
    exit();

} catch (Exception $e) {
    $pdo->rollBack();
    die("Erreur lors de l'enregistrement des scores : " . $e->getMessage());
}

?>