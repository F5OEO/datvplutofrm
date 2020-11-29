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
    </style>

    <title>ADALM-PLUTO DVB Controller</title>
    <meta name="description" content="ADALM-PLUTO DVB Controller ">
    <link type="text/css" href="./img/style.css" rel="stylesheet">
    <script src="lib/jquery-3.5.1.min.js"></script>
    <script src="lib/socket.io-2.3.0.min.js"></script>
    <script src="lib/u16Websocket.js"></script>
    <script src="lib/js.cookie.min.js"></script>
    <script src="lib/tooltip.js"></script>
    <link type="text/css" href="./lib/tooltip.css" rel="stylesheet">
    <link href="lib/favicon.ico" rel="icon" type="image/x-icon" />
  </head>

  <body onload="load()">

    <ul class='right-c-menu'>
      <li data-action="lock">🔒 Lock modulator</li>
      <li data-action="unlock">🔓 Unlock modulator</li>
      <li data-action="duplicate">➕ Duplicate this modulator</li>
      <li data-action="copydata">📋 <span  class="note tooltip" style="color: #333;" title="Copies the callsign, program name and power from the active modulator to all unlocked modulators">Copy Callsign, Program Name, Power</span></li>
   </ul>


    <header id="top">
      <div id="col1">
        &nbsp;
      </div>
      <div id='col3'>   
      <div class="anchor">
        Firmware version : <?php
        $fwver = shell_exec ( 'cat /www/fwversion.txt' );
        echo "$fwver";
        ?><br/> 
        <a href="https://twitter.com/F5OEOEvariste/" title="Go to Tweeter">F5OEO: <img style="width: 32px;" src="./img/tw.png" alt="Twitter Logo"></a>
      </div>
    </div>
    
    <div id="col2">
    <nav style="text-align: center;">
      <a class="button" href="analysis.php" >Analysis</a> 

      <a class="button" href="index.html" >Documentation</a>
      <a class="button" href="https://wiki.batc.org.uk/QO-100_WB_Bandplan" target="_blank">QO-100 WB Bandplan</a>
    </nav>
  </div>
  </header>


    <header id="maintitle"> <h1><strong>ADALM-PLUTO</strong> DATV Controller</h1>
      <section style=" text-align: right;">
        <div >Thanks Rob M0DTS for help. Mods by G4EML for codec selection and sound enable</div>
        


                <div >Mods by Chris <a href="https://www.f5uii.net/?o=2110
" title="Go to Chris blog and ressources" target="_blank">F5UII.net</a>&nbsp; <a href="https://twitter.com/f5uii/" title="Go to f5uii profile on twitter"><img style="width: 20px;" src="./img/tw.png" alt="Twitter Logo"></a> version <i id='patch-uii'>UII2.3</i><div id="note">A new UII patch is available<span id = 'uii-new-version'></span>. Follow <a href= "https://www.f5uii.net/en/patch-plutodvb/?ori=update" target="_blank">this link</a>.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <a id="close">❌</a></div>: <span class="note tooltip" title="
        <strong>Version UII2.3 - 22/11/2020</strong><ul><li>Multiple modulator memory (tabbed system) <i>big program evolution</i>🤯</li><li>Transmission time counter with totalizer of the switchover and duration</li><li>Internal temperatures of the PlutoSDR (Suggested by <a href='https://www.f5uii.net/en/patch-plutodvb/#div-comment-18162' target='_blank'> Greg SV2RR</a>)</li><li>Focus on null packets on the <a href='analysis.php'>analysis page</a>, with some formatting</li><li>Github commits history on <a href='index.html#releasenote'>Documentation page</a> </li></ul>
        <strong>Version UII2.2 - 18/10/2020</strong><ul><li>Copy to clipboard the RTMP URL string (<i>Detailed on Help tab</i>)</li></ul>
        <strong>Version UII2.1c - 15/10/2020</strong><ul><li>Saving parameters (Spectrum & Minitiouner Receiver control panel)</li><li>Minitiouner steering, Gateway address</li></ul>
        <strong>Version UII2 - 29/08/2020</strong> <ul><li>Minitiouner Receiver control by clicking on a channel of the spectrum with its setup fields and Help tab, Retractable spectrum</li></ul><strong>Version UII1 - 23/08/2020</strong> <ul><li>BATC spectrum (only if client is online) with transmit frequency choose by click on a channel</li><li>Reboot command, Delete patch, html format compliance mods...</li></ul> <hr>🛈 Link to <a href='https://www.f5uii.net/en/patch-plutodvb/?o=2110
