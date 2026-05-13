<?php

putenv('LDAPTLS_REQCERT=never');

require_once 'ad.php';

function connect_ad() {
    foreach (AD_HOSTS as $host) {
        $ad = ldap_connect($host);   
        if ($ad) {
            ldap_set_option($ad, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($ad, LDAP_OPT_REFERRALS, 0);
            ldap_set_option($ad, LDAP_OPT_NETWORK_TIMEOUT, 2); 
            if (@ldap_bind($ad, AD_ADMIN, AD_PASS)) { 
                return $ad;
            }
        }
    }
    error_log("Aucun serveur AD disponible.");
    return false; 
}


function is_admin($ad, $userDN) {
    $groupDN = "CN=Admins_jeux,OU=admins,OU=rouen,DC=openarena,DC=local";
    $filter = "(memberOf=$groupDN)";
    $search = ldap_read($ad, $userDN, $filter, array("cn"));
    
    if ($search) {
        $result = ldap_get_entries($ad, $search);
        return ($result["count"] > 0);
    }
    
    return false;
}


function create_user($username, $password) {
    $ad = connect_ad();
    if (ldap_bind($ad, AD_ADMIN, AD_PASS)) {
        $dn = "CN=$username," . USER_OU;
        $newPassword = mb_convert_encoding("\"$password\"", "UTF-16LE");
        
        $info = [
            "objectClass" => ["top", "person", "organizationalPerson", "user"], 
            "sAMAccountName" => $username,
            "userPrincipalName" => "$username@openarena.local",
            "unicodePwd" => $newPassword,
            "userAccountControl" => 512
        ];
        
        return ldap_add($ad, $dn, $info);
    }
    return false;
}
?>
