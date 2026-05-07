<?php

require_once __DIR__ . "/../config/database.php";

$id_tournoi = isset($_GET["id_tournoi"]) ? intval($_GET["id_tournoi"]) : 0;

if ($id_tournoi <= 0) {
    die("Tournoi invalide.");
}

/* Fonction pour créer un match */
function creerMatch($pdo, $id_tournoi, $round_num, $id_participation_1, $id_participation_2) {
    $sql = "
        INSERT INTO matchs
        (id_tournoi, round_num, id_participation_1, id_participation_2, type_match, termine)
        VALUES (?, ?, ?, ?, 'swiss', 0)
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        $id_tournoi,
        $round_num,
        $id_participation_1,
        $id_participation_2
    ));
}

/* Récupération du tournoi */
$sql = "SELECT * FROM tournois WHERE id_tournoi = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute(array($id_tournoi));
$tournoi = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$tournoi) {
    die("Tournoi introuvable.");
}

/* Mettre à jour les statuts */
$sql = "
    UPDATE participations
    SET qualifie = 1
    WHERE id_tournoi = ?
    AND victoires >= 3
";
$stmt = $pdo->prepare($sql);
$stmt->execute(array($id_tournoi));

$sql = "
    UPDATE participations
    SET elimine = 1
    WHERE id_tournoi = ?
    AND defaites >= 3
";
$stmt = $pdo->prepare($sql);
$stmt->execute(array($id_tournoi));

/* Vérifier que les matchs du round actuel sont terminés */
if ($tournoi["round_actuel"] > 0) {
    $sql = "
        SELECT COUNT(*)
        FROM matchs
        WHERE id_tournoi = ?
        AND round_num = ?
        AND termine = 0
        AND type_match = 'swiss'
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($id_tournoi, $tournoi["round_actuel"]));
    $matchs_non_termines = $stmt->fetchColumn();

    if ($matchs_non_termines > 0) {
        die("Tous les matchs du round actuel ne sont pas terminés.");
    }
}

$nouveau_round = intval($tournoi["round_actuel"]) + 1;

/* Récupérer les joueurs actifs */
$sql = "
    SELECT *
    FROM participations
    WHERE id_tournoi = ?
    AND qualifie = 0
    AND elimine = 0
    AND victoires < 3
    AND defaites < 3
    ORDER BY victoires DESC, defaites ASC
";
$stmt = $pdo->prepare($sql);
$stmt->execute(array($id_tournoi));
$participants = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($participants) == 0) {
    /*
        Plus aucun joueur actif :
        tous les joueurs sont soit qualifiés, soit éliminés.
        Le Swiss est terminé.
    */
    $sql = "
        UPDATE tournois
        SET phase = 'swiss_termine'
        WHERE id_tournoi = ?
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($id_tournoi));

    header("Location: " . BASE_URL . "/?page=gestion_tournoi&id_tournoi=" . $id_tournoi);
    exit();
}

if (count($participants) == 1) {
    /*
        Un seul joueur actif reste.
        Il ne peut plus avoir d'adversaire car tous les autres sont déjà qualifiés ou éliminés.
        On le qualifie donc automatiquement pour terminer correctement la phase Swiss.
    */

    $dernier_joueur = $participants[0];

    $sql = "
        UPDATE participations
        SET qualifie = 1,
            victoires = 3
        WHERE id_participation = ?
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($dernier_joueur["id_participation"]));

    $sql = "
        UPDATE tournois
        SET phase = 'swiss_termine'
        WHERE id_tournoi = ?
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($id_tournoi));

    header("Location: " . BASE_URL . "/?page=gestion_tournoi&id_tournoi=" . $id_tournoi);
    exit();
}
$nombre_matchs_crees = 0;

try {
    $pdo->beginTransaction();

    /* ROUND 1 : tirage complètement aléatoire */
    if ($nouveau_round == 1) {

        shuffle($participants);

        for ($i = 0; $i < count($participants); $i += 2) {
            if (!isset($participants[$i + 1])) {
                continue;
            }

            $joueur1 = $participants[$i];
            $joueur2 = $participants[$i + 1];

            creerMatch(
                $pdo,
                $id_tournoi,
                $nouveau_round,
                $joueur1["id_participation"],
                $joueur2["id_participation"]
            );

            $nombre_matchs_crees++;
        }

    } else {

        /*
            Rounds suivants :
            1. On crée d'abord les matchs entre joueurs avec le même nombre de victoires.
            2. Les joueurs seuls sont gardés dans $joueurs_restants.
            3. Ensuite, les joueurs restants s'affrontent avec le nombre de victoires le plus proche.
        */

        $groupes = array();
        $joueurs_restants = array();

        foreach ($participants as $participant) {
            $cle = $participant["victoires"];

            if (!isset($groupes[$cle])) {
                $groupes[$cle] = array();
            }

            $groupes[$cle][] = $participant;
        }

        /*
            Trier les groupes du plus grand nombre de victoires au plus petit.
            Exemple : 2 victoires, puis 1 victoire, puis 0 victoire.
        */
        krsort($groupes);

        /*
            Étape 1 : matchs dans les mêmes groupes de victoires.
        */
        foreach ($groupes as $victoires => $groupe) {

            shuffle($groupe);

            for ($i = 0; $i < count($groupe); $i += 2) {

                if (!isset($groupe[$i + 1])) {
                    $joueurs_restants[] = $groupe[$i];
                    continue;
                }

                $joueur1 = $groupe[$i];
                $joueur2 = $groupe[$i + 1];

                creerMatch(
                    $pdo,
                    $id_tournoi,
                    $nouveau_round,
                    $joueur1["id_participation"],
                    $joueur2["id_participation"]
                );

                $nombre_matchs_crees++;
            }
        }

        /*
            Étape 2 : apparier les joueurs restants avec le score le plus proche.
        */
        while (count($joueurs_restants) >= 2) {

            $joueur1 = array_shift($joueurs_restants);

            $meilleur_index = null;
            $meilleure_difference = null;

            for ($i = 0; $i < count($joueurs_restants); $i++) {
                $difference = abs(intval($joueur1["victoires"]) - intval($joueurs_restants[$i]["victoires"]));

                if ($meilleur_index === null || $difference < $meilleure_difference) {
                    $meilleur_index = $i;
                    $meilleure_difference = $difference;
                }
            }

            if ($meilleur_index === null) {
                break;
            }

            $joueur2 = $joueurs_restants[$meilleur_index];

            array_splice($joueurs_restants, $meilleur_index, 1);

            creerMatch(
                $pdo,
                $id_tournoi,
                $nouveau_round,
                $joueur1["id_participation"],
                $joueur2["id_participation"]
            );

            $nombre_matchs_crees++;
        }

        /*
            S'il reste un seul joueur après tout ça, il ne joue pas ce round.
            Aucun point automatique.
            Aucun match créé pour lui.
        */
    }

    if ($nombre_matchs_crees == 0) {
        $pdo->rollBack();
        die("Aucun match ne peut être créé pour ce round.");
    }

    $sql = "UPDATE tournois SET round_actuel = ? WHERE id_tournoi = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($nouveau_round, $id_tournoi));

    $pdo->commit();

    header("Location: " . BASE_URL . "/?page=matchs_tournoi&id_tournoi=" . $id_tournoi);
    exit();

} catch (Exception $e) {
    $pdo->rollBack();
    die("Erreur lors de la création du round : " . $e->getMessage());
}

?>