  <?php
  session_start();
  ?>
  <?php
  if ( isset( $_POST[ 'savefw' ] ) ) {
    exec( '/root/writeconfig_to_env.sh' );
  }
  ?>
  <?php
  if ( isset( $_POST[ 'reboot' ] ) ) {
    exec( '/sbin/reboot' );
  }
  ?>
  <?php
  if ( isset( $_POST[ 'delpatch' ] ) ) {
    exec( 'rm  /mnt/jffs2/patch.zip' );
  }
  ?>
  <!doctype html>
  <html>
  <head>
    <meta charset="UTF-8">

    <style>
      .slidernewpower {
        width: 500px;
      }

      .btn {
        border: none;
        color: white;
        padding: 14px 28px;
        font-size: 16px;
        cursor: pointer;
      }

      .success {background-color: #4CAF50;} /* Green */
      .success:hover {background-color: #46a049;}

      .info {background-color: #2196F3;} /* Blue */
      .info:hover {background: #0b7dda;}

      .warning {background-color: #ff9800;} /* Orange */
      .warning:hover {background: #e68a00;}

      .danger {background-color: #f44336;} /* Red */
      .danger:hover {background: #da190b;}

      .default {background-color: #e7e7e7; color: black;} /* Gray */
      .default:hover {background: #ddd;}

      .h265box-manual {display: none;}
    </style>

    <title>PlutoDVB Credits</title>
    <meta name="description" content="PlutoDVB Credits ">
    <meta http-equiv="Cache-Control" content="no-cache" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />        
    <link type="text/css" href="./img/style.css" rel="stylesheet">
    <script src="lib/jquery-3.5.1.min.js"></script>
    <script src="lib/u16Websocket.js"></script>
    <script src="lib/js.cookie.min.js"></script>
    <script src="lib/tooltip.js"></script>
    <script src="lib/mqttws31.js"></script>      
    <script src="lib/mqtt.js.php?page=<?php echo basename($_SERVER["SCRIPT_FILENAME"]); ?>"></script>        
    <link type="text/css" href="./lib/tooltip.css" rel="stylesheet">
    <link type="text/css" href="./lib/menu.css" rel="stylesheet">
    <link href="img/favicon-32x32.png" rel="icon" type="image/png" />
    


  </head>

  <body>

    <?php include ('lib/menu_header.php'); ?>


    <header id="maintitle"> <h1>PlutoDVB Credits</h1>

  </header>





<br>
  <img src='img/plutoDVB.svg' width="150px">


<section id="f5oeo" style="display: inline-block;">
<h3 id="about">Evariste, F5OEO</h3>
<div class="colLeft">
<p id="test"><strong>Author</strong></p>  
<p>Hamradio call F5OEO since 1995, electronic and computer science engineer. Interested in experimenting rather than communicating. Main interests : SDR, embedded platform (raspberry pi), digital television (DVB). </p>
<p id="test"><strong>Support the author</strong></p>
<p>Even most of this development is done under opensource (GPL), I should apreciate donation for integration, development and materials :  <a href="https://www.paypal.me/f5oeo" target="_blank">Donate</a></p>
<p></p>
</div>
<div class="colRight" style='text-align: left;'>
  <p id="test"><strong>Main projects</strong></p>
<ul style="margin-left:8em; margin-top:0em;">
    <li><a href="https://github.com/F5OEO/rpidatv">rpidatv : Standalone DVBS2 raspberry pi modulator</a></li>
    <li><a href="https://github.com/F5OEO/rpitx">rpitx : Low cost sdr transmitter on raspberry pi </a></li>
    <li><a href="https://github.com/F5OEO/avc2ts">avc2ts : Dvb compliant H264 encoder for raspberry pi</a></li>
    <li><a href="https://github.com/F5OEO/dvbsdr">dvbsdr : Script collection handling various encoders and modulators</a></li>
    
  </ul>
</div>
</section>

<section id="f5uii" style="display: inline-block;">
<h3 id="about">Christian, F5UII</h3>
<div class="colLeft">
<p id="test"><strong>Contributor</strong></p>  
<p>Hamradio call F5UII since 1993, computer science engineer. Likes many aspects of the hobby and is a member of the <a href="http://www.fy5ke.org/?page_id=795" target="_blank">FY5KE contest</a> team. Discovered a lot of new domains thanks to the QO-100 satellite : Hyperfrequency, SDR, digital television (DVB). </p><p>I contribute to PlutoDVB mainly on the man-machine interface (representation standardization, logo, multiple modulation tabs, exchange files), on the communication with third party tools (Minitiouner, OBS Studio, IoT,...). </p>

<p>
  <p>You may have good ideas for further development of this software. You can bring them on <a href="https://www.f5uii.net/en/patch-plutodvb/?o=<?php
        echo "$fwver";
        ?>" title="Go to Chris blog and ressources" target="_blank">my blog</a>. You can help me to continue the developments and projects around plutoSDR, by making a gift purchase of an item content published on the public wish list, or <a href="https://www.buymeacoffee.com/f5uii" target="_blank">buy me a cofee</a>. 😉 73 Chris F5UII</p>
</p>
</div>
<div class="colRight" style='text-align: left;'>
  <p id="test"><strong>Main projects</strong></p>
<ul style="margin-left:8em; margin-top:0em;">
    <li><a href="https://www.phase4a.eu">phase4a.eu : DATV online monitoring</a></li>
    <li><a href="https://www.f5uii.net/en/patch-plutodvb/">Patches for PlutoDVB</a></li>
    <li><a href="https://www.f5uii.net">Blog f5uii.net : With many tutorials (SDR, MMDVM DMR) </a></li>
  </ul>
</div>

</section>
<section style="display: inline-block;" >
<p>
<h3 id="behind">Behind the scene</h3>
<ul>
  <li> G4GUO Charles : C++ sourcecode reference for DVBS/S2/T modulator implementation</li>
  <li> G4EWJ Brian : arm assembler DVBS modulator, arm neon DVB-S2 modulator</li>
  <li> F4DAV Pascal : DVBS demodulator</li>
  <li> M0DNY Phil : BATC QO100 wb spectrum</li>
  <li> DJ0ABR : Spectrum and waterfall QO100 example</li>
  <li> Lelegard Thierry : TsDuck transport stream processing</li>
</ul>
</p>
<section style="display: inline-block;" >
<p>
<h3 id="technology">Onboard technology</h3>
<ul>
  <li><img src="img/mqtt-logo.svg" style="width: 100px; height: 40px;  margin-right: 15px; vertical-align: middle;"> The Standard for IoT Messaging. Mosquitto broker is embedded. The dictionnary for interface PlutoDVB is <a href="https://github.com/F5OEO/datvplutofrm/blob/topic-f5uii/README.MQTT.md" target="_blank">published</a>.</li>
  <li><img src="img/PHP-logo.svg" style="height: 40px;   margin-right: 15px; vertical-align: middle;">PHP free programming language for the web</li>
  <li><img src="img/OBS.svg" style="height: 40px;   margin-right: 15px; vertical-align: middle;"> OBS Studio Websocket, connection with the open-source video streaming softmware <a href="https://obsproject.com/" target="_blank">OBS Studio</a></li>
</ul>
</p>
<h3> Other contributions </h3>
<section style=" text-align: left;">
  <ul>
        <li >Thanks Rob M0DTS for help. Mods by G4EML for codec selection and sound enable</li>
        


                <li >Mods by Chris <a href="https://www.f5uii.net/?o=2110
" title="Go to Chris blog and ressources" target="_blank">F5UII.net</a>&nbsp; <a href="https://twitter.com/f5uii/" title="Go to f5uii profile on twitter"><img style="width: 20px;" src="./img/tw.png" alt="Twitter Logo"></a> version <i id='patch-uii'>UII2.5</i><div id="note">A new UII patch is available<span id = 'uii-new-version'></span>. Follow <a href= "https://www.f5uii.net/en/patch-plutodvb/?ori=update" target="_blank">this link</a>.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <a id="close">❌</a></div>: <span class="note tooltip" title="
        <strong>Version UII2.5 - 03/01/2021</strong><ul><li>Steering compatible with Longmynd patched with <a href='https://forum.batc.org.uk/viewtopic.php?f=101&t=6594&p=25786&hilit=g7jtt#p22243' target='_blank'>G7JTT script (Thanks to G8UGD)</a></li><li>New PlutoDVB <a href='setup.php'>setup page</a> (under development, not everything is functional yet)</li><li>Text (banner) generator</li><li>Mini and maximum adjustable power </li><li>Display of absolute output power expressed in dB and watts</li><li>masking of the H265 encoder control panels if set as such</li><li>Manual control of H265 encoder parameters</li></ul>
        <strong>Version UII2.4 - 29/11/2020</strong><ul><li>Right click on modulator profiles (lock/unlock + Duplicate + Copy 3 items crosswise)</li><li>Keyboard shortcuts (F9=Apply modulator settings, F10=PTT toggle)</li><li>Analysis page, reception with crossfade display (Not perfect yet)</li></ul>
        <strong>previous versions, see support page</strong><hr>🛈 Link to <a href='https://www.f5uii.net/en/patch-plutodvb/?o=2110
' target='_blank'>download, roadmap and support page">

        </a>Details</span></li>
        <li >Mods by Roberto IS0GRB (Save SpectrumView button state,Show how much patch.zip inserted (August 29th, 2020)</li>
        <br>
      </section>
<a class="anchor" href="#top">Back to top</a>
</ul>
</section>
<script>




  setInterval(function() {
    get_update('PatchUII'); //Check new patch version over internet (every 1h) = 3600000
  },  3600000 );

  $('#close').click(function () {
   $('#note').css("display", "none");
     // Repeat notice about update 1 day after close the notice
     Cookies.set('note', 'closed ' + $('#patch-uii').text().toUpperCase(), { expires: 1 });
   });

  function get_update (target) {
    if (target=='PatchUII' ) {
      if (navigator.onLine) {
        $.ajax({
          url: "https://www.f5uii.net/patch/patch-version.php",
          success: function(data) {
            let p_uii= data;
            if (p_uii !== undefined)
              if ((p_uii.toUpperCase()!=$('#patch-uii').text().toUpperCase()) && Cookies.get('note') !='closed '+ $('#patch-uii').text().toUpperCase())
              {
                $('#note').css("display", "block");
                $('#uii-new-version').html(' <b>('+ p_uii.toUpperCase()+ ')</b> ');
              } else
              {
                $('#note').css("display", "none");
              }
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {
              console.log('internet lost');
            }
          });
      }
      console.log('Check PatchUII');
    }

  }



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

</script>
<script>
  MQTTconnect();
</script>
</body>
</html>
