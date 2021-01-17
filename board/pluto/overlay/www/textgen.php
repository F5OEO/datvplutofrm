    <?php
    // F5UII : Text generator.

    session_start();
    require ('./lib/functions.php');

    $file_config ='/opt/config.txt';
    $file_general = '/mnt/jffs2/etc/settings-datv.txt';
    $dir = '/mnt/jffs2/etc/';
    if (true==false) // replace false by true for developping on debug server
    {
      echo "<i>Attention, in developping mode </i><br>";
      $file_config ='config.txt';
      $file_general = 'settings-datv.txt';
      $dir= addslashes ('C:\Users\cfur\Downloads\UwAmp\www\datvplutofrm\board\pluto\overlay\www\\');
    }

    $config_ini = readinifile($file_config);
    $headfile = $config_ini[0];
    $network_config = $config_ini[1];
    $general_ini = readinifile($file_general);
    $datv_config = $general_ini[1];    
  

  ?>
  <!doctype html>

  <html>
  <!-- if "?xpert" in url then expert parameters are displayed  -->
  <head>
    <meta charset="UTF-8">

    <title>PlutoDVB Text generator</title>
    <meta name="description" content="ADALM-PLUTO DVB Text generator">
    <link type="text/css" href="./img/style.css" rel="stylesheet">
    <link type="text/css" href="lib/nestable.css" rel="stylesheet">
    <script src="lib/jquery-3.5.1.min.js"></script>
    <script src="lib/tooltip.js"></script>
    <script src="lib/jquery.nestable.js"></script> 
    <script src="lib/mqttws31.js"></script>  
    <script src="lib/mqtt.js.php?page=<?php echo basename($_SERVER["SCRIPT_FILENAME"]); ?>"></script>  
    <link type="text/css" href="./lib/tooltip.css" rel="stylesheet">
    <link type="text/css" href="./lib/menu.css" rel="stylesheet">

    <style>
      /* le bloc défilant */
      .marquee-rtl {
  max-width: 30em;                      /* largeur de la fenêtre */
  margin: 1em auto 2em;
  border: 6px solid #33b3ca;
  overflow: hidden;                     /* masque tout ce qui dépasse */
  box-shadow: 0 .25em .5em #CCC,inset 0 0 1em .25em #CCC;
}

.marquee-rtl  :first-child {
  display: inline-block;                /* modèle de boîte en ligne */
  padding-right: 2em;                   /* un peu d'espace pour la transition */
  padding-left: 100%;                   /* placement à droite du conteneur */
  white-space: nowrap;                  /* pas de passage à la ligne */
  animation: defilement-rtl 15s infinite linear;
  font-family: Tahoma;
}
 animation-name: defilement-rtl {       /* référence à la règle @keyframes mise en oeuvre */
  animation-delay: 15s;                 /* valeur à ajuster suivant la longueur du message */
  animation-iteration-count: infinite;  /* boucle continue */
  animation-timing-function: linear;    /* pas vraiment utile ici */
}
@keyframes defilement-rtl {
  0% {
    transform: translate3d(0,0,0);      /* position initiale à droite */
  }
  100% {
    transform: translate3d(-100%,0,0);  /* position finale à gauche */
  }
}
    </style>
  </head>

  <body>

       <?php include ('lib/menu_header.php'); ?>
<!--
    <nav style="text-align: center;">
     <a class="button" href="analysis.php">Analysis</a>
     <a class="button" href="pluto.php">Controller</a>
     <a class="button" href="index.html">Documentation</a>
   </nav>
 -->
   <h1>PlutoDVB Text generator</h1> 
   <hr>
 

<h2>Text generator</h2>
Drag and drop the items to construct the text as you want it to be composed. It can be directly integrated into your video streaming software and thus be used to animate a banner updated in real time.
<p>For the generated text to be updated, <b>this page must remain open</b> (From the main menu of PlutoDVB, right click, open the link in a new tab).</p><i>under developpement</i>
<!-- le conteneur fenêtre -->
<div class="marquee-rtl">
    <!-- le contenu défilant -->
    <div id ='diplaytextgen'>The generator did not retrieve the items. This will happen when your controller is reloaded.</div>
