    <?php
    // F5UII : Text generator.

    session_start();
    require_once ('./lib/functions.php');


  ?>
  <!doctype html>

  <html>
  <head>
    <meta charset="UTF-8">

    <title>PlutoDVB Text generator</title>
    <meta name="description" content="ADALM-PLUTO DVB Text generator">
    <meta http-equiv="Cache-Control" content="no-cache" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />    
    <link type="text/css" href="./img/style.css" rel="stylesheet">
    <link type="text/css" href="lib/nestable.css" rel="stylesheet">
    <script src="lib/jquery-3.5.1.min.js"></script>
    <script src="lib/tooltip.js"></script>
    <script src="lib/jquery.nestable.js"></script> 
    <script src="lib/mqttws31.js"></script>  
    <script src="lib/mqtt.js.php?page=<?php echo basename($_SERVER["SCRIPT_FILENAME"]); ?>"></script>  
    <link type="text/css" href="./lib/tooltip.css" rel="stylesheet">
    <link type="text/css" href="./lib/menu.css" rel="stylesheet">
    <link href="img/favicon-32x32.png" rel="icon" type="image/png" />

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

.freetext {
  border-color : red !important;
}
.freemqtt {
  border-color : blue !important;
}

.a {
      margin: 5px 10px;
}
.line {
      min-height: 25px;
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
<p>For the generated text to be updated, <b>this page must remain open</b> (From the main menu of PlutoDVB, right click, open the link in a new tab).</p>
<h3>How to access to the text from your streaming software (e.g. OBS Studio) ?</h3>
There are several technical means to access the text.
<h4>File Sharing</h4>
PlutoDVB includes an NFS file server. You can create a network drive on Windows to access the text file in which the generator writes.For Windows, the configuration is carried out by two operations:
<ul>
  <li>Install the NFS Client (Services for NFS) : Open Programs and Features. Click Turn Windows features on or off. Scroll down and check the option Services for NFS, then click OK. Once installed, click Close and exit back to the desktop</li>
  <li>Mount the NFS Share (create a drive shortcut, here for example P like Pluto) : Open the Command Prompt and type <pre>mount -o anon \\<?php echo shell_exec('ip -f inet -o addr show eth0 | cut -d\  -f 7 | cut -d/ -f 1 | tr -d "\n"');?>\tmp P:</pre></li>
</ul>
In OBS Studio, add a Text(GDI+) source on a scene. Check File source, and choose the text file source that is <pre>P:\plutotext.txt</pre>
<!-- le conteneur fenêtre -->
<div class="marquee-rtl">
    <!-- le contenu défilant -->
    <div id ='displaytextgen'>The generator did not retrieve the items. This will happen when your controller is reloaded, on a seperate browser tab.</div>
</div>
<div style ="margin-bottom: 15px;">
<button id="addfreetext" type="button">Add a freetext</button> <button id="addmqttvar" type="button">Add a MQTT topic</button>



</div>
<div class="cf nestable-lists">

        <div class="dd" id="nestable"><strong>Items available</strong>
            <ol class="dd-list">
                
                <li class="dd-item" data-id="firmversion">
                    <div class="dd-handle">Firmware version</div>
                </li>
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
                <li class="dd-item" data-id="fpgatemp">
                    <div class="dd-handle">FGPA Temperature</div>
                </li>
                <li class="dd-item" data-id="plutodvb/status/voltage">
                    <div class="dd-handle">Pluto Voltage</div>
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
                  <div class="checkcontainer" style="margin-top: 6px;">
        <input type="checkbox" id="space_item" name="space_item-manualmode"  onchange="">
        <label for="space_item" aria-describedby="label"><span class="ui"></span><span  class="note tooltip" style="color: #333;" title="Add one space after each item">spaced</span></label>
      </div>
        </div>

    </div>
    <span id='jsonresult'></span>

<script>




function buildItem(item) {

    var html = "<li class='dd-item' data-id='" + item.id + "' id='" + item.id + "'>";
    html += "<div class='dd-handle'>" + item.desc + "</div>";
    if ((item.id.substring(0,8)=='freetext') || (item.id.substring(0,8)=='freemqtt')) {
    //if ((typeof item.text !== "undefined") && (item.text!='')) {
      html += '<div class="line"><span class="a" spellcheck="false" contentEditable="true">'+item.text+'</span><span class="del" style="float : right">✖️</span></div></li>';
    }

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
    $mark = "";
  }else
  {
    $cmd='cat ';
    $mark= "'";
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

let memo_textgen = textgen;

if (variable.substr(0,16)=='plutodvb/subvar/') {
  j=0;
  global_textgen.forEach(function(i) {
    
    if ('plutodvb/subvar/'+global_textgen[j].id==variable) {
      global_textgen[j]['value']=value;
    }
  j+=1;
  })
}

if (variable.substr(0,16)=='plutodvb/status/') {
  j=0;
  global_textgen.forEach(function(i) {
    
    if ('plutodvb/status/'+global_textgen[j].id==variable) {
      global_textgen[j]['value']=value;
    }
  j+=1;
  })
}

//console.table(global_textgen);
j=0;
textgen='';
let space = '';
if ($('#space_item').is(":checked")) {
  space = ' ';
} 

 global_textgen.forEach(function(i) {
  if (global_textgen[j]['id'].substring(0,8)=='freetext') {
    textgen += global_textgen[j]['text']+space;
  }
  if (global_textgen[j]['id']=='firmversion') {
    textgen += "<?php echo shell_exec ( "cat /www/fwversion.txt | tr '\n' ' '" );?>";
  }
   /* if (global_textgen[j]['id']=='fpgatemp') {
    textgen += global_textgen[j]['value']+' ';
  }*/
  if (typeof global_textgen[j]['value']!== 'undefined') {
    textgen += global_textgen[j]['value']+space;
  }
 j+=1;
 });
 
 if (textgen !== memo_textgen) {
   console.log ('textgen = '+textgen);
   write_text(textgen);
   if (mqtt.isConnected()) {
     sendmqtt('plutodvb/subvar/gentext',textgen) ;
     //sendmqtt('plutodvb/var','[{"gentext":"'+textgen+'"') ;
   }
   $('#displaytextgen').text(textgen);
 }
 

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
                if (item.id == 'fpgatemp') {
                  mqtt.subscribe("plutodvb/status/"+item.id); 
                }

              }
            });
           // update_textgen('novar','noval'); 
           if (mqtt.isConnected()) {
            message = new Paho.MQTT.Message('Ask');
            message.destinationName = "plutodvb/textgen/updaterequest";
            mqtt.send(message);
          }
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

       $.get( "requests.php?cmd="+encodeURIComponent("echo <?php echo $mark;?>["+($(this).nestable('serializeplus'))+"]<?php echo $mark;?> > <?php echo $dir ?>text_gen_available_items.json"), function( data, status ) {
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
      $.get( "requests.php?cmd="+encodeURIComponent("echo <?php echo $mark;?>["+($(this).nestable('serializeplus'))+"]<?php echo $mark;?> > <?php echo $dir ?>text_gen_set_items.json"), function( data, status ) {

        if (status=='success') { 

          
          if (typeof json2nestable2 == 'function') {
           json2nestable2(); //update textgen  
          }

          //$('#aa').fadeIn(250).fadeOut(1500);
            }
      });

    });

