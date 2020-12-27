    <?php
    // F5UII : Setup page. The outputs are multiples files, working by ajax call with global_save.php.

    session_start();
    require ('./lib/functions.php');
    if ( isset( $_POST[ 'reboot' ] ) ) {
     exec( '/sbin/reboot' );
    }
  
    $file_config ='/opt/config.txt';
    $file_general = '/mnt/jffs2/etc/settings-datv.txt';
    if (true==false) // replace false by true for developping on debug server
    {
      echo "<i>Attention, in developping mode </i><br>";
      $file_config ='config.txt';
      $file_general = 'settings-datv.txt';
    }

    $config_ini = readinifile($file_config);
    $headfile = $config_ini[0];
    $ini_array = $config_ini[1];
    $general_ini = readinifile($file_general);
    $ini2_array = $general_ini[1];    
  

  ?>
  <!doctype html>

  <html>
  <!-- if "?xpert" in url then expert parameters are displayed  -->
  <head>
    <meta charset="UTF-8">

    <title>ADALM-PLUTO DVB General setup</title>
    <meta name="description" content="ADALM-PLUTO DVB General Setup ">
    <link type="text/css" href="./img/style.css" rel="stylesheet">
    <script src="lib/jquery-3.5.1.min.js"></script>
    <script src="lib/tooltip.js"></script>
    <link type="text/css" href="./lib/tooltip.css" rel="stylesheet">
  </head>
  <header id="top">
    <div class="anchor">
      <a href="https://twitter.com/F5OEOEvariste/" title="Go to Tweeter">F5OEO: <img style="width: 32px;" src="./img/tw.png" alt="Twitter Logo"></a>
    </div>
  </header> 
  <body>
    <nav style="text-align: center;">
     <a class="button" href="analysis.php">Analysis</a>
     <a class="button" href="pluto.php">Controller</a>
     <a class="button" href="index.html">Documentation</a>
   </nav>
   <h1>ADALM-PLUTO General Setup</h1> 
   <hr>
   <h2>Pluto Configuration</h2>
   This section read and save the <pre>/opt/config.txt</pre> file. Take care of your modifications before applying them. Some modifications may make your equipment inaccessible from the network. To apply, please reboot (control button further down the page).
    <h3>USB on Ethernet </h3>
    <p>This <i>USB on Ethernet</i> setup section corresponds to the IP address mounted when the pluto is plugged in USB on a computer.</p>