</div>

<div class="cf nestable-lists">

        <div class="dd" id="nestable"><strong>Items available</strong>
            <ol class="dd-list">
                
                

                <li class="dd-item" data-id="power_rel">
                    <div class="dd-handle">Power - Pluto relative output (dB)</div>
                </li>     
                <li class="dd-item" data-id="power_abs">
                    <div class="dd-handle">Power - Absolute output (dB)</div>
                </li>       
                <li class="dd-item" data-id="power_abs_watt">
                    <div class="dd-handle">Power - Absolute output (W)</div>
                </li>
                <li class="dd-item" data-id="sr">
                    <div class="dd-handle">Symbol Rate</div>
                </li>
                <li class="dd-item" data-id="freq">
                    <div class="dd-handle">Transmission frequency</div>
                </li>
                <li class="dd-item" data-id="pcrpts">
                    <div class="dd-handle">PCR/PTS</div>
                </li>
                <li class="dd-item" data-id="patperiod">
                    <div class="dd-handle">PAT period</div>
                </li>
                <li class="dd-item" data-id="mode">
                    <div class="dd-handle">DVBS mode</div>
                </li>
                <li class="dd-item" data-id="mod">
                    <div class="dd-handle">Modulation</div>
                </li>
                <li class="dd-item" data-id="fec">
                    <div class="dd-handle">FEC forward error correction</div>
                </li>
                <li class="dd-item" data-id="sr">
                    <div class="dd-handle">Symbol Rate</div>
                </li>
                <li class="dd-item" data-id="pilots">
                    <div class="dd-handle">Pilots</div>
                </li>
                <li class="dd-item" data-id="frame">
                    <div class="dd-handle">Frame</div>
                </li>
                <li class="dd-item" data-id="rolloff">
                    <div class="dd-handle">Rolloff</div>
                </li>
                <li class="dd-item" data-id="trvlo">
                    <div class="dd-handle">Transverter frequency</div>
                </li>
                <li class="dd-item" data-id="comment">
                    <div class="dd-handle">Channel comment</div>
                </li>
                <li class="dd-item" data-id="nullpacket_p">
                <div class="dd-handle">Null packets (%)</div>
                <li class="dd-item" data-id="mod_status">
                <div class="dd-handle">Modulator status [underflow/overflow/waiting/running]</div>



                <li class="dd-item" data-id="phase_correction">
                    <div class="dd-handle">Phase correction</div>
                </li>  
                <li class="dd-item" data-id="module_correction">
                    <div class="dd-handle">Module correction</div>
                </li>
                <li class="dd-item" data-id="watchdog">
                    <div class="dd-handle">Watchdog duration (setting)</div>
                </li>       
                <li class="dd-item" data-id="text1">
                    <div class="dd-handle">Free text #1 </div>
                </li>
                <li class="dd-item" data-id="text2">
                    <div class="dd-handle">Free text #2</div>
                </li>
                <li class="dd-item" data-id="text3">
                    <div class="dd-handle">Free text #3</div>
                </li>                      
              </ol>
        </div>

        <div class="dd" id="nestable2"><strong>Generated text</strong>
            <ol class="dd-list">
                <li class="dd-item" data-id="callsign">
                    <div class="dd-handle">Callsign</div>
                </li>
                <li class="dd-item" data-id="provname">
                    <div class="dd-handle">Provider name</div>
                </li>
            </ol>
        </div>

    </div>
    <span id='jsonresult'></span>

<script>




function buildItem(item) {

    var html = "<li class='dd-item' data-id='" + item.id + "' id='" + item.id + "'>";
    html += "<div class='dd-handle'>" + item.desc + "</div>";

    if (item.children) {

        html += "<ol class='dd-list'>";
        $.each(item.children, function (index, sub) {
            html += buildItem(sub);
        });
        html += "</ol>";

    }

    html += "</li>";

    return html;
}