' target='_blank'>download, roadmap and support page">




        </a>Details</span></div>
        <div >Mods by Roberto IS0GRB (Save SpectrumView button state,Show how much patch.zip inserted (August 29th, 2020)</div>
        <br>
      </section>
    </div>
  </header>


  <section>

    <div class="tab-wrap">

      <input type="radio" id="tab1" name="tabGroup1" class="tab" checked>
      <label for="tab1">QO-100 Spectrum</label>

      <input type="radio" id="tab2" name="tabGroup1" class="tab">
      <label for="tab2">Setup</label>

      <input type="radio" id="tab3" name="tabGroup1" class="tab">
      <label for="tab3">Help</label>

      <div class="tab__content" id="tab_spectrum">
        <div id="no_wf"><p style="padding :10px 25px;">To display the QO-100 spectrum, enable the display on the Setup tab.</p></div>
        <div id="wf" style="width: 100%;">
          <div id="fft-col" class="col-xl-7"  style="width: 100%;">
            <canvas id="c" width="1" height="1"></canvas>
            <div id="under-canvas">
              <textarea readonly id="upf" name="upf" rows="1" cols="15" style="position : absolute; left :-99999px;">2401.123</textarea>
              <span id="fullscreen-link-span" style="  display: flex;
              align-items: center;
              justify-content: center;">
              <span id="message_spectrum" style="color: #1e4056; display: none; text-align: center;">Frequency set and also copied in clipboard ! <span id="rtmp">Click on 📋 to copy RTMP server URL in clipboard</span></span>
            </span>
          </div>
        </div>
      </div>
    </div>

    <div class="tab__content" id="tab_setup">
      <h3>Display</h3>
      <p>The QO-100 spectrum is an online ressource of BATC / AMSAT-UK. You can disable the display of the spectrum QO-100 here. When disabled, the data flow is interrupted. At the same time, the receiver control feature is switched off. Save your choice by Apply Settings button. </p>
      <form id="receiver" name="receiver" method="post" action = "javascript:save_receiver_setup();">
        <table>
          <tr>
            <td>Spectrum</td>
              <td>
                <div class="checkcontainer">
                  <input type="checkbox" id="spectrum_enable" name="spectrum_enable">
                  <label for="spectrum_enable" aria-describedby="label"><span class="ui"></span> displayed</label>
                </div>
              </td>

            </tr>
          </table>

          <h3>Receiver setup</h3>
          <p>To be able to control your minitiouner, please fill in these few parameters. For more details, please refer to the Help tab.</p>

          <table>
            <tr>
              <td>Destination IP address</td>
              <td><input type="text" name="minitiouner-ip" value="232.0.0.11"></td>
              <td>Destination Port number <br></td>
              <td><input type="text" name="minitiouner-port" value="6789" maxlength="15" size="16"> </td>
            </tr>
            <tr>
              <td>LNB Offset <i>kHz</i></td>
              <td><input type="text" name="minitiouner-offset" value="9750000"></td>
              <td>Rx socket <br></td>
              <td><select name="minitiouner-socket" >
                <option value="A">A</option>
                <option value="B">B</option>
              </select> </td>
            </tr>
            <tr>
              <td>LNB Voltage <i>V</i></td>
              <td>
                <select name="minitiouner-voltage" >
                  <option value="0">0</option>
                  <option value="13">13</option>
                  <option value="18">18</option>
                </select>
              </td>
              <td>LNB 22 kHz <br></td>
              <td><select name="minitiouner-22khz">
                <option value="OFF">Off</option>
                <option value="ON">On</option>
              </select> </td>
            </tr>  
            <tr>
              <td>DVB Mode <i></i></td>
              <td>
                <select name="minitiouner-mode" >
                  <option value="Auto">Auto</option>
                  <option value="DVB-S">DVBS</option>
                  <option value="DVB-S2">DVBS2</option>
                </select>
              </td>
              <td>LAN Gateway address<br></td>
              <td><input type="text" name="gateway-eth0" value="192.168.1.1" maxlength="15" size="16">
              </td>
            </tr>       
            <tr>
              <td>UDP Broadcast IP address</td>
              <td><input type="text" name="minitiouner-udp-ip" value="230.0.0.10"></td>
              <td>UDP Broadcast Port number <br></td>
              <td><input type="text" name="minitiouner-udp-port" value="10000" maxlength="15" size="16"> </td>
            </tr>                 
          </table><br>
          <input type="submit" value="Apply Settings" id ="submit_receiver"><span id="saved_receiver" class="saved"  style="display: none;"> Saved !</span>
        </form>

      </div>

      <div class="tab__content" id='tab_help'>

       <h2>Control your transmission frequency and paste the RTMP URL string</h2>
       <p>At the bottom of QO-100 Spectrum, there are horizontal bars representing the possible transmission channels on the satellite. By simply clicking on a bar, <ul><li>you will report  the corresponding transmission frequency in the Modulator table, in <i>Freq</i> field. This will take account of your possible transverter settings.</li><li>the chosen channel frequency is copied in your clipboard so that you can easily paste where you want.</li><li>then, by clicking on the text <i>Click here to copy RTMP server URL in </i>📋, you will directly copy in clipboard the whole string that is waiting in the destination URL. You will be able to simply paste it in URL field of your stream software like OBS Studio, or Vmix (the <a href="index.html#test" >RTMP string</a> is set with all parameters set in Modulator table, to be paste).</li></ul> </p>
       <h2>Steering DATV receiver</h2>
       <p><i>At this stage, only one minitiouner receiver can be controlled. The Pluto must be connected on the same local network, through a gateway (router). </i></p>
       <h3>About Minitiouner</h3><img src="./img/minitiouner.jpg" style="
       float: right;"/>
       <p>The Minitiouner hardware is designed for easy use with the software Minitiouner Pro conceived by F6DZP Jean-Pierre. The support and download are free and available on <a href="http://www.vivadatv.org/" target="_blank">vivadatv forum</a>.</p>
       <h3>How it works</h3>
       <p>To be able to directly drive your minitiouner by a simple click on a used channel of the QO-100 spectrum, you have to follow these few indications.</p>
       <p>The Ip adress and port to enter in the <i>Setup</i> tab correspond to the informations <i>Conf_AddrUDP</i> and <i>Conf_Port</i> that you find in the minitiouner configuration file <i>minitiouneConfig.ini</i>.  The IP address can also be the address of the computer on which the minitiouner is running.
        The gateway address indicated is that of your network router. Click <i>Apply Settings</i> for save your settings.</p>
        <p>Click on a signal on the spectrum. The command is sent directly to the minitiouner with the right frequency and SR, also considering your settings stored on the setup tab.</p>
        <h3>And now what else ?</h3>
        <p>You may have good ideas for further development of this software. You can bring them on <a href="https://www.f5uii.net/en/patch-plutodvb/?o=<?php
        echo "$fwver";
        ?>" title="Go to Chris blog and ressources" target="_blank">my blog</a>. You can help me to continue the developments and projects around plutoSDR, by making a gift purchase of an item content published on the public wish list, or buy me a cofee. 😉 73 Chris F5UII</p>
      </div>

    </div>
  </section>

  <section>
   <table>
    <tr>
      <td>PTT
        <td>

          <button id="ptt" onClick="request_ptt();"></button>
        </td>
        <td>
          <p id="textptt" style="display:none"></p><span id="temps" class="tooltip" title="Tuner temperature - Zynq FGPA temperature"></span><span id="txduration" class="note tooltip" title="Total duration : 00:00:00">00:00:00</span>
        </td>
      </tr>
    </table>



  </section>



  <h2>Modulator</h2>

  <hr>

<span id ="addtab"><a id='plussign'> ➕ </a><a><span  class="note tooltip" title="Click ➕ to add a new modulator profile.<ul><li>The new tab is initialized in the same state as the Main tab.</li><li> You can edit the name of the tab. <li>It is saved locally in your browser, as soon as you have changed <u>at least one</u> setting in the table. (No click on <i>Apply Settings</i> needed)</li><li>  To use the active modulator for the next transmission (or during an ongoing transmision), click <i>Apply settings</i> button.</li><li>With a right click on a form, you can <ul><li>lock the modulator so that no changes can be done before unlocking it again.</li><li>duplicate the current active modulator on a new tab</li><li>copies the callsign, program name and power from the active modulator to all unlocked profiles</li></li></ul> ">Add modulator</span></a></span>
  <ul id="tabs"  >
    <li><a id="tab1">Main</a></li>
  </ul>
 
  <div class="tabs-content" id="id-tabs-content">
    <div class="container" id="tab1C">

      <form  method="post" id="modulator" class="form_modulator" action = "javascript:save_modulator_setup();">

        <table>
          <tr><td>Power <i>(0.1 dB steps)</i></td>
            <td><div class="slidecontainer">
              <input type="range" min="-79" max="10" step="0.1" value="-10" class="slidernewpower" name="power" onchange="update_slider()" oninput="update_slidertxt()">
              <span id="powertext"></span>
            </div>
          </td>
        </tr>
      </table>

      <table>
        <tr>
          <td>Callsign<i>(DVB Program Name)</i></td>
          <td><input type="text" name="callsign" value="NOCALL"></td>
          <td>DVB Provider Name <br><i>(output: FwVer_ProvName)</i></td>
          <td><input type="text" name="provname" value="_yrname/project_" maxlength="15" size="16"> (max 15 chrs)</td>
        </tr>
        <tr><td>PCR/PTS</td>
          <td><div class="slidecontainer">
            <input type="range" min="100" max="2000" value="800" class="slider" name="pcrpts" oninput="update_slider_pts()">
            <span id="pcrptstext"></span>
          </div>
        </td>
        <td>PAT period</td>
        <td><div class="slidecontainer">
          <input type="range" min="100" max="1000" value="200" class="slider" name="patperiod" oninput="update_slider_pat()">
          <span id="pattext"></span>
        </div>
      </td>

    </tr>
    <tr>
      <td>Freq-Manual <i>(70 MHz - 6 GHz)</i></td>
      <td><input type="text" name="freq" value="0">
      </td>
      <td>Freq-Channel <br><i>(SR channel Uplink / Downlink)</i></td>
      <td><select name="channel" onchange="upd_freq();calc_ts()">

        <option value="2405.25-2000KS2">2000KS2 (2405.25 / 10494.75)</option>
        <option value="2406.75-2000KS3">2000KS3 (2406.75 / 10496.25)</option>

        <option value="2405.25-1500KS2">1500KS2 (2405.25 / 10494.75)</option>
        <option value="2406.75-1500KS3">1500KS3 (2406.75 / 10496.25)</option>

        <option value="2403.75-1000KS1">1000KS1 (2403.75 / 10493.25)</option>
        <option value="2405.25-1000KS2">1000KS2 (2405.25 / 10494.75)</option>
        <option value="2406.75-1000KS3">1000KS3 (2406.75 / 10496.25)</option>

        <option value="2403.25-500KS1">500KS1 (2403.25 / 10492.75)</option>
        <option value="2403.75-500KS2">500KS2 (2403.75 / 10493.25)</option>
        <option value="2404.25-500KS3">500KS3 (2404.25 / 10493.75)</option>
        <option value="2404.75-500KS4">500KS4 (2404.75 / 10494.25)</option>
        <option value="2405.25-500KS5">500KS5 (2405.25 / 10494.75)</option>
        <option value="2405.75-500KS6">500KS6 (2405.75 / 10495.25)</option>
        <option value="2406.25-500KS7">500KS7 (2406.25 / 10495.75)</option>
        <option value="2406.75-500KS8">500KS8 (2406.75 / 10496.25)</option>
        <option value="2407.25-500KS9">500KS9 (2407.25 / 10496.75)</option>
        <option value="2407.75-500KS10">500KS10 (2407.75 / 10497.25)</option>
        <option value="2408.25-500KS11">500KS11 (2408.25 / 10497.75)</option>
        <option value="2408.75-500KS12">500KS12 (2408.75 / 10498.25)</option>
        <option value="2409.25-500KS13">500KS13 (2409.25 / 10498.75)</option>
        <option value="2409.75-500KS14">500KS14 (2409.75 / 10499.25)</option>

        <option value="2403.25-333KS1">333KS1 (2403.25 / 10492.75)</option>
        <option value="2403.75-333KS2">333KS2 (2403.75 / 10493.25)</option>
        <option value="2404.25-333KS3">333KS3 (2404.25 / 10493.75)</option>
        <option value="2404.75-333KS4">333KS4 (2404.75 / 10494.25)</option>
        <option value="2405.25-333KS5">333KS5 (2405.25 / 10494.75)</option>
        <option value="2405.75-333KS6">333KS6 (2405.75 / 10495.25)</option>
        <option value="2406.25-333KS7">333KS7 (2406.25 / 10495.75)</option>
        <option value="2406.75-333KS8">333KS8 (2406.75 / 10496.25)</option>
        <option value="2407.25-333KS9">333KS9 (2407.25 / 10496.75)</option>
        <option value="2407.75-333KS10">333KS10 (2407.75 / 10497.25)</option>
        <option value="2408.25-333KS11">333KS11 (2408.25 / 10497.75)</option>
        <option value="2408.75-333KS12">333KS12 (2408.75 / 10498.25)</option>
        <option value="2409.25-333KS13">333KS13 (2409.25 / 10498.75)</option>
        <option value="2409.75-333KS14">333KS14 (2409.75 / 10499.25)</option>

        <option value="2403.25-250KS1">250KS1 (2403.25 / 10492.75)</option>
        <option value="2403.75-250KS2">250KS2 (2403.75 / 10493.25)</option>
        <option value="2404.25-250KS3">250KS3 (2404.25 / 10493.75)</option>
        <option value="2404.75-250KS4">250KS4 (2404.75 / 10494.25)</option>
        <option value="2405.25-250KS5">250KS5 (2405.25 / 10494.75)</option>
        <option value="2405.75-250KS6">250KS6 (2405.75 / 10495.25)</option>
        <option value="2406.25-250KS7">250KS7 (2406.25 / 10495.75)</option>
        <option value="2406.75-250KS8">250KS8 (2406.75 / 10496.25)</option>
        <option value="2407.25-250KS9">250KS9 (2407.25 / 10496.75)</option>
        <option value="2407.75-250KS10">250KS10 (2407.75 / 10497.25)</option>
        <option value="2408.25-250KS11">250KS11 (2408.25 / 10497.75)</option>
        <option value="2408.75-250KS12">250KS12 (2408.75 / 10498.25)</option>
        <option value="2409.25-250KS13">250KS13 (2409.25 / 10498.75)</option>
        <option value="2409.75-250KS14">250KS14 (2409.75 / 10499.25)</option>

        <option value="2403.25-125KS1">125KS1 (2403.25 / 10492.75)</option>
        <option value="2403.50-125KS2">125KS2 (2403.50 / 10493.00)</option>
        <option value="2403.75-125KS3">125KS3 (2403.75 / 10493.25)</option>
        <option value="2404.00-125KS4">125KS4 (2404.00 / 10493.50)</option>
        <option value="2404.25-125KS5">125KS5 (2404.25 / 10493.75)</option>
        <option value="2404.50-125KS6">125KS6 (2404.50 / 10494.00)</option>
        <option value="2404.75-125KS7">125KS7 (2404.75 / 10494.25)</option>
        <option value="2405.00-125KS8">125KS8 (2405.00 / 10494.50)</option>
        <option value="2405.25-125KS9">125KS9 (2405.25 / 10494.75)</option>
        <option value="2405.50-125KS10">125KS10 (2405.50 / 10495.00)</option>
        <option value="2405.75-125KS11">125KS11 (2405.75 / 10495.25)</option>
        <option value="2406.00-125KS12">125KS12 (2406.00 / 10495.50)</option>
        <option value="2406.25-125KS13">125KS13 (2406.25 / 10495.75)</option>
        <option value="2406.50-125KS14">125KS14 (2406.50 / 10496.00)</option>
        <option value="2406.75-125KS15">125KS15 (2406.75 / 10496.25)</option>
        <option value="2407.00-125KS16">125KS16 (2407.00 / 10496.50)</option>
        <option value="2407.25-125KS17">125KS17 (2407.25 / 10496.75)</option>
        <option value="2407.50-125KS18">125KS18 (2407.50 / 10497.00)</option>
        <option value="2407.75-125KS19">125KS19 (2407.75 / 10497.25)</option>
        <option value="2408.00-125KS20">125KS20 (2408.00 / 10497.50)</option>
        <option value="2408.25-125KS21">125KS21 (2408.25 / 10497.75)</option>
        <option value="2408.50-125KS22">125KS22 (2408.50 / 10498.00)</option>
        <option value="2408.75-125KS23">125KS23 (2408.75 / 10498.25)</option>
        <option value="2409.00-125KS24">125KS24 (2409.00 / 10498.50)</option>
        <option value="2409.25-125KS25">125KS25 (2409.25 / 10498.75)</option>
        <option value="2409.50-125KS26">125KS26 (2409.50 / 10499.00)</option>
        <option value="2409.75-125KS27">125KS27 (2409.75 / 10499.25)</option>

        <option value="2403.25-66KS1">66KS1 (2403.25 / 10492.75)</option>
        <option value="2403.50-66KS2">66KS2 (2403.50 / 10493.00)</option>
        <option value="2403.75-66KS3">66KS3 (2403.75 / 10493.25)</option>
        <option value="2404.00-66KS4">66KS4 (2404.00 / 10493.50)</option>
        <option value="2404.25-66KS5">66KS5 (2404.25 / 10493.75)</option>
        <option value="2404.50-66KS6">66KS6 (2404.50 / 10494.00)</option>
        <option value="2404.75-66KS7">66KS7 (2404.75 / 10494.25)</option>
        <option value="2405.00-66KS8">66KS8 (2405.00 / 10494.50)</option>
        <option value="2405.25-66KS9">66KS9 (2405.25 / 10494.75)</option>
        <option value="2405.50-66KS10">66KS10 (2405.50 / 10495.00)</option>
        <option value="2405.75-66KS11">66KS11 (2405.75 / 10495.25)</option>
        <option value="2406.00-66KS12">66KS12 (2406.00 / 10495.50)</option>
        <option value="2406.25-66KS13">66KS13 (2406.25 / 10495.75)</option>
        <option value="2406.50-66KS14">66KS14 (2406.50 / 10496.00)</option>
        <option value="2406.75-66KS15">66KS15 (2406.75 / 10496.25)</option>
        <option value="2407.00-66KS16">66KS16 (2407.00 / 10496.50)</option>
        <option value="2407.25-66KS17">66KS17 (2407.25 / 10496.75)</option>
        <option value="2407.50-66KS18">66KS18 (2407.50 / 10497.00)</option>
        <option value="2407.75-66KS19">66KS19 (2407.75 / 10497.25)</option>
        <option value="2408.00-66KS20">66KS20 (2408.00 / 10497.50)</option>
        <option value="2408.25-66KS21">66KS21 (2408.25 / 10497.75)</option>
        <option value="2408.50-66KS22">66KS22 (2408.50 / 10498.00)</option>
        <option value="2408.75-66KS23">66KS23 (2408.75 / 10498.25)</option>
        <option value="2409.00-66KS24">66KS24 (2409.00 / 10498.50)</option>
        <option value="2409.25-66KS25">66KS25 (2409.25 / 10498.75)</option>
        <option value="2409.50-66KS26">66KS26 (2409.50 / 10499.00)</option>
        <option value="2409.75-66KS27">66KS27 (2409.75 / 10499.25)</option>

        <option value="2403.25-33KS1">33KS1 (2403.25 / 10492.75)</option>
        <option value="2403.50-33KS2">33KS2 (2403.50 / 10493.00)</option>
        <option value="2403.75-33KS3">33KS3 (2403.75 / 10493.25)</option>
        <option value="2404.00-33KS4">33KS4 (2404.00 / 10493.50)</option>
        <option value="2404.25-33KS5">33KS5 (2404.25 / 10493.75)</option>
        <option value="2404.50-33KS6">33KS6 (2404.50 / 10494.00)</option>
        <option value="2404.75-33KS7">33KS7 (2404.75 / 10494.25)</option>
        <option value="2405.00-33KS8">33KS8 (2405.00 / 10494.50)</option>
        <option value="2405.25-33KS9">33KS9 (2405.25 / 10494.75)</option>
        <option value="2405.50-33KS10">33KS10 (2405.50 / 10495.00)</option>
        <option value="2405.75-33KS11">33KS11 (2405.75 / 10495.25)</option>
        <option value="2406.00-33KS12">33KS12 (2406.00 / 10495.50)</option>
        <option value="2406.25-33KS13">33KS13 (2406.25 / 10495.75)</option>
        <option value="2406.50-33KS14">33KS14 (2406.50 / 10496.00)</option>
        <option value="2406.75-33KS15">33KS15 (2406.75 / 10496.25)</option>
        <option value="2407.00-33KS16">33KS16 (2407.00 / 10496.50)</option>
        <option value="2407.25-33KS17">33KS17 (2407.25 / 10496.75)</option>
        <option value="2407.50-33KS18">33KS18 (2407.50 / 10497.00)</option>
        <option value="2407.75-33KS19">33KS19 (2407.75 / 10497.25)</option>
        <option value="2408.00-33KS20">33KS20 (2408.00 / 10497.50)</option>
        <option value="2408.25-33KS21">33KS21 (2408.25 / 10497.75)</option>
        <option value="2408.50-33KS22">33KS22 (2408.50 / 10498.00)</option>
        <option value="2408.75-33KS23">33KS23 (2408.75 / 10498.25)</option>
        <option value="2409.00-33KS24">33KS24 (2409.00 / 10498.50)</option>
        <option value="2409.25-33KS25">33KS25 (2409.25 / 10498.75)</option>
        <option value="2409.50-33KS26">33KS26 (2409.50 / 10499.00)</option>
        <option value="2409.75-33KS27">33KS27 (2409.75 / 10499.25)</option>


        <option value="Custom">Custom</option>
      </select>
    </td>

  </tr>
  <tr>
    <td>Mode</td>
    <td><select name="mode" onchange="upd_mod()" >
      <option value="DVBS2">DVBS2</option>
      <option value="DVBS">DVBS</option>
    </select></td>
    <td>Mod</td>
    <td><select name="mod" onchange="upd_fec()" >
      <option value="QPSK">QPSK</option>
      <option value="8PSK">8PSK</option>
      <option value="16APSK">16APSK</option>
      <option value="32APSK">32APSK</option>
    </select></td>
  </tr>
  <tr>
    <td>SR <i>(KSymbols)</i></td>
    <td><input type="text" name="sr" value="0" maxlength="4" size="5" onchange="calc_ts()">
      &nbsp; 
      <select name="srselect" onchange="upd_sr();calc_ts()">
        <option value="2000">2000KS</option>
        <option value="1500">1500KS</option>
        <option value="1000">1000KS</option>
        <option value="500"> 500KS</option>
        <option value="333"> 333KS</option>
        <option value="250"> 250KS</option>
        <option value="125"> 125KS</option>
        <option value="66">  66KS</option>
        <option value="33">  33KS</option>
        <option value="Custom">Custom</option>

      </select>
    </td>
    <td>FEC</td>
    <td><select name="fec" onchange="calc_ts()">
      <option value="12">1/2</option>
      <option value="23">2/3</option>
      <option value="34">3/4</option>
      <option value="56">5/6</option>
      <option value="78">7/8</option>
    </select></td>
  </tr>
  <tr id="pilots_option"  >
    <td >Pilots</td>
    <td><select name="pilots"  onchange="calc_ts()">
      <option value="Off">Off</option>
      <option value="On">On</option>
    </select></td>
    <td id="frame_option" >Frame</td>
    <td><select name="frame"   onchange="calc_ts()">
      <option value="LongFrame">LongFrame</option>
      <option value="ShortFrame">ShortFrame</option>
    </select></td>
  </tr>
  <tr id="rolloff_option">
    <td>Rolloff</td>
    <td><select name="rolloff">
      <option value="0.35">0.35</option>
      <option value="0.25">0.25</option>
      <option value="0.20">0.20</option>
    </select></td>

    <td>Transverter LO <i>(MHz)</i></td>
    <td>
      <input type="text" name="trvlo" value="0" maxlength="4" size="5" onchange="upd_freq()">
      &nbsp; 
      <select name="trvloselect" onchange="upd_trvlo();upd_freq()">
        <option value="0">  0 (No TRV/UpConv)</option>
        <option value="2256"> 2256 (IF 144)</option>
        <option value="1970"> 1970 (IF 430)</option>
        <option value="1968"> 1968 (IF 432)</option>
        <option value="1966"> 1966 (IF 434)</option>
        <option value="1965"> 1965 (IF 435)</option>
        <option value="1888"> 1888 (IF 512)</option>
        <option value="1886"> 1886 (IF 514)</option>
        <option value="1870"> 1870 (IF 530)</option>
        <option value="1570"> 1570 (IF 830)</option>
        <option value="1110"> 1110 (IF 1290)</option>
        <option value="1104"> 1104 (IF 1296)</option>
        <option value="Custom">Custom</option>
      </select></td>
    </tr>
  </table>

  <div id="advanced">
    <table>
      <tr>
        <td>TS Rate Available <i>(Kb/s)</i></td>
        <td><div id="tsrate" value=""></div></td>
        <td>Comment<br><i>(Example: CBR for reminder)</i></td>
        <td><textarea cols="20" rows="2" name="comment" maxlength="60"style="margin: 0px;width: 225px;height: 30px;overflow:hidden;resize:none;"></textarea></td>
      </tr>
      
  </table>
</div>

</div>

</div>         


<br>
<input type="submit" value="Apply Settings" id="apply_modulator"><span id="saved_modulator" class="saved"  style="display: none;"> Saved !</span>
</form>
<br><br>
<h2>H264/H265 box control (option)</h2>
<hr>
<form id="h264h265" method="post" action = "javascript:save_modulator_setup();">
<table>
  <tr> <td>IP (192.168.1.120 default)</td> <td> <input type="text" name="h265box" value="192.168.1.120"></td> </tr>
  <tr> <td>Codec</td> <td><select name="codec"> <option value= "H264">H264</option> <option value= "H265">H265</option> </select> </td> </tr>
  <tr> <td>Sound</td> <td> <select name="sound"> <option value="On">On</option> <option value="Off">Off</option> </select> </td> </tr>
  <tr> <td>Audio Input</td> <td> <select name="audioinput"> <option value="line">Line</option> <option value="HDMI">HDMI</option> </select> </td> </tr>
</table><br>
<input type="submit" value="Apply Settings"><span id="saved_h264h265" class="saved" style="display: none;"> Saved !</span>
<br><br>
<h2>Advanced (Remux)</h2>
<hr>
<tr id="remux">
  <td>Force compliant (H265box)</td>
  <td><select name="remux">
    <option selected value="1">on</option>
    <option value="0">off</option>
  </select></td>
</tr>
<input type="submit" value="Apply Settings"><span id="saved_h265compliant" class="saved"  style="display: none;"> Saved !</span>
</form>
<td><br><b>Warning : <i>Select ON if you have trouble receiving with continuous blocks</b></i></td>

<br><br>
<h2>Save for next reboot</h2>
<hr>
Warning : In order to write permanently, you need first to apply setting then Save to flash. <br>
<form method="post">
  <p>
    <button name="savefw">Save to flash</button>
  </p>
</form>

<br>

<h2>Upload a new firmware or new patch</h2>
<hr>

<?php
if ( isset( $_SESSION[ 'message' ] ) && $_SESSION[ 'message' ] ) {
  printf( '<b>%s</b>', $_SESSION[ 'message' ] );
  unset( $_SESSION[ 'message' ] );
}
?>
<form method="POST" action="upload.php" enctype="multipart/form-data">
  <div> <span>Upload a File (pluto.frm or patch.zip):</span>&nbsp;
    <input type="file" name="uploadedFile" />
  </div><br>
  <input type="submit" name="uploadBtn" value="Upload" />
</form>
<br><br>
<h2>Delete patch</h2>
<div class="xterm">
  <?php 
  $patchchk = shell_exec ( 'ls -la /mnt/jffs2 | grep -c patch.zip' ); 
  echo "<b>$patchchk</b> &nbsp; Loaded<br/>"; 
  if($patchchk)
  {
    $listzip = shell_exec ( 'unzip -l /mnt/jffs2/patch.zip' );
    $separator = "\r\n";
    $line = strtok($listzip, $separator);

    while ($line !== false) {
      echo $line;
      echo "<br>";
      $line = strtok( $separator );
    }
  }
  ?>
</div>
<hr>
This will restore to the last firmware state, removing the patches added in overlay.
<br>After erasing the files, you will have to reboot manually or with below reboot button to resume with the basic firmware. <br>
<form method="post">
  <p>
    <button name="delpatch">Delete Patch</button>
  </p>
</form>
<br>

<h2>Reboot</h2>
<hr>
Can be usefull. <br>
<form method="post">
  <p>
    <button name="reboot">Reboot the Pluto</button>
  </p>
</form>
<a class="anchor" href="#top">Back to top</a>

<script>
  function spectrum_display () {
    if ($( "#spectrum_enable" ).is(":checked")==true) {
      $("#wf").show(0);
      $("#no_wf").hide(0);
    } else { 
      $("#wf").hide(0);
      $("#no_wf").show(0);
    }
    jQuery.getScript("lib/wf.js");
  }

  $( "#spectrum_enable" ).click(function() {
    spectrum_display ();

  });

var tab='1';
var t = '#tab1C ';

  function save_modulator_setup (){     
    let n = '';
    if (tab!=1) {
      n=tab;
    } 
    $.ajax({
        url: 'modulator_save.php', // url where to submit the request
        type : "POST", // type of action POST || GET
        dataType : 'html', // data type
        processData: false,
        data : $("#modulator"+n+", #h264h265").serialize(), // post data || get data
        success : function(result) {
          $(".saved").fadeIn(250).fadeOut(1500);
        },
        error: function(xhr, resp, text) {
          console.log(xhr, resp, text);
        }
      })

  };

  function save_receiver_setup (){        
    $.ajax({
        url: 'receiver_save.php', // url where to submit the request
        type : "POST", // type of action POST || GET
        dataType : 'html', // data type
        processData: false,
        
        data : $("#receiver").serialize(), // post data || get data
        success : function(result) {
          $("#saved_receiver").fadeIn(250).fadeOut(1500);
        },
        error: function(xhr, resp, text) {
          console.log(xhr, resp, text);
        }
      })

  };


  setInterval(function() {

    request_onair();
        $.get( "pluto_temp.php", function( data ) {//Read temps of the pluto
          $( "#temps" ).html( data );
          });
      }, 1000);

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



  function hms(s) {
    var hours = Math.floor(s / 60 / 60);
    var minutes = Math.floor(s / 60) - (hours * 60);
    var seconds = s % 60;
    return hours.toString().padStart(2, '0') + ':' + minutes.toString().padStart(2, '0') + ':' + seconds.toString().padStart(2, '0');
  }

  function transmission_tooltip(){

        if (localStorage.getItem('total_duration') == null) {
          localStorage.setItem('total_duration',0);
        }
        if (localStorage.getItem('total_switchover') == null) {
          localStorage.setItem('total_switchover',0);
        }
        if (localStorage.getItem('txon_at') == null) {
          localStorage.setItem('txon_at',Date.now());
        }
        let total = parseInt(localStorage.getItem('total_duration'));
        let sw = parseInt(localStorage.getItem('total_switchover'));

        $('#txduration').tooltipster("destroy");
        $('#txduration').attr('title','Total duration : '+hms(total)+'<br>Transmission switchover : '+sw+'<br><span style="font: Arial; font-size:8px">Cumulates only if the controller page is open (foreground or background)<span><br><button id="counterreset" onclick="reset_counter()">Reset</button>').tooltipster({ delay: 100,maxWidth: 500,speed: 300,interactive: true,animation: 'grow',trigger: 'hover',position : 'bottom-left'})

  }
  function request_onair()
  {
    var status = $('#textptt').text();
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        var text = document.getElementById("textptt");
        text.style.display = "block";
        var IsPowerDown=this.responseText;
        var button = document.getElementById("ptt").innerHTML;
        if(IsPowerDown == '0')
        {
          console.log('On  air status');
          document.getElementById("ptt").innerHTML = 'Switch OFF';
          document.getElementById("textptt").innerHTML  = '<font color="#ff0000">ON AIR</font>';

          if (status=="STANDBY") {
            //start count duration
             localStorage.setItem('txon_at',Date.now() );
             if (localStorage.getItem('total_switchover')==null) {
              localStorage.setItem('total_switchover',0);
              }
              localStorage.setItem('total_switchover',parseInt(localStorage.getItem('total_switchover'))+1);     
          } else {
            
            $('#txduration').text(hms(parseInt((Date.now()-localStorage.getItem('txon_at'))/1000),10));
          }
        }
        else
        {
          console.log('Off air status');
          document.getElementById("ptt").innerHTML = 'Switch ON';
          document.getElementById("textptt").innerHTML  = '<font color="#33b3ca">STANDBY</font>';

          if (status=="ON AIR") {
            //memorise total duration
            if  (localStorage.getItem('total_duration')==null) {
              localStorage.setItem('total_duration', parseInt((Date.now()-localStorage.getItem('txon_at'))/1000,10));

            }
            else
            {
              let total = parseInt(localStorage.getItem('total_duration'))+parseInt((Date.now()-localStorage.getItem('txon_at'))/1000,10);
              localStorage.setItem('total_duration', total );
              transmission_tooltip();

            }
          }
        }
      }
    };
    xmlhttp.open("GET", "requests.php?onair", true);
    xmlhttp.send();
  }


  function request_ptt(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
          //document.getElementsByName("power")[0].value = this.responseText;
        }
      };

      var button = document.getElementById("ptt").innerHTML;

      if (button == 'Switch ON' )
      {
        console.log('PTTON');
        xmlhttp.open("GET", "requests.php?PTT=on", true);
      }
      else
      {
        console.log('PTTOFF');
        xmlhttp.open("GET", "requests.php?PTT=off", true);
      }
      xmlhttp.send();
    }


    function request_gain_change(level){
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          //document.getElementsByName("power")[0].value = this.responseText;
        }
      };
      xmlhttp.open("GET", "requests.php?gain=" + level, true);
      xmlhttp.send();
    }

