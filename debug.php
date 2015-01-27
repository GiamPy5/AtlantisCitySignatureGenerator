<?php 

require "SignatureClass.php";
if (! class_exists("CAtlantisCity_Signature")) {
  die('Errore interno, SignatureClass.php non trovato.');
}

if (! isset($_GET['id'])) {
  die('Nessun ID selezionato (es. ?id=NUMERO).');
}

$signature = new CAtlantisCity_Signature(
  "http://ac-rp.org/site/login.php?action=send", "http://ac-rp.org/site/viewprofile.php", "http://ac-rp.org/site/viewcharacter.php", "http://pd.lscity.org/site/sign/index.php", "cookie"
);
$signature->login("SignatureReader", "thisisapassword");
$character = $signature->retrieveCharacter(intval($_GET['id']));
$account = $signature->retrieveAccount($_GET['aid'], true);
$cop = $signature->retrieveCop($character['character']['firstname'], $character['character']['lastname']);

var_dump($character);
var_dump($account);
var_dump($cop);