<?php

class CAtlantisCity_Signature {
  
  private $__loginUrl             = null;
  
  private $__characterUrl         = null;
  
  private $__policeApiUrl         = null; 
  
  private $__cookieFile           = null;
  
  private $__signaturesDirectory  = "signatures/";
  
  private $__fontsDirectory       = "fonts/";
  
  private $__premiumDirectory     = "premium/";
  
  public function __construct($loginUrl, $accountUrl, $characterUrl, $policeApiUrl, $cookieFile) {
    $this->__loginUrl     = $loginUrl;
    $this->__accountUrl   = $accountUrl   . "?";
    $this->__characterUrl = $characterUrl . "?";
    $this->__policeApiUrl = $policeApiUrl . "?";
    $this->__cookieFile   = dirname(__FILE__) . "/{$cookieFile}-" . md5(microtime(true));
  }
  
  public function login($username, $password) {    
    $data = http_build_query(
      array('name' => $username, 'password' => $password, 'submit' => 'Accedi')
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,               $this->__loginUrl);
    curl_setopt($ch, CURLOPT_POSTFIELDS,        $data);
    curl_setopt($ch, CURLOPT_AUTOREFERER,       true); 
    curl_setopt($ch, CURLOPT_COOKIESESSION,     true); 
    curl_setopt($ch, CURLOPT_FAILONERROR,       false); 
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION,    false); 
    curl_setopt($ch, CURLOPT_FRESH_CONNECT,     true); 
    curl_setopt($ch, CURLOPT_HEADER,            true); 
    curl_setopt($ch, CURLOPT_POST,              true); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,    true); 
    curl_setopt($ch, CURLOPT_COOKIEFILE,        $this->__cookieFile);
    curl_setopt($ch, CURLOPT_COOKIEJAR,         $this->__cookieFile);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,    30); 
    $loginResult = curl_exec($ch);
    curl_close($ch);
    
    if (! $loginResult) {
      return false;
    } else {
      return $loginResult;
    }
  }
  
  public function retrieveAccount($id, $delete = true) {
    if (! $this->__doesCookieFileExist()) {
      return false;
    }
    
    $data = http_build_query(
      array('id' => $id)
    );   
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,               $this->__accountUrl . $data);
    curl_setopt($ch, CURLOPT_AUTOREFERER,       true); 
    curl_setopt($ch, CURLOPT_COOKIESESSION,     false); 
    curl_setopt($ch, CURLOPT_FAILONERROR,       false); 
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION,    true); 
    curl_setopt($ch, CURLOPT_FRESH_CONNECT,     true); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,    true); 
    curl_setopt($ch, CURLOPT_COOKIEFILE,        $this->__cookieFile);
    curl_setopt($ch, CURLOPT_COOKIEJAR,         $this->__cookieFile);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,    30); 
    $accountResult = curl_exec($ch);
    curl_close($ch);
    
    if ($delete === true) {
      $deleteCookieResult = $this->__deleteCookieFile();
      if (! $deleteCookieResult) {
        return $this->__drawError("E' stato rilevato un errore (errcode: 1).", "arialbold.ttf", "FF0000", 275, 40);
      }
    }
    
    if ($accountResult) {
      $accountDetails = strstr($accountResult, 'right" src="');
      if (! $accountDetails) {
        return "ACCOUNT_NOT_FOUND";
      }
      
      $information = array();
      
      $information['account']['avatar'] = substr($accountDetails, 12, (strpos($accountDetails, 'height') - 14));
      if($information['account']['avatar'] === "images/no-avatar.jpg") {
        $information['account']['avatar'] = "http://ac-rp.org/site/images/no-avatar.jpg";
      }
      
      $accountDetails = strstr($accountResult, '<b>Nome Account</b>:');
      $accountNameLine = substr($accountDetails, 0, strpos($accountDetails, '<br>'));
      $information['account']['name'] = substr($accountNameLine, 21);
      
      $accountDetails = strstr($accountResult, '<b>Premium</b>:');
      $accountNameLine = substr($accountDetails, 0, strpos($accountDetails, '<br>'));
      $information['account']['premium'] = substr($accountNameLine, 16);     

      if ($information['account']['premium'] === "No") {
        $information['account']['premium'] = false;
      }
      
      return $information;
    }
    
    return false;
  }
  
  public function retrieveCop($firstName, $lastName) {

    /*
     * L'API è stata rimossa.
     */

    $data = http_build_query(
      array('nome' => $firstName, 'cognome' => $lastName, 'api' => NULL)
    );
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,               $this->__policeApiUrl . $data);
    curl_setopt($ch, CURLOPT_AUTOREFERER,       true); 
    curl_setopt($ch, CURLOPT_COOKIESESSION,     false); 
    curl_setopt($ch, CURLOPT_FAILONERROR,       false); 
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION,    true); 
    curl_setopt($ch, CURLOPT_FRESH_CONNECT,     true); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,    true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,    30); 
    $copResult = curl_exec($ch);
    curl_close($ch);
    
    if ($copResult) {
      $result = json_decode($copResult);
      if (! $result->result) {
        return false;
      }     
      return $result;
    }   
    return false;
  }    
 
  public function retrieveCharacter($id) {  
    if (! $this->__doesCookieFileExist()) {
      return false;
    }

    $data = http_build_query(
      array('id' => $id)
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,               $this->__characterUrl . $data);
    curl_setopt($ch, CURLOPT_AUTOREFERER,       true); 
    curl_setopt($ch, CURLOPT_COOKIESESSION,     false); 
    curl_setopt($ch, CURLOPT_FAILONERROR,       false); 
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION,    true); 
    curl_setopt($ch, CURLOPT_FRESH_CONNECT,     true); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,    true); 
    curl_setopt($ch, CURLOPT_COOKIEFILE,        $this->__cookieFile);
    curl_setopt($ch, CURLOPT_COOKIEJAR,         $this->__cookieFile);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,    30); 
    $characterResult = curl_exec($ch);
    curl_close($ch);    
    
    if ($characterResult) {
    
      $playerDetails = strstr($characterResult, "<b>ID Personaggio</b>:");
      if (! $playerDetails) {
        return "PLAYER_NOT_FOUND";
      }
      
      $information = array();
      
      $idLine = substr($playerDetails, 0, strpos($playerDetails, '<br>'));
      $information['character']['id'] = substr($idLine, 23);
      
      $playerDetails = strstr($characterResult, "<b>Account</b>:");
      $accountLine = substr($playerDetails, 0, strpos($playerDetails, '<br>'));
      $information['character']['account'] = substr($accountLine, 16);
      
      $playerDetails = strstr($characterResult, "<b>Nome</b>:");
      $firstNameLine = substr($playerDetails, 0, strpos($playerDetails, '<br>'));
      $information['character']['firstname'] = substr($firstNameLine, 13);

      $playerDetails = strstr($characterResult, "<b>Cognome</b>:");
      $lastNameLine = substr($playerDetails, 0, strpos($playerDetails, '<br>'));
      $information['character']['lastname'] = substr($lastNameLine, 16);
      
      $playerDetails = strstr($characterResult, "<b>Livello</b>:");
      $levelLine = substr($playerDetails, 0, strpos($playerDetails, '<br>'));
      $information['character']['level'] = substr($levelLine, 16);      

      $playerDetails = strstr($characterResult, "<b>Ultimo Login</b>:");
      $lastLoginLine = substr($playerDetails, 0, strpos($playerDetails, '<br>'));
      $information['character']['lastlogin'] = substr($lastLoginLine, 21);
      
      if($information['character']['lastlogin'] === "<font color=green>Attualmente Online</font>") {
        $information['character']['lastlogin'] = "Attualmente Online";
      }
      
      $playerDetails = strstr($characterResult, "<b>Bannato</b>:");
      $bannedLine = substr($playerDetails, 0, strpos($playerDetails, '<br>'));
      $information['character']['banned'] = substr($bannedLine, 16);      
      
      if ($information['character']['banned'] == "Sì") {
        $information['character']['banned'] = true;
      } else if ($information['character']['banned'] == "No") {
        $information['character']['banned'] = false;
      }
      
      return $information;
    }
    
    return false;
  }
  
  public function showCharacter($information, $type, $leftRight, $hexTextColor) {
    if ($information === "PLAYER_NOT_FOUND") {
      return $this->__drawError("Il personaggio selezionato non esiste.", "arialbold.ttf", "FF0000", 275, 40);
    }
    
    $rgbTextColor = $this->__hexToRgb($hexTextColor);
    
    if ($type['type'] === 'avatar') {
      $accountResult = $this->retrieveAccount($type['aid'], true);
    } else {
      $this->__deleteCookieFile();
    }
    
    switch($type['type']) {    
      case 'avatar': {
        if($accountResult === "ACCOUNT_NOT_FOUND") {        
          return $this->__drawError("L'account selezionato non esiste.", "arialbold.ttf", "FF0000", 275, 40);
        } else if (is_array($accountResult) && is_array($information) && $accountResult['account']['name'] != $information['character']['account']) {
          return $this->__drawError("L'account selezionato non possiede il personaggio selezionato.", "arialbold.ttf", "FF0000", 375, 40);
        }
        
        $avatar = $this->__imagecreatefromany($accountResult['account']['avatar']);
        list($avatarX, $avatarY) = getimagesize($accountResult['account']['avatar']);
        
        $newAvatar = imagecreatetruecolor(60, 60);
        imagecopyresampled($newAvatar, $avatar, 0, 0, 0, 0, 60, 60, $avatarX, $avatarY);       
        imagedestroy($avatar);
        
        $black = imagecolorallocate($newAvatar, 0, 0, 0);       
        $this->__drawBorder($newAvatar, $black, 1);
        
        $image = imagecreatefrompng($this->__signaturesDirectory . "avatar.png");
        list($imageX, $imageY) = getimagesize($this->__signaturesDirectory . "avatar.png");
        
        imagecopy($image, $newAvatar, 10, 10, 0, 0, 60, 60);
        $text = imagecolorallocate($image, $rgbTextColor[0], $rgbTextColor[1], $rgbTextColor[2]);
        $red = imagecolorallocate($image, 255, 0, 0);
        $green = imagecolorallocate($image, 57, 179, 94);
        $black = imagecolorallocate($image, 0, 0, 0);                   
        
        if ($information['character']['banned'] === false) {
          $this->__writeWithOutline($image, 12, 0, (10 + $leftRight), 25, $text, $black, $this->__fontsDirectory . "arialbold.ttf", $information['character']['firstname'] . " " . $information['character']['lastname'], 1);
        } else {
          $this->__writeWithOutline($image, 12, 0, (10 + $leftRight), 25, $red, $black, $this->__fontsDirectory . "arialbold.ttf", $information['character']['firstname'] . " " . $information['character']['lastname'] . " (bannato)", 1);
        }
        
        $this->__writeWithOutline($image, 8, 0, (30 + $leftRight), 42, $text, $black, $this->__fontsDirectory . "arialbold.ttf", "Account:", 1);
        $this->__writeWithOutline($image, 8, 0, (85 + $leftRight), 42, $text, $black, $this->__fontsDirectory . "arial.ttf", $information['character']['account'], 1);
        
        $this->__writeWithOutline($image, 8, 0, (30 + $leftRight), 57, $text, $black, $this->__fontsDirectory . "arialbold.ttf", "Livello:", 1);
        $this->__writeWithOutline($image, 8, 0, (75 + $leftRight), 57, $text, $black, $this->__fontsDirectory . "arial.ttf", $information['character']['level'], 1);
        
        $this->__writeWithOutline($image, 8, 0, (30 + $leftRight), 72, $text, $black, $this->__fontsDirectory . "arialbold.ttf", "Ultimo Login:", 1);
        
        if ($information['character']['lastlogin'] === "Attualmente Online") {
          $this->__writeWithOutline($image, 8, 0, (107 + $leftRight), 72, $green, $black, $this->__fontsDirectory . "arial.ttf", $information['character']['lastlogin'], 1);   
        } else {
          $this->__writeWithOutline($image, 8, 0, (107 + $leftRight), 72, $text, $black, $this->__fontsDirectory . "arial.ttf", $information['character']['lastlogin'], 1);
        }
        break;
      }
      
      case 'polizia': {
        $poliziaResult = $this->retrieveCop($information['character']['firstname'], $information['character']['lastname']);
        if($poliziaResult === false) {        
          return $this->__drawError("Non e' stato possibile creare la signature di tipo 'Polizia'.", "arialbold.ttf", "FF0000", 350, 40);
        }
        
        $image = imagecreatefrompng($this->__signaturesDirectory . "polizia.png");
        $text = imagecolorallocate($image, $rgbTextColor[0], $rgbTextColor[1], $rgbTextColor[2]);
        $red = imagecolorallocate($image, 255, 0, 0);
        $green = imagecolorallocate($image, 57, 179, 94);
        
        if ($information['character']['banned'] === false) {
          $this->__writeWithOutline($image, 12, 0, (10 + $leftRight), 25, $text, $black, $this->__fontsDirectory . "arialbold.ttf", "(#" . $poliziaResult->distintivo . ") " . $poliziaResult->grado . " " . $information['character']['firstname'] . " " . $information['character']['lastname'], 1);
        } else {
          $this->__writeWithOutline($image, 12, 0, (10 + $leftRight), 25, $text, $black, $this->__fontsDirectory . "arialbold.ttf", "(#" . $poliziaResult->distintivo . ") " . $poliziaResult->grado . " " . $information['character']['firstname'] . " " . $information['character']['lastname'] . " (bannato)", 1);
        }

        $this->__writeWithOutline($image, 8, 0, (30 + $leftRight), 42, $text, $black, $this->__fontsDirectory . "arialbold.ttf", "Account:", 1);
        $this->__writeWithOutline($image, 8, 0, (85 + $leftRight), 42, $text, $black, $this->__fontsDirectory . "arial.ttf", $information['character']['account'], 1);
        
        $this->__writeWithOutline($image, 8, 0, (30 + $leftRight), 57, $text, $black, $this->__fontsDirectory . "arialbold.ttf", "Livello:", 1);
        $this->__writeWithOutline($image, 8, 0, (75 + $leftRight), 57, $text, $black, $this->__fontsDirectory . "arial.ttf", $information['character']['level'], 1);
        
        $this->__writeWithOutline($image, 8, 0, (30 + $leftRight), 72, $text, $black, $this->__fontsDirectory . "arialbold.ttf", "Ultimo Login:", 1);
        
        if ($information['character']['lastlogin'] === "Attualmente Online") {
          $this->__writeWithOutline($image, 8, 0, (107 + $leftRight), 72, $green, $black, $this->__fontsDirectory . "arial.ttf", $information['character']['lastlogin'], 1);   
        } else {
          $this->__writeWithOutline($image, 8, 0, (107 + $leftRight), 72, $text, $black, $this->__fontsDirectory . "arial.ttf", $information['character']['lastlogin'], 1);
        }  
        break;
      }       
      
      case 'topolino': {
        $image = imagecreatefrompng($this->__signaturesDirectory . "topolino.png");
        $text = imagecolorallocate($image, $rgbTextColor[0], $rgbTextColor[1], $rgbTextColor[2]);
        $red = imagecolorallocate($image, 255, 0, 0);
        $green = imagecolorallocate($image, 57, 179, 94);
        $black = imagecolorallocate($image, 0, 0, 0);                   
        
        if ($information['character']['banned'] === false) {
          $this->__writeWithOutline($image, 12, 0, (10 + $leftRight), 25, $text, $black, $this->__fontsDirectory . "arialbold.ttf", $information['character']['firstname'] . " " . $information['character']['lastname'], 1);
        } else {
          $this->__writeWithOutline($image, 12, 0, (10 + $leftRight), 25, $red, $black, $this->__fontsDirectory . "arialbold.ttf", $information['character']['firstname'] . " " . $information['character']['lastname'] . " (bannato)", 1);
        }
        
        $this->__writeWithOutline($image, 8, 0, (30 + $leftRight), 42, $text, $black, $this->__fontsDirectory . "arialbold.ttf", "Account:", 1);
        $this->__writeWithOutline($image, 8, 0, (85 + $leftRight), 42, $text, $black, $this->__fontsDirectory . "arial.ttf", $information['character']['account'], 1);
        
        $this->__writeWithOutline($image, 8, 0, (30 + $leftRight), 57, $text, $black, $this->__fontsDirectory . "arialbold.ttf", "Livello:", 1);
        $this->__writeWithOutline($image, 8, 0, (75 + $leftRight), 57, $text, $black, $this->__fontsDirectory . "arial.ttf", $information['character']['level'], 1);
        
        $this->__writeWithOutline($image, 8, 0, (30 + $leftRight), 72, $text, $black, $this->__fontsDirectory . "arialbold.ttf", "Ultimo Login:", 1);
        
        if ($information['character']['lastlogin'] === "Attualmente Online") {
          $this->__writeWithOutline($image, 8, 0, (107 + $leftRight), 72, $green, $black, $this->__fontsDirectory . "arial.ttf", $information['character']['lastlogin'], 1);   
        } else {
          $this->__writeWithOutline($image, 8, 0, (107 + $leftRight), 72, $text, $black, $this->__fontsDirectory . "arial.ttf", $information['character']['lastlogin'], 1);
        }  
        break;
      }

      case 'pavone': {
        $image = imagecreatefrompng($this->__signaturesDirectory . "pavone.png");
        $text = imagecolorallocate($image, $rgbTextColor[0], $rgbTextColor[1], $rgbTextColor[2]);
        $red = imagecolorallocate($image, 255, 0, 0);
        $green = imagecolorallocate($image, 57, 179, 94);
        $black = imagecolorallocate($image, 0, 0, 0);                   
        
        if ($information['character']['banned'] === false) {
          $this->__writeWithOutline($image, 12, 0, (10 + $leftRight), 25, $text, $black, $this->__fontsDirectory . "arialbold.ttf", $information['character']['firstname'] . " " . $information['character']['lastname'], 1);
        } else {
          $this->__writeWithOutline($image, 12, 0, (10 + $leftRight), 25, $red, $black, $this->__fontsDirectory . "arialbold.ttf", $information['character']['firstname'] . " " . $information['character']['lastname'] . " (bannato)", 1);
        }
        
        $this->__writeWithOutline($image, 8, 0, (30 + $leftRight), 42, $text, $black, $this->__fontsDirectory . "arialbold.ttf", "Account:", 1);
        $this->__writeWithOutline($image, 8, 0, (85 + $leftRight), 42, $text, $black, $this->__fontsDirectory . "arial.ttf", $information['character']['account'], 1);
        
        $this->__writeWithOutline($image, 8, 0, (30 + $leftRight), 57, $text, $black, $this->__fontsDirectory . "arialbold.ttf", "Livello:", 1);
        $this->__writeWithOutline($image, 8, 0, (75 + $leftRight), 57, $text, $black, $this->__fontsDirectory . "arial.ttf", $information['character']['level'], 1);
        
        $this->__writeWithOutline($image, 8, 0, (30 + $leftRight), 72, $text, $black, $this->__fontsDirectory . "arialbold.ttf", "Ultimo Login:", 1);
        
        if ($information['character']['lastlogin'] === "Attualmente Online") {
          $this->__writeWithOutline($image, 8, 0, (107 + $leftRight), 72, $green, $black, $this->__fontsDirectory . "arial.ttf", $information['character']['lastlogin'], 1);   
        } else {
          $this->__writeWithOutline($image, 8, 0, (107 + $leftRight), 72, $text, $black, $this->__fontsDirectory . "arial.ttf", $information['character']['lastlogin'], 1);
        } 
        break;
      }        

      case 'agricolo': {
        $image = imagecreatefrompng($this->__signaturesDirectory . "agricolo.png");
        $text = imagecolorallocate($image, $rgbTextColor[0], $rgbTextColor[1], $rgbTextColor[2]);
        $red = imagecolorallocate($image, 255, 0, 0);
        $green = imagecolorallocate($image, 57, 179, 94);
        $black = imagecolorallocate($image, 0, 0, 0);                   
        
        if ($information['character']['banned'] === false) {
          $this->__writeWithOutline($image, 12, 0, (10 + $leftRight), 25, $text, $black, $this->__fontsDirectory . "arialbold.ttf", $information['character']['firstname'] . " " . $information['character']['lastname'], 1);
        } else {
          $this->__writeWithOutline($image, 12, 0, (10 + $leftRight), 25, $red, $black, $this->__fontsDirectory . "arialbold.ttf", $information['character']['firstname'] . " " . $information['character']['lastname'] . " (bannato)", 1);
        }
        
        $this->__writeWithOutline($image, 8, 0, (30 + $leftRight), 42, $text, $black, $this->__fontsDirectory . "arialbold.ttf", "Account:", 1);
        $this->__writeWithOutline($image, 8, 0, (85 + $leftRight), 42, $text, $black, $this->__fontsDirectory . "arial.ttf", $information['character']['account'], 1);
        
        $this->__writeWithOutline($image, 8, 0, (30 + $leftRight), 57, $text, $black, $this->__fontsDirectory . "arialbold.ttf", "Livello:", 1);
        $this->__writeWithOutline($image, 8, 0, (75 + $leftRight), 57, $text, $black, $this->__fontsDirectory . "arial.ttf", $information['character']['level'], 1);
        
        $this->__writeWithOutline($image, 8, 0, (30 + $leftRight), 72, $text, $black, $this->__fontsDirectory . "arialbold.ttf", "Ultimo Login:", 1);
        
        if ($information['character']['lastlogin'] === "Attualmente Online") {
          $this->__writeWithOutline($image, 8, 0, (107 + $leftRight), 72, $green, $black, $this->__fontsDirectory . "arial.ttf", $information['character']['lastlogin'], 1);   
        } else {
          $this->__writeWithOutline($image, 8, 0, (107 + $leftRight), 72, $text, $black, $this->__fontsDirectory . "arial.ttf", $information['character']['lastlogin'], 1);
        } 
        break;
      }      
        
      case 'molef': {
        $image = imagecreatefrompng($this->__signaturesDirectory . "molef.png");
        $text = imagecolorallocate($image, $rgbTextColor[0], $rgbTextColor[1], $rgbTextColor[2]);
        $red = imagecolorallocate($image, 255, 0, 0);
        $green = imagecolorallocate($image, 57, 179, 94);
        $black = imagecolorallocate($image, 0, 0, 0);                   
        
        if ($information['character']['banned'] === false) {
          $this->__writeWithOutline($image, 12, 0, (10 + $leftRight), 25, $text, $black, $this->__fontsDirectory . "arialbold.ttf", $information['character']['firstname'] . " " . $information['character']['lastname'], 1);
        } else {
          $this->__writeWithOutline($image, 12, 0, (10 + $leftRight), 25, $red, $black, $this->__fontsDirectory . "arialbold.ttf", $information['character']['firstname'] . " " . $information['character']['lastname'] . " (bannato)", 1);
        }
        
        $this->__writeWithOutline($image, 8, 0, (30 + $leftRight), 42, $text, $black, $this->__fontsDirectory . "arialbold.ttf", "Account:", 1);
        $this->__writeWithOutline($image, 8, 0, (85 + $leftRight), 42, $text, $black, $this->__fontsDirectory . "arial.ttf", $information['character']['account'], 1);
        
        $this->__writeWithOutline($image, 8, 0, (30 + $leftRight), 57, $text, $black, $this->__fontsDirectory . "arialbold.ttf", "Livello:", 1);
        $this->__writeWithOutline($image, 8, 0, (75 + $leftRight), 57, $text, $black, $this->__fontsDirectory . "arial.ttf", $information['character']['level'], 1);
        
        $this->__writeWithOutline($image, 8, 0, (30 + $leftRight), 72, $text, $black, $this->__fontsDirectory . "arialbold.ttf", "Ultimo Login:", 1);
        
        if ($information['character']['lastlogin'] === "Attualmente Online") {
          $this->__writeWithOutline($image, 8, 0, (107 + $leftRight), 72, $green, $black, $this->__fontsDirectory . "arial.ttf", $information['character']['lastlogin'], 1);   
        } else {
          $this->__writeWithOutline($image, 8, 0, (107 + $leftRight), 72, $text, $black, $this->__fontsDirectory . "arial.ttf", $information['character']['lastlogin'], 1);
        } 
        break;
      }
      
      default: {
        $image = imagecreatefrompng($this->__signaturesDirectory . "default.png");
        $text = imagecolorallocate($image, $rgbTextColor[0], $rgbTextColor[1], $rgbTextColor[2]);
        $red = imagecolorallocate($image, 255, 0, 0);
        $green = imagecolorallocate($image, 57, 179, 94);
        $black = imagecolorallocate($image, 0, 0, 0);                   
        
        if ($information['character']['banned'] === false) {
          $this->__writeWithOutline($image, 12, 0, (10 + $leftRight), 25, $text, $black, $this->__fontsDirectory . "arialbold.ttf", $information['character']['firstname'] . " " . $information['character']['lastname'], 1);
        } else {
          $this->__writeWithOutline($image, 12, 0, (10 + $leftRight), 25, $red, $black, $this->__fontsDirectory . "arialbold.ttf", $information['character']['firstname'] . " " . $information['character']['lastname'] . " (bannato)", 1);
        }
        
        $this->__writeWithOutline($image, 8, 0, (30 + $leftRight), 42, $text, $black, $this->__fontsDirectory . "arialbold.ttf", "Account:", 1);
        $this->__writeWithOutline($image, 8, 0, (85 + $leftRight), 42, $text, $black, $this->__fontsDirectory . "arial.ttf", $information['character']['account'], 1);
        
        $this->__writeWithOutline($image, 8, 0, (30 + $leftRight), 57, $text, $black, $this->__fontsDirectory . "arialbold.ttf", "Livello:", 1);
        $this->__writeWithOutline($image, 8, 0, (75 + $leftRight), 57, $text, $black, $this->__fontsDirectory . "arial.ttf", $information['character']['level'], 1);
        
        $this->__writeWithOutline($image, 8, 0, (30 + $leftRight), 72, $text, $black, $this->__fontsDirectory . "arialbold.ttf", "Ultimo Login:", 1);
        
        if ($information['character']['lastlogin'] === "Attualmente Online") {
          $this->__writeWithOutline($image, 8, 0, (107 + $leftRight), 72, $green, $black, $this->__fontsDirectory . "arial.ttf", $information['character']['lastlogin'], 1);   
        } else {
          $this->__writeWithOutline($image, 8, 0, (107 + $leftRight), 72, $text, $black, $this->__fontsDirectory . "arial.ttf", $information['character']['lastlogin'], 1);
        }
        break;
      }       
    }   
    
    $imageString = imagepng($image);
    imagedestroy($image);            
    return $imageString;
  }
  
  private function __writeWithOutline(&$image, $size, $angle, $x, $y, &$textcolor, &$strokecolor, $fontfile, $text, $px) {
    for($c1 = ($x-abs($px)); $c1 <= ($x+abs($px)); $c1++)
      for($c2 = ($y-abs($px)); $c2 <= ($y+abs($px)); $c2++)
        $bg = imagettftext($image, $size, $angle, $c1, $c2, $strokecolor, $fontfile, $text);

    return imagettftext($image, $size, $angle, $x, $y, $textcolor, $fontfile, $text);
  }  
  
  private function __drawError($text, $font, $hexColor, $x = 275, $y = 40) {
    $image = imagecreatetruecolor($x, $y);
    imagesavealpha($image, true);
    
    $transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);
    imagefill($image, 0, 0, $transparent);
    
    $rgbColor = $this->__hexToRgb($hexColor);
    $color = imagecolorallocate($image, $rgbColor[0], $rgbColor[1], $rgbColor[2]);
    $black = imagecolorallocate($image, 0, 0, 0);  
    $this->__writeWithOutline($image, 8, 0, 10, 20, $color, $black, $this->__fontsDirectory . $font, $text, 1);
    
    $imageString = imagepng($image);
    imagedestroy($image);
    return $imageString;
  }

  private function __doesCookieFileExist() {
    return file_exists($this->__cookieFile);
  }
  
  private function __createCookieFile() {      
    if(! file_exists($this->__cookieFile)) {
      $fh = fopen($this->__cookieFile, "w");
      fwrite($fh, "");
      fclose($fh);
    }
  }
  
  private function __deleteCookieFile() {
    if (file_exists($this->__cookieFile)) {
      return unlink($this->__cookieFile);
    } else {
      return false;
    }
  }
  
  private function __hexToRgb($hex) {
     if(strlen($hex) == 3) {
        $r = hexdec(substr($hex,0,1).substr($hex,0,1));
        $g = hexdec(substr($hex,1,1).substr($hex,1,1));
        $b = hexdec(substr($hex,2,1).substr($hex,2,1));
     } else {
        $r = hexdec(substr($hex,0,2));
        $g = hexdec(substr($hex,2,2));
        $b = hexdec(substr($hex,4,2));
     }
     $rgb = array($r, $g, $b);
     
     return $rgb;
  }
  
  private function __imagecreatefromany($filepath) { 
    $type = getimagesize($filepath);
    $allowedTypes = array( 
        'image/gif',
        'image/jpeg',
        'image/png',
        'image/bmp'
    ); 
    if (!in_array($type["mime"], $allowedTypes)) { 
        return false; 
    } 
    switch ($type["mime"]) {
        case "image/gif": 
            $im = imagecreatefromgif($filepath); 
        break; 
        case "image/jpeg": 
            $im = imagecreatefromjpeg($filepath); 
        break; 
        case "image/png": 
            $im = imagecreatefrompng($filepath); 
        break; 
        case "image/bmp": 
            $im = imagecreatefrombmp($filepath); 
        break; 
    }
    return $im;  
  } 
  
  private function __drawBorder(&$image, &$color, $thickness = 1) {
    $x1 = 0; 
    $y1 = 0; 
    $x2 = ImageSX($image) - 1; 
    $y2 = ImageSY($image) - 1; 

    for($i = 0; $i < $thickness; $i++) 
    { 
        ImageRectangle($image, $x1++, $y1++, $x2--, $y2--, $color); 
    } 
  } 
}