<?php

define('SSH_KEY', '/var/www/.ssh/id_ed25519'); 

function preparer_pi(string $ip, string $admin_user, string $joueur_attendu): void {
    $cmd = sprintf(
        'ssh -i %s -o StrictHostKeyChecking=no -o BatchMode=yes %s@%s "echo %s > /tmp/joueur_attendu" 2>&1',
        escapeshellarg(SSH_KEY),
        escapeshellarg($admin_user), 
        escapeshellarg($ip),
        escapeshellarg($joueur_attendu)
    );
    shell_exec($cmd);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $joueur1      = $_POST['pseudo_1']; 
    $joueur2      = $_POST['pseudo_2']; 
    
    $pi1_ip   = "192.168.6.66"; 
    $pi1_user = "groupe1"; 

    $pi2_ip   = "192.168.6.67"; 
    $pi2_user = "r2";     

    $fichier_prets = '/tmp/joueurs_prets.json';
    if (file_exists($fichier_prets)) {
        unlink($fichier_prets);
    }

    shell_exec('sudo /bin/systemctl restart openarena-server');

    preparer_pi($pi1_ip, $pi1_user, $joueur1);
    preparer_pi($pi2_ip, $pi2_user, $joueur2);

    $_SESSION['message_succes'] = "Match préparé ! $joueur1 et $joueur2 peuvent se connecter.";
    
    $id_tournoi = $_POST['id_tournoi'] ?? 0;
    header("Location: /?page=matchs_tournoi&id_tournoi=" . $id_tournoi);
    exit;
}
?>