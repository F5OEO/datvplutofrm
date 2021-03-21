    <?php
    // F5UII : Setup page. The outputs are multiples files, working by ajax call with global_save.php.

    session_start();
   require_once ('./lib/functions.php');
 
  

  ?>
  <!doctype html>

  <html>
 
  <head>
    <meta charset="UTF-8">

    <title id='lng_title_page'>PlutoDVB General setup</title>
    <meta name="description" content="ADALM-PLUTO DVB General Setup ">
    <meta http-equiv="Cache-Control" content="no-cache" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />    
    <link type="text/css" href="./img/style.css" rel="stylesheet">
    <link type="text/css" href="lib/nestable.css" rel="stylesheet">
    <script src="lib/jquery-3.5.1.min.js"></script>
    <script src="lib/tooltip.js"></script>
    <script src="lib/jquery.nestable.js"></script> 
    <script src="lib/mqttws31.js"></script>  
    <script src="lib/obs-websocket.js"></script>
    <script src="obs.js.php"></script>
    <script src="lib/jquery.MultiLanguage.min.js"></script>  
    <script src="lib/mqtt.js.php?page=<?php echo basename($_SERVER["SCRIPT_FILENAME"]); ?>"></script>  
    <link type="text/css" href="./lib/tooltip.css" rel="stylesheet">
    <link type="text/css" href="./lib/menu.css" rel="stylesheet">
    <link href="img/favicon-32x32.png" rel="icon" type="image/png" />
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
   <h1 id='lng_title'>PlutoDVB General setup</h1> 
   
   <hr>
   <section id="linkdatvmode"></section>
      <form id="general" name="datv_config" method="post" action = "javascript:save_config_setup('general','<?php echo urlencode($file_general)?>', '<?php echo rawurlencode($general_ini[0]) ?>');">
   
   <h2 id='lng_mainmode'> Main mode selection</h2>
   <input type="radio" id="mainmode"
     name="mainmode" value="datv" <?php if (isset($datv_config['mainmode']))  echo $datv_config['mainmode']=='datv' ? " checked" :  "" ?>>
    <label for="datv">DATV</label>

    <input type="radio" id="mainmode"
     name="mainmode" value="pass" <?php if (isset($datv_config['mainmode']))  echo $datv_config['mainmode']=='pass' ? " checked" :  "" ?>>
    <label for="pass" id="lng_passt">Passthrough (SDR Console,...)</label>

    <input type="radio" id="mainmode"
     name="mainmode" value="transverter" <?php if (isset($datv_config['mainmode']))  echo $datv_config['mainmode']=='transverter' ? " checked" :  "" ?>>
    <label for="transverter"><span id="transverter_tt" class="note tooltip" title="Not available" style="color : #636363;">Transverter</span></label>

    <input type="radio" id="mainmode"
     name="mainmode" value="signal_generator" <?php if (isset($datv_config['mainmode']))  echo $datv_config['mainmode']=='signal_generator' ? " checked" :  "" ?>>
    <label for="signal_generator"><span id="signalgen_tt"class="note tooltip" title="Not available" style="color : #636363;">Signal generator</span></label>

  </div>
  <h2 id='lng_datv_mode'> DATV operating mode</h2>

   <input type="radio" id="datvmode"
     name="DATV[datvmode]" value="rtmp" <?php if (isset($datv_config['DATV']['datvmode']))  echo $datv_config['DATV']['datvmode']=='rtmp' ? " checked" :  "" ?>>
    <label for="rtmp"><span id="rtmp_tt" class="note tooltip" title="You send to PlutoDVB a transport stream on 7272 port, with rtmp (realtime messaging protocol)" style="color : #636363;">RTMP</span></label>

    <input type="radio" id="datvmode"
     name="DATV[datvmode]" value="udp" <?php if (isset($datv_config['DATV']['datvmode']))  echo $datv_config['DATV']['datvmode']=='udp' ? " checked" :  "" ?>> 
    <label for="udp"><span id="udp_tt" class="note tooltip" title="You send to PlutoDVB a transport stream on 8282 port, with udp (user datagram protocol)" style="color : #636363;">UDP</span></label>

    <input type="radio" id="datvmode"
     name="DATV[datvmode]" value="test" <?php if (isset($datv_config['DATV']['datvmode']))  echo $datv_config['DATV']['datvmode']=='test' ? " checked" :  "" ?>>
    <label for="test" id="pattern"><span id="pattern_tt" class="note tooltip" title="Not available" style="color : #636363;">Pattern</span></label>

    <input type="radio" id="datvmode"
     name="DATV[datvmode]" value="repeater" <?php if (isset($datv_config['DATV']['datvmode']))  echo $datv_config['DATV']['datvmode']=='repeater' ? " checked" :  "" ?>>
    <label for="repeater"><span id="repeater_tt" class="note tooltip" title="Not available" style="color : #636363;">Repeater</span></label>

  </div>
  <p>
     <input type="submit" value="Apply Settings" id ="general"><span id="general_saved" class="saved"  style="display: none;"> Saved !</span>

  </p>
   <hr> <section id="linkdatvsettings"></section>
   <h2 id="datv_tx_settings">DATV transmission settings</h2>
   <script>
    var callsign_for_intial_setup ="";
    var data = localStorage.getItem('modulator_1');
    if (data !== null ) {
      var datalines = (data.split('&'));
      for (var i in datalines) {        
        var datal =(decodeURIComponent(datalines[i]).split('='));
        if (datal[0]=='callsign') { //Change callsign source to setup
          callsign_for_intial_setup = datal[1];
        } 
        if (datal[0]=='provname') { //Change callsign source to setup
          provname_for_intial_setup = datal[1];
        } 
      }
    }
    let  callsign_insetup  = '<?php if (isset($datv_config['DATV']['callsign'])) echo $datv_config['DATV']['callsign']; else echo '' ?>';
    let  provname_insetup  = '<?php if (isset($datv_config['DATV']['provname'])) echo $datv_config['DATV']['provname']; else echo '' ?>'
    if (callsign_insetup == '') {
      callsign_insetup = callsign_for_intial_setup;
    }
    if (provname_insetup == '') {
      provname_insetup = provname_for_intial_setup;
    }   
   </script>
   <h3>General use</h3>

   <table>
        <tr>
      <td><span class="note tooltip" title="Radio transmission is regulated. Input here your authorized callsign." style="color : #636363;">Callsign</span> </td>
      <td><input type="text" id="callsign" name="DATV[callsign]" value="<?php // if (isset($datv_config['DATV']['callsign'])) echo $datv_config['DATV']['callsign']; else echo '' ?>" maxlength="16" size="16"></td>

      <td><span class="note tooltip" title="You can personnalize the DVB program name" style="color : #636363;">DVB Provider Name</span></td>
      <td><input type="text" id="provname" name="DATV[provname]" value="<?php // if (isset($datv_config['DATV']['provname'])) echo $datv_config['DATV']['provname']; else echo ''; ?>" maxlength="15" size="16"></td>
   

     </tr>
     <tr>
        <td><span class="note tooltip" title="<ul><li>When enabled (yes position), at (re)start of the Pluto, the transmit is activated. This feature is welcome for restart quickly transmission after an unexpected power cut.</li><li>When disabled (no position), the Pluto stay stand-by at (re)start.</li></ul>" style="color : #636363;">Transmission permitted at start-up</span><br></td>
        <td><div class="checkcontainer">

          <input type="checkbox" id="tx_onstart" name="DATV[tx_onstart]" <?php if (isset($datv_config['DATV']['tx_onstart']))  echo $datv_config['DATV']['tx_onstart']=='on' ? " checked" :  "" ?>>
          <label for="tx_onstart" aria-describedby="label"><span class="ui"></span> <span id='tx_onstart_label'> enabled</span></label>
        </div> </td>  
        <td><span class="note tooltip" title="<ul><li>When set to a value different than 0, the absolute power conversion (abs) is displayed in dBm and also Watt unit on the controller. </li><li>The value can be positive or negative.</li></ul>This conversion is meant to be simplistic and linear and does not take into consideration the non-linearities and saturations of the amplifiers..." style="color : #636363;">Conversion gain to display the real power (approximate absolute output level) </span><i>(dB)</i></td>
        <td><input type="text" id="abs_gain" name="DATV[abs_gain]" value="<?php if (isset($datv_config['DATV']['abs_gain'])) echo $datv_config['DATV']['abs_gain']; ?>" maxlength="4" size="4"></td>

     </tr>
     
     <tr>
        <td><span class="note tooltip" title="Limits the stroke of the power adjustment.<br/>  ⚠️ This setting is not to be considered as an absolute protection against overpower.<br>It is highly recommended for safety of your transmision line to ensure it by inserting suitable RF attenuators.<p>The value to be indicated is the relative power (maximum 0dB which corresponds to the maximum output power of the Pluto). The expected value is therefore <strong>negative</strong></p>" style="color : #636363;">Maximum adjustable power</span>  <i>(dB)</i></td>
        <td><input type="text" id="hi_power_limit" name="DATV[hi_power_limit]" value="<?php if (isset($datv_config['DATV']['hi_power_limit'])) echo $datv_config['DATV']['hi_power_limit']; ?>" maxlength="6" size="6"></td>
        <td><span class="note tooltip" title="Minimum  adjustable low stop of the relative output power" style="color : #636363;">Minimum adjustable power</span>  <i>(dB)</i></td>
        <td><input type="text" id="lo_power_limit" name="DATV[lo_power_limit]" value="<?php if (isset($datv_config['DATV']['lo_power_limit'])) echo $datv_config['DATV']['lo_power_limit']; ?>" maxlength="6" size="6"></td>        


   

     </tr>
   </table>
   <h3>OBS Studio steering</h3>
   <table>
          <tr>
        <td><span class="note tooltip" title="<ul><li>Before activating this function, you need to install the websocket plugin to OBS Studio. (<a href ='https://obsproject.com/forum/resources/obs-websocket-remote-control-obs-studio-from-websockets.466/' target='_blank' >Remote-control OBS Studio from WebSockets Plugin</a> - for download, see <a href='https://github.com/Palakis/obs-websocket/releases' target='_blank'>Install instructions</a>) </li><li>This allows you to drive OBS Studio directly from PlutoDVB.</li><li> The main feature is, usefull when you are working in RTMP mode, to write directly in OBS Studio the command line with the corresponding parameters (Parameters/Stream/Server). This action takes place when you click  on a channel (horizontal segment of the QO100 spectrum). You are also be able to activate streaming or recording from the buttons on the controller page.</li>" style="color : #636363;">Activation of OBS Studio steering</span><br></td>
        <td><div class="checkcontainer">

          <input type="checkbox" id="use_obs_steering" name="OBS[use_obs_steering]" <?php if (isset($datv_config['OBS']['use_obs_steering']))  echo $datv_config['OBS']['use_obs_steering']=='on' ? " checked" :  "" ?>>
          <label for="use_obs_steering" aria-describedby="label"><span class="ui"></span> <span id='use_obs_steering_label'> enabled</span></label>
        </div> </td>
        <td><span class="note tooltip" title="Address of your PC on which OBS Studio is running. If OBS Studio is on the same PC that your browser used for PlutoDVB, you can type <i>localhost</i>. The online status is updated after moving the cursor out of the input field and at the upload of the setup.<ul><li>✔️ : Is online from the Pluto (good answer to the ping command).</li><li>✖️ : Seems not online from the Pluto (no answer to the ping command)</li> " style="color : #636363;">OBS Studio IP address</span></td>

        <td><input type="text" id="ipaddr_obs" name="OBS[ipaddr_obs]" value="<?php  if (isset($datv_config['OBS']['ipaddr_obs'])) { $ping_ip_obs= $datv_config['OBS']['ipaddr_obs'] ;} else { $ping_ip_obs=  '192.168.1.111'; } ; echo $ping_ip_obs; ?>" maxlength="15" size="16"> <?php $a= shell_exec ("ping -W 1 -c 1 ".$ping_ip_obs); if (strpos($a, ", 100% packet loss") > 0) {$r= " ✖️";} else { $r= " ✔️"; } ?><span id="ipaddr_obs_status"><?php echo $r; ?></span></td>
      </tr>
      <tr>
        <td><span class="note tooltip" title="Default OBS Studio websocket port is 4444." style="color : #636363;">OBS websocket port</span> </td>
        <td><input type="text" id="obs_port" name="OBS[obs_port]" value="<?php if (isset($datv_config['OBS']['obs_port'])) echo $datv_config['OBS']['obs_port']; else echo "4444" ?>" maxlength="6" size="6"></td>

        <td><span class="note tooltip" title="By default, there is no password (empty)." style="color : #636363;">OBS websocket password</span></td>
        <td><input type="text" id="obs_password" name="OBS[obs_password]" value="<?php if (isset($datv_config['OBS']['obs_password'])) echo $datv_config['OBS']['obs_password']; else echo ""?>" maxlength="8" size="8"></td>
     

       </tr>
       <tr>
         <td>Destination OBS Studio text</td><td><span id='obs_text_destination'>No selection, OBS Studio steering must be enabled (<i>under developpement</i>)</span></td>
       </tr>
   </table>
   <h3>H264/H265 box</h3>
   <table>
     <tr>
        <td><span class="note tooltip" title="<ul><li>When enabled (yes position), the H264/H265 section is displayed on the controller page. " style="color : #636363;">Use of a H264/H265 box</span><br></td>
        <td><div class="checkcontainer">

          <input type="checkbox" id="use_h265box" name="H265BOX[use_h265box]" <?php if (isset($datv_config['H265BOX']['use_h265box']))  echo $datv_config['H265BOX']['use_h265box']=='on' ? " checked" :  "" ?>>
          <label for="use_h265box" aria-describedby="label"><span class="ui"></span> <span id='use_h265box_label'> enabled</span></label>
        </div> </td>
        <td><span class="note tooltip" title="Address of your H264/H265 encoder box. The online status is updated after moving the cursor out of the input field and at the upload of the setup.<ul><li>✔️ : Is online from the Pluto (good answer to the ping command).</li><li>✖️ : Seems not online from the Pluto (no answer to the ping command)</li> " style="color : #636363;">IP address</span></td>

        <td><input type="text" id="ipaddr_h265box" name="H265BOX[ipaddr_h265box]" value="<?php  if (isset($datv_config['H265BOX']['use_h265box'])) { $ping_ip= $datv_config['H265BOX']['ipaddr_h265box'] ;} else { $ping_ip=  '192.168.1.120'; } ; echo $ping_ip; ?>" maxlength="15" size="16"> <?php $a= shell_exec ("ping -W 1 -c 1 ".$ping_ip); if (strpos($a, ", 100% packet loss") > 0) {$r= " ✖️";} else { $r= " ✔️"; } ?><span id="ipaddr_h265box_status"><?php echo $r; ?></span></td>
      </tr>
      <tr>
          <td><span class="note tooltip" title="'admin' by default" style="color : #636363;">Administrator login</span> </td>
          <td><input type="text" id="h265box_login" name="H265BOX[h265box_login]" value="<?php if (isset($datv_config['H265BOX']['h265box_login'])) echo $datv_config['H265BOX']['h265box_login']; else echo "admin" ?>" maxlength="6" size="6"></td>

          <td><span class="note tooltip" title="'12345' by default." style="color : #636363;">Password</span></td>
          <td><input type="text" id="h265box_password" name="H265BOX[h265box_password]" value="<?php if (isset($datv_config['H265BOX']['h265box_password'])) echo $datv_config['H265BOX']['h265box_password']; else echo "12345"?>" maxlength="8" size="8"></td>
     

       </tr>

    
   </table>
   <p>
   <input type="submit" value="Apply Settings" id ="general"><span id="general_saved" class="saved"  style="display: none;"> Saved !</span>
 </p>

  <h2>Strategy Setting table</h2>
  This modifiable table makes it easy to set the parameters of the H264/265 encoder automatically according to the stream transport rate (depending on the transmitted signal characteristics).<br/>
