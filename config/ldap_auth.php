<?php
require_once 'ad.php';

function connect_ad() {
    $ad = ldap_connect(AD_HOST);
    ldap_set_option($ad, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($ad, LDAP_OPT_REFERRALS, 0);
    return $ad;
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
