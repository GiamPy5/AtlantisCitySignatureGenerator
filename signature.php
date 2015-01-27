<?php

session_cache_expire(0);

require "SignatureClass.php";
if (! class_exists("CAtlantisCity_Signature")) {
  die('Errore interno, SignatureClass.php non trovato.');
}

if (! isset($_GET['id'])) {
  die('Nessun ID selezionato (es. ?id=NUMERO).');
}

$signature = new CAtlantisCity_Signature(
  "http://ac-rp.org/site/login.php?action=send", // Account Login
  "http://ac-rp.org/site/viewprofile.php",       // Account Profile
  "http://ac-rp.org/site/viewcharacter.php",     // Character Profile
  "http://pd.lscity.org/site/sign/index.php",    // LSPD Database API
  "cookie"                                       // Cookie File Name
);
$signature->login("SignatureReader", "thisisapassword");
$character = $signature->retrieveCharacter(intval($_GET['id']));

if (! isset($_GET['aid'])) {
  $_GET['aid'] = null;
}

if (! isset($_GET['type'])) {
  $_GET['type'] = 'default';
}

if (! isset($_GET['textcolor'])) {
  $_GET['textcolor'] = 'FFFFFF';
}

if (! isset($_GET['leftright'])) {
  $_GET['leftright'] = 0;
}

header('Content-type: image/png');
header('Cache-Control:post-check=0, pre-check=0');
header('Cache-Control: no-store, no-cache, must-revalidate');
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
header('Connection: close');
echo $signature->showCharacter(
  $character, 
  array(
    'type' => $_GET['type'], 'aid' => intval($_GET['aid'])
  ),
  intval($_GET['leftright']), $_GET['textcolor']
);