function update_slidertxt()
{
 $(t+'#powertext').text($(t+'input[name ="power"]').val()+'dB')  ;
}

function update_slider()
{
  update_slidertxt();
  request_gain_change( $(t+'input[name ="power"]').val());
}

function update_slider_pts()
{
 $(t+'#pcrptstext').html($(t+'input[name ="pcrpts"]').val()+'ms')  ;

}

function update_slider_pat()
{
 $(t+'#pattext').html($(t+'input[name ="patperiod"]').val()+'ms')  ;
}

function upd_freq() {
if ($(t+'select[name ="channel"]').val()=='Custom') {
  $(t+'input[name ="freq"]').val(0);
  $(t+'input[name ="sr"]').val(0);
  $(t+'select[name ="srselect"]').val('Custom');
} 
else {
  $(t+'input[name ="freq"]').val(parseFloat($(t+'select[name ="channel"]').val().split("-")[0])-$(t+'input[name ="trvlo"]').val())

    var chan_array = $(t+'select[name ="channel"] option:selected').text().match(/[a-z]+|[^a-z]+/gi);;
    var sr=0;
    if(chan_array[1]=="KS"){
      sr=chan_array[0];
    }
    $(t+'input[name ="sr"]').val(sr);
    $(t+'select[name ="srselect"]').val(sr);
  }

}
function upd_trvlo() {
if ($(t+'select[name ="trvloselect"]').val()=='Custom') {
  $(t+'input[name ="trvlo"]').val(0);
} else {
  $(t+'input[name ="trvlo"]').val($(t+'select[name ="trvloselect"]').val());     
}
}


