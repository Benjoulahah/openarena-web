<?php

require_once __DIR__ . "/../config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: " . BASE_URL . "/?page=admin");
    exit();
}

$id_tournoi = isset($_POST["id_tournoi"]) ? intval($_POST["id_tournoi"]) : 0;
$ids_matchs = isset($_POST["id_match"]) ? $_POST["id_match"] : array();
$gagnants = isset($_POST["gagnant"]) ? $_POST["gagnant"] : array();

if ($id_tournoi <= 0) {
    die("Tournoi invalide.");
}

try {
    $pdo->beginTransaction();

    $rounds_modifies = array();

    foreach ($ids_matchs as $id_match) {

        $id_match = intval($id_match);

        if ($id_match <= 0) {
            continue;
        }

        if (!isset($gagnants[$id_match])) {
            continue;
        }

        $gagnant_choisi = intval($gagnants[$id_match]);

        $sql = "
            SELECT *
            FROM matchs
            WHERE id_match = ?
            AND id_tournoi = ?
            AND type_match = 'final'
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($id_match, $id_tournoi));
        $match = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$match) {
            continue;
        }

        if ($match["termine"] == 1) {
            continue;
        }

        if ($gagnant_choisi == 1) {
            $id_gagnant = $match["id_participation_1"];
            $id_perdant = $match["id_participation_2"];
        } elseif ($gagnant_choisi == 2) {
            $id_gagnant = $match["id_participation_2"];
            $id_perdant = $match["id_participation_1"];
        } else {
            continue;
        }

        $sql = "
            UPDATE matchs
            SET 
                gagnant_participation_id = ?,
                termine = 1
            WHERE id_match = ?
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($id_gagnant, $id_match));

        /*
            Stats globales simples pour la phase finale.
            Ici on ajoute seulement victoire / défaite globale.
        */
        $sql = "
            SELECT id_utilisateur
            FROM participations
            WHERE id_participation = ?
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($id_gagnant));
        $id_utilisateur_gagnant = $stmt->fetchColumn();

        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($id_perdant));
        $id_utilisateur_perdant = $stmt->fetchColumn();

        if ($id_utilisateur_gagnant) {
            $sql = "
                UPDATE utilisateurs
                SET total_victoires = total_victoires + 1
                WHERE id_utilisateur = ?
            ";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array($id_utilisateur_gagnant));
        }

        if ($id_utilisateur_perdant) {
            $sql = "
                UPDATE utilisateurs
                SET total_defaites = total_defaites + 1
                WHERE id_utilisateur = ?
            ";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array($id_utilisateur_perdant));
        }

        $rounds_modifies[$match["round_num"]] = true;
    }

    /*
        Créer automatiquement le round suivant si tous les matchs du round sont terminés.
    */
    if (!empty($rounds_modifies)) {

        $rounds = array_keys($rounds_modifies);
        sort($rounds);

        foreach ($rounds as $round_num) {

            $sql = "
                SELECT COUNT(*)
                FROM matchs
                WHERE id_tournoi = ?
                AND type_match = 'final'
                AND round_num = ?
                AND termine = 0
            ";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array($id_tournoi, $round_num));
            $matchs_non_termines = $stmt->fetchColumn();

            if ($matchs_non_termines > 0) {
                continue;
            }

            $sql = "
                SELECT gagnant_participation_id
                FROM matchs
                WHERE id_tournoi = ?
                AND type_match = 'final'
                AND round_num = ?
                ORDER BY id_match ASC
            ";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array($id_tournoi, $round_num));
            $gagnants_round = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($gagnants_round) == 1) {
                $sql = "UPDATE tournois SET phase = 'termine' WHERE id_tournoi = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array($id_tournoi));
                continue;
            }

            $round_suivant = intval($round_num) + 1;

            $sql = "
                SELECT COUNT(*)
                FROM matchs
                WHERE id_tournoi = ?
                AND type_match = 'final'
                AND round_num = ?
            ";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array($id_tournoi, $round_suivant));
            $round_suivant_existe = $stmt->fetchColumn();

            if ($round_suivant_existe > 0) {
                continue;
            }

            for ($i = 0; $i < count($gagnants_round); $i += 2) {
                if (!isset($gagnants_round[$i + 1])) {
                    continue;
                }

                $joueur1 = $gagnants_round[$i]["gagnant_participation_id"];
                $joueur2 = $gagnants_round[$i + 1]["gagnant_participation_id"];

                $sql = "
                    INSERT INTO matchs
                    (id_tournoi, round_num, id_participation_1, id_participation_2, type_match, termine)
                    VALUES (?, ?, ?, ?, 'final', 0)
                ";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(
                    $id_tournoi,
                    $round_suivant,
                    $joueur1,
                    $joueur2
                ));
            }
        }
    }

    $pdo->commit();

    header("Location: " . BASE_URL . "/?page=bracket_final&id_tournoi=" . $id_tournoi);
    exit();

} catch (Exception $e) {
    $pdo->rollBack();
    die("Erreur lors de l'enregistrement des résultats de phase finale : " . $e->getMessage());
}

?>