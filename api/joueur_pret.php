<?php

$fichier_prets = '/tmp/joueurs_prets.json';

// 1. Un Pi signale qu'il est prêt
if (isset($_GET['joueur']) && isset($_GET['pi'])) {
    $prets = file_exists($fichier_prets)
        ? json_decode(file_get_contents($fichier_prets), true)
        : [];

    $prets[$_GET['joueur']] = $_GET['pi'];
    file_put_contents($fichier_prets, json_encode($prets));
    echo "OK";
    exit;
}

if (isset($_GET['status'])) {
    $prets = file_exists($fichier_prets)
        ? json_decode(file_get_contents($fichier_prets), true)
        : [];

    echo count($prets) >= 2 ? "GO" : "WAIT";
    exit;
}
?>