function upd_sr() {

  if ($(t+'select[name ="srselect"]').val()=='Custom') {
  $(t+'input[name ="sr"]').val(0);
} 
else {
  $(t+'input[name ="sr"]').val($(t+'select[name ="srselect"]').val());  
}
}


function calc_ts(){

  if($(t+'select[name ="mode"]').val()=="DVBS2"){
    var m= $(t+'select[name ="mod"]')[0].selectedIndex+2;
    var p=0;
    var s=0;
    if($(t+'select[name ="frame"]').val()=="ShortFrame"){
      p=16200;
    }else{
      p=64800;
    }
    s=(p/m)
    var po=0;
    // PL header overhead
    if( $(t+'select[name ="pilots"]').val() =="On" )
    {
            //po = (s/(90*16))-1;// 1 pilot every 16 blocks (of 90 symbols)
            po = ((s.toFixed(2)/90.0-1.0)/16.0).toFixed(0);// 1 pilot every 16 blocks (of 90 symbols)
            po = po*36;        // No pilot at the end
            a  = s/(90+po+s);
    }
    else
    {
    a = s/(90+s);// No pilots
  }

  // Modulation efficiency
  a = a*m;
  // Take into account pilot symbols
  // TBD
  // Now calculate the useable data as percentage of the frame
  b = (get_usable_data_bits().toFixed(3))/p;
  // Now calculate the efficiency by multiplying the
  // useable bits efficiency by the modulation efficiency
  m_efficiency = b*a;    
  $(t+'#tsrate').html(($(t+'input[name ="sr"]').val()*m_efficiency).toFixed(3));
  }
  else{
    $(t+'#tsrate').html(($(t+'input[name ="sr"]').val()*2*(188.0/204.0)*($(t+'select[name ="fec"]').val().substring(0, 1)/$(t+'select[name ="fec"]').val().substring(1, 2))).toFixed(3));
  }
}