function write_text(texttofile) {
  $.get( "requests.php?cmd="+encodeURIComponent("echo <?php echo $mark;?>"+texttofile+"<?php echo $mark;?> > <?php echo $dirtemp ?>plutotext.txt"), function( data, status ) {
    if (status=='success') { 
      //$('#aa').fadeIn(250).fadeOut(1500);
    }
  });
}


$('#addfreetext').click(function(){
  var max = 0;
  //numItems = $('[data-id^=freetext]').length +1 ;
  $('[data-id^=freetext]').each(function() {
      max = Math.max($(this).data('id').substr(8), max);
      console.log('this '+$(this).data('id'));
  });
  max=max+1;
  $("#nestable ol").prepend('<li class="dd-item" data-id="freetext'+max+'" data-type="freetext"><div class="dd-handle freetext" >Editable freetext</div><div class="line"><span class="a" spellcheck="false" contentEditable="true">Customize here</span><span class="del" style="float : right">✖️</span></div></li>').change(); 
})
$('#addmqttvar').click(function(){
  var max = 0;
  //numItems = $('[data-id^=freetext]').length +1 ;
  $('[data-id^=freetext]').each(function() {
      max = Math.max($(this).data('id').substr(8), max);
      console.log('this '+$(this).data('id'));
  });
  max=max+1;
  $("#nestable ol").prepend('<li class="dd-item" data-id="freemqtt'+max+'" data-type="freemqtt"><div class="dd-handle freemqtt" >MQTT variable</div><div class="line"><span class="a" spellcheck="false" contentEditable="true">Type mqtt topic here</span><span class="del" style="float : right">✖️</span></div></li>'); 
})



$('.dd-placeholder').click(function(event) {
//$('li[data-id^=freemqtt]').mousedown(function(event) {
//$('li[data-id^=freemqtt]').dblclick( function() {
switch (event.which) {
  case 1 : //left
    break;
  case 2 : //middle
    break;
  case 3 : //right
  let sign = prompt("What's your text?");

  console.log('ok');
  window.prompt("sometext","defaultText");
  $(this).attr('contentEditable','true');
  $(this).addClass('inEdit');
  break;

}
});
$('li[data-id^=freemqtt]').blur( function() {
  $(this).attr('contentEditable','false');
  $(this).removeClass('inEdit');
})





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
$('body').on('click', '.del', function () {
  if (confirm('Are you sure you want to delete this item ?')) {
  $(this).parent().parent().remove();
  $('#nestable').change(); //save
  $('#nestable2').change(); //save

  } 
});


$('body').on('focus', '[contenteditable]', function() {
    const $this = $(this);
    $this.data('before', $this.html());
}).on('blur', '[contenteditable]', function() {
    const $this = $(this);
    if ($this.data('before') !== $this.html()) {
        $this.data('before', $this.html());
        $this.trigger('change');
        json2nestable2();

    }

});

  json2nestable(); //load textgenerator
  json2nestable2();

});

</script>
</body>
</html>
