<?php

require_once __DIR__ . "/../config/database.php";

$id_tournoi = isset($_GET["id_tournoi"]) ? intval($_GET["id_tournoi"]) : 0;

if ($id_tournoi <= 0) {
    die("Tournoi invalide.");
}

/* Vérifier si le tournoi existe */
$sql = "SELECT * FROM tournois WHERE id_tournoi = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute(array($id_tournoi));
$tournoi = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$tournoi) {
    die("Tournoi introuvable.");
}

if ($tournoi["phase"] != "swiss_termine") {
    die("La phase Swiss n'est pas encore terminée.");
}

/* Vérifier si un bracket existe déjà */
$sql = "
    SELECT COUNT(*)
    FROM matchs
    WHERE id_tournoi = ?
    AND type_match = 'final'
";
$stmt = $pdo->prepare($sql);
$stmt->execute(array($id_tournoi));
$bracket_existe = $stmt->fetchColumn();

if ($bracket_existe > 0) {
    header("Location: " . BASE_URL . "/?page=bracket_final&id_tournoi=" . $id_tournoi);
    exit();
}

/* Récupérer les joueurs qualifiés à 3 victoires */
$sql = "
    SELECT 
        participations.*,
        utilisateurs.pseudo
    FROM participations
    INNER JOIN utilisateurs
        ON participations.id_utilisateur = utilisateurs.id_utilisateur
    WHERE participations.id_tournoi = ?
    AND participations.qualifie = 1
    AND participations.victoires >= 3
    ORDER BY 
        participations.victoires DESC,
        participations.defaites ASC,
        utilisateurs.pseudo ASC
";
$stmt = $pdo->prepare($sql);
$stmt->execute(array($id_tournoi));
$qualifies = $stmt->fetchAll(PDO::FETCH_ASSOC);

$nb_qualifies = count($qualifies);

if ($nb_qualifies < 2) {
    die("Il n'y a pas assez de joueurs qualifiés pour créer une phase finale.");
}

/*
    Choisir la taille du bracket.
    16 joueurs = huitièmes
    8 joueurs = quarts
    4 joueurs = demies
    2 joueurs = finale
*/
if ($nb_qualifies >= 16) {
    $taille_bracket = 16;
} elseif ($nb_qualifies >= 8) {
    $taille_bracket = 8;
} elseif ($nb_qualifies >= 4) {
    $taille_bracket = 4;
} else {
    $taille_bracket = 2;
}

/* Garder uniquement les meilleurs qualifiés selon le classement */
$joueurs_bracket = array_slice($qualifies, 0, $taille_bracket);

try {
    $pdo->beginTransaction();

    /*
        Création des matchs du premier tour.
        Pairing simple :
        1er vs dernier
        2e vs avant-dernier
        etc.
    */
    $round_final = 1;

    for ($i = 0; $i < $taille_bracket / 2; $i++) {
        $joueur1 = $joueurs_bracket[$i];
        $joueur2 = $joueurs_bracket[$taille_bracket - 1 - $i];

        $sql = "
            INSERT INTO matchs
            (id_tournoi, round_num, id_participation_1, id_participation_2, type_match, termine)
            VALUES (?, ?, ?, ?, 'final', 0)
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            $id_tournoi,
            $round_final,
            $joueur1["id_participation"],
            $joueur2["id_participation"]
        ));
    }

    $sql = "UPDATE tournois SET phase = 'finale' WHERE id_tournoi = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($id_tournoi));

    $pdo->commit();

    header("Location: " . BASE_URL . "/?page=bracket_final&id_tournoi=" . $id_tournoi);
    exit();

} catch (Exception $e) {
    $pdo->rollBack();
    die("Erreur lors de la création du bracket : " . $e->getMessage());
}

?>