function get_usable_data_bits(){
  var fec = $(t+'select[name ="fec"]').val();
  if($(t+'select[name ="frame"]').val()=="LongFrame"){
    var kbch=0;
    switch(fec)
    {
      case "14":
      kbch  = 16008;
      break;
      case "13":
      kbch  = 21408;
      break;
      case "25":
      kbch  = 25728;
      break;
      case "12":
      kbch  = 32208;
      break;
      case "35":
      kbch  = 38688;
      break;
      case "23":
      kbch  = 43040;
      break;
      case "34":
      kbch  = 48408;
      break;
      case "45":
      kbch  = 51648;
      break;
      case "56":
      kbch  = 53840;
      break;
      case "89":
      kbch  = 57472;
      break;
      case "910":
      kbch  = 58192;
      break;
    }
  }else{
    switch(fec )
    {
      case "14":
      kbch  = 3072;
      break;
      case "13":
      kbch  = 5232;
      break;
      case "25":
      kbch  = 6312;
      break;
      case "12":
      kbch  = 7032;
      break;
      case "35":
      kbch  = 9552;
      break;
      case "23":
      kbch  = 10632;
      break;
      case "34":
      kbch  = 11712;
      break;
      case "45":
      kbch  = 12432;
      break;
      case "56":
      kbch  = 13152;
      break;
      case "89":
      kbch  = 14232;
      break;
    }
  }
  return kbch;
}