Attention, in this version the editable cells are not verified at all.
  <style type="text/css">
.tg  {border-collapse:collapse;border-color:#9ABAD9;border-spacing:0;   margin-left: auto;
  margin-right: auto;}
.tg td{background-color:#EBF5FF;border-color:#ddd;border: 1px dashed ;color:#444;
  font-family:Arial, sans-serif;font-size:14px;overflow:hidden;padding:10px 5px;word-break:normal;}
.tg th{background-color:#409cff;border-color:#9ABAD9;border: 1px dashed ;color:#fff;
  font-family:Arial, sans-serif;font-size:14px;font-weight:normal;overflow:hidden;padding:10px 5px;word-break:normal;}
.tg .tg-88b2{background-color:#33b3ca;border-color:inherit;text-align:center;vertical-align:top}
.tg .tg-wpev{border-color:#ddd;text-align:center;vertical-align:top}

[contenteditable] { padding: 5px; outline: 0px solid transparent; border-radius: 3px; }
[contenteditable]:not(:focus) { border: 1px dashed #ddd; }
[contenteditable]:focus { border: 1px solid #51a7e8; box-shadow: inset 0 1px 2px rgba(0,0,0,0.075),0 0 5px rgba(81,167,232,0.5); }
</style>
<table id="strategy_tab" class="tg" style="undefined;table-layout: fixed; width: 745px">
<colgroup>
<col style="width: 58px">
<col style="width: 89px">
<col style="width: 90px">
<col style="width: 90px">
<col style="width: 90px">
<col style="width: 90px">
<col style="width: 90px">
<col style="width: 90px">
<col style="width: 90px">
<col style="width: 90px">
<col style="width: 90px">
</colgroup>
<thead>

  <tr>
    <th class="tg-88b2" >Priority</th>
    <th class="tg-88b2" >Total bitrate available</th>
    <th class="tg-88b2">Audio channels</th>
    <th class="tg-88b2">Audio Bitrate<br>(kb/s)</th>
    <th class="tg-88b2">GOP</th>
    <th class="tg-88b2">Video Width</th>
    <th class="tg-88b2">Video Height</th>
    <th class="tg-88b2">FPS</th>
    <th class="tg-88b2">Video Rate<br>(kb/s)</th>
    <th class="tg-88b2">PCR/PTS<br>(ms)</th>
    <th class="tg-88b2">PAT period<br>(ms)</th>    
  </tr>
</thead>
<tbody>
  <tr id="tr1">
    <td class="tg-wpev">1</td>
    <td class="tg-wpev" id="td-1" contenteditable="true">5000</td>
    <td class="tg-wpev" id="td-2" contenteditable="true">2</td>
    <td class="tg-wpev" id="td-3" contenteditable="true">64</td>
    <td class="tg-wpev" id="td-4" contenteditable="true">200</td>
    <td class="tg-wpev" id="td-5" contenteditable="true">1920</td>
    <td class="tg-wpev" id="td-6" contenteditable="true">1080</td>
    <td class="tg-wpev" id="td-7" contenteditable="true">30</td>
    <td class="tg-wpev" id="td-8" contenteditable="true">1000</td>
    <td class="tg-wpev" id="td-9" contenteditable="true">800</td>
    <td class="tg-wpev" id="td-10" contenteditable="true">200</td>
  </tr>
  <tr id="tr2">
    <td class="tg-wpev">2</td>
    <td class="tg-wpev" contenteditable="true">1200</td>
    <td class="tg-wpev" contenteditable="true">2</td>
    <td class="tg-wpev" contenteditable="true">64</td>
    <td class="tg-wpev" contenteditable="true">50</td>
    <td class="tg-wpev" contenteditable="true">1920</td>
    <td class="tg-wpev" contenteditable="true">1080</td>
    <td class="tg-wpev" contenteditable="true">25</td>
    <td class="tg-wpev" contenteditable="true">999</td>
    <td class="tg-wpev" id="td-9" contenteditable="true">800</td>
    <td class="tg-wpev" id="td-10" contenteditable="true">200</td>
  </tr>
  <tr>
    <td class="tg-wpev">3</td>
    <td class="tg-wpev" contenteditable="true">400</td>
    <td class="tg-wpev" contenteditable="true">2</td>
    <td class="tg-wpev" contenteditable="true">32</td>
    <td class="tg-wpev" contenteditable="true">50</td>
    <td class="tg-wpev" contenteditable="true">768</td>
    <td class="tg-wpev" contenteditable="true">432</td>
    <td class="tg-wpev" contenteditable="true">25</td>
    <td class="tg-wpev" contenteditable="true">300</td>
    <td class="tg-wpev" id="td-9" contenteditable="true">800</td>
    <td class="tg-wpev" id="td-10" contenteditable="true">200</td>
  </tr>
  <tr>
    <td class="tg-wpev">4</td>
    <td class="tg-wpev" contenteditable="true">250</td>
    <td class="tg-wpev" contenteditable="true">1</td>
    <td class="tg-wpev" contenteditable="true">32</td>
    <td class="tg-wpev" contenteditable="true">30</td>
    <td class="tg-wpev" contenteditable="true">756</td>
    <td class="tg-wpev" contenteditable="true">324</td>
    <td class="tg-wpev" contenteditable="true">15</td>
    <td class="tg-wpev" contenteditable="true">200</td>
    <td class="tg-wpev" id="td-9" contenteditable="true">800</td>
    <td class="tg-wpev" id="td-10" contenteditable="true">200</td>
  </tr>
  <tr>
    <td class="tg-wpev">5</td>
    <td class="tg-wpev" contenteditable="true">200</td>
    <td class="tg-wpev" contenteditable="true">1</td>
    <td class="tg-wpev" contenteditable="true">32</td>
    <td class="tg-wpev" contenteditable="true">30</td>
    <td class="tg-wpev" contenteditable="true">384</td>
    <td class="tg-wpev" contenteditable="true">216</td>
    <td class="tg-wpev" contenteditable="true">15</td>
    <td class="tg-wpev" contenteditable="true">120</td>
    <td class="tg-wpev" id="td-9" contenteditable="true">800</td>
    <td class="tg-wpev" id="td-10" contenteditable="true">200</td>
  </tr>
  <tr>
    <td class="tg-wpev">6</td>
    <td class="tg-wpev" contenteditable="true">100</td>
    <td class="tg-wpev" contenteditable="true">1</td>
    <td class="tg-wpev" contenteditable="true">32</td>
    <td class="tg-wpev" contenteditable="true">20</td>
    <td class="tg-wpev" contenteditable="true">384</td>
    <td class="tg-wpev" contenteditable="true">216</td>
    <td class="tg-wpev" contenteditable="true">10</td>
    <td class="tg-wpev" contenteditable="true">64</td>
    <td class="tg-wpev" id="td-9" contenteditable="true">800</td>
    <td class="tg-wpev" id="td-10" contenteditable="true">200</td>
  </tr>
</tbody>
</table>
<br/>
    <input type="submit" value="Apply Settings" id ="st" onclick="table2json()"><span id="aa"   style="display: none;"> Saved !</span>

   <h2>Avanced ⚠️ for expert use only</h2>


   <h3>16APSK, 32APSK characteristics</h3>
   <br>
    <table>

     <tr>
        <td><span class="note tooltip" title="Allows to correct a phase shift by balancing the central points around the center of the constellation<ul><li>on the central points of 16ASPK mod</li></ul><p>💡 For fine adjustment, click on the slider and then use the up and down keys<br/> For an adjustment in steps of 10% of the full scale, click on the slider and then use the page up and page down keys.</p>" style="color : #636363;">16APSK phase</span> <i>(relative degrees)</i></td>
        <td><input type="range" min="-45" max="45" step="0.1" id="phase_correction" name="DATV_EXPERT[phase_correction]" value="<?php if (isset($datv_config['DATV_EXPERT']['phase_correction'])) echo $datv_config['DATV_EXPERT']['phase_correction']; ?>" oninput="update_slide($(this).attr('id'),1,' °')"> <span id="phase_correction-value"></span></td>    
        <td><span class="note tooltip" title="Allows to correct the distance from the constellation center<ul><li>for the central points of 16ASPK mod</li></ul><p>💡 For fine adjustment, click on the slider and then use the up and down keys<br/> For an adjustment in steps of 10% of the full scale, click on the slider and then use the page up and page down keys.</p>" style="color : #636363;">16APSK magnitude</span> <i>(relative factor)</i></td>
        <td><input type="range" min="0.4" max="2.5" step="0.01"  id="module_correction" name="DATV_EXPERT[module_correction]" value="<?php if (isset($datv_config['DATV_EXPERT']['module_correction'])) echo $datv_config['DATV_EXPERT']['module_correction']; ?>" oninput="update_slide($(this).attr('id'),2,'')"> <span id="module_correction-value"></span></td>               
     </tr>
     <tr> </tr>
     <tr>
        <td><span class="note tooltip" title="Allows to correct a phase shift by balancing the central points around the center of the constellation<ul><li>on the two central point circles of 32ASPK mod</li></ul><p>💡 For fine adjustment, click on the slider and then use the up and down keys<br/> For an adjustment in steps of 10% of the full scale, click on the slider and then use the page up and page down keys.</p>" style="color : #636363;">32APSK phase 1</span> <i>(relative degrees)</i></td>
        <td><input type="range" min="-45" max="45" step="0.1" id="phase_correction_32_1" name="DATV_EXPERT[phase_correction_32_1]" value="<?php if (isset($datv_config['DATV_EXPERT']['phase_correction_32_1'])) echo $datv_config['DATV_EXPERT']['phase_correction_32_1']; ?>" oninput="update_slide($(this).attr('id'),1,' °')"> <span id="phase_correction_32_1-value"></span></td>    
        <td><span class="note tooltip" title="Allows to correct the distance from the constellation center<ul><li>for the two central point circles of 32ASPK mod</li></ul><p>💡 For fine adjustment, click on the slider and then use the up and down keys<br/> For an adjustment in steps of 10% of the full scale, click on the slider and then use the page up and page down keys.</p>" style="color : #636363;">32APSK magnitude 1</span> <i>(relative factor)</i></td>
        <td><input type="range" min="0.4" max="2.5" step="0.01"  id="module_correction_32_1" name="DATV_EXPERT[module_correction_32_1]" value="<?php if (isset($datv_config['DATV_EXPERT']['module_correction_32_1'])) echo $datv_config['DATV_EXPERT']['module_correction_32_1']; ?>" oninput="update_slide($(this).attr('id'),2,'')"> <span id="module_correction_32_1-value"></span></td>               
     </tr>
     <tr>
        <td><span class="note tooltip" title="Allows to correct a phase shift by balancing the central points around the center of the constellation<ul><li>on the two central point circles of 32ASPK mod</li></ul><p>💡 For fine adjustment, click on the slider and then use the up and down keys<br/> For an adjustment in steps of 10% of the full scale, click on the slider and then use the page up and page down keys.</p>" style="color : #636363;">32APSK phase 2</span> <i>(relative degrees)</i></td>
        <td><input type="range" min="-45" max="45" step="0.1" id="phase_correction_32_2" name="DATV_EXPERT[phase_correction_32_2]" value="<?php if (isset($datv_config['DATV_EXPERT']['phase_correction_32_2'])) echo $datv_config['DATV_EXPERT']['phase_correction_32_2']; ?>" oninput="update_slide($(this).attr('id'),1,' °')"> <span id="phase_correction_32_2-value"></span></td>    
        <td><span class="note tooltip" title="Allows to correct the distance from the constellation center<ul><li>for the two central point circles of 32ASPK mod</li></ul><p>💡 For fine adjustment, click on the slider and then use the up and down keys<br/> For an adjustment in steps of 10% of the full scale, click on the slider and then use the page up and page down keys.</p>" style="color : #636363;">32APSK magnitude 2</span> <i>(relative factor)</i></td>
        <td><input type="range" min="0.4" max="2.5" step="0.01"  id="module_correction_32_2" name="DATV_EXPERT[module_correction_32_2]" value="<?php if (isset($datv_config['DATV_EXPERT']['module_correction_32_2'])) echo $datv_config['DATV_EXPERT']['module_correction_32_2']; ?>" oninput="update_slide($(this).attr('id'),2,'')"> <span id="module_correction_32_2-value"></span></td>               
     </tr>
     
   </table>
   <h3>Radio</h3>
<table>
    <tr>
      
      <td><span class="note tooltip" title="<strong>Not available</strong><br>Automatically switches off the transmission after the specificated duration .<ul><li>The feature is disabled when the parameter is empty or equal to zero.</li></ul>" style="color : #636363;">Watchdog</span> <i>(min)</i></td>
      <td><input type="text" id="tx_watchdog" name="DATV_EXPERT[tx_watchdog]" value="<?php if (isset($datv_config['DATV_EXPERT']['tx_watchdog'])) echo $datv_config['DATV_EXPERT']['tx_watchdog']; ?>" maxlength="4" size="4"></td>    

    
          <td><span class="note tooltip" title="To fluidify, normalize the video stream coming from the external source.<br>If you are not an expert, keep this option active." style="color : #636363;">Remux - Force compliant</span> </td>
        <td><div class="checkcontainer">
          <input type="checkbox" id="remux" name="DATV[remux]" <?php if (isset($datv_config['DATV']['remux']))  echo $datv_config['DATV']['remux']=='on' ? " checked" :  "" ?>>
          <label for="remux" aria-describedby="label"><span class="ui"></span> <span id='remux_label'> enabled</span></label>
        </div> </td>
     </tr>
   </table>
<h3>System</h3>
  <table>

      <tr>
        <td>Xo Correction</td>
        <td><input type="text" id="xo_correction" name="SYSTEM[xo_correction]" value="<?php if (isset($network_config['SYSTEM']['xo_correction'])) echo $network_config['SYSTEM']['xo_correction']; ?>" maxlength="4" size="4"></td>
        <td>UDC Handle suspend </td>
        <td><input type="text" id="udc_handle_suspend" name="SYSTEM[udc_handle_suspend]" value="<?php if (isset($network_config['SYSTEM']['udc_handle_suspend'])) echo $network_config['SYSTEM']['udc_handle_suspend']; ?>" maxlength="4" size="4"></td>

      </tr>

    </table>
    <table>

      <tr>
        <td>Diagnostic report</td>
        <td><input type="text" id="diagnostic_report" name="ACTIONS[diagnostic_report]" value="<?php if (isset($network_config['ACTIONS']['diagnostic_report'])) echo $network_config['ACTIONS']['diagnostic_report']; ?>" maxlength="4" size="4"></td>
        <td>DFU (Device Firmware Update) </td>
        <td><input type="text" id="dfu" name="ACTIONS[dfu]" value="<?php if (isset($network_config['ACTIONS']['dfu'])) echo $network_config['ACTIONS']['dfu']; ?>" maxlength="4" size="4"></td>
      </tr>
      <tr>
        <td>Reset</td>
        <td><input type="text" id="reset" name="ACTIONS[reset]" value="<?php if (isset($network_config['ACTIONS']['reset'])) echo $network_config['ACTIONS']['reset']; ?>" maxlength="4" size="4"></td>
        <td>Calibrate </td>
        <td><input type="text" id="calibrate" name="ACTIONS[calibrate]" value="<?php if (isset($network_config['ACTIONS']['calibrate'])) echo $network_config['ACTIONS']['calibrate']; ?>" maxlength="4" size="4"></td>

      </tr>
  
    </table>
    <br/>
   <input type="submit" value="Apply Settings" id ="general"><span id="general_saved" class="saved"  style="display: none;"> Saved !</span>

 <hr>
<section id="linkreceiversettings"></section>
<h2>DATV Reception</h2>
      <h3>Spectrum</h3>
      <p>You can display a wideband reception spectrum on the controller.
The source can be either an external source (internet) or the reception channel, active in full-duplex (For the safety of your Pluto receiver, be careful to use two different transmit and receive bands or switch in case of DATV traffic in simplex or near frequencies).
If you are using a transverter (e.g. an LNB), specify the offset (LNB Offset) to be used in the Receiver setup section below.</p>

        <table>
          <tr>
            <td>Spectrum</td>
              <td>
                <div class="checkcontainer">
                  <input type="checkbox" id="spectrum_enable" name="DATV_RECEIVER[spectrum_enable]" <?php if (isset($datv_config['DATV_RECEIVER']['spectrum_enable']))  echo $datv_config['DATV_RECEIVER']['spectrum_enable']=='on' ? " checked" :  "" ?>>
                  <label for="spectrum_enable" aria-describedby="label"><span class="ui"></span> displayed</label>
                </div>
              </td>
              <td>Spectrum Band source <i></i></td>
              <td>
                <select name="DATV_RECEIVER[spectrum-source]" >
                  <option value="QO100-web" <?php if (isset($datv_config['DATV_RECEIVER']['spectrum-source']))  echo $datv_config['DATV_RECEIVER']['spectrum-source']=='QO100-web' ? " selected" :  "" ?>>QO-100 BATC AMSAT-UK web</option>
                  <option value="QO100-pluto" <?php if (isset($datv_config['DATV_RECEIVER']['spectrum-source']))  echo $datv_config['DATV_RECEIVER']['spectrum-source']=='QO100-pluto' ? " selected" :  "" ?>>QO-100 Pluto reception</option>
                 <!-- <option value="70cm" <?php if (isset($datv_config['DATV_RECEIVER']['spectrum-source']))  echo $datv_config['DATV_RECEIVER']['spectrum-source']=='70cm' ? " selected" :  "" ?>>70cm band Pluto reception</option>
                  <option value="23cm" <?php if (isset($datv_config['DATV_RECEIVER']['spectrum-source']))  echo $datv_config['DATV_RECEIVER']['spectrum-source']=='23cm' ? " selected" :  "" ?>>23cm band Pluto reception</option>            -->       
                </select>
              </td>

            </tr>
          </table>
                <p>The QO-100 spectrum is an online ressource of <a href="https://batc.org.uk/?origin=plutodvb" target="_blank"> BATC</a> / <a href="https://amsat-uk.org/?origin=plutodvb" target="_blank">AMSAT-UK</a>. We thank them for making this resource available.  </p>

          <h3><span class="note tooltip"  style="color: #636363;" title= '<h2>Control your transmission frequency and paste the RTMP URL string</h2>
       <p>At the bottom of QO-100 Spectrum, there are horizontal bars representing the possible transmission channels on the satellite. By simply clicking on a bar, <ul><li>you will report  the corresponding transmission frequency in the Modulator table, in <i>Freq</i> field. This will take account of your possible transverter settings.</li><li>the chosen channel frequency is copied in your clipboard so that you can easily paste where you want.</li><li>then, by clicking on the text <i>Click here to copy RTMP server URL in </i>📋, you will directly copy in clipboard the whole string that is waiting in the destination URL. You will be able to simply paste it in URL field of your stream software like OBS Studio, or Vmix (the <a href="index.html#test" >RTMP string</a> is set with all parameters set in Modulator table, to be paste).</li></ul> </p>
       <h3>About Minitiouner</h3><img src="./img/minitiouner.jpg" style="
       float: right;"/>
       <p>The Minitiouner hardware is designed for easy use with the software Minitiouner Pro conceived by F6DZP Jean-Pierre. The support and download are free and available on <a href="http://www.vivadatv.org/" target="_blank">vivadatv forum</a>.</p>
        <h3>About control Longmynd</h3>
       This steering feature also makes it possible to control Longmynd thanks to <a href="https://forum.batc.org.uk/viewtopic.php?f=101&t=6594&p=25786&hilit=g7jtt#p22243" target="_blank">G7JTT script (Thanks to G8UGD)</a>
       <h3>How it works</h3>
       <p>To be able to directly drive your minitiouner by a simple click on a used channel of the QO-100 spectrum, you have to follow these few indications.</p>
       <p>The Ip adress and port to enter in the <i>Setup</i> tab correspond to the informations <i>Conf_AddrUDP</i> and <i>Conf_Port</i> that you find in the minitiouner configuration file <i>minitiouneConfig.ini</i>.  The IP address can also be the address of the computer on which the minitiouner is running.
        The gateway address indicated is that of your network router. Click <i>Apply Settings</i> for save your settings.</p>
        <p>Click on a signal on the spectrum. The command is sent directly to the minitiouner with the right frequency and SR, also considering your settings stored on the setup.</p>'>Receiver setup</span></h3>
          <p>To be able to control Minitioune or Longmynd, please fill in these few parameters.</p>

          <table>
            <tr>
              <td>Destination IP address</td>
              <td><input type="text"  name="DATV_RECEIVER[minitiouner-ip]" value="<?php if (isset($datv_config['DATV_RECEIVER']['minitiouner-ip'])) echo $datv_config['DATV_RECEIVER']['minitiouner-ip']; else echo '232.0.0.11'; ?>"></td>
              <td>Destination Port number <br></td>
              <td><input type="text" name="DATV_RECEIVER[minitiouner-port]" value="<?php if (isset($datv_config['DATV_RECEIVER']['minitiouner-port'])) echo $datv_config['DATV_RECEIVER']['minitiouner-port']; else echo '6789'; ?>" maxlength="15" size="16"> </td>
            </tr>
            <tr>
              <td>UDP Broadcast IP address</td>
              <td><input type="text" name="DATV_RECEIVER[minitiouner-udp-ip]" value="<?php if (isset($datv_config['DATV_RECEIVER']['minitiouner-udp-ip'])) echo $datv_config['DATV_RECEIVER']['minitiouner-udp-ip']; else echo '230.0.0.10'; ?>" ></td>
              <td>UDP Broadcast Port number <br></td>
              <td><input type="text" name="DATV_RECEIVER[minitiouner-udp-port]" value="<?php if (isset($datv_config['DATV_RECEIVER']['minitiouner-udp-port'])) echo $datv_config['DATV_RECEIVER']['minitiouner-udp-port']; else echo '230.0.0.10'; ?>" maxlength="15" size="16"> </td>
            </tr> 
            <tr>
              <td>LNB Offset <i>kHz</i></td>
              <td><input type="text" name="DATV_RECEIVER[minitiouner-offset]" value="<?php if (isset($datv_config['DATV_RECEIVER']['minitiouner-offset'])) echo $datv_config['DATV_RECEIVER']['minitiouner-offset']; else echo '9750000'; ?>"></td>
              <td>Rx socket <br></td>
              <td><select name="DATV_RECEIVER[minitiouner-socket]" >
                <option value="A" <?php if (isset($datv_config['DATV_RECEIVER']['minitiouner-socket']))  echo $datv_config['DATV_RECEIVER']['minitiouner-socket']=='A' ? " selected" :  "" ?>>A</option>
                <option value="B" <?php if (isset($datv_config['DATV_RECEIVER']['minitiouner-socket']))  echo $datv_config['DATV_RECEIVER']['minitiouner-socket']=='B' ? " selected" :  "" ?>>B</option>
              </select> </td>
            </tr>
            <tr>
              <td>LNB Voltage <i>V</i></td>
              <td>
                <select name="DATV_RECEIVER[minitiouner-voltage]" >
                  <option value="0" <?php if (isset($datv_config['DATV_RECEIVER']['minitiouner-voltage']))  echo $datv_config['DATV_RECEIVER']['minitiouner-voltage']=='0' ? " selected" :  "" ?>>0</option>
                  <option value="13" <?php if (isset($datv_config['DATV_RECEIVER']['minitiouner-voltage']))  echo $datv_config['DATV_RECEIVER']['minitiouner-voltage']=='13' ? " selected" :  "" ?>>13</option>
                  <option value="18" <?php if (isset($datv_config['DATV_RECEIVER']['minitiouner-voltage']))  echo $datv_config['DATV_RECEIVER']['minitiouner-voltage']=='18' ? " selected" :  "" ?>>18</option>
                </select>
              </td>
              <td>LNB 22 kHz <br></td>
              <td><select name="DATV_RECEIVER[minitiouner-22khz]">
                <option value="OFF" <?php if (isset($datv_config['DATV_RECEIVER']['minitiouner-22khz']))  echo $datv_config['DATV_RECEIVER']['minitiouner-22khz']=='OFF' ? " selected" :  "" ?>>Off</option>
                <option value="ON" <?php if (isset($datv_config['DATV_RECEIVER']['minitiouner-22khz']))  echo $datv_config['DATV_RECEIVER']['minitiouner-22khz']=='ON' ? " selected" :  "" ?>>On</option>
              </select> </td>
            </tr>  
            <tr>
              <td>DVB Mode <i></i></td>
              <td>
                <select name="DATV_RECEIVER[minitiouner-mode]" >
                  <option value="Auto" <?php if (isset($datv_config['DATV_RECEIVER']['minitiouner-mode']))  echo $datv_config['DATV_RECEIVER']['minitiouner-mode']=='Auto' ? " selected" :  "" ?>>Auto</option>
                  <option value="DVB-S" <?php if (isset($datv_config['DATV_RECEIVER']['minitiouner-mode']))  echo $datv_config['DATV_RECEIVER']['minitiouner-mode']=='DVB-S' ? " selected" :  "" ?>>DVBS</option>
                  <option value="DVB-S2" <?php if (isset($datv_config['DATV_RECEIVER']['minitiouner-mode']))  echo $datv_config['DATV_RECEIVER']['minitiouner-mode']=='DVB-S2' ? " selected" :  "" ?>>DVBS2</option>
                </select>
              </td>
              <!--
              <td>LAN Gateway address<br></td>
              <td><input type="text" name="gateway-eth0" value="192.168.1.1" maxlength="15" size="16">
              </td>
            </tr>  -->     
               
          </table><br>
          <input type="submit" value="Apply Settings" id ="submit_receiver"><span id="saved_receiver" class="saved"  style="display: none;"> Saved !</span>
   
<br><hr> <section id=""></section>
   <h2>Display Settings</h2>
           <table>
          <tr>
            <td>Fixed menu banner at the top of all the pages</td>
              <td>
                <div>
                  <input type="checkbox" id="menu_fixed" name="DATV[menu_fixed]" <?php if (isset($datv_config['DATV']['menu_fixed']))  echo $datv_config['DATV']['menu_fixed']=='on' ? " checked" :  "" ?>>
                  <label for="menu_fixed" aria-describedby="label"><span class="ui" ></span> fixed</label>
                </div>
              </td>

            </tr>
          </table><br>
          <input type="submit" value="Apply Settings" id ="submit_receiver"><span id="saved_receiver" class="saved"  style="display: none;"> Saved !</span>
        </form>
<hr> <section id="linkplutosettings"></section>
   <h2>Pluto Configuration</h2>
   This section read and save the <pre>/mnt/jffs2/etc/config.txt</pre> file. Take care of your modifications before applying them. Some modifications may make your equipment inaccessible from the network. To apply, please reboot (control button further down the page).
    <h3>USB on Ethernet </h3>
    <p>This <i>USB on Ethernet</i> setup section corresponds to the IP address mounted when the pluto is plugged in USB on a computer.</p>
<form id="configtxt" name="config" method="post" action = "javascript:save_config_setup('configtxt','<?php echo urlencode($file_config)?>', '<?php echo urlencode($config_ini[0]) ?>');">
    <table>
      <tr>
        <td><span class="note tooltip" title="Name the Pluto will have on your network. This setting is also valid when the pluto is accessed using a network adapter. <br/> Factory default value is 192.168.2.1" style="color : #636363;">Hostname</span></td>
        <td><input type="text" id="hostname" name="NETWORK[hostname]" value="<?php echo $network_config['NETWORK']['hostname']; ?>" maxlength="15" size="16"></td>
        <td><span class="note tooltip" title="IP address that the pluto will take when plugged in one of its USB port. <br/> Factory default value is 192.168.2.10" style="color : #636363;">IP address</span></td>
        <td><input type="text" id="ipaddr" name="NETWORK[ipaddr]" value="<?php echo $network_config['NETWORK']['ipaddr']; ?>" maxlength="15" size="16"></td>        
      </tr>
      <tr>
        <td><span class="note tooltip" title="IP address that the PC will take when plugged the Pluto is plugged into a USB port." style="color : #636363;">Host IP address (computer)</span><br></td>
        <td><input type="text" id="ipaddr_host" name="NETWORK[ipaddr_host]"value="<?php echo $network_config['NETWORK']['ipaddr_host']; ?>" maxlength="15" size="16"> </td>
        <td><span class="note tooltip" title="Factory default value is 255.255.255.0" style="color : #636363;">Network mask</span></td>
        <td><input type="text" id="netmask" name="NETWORK[netmask]"value="<?php echo $network_config['NETWORK']['netmask']; ?>" maxlength="15" size="16"></td>
      </tr>
    </table>
    <h3>Ethernet </h3>
    <p>This <i>Ethernet</i> setup section corresponds to the IP address mounted when the pluto is plugged over an Ethernet USB adapter on a local area network.</p>

    <table>
      <tr>
        <td><span class="note tooltip" title="Automatic determination of an IP address by your network router." style="color : #636363;">DHCP (dynamic IP)</span><br></td>
        <td><div class="checkcontainer">
          <input type="checkbox" id="dhcp_eth" name="USB_ETHERNET[dhcp_eth]" <?php if (isset($network_config['USB_ETHERNET']['dhcp_eth']))  echo $network_config['USB_ETHERNET']['dhcp_eth']=='on' ? " checked" :  "" ?>>
          <label for="dhcp_eth" aria-describedby="label"><span class="ui"></span> <span id='dhcp_eth_label'> static</span></label>
        </div> </td>
        <td class="toggle1"> <span class="note tooltip" title="Enter a free IP address according to your network plan (LAN router)." style="color : #636363;">IP address</span></td>
        <td class="toggle1"><input type="text" id="ipaddr_eth" name="USB_ETHERNET[ipaddr_eth]"value="<?php if (isset($network_config['USB_ETHERNET']['ipaddr_eth'])) echo $network_config['USB_ETHERNET']['ipaddr_eth']; ?>" maxlength="15" size="16"></td>
      </tr>
      <tr class="toggle1">
        <td>Network mask</td>
        <td><input type="text" id="netmask_eth" name="USB_ETHERNET[netmask_eth]" value="<?php if (isset($network_config['USB_ETHERNET']['netmask_eth'])) echo $network_config['USB_ETHERNET']['netmask_eth']; ?>" maxlength="15" size="16"></td>
        <td><span class="note tooltip" title="LAN Router address : Necessary to control the network equipment from the Pluto (Longmynd, Minitiouner)" style="color : #636363;">Gateway IP address</span><br></td>
        <td><input type="text" id="gateway_eth" name="USB_ETHERNET[gateway_eth]" value="<?php if (isset($network_config['USB_ETHERNET']['gateway_eth'])) echo $network_config['USB_ETHERNET']['gateway_eth']; ?>" maxlength="15" size="16"> </td>
      </tr>

    </table><br>
    <h3>Wifi or Access Point </h3>
    <table>
      <tr>
        <td>WLAN SSID</td>
        <td><input type="text" id="ssid_wlan" name="WLAN[ssid_wlan]" value="<?php if (isset($network_config['WLAN']['ssid_wlan'])) echo $network_config['WLAN']['ssid_wlan']; ?>" maxlength="15" size="16"></td>
        <td>WLAN Password<br></td>
        <td><input type="text" id="pwd_wlan" name="WLAN[pwd_wlan]" value="<?php if (isset($network_config['WLAN']['pwd_wlan'])) echo $network_config['WLAN']['pwd_wlan']; ?>" maxlength="15" size="16"> </td>
      </tr>
      <tr>
        <td>WLAN IP address</td>
        <td><input type="text" id="ssid_wlan" name="WLAN[ipaddr_wlan]" value="<?php if (isset($network_config['WLAN']['ipaddr_wlan'])) echo $network_config['WLAN']['ipaddr_wlan']; ?>" maxlength="15" size="16"></td>
      </tr>               
    </table>
    <br>
     
    <input type="submit" value="Apply Settings" id ="configtxt"><span id="configtxt_saved" class="saved"  style="display: none;"> Saved !</span>
  </form>


<script>



   // Check device ping
    $("#ipaddr_h265box").on('change',function() {
        $.get("requests.php?cmd="+encodeURIComponent("ping -W 1 -c 1 "+$("#ipaddr_h265box").val()+" >/dev/null && echo 'ok' || echo 'nok'"), function(data, status) {
            if (status=='success') {
              if (data.substring(0,2)!='ok') 
                {r= " ✖️";} 
              else { r= " ✔️"; }
               $("#ipaddr_h265box_status").html(r);
            }

        });
    });



$("#strategy_tab").on('td[contenteditable]', function() {
  var data = $(this).val();
  console.log(data);
});


function table2json (){
var rows = [];
var $headers = $("th");
var $rows = $("#strategy_tab tbody tr").each(function(index) {
  $cells = $(this).find("td");
  rows[index] = {};
  $cells.each(function(cellIndex) {
    rows[index][$($headers[cellIndex]).text()] = $(this).text();
  });    
});
var myObj = {};
myObj.rows = rows;
        $.get( "requests.php?cmd="+encodeURIComponent("echo '"+JSON.stringify(myObj)+"' > /mnt/jffs2/etc/strategy.json"), function( data ) {
            if (status=='success') { 
              $('#aa').fadeIn(250).fadeOut(1500);
            }
          });
}



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


function json2table() {

    $.ajax({
        url: "requests.php?cmd="+encodeURIComponent('cat /mnt/jffs2/etc/strategy.json'),
        dataType: 'json',
        type: 'get',
        cache:false,
        success: function(data){
            console.log(data);
            var event_data = '';
            $.each(data.rows, function(index, value){
                /*console.log(value);*/
                event_data += '<tr>';
                event_data += '<td class="tg-wpev" id="td-1">'+value['Priority']+'</td>';
                event_data += '<td class="tg-wpev" id="td-2" contenteditable="true">'+value['Total bitrate available']+'</td>';
                event_data += '<td class="tg-wpev" id="td-3" contenteditable="true">'+value['Audio channels']+'</td>';
                event_data += '<td class="tg-wpev" id="td-4" contenteditable="true">'+value['Audio Bitrate(kb/s)']+'</td>';
                event_data += '<td class="tg-wpev" id="td-5" contenteditable="true">'+value['GOP']+'</td>';
                event_data += '<td class="tg-wpev" id="td-6" contenteditable="true">'+value['Video Width']+'</td>';
                event_data += '<td class="tg-wpev" id="td-7" contenteditable="true">'+value['Video Height']+'</td>';
                event_data += '<td class="tg-wpev" id="td-8" contenteditable="true">'+value['FPS']+'</td>';
                event_data += '<td class="tg-wpev" id="td-9" contenteditable="true">'+value['Video Rate(kb/s)']+'</td>';
                event_data += '<td class="tg-wpev" id="td-8" contenteditable="true">'+value['PCR/PTS(ms)']+'</td>';
                event_data += '<td class="tg-wpev" id="td-9" contenteditable="true">'+value['PAT period(ms)']+'</td>';
                
                
                event_data += '</tr>';
            });
            $("#strategy_tab tbody").html(event_data);
        },
        error: function(d){
            /*console.log("error");*/
            console.log("strategy.json not found. Can be normal if table never modified.");
        }

    })
}

  function dhcp () {
    if ($("#dhcp_eth").is(":checked")==true) {
      $('#dhcp_eth_label').text('dynamic');
      $('.toggle1').hide();

    }
    else  {
     $('#dhcp_eth_label').text('static');
     $('.toggle1').show();
    }         
   
 }
   $("#dhcp_eth").click(function() {
    dhcp ();
  });

 function save_config_setup (destination,file_name,hl){    
  
  $.ajax({
          url: 'global_save.php', // url where to submit the request
          type : "POST", // type of action POST || GET
          dataType : 'html', // data type
          processData: false,
          
          data : $("#"+destination).serialize()+'&file_dest='+file_name+'&headlines='+hl, // post data || get data
          success : function(result) {
            $("#"+destination+'_saved').fadeIn(250).fadeOut(1500);
          },
          error: function(xhr, resp, text) {
            console.log(xhr, resp, text);
          }
        })

};

function update_slide(id,decimal,text) {
  $('#'+id+'-value').text(Number.parseFloat($('#'+id).val()).toFixed(decimal)+text)  ;
  if ((typeof mqtt.isConnected  === 'function') )  {
    if (mqtt.isConnected()) {
     sendmqtt('plutodvb/var', '{"'+id+'":"'+$('#'+id).val()+'"}' ) ;
     sendmqtt('plutodvb/subvar/'+id, $('#'+id).val() ) ;
    }
  }
}

</script>
<script>
  $( document ).ready(function() {

    
if  ($('#callsign').val()== '') {
   $('#callsign').val(callsign_insetup);
   }
if  ($('#provname').val()== '') {
   $('#provname').val(provname_insetup);
   }
  //$.MultiLanguage('./lib/language.json','fr');

  MQTTconnect();


//MQTT send messages
$('body').on('change', 'input,select', function () {
if ((typeof mqtt.isConnected === 'function') )  {
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
    sendmqtt('plutodvb/subvar/'+obj, val ) ;
  }
}


});

  json2table(); // load the json table definition

      dhcp ();
    update_slide('phase_correction',1,' °');
    update_slide('module_correction',2,'');
    update_slide('phase_correction_32_1',1,' °');
    update_slide('module_correction_32_1',2,'');
    update_slide('phase_correction_32_2',1,' °');
    update_slide('module_correction_32_2',2,'');


    $('#hi_power_limit').on('change paste keyup',function() {
      if (parseFloat($('#hi_power_limit').val())>0) {
        $('#hi_power_limit').css("background-color","red");
        alert ('Power limit must be a negative value (0dB is the maximum relative output level).');
      } else {
        $('#hi_power_limit').css("background-color","");
      }
    })

        $('#lo_power_limit,#hi_power_limit').on('change paste keyup',function() {
      if (parseFloat($('#hi_power_limit').val())<parseFloat($('#lo_power_limit').val())) {
        $('#lo_power_limit,#hi_power_limit').css("background-color","red");
        alert ('The maximum limit is lower than the minimum limit. Please correct one or the other of the limit values.');
      } else {
        $('#lo_power_limit,#hi_power_limit').css("background-color","");
      }
    })


});

</script>
</body>
</html>