function json2nestable() {
<?php
  if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    $cmd = 'type ';
  }else
  {
    $cmd='cat ';
  }

  ?>

    $.ajax({
        url: "requests.php?cmd="+encodeURIComponent('<?php echo $cmd; echo $dir ?>text_gen_available_items.json'),
        dataType: 'json',
        type: 'get',
        cache:false,
        success: function(data){
            // console.log(data);
            $('#nestable ol').html("");
            $.each(data, function (index, item) {
              $('#nestable ol').append(buildItem(item));
            });
        },
        error: function(d){
            /*console.log("error");*/
            console.log("text_gen_available_items.json not found. Can be normal if table never modified.");
        }

    })

}

function update_textgen(variable, value) {

if (variable.substr(0,16)=='plutodvb/subvar/') {
  j=0;
  global_textgen.forEach(function(i) {
    
    if ('plutodvb/subvar/'+global_textgen[j].id==variable) {
      global_textgen[j]['value']=value;
    }
  j+=1;
  })
}
//console.table(global_textgen);
j=0;
textgen='';
 global_textgen.forEach(function(i) {
  if (global_textgen[j]['value']!='undefined') {
    textgen += global_textgen[j]['value']+' ';
  }
 j+=1;
 });
 console.log ('textgen = '+textgen);
 sendmqtt('plutodvb/subvar/gentext',textgen) ;
 sendmqtt('plutodvb/var','[{"gentext":"'+textgen+'"') ;
 $('#diplaytextgen').text(textgen);

}

var textgen = "";
var global_textgen = [];
function json2nestable2() {
  <?php
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    $cmd = 'type ';
  }else
  {
    $cmd='cat ';
  }

  ?>

global_textgen=[];
    $.ajax({
        url: "requests.php?cmd="+encodeURIComponent('<?php echo $cmd ; echo $dir ?>text_gen_set_items.json'),
        dataType: 'json',
        type: 'get',
        cache:false,
        success: function(data){
            // console.log(data);
            $('#nestable2 ol').html("");
            $.each(data, function (index, item) {
              $('#nestable2 ol').append(buildItem(item));
              global_textgen.push(item);
console.log("subs "+item.id);
              if (mqtt.isConnected()) {
                console.log("subs plutodvb/subvar/"+item.id);
                mqtt.subscribe("plutodvb/subvar/"+item.id);

              }
            });
        },
        error: function(d){
            /*console.log("error");*/
            console.log("text_gen_set_items.json not found. Can be normal if table never modified.");
        }

    })

}




    // activate Nestable for list 1
    $('#nestable').nestable({
        group: 1,
        maxDepth :1

    })
    .on('change', function (){

       $.get( "requests.php?cmd="+encodeURIComponent("echo '["+($(this).nestable('serializeplus'))+"]' > <?php echo $dir ?>text_gen_available_items.json"), function( data ) {
        if (status=='success') { 
          //$('#aa').fadeIn(250).fadeOut(1500);
        }
  });

    });

    // activate Nestable for list 2
    $('#nestable2').nestable({
        group: 1,
        maxDepth :1
    })
    .on('change', function (){
      //console.log ($('#nestable2').nestable('serialize'));
      $.get( "requests.php?cmd="+encodeURIComponent("echo '["+($(this).nestable('serializeplus'))+"]' > <?php echo $dir ?>text_gen_set_items.json"), function( data ) {
        if (status=='success') { 
          if (typeof json2nestable2 == 'function') {
            json2nestable2();
             
          }

          //$('#aa').fadeIn(250).fadeOut(1500);
            }
      });

    });







</script>
<script>
  $( document ).ready(function() {
  MQTTconnect();
  //MQTT send messages
$('body').on('change', 'input,select', function () {
  if (mqtt.isConnected()) {
    obj= $(this).attr('id');
    if (obj==undefined) {
      obj=$(this).attr('name');
    }
    if ($(this).is(':checkbox')) {
      val= $(this).is(':checked');
    } else {
      val=$(this).val();
    }

    sendmqtt('plutodvb/var', '{"'+obj+'":"'+ val +'"}' ) ;
  }
});

  json2nestable(); //load textgenerator
  json2nestable2();
});

</script>
</body>
</html>
