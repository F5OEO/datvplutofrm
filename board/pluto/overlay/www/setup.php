    <?php
    // F5UII : Setup page. The outputs are multiples files, working by ajax call with global_save.php.

    session_start();
    require ('./lib/functions.php');
    if ( isset( $_POST[ 'reboot' ] ) ) {
     exec( '/sbin/reboot' );
    }
  
    $file_config ='/opt/config.txt';
    $file_general = '/mnt/jffs2/etc/settings-datv.txt';
    if (true==true) // replace false by true for developping on debug server
    {
      echo "<i>Attention, in developping mode </i><br>";
      $file_config ='config.txt';
      $file_general = 'settings-datv.txt';
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

    <title>PlutoDVB General setup</title>
    <meta name="description" content="ADALM-PLUTO DVB General Setup ">
    <link type="text/css" href="./img/style.css" rel="stylesheet">
    <script src="lib/jquery-3.5.1.min.js"></script>
    <script src="lib/tooltip.js"></script>
    <script src="lib/mqttws31.js"></script>  
    <script src="lib/mqtt.js.php?page=<?php echo basename($_SERVER["SCRIPT_FILENAME"]); ?>"></script>  
    <link type="text/css" href="./lib/tooltip.css" rel="stylesheet">
    <link type="text/css" href="./lib/menu.css" rel="stylesheet">
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
   <h1>PlutoDVB General setup</h1> 
   <hr>
   <h2>Pluto Configuration</h2>
   This section read and save the <pre>/opt/config.txt</pre> file. Take care of your modifications before applying them. Some modifications may make your equipment inaccessible from the network. To apply, please reboot (control button further down the page).
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


     
    <div class="xpert" style="display: none;">
        <h2>Advanced <i>( ⚠️ Be carefull, expert use only)</i> </h2>
    <p></p>

    <h3>System, Radio </h3>
    <p></p>

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
  

   </div>

    <br>      
    <input type="submit" value="Apply Settings" id ="configtxt"><span id="configtxt_saved" class="saved"  style="display: none;"> Saved !</span>
  </form>

<hr>
   <h2>DATV transmission settings</h2>
   <h3>General use</h3>
   <br>
   <form id="general" name="datv_config" method="post" action = "javascript:save_config_setup('general','<?php echo urlencode($file_general)?>', '<?php echo rawurlencode($general_ini[0]) ?>');">
   <table>
     <tr>
        <td><span class="note tooltip" title="<ul><li>When enabled (yes position), at (re)start of the Pluto, the transmit is activated. This feature is welcome for restart quickly transmission after an unexpected power cut.</li><li>When disabled (no position), the Pluto stay stand-by at (re)start.</li></ul>" style="color : #636363;">Transmission permitted at start-up</span><br></td>
        <td><div class="checkcontainer">

          <input type="checkbox" id="tx_onstart" name="DATV[tx_onstart]" <?php if (isset($datv_config['DATV']['tx_onstart']))  echo $datv_config['DATV']['tx_onstart']=='on' ? " checked" :  "" ?>>
          <label for="tx_onstart" aria-describedby="label"><span class="ui"></span> <span id='tx_onstart_label'> enabled</span></label>
        </div> </td>  

     </tr>
     
     <tr>
        <td><span class="note tooltip" title="Limits the stroke of the power adjustment.<br/>  ⚠️ This setting is not to be considered as an absolute protection against overpower.<br>It is highly recommended for safety of your transmision line to ensure it by inserting suitable RF attenuators.<p>The value to be indicated is the relative power (maximum 0dB which corresponds to the maximum output power of the Pluto). The expected value is therefore <strong>negative</strong></p>" style="color : #636363;">Maximum adjustable power</span>  <i>(dB)</i></td>
        <td><input type="text" id="hi_power_limit" name="DATV[hi_power_limit]" value="<?php if (isset($datv_config['DATV']['hi_power_limit'])) echo $datv_config['DATV']['hi_power_limit']; ?>" maxlength="6" size="6"></td>

        <td><span class="note tooltip" title="<ul><li>When set to a value different than 0, the absolute power conversion (abs) is displayed in dB and also Watt unit on the controller. </li><li>The value can be positive or negative.</li></ul>" style="color : #636363;">Conversion gain to display the real power (absolute output level) </span><i>(dB)</i></td>
        <td><input type="text" id="abs_gain" name="DATV[abs_gain]" value="<?php if (isset($datv_config['DATV']['abs_gain'])) echo $datv_config['DATV']['abs_gain']; ?>" maxlength="4" size="4"></td>
   

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
        <td><span class="note tooltip" title="Address of your H264/H265 encoder box. The online status is updated after moving the cursor out of the input field.<ul><li>✔️ : Is online from the Pluto (good answer to the ping command).</li><li>✖️ : Seems not online from the Pluto (no answer to the ping command)</li> " style="color : #636363;">IP address</span></td>

        <td><input type="text" id="ipaddr_h265box" name="H265BOX[ipaddr_h265box]" value="<?php  if (isset($datv_config['H265BOX']['use_h265box'])) { $ping_ip= $datv_config['H265BOX']['ipaddr_h265box'] ;} else { $ping_ip=  '192.168.1.120'; } ; echo $ping_ip; ?>" maxlength="15" size="16"> <?php $a= shell_exec ("ping -W 1 -c 1 ".$ping_ip); if (strpos($a, ", 100% packet loss") > 0) {$r= " ✖️";} else { $r= " ✔️"; } ?><span id="ipaddr_h265box_status"><?php echo $r; ?></span></td>

      <tr>
          <td><span class="note tooltip" title="'admin' by default" style="color : #636363;">Administrator login</span> </td>
          <td><input type="text" id="h265box_login" name="H265BOX[h265box_login]" value="<?php if (isset($datv_config['H265BOX']['h265box_login'])) echo $datv_config['H265BOX']['h265box_login']; else echo "admin" ?>" maxlength="6" size="6"></td>

          <td><span class="note tooltip" title="'12345' by default." style="color : #636363;">Password</span></td>
          <td><input type="text" id="h265box_password" name="H265BOX[h265box_password]" value="<?php if (isset($datv_config['H265BOX']['h265box_password'])) echo $datv_config['H265BOX']['h265box_password']; else echo "12345"?>" maxlength="8" size="8"></td>
     

       </tr>
     </tr>
    
   </table>
  <h2>Strategy Setting table</h2>
  This modifiable table makes it easy to set the parameters of the H264/265 encoder automatically according to the stream transport rate (depending on the transmitted signal characteristics).<br/>
Attention, in this version the editable cells are not verified at all.
  <style type="text/css">
.tg  {border-collapse:collapse;border-color:#9ABAD9;border-spacing:0;}
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
  </tr>
</thead>
<tbody>
  <tr id="tr1">
    <td class="tg-wpev">1</td>
    <td class="tg-wpev" contenteditable="true">5000</td>
    <td class="tg-wpev" contenteditable="true">2</td>
    <td class="tg-wpev" contenteditable="true">64</td>
    <td class="tg-wpev" contenteditable="true">200</td>
    <td class="tg-wpev" contenteditable="true">1920</td>
    <td class="tg-wpev" contenteditable="true">1080</td>
    <td class="tg-wpev" contenteditable="true">30</td>
    <td class="tg-wpev" contenteditable="true">1000</td>
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
  </tr>
</tbody>
</table>
<br/>
    <input type="submit" value="Apply Settings" id ="st" onclick="table2json()"><span id="aa"   style="display: none;"> Saved !</span>

   <h2>Avanced for expert use only</h2>

   <table>
    <tr>
      
      <td><span class="note tooltip" title="Automatically switches off the transmission after the specificated duration .<ul><li>The feature is disabled when the parameter is empty or equal to zero.</li></ul>" style="color : #636363;">Watchdog</span> <i>(min)</i></td>
      <td><input type="text" id="tx_watchdog" name="DATV_EXPERT[tx_watchdog]" value="<?php if (isset($datv_config['DATV_EXPERT']['tx_watchdog'])) echo $datv_config['DATV_EXPERT']['tx_watchdog']; ?>" maxlength="4" size="4"></td>    

    </tr>
   </table>
   <h3>16APSK, 32APSK characteristics</h3>
   <br>
    <table>

     <tr>
        <td><span class="note tooltip" title="Allows to correct a phase shift by balancing the central points around the center of the constellation<ul><li>on the central points of 16ASPK mod</li><li>on the two central point circles of 32ASPK mod</li></ul><p>💡 For fine adjustment, click on the slider and then use the up and down keys<br/> For an adjustment in steps of 10% of the full scale, click on the slider and then use the page up and page down keys.</p>" style="color : #636363;">Phase rotation correction</span> <i>(degrees)</i></td>
        <td><input type="range" min="-45" max="45" step="0.1" id="phase_correction" name="DATV_EXPERT[phase_correction]" value="<?php if (isset($datv_config['DATV_EXPERT']['phase_correction'])) echo $datv_config['DATV_EXPERT']['phase_correction']; ?>" oninput="update_slide($(this).attr('id'),1,' °')"> <span id="phase_correction-value"></span></td>    
        <td><span class="note tooltip" title="Allows to correct the distance from the constellation center<ul><li>for the central points of 16ASPK mod</li><li>for the two central point circles of 32ASPK mod</li></ul><p>💡 For fine adjustment, click on the slider and then use the up and down keys<br/> For an adjustment in steps of 10% of the full scale, click on the slider and then use the page up and page down keys.</p>" style="color : #636363;">Module vector correction</span> <i>(factor)</i></td>
        <td><input type="range" min="0.4" max="2.5" step="0.01"  id="module_correction" name="DATV_EXPERT[module_correction]" value="<?php if (isset($datv_config['DATV_EXPERT']['module_correction'])) echo $datv_config['DATV_EXPERT']['module_correction']; ?>" oninput="update_slide($(this).attr('id'),2,'')"> <span id="module_correction-value"></span></td>               
     </tr>


     
   </table><br>
   <input type="submit" value="Apply Settings" id ="general"><span id="general_saved" class="saved"  style="display: none;"> Saved !</span>
 </form>

<br>
<h2>Reboot</h2>

This is needed for apply your saved modifications made in Pluto Configuration section. Take a moment to check your settings before applying them.<br>
<form method="post">
  <p>
    <button name="reboot">Reboot the Pluto</button>
  </p>
</form>
<script>
   $(document).ready(function() {
    dhcp ();
    update_slide('phase_correction',1,' °');
    update_slide('module_correction',2,'');
    if (window.location.href.indexOf("xpert") > -1) {
      $(".xpert").show();
    }

    $('#hi_power_limit').on('change paste keyup',function() {
      if (parseFloat($('#hi_power_limit').val())>0) {
        $('#hi_power_limit').css("background-color","red");
        alert ('Power limit must be a negative value (0dB is the maximum relative output level).');
      } else {
        $('#hi_power_limit').css("background-color","");
      }
    })
  });

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
//alert(JSON.stringify(myObj));
        $.get( "requests.php?cmd="+encodeURIComponent('echo '+JSON.stringify(myObj)+' > /mnt/jffs2/etc/strategy.json'), function( data ) {
            if (status=='success') { 
              $('#aa').fadeIn(250).fadeOut(1500);
            }
          });
}

function json2table() {

    $.ajax({
        url: "strategy.json",
        dataType: 'json',
        type: 'get',
        cache:false,
        success: function(data){
            /*console.log(data);*/
            var event_data = '';
            $.each(data.rows, function(index, value){
                /*console.log(value);*/
                event_data += '<tr>';
                event_data += '<td class="tg-wpev">'+value['Priority']+'</td>';
                event_data += '<td class="tg-wpev" contenteditable="true">'+value['Total bitrate available']+'</td>';
                event_data += '<td class="tg-wpev" contenteditable="true">'+value['Audio channels']+'</td>';
                event_data += '<td class="tg-wpev" contenteditable="true">'+value['Audio Bitrate(kb/s)']+'</td>';
                event_data += '<td class="tg-wpev" contenteditable="true">'+value['GOP']+'</td>';
                event_data += '<td class="tg-wpev" contenteditable="true">'+value['Video Width']+'</td>';
                event_data += '<td class="tg-wpev" contenteditable="true">'+value['Video Height']+'</td>';
                event_data += '<td class="tg-wpev" contenteditable="true">'+value['FPS']+'</td>';
                event_data += '<td class="tg-wpev" contenteditable="true">'+value['Video Rate(kb/s)']+'</td>';
                
                
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
  //if (mqtt_connected == true) {
  // sendmqtt('plutodvb/var', '{"'+id+'":"'+$('#'+id).val()+'"}' ) ;
  //}
}

//MQTT send messages
$('body').on('change', 'input,select', function () {
  if (mqtt_connected == true) {
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
  json2table(); // load the json table definition
  var mqtt_connected = false;
  MQTTconnect();
</script>
</body>
</html>