function upd_mod() {
  var DVBS2_MOD = ["QPSK","8PSK","16APSK","32APSK"];

  if($(t+'select[name ="mode"]').val()=="DVBS"){
      $(t+'select[name ="mod"]').find('option').remove().end().append('<option value="QPSK">QPSK</option>');
      $(t+'#pilots_option').hide();
      $(t+'#frame_option').hide();
      $(t+'#rolloff_option').hide();
  }else{
    $(t+'select[name ="mod"]').find('option').remove();
    for (key in DVBS2_MOD) {
      $(t+'select[name ="mod"]').append($('<option value="'+DVBS2_MOD[key]+'">'+DVBS2_MOD[key]+'</option>'));
    }
    $(t+'#pilots_option').show();
    $(t+'#frame_option').show();
    $(t+'#rolloff_option').show();
  }
  upd_fec();
}

function fec_list(feclist) {
  var lastfec = $(t+'select[name ="fec"]').val()
  $(t+'select[name ="fec"]').find('option').remove();
  for (key in feclist) {
     $(t+'select[name ="fec"]').append($('<option value="'+feclist[key].replace("/", "")+'">'+feclist[key]+'</option>'));  
  }
  if ($(t+'select[name ="fec"] option[value='+lastfec+']').length >0) {
    $(t+'select[name ="fec"]').val(lastfec);
  } 
  
}