<form id="configtxt" name="config" method="post" action = "javascript:save_config_setup('configtxt','<?php echo urlencode($file_config)?>', '<?php echo urlencode($config_ini[0]) ?>');">
    <table>
      <tr>
        <td>hostname</td>
        <td><input type="text" id="hostname" name="NETWORK[hostname]" value="<?php echo $ini_array['NETWORK']['hostname']; ?>" maxlength="15" size="16"></td>
        <td>IP address</td>
        <td><input type="text" id="ipaddr" name="NETWORK[ipaddr]" value="<?php echo $ini_array['NETWORK']['ipaddr']; ?>" maxlength="15" size="16"></td>        
      </tr>
      <tr>
        <td>Host IP address (computer)<br></td>
        <td><input type="text" id="ipaddr_host" name="NETWORK[ipaddr_host]"value="<?php echo $ini_array['NETWORK']['ipaddr_host']; ?>" maxlength="15" size="16"> </td>
        <td>Network mask</td>
        <td><input type="text" id="netmask" name="NETWORK[netmask]"value="<?php echo $ini_array['NETWORK']['netmask']; ?>" maxlength="15" size="16"></td>
      </tr>
    </table>
    <h3>Ethernet </h3>
    <p>This <i>Ethernet</i> setup section corresponds to the IP address mounted when the pluto is plugged over an Ethernet USB adapter on a local area network.</p>

    <table>
      <tr>
        <td>DHCP (dynamic IP)<br></td>
        <td><div class="checkcontainer">
          <input type="checkbox" id="dhcp_eth" name="USB_ETHERNET[dhcp_eth]" <?php if (isset($ini_array['USB_ETHERNET']['dhcp_eth']))  echo $ini_array['USB_ETHERNET']['dhcp_eth']=='on' ? " checked" :  "" ?>>
          <label for="dhcp_eth" aria-describedby="label"><span class="ui"></span> <span id='dhcp_eth_label'> static</span></label>
        </div> </td>
        <td class="toggle1"> IP address</td>
        <td class="toggle1"><input type="text" id="ipaddr_eth" name="USB_ETHERNET[ipaddr_eth]"value="<?php if (isset($ini_array['USB_ETHERNET']['ipaddr_eth'])) echo $ini_array['USB_ETHERNET']['ipaddr_eth']; ?>" maxlength="15" size="16"></td>
      </tr>
      <tr class="toggle1">
        <td>Network mask</td>
        <td><input type="text" id="netmask_eth" name="USB_ETHERNET[netmask_eth]" value="<?php if (isset($ini_array['USB_ETHERNET']['netmask_eth'])) echo $ini_array['USB_ETHERNET']['netmask_eth']; ?>" maxlength="15" size="16"></td>
        <td><span class="note tooltip" title="LAN Router address : Necessary to control the network equipment from the Pluto (Longmynd, Minitiouner)" style="color : #636363;">Gateway IP address</span><br></td>
        <td><input type="text" id="gateway_eth" name="USB_ETHERNET[gateway_eth]" value="<?php if (isset($ini_array['USB_ETHERNET']['gateway_eth'])) echo $ini_array['USB_ETHERNET']['gateway_eth']; ?>" maxlength="15" size="16"> </td>
      </tr>

    </table><br>
    <h3>Wifi or Access Point </h3>
    <table>
      <tr>
        <td>WLAN SSID</td>
        <td><input type="text" id="ssid_wlan" name="WLAN[ssid_wlan]" value="<?php if (isset($ini_array['WLAN']['ssid_wlan'])) echo $ini_array['WLAN']['ssid_wlan']; ?>" maxlength="15" size="16"></td>
        <td>WLAN Password<br></td>
        <td><input type="text" id="pwd_wlan" name="WLAN[pwd_wlan]" value="<?php if (isset($ini_array['WLAN']['pwd_wlan'])) echo $ini_array['WLAN']['pwd_wlan']; ?>" maxlength="15" size="16"> </td>
      </tr>
      <tr>
        <td>WLAN IP address</td>
        <td><input type="text" id="ssid_wlan" name="WLAN[ipaddr_wlan]" value="<?php if (isset($ini_array['WLAN']['ipaddr_wlan'])) echo $ini_array['WLAN']['ipaddr_wlan']; ?>" maxlength="15" size="16"></td>
      </tr>               
    </table>
    <br>


     
    <div class="xpert" style="display: none;">
        <h2>Advanced <i>( ⚠️ Be carefull, expert use only)</i> </h3>
    <p></p>

    <h3>System, Radio </h3>
    <p></p>

    <table>

      <tr>
        <td>Xo Correction</td>
        <td><input type="text" id="xo_correction" name="SYSTEM[xo_correction]" value="<?php if (isset($ini_array['SYSTEM']['xo_correction'])) echo $ini_array['SYSTEM']['xo_correction']; ?>" maxlength="4" size="4"></td>
        <td>UDC Handle suspend </td>
        <td><input type="text" id="udc_handle_suspend" name="SYSTEM[udc_handle_suspend]" value="<?php if (isset($ini_array['SYSTEM']['udc_handle_suspend'])) echo $ini_array['SYSTEM']['udc_handle_suspend']; ?>" maxlength="4" size="4"></td>

      </tr>

    </table>
    <table>

      <tr>
        <td>Diagnostic report</td>
        <td><input type="text" id="diagnostic_report" name="ACTIONS[diagnostic_report]" value="<?php if (isset($ini_array['ACTIONS']['diagnostic_report'])) echo $ini_array['ACTIONS']['diagnostic_report']; ?>" maxlength="4" size="4"></td>
        <td>DFU (Device Firmware Update) </td>
        <td><input type="text" id="dfu" name="ACTIONS[dfu]" value="<?php if (isset($ini_array['ACTIONS']['dfu'])) echo $ini_array['ACTIONS']['dfu']; ?>" maxlength="4" size="4"></td>
      </tr>
      <tr>
        <td>Reset</td>
        <td><input type="text" id="reset" name="ACTIONS[reset]" value="<?php if (isset($ini_array['ACTIONS']['reset'])) echo $ini_array['ACTIONS']['reset']; ?>" maxlength="4" size="4"></td>
        <td>Calibrate </td>
        <td><input type="text" id="calibrate" name="ACTIONS[calibrate]" value="<?php if (isset($ini_array['ACTIONS']['calibrate'])) echo $ini_array['ACTIONS']['calibrate']; ?>" maxlength="4" size="4"></td>

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

          <input type="checkbox" id="tx_onstart" name="DATV[tx_onstart]" <?php if (isset($ini2_array['DATV']['tx_onstart']))  echo $ini2_array['DATV']['tx_onstart']=='on' ? " checked" :  "" ?>>
          <label for="tx_onstart" aria-describedby="label"><span class="ui"></span> <span id='tx_onstart_label'> enabled</span></label>
        </div> </td>  

     </tr>
     
     <tr>
        <td><span class="note tooltip" title="⚠️ This setting is not to be considered as an absolute protection against overpower.<br>It is highly recommended for safety of your transmision line to ensure it by inserting suitable RF attenuators.<p>The value to be indicated is the relative power (maximum 0dB which corresponds to the maximum output power of the Pluto). The expected value is therefore <strong>negative</strong></p>" style="color : #636363;">Maximum adjustable power</span>  <i>(dB)</i></td>
        <td><input type="text" id="hi_power_limit" name="DATV[hi_power_limit]" value="<?php if (isset($ini2_array['DATV']['hi_power_limit'])) echo $ini2_array['DATV']['hi_power_limit']; ?>" maxlength="6" size="6"></td>

        <td><span class="note tooltip" title="<ul><li>When set to a value different than 0, the absolute power conversion (abs) is displayed in dB and also Watt unit on the controller. </li><li>The value can be positive or negative.</li></ul>" style="color : #636363;">Conversion gain to display the real power (absolute level) </span><i>(dB)</i></td>
        <td><input type="text" id="abs_gain" name="DATV[abs_gain]" value="<?php if (isset($ini2_array['DATV']['abs_gain'])) echo $ini2_array['DATV']['abs_gain']; ?>" maxlength="4" size="4"></td>
   

     </tr>
     <tr>
     </tr>
    
   </table>
   <h2>Avanced for expert use only</h2>

   <table>
    <tr>
      
      <td><span class="note tooltip" title="Automatically switches off the transmission after the specificated duration .<ul><li>The feature is disabled when the parameter is empty or equal to zero.</li></ul>" style="color : #636363;">Watchdog</span> <i>(min)</i></td>
      <td><input type="text" id="tx_watchdog" name="DATV_EXPERT[tx_watchdog]" value="<?php if (isset($ini2_array['DATV_EXPERT']['tx_watchdog'])) echo $ini2_array['DATV_EXPERT']['tx_watchdog']; ?>" maxlength="4" size="4"></td>    

    </tr>
   </table>
   <h3>16APSK, 32APSK characteristics</h3>
   <br>
    <table>

     <tr>
        <td><span class="note tooltip" title="Allows to correct a phase shift by balancing the central points around the centre of the constellation<ul><li>on the central points of 16ASPK mod</li><li>on the two central point circles of 32ASPK mod</li></ul><p>💡 For fine adjustment, click on the slider and then use the up and down keys<br/> For an adjustment in steps of 10% of the full scale, click on the slider and then use the page up and page down keys.</p>" style="color : #636363;">Phase rotation correction</span> <i>(degrees)</i></td>
        <td><input type="range" min="-45" max="45" step="0.1" id="phase_correction" name="DATV_EXPERT[phase_correction]" value="<?php if (isset($ini2_array['DATV_EXPERT']['phase_correction'])) echo $ini2_array['DATV_EXPERT']['phase_correction']; ?>" oninput="update_slide($(this).attr('id'),1,' °')"> <span id="phase_correction-value"></span></td>    
        <td><span class="note tooltip" title="Allows to correct the distance from the constellation center<ul><li>for the central points of 16ASPK mod</li><li>for the two central point circles of 32ASPK mod</li></ul><p>💡 For fine adjustment, click on the slider and then use the up and down keys<br/> For an adjustment in steps of 10% of the full scale, click on the slider and then use the page up and page down keys.</p>" style="color : #636363;">Module vector correction</span> <i>(factor)</i></td>
        <td><input type="range" min="0.8" max="1.2" step="0.01"  id="module_correction" name="DATV_EXPERT[module_correction]" value="<?php if (isset($ini2_array['DATV_EXPERT']['module_correction'])) echo $ini2_array['DATV_EXPERT']['module_correction']; ?>" oninput="update_slide($(this).attr('id'),2,'')"> <span id="module_correction-value"></span></td>               
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

}


</script>



</body>
</html>