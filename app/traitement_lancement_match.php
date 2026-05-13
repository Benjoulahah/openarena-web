<?php
 
session_start();
 
define('SSH_KEY', '/var/www/.ssh/id_ed25519');
 
/**
 * Crée /tmp/joueur_attendu sur un Raspberry via SSH.
 * Le pseudo est transporté en base64 pour éviter tout problème de quoting shell.
 * Retourne null si succès, ou un message d'erreur brut si échec.
 */
function preparer_pi(string $ip, string $admin_user, string $joueur_attendu): ?string {
    $b64 = base64_encode($joueur_attendu);
 
    $cmd_distante = 'echo ' . escapeshellarg($b64) . ' | base64 -d > /tmp/joueur_attendu';
 
    $cmd = sprintf(
        'ssh -i %s -o StrictHostKeyChecking=no -o BatchMode=yes -o ConnectTimeout=5 %s@%s %s 2>&1',
        escapeshellarg(SSH_KEY),
        escapeshellarg($admin_user),
        escapeshellarg($ip),
        escapeshellarg($cmd_distante)
    );
 
    $output = shell_exec($cmd);
 
    if (!empty(trim($output))) {
        return trim($output);
    }
 
    return null;
}
 
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $joueur1    = trim($_POST['pseudo_1'] ?? '');
    $joueur2    = trim($_POST['pseudo_2'] ?? '');
    $id_tournoi = intval($_POST['id_tournoi'] ?? 0);
 

    if ($joueur1 === '' || $joueur2 === '') {
        $_SESSION['message_erreur'] = "Erreur : les pseudos des deux joueurs sont requis.";
        header("Location: /?page=matchs_tournoi&id_tournoi=" . $id_tournoi);
        exit;
    }
 
    $pi1_ip   = "192.168.6.66";
    $pi1_user = "groupe1";
 
    $pi2_ip   = "192.168.6.67";
    $pi2_user = "r2";
 
    $erreurs = [];
 
    // Réinitialise le fichier des joueurs prêts
    $fichier_prets = '/tmp/joueurs_prets.json';
    if (file_exists($fichier_prets)) {
        unlink($fichier_prets);
    }
 
    // Redémarre le serveur OpenArena
    shell_exec('sudo /bin/systemctl restart openarena-server 2>&1');
 
    // Prépare Pi 1
    $err1 = preparer_pi($pi1_ip, $pi1_user, $joueur1);
    if ($err1 !== null) {
        // htmlspecialchars sur le pseudo ET sur la sortie SSH brute pour éviter tout XSS
        $erreurs[] = "Pi 1 (" . $pi1_ip . ") — "
                   . htmlspecialchars($joueur1, ENT_QUOTES, 'UTF-8')
                   . " : "
                   . htmlspecialchars($err1, ENT_QUOTES, 'UTF-8');
    }
 
    // Prépare Pi 2
    $err2 = preparer_pi($pi2_ip, $pi2_user, $joueur2);
    if ($err2 !== null) {
        $erreurs[] = "Pi 2 (" . $pi2_ip . ") — "
                   . htmlspecialchars($joueur2, ENT_QUOTES, 'UTF-8')
                   . " : "
                   . htmlspecialchars($err2, ENT_QUOTES, 'UTF-8');
    }
 
    if (!empty($erreurs)) {
        // Le contenu est déjà échappé ci-dessus, on peut afficher avec <?= directement
        $_SESSION['message_erreur'] = "Échec de la préparation :<br>" . implode("<br>", $erreurs);
    } else {
        // htmlspecialchars ici car l'affichage dans matchs_tournoi utilise htmlspecialchars()
        $_SESSION['message_succes'] = "Match préparé ! "
            . htmlspecialchars($joueur1, ENT_QUOTES, 'UTF-8')
            . " et "
            . htmlspecialchars($joueur2, ENT_QUOTES, 'UTF-8')
            . " peuvent se connecter.";
    }
 
    header("Location: /?page=matchs_tournoi&id_tournoi=" . $id_tournoi);
    exit;
}
?>