function upd_fec() {
  var sel=0;
  var DVBS = ["1/2","2/3","3/4","5/6","7/8"];
  var DVBS2_QPSK = ["1/4","1/3","2/5","1/2","3/5","2/3","3/4","4/5","5/6","8/9","9/10"];
  var DVBS2_8PSK = ["3/5","2/3","3/4","4/5","5/6","8/9","9/10"];
  var DVBS2_16APSK = ["2/3","3/4","4/5","5/6","8/9","9/10"];
  var DVBS2_32APSK = ["3/4","4/5","5/6","8/9","9/10"];

  if($(t+'select[name ="mode"]').val()=="DVBS"){
    //DVBS
    fec_list(DVBS);

  }else{
  //DVBS2
    switch ($(t+'select[name ="mod"]').val()) {
      case "QPSK" : 
        fec_list(DVBS2_QPSK);
        break;
      case "8PSK" : 
        fec_list(DVBS2_8PSK);
        break;
      case "16APSK" :
        fec_list(DVBS2_16APSK);
        break;
      case "32APSK" :
        fec_list(DVBS2_32APSK);
        break;
    }
}
calc_ts();
}
var max= 0;
function get_config_receiver() {

 $.get('settings-receiver.txt', function(data) {
  var datalines = (data.split('\n'));
  for (var i in datalines) {        
    var datal =(datalines[i].split(' '));
    var $el = $('[name="'+datal[0]+'"]');
    type = $el.attr('type');

    switch(type){
      case 'checkbox':
      $el.attr('checked', 'checked');
      break;
      case 'radio':
      $el.filter('[value="'+datal[1]+'"]').attr('checked',  'checked');
      break;
      case 'option':
        //$('select[name="minitiouner-22khz"]').find('option:contains("On")').attr("selected",true);
        $el.removeAttr("selected");
        $el.filter('[value="'+datal[1]+'"]').find('option:contains("'+datal[0]+'")').attr('selected', true);
        break;                    
        default:
        $el.val(datal[1]);
      }
    }
    spectrum_display(); 

  })
 .fail(function() {
  if (max<4) {
    $.get('copy-config-jffs2www.php', function() {});
    get_config_receiver() ;
    max++;
  }
 }) 
}


function update_tab(id) {
      
      var data = localStorage.getItem('modulator_'+id);
      if (data !== null ) {
        var datalines = (data.split('&'));
        for (var i in datalines) {        
          var datal =(decodeURIComponent(datalines[i]).split('='));
          var $el = $('#tab'+id+'C [name="'+datal[0]+'"]');
          $el.val(datal[1]);

        }
      }
      if (localStorage.getItem('tablocked_'+id)=='true') {
        $('#tab'+id+'C :input').prop("disabled", true);
      } else {
        $('#tab'+id+'C :input').prop("disabled", false);
      }

     //upd_mod();
     upd_fec();
     update_slidertxt()
     update_slider_pat();
     update_slider_pts();
}

var max_id_modulator =1;
function get_local_modulator() {
  for (var j = 0; j < localStorage.length; j++) {
     if (localStorage.key(j).substring(0,10) == 'modulator_') {

      var id =  localStorage.key(j).substring(10,localStorage.key(j).length);
      if (id!=1) {
      
        var tabId = 'tab' + id;
        if (parseInt(id,10)>parseInt(max_id_modulator,10)) {
          max_id_modulator = parseInt(id,10);
        }
        
        let tabname = localStorage.getItem('tabname_'+id);
        if (tabname==null) {
           tabname ="Mod";
        }

        $('#tabs').append('<li><a id="tab' + id + '" contenteditable="true" class="inactive">'+tabname+'<span id="delcross" contenteditable="false"> ✖️ </span></a></li>');
        $('.tabs-content').append('<div class="container" id="' + tabId + 'C" style="display: none;"></div>');
        $('div#tab1C.container #modulator').clone().appendTo('div#'+tabId+'C.container');
        $('div#tab'+id+'C.container form#modulator').attr('id','modulator'+id);

      }
     }
  }
}



function get_config_modulator(only_part) {

 $.get('settings.txt', function(data) {  
  var datalines = (data.split('\n'));
  for (var i in datalines) {        
    var datal =(datalines[i].split(' '));

     if (['h265box','codec','sound','audioinput','remux'].indexOf(datal[0]) >=0) {
        var $el = $('[name="'+datal[0]+'"]');
        $el.val(datal[1]);
    }
     if (only_part !== true) {
       if (['h265box','codec','sound','audioinput','remux'].indexOf(datal[0]) <0) {
        var $el = $('#tab1C '+'[name="'+datal[0]+'"]');
        }
       $el.val(datal[1]);
     if (datal[0] == 'mod')  {
        upd_mod();
      }
    }
  }
})
 .fail(function() {
  console.log('modulator settings read failed. It may be normal if never saved Modulator section');
   upd_mod();
}) 
 .done(function() {
   update_slider_pat();
   update_slider_pts();
   update_slider();
   calc_ts();
 }) 

}

