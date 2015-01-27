<!DOCTYPE html>
<html class="no-js" lang="en">
  <head>
    <link rel="stylesheet" href="assets/stylesheets/normalize.css">    
    <link rel="stylesheet" href="assets/stylesheets/foundation.css">
    <link rel="stylesheet" href="assets/stylesheets/custom.css">
    <link rel="shortcut icon" href="favicon.png" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>
    <script src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
    <title>AC-RP Signature Generator</title>
  </head>
  
  <body>
    <header>
      <div class="row panel callout">
        <div class="row">
          <div class="small-12 small-centered text-center columns">
            <br><img src="assets/images/logo.png"><br><br>
            <h3 class="subheader" style="font-family: 'Open Sans'; font-width: 300;">Signature Generator</h3>
          </div>
                          
        </div>
        <hr>
        
        <form data-abide="ajax" id="generatorform">
        <div class="row">
          <div class="small-3 columns">
            <label for="accountid" class="right inline">ID Account <small>(richiesto)</small></label>
          </div>
          <div class="small-7 columns">
            <input type="text" id="accountid" placeholder="Inserisci l'ID del tuo account." required pattern="integer"/> 
            <small class="error">L'ID dell'account è obbligatorio e deve essere numerico.</small>
          </div>
          <div class="small-2 columns">
            <div id="modalAccountId" class="reveal-modal" data-reveal>
              <img src="assets/images/accountid.PNG">
              <a class="close-reveal-modal">×</a>
            </div>          
            <a href="#" data-reveal-id="modalAccountId" class="radius button tiny"><span style="color: white;">Aiuto</span></a>
          </div>                      
        </div>
        
        <form data-abide="ajax" id="generatorform">
        <div class="row">
          <div class="small-3 columns">
            <label for="characterid" class="right inline">ID Personaggio <small>(richiesto)</small></small></label>
          </div>
          <div class="small-7 columns">
            <input type="text" id="characterid" placeholder="Inserisci l'ID del tuo personaggio." required pattern="integer"/>
            <small class="error">L'ID del personaggio è obbligatorio e deve essere numerico.</small>
          </div>
          <div class="small-2 columns">
            <div id="modalPersonaggioId" class="reveal-modal" data-reveal>
              <img src="assets/images/personaggioid.PNG">
              <a class="close-reveal-modal">×</a>
            </div>          
            <a href="#" data-reveal-id="modalPersonaggioId" class="radius button tiny"><span style="color: white;">Aiuto</span></a>
          </div>                      
        </div>

        <form data-abide="ajax" id="generatorform">
        <div class="row">
          <div class="small-3 columns">
            <label for="leftright" class="right inline">Coordinate Left-Right <small>(predefinito: 0)</small></small></label>
          </div>
          <div class="small-7 columns">
            <input type="text" id="leftright" value="0" placeholder="Inserisci qui le coordinate Left-Right." pattern="integer"/>
            <small class="error">Il valore deve essere numerico (positivo o negativo).</small>
          </div>
          <div class="small-2 columns">
            <div id="modalLeftRightId" class="reveal-modal small" data-reveal>
              <div style="text-align: center;">
                Più è alto il valore, più il testo della signature si sposterà verso <em>destra</em>, per esempio:<br><br>
                
                LeftRight: 66<br><br>
                <img src="http://code.giampaolofalqui.com/acrp/signature.php?aid=97&id=25821&leftright=66&textcolor=ffffffff&type=molef"><br><br>
                
                LeftRight: 100<br><br>
                <img src="http://code.giampaolofalqui.com/acrp/signature.php?aid=97&id=25821&leftright=100&textcolor=ffffffff&type=molef">
              </div>
              <a class="close-reveal-modal">×</a>
            </div>          
            <a href="#" data-reveal-id="modalLeftRightId" class="radius button tiny"><span style="color: white;">Aiuto</span></a>
          </div>                      
        </div>

        <form data-abide="ajax" id="generatorform">
        <div class="row">
          <div class="small-3 columns">
            <label for="textcolor" class="right inline">Colore del Testo <small>(colore esadecimale)</small></small></label>
          </div>
          <div class="small-7 columns">
            <input type="text" id="textcolor" value="FFFFFF" placeholder="Inserisci qui il colore del testo che preferisci senza l'asterisco." />
          </div>
          <div class="small-2 columns">
            <div id="modalColore" class="reveal-modal tiny" data-reveal>
              <div style="text-align: center;">
                Vai su <a href="http://colorpicker.com" target="_blank">colorpicker.com</a> per scegliere il tuo colore preferito.<br>
                Incolla il colore nel campo di testo <strong>senza</strong> l'asterisco!
              </div>
              <a class="close-reveal-modal">×</a>
            </div>          
            <a href="#" data-reveal-id="modalColore" class="radius button tiny"><span style="color: white;">Aiuto</span></a>
          </div>                      
        </div>

        <form data-abide="ajax" id="generatorform">
        <div class="row">
          <div class="small-3 columns">
            <label for="textcolor" class="right inline">Tipo <small>(richiesto)</small></small></label>
          </div>
          <div class="small-7 columns">
            <select id="signaturetype">
              <option value="default" selected>Predefinito</option>
              <option value="molef">molef</option>
              <option value="avatar">Avatar UCP</option>
              <option value="agricolo">Macchinario Agricolo</option>
              <option value="topolino">Topolino</option>
              <option value="pavone">Pavone</option>  
              <option value="polizia">(NEW!) Polizia</option>              
            </select>
          </div>
          <div class="small-2 columns">
            <div id="modalTipo" class="reveal-modal small" data-reveal>
              <div style="text-align: center;">
                <h4>Predefinito</h4>
                <img src="http://code.giampaolofalqui.com/acrp/signature.php?aid=97&id=25821&leftright=66&textcolor=ffffffff&type=default"><br>
                <hr>                
                <h4>molef</h4>
                <img src="http://code.giampaolofalqui.com/acrp/signature.php?aid=97&id=25821&leftright=66&textcolor=ffffffff&type=molef"><br>       
                <hr>
                <h4>Avatar UCP</h4>
                <img src="http://code.giampaolofalqui.com/acrp/signature.php?aid=97&id=25821&leftright=66&textcolor=ffffffff&type=avatar"><br>
                <hr>
                <h4>Topolino</h4>
                <img src="http://code.giampaolofalqui.com/acrp/signature.php?aid=97&id=25821&leftright=66&textcolor=ffffffff&type=topolino"><br>    
                <hr>
                <h4>Macchinario Agricolo</h4>
                <img src="http://code.giampaolofalqui.com/acrp/signature.php?aid=97&id=25821&leftright=66&textcolor=ffffffff&type=agricolo"><br>                    
                <hr>
                <h4>Pavone</h4>
                <img src="http://code.giampaolofalqui.com/acrp/signature.php?aid=97&id=25821&leftright=66&textcolor=ffffffff&type=pavone"><br>
                <hr>
                <h4>Polizia (riservato!)</h4>
                <img src="http://code.giampaolofalqui.com/acrp/signature.php?aid=97&id=25821&leftright=66&textcolor=ffffffff&type=polizia"><br>
              </div>
              <a class="close-reveal-modal">&#215;</a>
            </div>          
            <a href="#" data-reveal-id="modalTipo" class="radius button tiny"><span style="color: white;">Aiuto</span></a>
          </div>                      
        </div>     
        
        <div class="row">
          <div class="small-6 small-centered text-center columns">
            <button type="submit" id="generateSignature">Genera</button>     
          </div>       
        </div>
        </form>               
        
        <div id="resultcontainer" style="display: none;">
        <hr>       
          <div class="row">
            <div class="small-12 small-centered text-center columns" id="showsignature">
            </div>
          </div>
          <div class="row">
            <div class="small-6 small-centered text-center columns">
              <input type="text" id="signaturecode" value="prova"/> 
            </div>
          </div>
         </div>
      </div>
    </header>
    
    <script src="assets/javascripts/foundation/foundation.js"></script>
    <script src="assets/javascripts/foundation/foundation.abide.js"></script>
    <script src="assets/javascripts/foundation/foundation.reveal.js"></script>
    
    <script>
    $(document).foundation();
    
    $('#generatorform')
      .on('invalid', function() {
        $('#resultcontainer').slideUp();
      })
      .on('valid', function () {
        $('#resultcontainer').hide();              
      
        var accountId = $('#accountid').val();
        var characterId = $('#characterid').val();
        var leftRight = $('#leftright').val();
        var textColor = $('#textcolor').val();
        var signatureType = $('#signaturetype').val();       
        
        $('#showsignature').html(
          '<img src="http://code.giampaolofalqui.com/acrp/signature.php?aid='+ accountId +'&id=' + characterId + '&leftright=' + leftRight + '&textcolor=' + textColor + '&type=' + signatureType + '"><br/><br/>'
        );
                
        $('#signaturecode').val(
          'http://code.giampaolofalqui.com/acrp/signature.php?aid='+ accountId +'&id=' + characterId + '&leftright=' + leftRight + '&textcolor=' + textColor + '&type=' + signatureType
        );
        
        $('#resultcontainer').slideDown();          
      });
    </script>
    
    <footer>
      <div class="row">
        <div class="small-12 small-centered text-center columns">
          <small>&copy; 2014 - <a href="http://giampaolofalqui.com/">Giampaolo Falqui</a><br/><br/></small>
          </div>
      </div>    
    </footer>
  </body>
</html>
  