function load() {
  get_config_receiver() ;
  if  (localStorage.getItem('modulator_1')==null) {
    get_config_modulator(false);
  }
  else {
  get_config_modulator(true);
  update_tab(1);
}
get_local_modulator();
if ((localStorage.getItem('ActivTab')!=null) && ($('#tabs #tab'+localStorage.getItem('ActivTab')).length>0)) {
  $('#tabs #tab' + localStorage.getItem('ActivTab') ).trigger( "click" ); 
}
transmission_tooltip();

$('input[type=file]').change(function(e){
  var filen = $('input[type=file]')[0].files[0].name;
  if ((filen!='patch.zip')&&(filen!='pluto.frm')){
    alert('The file "' + filen +  '" is incorrect. Only pluto.frm and patch.zip are allowed. File names must be in lower case'); }
  });
}

  function save_local_modulator() {
    if (tab!= '1') {
      localStorage.setItem('modulator_'+tab,$("#modulator"+tab).serialize());
    } else if (tab== '1') {
      localStorage.setItem('modulator_'+tab,$("#modulator").serialize());
    }
  }



  $('.tabs-content').on('change', 'form', function() { 
      save_local_modulator();
  }); 

  $('#tabs li a:not(:first)').addClass('inactive');
  $('.container').hide();
  $('.container:first').show();


function reset_counter() {
    localStorage.setItem('total_duration',0);
    localStorage.setItem('total_switchover',0);
    transmission_tooltip();
}


  $('#tabs').on ('click','li a', function(){
    $(".right-c-menu").hide(100);
    if ($('#'+ $(this).attr('id')+'C').length >0) {
       t = '#'+ $(this).attr('id')+'C ';
       tab = $(this).attr('id').substring(3);
       update_tab(tab);
       localStorage.setItem('ActivTab',tab);

    if($(this).hasClass('inactive')){ 
      $('#tabs li a').addClass('inactive');           
      $(this).removeClass('inactive');
      
      $('.container').hide();
      $(t ).fadeIn('slow');
     }
  }
})

    .on("click", "span", function () {
      if (confirm('Are you sure you want to delete this tab ?')) {
        var memotab=tab;
        $('.tabs-content '+t).remove() 
        $('#tabs #tab'+ tab).parent().remove()

         $('#tabs #tab1' ).trigger( "click" ); 
        localStorage.removeItem('modulator_'+memotab);
        localStorage.removeItem('tabname_'+memotab);
        localStorage.removeItem('tablocked_'+memotab);


     }
     });

 function add_tab (sourcetab) {
    $(".right-c-menu").hide(100);
    max_id_modulator = parseInt(max_id_modulator) +1 ;
    var id =  parseInt(max_id_modulator,10)  ;//$("#tabs").children().length; 
    var tabId = 'tab' + id;
    $('#tabs').append('<li><a id="tab' + id + '" contenteditable="true" class="inactive">Mod<span  contenteditable="false"> ✖️ </span></a></li>');
    $('.tabs-content').append('<div class="container" id="' + tabId + 'C" style="display: none;"></div>');
    //$('div#tab1C.container').clone().appendTo('div#tab2C.container');
    if (sourcetab === undefined) {
      sourcetab = '1';
    }
    if (sourcetab == '1') {
        $('div#tab'+sourcetab+'C.container #modulator').clone().appendTo('div#'+tabId+'C.container');  
        $('div#tab'+id+'C.container form#modulator').attr('id','modulator'+id);
      } else {
        $('div#tab'+sourcetab+'C.container #modulator'+sourcetab).clone().appendTo('div#'+tabId+'C.container');  
        $('div#tab'+id+'C.container form#modulator'+sourcetab).attr('id','modulator'+id);
      }
  
    //$('div#tab'+id+'C.container form#modulator').attr('id','modulator'+id);
    var selects = $('#tab'+sourcetab+'C').find("select");
    $(selects).each(function(i) {
        let selname=$(this).prop('name');
        $('div#tab'+id+'C select[name ="'+selname+'"] option[value="'+$('#tab'+sourcetab+'C select[name ="'+selname+'"]').val()+'"]').prop('selected',true);      
    });

   $('#tabs #tab' + id ).trigger( "click" ); 
 }

 function copy_data(sourcetab) {
    var find_mod1=false;
    for (var j = 0; j < localStorage.length; j++) {
     if (localStorage.key(j).substring(0,10) == 'modulator_') {

      var id =  localStorage.key(j).substring(10,localStorage.key(j).length);
      //if (id!=1) {
      
        var tabId = 'tab' + id;
        if ((id!=sourcetab) && (localStorage.getItem('tablocked_'+id)!='true')) {
          $('div#tab'+id+'C input[name ="callsign"]').val($('div#tab'+sourcetab+'C input[name ="callsign"]').val());
          $('div#tab'+id+'C input[name ="provname"]').val($('div#tab'+sourcetab+'C input[name ="provname"]').val());
          $('div#tab'+id+'C input[name ="power"]').val($('div#tab'+sourcetab+'C input[name ="power"]').val());    
          if (id==1) {
           find_mod1 =true;
           localStorage.setItem('modulator_'+id,$("#modulator").serialize());
          } else {
            localStorage.setItem('modulator_'+id,$("#modulator"+id).serialize());
          }

         }

      //}
     }
  }
  id=1;
  if ((find_mod1==false) && (tab !=1 )&& (localStorage.getItem('tablocked_'+id)!='true')) {

          $('div#tab'+id+'C input[name ="callsign"]').val($('div#tab'+sourcetab+'C input[name ="callsign"]').val());
          $('div#tab'+id+'C input[name ="provname"]').val($('div#tab'+sourcetab+'C input[name ="provname"]').val());
          $('div#tab'+id+'C input[name ="power"]').val($('div#tab'+sourcetab+'C input[name ="power"]').val());  
  }
 }


  $('#addtab').click(function (e) {
    e.preventDefault();

    add_tab();

 });

  $("body").on("keydown", "[contenteditable='true'], [name='comment']", function (e) { 
  if(e.keyCode == 13) {   
    event.preventDefault();
  } 
});

$("body").on("keyup", "[contenteditable='true']", function (e) {
  let name = $(this).text();
  let strname = name.substring(0,name.length-4);
  localStorage.setItem('tabname_'+tab,strname);
  });


$(".tabs-content").bind("contextmenu", function (event) {
    // Avoid the real one
    event.preventDefault();
    // Show contextmenu
    $(".right-c-menu").finish().toggle(100).
    // In the right position (the mouse)
    css({
        top: event.pageY + "px",
        left: event.pageX + "px"
    });
});

$(".tabs-content").bind("mousedown", function (e) {
    // If the clicked element is not the menu
    if (!$(e.target).parents(".right-c-menu").length > 0) {
        // Hide it
        $(".right-c-menu").hide(100);
    }
});


// right click menu
$(".right-c-menu li").on('click', function(){
  switch($(this).attr("data-action")) {
      
      // A case for each action. Your actions here
      case "lock": 
        $(t+':input').prop("disabled", true);
        localStorage.setItem('tablocked_'+tab,true);
      break;
      case "unlock":
        $(t+':input').prop("disabled", false);
        localStorage.removeItem('tablocked_'+tab);
      break;
      case "duplicate": 
        add_tab(tab);
      break;
      case "copydata": 
        copy_data(tab);
      break;      
  }
  // Hide after the action was triggered
  $(".right-c-menu").hide(100);
});


</script>
</body>
</html>

